<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Mengatur judul halaman aplikasi -->
    <title>{{ config('app.name', 'KEDAI KOPI SENJA') }}</title>

    <!-- Memuat CSS dan JavaScript aplikasi -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<!-- Background cream dan teks utama cokelat tua -->
<body class="min-h-screen bg-senja-cream text-senja-brown antialiased">

    <main class="min-h-screen flex flex-col items-center justify-center px-5 py-10">

        <!-- Judul nama kedai menggunakan warna cokelat tua -->
        <div class="mb-7 text-center">
            <h1 class="text-2xl font-bold tracking-widest text-senja-brown">
                KEDAI KOPI SENJA
            </h1>
        </div>

        <!-- Kartu login tetap putih dengan garis tepi olive -->
        <section class="w-full max-w-md rounded-lg border border-senja-olive/30 bg-white p-7 shadow-sm sm:p-9">
            {{ $slot }}
        </section>

        <!-- Keterangan hak cipta menggunakan warna cokelat -->
        <p class="mt-6 text-xs text-senja-brown/70">
            &copy; {{ date('Y') }} KEDAI KOPI SENJA
        </p>

    </main>

</body>
</html>
