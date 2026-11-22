
@extends('layouts.manajer')

@section('content')
<style>
    body:has(.manager-recipes-page) main {
        background-color: #F1ECD8;
    }

    .manager-recipes-page {
        color: #4E3936;
    }

    .manager-recipes-page .page-banner {
        background: #465A3D;
        color: #F1ECD8;
        border-radius: 20px;
        padding: 28px;
    }

    .manager-recipes-page .panel {
        background: #F1ECD8;
        border: 1px solid rgb(130 136 84 / 40%);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgb(78 57 54 / 5%);
    }

    .manager-recipes-page .panel-header {
        background: #F1ECD8;
        border-bottom: 1px solid rgb(130 136 84 / 40%);
        padding: 20px 24px;
    }

    .manager-recipes-page .field-input {
        width: 100%;
        border: 1px solid #828854;
        border-radius: 12px;
        background: #fffdf5;
        padding: 12px 16px;
        color: #4E3936;
        font-size: 14px;
    }

    .manager-recipes-page .field-input:focus {
        outline: none;
        border-color: #465A3D;
        box-shadow: 0 0 0 2px rgb(70 90 61 / 12%);
    }

    .manager-recipes-page .primary-button {
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

    .manager-recipes-page .primary-button:hover {
        background: #35472F;
    }

    .manager-recipes-page .secondary-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #465A3D;
        border-radius: 12px;
        background: #F1ECD8;
        color: #465A3D;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 600;
        transition: .2s;
    }

    .manager-recipes-page .secondary-button:hover {
        background: #465A3D;
        color: #F1ECD8;
    }

    .manager-recipes-page .danger-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #640017;
        border-radius: 12px;
        background: transparent;
        color: #640017;
        padding: 9px 14px;
        font-size: 13px;
        font-weight: 600;
        transition: .2s;
    }

    .manager-recipes-page .danger-button:hover {
        background: #640017;
        color: #F1ECD8;
    }

    .manager-recipes-page .edit-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #465A3D;
        border-radius: 12px;
        background: transparent;
        color: #465A3D;
        padding: 9px 14px;
        font-size: 13px;
        font-weight: 600;
        transition: .2s;
    }

    .manager-recipes-page .edit-button:hover {
        background: #465A3D;
        color: #F1ECD8;
    }

    .manager-recipes-page .table-heading {
        background: #465A3D;
        color: #F1ECD8;
    }

    .manager-recipes-page .table-row:nth-child(even) {
        background: rgb(130 136 84 / 10%);
    }

    .manager-recipes-page .table-row:hover {
        background: rgb(130 136 84 / 16%);
    }

    .manager-recipes-page .menu-card {
        display: block;
        border: 1px solid #828854;
        border-radius: 14px;
        background: #fffdf5;
        padding: 18px;
        color: #4E3936;
        transition: .2s;
    }

    .manager-recipes-page .menu-card:hover {
        border-color: #465A3D;
        background: rgb(130 136 84 / 10%);
    }

    .manager-recipes-page .notice-success {
        border: 1px solid rgb(70 90 61 / 35%);
        border-radius: 12px;
        background: #F1ECD8;
        padding: 14px 16px;
        color: #465A3D;
    }

    .manager-recipes-page .notice-error {
        border: 1px solid rgb(100 0 23 / 30%);
        border-radius: 12px;
        background: #F1ECD8;
        padding: 14px 16px;
        color: #640017;
    }
</style>

<div class="manager-recipes-page space-y-6">

    {{-- JUDUL HALAMAN --}}
    <section class="page-banner">
        <p class="text-xs font-bold uppercase tracking-widest text-[#EFE8C7]">
            DATA RESEP
        </p>
        <div class="mt-2 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold">Kelola Resep</h1>
                <p class="mt-1 text-sm text-[#F1ECD8]/80">
                    Pilih menu untuk melihat dan mengatur bahan resepnya.
                </p>
            </div>

            <div class="rounded-xl border border-[#F1ECD8]/30 bg-[#F1ECD8]/10 px-4 py-2 text-sm font-semibold text-[#F1ECD8]">
                {{ $menus->count() }} menu
            </div>
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

    @if ($selectedMenu)

        {{-- INFORMASI MENU TERPILIH --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-[#465A3D]">
                    Detail Resep
                </p>
                <h2 class="mt-1 text-xl font-bold text-[#4E3936]">
                    {{ $selectedMenu->name }}
                </h2>
            </div>

            <a
                href="{{ route('manajer.recipes.index') }}"
                class="secondary-button"
            >
                Kembali ke daftar menu
            </a>
        </div>

        {{-- FORM TAMBAH BAHAN --}}
        <section class="panel">
            <div class="panel-header">
                <h3 class="text-lg font-bold text-[#4E3936]">
                    Tambah Bahan Resep
                </h3>
                <p class="mt-1 text-sm text-[#4E3936]/70">
                    Tambahkan bahan dan jumlah pemakaiannya untuk {{ $selectedMenu->name }}.
                </p>
            </div>

            <form
                action="{{ route('manajer.recipes.store') }}"
                method="POST"
                class="space-y-5 p-5"
            >
                @csrf
                <input type="hidden" name="menu_id" value="{{ $selectedMenu->id }}">

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <span class="mb-2 block text-sm font-semibold text-[#4E3936]">
                            Menu
                        </span>
                        <p class="rounded-xl border border-[#828854] bg-[#fffdf5] px-4 py-3 text-sm text-[#4E3936]">
                            {{ $selectedMenu->name }}
                        </p>
                    </div>

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
                                    @selected(old('ingredient_id') == $ingredient->id)
                                >
                                    {{ $ingredient->name }} ({{ $ingredient->base_unit }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="quantity" class="mb-2 block text-sm font-semibold text-[#4E3936]">
                            Jumlah Pemakaian
                        </label>
                        <input
                            type="number"
                            name="quantity"
                            id="quantity"
                            value="{{ old('quantity') }}"
                            min="0.001"
                            step="0.001"
                            required
                            placeholder="Masukkan jumlah pemakaian"
                            class="field-input"
                        >
                    </div>
                </div>

                <div class="border-t border-[#828854]/40 pt-4">
                    <button type="submit" class="primary-button">
                        + Tambah Bahan Resep
                    </button>
                </div>
            </form>
        </section>

        {{-- DAFTAR BAHAN RESEP --}}
        <section class="panel">
            <div class="panel-header">
                <h3 class="text-lg font-bold text-[#4E3936]">
                    Bahan dalam Resep
                </h3>
                <p class="mt-1 text-sm text-[#4E3936]/70">
                    Daftar bahan dan jumlah yang digunakan untuk {{ $selectedMenu->name }}.
                </p>
            </div>

            @if ($selectedMenu->recipes->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[620px] text-left text-sm">
                        <thead class="table-heading">
                            <tr>
                                <th class="px-5 py-4 font-bold">No.</th>
                                <th class="px-5 py-4 font-bold">Bahan Baku</th>
                                <th class="px-5 py-4 font-bold">Jumlah Pemakaian</th>
                                <th class="px-5 py-4 text-center font-bold">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#828854]/30">
                            @foreach ($selectedMenu->recipes as $recipe)
                                <tr class="table-row text-[#4E3936]">
                                    <td class="px-5 py-4">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-5 py-4 font-semibold">
                                        {{ $recipe->ingredient->name }}
                                    </td>

                                    <td class="px-5 py-4">
                                        {{ str_replace('.', ',', rtrim(rtrim((string) $recipe->quantity, '0'), '.')) }}
                                        {{ $recipe->ingredient->base_unit }}
                                    </td>

                                    <td class="px-5 py-4 text-center">
                                        <div class="flex flex-wrap items-center justify-center gap-3">
                                            <button
                                                type="button"
                                                onclick="document.getElementById('edit-recipe-{{ $recipe->id }}').classList.toggle('hidden')"
                                                class="edit-button"
                                            >
                                                Edit
                                            </button>

                                            <form
                                                action="{{ route('manajer.recipes.destroy', $recipe) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus bahan resep ini? {{ addslashes($selectedMenu->name) }} - {{ addslashes($recipe->ingredient->name) }}?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="danger-button">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                {{-- FORM EDIT --}}
                                <tr
                                    id="edit-recipe-{{ $recipe->id }}"
                                    class="hidden bg-[#F1ECD8]/70"
                                >
                                    <td colspan="4" class="border-y border-[#828854]/40 p-5">
                                        <form
                                            action="{{ route('manajer.recipes.update', $recipe) }}"
                                            method="POST"
                                            class="space-y-4"
                                        >
                                            @csrf
                                            @method('PUT')

                                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                                <div>
                                                    <label class="mb-2 block text-sm font-semibold text-[#4E3936]">
                                                        Menu
                                                    </label>
                                                    <select
                                                        name="menu_id"
                                                        required
                                                        class="field-input"
                                                    >
                                                        @foreach ($menus as $menu)
                                                            <option
                                                                value="{{ $menu->id }}"
                                                                @selected(old('menu_id', $recipe->menu_id) == $menu->id)
                                                            >
                                                                {{ $menu->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="mb-2 block text-sm font-semibold text-[#4E3936]">
                                                        Bahan Baku
                                                    </label>
                                                    <select
                                                        name="ingredient_id"
                                                        required
                                                        class="field-input"
                                                    >
                                                        @foreach ($ingredients as $ingredient)
                                                            <option
                                                                value="{{ $ingredient->id }}"
                                                                @selected(old('ingredient_id', $recipe->ingredient_id) == $ingredient->id)
                                                            >
                                                                {{ $ingredient->name }} ({{ $ingredient->base_unit }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="mb-2 block text-sm font-semibold text-[#4E3936]">
                                                        Jumlah Pemakaian
                                                    </label>
                                                    <input
                                                        type="number"
                                                        name="quantity"
                                                        value="{{ old('quantity', $recipe->quantity) }}"
                                                        min="0.001"
                                                        step="0.001"
                                                        required
                                                        class="field-input"
                                                    >
                                                </div>
                                            </div>

                                            <button type="submit" class="primary-button">
                                                Simpan Perubahan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="p-5 text-sm text-[#4E3936]/70">
                    Belum ada bahan untuk resep menu ini. Gunakan formulir di atas untuk menambahkannya.
                </p>
            @endif
        </section>

    @else

        {{-- PILIH MENU --}}
        <section class="panel">
            <div class="panel-header">
                <h2 class="text-lg font-bold text-[#4E3936]">
                    Pilih Menu
                </h2>
                <p class="mt-1 text-sm text-[#4E3936]/70">
                    Pilih nama menu untuk melihat bahan resepnya.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($menus as $menu)
                    <a
                        href="{{ route('manajer.recipes.index', ['menu' => $menu->id]) }}"
                        class="menu-card"
                    >
                        <span class="block text-base font-bold">
                            {{ $menu->name }}
                        </span>
                        <span class="mt-2 block text-sm text-[#4E3936]/70">
                            {{ $menu->recipes_count }} bahan resep
                        </span>
                    </a>
                @empty
                    <p class="text-sm text-[#4E3936]/70">
                        Belum ada menu yang tersedia.
                    </p>
                @endforelse
            </div>
        </section>

    @endif

</div>
@endsection
