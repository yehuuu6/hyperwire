<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

#[Layout('components.layouts.auth')]
class ForgotPassword extends Component
{
    use WithRateLimiting;

    public string $email = '';

    #[Renderless]
    public function sendResetLink()
    {
        try {
            $this->rateLimit(10, decaySeconds: 300);
        } catch (TooManyRequestsException $exception) {
            Toaster::error("You have made too many requests. Please try again in {$exception->minutesUntilAvailable} minutes.");
            return;
        }

        $messages = [
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'email.max' => 'Email address must be less than :max characters',
        ];

        try {
            $this->validate([
                'email' => 'required|email|max:255'
            ], $messages);
        } catch (ValidationException $e) {
            $message = $e->getMessage();
            Toaster::error($message);
            return;
        }

        try {
            $status = Password::sendResetLink(['email' => $this->email]);
        } catch (ValidationException $e) {
            $message = $e->getMessage();
            Toaster::error($message);
            return;
        }

        // Always display the same message
        Toaster::info('If an account exists with this email, a password reset link will be sent.');
    }

    public function render()
    {
        return view('livewire.auth.forgot-password')->title('Forgot Password - ' . config('app.name'));
    }
}
