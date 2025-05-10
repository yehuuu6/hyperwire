<div class="p-6 xl:p-10 rounded-lg flex-1">
    <div class="flex items-center justify-between gap-5">
        <div class="size-9 rounded-full bg-blue-700 shadow-blue-500/10">
        </div>
        <p class="text-xs text-gray-500 flex items-center gap-1">
            Don't have an account? <a wire:navigate.hover href="{{ route('register') }}"
                class="text-blue-500 hover:text-blue-400 font-medium">Sign Up</a>
        </p>
    </div>
    <div class="mt-10 w-min mx-auto">
        <div
            class="bg-gray-900 shadow-xl shadow-blue-500/10 rounded-full p-4 flex items-center justify-center self-start">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-user-icon lucide-user text-gray-500 size-8">
                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
            </svg>
        </div>
    </div>
    <div class="flex flex-col gap-2 items-center justify-center mt-4">
        <h1 class="text-xl font-bold">Sign in to your account</h1>
        <p class="text-sm text-gray-400">Enter your details to sign in.</p>
    </div>
    <form wire:submit="authenticate" class="mt-7 w-11/12 xl:w-3/4 2xl:w-2/3 mx-auto space-y-4">
        <hr class="border-t border-gray-800 h-1 mb-6">
        <x-form.input-text label="Email Address" name="email" placeholder="hello@example.com" required />
        <x-form.input-text label="Password" type="password" name="password" placeholder="********" required />
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <input type="checkbox" id="remember" class="w-4 h-4 rounded-sm bg-gray-900 border border-gray-800" />
                <label for="remember" class="text-sm text-gray-400">Keep me logged in</label>
            </div>
            <a href="{{ route('forgot-password') }}" wire:navigate.hover
                class="text-sm text-blue-400 hover:underline">Forgot password?</a>
        </div>
        <button type="submit" class="btn-primary px-4 py-2 w-full">
            <span wire:loading.remove wire:target="authenticate">Sign In</span>
            <x-spinner class="size-6" wire:loading wire:target="authenticate" />
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
