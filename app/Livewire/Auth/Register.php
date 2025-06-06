<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Masmerise\Toaster\Toaster;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

#[Layout('components.layouts.auth')]
class Register extends Component
{
    use WithRateLimiting;

    public string $name;
    public string $surname;
    public string $email;
    public string $password;
    public string $password_confirmation;
    public bool $terms;

    public function register()
    {
        try {
            $this->rateLimit(10, decaySeconds: 300);
        } catch (TooManyRequestsException $exception) {
            Toaster::error("You have made too many requests. Please try again in {$exception->minutesUntilAvailable} minutes.");
            return;
        }

        try {
            $this->validate([
                'name' => ['required', 'string', 'max:255'],
                'surname' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'terms' => ['required', 'accepted'],
            ]);
        } catch (ValidationException $exception) {
            Toaster::error($exception->getMessage());
            return;
        }

        $attributes = [
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'password' => $this->password,
        ];

        $user = User::create($attributes);

        Auth::login($user);

        event(new Registered($user));

        return redirect(route('verification.notice'))->success('Welcome to the ' . config('app.name') . '!');
    }

    public function render()
    {
        return view('livewire.auth.register')->title('Sign Up - ' . config('app.name'));
    }
}
