{{-- resources/views/components/public-layout.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'WAKAF NUSA - Platform wakaf uang digital Nahdlatul Ulama.')">
    <title>@yield('title', 'WAKAF NUSA') — Wakaf Nahdlatul Ulama</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon_wakaf_nusa.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-surface-50 text-surface-900">
    @include('layouts.partials.navbar')
    <main>{{ $slot }}</main>
    @include('layouts.partials.footer')
</body>
</html>
