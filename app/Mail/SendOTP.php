<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendOTP extends Mailable implements ShouldQueue 
{
    use Queueable, SerializesModels;

    public string $fromName; 

    public function __construct(public User $user)
    {
        $this->fromName = config('app.name');
    }

    public function envelope(): Envelope
    {
        Log::info('SendOTP envelope', [
            'fromName'=> $this->fromName,
        ]);

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address(
                config('mail.from.address'),
                $this->fromName 
            ),
            subject: 'OTP Message',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.send-otp',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}