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
        <main class="flex-1 relative z-10">
            {{ $slot }}
        </main>
        <x-toaster-hub />
        @livewireScriptConfig
    </body>

</html>
