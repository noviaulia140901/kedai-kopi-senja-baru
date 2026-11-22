<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kedai Kopi Senja') }} - Kasir</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700,800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .scroll-kasir {
            scrollbar-width: thin;
            scrollbar-color: rgba(239, 232, 199, .45) transparent;
        }

        .scroll-kasir::-webkit-scrollbar {
            width: 6px;
        }

        .scroll-kasir::-webkit-scrollbar-thumb {
            background: rgba(239, 232, 199, .45);
            border-radius: 999px;
        }

        .scroll-konten {
            scrollbar-width: thin;
            scrollbar-color: #A3A65A transparent;
        }

        .scroll-konten::-webkit-scrollbar {
            width: 8px;
        }

        .scroll-konten::-webkit-scrollbar-thumb {
            background: #A3A65A;
            border-radius: 999px;
        }
    </style>
</head>

<body class="font-sans antialiased bg-[#F5F1E7] text-[#30201B] h-screen overflow-hidden">

    @php
        $menuDasar = 'group flex w-full items-center rounded-xl px-4 py-3 text-sm font-semibold transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#F1DFA4]';

        $menuAktif = 'bg-[#EFE8C7] text-[#30201B] shadow-md shadow-black/10';

        $menuBiasa = 'text-[#E9DFD0] hover:bg-white/10 hover:text-white';

        $ikonDasar = 'mr-3 h-5 w-5 flex-shrink-0 transition-colors duration-200';

        $ikonAktif = 'text-[#4F5B1D]';

        $ikonBiasa = 'text-[#E5CFAE] group-hover:text-[#EFE8C7]';
    @endphp

    <div x-data="{ open: false }" class="flex h-screen w-full overflow-hidden">

        <!-- OVERLAY MOBILE -->
        <div
            x-show="open"
            x-cloak
            @click="open = false"
            class="fixed inset-0 z-30 bg-[#30201B]/60 backdrop-blur-sm md:hidden">
        </div>

        <!-- SIDEBAR -->
        <aside
            :class="open ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-40 flex w-[272px] flex-shrink-0 flex-col bg-[#2D1D18] shadow-xl shadow-black/20 transition-transform duration-200 md:static md:translate-x-0">

            <!-- BRAND -->
            <div class="flex flex-shrink-0 items-center gap-3 px-6 py-7">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#EFE8C7] text-[#30201B] shadow-sm">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 9h12v5a5 5 0 0 1-5 5h-2a5 5 0 0 1-5-5V9z"/>
                        <path d="M16 10h1.5a2.5 2.5 0 0 1 0 5H16"/>
                        <path d="M8 3v2M12 3v2"/>
                    </svg>
                </div>

                <div>
                    <a href="{{ route('kasir.dashboard') }}"
                       class="text-base font-extrabold leading-tight tracking-tight text-white">
                        Kedai Kopi Senja
                    </a>

                    <p class="mt-1 text-xs font-medium text-[#D8C8AD]">
                        Panel Kasir
                    </p>
                </div>
            </div>

            <!-- NAVIGASI -->
            <nav class="scroll-kasir min-h-0 flex-1 space-y-1.5 overflow-y-auto px-5 py-3">

                <p class="mb-3 px-3 text-xs font-bold uppercase tracking-widest text-[#A3A65A]">
                    Menu Utama
                </p>

                <!-- DASHBOARD -->
                <a
                    href="{{ route('kasir.dashboard') }}"
                    class="{{ $menuDasar }} {{ request()->routeIs('kasir.dashboard') ? $menuAktif : $menuBiasa }}"
                >
                    <svg class="{{ $ikonDasar }} {{ request()->routeIs('kasir.dashboard') ? $ikonAktif : $ikonBiasa }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="3" width="7" height="9" rx="1.5"/>
                        <rect x="14" y="3" width="7" height="5" rx="1.5"/>
                        <rect x="14" y="12" width="7" height="9" rx="1.5"/>
                        <rect x="3" y="16" width="7" height="5" rx="1.5"/>
                    </svg>
                    Dashboard
                </a>

                <!-- BUAT PESANAN -->
                <a
                    href="{{ route('kasir.menus.index') }}"
                    class="{{ $menuDasar }} {{ request()->routeIs('kasir.menus.*') ? $menuAktif : $menuBiasa }}"
                >
                    <svg class="{{ $ikonDasar }} {{ request()->routeIs('kasir.menus.*') ? $ikonAktif : $ikonBiasa }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 9h12v5a5 5 0 0 1-5 5h-2a5 5 0 0 1-5-5V9z"/>
                        <path d="M16 10h1.5a2.5 2.5 0 0 1 0 5H16"/>
                    </svg>
                    Buat Pesanan
                </a>

                <!-- DAFTAR PESANAN -->
                <a
                    href="{{ route('kasir.orders.index') }}"
                    class="{{ $menuDasar }} {{ request()->routeIs('kasir.orders.*') ? $menuAktif : $menuBiasa }}"
                >
                    <svg class="{{ $ikonDasar }} {{ request()->routeIs('kasir.orders.*') ? $ikonAktif : $ikonBiasa }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="4" y="3" width="16" height="18" rx="2"/>
                        <path d="M8 8h8M8 12h8M8 16h5"/>
                    </svg>
                    Daftar Pesanan
                </a>

                <!-- RESET PASSWORD PELANGGAN -->
                <a
                    href="{{ route('kasir.customers.reset-password') }}"
                    class="{{ $menuDasar }} {{ request()->routeIs('kasir.customers.reset-password*') ? $menuAktif : $menuBiasa }}"
                >
                    <svg class="{{ $ikonDasar }} {{ request()->routeIs('kasir.customers.reset-password*') ? $ikonAktif : $ikonBiasa }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="11" width="18" height="10" rx="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        <path d="M12 15v2"/>
                    </svg>
                    Reset Password Pelanggan
                </a>

            </nav>

            <!-- GANTI PASSWORD DAN LOGOUT -->
            <div class="flex-shrink-0 border-t border-[#E5CFAE]/20 p-5">

                <div class="space-y-2">

                    <!-- GANTI PASSWORD -->
                    <a
                        href="{{ route('profile.edit') }}"
                        class="{{ $menuDasar }} {{ request()->routeIs('profile.*') ? $menuAktif : $menuBiasa }}"
                    >
                        <svg class="{{ $ikonDasar }} {{ request()->routeIs('profile.*') ? $ikonAktif : $ikonBiasa }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="8" r="4"/>
                            <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                        </svg>
                        Ganti Password
                    </a>

                    <!-- LOGOUT -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button
                            type="submit"
                            class="group flex w-full items-center rounded-xl border border-[#E5CFAE]/40 px-4 py-3 text-left text-sm font-bold text-[#EFE8C7] transition-colors duration-200 hover:border-[#E5CFAE] hover:bg-[#E5CFAE] hover:text-[#30201B] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#EFE8C7]"
                        >
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 4H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h4"/>
                                <path d="m16 8 4 4-4 4"/>
                                <path d="M20 12H9"/>
                            </svg>
                            Logout
                        </button>
                    </form>

                </div>
            </div>
        </aside>

        <!-- AREA KANAN -->
        <div class="flex h-screen min-w-0 flex-1 flex-col overflow-hidden">

            <!-- GARIS AKSEN WARNA -->
            <div class="flex h-1.5 flex-shrink-0" aria-hidden="true">
                <span class="flex-1 bg-[#2D1D18]"></span>
                <span class="flex-1 bg-[#EFE8C7]"></span>
                <span class="flex-1 bg-[#E5CFAE]"></span>
                <span class="flex-1 bg-[#A3A65A]"></span>
            </div>

            <!-- HEADER -->
            <header class="z-10 flex min-h-[96px] flex-shrink-0 items-center justify-between gap-3 border-b border-[#E9E0CF] bg-[#FFFCF5] px-5 py-4 md:px-10">

                <div class="flex min-w-0 items-center gap-3">

                    <button
                        type="button"
                        @click="open = true"
                        class="rounded-lg border border-[#E9E0CF] bg-white p-2.5 text-[#30201B] transition-colors hover:bg-[#EFE8C7] md:hidden"
                        aria-label="Buka menu"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                            <path d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="min-w-0">
                        <h2 class="truncate text-xl font-extrabold tracking-tight text-[#30201B] md:text-2xl">
                            @yield('page-title', 'Halaman Kasir')
                        </h2>

                        <p class="mt-1 text-sm text-[#806D63]">
                            {{ now()->locale('id')->translatedFormat('l, d F Y') }}
                        </p>
                    </div>
                </div>

                <!-- PROFIL KASIR DI HEADER -->
                <div class="flex flex-shrink-0 items-center gap-3 rounded-full border border-[#E9E0CF] bg-white py-1.5 pl-1.5 pr-4 shadow-sm">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-[#E5CFAE] text-sm font-extrabold text-[#30201B]">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>

                    <span class="text-sm font-semibold text-[#30201B]">
                        Kasir
                    </span>
                </div>

            </header>

            <!-- KONTEN -->
            <main class="scroll-konten min-h-0 flex-1 overflow-y-auto overflow-x-hidden bg-[#F5F1E7] p-5 md:p-10">

                @yield('content')

                <p class="mt-8 border-t border-[#E9E0CF] pt-4 pb-2 text-xs text-[#806D63]">
                    Panel Kasir Kedai Kopi Senja.
                </p>

            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
