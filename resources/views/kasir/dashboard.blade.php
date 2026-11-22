
@extends('layouts.kasir')

@section('page-title', 'Dashboard Kasir')

@section('content')
<style>
    /* BACKGROUND HALAMAN */
    body:has(.cashier-dashboard-page) main {
        background-color: #F1ECD8;
    }

    .cashier-dashboard-page {
        color: #4E3936;
    }

    /* BANNER SAMBUTAN */
    .cashier-dashboard-page .dashboard-welcome {
        background-color: #465A3D;
        color: #F1ECD8;
        border-radius: 30px;
        padding: 32px 28px;
    }

    .cashier-dashboard-page .welcome-label {
        color: #F1ECD8;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .1em;
    }

    .cashier-dashboard-page .welcome-title {
        margin-top: 12px;
        color: #F1ECD8;
        font-size: 24px;
        font-weight: 700;
        line-height: 1.3;
    }

    .cashier-dashboard-page .welcome-description {
        margin-top: 4px;
        color: #F1ECD8;
        font-size: 14px;
        line-height: 1.5;
        opacity: .9;
    }

    /* VARIASI WARNA KARTU */
    .cashier-dashboard-page .dashboard-green {
        background-color: #465A3D;
        border-color: #465A3D;
        color: #F1ECD8;
    }

    .cashier-dashboard-page .dashboard-mocha {
        background-color: #4E3936;
        border-color: #4E3936;
        color: #F1ECD8;
    }

    .cashier-dashboard-page .dashboard-cream {
        background-color: #F1ECD8;
        border-color: #828854;
        color: #4E3936;
    }

    .cashier-dashboard-page .dashboard-olive {
        background-color: #828854;
        border-color: #828854;
        color: #F1ECD8;
    }

    .cashier-dashboard-page .dashboard-icon-light {
        background-color: rgb(241 236 216 / 0.15);
        color: #F1ECD8;
    }

    /* KARTU STATISTIK */
    .cashier-dashboard-page .dashboard-stat-card {
        min-height: 160px;
        border-radius: 24px;
        padding: 20px;
        box-shadow: 0 1px 3px rgb(0 0 0 / 0.08);
    }

    .cashier-dashboard-page .dashboard-stat-title {
        font-size: 14px;
        font-weight: 700;
    }

    .cashier-dashboard-page .dashboard-stat-number {
        font-size: 36px;
        font-weight: 700;
        line-height: 1.2;
    }

    .cashier-dashboard-page .dashboard-stat-description {
        margin-top: 8px;
        font-size: 14px;
        line-height: 1.5;
    }

    /* PANEL AKSI DAN AKTIVITAS */
    .cashier-dashboard-page .dashboard-panel {
        border-radius: 24px;
        box-shadow: 0 1px 3px rgb(0 0 0 / 0.08);
    }

    /* TABEL PESANAN */
    .cashier-dashboard-page .dashboard-orders-panel {
        border-radius: 24px;
        background-color: #F1ECD8;
        border-color: #828854;
    }

    .cashier-dashboard-page .dashboard-table tbody tr:nth-child(even) {
        background-color: rgb(130 136 84 / 0.10);
    }

    .cashier-dashboard-page .dashboard-table tbody tr:hover {
        background-color: rgb(130 136 84 / 0.18);
    }

    @media (max-width: 640px) {
        .cashier-dashboard-page .dashboard-welcome {
            padding: 28px 22px;
        }
    }
</style>

<div class="cashier-dashboard-page w-full space-y-6">

    {{-- SAMBUTAN --}}
    <section class="dashboard-welcome w-full">
        <p class="welcome-label uppercase">
            KEDAI KOPI SENJA
        </p>

        <h2 class="welcome-title">
            Selamat datang, Kasir
        </h2>

        <p class="welcome-description">
            Berikut ringkasan pesanan dan aktivitas kasir hari ini.
        </p>
    </section>

    {{-- RINGKASAN PESANAN --}}
    <section class="space-y-4">
        <div>
            <h3 class="text-lg font-bold text-[#4E3936]">
                Ringkasan Pesanan
            </h3>

            <p class="mt-1 text-sm text-[#4E3936]/80">
                Pantau kondisi pesanan Kedai Kopi Senja.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- TOTAL PESANAN --}}
            <div class="dashboard-green dashboard-stat-card flex flex-col justify-between border">
                <div class="flex items-center justify-between gap-2">
                    <p class="dashboard-stat-title">
                        Total Pesanan
                    </p>

                    <div class="dashboard-icon-light flex h-10 w-10 items-center justify-center rounded-xl">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="3" width="16" height="18" rx="2"/>
                            <path d="M8 8h8M8 12h8M8 16h5"/>
                        </svg>
                    </div>
                </div>

                <div>
                    <p class="dashboard-stat-number">
                        {{ $totalOrders }}
                    </p>

                    <p class="dashboard-stat-description opacity-90">
                        Seluruh pesanan
                    </p>
                </div>
            </div>

            {{-- PESANAN BARU --}}
            <div class="dashboard-mocha dashboard-stat-card flex flex-col justify-between border">
                <div class="flex items-center justify-between gap-2">
                    <p class="dashboard-stat-title">
                        Pesanan Baru
                    </p>

                    <div class="dashboard-icon-light flex h-10 w-10 items-center justify-center rounded-xl">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 7v5l3 2"/>
                        </svg>
                    </div>
                </div>

                <div>
                    <p class="dashboard-stat-number">
                        {{ $newOrders }}
                    </p>

                    <p class="dashboard-stat-description opacity-90">
                        Menunggu diproses
                    </p>
                </div>
            </div>

            {{-- SEDANG DIPROSES --}}
            <div class="dashboard-cream dashboard-stat-card flex flex-col justify-between border-2">
                <div class="flex items-center justify-between gap-2">
                    <p class="dashboard-stat-title">
                        Sedang Diproses
                    </p>

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#465A3D]/10 text-[#465A3D]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3v3M12 18v3M3 12h3M18 12h3"/>
                            <circle cx="12" cy="12" r="6"/>
                        </svg>
                    </div>
                </div>

                <div>
                    <p class="dashboard-stat-number text-[#465A3D]">
                        {{ $processingOrders }}
                    </p>

                    <p class="dashboard-stat-description">
                        Dalam pengerjaan
                    </p>
                </div>
            </div>

            {{-- PESANAN SELESAI --}}
            <div class="dashboard-olive dashboard-stat-card flex flex-col justify-between border">
                <div class="flex items-center justify-between gap-2">
                    <p class="dashboard-stat-title">
                        Pesanan Selesai
                    </p>

                    <div class="dashboard-icon-light flex h-10 w-10 items-center justify-center rounded-xl">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m5 12 4 4L19 6"/>
                        </svg>
                    </div>
                </div>

                <div>
                    <p class="dashboard-stat-number">
                        {{ $completedOrders }}
                    </p>

                    <p class="dashboard-stat-description opacity-90">
                        Pesanan telah selesai
                    </p>
                </div>
            </div>

        </div>
    </section>

    {{-- AKSI CEPAT --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-3">

        <div class="dashboard-cream dashboard-panel border p-6 lg:col-span-1">
            <h3 class="text-lg font-bold">
                Aksi Cepat
            </h3>

            <p class="mt-1 text-sm opacity-80">
                Akses menu kasir dengan mudah.
            </p>

            <div class="mt-4 space-y-2">

                <a
                    href="{{ route('kasir.menus.index') }}"
                    class="flex items-center justify-between rounded-xl px-3 py-4 text-sm font-semibold text-[#4E3936] transition-colors hover:bg-[#828854]/10"
                >
                    Buat Pesanan
                    <svg class="h-5 w-5 text-[#465A3D]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </a>

                <a
                    href="{{ route('kasir.orders.index') }}"
                    class="flex items-center justify-between rounded-xl px-3 py-4 text-sm font-semibold text-[#4E3936] transition-colors hover:bg-[#828854]/10"
                >
                    Daftar Pesanan
                    <svg class="h-5 w-5 text-[#465A3D]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </a>

                <a
                    href="{{ route('kasir.customers.reset-password') }}"
                    class="flex items-center justify-between rounded-xl px-3 py-4 text-sm font-semibold text-[#4E3936] transition-colors hover:bg-[#828854]/10"
                >
                    Reset Password Pelanggan
                    <svg class="h-5 w-5 text-[#465A3D]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </a>

            </div>
        </div>

        {{-- INFORMASI PESANAN --}}
        <div class="dashboard-mocha dashboard-panel flex flex-col justify-center border p-7 lg:col-span-2">
            <p class="text-sm font-bold">
                Aktivitas Pesanan
            </p>

            <p class="mt-3 text-3xl font-bold text-[#F1ECD8]">
                {{ $totalOrders }} Pesanan
            </p>

            <p class="mt-2 max-w-xl text-sm leading-relaxed opacity-90">
                Gunakan dashboard ini untuk memantau pesanan baru,
                pesanan yang sedang diproses, dan pesanan yang telah selesai.
            </p>

            <div class="mt-5">
                <a
                    href="{{ route('kasir.orders.index') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-[#465A3D] px-5 py-3 text-sm font-bold text-[#F1ECD8] transition-colors hover:bg-[#35472F]"
                >
                    Lihat Semua Pesanan
                </a>
            </div>
        </div>

    </section>

    {{-- PESANAN TERBARU --}}
    <section class="dashboard-orders-panel w-full overflow-hidden border shadow-sm">

        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#828854]/40 px-6 py-5">
            <div>
                <h3 class="text-lg font-bold text-[#4E3936]">
                    Pesanan Terbaru
                </h3>

                <p class="mt-1 text-sm text-[#4E3936]/80">
                    Pesanan kasir yang terakhir tercatat.
                </p>
            </div>

            <a
                href="{{ route('kasir.orders.index') }}"
                class="text-xs font-bold uppercase tracking-widest text-[#465A3D] transition-colors hover:text-[#35472F]"
            >
                Lihat daftar pesanan
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="dashboard-table w-full min-w-[560px] text-left">

                <thead class="bg-[#465A3D] text-[#F1ECD8]">
                    <tr>
                        <th class="whitespace-nowrap px-6 py-4 text-sm font-bold">
                            Kode Pesanan
                        </th>

                        <th class="whitespace-nowrap px-6 py-4 text-sm font-bold">
                            Waktu
                        </th>

                        <th class="px-6 py-4 text-sm font-bold">
                            Item
                        </th>

                        <th class="whitespace-nowrap px-6 py-4 text-sm font-bold">
                            Status
                        </th>

                        <th class="whitespace-nowrap px-6 py-4 text-right text-sm font-bold">
                            Total
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($latestOrders as $order)
                        <tr class="border-b border-[#828854]/30 last:border-b-0">

                            <td class="whitespace-nowrap px-6 py-4 text-sm font-bold text-[#4E3936]">
                                {{ $order->order_code }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm text-[#4E3936]/80">
                                {{ $order->ordered_at?->format('d/m/Y H:i') ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-sm text-[#4E3936]">
                                @forelse ($order->items as $item)
                                    <p>{{ $item->menu_name }} x {{ $item->quantity }}</p>
                                @empty
                                    <span class="text-[#4E3936]/60">-</span>
                                @endforelse
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                @if ($order->status === 'baru')
                                    <span class="inline-flex rounded-full bg-[#F1ECD8] px-3 py-1 text-xs font-bold text-[#4E3936] ring-1 ring-[#828854]">
                                        Baru
                                    </span>
                                @elseif ($order->status === 'diproses')
                                    <span class="inline-flex rounded-full bg-[#828854] px-3 py-1 text-xs font-bold text-[#F1ECD8]">
                                        Diproses
                                    </span>
                                @elseif ($order->status === 'selesai')
                                    <span class="inline-flex rounded-full bg-[#465A3D] px-3 py-1 text-xs font-bold text-[#F1ECD8]">
                                        Selesai
                                    </span>
                                @elseif ($order->status === 'dibatalkan')
                                    <span class="inline-flex rounded-full bg-[#640017]/10 px-3 py-1 text-xs font-bold text-[#640017]">
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-[#4E3936]/10 px-3 py-1 text-xs font-bold text-[#4E3936]">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-bold text-[#4E3936]">
                                Rp {{ number_format((float) $order->total, 0, ',', '.') }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <p class="font-bold text-[#4E3936]">
                                    Belum ada pesanan terbaru.
                                </p>

                                <p class="mt-1 text-sm text-[#4E3936]/80">
                                    Pesanan akan muncul di sini setelah transaksi tercatat.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </section>

</div>
@endsection
