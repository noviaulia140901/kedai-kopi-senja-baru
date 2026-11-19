@extends('layouts.manajer')

@section('content')
<div class="w-full space-y-6">

    {{-- SAMBUTAN --}}
    <section class="w-full border-2 border-black bg-[#F3F3F3] px-6 py-6">
        <p class="text-xs font-bold uppercase tracking-widest text-[#805238]">
            KEDAI KOPI SENJA
        </p>

        <h2 class="mt-2 text-2xl font-bold text-black">
            Dashboard Manajer
        </h2>

        <p class="mt-2 text-sm leading-relaxed text-gray-700">
            Selamat datang, {{ Auth::user()->name }}. Berikut ringkasan operasional kedai hari ini.
        </p>
    </section>

    {{-- RINGKASAN OPERASIONAL --}}
    <section class="space-y-4">
        <div>
            <h3 class="text-lg font-bold text-black">
                Ringkasan Operasional
            </h3>
            <p class="mt-1 text-sm text-gray-600">
                Pantau kondisi kedai melalui informasi berikut.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- MENU AKTIF --}}
            <div class="flex min-h-36 flex-col justify-between border-2 border-black bg-white p-5">
                <p class="text-sm font-bold text-gray-700">
                    Menu Aktif
                </p>

                <div>
                    <p class="text-4xl font-bold leading-tight text-black">
                        {{ $totalMenuAktif }}
                    </p>
                    <p class="mt-2 text-sm text-gray-600">
                        Menu tersedia
                    </p>
                </div>
            </div>

            {{-- BAHAN BAKU AKTIF --}}
            <div class="flex min-h-36 flex-col justify-between border-2 border-black bg-white p-5">
                <p class="text-sm font-bold text-gray-700">
                    Bahan Baku Aktif
                </p>

                <div>
                    <p class="text-4xl font-bold leading-tight text-black">
                        {{ $totalBahanBaku }}
                    </p>
                    <p class="mt-2 text-sm text-gray-600">
                        Jenis bahan baku
                    </p>
                </div>
            </div>

            {{-- BAHAN MENIPIS --}}
            <div class="flex min-h-36 flex-col justify-between border-2 border-black bg-white p-5">
                <p class="text-sm font-bold text-gray-700">
                    Bahan Baku Menipis
                </p>

                <div>
                    <p class="text-4xl font-bold leading-tight text-[#A66A45]">
                        {{ $bahanMenipis }}
                    </p>
                    <p class="mt-2 text-sm text-gray-600">
                        Perlu diperiksa
                    </p>
                </div>
            </div>

            {{-- PESANAN HARI INI --}}
            <div class="flex min-h-36 flex-col justify-between border-2 border-black bg-white p-5">
                <p class="text-sm font-bold text-gray-700">
                    Pesanan Hari Ini
                </p>

                <div>
                    <p class="text-4xl font-bold leading-tight text-black">
                        {{ $pesananHariIni }}
                    </p>
                    <p class="mt-2 text-sm text-gray-600">
                        Tidak termasuk pesanan dibatalkan
                    </p>
                </div>
            </div>

            {{-- PENJUALAN HARI INI --}}
            <div class="border-2 border-black bg-[#493225] p-6 sm:col-span-2 xl:col-span-4">
                <p class="text-sm font-bold text-[#F2E4D9]">
                    Penjualan Hari Ini
                </p>

                <p class="mt-3 break-words text-3xl font-bold text-white sm:text-4xl">
                    Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}
                </p>

                <p class="mt-2 text-sm text-[#F2E4D9]">
                    Dari pesanan berstatus selesai
                </p>
            </div>

        </div>
    </section>

    {{-- PESANAN TERBARU --}}
    <section class="w-full overflow-hidden border-2 border-black bg-white">

        <div class="flex flex-wrap items-center justify-between gap-3 border-b-2 border-black px-6 py-5">
            <div>
                <h3 class="text-lg font-bold text-black">
                    Pesanan Terbaru
                </h3>
                <p class="mt-1 text-sm text-gray-600">
                    Lima pesanan terakhir yang tercatat.
                </p>
            </div>

            <p class="text-xs font-bold uppercase tracking-widest text-[#805238]">
                Transaksi
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[560px] text-left">

                <thead class="bg-black text-white">
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
                        <tr class="border-b border-black last:border-b-0 even:bg-[#EEEEEE]">

                            <td class="whitespace-nowrap px-6 py-4 text-sm font-bold text-black">
                                {{ $pesanan->order_code }}
                            </td>

                            <td class="px-6 py-4 text-sm text-black">
                                {{ $pesanan->customer_name ?? 'Pelanggan umum' }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <span class="inline-flex border-2 border-black bg-white px-4 py-1 text-xs font-bold">
                                    {{ ucfirst($pesanan->status) }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-bold text-black">
                                Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <p class="font-bold text-black">
                                    Belum ada pesanan
                                </p>
                                <p class="mt-1 text-sm text-gray-600">
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
