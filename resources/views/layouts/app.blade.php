<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Mengatur font utama aplikasi -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Memuat CSS dan JavaScript Laravel melalui Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <!-- Menggunakan warna cokelat tua untuk teks utama -->
    <body class="font-sans antialiased text-senja-brown">

        <!-- Latar belakang utama menggunakan warna cream -->
        <div class="min-h-screen bg-senja-cream">

            <!-- Menampilkan navigasi utama -->
            @include('layouts.navigation')

            <!-- Bagian judul halaman dengan latar cream -->
            @isset($header)
                <header class="bg-senja-cream shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Menampilkan isi halaman -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
