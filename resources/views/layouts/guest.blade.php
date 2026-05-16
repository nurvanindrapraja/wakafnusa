<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-surface-900 antialiased bg-surface-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-12 pb-12 px-4 sm:pt-0">
            <div class="mb-8">
                <x-application-logo />
            </div>

            <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-card border border-surface-100 overflow-hidden rounded-[2.5rem]">
                {{ $slot }}
            </div>

            <div class="mt-10">
                <a href="/" class="flex items-center gap-2 text-sm font-bold text-surface-400 hover:text-nu-green-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </body>
</html>
