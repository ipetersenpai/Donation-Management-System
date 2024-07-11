<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    /**
     * Create a new message instance.
     *
     * @param string $token
     * @param string $email
     * @return void
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
     public function build()
{
    $url = url(route('password.reset', ['token' => $this->token, 'email' => $this->email], false));

    return $this->view('smtp_templates.forgot_password')
                ->with([
                    'url' => $url,
                    'email' => urldecode($this->email)
                ]);
}

}
