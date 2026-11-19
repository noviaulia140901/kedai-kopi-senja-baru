<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KEDAI KOPI SENJA') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F5F5F5] text-[#292929] antialiased">

    <main class="min-h-screen flex flex-col items-center justify-center px-5 py-10">

        <div class="mb-7 text-center">
            <h1 class="text-2xl font-bold tracking-widest text-[#493225]">
                KEDAI KOPI SENJA
            </h1>
        </div>

        <section class="w-full max-w-md rounded-lg border border-[#E5E5E5] bg-white p-7 shadow-sm sm:p-9">
            {{ $slot }}
        </section>

        <p class="mt-6 text-xs text-[#888888]">
            &copy; {{ date('Y') }} KEDAI KOPI SENJA
        </p>

    </main>

</body>
</html>
