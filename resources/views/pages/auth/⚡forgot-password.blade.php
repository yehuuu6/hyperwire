<?php

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Title;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

new
#[Layout('layouts.auth')]
#[Title('Forgot Password - Hyper Wire')]
class extends Component
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
                'email' => 'required|email|max:255',
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
};
?>

<div class="p-6 xl:p-10 rounded-lg flex-1">
    <div class="flex items-center justify-between gap-5">
        <div class="size-9 rounded-full bg-blue-700 shadow-blue-500/10">
        </div>
        <p class="text-xs text-gray-500 flex items-center gap-1">
            Remembered your password? <a wire:navigate.hover href="{{ route('login') }}"
                class="text-blue-500 hover:text-blue-400 font-medium">Sign In</a>
        </p>
    </div>
    <div class="mt-10 w-min mx-auto">
        <div
            class="bg-gray-900 shadow-xl shadow-blue-500/10 rounded-full p-4 flex items-center justify-center self-start">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-key-round text-gray-500 size-8">
                <path d="M2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l1.4-1.4a6.5 6.5 0 1 0-4-4Z" />
                <circle cx="16.5" cy="7.5" r=".5" fill="currentColor" />
            </svg>
        </div>
    </div>
    <div class="flex flex-col gap-2 items-center justify-center mt-4">
        <h1 class="text-xl font-bold">Forgot Your Password?</h1>
        <p class="text-sm text-gray-400">Enter your email to receive a password reset link.</p>
    </div>
    <form wire:submit="sendResetLink" class="mt-7 w-11/12 xl:w-3/4 2xl:w-2/3 mx-auto space-y-4">
        <hr class="border-t border-gray-800 h-1 mb-6">
        <x-form.input-text label="Email Address" name="email" placeholder="hello@example.com" required />
        <button type="submit" class="btn-primary px-4 py-2 w-full">
            <span wire:loading.remove wire:target="sendResetLink">Send Password Reset Link</span>
            <x-spinner class="size-6" wire:loading wire:target="sendResetLink" />
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
