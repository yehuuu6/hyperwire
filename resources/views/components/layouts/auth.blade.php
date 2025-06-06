<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:200,300,400,500,600,700,800" rel="stylesheet" />
        <title>{{ $title ?? config('app.name') }}</title>
        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="min-h-svh antialiased font-inter bg-gray-900 text-gray-900 flex flex-col">
        <main
            class="flex-1 relative bg-gradient-to-br from-gray-900 to-gray-800 text-gray-300 flex flex-col justify-center items-center">
            <div aria-hidden="true" class="absolute inset-0 pointer-events-none">
                <div class="absolute top-0 left-10 w-1/2 h-1/2 bg-blue-500/10 rounded-full blur-3xl animate-pulse">
                </div>
                <div
                    class="absolute bottom-0 right-10 w-1/3 h-1/3 bg-purple-600/15 rounded-full blur-3xl animate-pulse delay-1000">
                </div>
            </div>

            <div class="flex relative z-10 p-0 xl:p-2 rounded-2xl shadow bg-gray-950 w-11/12 my-4 lg:my-0 2xl:w-8/12">
                {{ $slot }}
                <div class="auth-image hidden lg:flex p-6 xl:p-10 pb-5 rounded-lg flex-1 flex-col justify-between">
                    <div class="flex flex-col gap-2 flex-1">
                        <div class="rounded-xl bg-gray-200 size-10"></div>
                        <h1 class="text-2xl font-bold text-gray-200">{{ config('app.name') }}</h1>
                        <p class="text-sm text-gray-200">
                            Creating your next Laravel project <span class="text-gray-400">has never been</span> easier.
                        </p>
                    </div>
                    <div class="border border-gray-400/40 p-1 rounded-xl backdrop-blur-sm">
                        <div class="bg-gray-950/70 rounded-xl p-4 flex items-center justify-center gap-4">
                            <div class="flex flex-col gap-2 pr-4 border-r border-gray-800">
                                <h4 class="text-gray-300 font-medium text-sm">
                                    No Bullsh*t
                                </h4>
                                <p class="text-gray-400 text-xs">
                                    No Flux, no Volt. Just pure Blade & Livewire at your service.
                                </p>
                            </div>
                            <div class="flex flex-col gap-2 pl-4">
                                <h4 class="text-gray-300 font-medium text-sm">
                                    Have Ideas?
                                </h4>
                                <p class="text-gray-400 text-xs">
                                    Submit a pull request to the <a class="text-blue-400 hover:underline"
                                        target="_blank" href="https://github.com/yehuuu6/hyperwire">GitHub
                                        repository</a> and I
                                    will review it.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <x-toaster-hub />
        @livewireScriptConfig
    </body>

</html>
