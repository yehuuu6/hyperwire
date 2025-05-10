<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

#[Layout('components.layouts.auth')]
class Login extends Component
{
    use WithRateLimiting;

    public string $email;
    public string $password;
    public bool $remember = false;

    public function logout()
    {
        Auth::logout();

        session()->invalidate();

        session()->regenerateToken();

        return redirect()->route('login')->success('You have been logged out');
    }

    public function authenticate()
    {
        try {
            $this->rateLimit(10, decaySeconds: 300);
        } catch (TooManyRequestsException $exception) {
            Toaster::error("You have made too many requests. Please try again in {$exception->minutesUntilAvailable} minutes.");
            return;
        }

        $credentials = $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $this->remember)) {
            session()->regenerate();

            return redirect(route('home'))->success('Logged in as ' . Auth::user()->name . ' ' . Auth::user()->surname . '.');
        }

        Toaster::error('Invalid credentials');
        return;
    }

    public function render()
    {
        return view('livewire.auth.login')->title('Sign In - ' . config('app.name'));
    }
}
