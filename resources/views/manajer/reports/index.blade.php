@extends('layouts.manajer')

@section('content')
<div class="space-y-7">

    {{-- Judul halaman --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Laporan Usaha</h1>
        <p class="mt-1 text-sm text-gray-600">
            Ringkasan penjualan, stok bahan baku, dan margin keuntungan menu.
        </p>
    </div>

    {{-- Navigasi kategori laporan --}}
    <nav class="flex flex-wrap items-center gap-2" aria-label="Kategori laporan">
        <a href="#laporan-penjualan"
            class="rounded-full border border-gray-800 bg-gray-800 px-4 py-1.5 text-xs font-semibold text-white hover:bg-gray-700">
            Penjualan per Menu
        </a>

        <a href="#bahan-menipis"
            class="rounded-full border border-gray-800 bg-white px-4 py-1.5 text-xs font-semibold text-gray-800 hover:bg-gray-100">
            Bahan Menipis
        </a>

        <a href="#margin-menu"
            class="rounded-full border border-gray-800 bg-white px-4 py-1.5 text-xs font-semibold text-gray-800 hover:bg-gray-100">
            Margin per Menu
        </a>
    </nav>

    {{-- Filter periode --}}
    <section class="space-y-3">
        <form action="{{ route('manajer.reports.index') }}" method="GET"
            class="flex flex-col gap-4 md:flex-row md:items-end">

            <div class="w-full md:max-w-[201px]">
                <label for="tanggal_mulai" class="mb-2 block text-xs font-bold text-gray-800">
                    Periode Awal
                </label>

                <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                    value="{{ request('tanggal_mulai', $tanggalMulai) }}"
                    class="h-[37px] w-full rounded-md border border-gray-800 bg-white px-3 text-sm text-gray-700 focus:border-gray-500 focus:outline-none">

                @error('tanggal_mulai')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="w-full md:max-w-[201px]">
                <label for="tanggal_akhir" class="mb-2 block text-xs font-bold text-gray-800">
                    Periode Akhir
                </label>

                <input type="date" id="tanggal_akhir" name="tanggal_akhir"
                    value="{{ request('tanggal_akhir', $tanggalAkhir) }}"
                    class="h-[37px] w-full rounded-md border border-gray-800 bg-white px-3 text-sm text-gray-700 focus:border-gray-500 focus:outline-none">

                @error('tanggal_akhir')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="h-[38px] w-full rounded-md bg-gray-800 px-6 text-sm font-bold text-white hover:bg-gray-700 md:w-[141px]">
                    Tampilkan
                </button>

                <a href="{{ route('manajer.reports.index') }}"
                    class="flex h-[38px] items-center justify-center rounded-md border border-gray-800 bg-white px-5 text-sm font-semibold text-gray-800 hover:bg-gray-100">
                    Reset
                </a>
            </div>
        </form>
    </section>

    {{-- Laporan penjualan per menu --}}
    <section id="laporan-penjualan"
        class="scroll-mt-6 rounded-lg border-2 border-gray-800 bg-white p-5">

        <div class="mb-7">
            <h2 class="text-base font-bold text-gray-800">
                Laporan Penjualan per Menu
            </h2>

            <p class="mt-1 text-xs text-gray-500">
                Periode
                {{ \Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') }}
                sampai
                {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}
            </p>
        </div>

        @if ($penjualan->isNotEmpty())
            @php
                $penjualanMaksimum = (float) $penjualan->max('total_terjual');
            @endphp

            <div class="space-y-5">
                @foreach ($penjualan as $item)
                    @php
                        $nilaiPenjualan = (float) $item->total_terjual;

                        $lebarBatang = $penjualanMaksimum > 0
                            ? ($nilaiPenjualan / $penjualanMaksimum) * 100
                            : 0;
                    @endphp

                    <div class="grid grid-cols-[100px_minmax(0,1fr)_65px] items-center gap-3 sm:grid-cols-[150px_minmax(0,1fr)_85px] sm:gap-4">

                        {{-- Nama menu --}}
                        <div class="truncate text-xs font-medium text-gray-800 sm:text-sm"
                            title="{{ $item->menu_name }}">
                            {{ $item->menu_name }}
                        </div>

                        {{-- Batang grafik --}}
                        <div class="h-7 w-full overflow-hidden bg-gray-100">
                            <div class="h-7 rounded-sm bg-gray-800"
                                style="width: {{ $lebarBatang }}%">
                            </div>
                        </div>

                        {{-- Jumlah terjual --}}
                        <div class="text-right text-xs font-semibold text-gray-800">
                            {{ number_format($item->total_terjual, 0, ',', '.') }}
                            <span class="font-normal text-gray-500">porsi</span>
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- Total penjualan --}}
            <div class="mt-8 border-t border-gray-800 pt-4 text-right">
                <p class="text-xs font-medium text-gray-600">
                    Total Keseluruhan Penjualan
                </p>

                <p class="mt-1 text-lg font-bold text-gray-800">
                    Rp{{ number_format($penjualan->sum('total_penjualan'), 0, ',', '.') }}
                </p>
            </div>
        @else
            <div class="border-t border-gray-200 py-12 text-center">
                <p class="text-sm text-gray-500">
                    Belum ada penjualan selesai pada periode ini.
                </p>
            </div>
        @endif
    </section>

    {{-- Bahan baku menipis --}}
    <section id="bahan-menipis"
        class="scroll-mt-6 rounded-lg border-2 border-gray-800 bg-white p-5">

        <div class="mb-5">
            <h2 class="text-base font-bold text-gray-800">
                Bahan Baku Menipis
            </h2>

            <p class="mt-1 text-xs text-gray-500">
                Bahan aktif dengan stok yang sudah mencapai atau di bawah batas minimum.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[500px] text-left text-sm">
                <thead class="border-b border-gray-800 bg-gray-100 text-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-xs font-bold">No.</th>
                        <th class="px-4 py-3 text-xs font-bold">Nama Bahan</th>
                        <th class="px-4 py-3 text-right text-xs font-bold">Stok Saat Ini</th>
                        <th class="px-4 py-3 text-right text-xs font-bold">Batas Minimum</th>
                        <th class="px-4 py-3 text-center text-xs font-bold">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($bahanMenipis as $bahan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-700">{{ $loop->iteration }}</td>

                            <td class="px-4 py-3 font-medium text-gray-800">
                                {{ $bahan->name }}
                            </td>

                            <td class="px-4 py-3 text-right text-gray-700">
                                {{ number_format((float) $bahan->current_stock, 3, ',', '.') }}
                                {{ $bahan->base_unit }}
                            </td>

                            <td class="px-4 py-3 text-right text-gray-700">
                                {{ number_format((float) $bahan->minimum_stock, 3, ',', '.') }}
                                {{ $bahan->base_unit }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                <span class="inline-block rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                    Perlu Restok
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">
                                Tidak ada bahan baku yang menipis.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- Margin keuntungan menu --}}
    <section id="margin-menu"
        class="scroll-mt-6 rounded-lg border-2 border-gray-800 bg-white p-5">

        <div class="mb-5">
            <h2 class="text-base font-bold text-gray-800">
                Margin Keuntungan Menu
            </h2>

            <p class="mt-1 text-xs text-gray-500">
                Perbandingan harga jual menu dengan Harga Pokok Produksi (HPP).
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[650px] text-left text-sm">
                <thead class="border-b border-gray-800 bg-gray-100 text-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-xs font-bold">No.</th>
                        <th class="px-4 py-3 text-xs font-bold">Nama Menu</th>
                        <th class="px-4 py-3 text-right text-xs font-bold">Harga Jual</th>
                        <th class="px-4 py-3 text-right text-xs font-bold">Margin</th>
                        <th class="px-4 py-3 text-center text-xs font-bold">Keterangan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($menus as $menu)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-700">{{ $loop->iteration }}</td>

                            <td class="px-4 py-3 font-medium text-gray-800">
                                {{ $menu->name }}
                            </td>

                            <td class="px-4 py-3 text-right text-gray-700">
                                Rp{{ number_format((float) $menu->selling_price, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-3 text-right font-medium text-gray-800">
                                @if ($menu->hpp_lengkap)
                                    Rp{{ number_format((float) $menu->margin, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>

                            <td class="px-4 py-3 text-center">
                                @if (!$menu->hpp_lengkap)
                                    <span class="inline-block rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                        HPP Belum Lengkap
                                    </span>
                                @elseif ($menu->margin > 0)
                                    <span class="inline-block rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Menguntungkan
                                    </span>
                                @elseif ($menu->margin == 0)
                                    <span class="inline-block rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                        Impas
                                    </span>
                                @else
                                    <span class="inline-block rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                        Rugi
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">
                                Belum ada data menu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- Keterangan hak akses --}}
    <p class="pt-1 text-xs text-gray-500">
        Hanya Manajer yang bisa membuka halaman ini. Kasir/Pelanggan ditolak server.
    </p>

</div>
@endsection
