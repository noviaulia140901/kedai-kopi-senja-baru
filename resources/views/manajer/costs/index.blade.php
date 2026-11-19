@extends('layouts.manajer')

@section('content')
<div class="min-h-screen bg-[#F7F3ED] py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Judul Halaman --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#34251F]">
                HPP dan Margin Menu
            </h1>
            <p class="mt-2 text-sm text-[#6F5548]">
                Perhitungan biaya produksi dan margin setiap menu berdasarkan resep dan harga bahan supplier.
            </p>
        </div>

        {{-- Tabel HPP dan Margin --}}
        <div class="overflow-hidden rounded-2xl border border-[#D8C9BE] bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#E8DED5]">
                    <thead class="bg-[#F3EBE4]">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-[#493225]">
                                Nama Menu
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-[#493225]">
                                HPP
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-[#493225]">
                                Harga Jual
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-[#493225]">
                                Margin
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-[#493225]">
                                Pembaruan Harga Supplier
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#E8DED5]">
                        @forelse ($menus as $menu)
                            <tr class="hover:bg-[#FAF7F3]">
                                <td class="px-6 py-4 text-sm font-medium text-[#34251F]">
                                    {{ $menu->name }}
                                </td>

                                <td class="px-6 py-4 text-right text-sm text-[#493225]">
                                    @if ($menu->hpp_lengkap)
                                        Rp {{ number_format($menu->hpp_total, 2, ',', '.') }}
                                    @else
                                        Belum lengkap
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right text-sm text-[#493225]">
                                    Rp {{ number_format((float) $menu->selling_price, 2, ',', '.') }}
                                </td>

                                <td class="px-6 py-4 text-right text-sm font-semibold text-[#493225]">
                                    @if ($menu->hpp_lengkap)
                                        Rp {{ number_format($menu->margin, 2, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-sm text-[#6F5548]">
                                    @if ($menu->tanggal_harga)
                                        {{ $menu->tanggal_harga->format('d-m-Y') }}
                                    @else
                                        Belum tersedia
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-[#6F5548]">
                                    Belum ada data menu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Rumus Perhitungan --}}
        <div class="mt-6 rounded-xl border border-[#D8C9BE] bg-white p-5">
            <h2 class="font-semibold text-[#34251F]">
                Rumus Perhitungan
            </h2>

            <p class="mt-2 text-sm text-[#6F5548]">
                HPP = Jumlah (Kuantitas bahan × Harga bahan per satuan dasar).
            </p>

            <p class="mt-1 text-sm text-[#6F5548]">
                Margin = Harga jual menu − HPP.
            </p>
        </div>

    </div>
</div>
@endsection
