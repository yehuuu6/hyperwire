<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class SendEmailOTP extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Get the mail message for verification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Generate a new verification code
        $code = $notifiable->generateVerificationCode();

        // Create the code HTML for the email
        $codeHtml = <<<HTML
<div style="text-align: center; margin: 15px 0;">
    <div style="display: inline-block; background-color: #101828; border: 1px solid #2b7fff; border-radius: 6px; padding: 16px 24px; margin: 15px 0;">
        <span style="font-family: 'Courier New', monospace; font-size: 32px; font-weight: 700; letter-spacing: 3px; color: #3498db;">{$code}</span>
    </div>
</div>
HTML;

        return (new MailMessage)
            ->subject("Verify Your Email Address")
            ->line("Welcome to the " . config('app.name') . "! Please verify your email address by entering the following code.")
            ->line(new \Illuminate\Support\HtmlString($codeHtml))
            ->line("This code will expire in 24 hours.")
            ->line(
                "If you did not create this account, please ignore this email."
            );
    }
}
