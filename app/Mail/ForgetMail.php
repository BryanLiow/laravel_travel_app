<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;


class ForgetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('bryan.zy.liow@gmail.com', 'TripTalk'),
            subject: '[TripTalk] Password Reset E-mail',
        );
    }


    public function content(): Content
    {
        return new Content(
            view: 'mail.forget', // Make sure to create this view with the appropriate content
            with: ['data' => $this->token, 'email' => $this->email],
        );
    }
}
