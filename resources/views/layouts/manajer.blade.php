<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KEDAI KOPI SENJA') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-white text-black h-screen overflow-hidden">

    @php
        $judulHalaman = 'Dashboard Manajer';

        if (request()->routeIs('manajer.ingredients.*')) $judulHalaman = 'Kelola Bahan';
        elseif (request()->routeIs('manajer.recipes.*')) $judulHalaman = 'Kelola Resep';
        elseif (request()->routeIs('manajer.menus.*')) $judulHalaman = 'Kelola Menu';
        elseif (request()->routeIs('manajer.stock-entries.*')) $judulHalaman = 'Stok Masuk';
        elseif (request()->routeIs('manajer.categories.*')) $judulHalaman = 'Kategori';
        elseif (request()->routeIs('manajer.supplier-prices.*')) $judulHalaman = 'Harga Supplier';
        elseif (request()->routeIs('manajer.reports.*')) $judulHalaman = 'Laporan';
        elseif (request()->routeIs('manajer.costs.*')) $judulHalaman = 'HPP dan Margin';
        elseif (request()->routeIs('profile.*')) $judulHalaman = 'Ganti Password';

        $menuDasar = 'block w-full border-2 border-black px-4 py-3 text-sm font-semibold text-left transition-colors';
        $menuAktif = 'bg-black text-white';
        $menuBiasa = 'bg-white text-black hover:bg-gray-200';
    @endphp

    <div x-data="{ open: false }" class="flex h-screen w-full overflow-hidden">

        <div x-show="open" x-cloak @click="open = false"
             class="fixed inset-0 z-30 bg-black/40 md:hidden"></div>

        <!-- SIDEBAR -->
        <aside
            :class="open ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-40 flex w-[220px] flex-shrink-0 flex-col border-r-2 border-black bg-[#F3F3F3] transition-transform duration-200 md:static md:translate-x-0">

            <!-- BRAND -->
            <div class="flex-shrink-0 border-b-2 border-black px-5 py-6">
                <a href="{{ route('manajer.dashboard') }}" class="block">
                    <h1 class="text-base font-bold leading-tight tracking-wide">
                        KEDAI KOPI SENJA
                    </h1>
                    <p class="mt-1 text-xs text-gray-600">Panel Manajer</p>
                </a>
            </div>

            <!-- NAVIGASI -->
            <nav class="min-h-0 flex-1 space-y-2 overflow-y-auto px-3 py-4">

                <!-- DASHBOARD -->
                <a href="{{ route('manajer.dashboard') }}"
                   class="{{ $menuDasar }} {{ request()->routeIs('manajer.dashboard') ? $menuAktif : $menuBiasa }}">
                    Dashboard
                </a>

                <a href="{{ route('manajer.ingredients.index') }}"
                   class="{{ $menuDasar }} {{ request()->routeIs('manajer.ingredients.*') ? $menuAktif : $menuBiasa }}">
                    Kelola Bahan
                </a>

                <a href="{{ route('manajer.recipes.index') }}"
                   class="{{ $menuDasar }} {{ request()->routeIs('manajer.recipes.*') ? $menuAktif : $menuBiasa }}">
                    Kelola Resep
                </a>

                <a href="{{ route('manajer.menus.index') }}"
                   class="{{ $menuDasar }} {{ request()->routeIs('manajer.menus.*') ? $menuAktif : $menuBiasa }}">
                    Kelola Menu
                </a>

                <a href="{{ route('manajer.stock-entries.index') }}"
                   class="{{ $menuDasar }} {{ request()->routeIs('manajer.stock-entries.*') ? $menuAktif : $menuBiasa }}">
                    Stok Masuk
                </a>

                <a href="{{ route('manajer.reports.index') }}"
                   class="{{ $menuDasar }} {{ request()->routeIs('manajer.reports.*') ? $menuAktif : $menuBiasa }}">
                    Laporan
                </a>

                <a href="{{ route('manajer.costs.index') }}"
                   class="{{ $menuDasar }} {{ request()->routeIs('manajer.costs.*') ? $menuAktif : $menuBiasa }}">
                    HPP dan Margin
                </a>

                <!-- MENU TAMBAHAN -->
                <div class="border-t-2 border-gray-400 pt-4 mt-4">
                    <p class="mb-2 px-1 text-[11px] font-bold uppercase tracking-wider text-gray-600">
                        Menu Tambahan
                    </p>

                    <div class="space-y-2">
                        <a href="{{ route('manajer.categories.index') }}"
                           class="{{ $menuDasar }} {{ request()->routeIs('manajer.categories.*') ? $menuAktif : $menuBiasa }}">
                            Kategori
                        </a>

                        <a href="{{ route('manajer.supplier-prices.index') }}"
                           class="{{ $menuDasar }} {{ request()->routeIs('manajer.supplier-prices.*') ? $menuAktif : $menuBiasa }}">
                            Harga Supplier
                        </a>

                        <a href="{{ route('profile.edit') }}"
                           class="{{ $menuDasar }} {{ request()->routeIs('profile.*') ? $menuAktif : $menuBiasa }}">
                            Ganti Password
                        </a>
                    </div>
                </div>
            </nav>

            <!-- LOGOUT -->
            <div class="flex-shrink-0 border-t-2 border-black p-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full border-2 border-black bg-white px-4 py-3 text-left text-sm font-bold transition-colors hover:bg-gray-200">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- AREA KANAN -->
        <div class="flex h-screen min-w-0 flex-1 flex-col overflow-hidden">

            <!-- HEADER -->
            <header class="z-10 flex h-16 flex-shrink-0 items-center justify-between gap-3 border-b-2 border-black bg-white px-4 md:px-6">

                <div class="flex min-w-0 items-center gap-3">
                    <button type="button" @click="open = true"
                        class="border-2 border-black px-3 py-2 text-sm font-bold md:hidden"
                        aria-label="Buka menu">
                        Menu
                    </button>

                    <h2 class="truncate text-lg font-bold md:text-xl">
                        {{ $judulHalaman }}
                    </h2>
                </div>

                <div class="flex-shrink-0 border-2 border-black px-4 py-2 text-center text-sm font-semibold">
                    Manajer (Web)
                </div>
            </header>

            <!-- KONTEN -->
            <main class="min-h-0 flex-1 overflow-y-auto overflow-x-hidden bg-white p-4 md:p-6">
                @yield('content')

                <p class="mt-6 border-t border-gray-300 pt-4 pb-2 text-xs text-gray-500">
                    Hanya Manajer yang bisa membuka halaman ini. Kasir/Pelanggan ditolak server.
                </p>
            </main>
        </div>
    </div>
</body>
</html>
