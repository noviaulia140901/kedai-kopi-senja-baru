
@extends('layouts.manajer')

@section('content')
<style>
    .hpp-margin-page {
        background-color: #F1ECD8;
        color: #4E3936;
    }

    .hpp-margin-page .hpp-panel {
        background-color: #F1ECD8;
        border-color: rgb(130 136 84 / 40%);
    }

    .hpp-margin-page .hpp-table thead {
        background-color: #465A3D;
        color: #F1ECD8;
    }

    .hpp-margin-page .hpp-table tbody tr:nth-child(even) {
        background-color: rgb(130 136 84 / 10%);
    }

    .hpp-margin-page .hpp-table tbody tr:hover {
        background-color: rgb(130 136 84 / 16%);
    }
</style>

<div class="hpp-margin-page min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Judul Halaman --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#4E3936]">
                HPP dan Margin Menu
            </h1>
            <p class="mt-2 text-sm text-[#4E3936]/70">
                Perhitungan biaya produksi dan margin setiap menu berdasarkan resep dan harga bahan supplier.
            </p>
        </div>

        {{-- Tabel HPP dan Margin --}}
        <div class="hpp-panel overflow-hidden rounded-md border shadow-sm">
            <div class="overflow-x-auto">
                <table class="hpp-table min-w-full divide-y divide-[#828854]/40">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-[#F1ECD8]">
                                Nama Menu
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-[#F1ECD8]">
                                HPP
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-[#F1ECD8]">
                                Harga Jual
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-[#F1ECD8]">
                                Margin
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-[#F1ECD8]">
                                Pembaruan Harga Supplier
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#828854]/40">
                        @forelse ($menus as $menu)
                            <tr class="text-[#4E3936]">
                                <td class="px-6 py-4 text-sm font-medium text-[#4E3936]">
                                    {{ $menu->name }}
                                </td>

                                <td class="px-6 py-4 text-right text-sm text-[#4E3936]">
                                    @if ($menu->hpp_lengkap)
                                        Rp {{ number_format($menu->hpp_total, 2, ',', '.') }}
                                    @else
                                        <span class="text-[#828854]">
                                            Belum lengkap
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right text-sm text-[#4E3936]">
                                    Rp {{ number_format((float) $menu->selling_price, 2, ',', '.') }}
                                </td>

                                <td class="px-6 py-4 text-right text-sm font-semibold">
                                    @if ($menu->hpp_lengkap)
                                        <span class="{{ $menu->margin > 0 ? 'text-[#465A3D]' : ($menu->margin < 0 ? 'text-[#640017]' : 'text-[#4E3936]') }}">
                                            Rp {{ number_format($menu->margin, 2, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-[#828854]">-</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-sm text-[#4E3936]/70">
                                    @if ($menu->tanggal_harga)
                                        {{ $menu->tanggal_harga->format('d-m-Y') }}
                                    @else
                                        Belum tersedia
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-[#4E3936]/70">
                                    Belum ada data menu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Rumus Perhitungan --}}
        <div class="hpp-panel mt-6 rounded-md border p-5">
            <h2 class="font-semibold text-[#4E3936]">
                Rumus Perhitungan
            </h2>

            <p class="mt-2 text-sm text-[#4E3936]/70">
                HPP = Jumlah (Kuantitas bahan × Harga bahan per satuan dasar).
            </p>

            <p class="mt-1 text-sm text-[#4E3936]/70">
                Margin = Harga jual menu − HPP.
            </p>
        </div>

    </div>
</div>
@endsection
