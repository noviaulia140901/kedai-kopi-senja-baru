
@extends('layouts.manajer')

@section('content')
<style>
    body:has(.manager-menus-page) main {
        background-color: #F1ECD8;
    }

    .manager-menus-page {
        color: #4E3936;
    }

    /* BANNER */
    .manager-menus-page .page-banner {
        background-color: #465A3D;
        color: #F1ECD8;
        border-radius: 30px;
        padding: 32px 28px;
    }

    .manager-menus-page .page-banner .banner-label {
        font-size: 12px !important;
        font-weight: 700 !important;
        letter-spacing: 0.1em;
    }

    .manager-menus-page .page-banner h2 {
        margin-top: 12px !important;
        font-size: 24px !important;
        font-weight: 700 !important;
        line-height: 1.3 !important;
    }

    .manager-menus-page .page-banner p:not(.banner-label) {
        margin-top: 4px;
        font-size: 14px !important;
        font-weight: 400 !important;
        line-height: 1.5 !important;
    }

    /* PANEL */
    .manager-menus-page .panel {
        background-color: #F1ECD8;
        border-color: #828854;
        border-radius: 24px;
    }

    /* JUDUL BAGIAN */
    .manager-menus-page h3 {
        font-size: 18px;
        font-weight: 700;
    }

    .manager-menus-page .section-description {
        font-size: 14px;
        font-weight: 400;
    }

    /* INPUT */
    .manager-menus-page .field-input {
        background-color: #fffdf5;
        border-color: #828854;
        color: #4E3936;
        font-size: 14px;
        font-weight: 400;
    }

    .manager-menus-page .field-input:focus {
        border-color: #465A3D;
        --tw-ring-color: rgb(70 90 61 / 0.2);
    }

    /* LABEL */
    .manager-menus-page label {
        font-size: 14px;
        font-weight: 700;
    }

    /* TOMBOL */
    .manager-menus-page .primary-button {
        background-color: #465A3D;
        color: #F1ECD8;
        font-size: 14px;
        font-weight: 700;
    }

    .manager-menus-page .primary-button:hover {
        background-color: #35472F;
    }

    /* TABEL */
    .manager-menus-page .table-heading {
        background-color: #465A3D;
        color: #F1ECD8;
    }

    .manager-menus-page .table-heading th {
        font-size: 14px;
        font-weight: 700;
    }

    .manager-menus-page .table-row:nth-child(even) {
        background-color: rgb(130 136 84 / 0.10);
    }

    .manager-menus-page .table-row:hover {
        background-color: rgb(130 136 84 / 0.16);
    }

    .manager-menus-page .soft-divider {
        border-color: rgb(130 136 84 / 0.4);
    }
</style>

<div class="manager-menus-page w-full space-y-6">

    {{-- JUDUL HALAMAN --}}
    <section class="page-banner w-full rounded-[30px]">
        <p class="banner-label uppercase text-[#F1ECD8]/90">
            MANAJEMEN PRODUK
        </p>

        <h2>
            Kelola Menu
        </h2>

        <p class="text-[#F1ECD8]/90">
            Atur daftar makanan dan minuman Kedai Kopi Senja.
        </p>
    </section>

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="rounded-xl border border-[#828854] bg-[#828854]/15 px-5 py-4 text-sm font-semibold text-[#465A3D]">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-xl border border-[#640017]/30 bg-[#640017]/10 px-5 py-4 text-sm font-semibold text-[#640017]">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-[#640017]/30 bg-[#640017]/10 px-5 py-4 text-sm text-[#640017]">
            <p class="font-bold">Periksa kembali data berikut:</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM TAMBAH MENU --}}
    <section class="panel overflow-hidden border shadow-sm">

        <div class="border-b border-[#828854]/40 px-6 py-5">
            <h3 class="text-lg font-bold text-[#4E3936]">
                Tambah Menu Baru
            </h3>

            <p class="section-description mt-1 text-[#4E3936]/70">
                Masukkan informasi menu yang akan dijual.
            </p>
        </div>

        <form
            action="{{ route('manajer.menus.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-5 p-6"
        >
            @csrf

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                {{-- NAMA MENU --}}
                <div>
                    <label for="name" class="mb-2 block text-[#4E3936]">
                        Nama Menu
                    </label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Kopi Susu Senja"
                        required
                        maxlength="150"
                        class="field-input w-full rounded-xl border px-4 py-3 placeholder:text-[#4E3936]/40 focus:outline-none focus:ring-2"
                    >
                </div>

                {{-- KATEGORI --}}
                <div>
                    <label for="category_id" class="mb-2 block text-[#4E3936]">
                        Kategori
                    </label>

                    <select
                        name="category_id"
                        id="category_id"
                        required
                        class="field-input w-full rounded-xl border px-4 py-3 focus:outline-none focus:ring-2"
                    >
                        <option value="">Pilih kategori</option>

                        @foreach ($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                @selected(old('category_id') == $category->id)
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- HARGA JUAL --}}
                <div>
                    <label for="selling_price" class="mb-2 block text-[#4E3936]">
                        Harga Jual (Rp)
                    </label>

                    <input
                        type="number"
                        name="selling_price"
                        id="selling_price"
                        value="{{ old('selling_price') }}"
                        placeholder="Contoh: 18000"
                        min="0"
                        step="0.01"
                        required
                        class="field-input w-full rounded-xl border px-4 py-3 focus:outline-none focus:ring-2"
                    >
                </div>

                {{-- GAMBAR MENU --}}
                <div>
                    <label for="image" class="mb-2 block text-[#4E3936]">
                        Gambar Menu (Opsional)
                    </label>

                    <input
                        type="file"
                        name="image"
                        id="image"
                        accept="image/*"
                        class="field-input w-full rounded-xl border px-4 py-3"
                    >

                    <p class="mt-1 text-xs text-[#4E3936]/60">
                        Format gambar maksimal 2 MB.
                    </p>
                </div>

                {{-- STATUS MENU --}}
                <div>
                    <label for="is_active" class="mb-2 block text-[#4E3936]">
                        Status Menu
                    </label>

                    <select
                        name="is_active"
                        id="is_active"
                        required
                        class="field-input w-full rounded-xl border px-4 py-3 focus:outline-none focus:ring-2"
                    >
                        <option value="1" @selected(old('is_active', '1') == '1')>
                            Aktif
                        </option>

                        <option value="0" @selected(old('is_active') == '0')>
                            Nonaktif
                        </option>
                    </select>
                </div>

                {{-- DESKRIPSI --}}
                <div>
                    <label for="description" class="mb-2 block text-[#4E3936]">
                        Deskripsi Menu
                    </label>

                    <textarea
                        name="description"
                        id="description"
                        rows="3"
                        placeholder="Deskripsi singkat menu (opsional)"
                        class="field-input w-full rounded-xl border px-4 py-3 placeholder:text-[#4E3936]/40 focus:outline-none focus:ring-2"
                    >{{ old('description') }}</textarea>
                </div>

            </div>

            <div class="border-t border-[#828854]/40 pt-5">
                <button
                    type="submit"
                    class="primary-button inline-flex items-center justify-center rounded-xl px-6 py-3 transition-colors focus:outline-none focus:ring-2 focus:ring-[#828854]/40"
                >
                    + Tambah Menu
                </button>
            </div>
        </form>
    </section>

    {{-- DAFTAR MENU --}}
    <section class="panel w-full overflow-hidden border shadow-sm">

        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#828854]/40 px-6 py-5">
            <div>
                <h3 class="text-lg font-bold text-[#4E3936]">
                    Daftar Menu
                </h3>

                <p class="section-description mt-1 text-[#4E3936]/70">
                    Daftar makanan dan minuman yang terdaftar.
                </p>
            </div>

            <span class="rounded-full bg-[#465A3D] px-4 py-2 text-sm font-bold text-[#F1ECD8]">
                {{ $menus->total() }} Menu
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left">

                <thead class="table-heading">
                    <tr>
                        <th class="px-5 py-4">No.</th>
                        <th class="px-5 py-4">Menu</th>
                        <th class="px-5 py-4">Kategori</th>
                        <th class="px-5 py-4 text-right">Harga</th>
                        <th class="px-5 py-4 text-center">Status</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($menus as $menu)
                        <tr class="table-row border-b border-[#828854]/30 last:border-b-0">

                            {{-- NOMOR --}}
                            <td class="px-5 py-4 text-sm text-[#4E3936]/70">
                                {{ $menus->firstItem() + $loop->index }}
                            </td>

                            {{-- NAMA MENU --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($menu->image)
                                        <img
                                            src="{{ asset('storage/' . $menu->image) }}"
                                            alt="{{ $menu->name }}"
                                            class="h-14 w-14 rounded-xl border border-[#828854]/40 object-cover"
                                        >
                                    @else
                                        <div class="flex h-14 w-14 items-center justify-center rounded-xl border border-[#828854]/40 bg-[#828854]/10 text-xs font-bold text-[#4E3936]/60">
                                            MENU
                                        </div>
                                    @endif

                                    <div>
                                        <p class="text-sm font-bold text-[#4E3936]">
                                            {{ $menu->name }}
                                        </p>

                                        <p class="mt-1 max-w-xs text-xs text-[#4E3936]/70">
                                            {{ $menu->description ?: 'Tidak ada deskripsi' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- KATEGORI --}}
                            <td class="px-5 py-4 text-sm text-[#4E3936]">
                                {{ $menu->category->name ?? '-' }}
                            </td>

                            {{-- HARGA --}}
                            <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-semibold text-[#4E3936]">
                                Rp {{ number_format((float) $menu->selling_price, 0, ',', '.') }}
                            </td>

                            {{-- STATUS --}}
                            <td class="px-5 py-4 text-center">
                                @if ($menu->is_active)
                                    <span class="inline-flex rounded-full bg-[#465A3D]/15 px-3 py-1 text-xs font-bold text-[#465A3D]">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-[#4E3936]/10 px-3 py-1 text-xs font-bold text-[#4E3936]">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="px-5 py-4 text-center">
                                <button
                                    type="button"
                                    onclick="document.getElementById('edit-menu-{{ $menu->id }}').classList.toggle('hidden')"
                                    class="rounded-lg border border-[#828854] bg-[#F1ECD8] px-4 py-2 text-xs font-bold text-[#465A3D] transition-colors hover:bg-[#828854]/15"
                                >
                                    Edit
                                </button>
                            </td>

                        </tr>

                        {{-- FORM EDIT MENU --}}
                        <tr id="edit-menu-{{ $menu->id }}" class="hidden bg-[#828854]/10">
                            <td colspan="6" class="border-y border-[#828854]/40 p-5">

                                <form
                                    action="{{ route('manajer.menus.update', $menu) }}"
                                    method="POST"
                                    enctype="multipart/form-data"
                                    class="space-y-5"
                                >
                                    @csrf
                                    @method('PUT')

                                    <div>
                                        <h3 class="text-lg font-bold text-[#4E3936]">
                                            Edit Menu: {{ $menu->name }}
                                        </h3>

                                        <p class="section-description mt-1 text-[#4E3936]/70">
                                            Perbarui informasi menu di bawah ini.
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                                        {{-- NAMA MENU --}}
                                        <div>
                                            <label class="mb-2 block text-[#4E3936]">
                                                Nama Menu
                                            </label>

                                            <input
                                                type="text"
                                                name="name"
                                                value="{{ $menu->name }}"
                                                required
                                                maxlength="150"
                                                class="field-input w-full rounded-xl border px-4 py-3"
                                            >
                                        </div>

                                        {{-- KATEGORI --}}
                                        <div>
                                            <label class="mb-2 block text-[#4E3936]">
                                                Kategori
                                            </label>

                                            <select
                                                name="category_id"
                                                required
                                                class="field-input w-full rounded-xl border px-4 py-3"
                                            >
                                                @foreach ($categories as $category)
                                                    <option
                                                        value="{{ $category->id }}"
                                                        @selected($menu->category_id == $category->id)
                                                    >
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- HARGA JUAL --}}
                                        <div>
                                            <label class="mb-2 block text-[#4E3936]">
                                                Harga Jual (Rp)
                                            </label>

                                            <input
                                                type="number"
                                                name="selling_price"
                                                value="{{ $menu->selling_price }}"
                                                min="0"
                                                step="0.01"
                                                required
                                                class="field-input w-full rounded-xl border px-4 py-3"
                                            >
                                        </div>

                                        {{-- GAMBAR --}}
                                        <div>
                                            <label class="mb-2 block text-[#4E3936]">
                                                Ganti Gambar (Opsional)
                                            </label>

                                            <input
                                                type="file"
                                                name="image"
                                                accept="image/*"
                                                class="field-input w-full rounded-xl border px-4 py-3"
                                            >
                                        </div>

                                        {{-- STATUS --}}
                                        <div>
                                            <label class="mb-2 block text-[#4E3936]">
                                                Status
                                            </label>

                                            <select
                                                name="is_active"
                                                required
                                                class="field-input w-full rounded-xl border px-4 py-3"
                                            >
                                                <option value="1" @selected($menu->is_active)>
                                                    Aktif
                                                </option>

                                                <option value="0" @selected(!$menu->is_active)>
                                                    Nonaktif
                                                </option>
                                            </select>
                                        </div>

                                        {{-- DESKRIPSI --}}
                                        <div>
                                            <label class="mb-2 block text-[#4E3936]">
                                                Deskripsi
                                            </label>

                                            <textarea
                                                name="description"
                                                rows="3"
                                                class="field-input w-full rounded-xl border px-4 py-3"
                                            >{{ $menu->description }}</textarea>
                                        </div>

                                    </div>

                                    <div class="flex flex-wrap gap-2 border-t border-[#828854]/40 pt-5">

                                        <button
                                            type="submit"
                                            class="primary-button rounded-xl px-5 py-3 transition-colors"
                                        >
                                            Simpan Perubahan
                                        </button>

                                        <button
                                            type="button"
                                            onclick="document.getElementById('edit-menu-{{ $menu->id }}').classList.add('hidden')"
                                            class="rounded-xl border border-[#828854] bg-[#F1ECD8] px-5 py-3 text-sm font-bold text-[#465A3D] transition-colors hover:bg-[#828854]/15"
                                        >
                                            Batal
                                        </button>

                                    </div>
                                </form>

                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <p class="text-sm font-bold text-[#4E3936]">
                                    Belum ada menu.
                                </p>

                                <p class="mt-1 text-sm text-[#4E3936]/70">
                                    Tambahkan menu pertama melalui formulir di atas.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($menus->hasPages())
            <div class="border-t border-[#828854]/40 px-5 py-4">
                {{ $menus->links() }}
            </div>
        @endif

    </section>

</div>
@endsection
