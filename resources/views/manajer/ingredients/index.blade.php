
@extends('layouts.manajer')

@section('content')
<style>
    body:has(.manager-ingredients-page) main {
        background-color: #F1ECD8;
    }

    .manager-ingredients-page {
        color: #4E3936;
    }

    /* BANNER HALAMAN */
    .manager-ingredients-page .page-banner {
        background-color: #465A3D;
        color: #F1ECD8;
        border-radius: 30px !important;
        overflow: hidden;
        width: 100%;
        padding: 36px 40px !important;
        box-sizing: border-box;
    }

    .manager-ingredients-page .page-banner .banner-label {
        display: block;
        color: #F1ECD8;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
        line-height: 1.5;
    }

    /* Judul disamakan dengan Kelola Resep */
    .manager-ingredients-page .page-banner h2 {
        margin-top: 12px !important;
        color: #F1ECD8 !important;
        font-size: 24px !important;
        font-weight: 700 !important;
        line-height: 1.5 !important;
    }

    .manager-ingredients-page .page-banner .banner-description {
        margin-top: 6px !important;
        color: rgb(241 236 216 / 90%);
        font-size: 14px !important;
        font-weight: 400 !important;
        line-height: 1.5;
    }

    .manager-ingredients-page .panel {
        background-color: #F1ECD8;
        border-color: #828854;
    }

    .manager-ingredients-page .field-input {
        background-color: #fffdf5;
        border-color: #828854;
        color: #4E3936;
    }

    .manager-ingredients-page .field-input:focus {
        border-color: #465A3D;
        --tw-ring-color: rgb(70 90 61 / 0.2);
    }

    .manager-ingredients-page .primary-button {
        background-color: #465A3D;
        color: #F1ECD8;
    }

    .manager-ingredients-page .primary-button:hover {
        background-color: #35472F;
    }

    .manager-ingredients-page .table-heading {
        background-color: #465A3D;
        color: #F1ECD8;
    }

    .manager-ingredients-page .table-row:nth-child(even) {
        background-color: rgb(130 136 84 / 0.10);
    }

    .manager-ingredients-page .table-row:hover {
        background-color: rgb(130 136 84 / 0.16);
    }

    .manager-ingredients-page .soft-divider {
        border-color: rgb(130 136 84 / 0.4);
    }
</style>

<div class="manager-ingredients-page w-full space-y-6">

    {{-- JUDUL HALAMAN --}}
    <section class="page-banner">
        <p class="banner-label">
            DATA PERSEDIAAN
        </p>

        <h2>
            Kelola Bahan Baku
        </h2>

        <p class="banner-description">
            Kelola data bahan baku, jumlah stok, dan batas minimum persediaan.
        </p>
    </section>

    {{-- PESAN BERHASIL --}}
    @if (session('success'))
        <div class="rounded-xl border border-[#828854] bg-[#828854]/15 px-5 py-4 text-sm font-semibold text-[#465A3D]">
            {{ session('success') }}
        </div>
    @endif

    {{-- PESAN GAGAL --}}
    @if (session('error'))
        <div class="rounded-xl border border-[#640017]/30 bg-[#640017]/10 px-5 py-4 text-sm font-semibold text-[#640017]">
            {{ session('error') }}
        </div>
    @endif

    {{-- FORM TAMBAH BAHAN --}}
    <section class="panel overflow-hidden rounded-2xl border shadow-sm">

        <div class="border-b border-[#828854]/40 px-6 py-5">
            <h3 class="text-lg font-bold text-[#4E3936]">
                Tambah Bahan Baku
            </h3>

            <p class="mt-1 text-sm text-[#4E3936]/70">
                Masukkan informasi bahan baku baru.
            </p>
        </div>

        <form method="POST" action="{{ route('manajer.ingredients.store') }}" class="space-y-5 p-6">
            @csrf

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <div>
                    <label for="name" class="mb-2 block text-sm font-bold text-[#4E3936]">
                        Nama Bahan
                    </label>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        maxlength="150"
                        placeholder="Contoh: Biji kopi arabika"
                        class="field-input w-full rounded-xl border px-4 py-3 text-sm placeholder:text-[#4E3936]/40 focus:outline-none focus:ring-2"
                    >

                    @error('name')
                        <p class="mt-1 text-sm text-[#640017]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="base_unit" class="mb-2 block text-sm font-bold text-[#4E3936]">
                        Satuan
                    </label>

                    <select
                        id="base_unit"
                        name="base_unit"
                        required
                        class="field-input w-full rounded-xl border px-4 py-3 text-sm focus:outline-none focus:ring-2"
                    >
                        <option value="">Pilih satuan</option>
                        <option value="g" @selected(old('base_unit') === 'g')>Gram (g)</option>
                        <option value="ml" @selected(old('base_unit') === 'ml')>Mililiter (ml)</option>
                        <option value="pcs" @selected(old('base_unit') === 'pcs')>Pieces (pcs)</option>
                    </select>

                    @error('base_unit')
                        <p class="mt-1 text-sm text-[#640017]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="current_stock" class="mb-2 block text-sm font-bold text-[#4E3936]">
                        Stok Saat Ini
                    </label>

                    <input
                        id="current_stock"
                        type="number"
                        name="current_stock"
                        value="{{ old('current_stock', 0) }}"
                        min="0"
                        step="0.001"
                        required
                        class="field-input w-full rounded-xl border px-4 py-3 text-sm focus:outline-none focus:ring-2"
                    >

                    @error('current_stock')
                        <p class="mt-1 text-sm text-[#640017]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="minimum_stock" class="mb-2 block text-sm font-bold text-[#4E3936]">
                        Batas Minimum Stok
                    </label>

                    <input
                        id="minimum_stock"
                        type="number"
                        name="minimum_stock"
                        value="{{ old('minimum_stock', 0) }}"
                        min="0"
                        step="0.001"
                        required
                        class="field-input w-full rounded-xl border px-4 py-3 text-sm focus:outline-none focus:ring-2"
                    >

                    @error('minimum_stock')
                        <p class="mt-1 text-sm text-[#640017]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="mb-2 block text-sm font-bold text-[#4E3936]">
                        Deskripsi
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        placeholder="Keterangan bahan baku (opsional)"
                        class="field-input w-full rounded-xl border px-4 py-3 text-sm placeholder:text-[#4E3936]/40 focus:outline-none focus:ring-2"
                    >{{ old('description') }}</textarea>

                    @error('description')
                        <p class="mt-1 text-sm text-[#640017]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="is_active" class="mb-2 block text-sm font-bold text-[#4E3936]">
                        Status Bahan
                    </label>

                    <select
                        id="is_active"
                        name="is_active"
                        required
                        class="field-input w-full rounded-xl border px-4 py-3 text-sm focus:outline-none focus:ring-2"
                    >
                        <option value="1" @selected(old('is_active', '1') == '1')>Aktif</option>
                        <option value="0" @selected(old('is_active') == '0')>Tidak Aktif</option>
                    </select>

                    @error('is_active')
                        <p class="mt-1 text-sm text-[#640017]">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="border-t border-[#828854]/40 pt-5">
                <button
                    type="submit"
                    class="primary-button inline-flex items-center justify-center rounded-xl px-6 py-3 text-sm font-bold transition-colors focus:outline-none focus:ring-2 focus:ring-[#828854]/40"
                >
                    + Tambah Bahan
                </button>
            </div>
        </form>
    </section>

    {{-- DAFTAR BAHAN --}}
    <section id="daftar-batas-minimum" class="panel scroll-mt-5 w-full overflow-hidden rounded-2xl border shadow-sm">

        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#828854]/40 px-6 py-5">
            <div>
                <h3 class="text-lg font-bold text-[#4E3936]">
                    Daftar Bahan Baku
                </h3>

                <p class="mt-1 text-sm text-[#4E3936]/70">
                    Informasi stok dan batas minimum setiap bahan.
                </p>
            </div>

            <span class="rounded-full bg-[#465A3D] px-4 py-2 text-sm font-bold text-[#F1ECD8]">
                {{ $ingredients->total() }} Bahan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left">

                <thead class="table-heading">
                    <tr>
                        <th class="px-5 py-4 text-sm font-bold">Nama Bahan</th>
                        <th class="px-5 py-4 text-right text-sm font-bold">Stok</th>
                        <th class="px-5 py-4 text-right text-sm font-bold">Batas Minimum</th>
                        <th class="px-5 py-4 text-center text-sm font-bold">Status Stok</th>
                        <th class="px-5 py-4 text-center text-sm font-bold">Status Bahan</th>
                        <th class="px-5 py-4 text-center text-sm font-bold">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($ingredients as $ingredient)
                        <tr class="table-row border-b border-[#828854]/30 last:border-b-0">

                            <td class="px-5 py-4">
                                <p class="font-bold text-[#4E3936]">
                                    {{ $ingredient->name }}
                                </p>

                                <p class="mt-1 text-xs text-[#4E3936]/70">
                                    {{ $ingredient->description ?: 'Tidak ada deskripsi' }}
                                </p>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-semibold text-[#4E3936]">
                                {{ rtrim(rtrim(number_format((float) $ingredient->current_stock, 3, ',', '.'), '0'), ',') }}
                                {{ $ingredient->base_unit }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right text-sm text-[#4E3936]">
                                {{ rtrim(rtrim(number_format((float) $ingredient->minimum_stock, 3, ',', '.'), '0'), ',') }}
                                {{ $ingredient->base_unit }}
                            </td>

                            <td class="px-5 py-4 text-center">
                                @if ($ingredient->current_stock <= $ingredient->minimum_stock)
                                    <span class="inline-flex rounded-full bg-[#640017]/10 px-3 py-1 text-xs font-bold text-[#640017]">
                                        Menipis
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-[#465A3D]/15 px-3 py-1 text-xs font-bold text-[#465A3D]">
                                        Aman
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-center">
                                @if ($ingredient->is_active)
                                    <span class="inline-flex rounded-full bg-[#465A3D]/15 px-3 py-1 text-xs font-bold text-[#465A3D]">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-[#4E3936]/10 px-3 py-1 text-xs font-bold text-[#4E3936]">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-center">
                                <div class="flex flex-wrap justify-center gap-2">

                                    <details class="relative">
                                        <summary class="cursor-pointer list-none rounded-lg border border-[#828854] bg-[#F1ECD8] px-4 py-2 text-xs font-bold text-[#465A3D] transition-colors hover:bg-[#828854]/15">
                                            Edit
                                        </summary>

                                        <div class="absolute right-0 z-20 mt-2 w-[min(85vw,420px)] rounded-2xl border border-[#828854] bg-[#F1ECD8] p-5 text-left shadow-xl">

                                            <h4 class="mb-4 font-bold text-[#4E3936]">
                                                Edit {{ $ingredient->name }}
                                            </h4>

                                            <form
                                                method="POST"
                                                action="{{ route('manajer.ingredients.update', $ingredient) }}"
                                                class="space-y-3"
                                            >
                                                @csrf
                                                @method('PUT')

                                                <div>
                                                    <label class="mb-1 block text-xs font-bold text-[#4E3936]">
                                                        Nama Bahan
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="name"
                                                        value="{{ $ingredient->name }}"
                                                        required
                                                        maxlength="150"
                                                        class="field-input w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2"
                                                    >
                                                </div>

                                                <div>
                                                    <label class="mb-1 block text-xs font-bold text-[#4E3936]">
                                                        Satuan
                                                    </label>

                                                    <select
                                                        name="base_unit"
                                                        required
                                                        class="field-input w-full rounded-lg border px-3 py-2 text-sm"
                                                    >
                                                        <option value="g" @selected($ingredient->base_unit === 'g')>Gram (g)</option>
                                                        <option value="ml" @selected($ingredient->base_unit === 'ml')>Mililiter (ml)</option>
                                                        <option value="pcs" @selected($ingredient->base_unit === 'pcs')>Pieces (pcs)</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="mb-1 block text-xs font-bold text-[#4E3936]">
                                                        Stok Saat Ini
                                                    </label>

                                                    <input
                                                        type="number"
                                                        name="current_stock"
                                                        value="{{ $ingredient->current_stock }}"
                                                        min="0"
                                                        step="0.001"
                                                        required
                                                        class="field-input w-full rounded-lg border px-3 py-2 text-sm"
                                                    >
                                                </div>

                                                <div>
                                                    <label class="mb-1 block text-xs font-bold text-[#4E3936]">
                                                        Batas Minimum
                                                    </label>

                                                    <input
                                                        type="number"
                                                        name="minimum_stock"
                                                        value="{{ $ingredient->minimum_stock }}"
                                                        min="0"
                                                        step="0.001"
                                                        required
                                                        class="field-input w-full rounded-lg border px-3 py-2 text-sm"
                                                    >
                                                </div>

                                                <div>
                                                    <label class="mb-1 block text-xs font-bold text-[#4E3936]">
                                                        Deskripsi
                                                    </label>

                                                    <textarea
                                                        name="description"
                                                        rows="2"
                                                        class="field-input w-full rounded-lg border px-3 py-2 text-sm"
                                                    >{{ $ingredient->description }}</textarea>
                                                </div>

                                                <div>
                                                    <label class="mb-1 block text-xs font-bold text-[#4E3936]">
                                                        Status Bahan
                                                    </label>

                                                    <select
                                                        name="is_active"
                                                        required
                                                        class="field-input w-full rounded-lg border px-3 py-2 text-sm"
                                                    >
                                                        <option value="1" @selected($ingredient->is_active)>Aktif</option>
                                                        <option value="0" @selected(!$ingredient->is_active)>Tidak Aktif</option>
                                                    </select>
                                                </div>

                                                <button
                                                    type="submit"
                                                    class="primary-button w-full rounded-xl px-4 py-3 text-sm font-bold transition-colors"
                                                >
                                                    Simpan Perubahan
                                                </button>
                                            </form>
                                        </div>
                                    </details>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <p class="font-bold text-[#4E3936]">
                                    Belum ada bahan baku
                                </p>

                                <p class="mt-1 text-sm text-[#4E3936]/70">
                                    Tambahkan bahan baku melalui formulir di atas.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($ingredients->hasPages())
            <div class="border-t border-[#828854]/40 px-5 py-4">
                {{ $ingredients->links() }}
            </div>
        @endif

    </section>

</div>
@endsection
