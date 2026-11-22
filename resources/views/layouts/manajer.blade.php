<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KEDAI KOPI SENJA') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700,800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{--
        PALET WARNA (Ice Cream)
        Fudge Brownie    #6E3427  -> sidebar, teks judul, avatar teks
        Banana Cream     #FDEBAA  -> menu aktif, logo, aksen terang
        Strawberry Cream #FFBAA5  -> ikon menu, avatar, hover, subjudul
        Pistachio        #C4BE53  -> label section, aksen kecil
    --}}
    <style>
        /* Scrollbar tipis untuk sidebar & konten */
        .scroll-senja { scrollbar-width: thin; scrollbar-color: rgba(255, 186, 165, .45) transparent; }
        .scroll-senja::-webkit-scrollbar { width: 6px; }
        .scroll-senja::-webkit-scrollbar-thumb { background: rgba(255, 186, 165, .45); border-radius: 999px; }
        .scroll-konten { scrollbar-width: thin; scrollbar-color: #C4BE53 transparent; }
        .scroll-konten::-webkit-scrollbar { width: 8px; }
        .scroll-konten::-webkit-scrollbar-thumb { background: #C4BE53; border-radius: 999px; }
    </style>
</head>

<body class="font-sans antialiased bg-[#FBF7ED] text-[#3B241E] h-screen overflow-hidden">

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

        // Gaya dasar item menu
        $menuDasar = 'group flex w-full items-center rounded-xl px-4 py-3 text-sm font-semibold transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#FFBAA5]';

        // Menu aktif: Banana Cream + teks Fudge Brownie
        $menuAktif = 'bg-[#FDEBAA] text-[#6E3427] shadow-md shadow-black/20';

        // Menu biasa: teks krem, hover lebih terang
        $menuBiasa = 'text-[#F9EDE5] hover:bg-white/10 hover:text-white';

        // Warna ikon: aktif = brownie, biasa = Strawberry Cream (hover jadi Banana Cream)
        $ikonDasar = 'mr-3 h-5 w-5 flex-shrink-0 transition-colors duration-200';
        $ikonAktif = 'text-[#6E3427]';
        $ikonBiasa = 'text-[#FFBAA5] group-hover:text-[#FDEBAA]';
    @endphp

    <div x-data="{ open: false }" class="flex h-screen w-full overflow-hidden">

        <!-- OVERLAY MOBILE -->
        <div
            x-show="open"
            x-cloak
            @click="open = false"
            class="fixed inset-0 z-30 bg-[#3B241E]/60 backdrop-blur-sm md:hidden">
        </div>

        <!-- SIDEBAR -->
        <aside
            :class="open ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-40 flex w-[272px] flex-shrink-0 flex-col bg-[#6E3427] shadow-xl shadow-[#3B241E]/20 transition-transform duration-200 md:static md:translate-x-0">

            <!-- BRAND -->
            <div class="flex flex-shrink-0 items-center gap-3 px-6 py-7">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#FDEBAA] text-[#6E3427] shadow-sm">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 9h12v5a5 5 0 0 1-5 5h-2a5 5 0 0 1-5-5V9z"/>
                        <path d="M16 10h1.5a2.5 2.5 0 0 1 0 5H16"/>
                        <path d="M8 3v2M12 3v2"/>
                    </svg>
                </div>

                <div>
                    <a href="{{ route('manajer.dashboard') }}"
                       class="text-base font-extrabold leading-tight tracking-tight text-white">
                        Kedai Kopi Senja
                    </a>
                    <p class="mt-1 text-xs font-medium text-[#FFBAA5]">
                        Panel Manajer
                    </p>
                </div>
            </div>

            <!-- NAVIGASI -->
            <nav class="scroll-senja min-h-0 flex-1 space-y-1.5 overflow-y-auto px-5 py-3">

                <p class="mb-3 px-3 text-xs font-bold uppercase tracking-widest text-[#C4BE53]">
                    Menu Utama
                </p>

                <a href="{{ route('manajer.dashboard') }}"
                   class="{{ $menuDasar }} {{ request()->routeIs('manajer.dashboard') ? $menuAktif : $menuBiasa }}">
                    <svg class="{{ $ikonDasar }} {{ request()->routeIs('manajer.dashboard') ? $ikonAktif : $ikonBiasa }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('manajer.ingredients.index') }}"
                   class="{{ $menuDasar }} {{ request()->routeIs('manajer.ingredients.*') ? $menuAktif : $menuBiasa }}">
                    <svg class="{{ $ikonDasar }} {{ request()->routeIs('manajer.ingredients.*') ? $ikonAktif : $ikonBiasa }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 8l-9-5-9 5 9 5 9-5z"/><path d="M3 8v8l9 5 9-5V8"/><path d="M12 13v8"/>
                    </svg>
                    Kelola Bahan
                </a>

                <a href="{{ route('manajer.recipes.index') }}"
                   class="{{ $menuDasar }} {{ request()->routeIs('manajer.recipes.*') ? $menuAktif : $menuBiasa }}">
                    <svg class="{{ $ikonDasar }} {{ request()->routeIs('manajer.recipes.*') ? $ikonAktif : $ikonBiasa }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 4h11a3 3 0 0 1 3 3v13H8a3 3 0 0 1-3-3V4z"/><path d="M9 9h6M9 13h6"/>
                    </svg>
                    Kelola Resep
                </a>

                <a href="{{ route('manajer.menus.index') }}"
                   class="{{ $menuDasar }} {{ request()->routeIs('manajer.menus.*') ? $menuAktif : $menuBiasa }}">
                    <svg class="{{ $ikonDasar }} {{ request()->routeIs('manajer.menus.*') ? $ikonAktif : $ikonBiasa }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 9h12v5a5 5 0 0 1-5 5h-2a5 5 0 0 1-5-5V9z"/><path d="M16 10h1.5a2.5 2.5 0 0 1 0 5H16"/>
                    </svg>
                    Kelola Menu
                </a>

                <a href="{{ route('manajer.stock-entries.index') }}"
                   class="{{ $menuDasar }} {{ request()->routeIs('manajer.stock-entries.*') ? $menuAktif : $menuBiasa }}">
                    <svg class="{{ $ikonDasar }} {{ request()->routeIs('manajer.stock-entries.*') ? $ikonAktif : $ikonBiasa }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 4v11"/><path d="M7 11l5 5 5-5"/><path d="M4 20h16"/>
                    </svg>
                    Stok Masuk
                </a>

                <a href="{{ route('manajer.reports.index') }}"
                   class="{{ $menuDasar }} {{ request()->routeIs('manajer.reports.*') ? $menuAktif : $menuBiasa }}">
                    <svg class="{{ $ikonDasar }} {{ request()->routeIs('manajer.reports.*') ? $ikonAktif : $ikonBiasa }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>
                    </svg>
                    Laporan
                </a>

                <a href="{{ route('manajer.costs.index') }}"
                   class="{{ $menuDasar }} {{ request()->routeIs('manajer.costs.*') ? $menuAktif : $menuBiasa }}">
                    <svg class="{{ $ikonDasar }} {{ request()->routeIs('manajer.costs.*') ? $ikonAktif : $ikonBiasa }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M19 5L5 19"/><circle cx="7" cy="7" r="2.5"/><circle cx="17" cy="17" r="2.5"/>
                    </svg>
                    HPP dan Margin
                </a>

                <!-- MENU TAMBAHAN -->
                <div class="mt-5 border-t border-[#FFBAA5]/25 pt-5">
                    <p class="mb-3 px-3 text-xs font-bold uppercase tracking-widest text-[#C4BE53]">
                        Menu Tambahan
                    </p>

                    <div class="space-y-1.5">
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
            <div class="flex-shrink-0 border-t border-[#FFBAA5]/25 p-5">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="group flex w-full items-center rounded-xl border border-[#FFBAA5]/50 px-4 py-3 text-left text-sm font-bold text-[#FDEBAA] transition-colors duration-200 hover:border-[#FFBAA5] hover:bg-[#FFBAA5] hover:text-[#6E3427] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#FDEBAA]">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 4H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h4"/><path d="M16 8l4 4-4 4"/><path d="M20 12H9"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- AREA KANAN -->
        <div class="flex h-screen min-w-0 flex-1 flex-col overflow-hidden">

            <!-- GARIS PALET (aksen 4 warna es krim) -->
            <div class="flex h-1.5 flex-shrink-0" aria-hidden="true">
                <span class="flex-1 bg-[#6E3427]"></span>
                <span class="flex-1 bg-[#FDEBAA]"></span>
                <span class="flex-1 bg-[#FFBAA5]"></span>
                <span class="flex-1 bg-[#C4BE53]"></span>
            </div>

            <!-- HEADER -->
            <header class="z-10 flex min-h-[96px] flex-shrink-0 items-center justify-between gap-3 border-b border-[#EFE3C2] bg-[#FFFCF5] px-5 py-4 md:px-10">

                <div class="flex min-w-0 items-center gap-3">
                    <button type="button"
                        @click="open = true"
                        class="rounded-lg border border-[#EFE3C2] bg-white p-2.5 text-[#6E3427] transition-colors hover:bg-[#FDEBAA] md:hidden"
                        aria-label="Buka menu">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                            <path d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="min-w-0">
                        <h2 class="truncate text-xl font-extrabold tracking-tight text-[#6E3427] md:text-2xl">
                            {{ $judulHalaman }}
                        </h2>

                        <p class="mt-1 text-sm text-[#806D63]">
                            {{ now()->locale('id')->translatedFormat('l, d F Y') }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-shrink-0 items-center gap-3 rounded-full border border-[#EFE3C2] bg-white py-1.5 pl-1.5 pr-4 shadow-sm">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-[#FFBAA5] text-sm font-extrabold text-[#6E3427]">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>

                    <span class="text-sm font-semibold text-[#3B241E]">
                        Manajer (Web)
                    </span>
                </div>
            </header>

            <!-- KONTEN -->
            <main class="scroll-konten min-h-0 flex-1 overflow-y-auto overflow-x-hidden bg-[#FBF7ED] p-5 md:p-10">

                @yield('content')

                <p class="mt-8 border-t border-[#EFE3C2] pt-4 pb-2 text-xs text-[#806D63]">
                    Hanya Manajer yang bisa membuka halaman ini. Kasir/Pelanggan ditolak server.
                </p>
            </main>
        </div>
    </div>
</body>
</html>
