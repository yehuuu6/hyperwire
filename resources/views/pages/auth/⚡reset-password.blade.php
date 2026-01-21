<?php

use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

new
#[Layout('layouts.auth')]
#[Title('Reset Password - Hyper Wire')]
class extends Component
{
    use WithRateLimiting;

    public string $token = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function resetPassword()
    {
        try {
            $this->rateLimit(10, decaySeconds: 300);
        } catch (TooManyRequestsException $exception) {
            Toaster::error("You have made too many requests. Please try again in {$exception->minutesUntilAvailable} minutes.");

            return;
        }

        try {
            $this->validate([
                'email' => 'required|email',
                'password' => 'required|min:8|confirmed',
                'token' => 'required',
            ]);
        } catch (ValidationException $e) {
            Toaster::error($e->validator->errors()->first());

            return;
        }

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect(route('login'))->success('Password reset successfully. You can now login with your new password.');
        } else {
            Toaster::error('Password reset failed! Try again later.');
        }
    }
};
?>

<div class="p-6 xl:p-10 rounded-lg flex-1">
    <div class="flex items-center justify-between gap-5">
        <div class="size-9 rounded-full bg-blue-700 shadow-blue-500/10">
        </div>
        <p class="text-xs text-gray-500 flex items-center gap-1">
            Remembered your password? <a wire:navigate.hover href="{{ route('login') }}"
                class="text-blue-500 hover:text-blue-400 font-semibold">Sign In</a>
        </p>
    </div>
    <div class="mt-10 w-min mx-auto">
        <div
            class="bg-gray-900 shadow-xl shadow-blue-500/10 rounded-full p-4 flex items-center justify-center self-start">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-lock-keyhole text-gray-500 size-8">
                <circle cx="12" cy="16" r="1" />
                <rect x="3" y="10" width="18" height="12" rx="2" />
                <path d="M7 10V7a5 5 0 0 1 10 0v3" />
            </svg>
        </div>
    </div>
    <div class="flex flex-col gap-2 items-center justify-center mt-4">
        <h1 class="text-xl font-bold">Reset Your Password</h1>
        <p class="text-sm text-gray-400">Enter your email and new password to reset your account password.</p>
    </div>
    <form wire:submit="resetPassword" class="mt-7 w-11/12 xl:w-3/4 2xl:w-2/3 mx-auto space-y-4">
        <hr class="border-t border-gray-800 h-1 mb-6">
        <x-form.input-text label="Email Address" name="email" type="email" placeholder="hello@example.com"
            required />
        <x-form.input-text label="New Password" name="password" type="password" placeholder="********" required />
        <x-form.input-text label="Confirm New Password" name="password_confirmation" type="password"
            placeholder="********" required />
        <button type="submit" class="btn-primary px-4 py-2 w-full">
            <span wire:loading.remove wire:target="resetPassword">
                Reset Password
            </span>
            <x-spinner class="size-6" wire:loading wire:target="resetPassword" />
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
