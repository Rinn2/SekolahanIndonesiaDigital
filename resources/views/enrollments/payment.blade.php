@extends('layouts.app')

@section('title', 'Pembayaran - ' . $enrollment->program->title)
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center mb-4">
                <div class="bg-green-100 p-2 rounded-full mr-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Pembayaran</h1>
            </div>
            <p class="text-gray-600">Selesaikan pembayaran untuk menyelesaikan pendaftaran program.</p>
        </div>

        <!-- Payment Status Alert -->
        @if($enrollment->payment_status === 'pending')
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-yellow-800 font-medium">Pembayaran belum selesai. Silakan lakukan pembayaran untuk melanjutkan.</span>
                </div>
            </div>
        @elseif($enrollment->payment_status === 'processing')
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.414-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-blue-800 font-medium">Pembayaran sedang diproses. Silakan tunggu konfirmasi.</span>
                </div>
            </div>
        @elseif($enrollment->payment_status === 'failed')
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-red-800 font-medium">Pembayaran gagal. Silakan coba lagi.</span>
                </div>
            </div>
        @elseif($enrollment->payment_status === 'paid')
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-green-800 font-medium">Pembayaran berhasil! Pendaftaran Anda telah dikonfirmasi.</span>
                </div>
            </div>
        @endif

        <!-- Order Summary -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Ringkasan Pesanan</h2>
            
            <div class="space-y-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $enrollment->program->title }}</h3>
                        <p class="text-sm text-gray-600">{{ $enrollment->program->description }}</p>
                        @if($enrollment->schedule)
                            <p class="text-sm text-gray-600 mt-1">
                                <span class="font-medium">Jadwal:</span> {{ $enrollment->schedule->name }}
                            </p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold text-gray-800">
                            Rp {{ number_format($enrollment->program->price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
                
                <div class="border-t pt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold text-gray-800">Total</span>
                        <span class="text-xl font-bold text-green-600">
                            Rp {{ number_format($enrollment->program->price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Informasi Pembayaran</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Order ID</label>
                    <p class="font-mono text-gray-800">{{ $enrollment->order_id }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Jumlah</label>
                    <p class="font-semibold text-gray-800">Rp {{ number_format($enrollment->program->price, 0, ',', '.') }}</p>
                </div>
            </div>
            
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="font-semibold text-blue-800 mb-2">Metode Pembayaran</h3>
                <p class="text-sm text-blue-700">Kami menerima berbagai metode pembayaran:</p>
                <ul class="text-sm text-blue-700 mt-2 space-y-1">
                    <li>• Transfer Bank (BCA, BNI, BRI, Mandiri)</li>
                    <li>• E-Wallet (GoPay, OVO, DANA, ShopeePay)</li>
                    <li>• Virtual Account</li>
                    <li>• Kartu Kredit/Debit</li>
                </ul>
            </div>
        </div>

        <!-- Payment Button -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                @if($enrollment->payment_status === 'paid')
                    <a href="{{ route('enrollments.status', $enrollment->id) }}" 
                       class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Lihat Status Pendaftaran
                    </a>
                @elseif($enrollment->payment_status === 'processing')
                    <div class="text-center">
                        <div class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-md">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Pembayaran Sedang Diproses
                        </div>
                        <p class="text-sm text-gray-600 mt-4">Mohon tunggu hingga proses pembayaran selesai.</p>
                    </div>
                @else
                    @if(!empty($paymentData) && !empty($paymentData['snap_token']))
                        <button id="pay-button" 
                                class="w-full bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700 transition-colors font-medium text-lg disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="pay-button-text">Bayar Sekarang</span>
                            <svg id="pay-button-loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                        
                        <div class="mt-4 text-sm text-gray-600">
                            <p>Dengan mengklik "Bayar Sekarang", Anda akan diarahkan ke halaman pembayaran yang aman.</p>
                            <p class="mt-2">Pembayaran dilindungi oleh sistem keamanan terdepan.</p>
                        </div>
                    @else
                        <div class="text-center">
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                <p class="text-red-800">Tidak dapat membuat token pembayaran. Silakan refresh halaman atau hubungi support.</p>
                            </div>
                            <button onclick="window.location.reload()" 
                                    class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors font-medium">
                                Refresh Halaman
                            </button>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Back to Status Button -->
        <div class="mt-6 text-center">
            <a href="{{ route('enrollments.status', $enrollment->id) }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Status Pendaftaran
            </a>
        </div>
    </div>
</div>
<script>
    window.snapToken = {!! json_encode($paymentData['snap_token'] ?? '') !!};
</script>
@if($enrollment->payment_status !== 'paid' && $enrollment->payment_status !== 'processing' && !empty($paymentData) && !empty($paymentData['snap_token']))
<!-- Midtrans Snap Script -->
<script type="text/javascript"
        src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    const payButton = document.getElementById('pay-button');
    const payButtonText = document.getElementById('pay-button-text');
    const payButtonLoading = document.getElementById('pay-button-loading');
    
   
    if (typeof snap === 'undefined') {
        console.error('Midtrans Snap tidak berhasil dimuat');
        showAlert('error', 'Sistem pembayaran tidak dapat dimuat. Silakan refresh halaman.');
        return;
    }
    
   if (payButton) {
        payButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            const snapToken = window.snapToken;

            if (!snapToken) {
                showAlert('error', 'Token pembayaran tidak tersedia. Silakan refresh halaman.');
                return;
            }
            
            setButtonLoading(true);
            
            try {
                snap.pay(snapToken, {
                    onSuccess: function(result) {
                        console.log('Payment success:', result);
                        showAlert('success', 'Pembayaran berhasil! Mengalihkan halaman...');
                        // PERBAIKAN: Langsung arahkan ke halaman finish setelah sukses.
                        // Server akan memverifikasi status di backend saat halaman dimuat.
                        window.location.href = "{{ route('enrollments.payment.finish', $enrollment->id) }}";
                    },
                    onPending: function(result) {
                        console.log('Payment pending:', result);
                        showAlert('info', 'Pembayaran sedang diproses. Anda akan dialihkan.');
                        // PERBAIKAN: Arahkan juga ke halaman finish untuk status pending.
                        // Halaman finish akan menampilkan status "sedang diproses".
                        window.location.href = "{{ route('enrollments.payment.finish', $enrollment->id) }}";
                    },
                    onError: function(result) {
                        console.error('Payment error:', result);
                        showAlert('error', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
                        setButtonLoading(false);
                    },
                    onClose: function() {
                        console.log('Payment popup closed');
                        setButtonLoading(false);
                    }
                });
            } catch (error) {
                console.error('Error opening payment popup:', error);
                showAlert('error', 'Tidak dapat membuka popup pembayaran. Silakan coba lagi.');
                setButtonLoading(false);
            }
        });
    }
    
    function setButtonLoading(isLoading) {
        if (payButton && payButtonText && payButtonLoading) {
            payButton.disabled = isLoading;
            payButtonText.textContent = isLoading ? 'Memproses...' : 'Bayar Sekarang';
            if (isLoading) {
                payButtonLoading.classList.remove('hidden');
                payButtonLoading.classList.add('inline');
            } else {
                payButtonLoading.classList.add('hidden');
                payButtonLoading.classList.remove('inline');
            }
        }
    }
    
    function updatePaymentStatus(transactionId, status) {
        // Update status pembayaran ke server
        fetch("{{ route('enrollments.payment.update', $enrollment->id) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                transaction_id: transactionId,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Payment status updated:', data);
        })
        .catch(error => {
            console.error('Error updating payment status:', error);
        });
    }
    
    function showAlert(type, message) {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.payment-alert');
        existingAlerts.forEach(alert => alert.remove());
        
        // Create alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = `payment-alert fixed top-4 right-4 z-50 max-w-md p-4 rounded-lg shadow-lg transition-all duration-300 ${
            type === 'error' ? 'bg-red-50 border border-red-200' : 
            type === 'info' ? 'bg-blue-50 border border-blue-200' : 
            'bg-green-50 border border-green-200'
        }`;
        
        alertDiv.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2 ${
                    type === 'error' ? 'text-red-600' : 
                    type === 'info' ? 'text-blue-600' : 
                    'text-green-600'
                }" fill="currentColor" viewBox="0 0 20 20">
                    ${type === 'error' ? 
                        '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>' :
                        '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>'
                    }
                </svg>
                <span class="font-medium ${
                    type === 'error' ? 'text-red-800' : 
                    type === 'info' ? 'text-blue-800' : 
                    'text-green-800'
                }">${message}</span>
                <button class="ml-auto" onclick="this.parentElement.parentElement.remove()">
                    <svg class="w-4 h-4 ${
                        type === 'error' ? 'text-red-600' : 
                        type === 'info' ? 'text-blue-600' : 
                        'text-green-600'
                    }" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            if (alertDiv.parentElement) {
                alertDiv.remove();
            }
        }, 5000);
    }
});
</script>
@endif
@endsection