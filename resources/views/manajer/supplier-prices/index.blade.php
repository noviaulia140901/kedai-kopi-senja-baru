@extends('layouts.manajer')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Harga Supplier</h1>
            <p class="mt-1 text-sm text-gray-500">
                Informasi harga bahan baku dari layanan supplier.
            </p>
        </div>

        <form action="{{ route('manajer.supplier-prices.sync') }}" method="POST">
            @csrf
            <button type="submit"
                style="background-color: #2563eb; color: white; padding: 10px 16px; border-radius: 8px; font-weight: 600;">
            Sinkronisasi Harga
        </button>
        </form>
    </div>

    @if (session('success'))
        <div class="rounded-lg bg-green-100 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-lg bg-red-100 p-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">
                            No.
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">
                            Nama Bahan
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">
                            Kode Supplier
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">
                            Satuan Dasar
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">
                            Harga per Satuan
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">
                            Diperbarui Supplier
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">
                            Terakhir Diambil
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($ingredients as $ingredient)
                        @php
                            $price = $ingredient->prices->first();
                        @endphp

                        <tr>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600">
                                {{ $ingredients->firstItem() + $loop->index }}
                            </td>

                            <td class="px-4 py-3 text-sm font-medium text-gray-800">
                                {{ $ingredient->name }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600">
                                {{ $price?->supplier_code ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600">
                                {{ $ingredient->base_unit }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-800">
                                @if ($price)
                                    Rp {{ number_format((float) $price->price_per_unit, 2, ',', '.') }}
                                @else
                                    Belum tersedia
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600">
                                {{ $price?->supplier_updated_at?->format('d/m/Y') ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600">
                                {{ $price?->fetched_at?->format('d/m/Y H:i') ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">
                                Belum ada data bahan baku.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-200 px-4 py-3">
            {{ $ingredients->links() }}
        </div>
    </div>

    <p class="text-sm text-gray-500">
        Jika sinkronisasi gagal, aplikasi tetap menggunakan harga supplier terakhir yang tersimpan.
    </p>

</div>
@endsection
