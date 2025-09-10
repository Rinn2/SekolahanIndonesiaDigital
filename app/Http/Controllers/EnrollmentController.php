<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Enrollment;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Illuminate\Support\Facades\Http;

class EnrollmentController extends Controller
{
    public function __construct()
    {
        // Wajib login
        $this->middleware('auth');

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
    }

    public function confirm(Program $program)
    {
        // Check if user already enrolled
        $existingEnrollment = Enrollment::where('user_id', Auth::id())
                                      ->where('program_id', $program->id)
                                      ->first();

        if ($existingEnrollment) {
            return redirect()->route('enrollments.status', $existingEnrollment->id)
                           ->with('info', 'Anda sudah mendaftar untuk program ini.');
        }

        // Get available schedules
        $schedules = Schedule::where('program_id', $program->id)
                           ->where('is_active', true)
                           ->get();

        return view('enrollments.confirm', compact('program', 'schedules'));
    }

    public function store(Request $request, Program $program)
    {
        $request->validate([
            'schedule_id' => 'nullable|exists:schedules,id',
            'notes' => 'nullable|string|max:500'
        ]);

        // Check if user already enrolled
        $existingEnrollment = Enrollment::where('user_id', Auth::id())
                                      ->where('program_id', $program->id)
                                      ->first();

        if ($existingEnrollment) {
            return redirect()->route('enrollments.status', $existingEnrollment->id)
                           ->with('info', 'Anda sudah mendaftar untuk program ini.');
        }

        DB::beginTransaction();
        
        try {
            // Create enrollment - SELALU PENDING dulu
            $enrollment = Enrollment::create([
                'user_id' => Auth::id(),
                'program_id' => $program->id,
                'schedule_id' => $request->schedule_id,
                'status' => 'pending', // Selalu pending dulu
                'payment_status' => 'pending', // Selalu pending dulu
                'enrollment_date' => now(),
                'notes' => $request->notes
            ]);

            // program Gratis
            if ($program->price == 0 || $program->price == null) {
    // auto diterima pada programs gratis
    $enrollment->update([
        'status' => 'diterima',
        'payment_status' => 'paid', 
        'payment_date' => now(),
        'completion_date' => null
    ]);
    
    DB::commit();
    
    return redirect()->route('enrollments.status', $enrollment->id)
                   ->with('success', 'Pendaftaran berhasil! Program gratis telah otomatis diterima.');
}

            $paymentData = $this->createPayment($enrollment, $program);
            
            DB::commit();

            return redirect()->route('enrollments.payment', $enrollment->id)
                           ->with('payment_data', $paymentData)
                           ->with('info', 'Pendaftaran berhasil! Silakan lakukan pembayaran untuk menyelesaikan proses pendaftaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function status(Enrollment $enrollment)
    {
        // Check if user owns this enrollment
        if ($enrollment->user_id !== Auth::id()) {
            abort(403);
        }

        // Auto-update status jika payment sudah paid tapi status masih pending
        if ($enrollment->payment_status === 'paid' && $enrollment->status === 'pending') {
            $enrollment->update([
                'status' => 'diterima',
                'updated_at' => now()
            ]);
            $enrollment->refresh(); // Refresh model setelah update
        }

        return view('enrollments.status', compact('enrollment'));
    }

    public function payment(Enrollment $enrollment)
    {
        // Check if user owns this enrollment
        if ($enrollment->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if already paid and approved
        if ($enrollment->status === 'diterima' && $enrollment->payment_status === 'paid') {
            return redirect()->route('enrollments.status', $enrollment->id)
                           ->with('success', 'Pembayaran sudah berhasil dan pendaftaran telah diterima.');
        }

        // Check if program is free
        if ($enrollment->program->price == 0 || $enrollment->program->price == null) {
    // Jika belum diterima, update statusnya
    if ($enrollment->status === 'pending') {
        $enrollment->update([
            'status' => 'diterima',
            'payment_status' => 'paid',
            'payment_date' => now()
        ]);
    }
    
    return redirect()->route('enrollments.status', $enrollment->id)
                   ->with('info', 'Program ini gratis, pendaftaran sudah otomatis diterima.');
}

        try {
            // Create or get existing payment data
            $paymentData = $this->createPayment($enrollment, $enrollment->program);
            
            // Validate snap token exists
            if (!$paymentData['snap_token']) {
                throw new \Exception('Gagal mendapatkan token pembayaran');
            }
            
            return view('enrollments.payment', compact('enrollment', 'paymentData'));
            
        } catch (\Exception $e) {
            return redirect()->route('enrollments.status', $enrollment->id)
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Refresh snap token jika expired
     */
    public function refreshSnapToken(Enrollment $enrollment)
    {
        // Check if user owns this enrollment
        if ($enrollment->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            // Force generate new snap token
            $enrollment->update([
                'snap_token' => null,
                'order_id' => null
            ]);
            
            $paymentData = $this->createPayment($enrollment, $enrollment->program);
            
            return response()->json([
                'success' => true,
                'snap_token' => $paymentData['snap_token'],
                'message' => 'Token pembayaran berhasil diperbarui'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui token: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $enrollment = Enrollment::findOrFail($id);

        $status = $request->input('status'); // 'success', 'pending', 'failed'

        // Mapping status
        $mappedPaymentStatus = match ($status) {
            'success' => 'paid',
            'pending' => 'processing',
            'failed'  => 'failed',
            default   => $enrollment->payment_status, 
        };

        // Update enrollment status juga
        $updateData = [
            'payment_status' => $mappedPaymentStatus,
            'transaction_id' => $request->input('transaction_id'),
        ];

        // Jika pembayaran berhasil, ubah status enrollment menjadi diterima
        if ($mappedPaymentStatus === 'paid') {
            $updateData['status'] = 'diterima';
            $updateData['payment_date'] = now();
        }

        $enrollment->update($updateData);

        return response()->json([
            'message' => 'Status pembayaran diperbarui.', 
            'payment_status' => $mappedPaymentStatus,
            'enrollment_status' => $updateData['status'] ?? $enrollment->status
        ]);
    }

    private function createPayment(Enrollment $enrollment, Program $program)
    {
        // Cek apakah snap token sudah ada dan masih valid (kurang dari 23 jam)
        if ($enrollment->snap_token && $enrollment->updated_at && $enrollment->updated_at->diffInHours(now()) < 23) {
            return [
                'snap_token' => $enrollment->snap_token,
                'order_id' => $enrollment->order_id,
                'amount' => $program->price
            ];
        }
        
        $orderId = 'ENROLL-' . $enrollment->id . '-' . time();
        
        // Pastikan harga program valid
        $amount = $program->price;
        if ($amount <= 0) {
            throw new \Exception('Harga program tidak valid');
        }
        
        $transactionDetails = [
            'order_id' => $orderId,
            'gross_amount' => (int) $amount,
        ];

        $itemDetails = [
            [
                'id' => (string) $program->id,
                'price' => (int) $amount,
                'quantity' => 1,
                'name' => $program->title ?: 'Program Pelatihan',
                'category' => 'Program Pelatihan'
            ]
        ];

        $customerDetails = [
            'first_name' => Auth::user()->name ?: 'Customer',
            'email' => Auth::user()->email,
            'phone' => Auth::user()->phone ?? ''
        ];

        $transactionData = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
            'callbacks' => [
                'finish' => route('enrollments.payment.finish', $enrollment->id)
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($transactionData);
            
            // PENTING: Simpan snap_token dan order_id ke database
            $enrollment->update([
                'snap_token' => $snapToken,
                'order_id' => $orderId,
                'payment_order_id' => $orderId,
                'updated_at' => now() // Untuk tracking expire time
            ]);
            
            return [
                'snap_token' => $snapToken,
                'order_id' => $orderId,
                'amount' => $amount
            ];
            
        } catch (\Exception $e) {
            throw new \Exception('Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Handle Midtrans notification callback
     * Route: POST /midtrans/notification
     */
    public function handleNotification(Request $request)
    {
        try {
            // Inisialisasi Midtrans notification
            $notification = new Notification();
            
            $transaction_status = $notification->transaction_status;
            $order_id = $notification->order_id;
            $fraud_status = $notification->fraud_status ?? '';
            
            // Log notification untuk debugging
            Log::info('Midtrans Notification Received:', [
                'order_id' => $order_id,
                'transaction_status' => $transaction_status,
                'fraud_status' => $fraud_status,
                'payment_type' => $notification->payment_type ?? '',
                'gross_amount' => $notification->gross_amount ?? '',
                'signature_key' => $notification->signature_key ?? '',
            ]);
            
            // Cari enrollment berdasarkan order_id
            $enrollment = Enrollment::where('order_id', $order_id)->first();
            
            if (!$enrollment) {
                Log::error('Enrollment not found for order_id: ' . $order_id);
                return response()->json(['status' => 'error', 'message' => 'Enrollment not found'], 404);
            }
            
            // Update status berdasarkan transaction_status
            $statusMapping = $this->mapTransactionStatus($transaction_status, $fraud_status);
            
            if ($statusMapping) {
                $updateData = [
                    'status' => $statusMapping['enrollment_status'],
                    'payment_status' => $statusMapping['payment_status'],
                    'updated_at' => now()
                ];
                
                // Jika pembayaran berhasil, set payment_date dan payment_order_id
                if ($statusMapping['payment_status'] === 'paid') {
                    $updateData['payment_date'] = now();
                    $updateData['payment_order_id'] = $order_id;
                }
                
                $enrollment->update($updateData);
                
                Log::info('Enrollment status updated from notification:', [
                    'enrollment_id' => $enrollment->id,
                    'order_id' => $order_id,
                    'old_status' => $enrollment->status,
                    'new_status' => $statusMapping['enrollment_status'],
                    'old_payment_status' => $enrollment->payment_status,
                    'new_payment_status' => $statusMapping['payment_status'],
                    'transaction_status' => $transaction_status
                ]);
            }
            
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            Log::error('Midtrans notification error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error', 
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Map Midtrans transaction status to enrollment status
     * Mengubah status menjadi 'diterima' setelah pembayaran berhasil
     */
    private function mapTransactionStatus($transactionStatus, $fraudStatus = '')
    {
        switch ($transactionStatus) {
            case 'capture':
                if ($fraudStatus == 'accept') {
                    return [
                        'enrollment_status' => 'diterima', // BERUBAH: dari 'approved' ke 'diterima'
                        'payment_status' => 'paid'
                    ];
                } else {
                    return [
                        'enrollment_status' => 'pending',
                        'payment_status' => 'pending'
                    ];
                }
            case 'settlement':
                return [
                    'enrollment_status' => 'diterima', // BERUBAH: dari 'approved' ke 'diterima'
                    'payment_status' => 'paid'
                ];
            case 'pending':
                return [
                    'enrollment_status' => 'pending',
                    'payment_status' => 'pending'
                ];
            case 'deny':
            case 'cancel':
            case 'expire':
            case 'failure':
                return [
                    'enrollment_status' => 'ditolak', // BERUBAH: dari 'cancelled' ke 'ditolak'
                    'payment_status' => 'failed'
                ];
            default:
                return null; // Tidak update status
        }
    }

    public function handleWebhook(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                // Update enrollment status - langsung diterima
                $enrollment = Enrollment::where('order_id', $request->order_id)->first();
                if ($enrollment) {
                    $enrollment->update([
                        'status' => 'diterima', // langsung diterima
                        'payment_status' => 'paid',
                        'payment_date' => now()
                    ]);
                }
            }
        }
    }

    /**
     * mengatasi pembayaran selesai
     */
    public function paymentFinish(Request $request, $id)
{
    $enrollment = Enrollment::with('program')->findOrFail($id);
    if ($enrollment->user_id !== Auth::id()) {
            abort(403);
        }
        $transactionStatus = $request->query('transaction_status');
        $statusCode = $request->query('status_code');
    $orderId = $request->query('order_id');

    //  'settlement' untuk menandai pembayaran berhasil
     if ($transactionStatus) {
            if (($transactionStatus === 'settlement' || $transactionStatus === 'capture') && $statusCode == '200') {
                $enrollment->update([
                    'payment_status' => 'paid',
                    'status' => 'diterima',
                    'payment_date' => now()
                ]);
            } elseif (in_array($transactionStatus, ['pending', 'authorize'])) {
                // 'processing' adalah status custom yang bagus untuk membedakan dari 'pending' awal
                $enrollment->update(['payment_status' => 'processing']);
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire', 'failure'])) {
                $enrollment->update([
                    'payment_status' => 'failed',
                    'status' => 'ditolak'
                ]);
            }
        } else {
            // Jika tidak ada status di URL, panggil API Midtrans untuk memeriksa status terbaru.
            // Ini adalah fallback paling penting untuk mengatasi redirect "kosong".
            if ($enrollment->payment_status === 'pending' || $enrollment->payment_status === 'processing') {
                $this->checkPaymentStatus($enrollment);
            }
        }

        // Muat ulang data enrollment setelah potensi update
        $enrollment->refresh();

        return view('enrollments.payment-finish', compact('enrollment'));
    }

    /**
     * Check payment status from Midtrans API
     */
    public function checkPaymentStatus(Enrollment $enrollment) // Ubah parameter menjadi object Enrollment
    {
        // Gunakan order_id 
        if (!$enrollment->order_id) {
            Log::warning('No order_id found for enrollment: ' . $enrollment->id);
            return;
        }

        $serverKey = config('midtrans.server_key');
        $url = config('midtrans.is_production')
            ? 'https://api.midtrans.com/v2/' . $enrollment->order_id . '/status'
            : 'https://api.sandbox.midtrans.com/v2/' . $enrollment->order_id . '/status';

        try {
            $response = Http::withBasicAuth($serverKey, '')
                ->timeout(30)
                ->get($url);

            if ($response->successful()) {
                $data = $response->json();

                Log::info('Payment status check result:', [
                    'enrollment_id' => $enrollment->id,
                    'order_id' => $enrollment->order_id,
                    'transaction_status' => $data['transaction_status'] ?? 'unknown',
                    'fraud_status' => $data['fraud_status'] ?? 'unknown'
                ]);

                // Update enrollment status based on Midtrans response
                $statusMapping = $this->mapTransactionStatus(
                    $data['transaction_status'] ?? '',
                    $data['fraud_status'] ?? ''
                );

                if ($statusMapping) {
                    $updateData = [
                        'status' => $statusMapping['enrollment_status'],
                        'payment_status' => $statusMapping['payment_status'],
                        'updated_at' => now()
                    ];

                    // Jika pembayaran berhasil, set payment_date
                    if ($statusMapping['payment_status'] === 'paid' && is_null($enrollment->payment_date)) {
                        $updateData['payment_date'] = now();
                        $updateData['payment_order_id'] = $enrollment->order_id;
                    }

                    $enrollment->update($updateData);

                    Log::info('Enrollment status updated from API check:', [
                        'enrollment_id' => $enrollment->id,
                        'new_status' => $statusMapping['enrollment_status'],
                        'new_payment_status' => $statusMapping['payment_status']
                    ]);
                }
            } else {
                Log::error('Failed to check payment status:', [
                    'enrollment_id' => $enrollment->id,
                    'order_id' => $enrollment->order_id,
                    'response_code' => $response->status(),
                    'response_body' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error checking payment status: ' . $e->getMessage(), [
                'enrollment_id' => $enrollment->id,
                'order_id' => $enrollment->order_id,
            ]);
        }
    }

    /**
     * pembayaran callback
     */
    public function paymentCallback(Request $request, $enrollment)
    {
        $enrollment = Enrollment::findOrFail($enrollment);
        
        if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
            $enrollment->update([
                'status' => 'diterima', // apabila program gratis langsung diterima
                'payment_status' => 'paid',
                'payment_date' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'redirect_url' => route('enrollments.payment.finish', $enrollment)
            ]);
        }
        
        return response()->json(['success' => false]);
    }

    public function index()
    {
        $enrollments = Enrollment::where('user_id', Auth::id())
                                ->with(['program', 'schedule'])
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);

        return view('enrollments.index', compact('enrollments'));
    }

}