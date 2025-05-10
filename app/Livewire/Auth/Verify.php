<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Http\Request;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

#[Layout('components.layouts.auth')]
class Verify extends Component
{
    use WithRateLimiting;

    protected User $user;

    public string $code0 = '';
    public string $code1 = '';
    public string $code2 = '';
    public string $code3 = '';
    public string $code4 = '';
    public string $code5 = '';

    #[Validate(
        'required|size:6|regex:/^[0-9]+$/',
        message: [
            'required' => 'You must enter a 6-digit verification code.',
            'size' => 'The verification code must be 6 digits long.',
            'regex' => 'The verification code must contain only numbers.'
        ]
    )]
    public $verification_code = '';

    public function mount()
    {
        $this->returnHomeIfVerified();
    }

    public function verifyEmail()
    {
        $this->verification_code = $this->code0 . $this->code1 . $this->code2 . $this->code3 . $this->code4 . $this->code5;

        try {
            $this->validate();
        } catch (ValidationException $e) {
            Toaster::error($e->getMessage());
            return;
        }

        if (Auth::user()->verifyCode($this->verification_code)) {
            return redirect(route('home'))->success('Email verified successfully');
        }

        Toaster::error('Invalid verification code');
    }

    public function sendVerifyMail(Request $request)
    {
        try {
            $this->rateLimit(5, decaySeconds: 300);
        } catch (TooManyRequestsException $exception) {
            Toaster::error("You have made too many requests. Please try again in {$exception->minutesUntilAvailable} minutes.");
            return;
        }

        $request->user()->sendEmailVerificationNotification();

        Toaster::info('Verification email sent');
    }

    protected function returnHomeIfVerified()
    {
        $this->user = Auth::user();
        // If user is verified, redirect to home page
        if ($this->user->hasVerifiedEmail()) {
            return redirect(route('home'))->warning('This email address is already verified');
        }
    }

    public function render()
    {
        return view('livewire.auth.verify')->title('Verify Email - ' . config('app.name'));
    }
}
