<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;

class CustomVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        $verificationUrl = $this->verificationUrl($this->user);

        return $this->subject('Verify Your Email Address')
                    ->view('smtp_templates.verify')
                    ->with([
                        'verificationUrl' => $verificationUrl,
                    ]);
    }

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute('verification.verify',
        Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
        ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]);
    }
}
