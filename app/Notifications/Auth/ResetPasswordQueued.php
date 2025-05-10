<?php

namespace App\Notifications\Auth;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordQueued extends ResetPassword implements ShouldQueue
{
    use Queueable;

    /**
     * Get the reset password notification mail message for the given URL.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject("Reset Password Request")
            ->line("We received a request to reset your password. Please click the button below to reset your password.")
            ->action("Reset Password", $url)
            ->line(
                'This link will expire in 60 minutes.'
            )
            ->line("If you did not request a password reset, please ignore this email.");
    }
}
