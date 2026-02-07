<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactAdminMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $data;

    /**
     * Create a new message instance.
     */
     public function __construct(array $data)
    {
        $this->data = $data;
    }

     public function build()
    {
       return $this
        ->subject('رسالة تواصل جديدة من الموقع')
        ->view('emails.contact-admin')
        ->with([
            'data' => $this->data,
        ]);
    }

}
