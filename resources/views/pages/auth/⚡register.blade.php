<?php

use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

new
#[Layout('layouts.auth')]
#[Title('Register - Hyper Wire')]
class extends Component
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

        return redirect(route('verification.notice'))->success('Welcome to the '.config('app.name').'!');
    }
};
?>

<div class="p-6 xl:p-10 rounded-lg flex-1">
    <div class="flex items-center justify-between gap-5">
        <div class="size-9 rounded-full bg-blue-700 shadow-blue-500/10">
        </div>
        <p class="text-xs text-gray-400 flex items-center gap-1">
            Already have an account? <a wire:navigate.hover href="{{ route('login') }}"
                class="text-blue-500 hover:text-blue-400 font-semibold">Login</a>
        </p>
    </div>
    <div class="flex flex-col gap-2 items-center justify-center mt-10">
        <h1 class="text-xl font-bold">Create your account</h1>
        <p class="text-sm text-gray-400">Fill in your details to get started.</p>
    </div>
    <form wire:submit="register" class="mt-7 w-11/12 xl:w-3/4 2xl:w-2/3 mx-auto space-y-4">
        <hr class="border-t border-gray-800 h-1 mb-6">
        <div class="flex items-center gap-3">
            <x-form.input-text label="Name" name="name" placeholder="John" required />
            <x-form.input-text label="Surname" name="surname" placeholder="Doe" required />
        </div>
        <x-form.input-text label="Email Address" name="email" placeholder="hello@example.com" required />
        <x-form.input-text label="Password" type="password" name="password" placeholder="********" required />
        <x-form.input-text label="Confirm Password" type="password" name="password_confirmation" required
            placeholder="********" />
        <x-form.input-checkbox name="terms">
            <x-slot name="label">
                I agree to the
                <a href="#" class="text-blue-400 hover:text-blue-300">Terms of Service</a> and
                <a href="#" class="text-blue-400 hover:text-blue-300">Privacy Policy</a>.
            </x-slot>
        </x-form.input-checkbox>
        <button type="submit" class="btn-primary px-4 py-2 w-full">
            <span wire:loading.remove wire:target="register">
                Sign Up
            </span>
            <x-spinner class="size-6" wire:loading wire:target="register" />
        </button>
    </form>
    <div class="flex items-center justify-between mt-10">
        <span class="text-xs text-gray-500">
            @ {{ date('Y') }} {{ config('app.name') }}
        </span>
        <a href="/" wire:navigate.hover
            class="inline-flex items-center text-xs text-gray-500 hover:text-blue-400 transition duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-arrow-left-icon lucide-arrow-left size-5 mr-0.5">
                <path d="m12 19-7-7 7-7" />
                <path d="M19 12H5" />
            </svg> Go back to home
        </a>
    </div>
</div>
