
@extends('layouts.manajer')

@section('content')
<style>
    body:has(.manager-stock-entry-page) main {
        background-color: #F1ECD8;
    }

    .manager-stock-entry-page {
        color: #4E3936;
    }

    .manager-stock-entry-page .page-banner {
        background: #465A3D;
        color: #F1ECD8;
        border-radius: 20px;
        padding: 28px;
    }

    .manager-stock-entry-page .panel {
        background: #F1ECD8;
        border: 1px solid rgb(130 136 84 / 40%);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgb(78 57 54 / 5%);
    }

    .manager-stock-entry-page .panel-header {
        background: #F1ECD8;
        border-bottom: 1px solid rgb(130 136 84 / 40%);
        padding: 20px 24px;
    }

    .manager-stock-entry-page .field-input {
        width: 100%;
        border: 1px solid #828854;
        border-radius: 12px;
        background: #fffdf5;
        padding: 12px 16px;
        color: #4E3936;
        font-size: 14px;
    }

    .manager-stock-entry-page .field-input:focus {
        outline: none;
        border-color: #465A3D;
        box-shadow: 0 0 0 2px rgb(70 90 61 / 12%);
    }

    .manager-stock-entry-page .primary-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #465A3D;
        border-radius: 12px;
        background: #465A3D;
        color: #F1ECD8;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 700;
        transition: background .2s;
    }

    .manager-stock-entry-page .primary-button:hover {
        background: #35472F;
    }

    .manager-stock-entry-page .table-heading {
        background: #465A3D;
        color: #F1ECD8;
    }

    .manager-stock-entry-page .table-row:nth-child(even) {
        background: rgb(130 136 84 / 10%);
    }

    .manager-stock-entry-page .table-row:hover {
        background: rgb(130 136 84 / 16%);
    }

    .manager-stock-entry-page .notice-success {
        border: 1px solid rgb(70 90 61 / 35%);
        border-radius: 12px;
        background: #F1ECD8;
        padding: 14px 16px;
        color: #465A3D;
    }

    .manager-stock-entry-page .notice-error {
        border: 1px solid rgb(100 0 23 / 30%);
        border-radius: 12px;
        background: #F1ECD8;
        padding: 14px 16px;
        color: #640017;
    }

    .manager-stock-entry-page .unit-label {
        display: flex;
        min-width: 90px;
        align-items: center;
        justify-content: center;
        border: 1px solid #828854;
        border-left: 0;
        border-radius: 0 12px 12px 0;
        background: #E8E4CE;
        padding: 12px;
        color: #4E3936;
        font-size: 14px;
        font-weight: 700;
    }

    .manager-stock-entry-page .quantity-input {
        border-radius: 12px 0 0 12px;
    }

    .manager-stock-entry-page .total-box {
        border: 1px solid rgb(130 136 84 / 40%);
        border-radius: 12px;
        background: rgb(130 136 84 / 10%);
        padding: 20px;
    }
</style>

<div class="manager-stock-entry-page space-y-6">

    {{-- JUDUL HALAMAN --}}
    <section class="page-banner">
        <p class="text-xs font-bold uppercase tracking-widest text-[#EFE8C7]">
            MANAJEMEN PERSEDIAAN
        </p>

        <div class="mt-2">
            <h1 class="text-2xl font-bold">
                Stok Masuk
            </h1>

            <p class="mt-1 text-sm text-[#F1ECD8]/80">
                Catat penerimaan bahan baku dan pantau riwayat stok masuk.
            </p>
        </div>
    </section>

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="notice-success text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="notice-error text-sm font-semibold">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="notice-error text-sm">
            <p class="mb-2 font-bold">Periksa kembali data berikut:</p>

            <ul class="list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM STOK MASUK --}}
    <section class="panel">

        <div class="panel-header">
            <h2 class="text-lg font-bold text-[#4E3936]">
                Catat Stok Masuk Baru
            </h2>

            <p class="mt-1 text-sm text-[#4E3936]/70">
                Masukkan informasi bahan baku yang baru diterima.
            </p>
        </div>

        <div class="space-y-5 p-5 md:p-6">

            @if ($ingredients->isEmpty())
                <div class="notice-success text-sm">
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

                        {{-- BAHAN BAKU --}}
                        <div>
                            <label for="ingredient_id" class="mb-2 block text-sm font-semibold text-[#4E3936]">
                                Bahan Baku
                            </label>

                            <select
                                name="ingredient_id"
                                id="ingredient_id"
                                required
                                class="field-input"
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

                        {{-- JUMLAH MASUK --}}
                        <div>
                            <label for="quantity" class="mb-2 block text-sm font-semibold text-[#4E3936]">
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
                                    class="field-input quantity-input min-w-0"
                                >

                                <span id="unit-label" class="unit-label">
                                    Satuan
                                </span>
                            </div>
                        </div>

                        {{-- HARGA SATUAN --}}
                        <div>
                            <label for="unit_price" class="mb-2 block text-sm font-semibold text-[#4E3936]">
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
                                class="field-input"
                            >
                        </div>

                        {{-- SUPPLIER --}}
                        <div>
                            <label for="supplier_name" class="mb-2 block text-sm font-semibold text-[#4E3936]">
                                Nama Supplier
                            </label>

                            <input
                                type="text"
                                name="supplier_name"
                                id="supplier_name"
                                value="{{ old('supplier_name') }}"
                                maxlength="150"
                                placeholder="Nama pemasok (opsional)"
                                class="field-input"
                            >
                        </div>

                        {{-- TANGGAL MASUK --}}
                        <div>
                            <label for="entry_date" class="mb-2 block text-sm font-semibold text-[#4E3936]">
                                Tanggal Masuk
                            </label>

                            <input
                                type="date"
                                name="entry_date"
                                id="entry_date"
                                value="{{ old('entry_date', now()->format('Y-m-d')) }}"
                                required
                                class="field-input"
                            >
                        </div>

                    </div>

                    {{-- CATATAN --}}
                    <div>
                        <label for="notes" class="mb-2 block text-sm font-semibold text-[#4E3936]">
                            Catatan
                        </label>

                        <textarea
                            name="notes"
                            id="notes"
                            rows="3"
                            placeholder="Catatan tambahan (opsional)"
                            class="field-input"
                        >{{ old('notes') }}</textarea>
                    </div>

                    {{-- TOTAL HARGA --}}
                    <div class="total-box">
                        <p class="text-sm font-semibold text-[#4E3936]/70">
                            Perkiraan Total Harga
                        </p>

                        <p id="total-preview" class="mt-2 text-2xl font-bold text-[#465A3D]">
                            Rp 0
                        </p>

                        <p class="mt-1 text-xs leading-relaxed text-[#4E3936]/60">
                            Total dihitung otomatis berdasarkan jumlah dan harga per satuan.
                        </p>
                    </div>

                    {{-- TOMBOL SIMPAN --}}
                    <div class="border-t border-[#828854]/40 pt-4">
                        <button type="submit" class="primary-button">
                            + Simpan Stok Masuk
                        </button>
                    </div>

                </form>

            @endif

        </div>
    </section>

    {{-- RIWAYAT STOK MASUK --}}
    <section class="panel">

        <div class="panel-header">
            <h2 class="text-lg font-bold text-[#4E3936]">
                Riwayat Stok Masuk
            </h2>

            <p class="mt-1 text-sm text-[#4E3936]/70">
                Daftar penerimaan bahan baku yang sudah dicatat.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[950px] text-left text-sm">

                <thead class="table-heading">
                    <tr>
                        <th class="px-5 py-4 font-bold">No.</th>
                        <th class="px-5 py-4 font-bold">Tanggal</th>
                        <th class="px-5 py-4 font-bold">Bahan Baku</th>
                        <th class="px-5 py-4 font-bold">Jumlah</th>
                        <th class="px-5 py-4 font-bold">Harga Satuan</th>
                        <th class="px-5 py-4 font-bold">Total Harga</th>
                        <th class="px-5 py-4 font-bold">Supplier</th>
                        <th class="px-5 py-4 font-bold">Dicatat Oleh</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#828854]/30">

                    @forelse ($stockEntries as $entry)
                        <tr class="table-row text-[#4E3936] transition">

                            <td class="px-5 py-4">
                                {{ $stockEntries->firstItem() + $loop->index }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                {{ $entry->entry_date->format('d/m/Y') }}
                            </td>

                            <td class="px-5 py-4 font-semibold">
                                {{ $entry->ingredient->name }}
                            </td>

                            <td class="px-5 py-4">
                                {{ rtrim(rtrim(number_format((float) $entry->quantity, 3, ',', '.'), '0'), ',') }}
                                {{ $entry->ingredient->base_unit }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                Rp {{ number_format((float) $entry->unit_price, 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 font-bold">
                                Rp {{ number_format((float) $entry->total_price, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $entry->supplier_name ?: '-' }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $entry->enteredBy->name ?? '-' }}
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <p class="font-bold text-[#4E3936]">
                                    Belum ada riwayat stok masuk.
                                </p>

                                <p class="mt-1 text-sm text-[#4E3936]/60">
                                    Catatan stok masuk akan muncul di sini setelah disimpan.
                                </p>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($stockEntries->hasPages())
            <div class="border-t border-[#828854]/40 px-6 py-4">
                {{ $stockEntries->links() }}
            </div>
        @endif

    </section>

</div>

{{-- PERHITUNGAN TOTAL DAN SATUAN --}}
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
