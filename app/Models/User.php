<?php

namespace App\Models;

use App\Notifications\Auth\ResetPasswordQueued;
use App\Notifications\Auth\SendEmailOTP;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'verification_code',
        'verification_code_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'verification_code_expires_at' => 'datetime',
        ];
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new SendEmailOTP);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordQueued($token));
    }

    public function generateVerificationCode()
    {
        // Generate a 6-digit verification code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Set the verification code and expiration time (24 hours from now)
        $this->verification_code = $code;
        $this->verification_code_expires_at = now()->addHours(24);
        $this->save();

        return $code;
    }

    public function verifyCode($code)
    {
        // Check if the verification code matches and has not expired
        if (
            $this->verification_code === $code &&
            $this->verification_code_expires_at &&
            $this->verification_code_expires_at->gt(now())
        ) {

            // Mark email as verified
            $this->email_verified_at = now();
            $this->verification_code = null;
            $this->verification_code_expires_at = null;
            $this->save();

            return true;
        }

        return false;
    }

    public function hasValidVerificationCode()
    {
        return $this->verification_code &&
            $this->verification_code_expires_at &&
            $this->verification_code_expires_at->gt(now());
    }
}
