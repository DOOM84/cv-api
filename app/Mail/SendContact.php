<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendContact extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(Request $request)
    {
        return $this->markdown('SendMail.emailToAdmin', [
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ])->subject('Письмо от: ' . $request->name)
            ->to(env('MAIL_ADMIN'))
            ->from(env('MAIL_ADMIN'), env('APP_NAME'));
    }
}
