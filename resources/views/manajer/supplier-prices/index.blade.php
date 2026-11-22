
@extends('layouts.manajer')

@section('content')
<style>
    body:has(.manager-supplier-prices-page) main {
        background-color: #F1ECD8;
    }

    .manager-supplier-prices-page {
        color: #4E3936;
    }

    .manager-supplier-prices-page .supplier-panel {
        background-color: #F1ECD8;
        border-color: rgb(130 136 84 / 40%);
    }

    .manager-supplier-prices-page .supplier-table thead {
        background-color: #465A3D;
        color: #F1ECD8;
    }

    .manager-supplier-prices-page .supplier-table tbody tr:nth-child(even) {
        background-color: rgb(130 136 84 / 10%);
    }

    .manager-supplier-prices-page .supplier-table tbody tr:hover {
        background-color: rgb(130 136 84 / 16%);
    }

    .manager-supplier-prices-page .sync-button {
        background-color: #465A3D;
        color: #F1ECD8;
        transition: background-color 0.2s ease;
    }

    .manager-supplier-prices-page .sync-button:hover {
        background-color: #35472F;
    }
</style>

<div class="manager-supplier-prices-page space-y-6">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-[#4E3936]">
                Harga Supplier
            </h1>
            <p class="mt-1 text-sm text-[#4E3936]/60">
                Informasi harga bahan baku dari layanan supplier.
            </p>
        </div>

        <form action="{{ route('manajer.supplier-prices.sync') }}" method="POST">
            @csrf
            <button
                type="submit"
                class="sync-button rounded-md px-4 py-[10px] font-semibold"
            >
                Sinkronisasi Harga
            </button>
        </form>
    </div>

    @if (session('success'))
        <div class="rounded-md border border-[#828854]/40 bg-[#F1ECD8] p-4 text-sm text-[#465A3D]">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-md border border-[#640017]/30 bg-[#F1ECD8] p-4 text-sm text-[#640017]">
            {{ session('error') }}
        </div>
    @endif

    <div class="supplier-panel overflow-hidden rounded-md border">
        <div class="overflow-x-auto">
            <table class="supplier-table min-w-full divide-y divide-[#828854]/20">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-[#F1ECD8]">
                            No.
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-[#F1ECD8]">
                            Nama Bahan
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-[#F1ECD8]">
                            Kode Supplier
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-[#F1ECD8]">
                            Satuan Dasar
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-[#F1ECD8]">
                            Harga per Satuan
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-[#F1ECD8]">
                            Diperbarui Supplier
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-[#F1ECD8]">
                            Terakhir Diambil
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#828854]/20 text-[#4E3936]">
                    @forelse ($ingredients as $ingredient)
                        @php
                            $price = $ingredient->prices->first();
                        @endphp

                        <tr>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-[#4E3936]/60">
                                {{ $ingredients->firstItem() + $loop->index }}
                            </td>

                            <td class="px-4 py-3 text-sm font-medium text-[#4E3936]">
                                {{ $ingredient->name }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 text-sm text-[#4E3936]/60">
                                {{ $price?->supplier_code ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 text-sm text-[#4E3936]/60">
                                {{ $ingredient->base_unit }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 text-sm text-[#4E3936]">
                                @if ($price)
                                    Rp {{ number_format((float) $price->price_per_unit, 2, ',', '.') }}
                                @else
                                    <span class="text-[#828854]">
                                        Belum tersedia
                                    </span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 text-sm text-[#4E3936]/60">
                                {{ $price?->supplier_updated_at?->format('d/m/Y') ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 text-sm text-[#4E3936]/60">
                                {{ $price?->fetched_at?->format('d/m/Y H:i') ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-sm text-[#4E3936]/60">
                                Belum ada data bahan baku.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-[#828854]/40 px-4 py-3">
            {{ $ingredients->links() }}
        </div>
    </div>

    <p class="text-sm text-[#4E3936]/60">
        Jika sinkronisasi gagal, aplikasi tetap menggunakan harga supplier terakhir yang tersimpan.
    </p>

</div>
@endsection
