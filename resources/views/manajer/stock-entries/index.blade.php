@extends('layouts.manajer')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <p class="text-sm font-semibold uppercase tracking-wider text-[#8A776B]">
            Manajemen Persediaan
        </p>
        <h1 class="mt-2 text-3xl font-bold text-[#493225]">
            Stok Masuk
        </h1>
        <p class="mt-2 text-sm text-[#684936]">
            Catat penerimaan bahan baku dan pantau riwayat stok masuk.
        </p>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="border border-[#D8C9BE] bg-[#F5F1ED] px-4 py-3 text-sm text-[#493225]">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700">
            <p class="font-semibold">Periksa kembali data berikut:</p>
            <ul class="mt-2 list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Stok Masuk --}}
    <div class="border border-[#D8C9BE] bg-white">
        <div class="border-b border-[#D8C9BE] px-6 py-5">
            <h2 class="text-lg font-bold text-[#493225]">
                Catat Stok Masuk Baru
            </h2>
            <p class="mt-1 text-sm text-[#684936]">
                Masukkan informasi bahan baku yang baru diterima.
            </p>
        </div>

        <div class="p-6">
            @if ($ingredients->isEmpty())
                <div class="border border-yellow-300 bg-yellow-50 px-4 py-3 text-sm text-yellow-800">
                    Belum ada bahan baku aktif. Tambahkan atau aktifkan bahan baku terlebih dahulu.
                </div>
            @else
                <form
                    action="{{ route('manajer.stock-entries.store') }}"
                    method="POST"
                    class="space-y-5"
                >
                    @csrf

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        {{-- Bahan Baku --}}
                        <div>
                            <label for="ingredient_id" class="mb-2 block text-sm font-semibold text-[#493225]">
                                Bahan Baku
                            </label>
                            <select
                                name="ingredient_id"
                                id="ingredient_id"
                                required
                                class="w-full border border-[#D8C9BE] bg-white px-4 py-3 text-sm text-[#493225] focus:border-[#493225] focus:ring-1 focus:ring-[#493225]"
                            >
                                <option value="">Pilih bahan baku</option>
                                @foreach ($ingredients as $ingredient)
                                    <option
                                        value="{{ $ingredient->id }}"
                                        data-unit="{{ $ingredient->base_unit }}"
                                        @selected(old('ingredient_id') == $ingredient->id)
                                    >
                                        {{ $ingredient->name }} ({{ $ingredient->base_unit }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Jumlah --}}
                        <div>
                            <label for="quantity" class="mb-2 block text-sm font-semibold text-[#493225]">
                                Jumlah Masuk
                            </label>
                            <div class="flex">
                                <input
                                    type="number"
                                    name="quantity"
                                    id="quantity"
                                    value="{{ old('quantity') }}"
                                    min="0.001"
                                    step="0.001"
                                    placeholder="Contoh: 500"
                                    required
                                    class="w-full min-w-0 border border-[#D8C9BE] px-4 py-3 text-sm focus:border-[#493225] focus:ring-1 focus:ring-[#493225]"
                                >
                                <span
                                    id="unit-label"
                                    class="flex min-w-16 items-center justify-center border border-l-0 border-[#D8C9BE] bg-[#F5F1ED] px-3 text-sm font-semibold text-[#493225]"
                                >
                                    Satuan
                                </span>
                            </div>
                        </div>

                        {{-- Harga --}}
                        <div>
                            <label for="unit_price" class="mb-2 block text-sm font-semibold text-[#493225]">
                                Harga per Satuan (Rp)
                            </label>
                            <input
                                type="number"
                                name="unit_price"
                                id="unit_price"
                                value="{{ old('unit_price') }}"
                                min="0"
                                step="0.01"
                                placeholder="Contoh: 15000"
                                required
                                class="w-full border border-[#D8C9BE] bg-[#E8F0FE] px-4 py-3 text-sm focus:border-[#493225] focus:ring-1 focus:ring-[#493225]"
                            >
                        </div>

                        {{-- Supplier --}}
                        <div>
                            <label for="supplier_name" class="mb-2 block text-sm font-semibold text-[#493225]">
                                Nama Supplier
                            </label>
                            <input
                                type="text"
                                name="supplier_name"
                                id="supplier_name"
                                value="{{ old('supplier_name') }}"
                                maxlength="150"
                                placeholder="Nama pemasok (opsional)"
                                class="w-full border border-[#D8C9BE] bg-[#E8F0FE] px-4 py-3 text-sm focus:border-[#493225] focus:ring-1 focus:ring-[#493225]"
                            >
                        </div>

                        {{-- Tanggal --}}
                        <div>
                            <label for="entry_date" class="mb-2 block text-sm font-semibold text-[#493225]">
                                Tanggal Masuk
                            </label>
                            <input
                                type="date"
                                name="entry_date"
                                id="entry_date"
                                value="{{ old('entry_date', now()->format('Y-m-d')) }}"
                                required
                                class="w-full border border-[#D8C9BE] bg-[#E8F0FE] px-4 py-3 text-sm focus:border-[#493225] focus:ring-1 focus:ring-[#493225]"
                            >
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label for="notes" class="mb-2 block text-sm font-semibold text-[#493225]">
                            Catatan
                        </label>
                        <textarea
                            name="notes"
                            id="notes"
                            rows="3"
                            placeholder="Catatan tambahan (opsional)"
                            class="w-full border border-[#D8C9BE] bg-[#E8F0FE] px-4 py-3 text-sm focus:border-[#493225] focus:ring-1 focus:ring-[#493225]"
                        >{{ old('notes') }}</textarea>
                    </div>

                    {{-- Total Harga --}}
                    <div class="border border-[#D8C9BE] bg-[#F5F1ED] p-5">
                        <p class="text-sm font-medium text-[#684936]">
                            Perkiraan Total Harga
                        </p>
                        <p id="total-preview" class="mt-2 text-2xl font-bold text-[#493225]">
                            Rp 0
                        </p>
                        <p class="mt-1 text-xs text-[#8A776B]">
                            Total dihitung otomatis berdasarkan jumlah dan harga per satuan.
                        </p>
                    </div>

                    <div>
                        <button
                            type="submit"
                            class="border border-[#493225] bg-[#493225] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#684936]"
                        >
                            + Simpan Stok Masuk
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    {{-- Riwayat Stok Masuk --}}
    <div class="border border-[#D8C9BE] bg-white">

        <div class="border-b border-[#D8C9BE] px-6 py-5">
            <h2 class="text-lg font-bold text-[#493225]">
                Riwayat Stok Masuk
            </h2>
            <p class="mt-1 text-sm text-[#684936]">
                Daftar penerimaan bahan baku yang sudah dicatat.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[950px] text-left text-sm">
                <thead class="bg-[#F5F1ED] text-[#493225]">
                    <tr>
                        <th class="px-5 py-4 font-semibold">No.</th>
                        <th class="px-5 py-4 font-semibold">Tanggal</th>
                        <th class="px-5 py-4 font-semibold">Bahan Baku</th>
                        <th class="px-5 py-4 font-semibold">Jumlah</th>
                        <th class="px-5 py-4 font-semibold">Harga Satuan</th>
                        <th class="px-5 py-4 font-semibold">Total Harga</th>
                        <th class="px-5 py-4 font-semibold">Supplier</th>
                        <th class="px-5 py-4 font-semibold">Dicatat Oleh</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($stockEntries as $entry)
                        <tr class="transition hover:bg-[#F5F1ED]">
                            <td class="px-5 py-4 text-[#684936]">
                                {{ $stockEntries->firstItem() + $loop->index }}
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap text-[#493225]">
                                {{ $entry->entry_date->format('d/m/Y') }}
                            </td>

                            <td class="px-5 py-4 font-semibold text-[#493225]">
                                {{ $entry->ingredient->name }}
                            </td>

                            <td class="px-5 py-4 text-[#493225]">
                                {{ number_format((float) $entry->quantity, 3, ',', '.') }}
                                {{ $entry->ingredient->base_unit }}
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap text-[#493225]">
                                Rp {{ number_format((float) $entry->unit_price, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap font-semibold text-[#493225]">
                                Rp {{ number_format((float) $entry->total_price, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-4 text-[#493225]">
                                {{ $entry->supplier_name ?: '-' }}
                            </td>

                            <td class="px-5 py-4 text-[#493225]">
                                {{ $entry->enteredBy->name ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <p class="font-semibold text-[#493225]">
                                    Belum ada riwayat stok masuk.
                                </p>
                                <p class="mt-1 text-sm text-[#8A776B]">
                                    Catatan stok masuk akan muncul di sini setelah disimpan.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($stockEntries->hasPages())
            <div class="border-t border-[#D8C9BE] px-6 py-4">
                {{ $stockEntries->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Perhitungan Total dan Satuan --}}
<script>
    const quantityInput = document.getElementById('quantity');
    const priceInput = document.getElementById('unit_price');
    const ingredientSelect = document.getElementById('ingredient_id');
    const totalPreview = document.getElementById('total-preview');
    const unitLabel = document.getElementById('unit-label');

    function updateTotal() {
        if (!totalPreview) return;

        const quantity = parseFloat(quantityInput?.value) || 0;
        const price = parseFloat(priceInput?.value) || 0;
        const total = quantity * price;

        totalPreview.textContent = 'Rp ' + new Intl.NumberFormat('id-ID', {
            maximumFractionDigits: 2
        }).format(total);
    }

    function updateUnit() {
        if (!unitLabel) return;

        const selectedOption = ingredientSelect?.selectedOptions[0];
        unitLabel.textContent = selectedOption?.dataset.unit || 'Satuan';
    }

    quantityInput?.addEventListener('input', updateTotal);
    priceInput?.addEventListener('input', updateTotal);
    ingredientSelect?.addEventListener('change', updateUnit);

    updateTotal();
    updateUnit();
</script>
@endsection

