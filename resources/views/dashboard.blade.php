@extends('layouts.manajer')

@section('content')
<div class="w-full space-y-6">

    {{-- SAMBUTAN --}}
    <section class="w-full border border-[#5A5E27] bg-[#5A5E27] px-6 py-6">
        <p class="text-xs font-bold uppercase tracking-widest text-[#EFEFC9]">
            KEDAI KOPI SENJA
        </p>

        <h2 class="mt-2 text-2xl font-bold text-[#EFEFC9]">
            Dashboard Manajer
        </h2>

        <p class="mt-2 text-sm leading-relaxed text-[#EFEFC9]/90">
            Selamat datang, {{ Auth::user()->name }}. Berikut ringkasan operasional kedai hari ini.
        </p>
    </section>

    {{-- RINGKASAN OPERASIONAL --}}
    <section class="space-y-4">
        <div>
            <h3 class="text-lg font-bold text-[#2F1B1A]">
                Ringkasan Operasional
            </h3>
            <p class="mt-1 text-sm text-[#2F1B1A]/70">
                Pantau kondisi kedai melalui informasi berikut.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- MENU AKTIF --}}
            <div class="flex min-h-36 flex-col justify-between border border-[#5A5E27]/20 bg-[#EFEFC9] p-5">
                <p class="text-sm font-bold text-[#2F1B1A]">
                    Menu Aktif
                </p>

                <div>
                    <p class="text-4xl font-bold leading-tight text-[#5A5E27]">
                        {{ $totalMenuAktif }}
                    </p>
                    <p class="mt-2 text-sm text-[#2F1B1A]/70">
                        Menu tersedia
                    </p>
                </div>
            </div>

            {{-- BAHAN BAKU AKTIF --}}
            <div class="flex min-h-36 flex-col justify-between border border-[#5A5E27]/20 bg-[#EFEFC9] p-5">
                <p class="text-sm font-bold text-[#2F1B1A]">
                    Bahan Baku Aktif
                </p>

                <div>
                    <p class="text-4xl font-bold leading-tight text-[#5A5E27]">
                        {{ $totalBahanBaku }}
                    </p>
                    <p class="mt-2 text-sm text-[#2F1B1A]/70">
                        Jenis bahan baku
                    </p>
                </div>
            </div>

            {{-- BAHAN MENIPIS --}}
            <div class="flex min-h-36 flex-col justify-between border border-[#2F1B1A]/20 bg-[#EFEFC9] p-5">
                <p class="text-sm font-bold text-[#2F1B1A]">
                    Bahan Baku Menipis
                </p>

                <div>
                    <p class="text-4xl font-bold leading-tight text-[#2F1B1A]">
                        {{ $bahanMenipis }}
                    </p>
                    <p class="mt-2 text-sm text-[#2F1B1A]/70">
                        Perlu diperiksa
                    </p>
                </div>
            </div>

            {{-- PESANAN HARI INI --}}
            <div class="flex min-h-36 flex-col justify-between border border-[#5A5E27]/20 bg-[#EFEFC9] p-5">
                <p class="text-sm font-bold text-[#2F1B1A]">
                    Pesanan Hari Ini
                </p>

                <div>
                    <p class="text-4xl font-bold leading-tight text-[#5A5E27]">
                        {{ $pesananHariIni }}
                    </p>
                    <p class="mt-2 text-sm text-[#2F1B1A]/70">
                        Tidak termasuk pesanan dibatalkan
                    </p>
                </div>
            </div>

            {{-- PENJUALAN HARI INI --}}
            <div class="border border-[#2F1B1A] bg-[#2F1B1A] p-6 sm:col-span-2 xl:col-span-4">
                <p class="text-sm font-bold text-[#EFEFC9]">
                    Penjualan Hari Ini
                </p>

                <p class="mt-3 break-words text-3xl font-bold text-[#EFEFC9] sm:text-4xl">
                    Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}
                </p>

                <p class="mt-2 text-sm text-[#EFEFC9]/80">
                    Dari pesanan berstatus selesai
                </p>
            </div>

        </div>
    </section>

    {{-- PESANAN TERBARU --}}
    <section class="w-full overflow-hidden border border-[#5A5E27]/20 bg-[#EFEFC9]">

        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#5A5E27]/20 px-6 py-5">
            <div>
                <h3 class="text-lg font-bold text-[#2F1B1A]">
                    Pesanan Terbaru
                </h3>
                <p class="mt-1 text-sm text-[#2F1B1A]/70">
                    Lima pesanan terakhir yang tercatat.
                </p>
            </div>

            <p class="text-xs font-bold uppercase tracking-widest text-[#5A5E27]">
                Transaksi
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[560px] text-left">

                <thead class="bg-[#5A5E27] text-[#EFEFC9]">
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
                        <tr class="border-b border-[#5A5E27]/15 last:border-b-0 even:bg-white/40">

                            <td class="whitespace-nowrap px-6 py-4 text-sm font-bold text-[#2F1B1A]">
                                {{ $pesanan->order_code }}
                            </td>

                            <td class="px-6 py-4 text-sm text-[#2F1B1A]">
                                {{ $pesanan->customer_name ?? 'Pelanggan umum' }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <span class="inline-flex border border-[#5A5E27]/30 bg-[#5A5E27]/10 px-4 py-1 text-xs font-bold text-[#5A5E27]">
                                    {{ ucfirst($pesanan->status) }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-bold text-[#2F1B1A]">
                                Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <p class="font-bold text-[#2F1B1A]">
                                    Belum ada pesanan
                                </p>
                                <p class="mt-1 text-sm text-[#2F1B1A]/70">
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
