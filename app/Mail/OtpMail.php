<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Kode OTP yang akan dikirim.
     */
    public string $otp;

    /**
     * Nama penerima untuk sapaan.
     */
    public string $recipientName;

    /**
     * Create a new message instance.
     */
    public function __construct(string $otp, string $recipientName)
    {
        $this->otp           = $otp;
        $this->recipientName = $recipientName;
    }

    /**
     * Get the message envelope.
     * Subject dikonfigurasi di sini, FROM address diambil otomatis dari config/mail.php
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode OTP Atur Ulang Kata Sandi - VizzioDocs',
        );
    }

    /**
     * Get the message content definition.
     * Menggunakan view Blade untuk body email.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
            with: [
                'otp'           => $this->otp,
                'recipientName' => $this->recipientName,
            ],
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
