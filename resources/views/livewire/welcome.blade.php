<div class="min-h-svh text-white flex items-center justify-center p-10">
    <div aria-hidden="true" class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-1/4 w-1/2 h-1/2 bg-purple-600/20 rounded-full blur-3xl animate-pulse-slow">
        </div>
        <div class="absolute bottom-0 right-1/4 w-1/3 h-1/3 bg-indigo-600/30 rounded-full blur-3xl animate-pulse-slower">
        </div>
    </div>
    <div class="relative z-10 container mx-auto max-w-6xl bg-gray-950 shadow-xl rounded-lg overflow-hidden md:flex">
        <!-- Left Column -->
        <div class="w-full md:w-1/2 p-8 md:p-12 space-y-8">
            <div>
                <h2 class="text-3xl font-bold mb-6">No Flux, no Volt just <span class="text-blue-400">Perfection</span>
                </h2>
                <ul class="space-y-6">
                    <li class="flex items-start">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('images/laravel-icon.png') }}" alt="Laravel" class="h-6 w-6 mr-3 mt-1">
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Laravel 12</h3>
                            <p class="text-gray-400 text-sm">The PHP Framework For Web Artisans</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('images/livewire-icon.png') }}" alt="Livewire" class="h-6 w-6 mr-3 mt-1">
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Livewire 3</h3>
                            <p class="text-gray-400 text-sm">Powerful, dynamic interfaces</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('images/tailwind-icon.png') }}" alt="Tailwind"
                                class="h-6 w-6 mr-3 mt-1 object-contain">
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Tailwind CSS 4</h3>
                            <p class="text-gray-400 text-sm">Rapidly build modern websites</p>
                        </div>
                    </li>
                </ul>
            </div>
            @auth
                <div class="space-y-4">
                    <p class="text-lg text-gray-300 text-center">
                        Welcome to <span class="font-semibold text-blue-400">{{ config('app.name') }}</span>, <span
                            class="text-white">{{ auth()->user()->name }}
                            {{ auth()->user()->surname }}</span>!
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        @if (!auth()->user()->hasVerifiedEmail())
                            <a href="{{ route('verification.notice') }}" wire:navigate.hover
                                class="btn-primary w-full px-4 py-2 text-center">
                                Verify Email
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-secondary w-full px-4 py-2 text-center">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
            @guest
                <div class="flex space-x-4">
                    <a href="{{ route('login') }}" wire:navigate.hover class="btn-secondary w-full px-4 py-2 text-center">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" wire:navigate.hover class="btn-primary w-full px-4 py-2 text-center">
                        Register
                    </a>
                </div>
            @endguest
        </div>

        <!-- Right Column -->
        <div
            class="w-full md:w-1/2 bg-gradient-to-br from-blue-600 to-cyan-600 flex flex-col items-center justify-center p-8 md:p-12 text-center relative">
            {{-- You can replace this div with an <img> tag or a more complex background --}}
            <div class="absolute inset-0 bg-gray-800 opacity-50 mix-blend-overlay"
                style="background-image: url('/images/showcase.avif'); background-size: cover; background-position: center;">
            </div>
            <div class="relative z-10">
                <h1 class="text-6xl md:text-7xl font-bold mb-4 leading-tight">
                    {{ config('app.name') }}
                </h1>
                <p class="text-xl text-blue-200">Your Laravel Livewire Starter Kit</p>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-1/3 opacity-20" style="pointer-events: none;">
                <svg width="100%" height="100%" viewBox="0 0 400 200" preserveAspectRatio="xMidYMid slice"
                    xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(0,255,150,0.3)"
                                stroke-width="0.5" />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                    <path d="M50 150 Q150 50 250 150 T450 150" stroke="rgba(0,255,150,0.5)" stroke-width="3"
                        fill="none" />
                    <path d="M0 100 Q100 0 200 100 T400 100" stroke="rgba(0,255,150,0.5)" stroke-width="2"
                        fill="none" />
                    <path d="M20 180 Q120 80 220 180 T420 180" stroke="rgba(0,255,150,0.3)" stroke-width="4"
                        fill="none" />
                </svg>
            </div>
        </div>
    </div>
</div>
