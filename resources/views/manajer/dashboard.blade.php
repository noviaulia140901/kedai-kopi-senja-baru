
@extends('layouts.manajer')

@section('content')
<style>
    body:has(.manager-dashboard-page) main {
        background-color: #F1ECD8;
    }

    .manager-dashboard-page {
        color: #4E3936;
    }

    /* BANNER SAMBUTAN */
    .manager-dashboard-page .dashboard-welcome {
        background-color: #465A3D;
        color: #F1ECD8;
        border-radius: 30px;
        padding: 32px 28px;
    }

    .manager-dashboard-page .dashboard-welcome .welcome-label {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .1em;
    }

    .manager-dashboard-page .dashboard-welcome h2 {
        margin-top: 12px;
        font-size: 24px;
        font-weight: 700;
        line-height: 1.3;
    }

    .manager-dashboard-page .dashboard-welcome .welcome-description {
        margin-top: 4px;
        font-size: 14px;
        font-weight: 400;
        line-height: 1.5;
    }

    /* WARNA KARTU */
    .manager-dashboard-page .dashboard-green {
        background-color: #465A3D;
        border-color: #465A3D;
        color: #F1ECD8;
    }

    .manager-dashboard-page .dashboard-mocha {
        background-color: #4E3936;
        border-color: #4E3936;
        color: #F1ECD8;
    }

    .manager-dashboard-page .dashboard-cream {
        background-color: #F1ECD8;
        border-color: #828854;
        color: #4E3936;
    }

    .manager-dashboard-page .dashboard-olive {
        background-color: #828854;
        border-color: #828854;
        color: #F1ECD8;
    }

    .manager-dashboard-page .dashboard-burgundy {
        background-color: #640017;
        border-color: #640017;
        color: #F1ECD8;
    }

    .manager-dashboard-page .dashboard-icon-light {
        background-color: rgb(241 236 216 / 0.15);
        color: #F1ECD8;
    }

    /* KARTU STATISTIK */
    .manager-dashboard-page .dashboard-stat-card {
        min-height: 160px;
        border-radius: 24px;
        padding: 20px;
        box-shadow: 0 1px 3px rgb(0 0 0 / 0.08);
    }

    .manager-dashboard-page .dashboard-stat-title {
        font-size: 14px;
        font-weight: 700;
    }

    .manager-dashboard-page .dashboard-stat-number {
        font-size: 36px;
        font-weight: 700;
        line-height: 1.2;
    }

    .manager-dashboard-page .dashboard-stat-description {
        margin-top: 8px;
        font-size: 14px;
        font-weight: 400;
        line-height: 1.5;
    }

    /* PANEL PENJUALAN */
    .manager-dashboard-page .dashboard-sales-panel {
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 1px 3px rgb(0 0 0 / 0.08);
    }

    /* PANEL PESANAN */
    .manager-dashboard-page .dashboard-orders-panel {
        border-radius: 24px;
    }

    .manager-dashboard-page .dashboard-orders-panel h3 {
        font-size: 18px;
        font-weight: 700;
    }

    .manager-dashboard-page .dashboard-table tbody tr:nth-child(even) {
        background-color: rgb(130 136 84 / 0.10);
    }

    .manager-dashboard-page .dashboard-table tbody tr:hover {
        background-color: rgb(130 136 84 / 0.18);
    }

    @media (max-width: 640px) {
        .manager-dashboard-page .dashboard-welcome {
            padding: 28px 22px;
        }

        .manager-dashboard-page .dashboard-sales-panel {
            padding: 20px;
        }
    }
</style>

<div class="manager-dashboard-page w-full space-y-6">

    {{-- SAMBUTAN --}}
    <section class="dashboard-welcome w-full">
        <p class="welcome-label uppercase">
            KEDAI KOPI SENJA
        </p>

        <h2>
            Selamat datang, Manajer
        </h2>

        <p class="welcome-description opacity-90">
            Berikut ringkasan operasional Kedai Kopi Senja hari ini.
        </p>
    </section>

    {{-- RINGKASAN OPERASIONAL --}}
    <section class="space-y-4">

        <div>
            <h3 class="text-lg font-bold text-[#4E3936]">
                Ringkasan Operasional
            </h3>

            <p class="mt-1 text-sm text-[#4E3936]/80">
                Pantau kondisi operasional kedai melalui informasi berikut.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- MENU AKTIF --}}
            <div class="dashboard-green dashboard-stat-card flex flex-col justify-between">
                <div class="flex items-center justify-between gap-2">
                    <p class="dashboard-stat-title">
                        Menu Aktif
                    </p>

                    <div class="dashboard-icon-light flex h-10 w-10 items-center justify-center rounded-xl">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 9h12v5a5 5 0 0 1-5 5h-2a5 5 0 0 1-5-5V9z"/>
                            <path d="M16 10h1.5a2.5 2.5 0 0 1 0 5H16"/>
                        </svg>
                    </div>
                </div>

                <div>
                    <p class="dashboard-stat-number text-[#F1ECD8]">
                        {{ $totalMenuAktif }}
                    </p>

                    <p class="dashboard-stat-description opacity-90">
                        Menu tersedia
                    </p>
                </div>
            </div>

            {{-- BAHAN BAKU AKTIF --}}
            <div class="dashboard-mocha dashboard-stat-card flex flex-col justify-between">
                <div class="flex items-center justify-between gap-2">
                    <p class="dashboard-stat-title">
                        Bahan Baku Aktif
                    </p>

                    <div class="dashboard-icon-light flex h-10 w-10 items-center justify-center rounded-xl">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3 3 8l9 5 9-5-9-5z"/>
                            <path d="m3 8 9 5 9-5"/>
                            <path d="M3 8v9l9 5 9-5V8"/>
                            <path d="M12 13v9"/>
                        </svg>
                    </div>
                </div>

                <div>
                    <p class="dashboard-stat-number text-[#F1ECD8]">
                        {{ $totalBahanBaku }}
                    </p>

                    <p class="dashboard-stat-description opacity-90">
                        Jenis bahan baku
                    </p>
                </div>
            </div>

            {{-- BAHAN BAKU MENIPIS --}}
            <div class="dashboard-cream dashboard-stat-card flex flex-col justify-between border-2">
                <div class="flex items-center justify-between gap-2">
                    <p class="dashboard-stat-title">
                        Bahan Baku Menipis
                    </p>

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#640017]/10 text-[#640017]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3 2.8 19h18.4L12 3z"/>
                            <path d="M12 9v4M12 16h.01"/>
                        </svg>
                    </div>
                </div>

                <div>
                    <p class="dashboard-stat-number text-[#640017]">
                        {{ $bahanMenipis }}
                    </p>

                    <p class="dashboard-stat-description">
                        Perlu diperiksa
                    </p>
                </div>
            </div>

            {{-- PESANAN HARI INI --}}
            <div class="dashboard-olive dashboard-stat-card flex flex-col justify-between">
                <div class="flex items-center justify-between gap-2">
                    <p class="dashboard-stat-title">
                        Pesanan Hari Ini
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
                        {{ $pesananHariIni }}
                    </p>

                    <p class="dashboard-stat-description opacity-90">
                        Tidak termasuk pesanan dibatalkan
                    </p>
                </div>
            </div>

        </div>
    </section>

    {{-- PENJUALAN HARI INI --}}
    <section class="dashboard-mocha dashboard-sales-panel border">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-bold">
                    Penjualan Hari Ini
                </p>

                <p class="mt-3 break-words text-3xl font-bold text-[#F1ECD8] sm:text-4xl">
                    Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}
                </p>

                <p class="mt-2 text-sm font-normal opacity-90">
                    Dari pesanan berstatus selesai
                </p>
            </div>

            <div class="dashboard-icon-light flex h-12 w-12 items-center justify-center rounded-xl">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3v18h18"/>
                    <path d="m7 14 4-4 4 4 6-7"/>
                </svg>
            </div>
        </div>
    </section>

    {{-- PESANAN TERBARU --}}
    <section class="dashboard-orders-panel w-full overflow-hidden border border-[#828854] bg-[#F1ECD8] shadow-sm">

        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#828854]/40 px-6 py-5">
            <div>
                <h3 class="text-lg font-bold text-[#4E3936]">
                    Pesanan Terbaru
                </h3>

                <p class="mt-1 text-sm text-[#4E3936]/80">
                    Lima pesanan terakhir yang tercatat.
                </p>
            </div>

            <p class="rounded-full bg-[#465A3D] px-4 py-2 text-xs font-bold uppercase tracking-widest text-[#F1ECD8]">
                Transaksi
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="dashboard-table w-full min-w-[560px] text-left">

                <thead class="bg-[#465A3D] text-[#F1ECD8]">
                    <tr>
                        <th class="whitespace-nowrap px-6 py-4 text-sm font-bold">
                            Kode Pesanan
                        </th>

                        <th class="whitespace-nowrap px-6 py-4 text-sm font-bold">
                            Pelanggan
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
                    @forelse ($pesananTerbaru as $pesanan)
                        <tr class="border-b border-[#828854]/30 last:border-b-0">

                            <td class="whitespace-nowrap px-6 py-4 text-sm font-bold text-[#4E3936]">
                                {{ $pesanan->order_code }}
                            </td>

                            <td class="px-6 py-4 text-sm text-[#4E3936]">
                                {{ $pesanan->customer_name ?? 'Pelanggan umum' }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                @if ($pesanan->status === 'selesai')
                                    <span class="inline-flex rounded-full bg-[#465A3D]/15 px-3 py-1 text-xs font-bold text-[#465A3D]">
                                        Selesai
                                    </span>
                                @elseif ($pesanan->status === 'dibatalkan')
                                    <span class="inline-flex rounded-full bg-[#640017]/10 px-3 py-1 text-xs font-bold text-[#640017]">
                                        Dibatalkan
                                    </span>
                                @elseif ($pesanan->status === 'baru')
                                    <span class="inline-flex rounded-full bg-[#F1ECD8] px-3 py-1 text-xs font-bold text-[#4E3936] ring-1 ring-[#828854]">
                                        Baru
                                    </span>
                                @elseif ($pesanan->status === 'diproses')
                                    <span class="inline-flex rounded-full bg-[#828854] px-3 py-1 text-xs font-bold text-[#F1ECD8]">
                                        Diproses
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-[#4E3936]/10 px-3 py-1 text-xs font-bold text-[#4E3936]">
                                        {{ ucfirst($pesanan->status) }}
                                    </span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-bold text-[#4E3936]">
                                Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <p class="font-bold text-[#4E3936]">
                                    Belum ada pesanan.
                                </p>

                                <p class="mt-1 text-sm text-[#4E3936]/70">
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
