<?php

namespace App\Mail;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $enrollment;

    /**
     * Create a new message instance.
     */
    public function __construct(Enrollment $enrollment)
    {
        $this->enrollment = $enrollment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bukti Pendaftaran - ' . $this->enrollment->program->title,
            from: config('mail.from.address', 'noreply@sekolahid.com'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.payment-success',
            with: [
                'enrollment' => $this->enrollment,
                'user' => $this->enrollment->user,
                'program' => $this->enrollment->program,
                'schedule' => $this->enrollment->schedule,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}