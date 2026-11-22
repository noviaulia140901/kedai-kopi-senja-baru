
@extends('layouts.manajer')

@section('content')
<style>
    body:has(.manager-categories-page) main {
        background-color: #F1ECD8;
    }

    .manager-categories-page {
        color: #4E3936;
    }

    .manager-categories-page .category-panel {
        background-color: #F1ECD8;
        border-color: rgb(130 136 84 / 40%);
    }

    .manager-categories-page .category-input {
        background-color: #fffdf5;
        border-color: #828854;
        color: #4E3936;
    }

    .manager-categories-page .category-input:focus {
        border-color: #465A3D;
        --tw-ring-color: #465A3D;
    }

    .manager-categories-page .category-table thead {
        background-color: #465A3D;
        color: #F1ECD8;
    }

    .manager-categories-page .category-table tbody tr:nth-child(even) {
        background-color: rgb(130 136 84 / 10%);
    }

    .manager-categories-page .category-table tbody tr:hover {
        background-color: rgb(130 136 84 / 16%);
    }

    .manager-categories-page .primary-button {
        background-color: #465A3D;
        color: #F1ECD8;
    }

    .manager-categories-page .primary-button:hover {
        background-color: #35472F;
    }

    .manager-categories-page .secondary-button {
        background-color: #F1ECD8;
        border-color: #828854;
        color: #4E3936;
    }

    .manager-categories-page .secondary-button:hover {
        background-color: #fffdf5;
    }

    .manager-categories-page .edit-button {
        border-color: #465A3D;
        color: #465A3D;
    }

    .manager-categories-page .edit-button:hover {
        background-color: rgb(70 90 61 / 10%);
    }

    .manager-categories-page .delete-button {
        border-color: rgb(100 0 23 / 40%);
        color: #640017;
    }

    .manager-categories-page .delete-button:hover {
        background-color: rgb(100 0 23 / 10%);
    }

    .manager-categories-page .success-notice {
        background-color: #F1ECD8;
        border-color: rgb(130 136 84 / 40%);
        color: #465A3D;
    }

    .manager-categories-page .error-notice {
        background-color: #F1ECD8;
        border-color: rgb(100 0 23 / 30%);
        color: #640017;
    }
</style>

<div class="manager-categories-page space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm font-semibold tracking-widest uppercase text-[#465A3D]">
                Manajemen Menu
            </p>
            <h1 class="mt-2 text-3xl font-bold text-[#4E3936]">
                Kelola Kategori
            </h1>
            <p class="mt-2 text-sm text-[#4E3936]/70">
                Atur kategori untuk mengelompokkan menu Kedai Kopi Senja.
            </p>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="success-notice rounded-md border px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="error-notice rounded-md border px-4 py-3 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="error-notice rounded-md border px-4 py-3 text-sm">
            <p class="font-semibold">Periksa kembali data yang dimasukkan:</p>
            <ul class="mt-2 list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Tambah Kategori --}}
    <div class="category-panel rounded-md border p-6 shadow-sm">
        <h2 class="text-lg font-bold text-[#4E3936]">
            Tambah Kategori Baru
        </h2>
        <p class="mt-1 text-sm text-[#4E3936]/70">
            Masukkan informasi kategori yang ingin ditambahkan.
        </p>

        <form action="{{ route('manajer.categories.store') }}" method="POST" class="mt-5 space-y-4">
            @csrf

            <div>
                <label for="name" class="mb-2 block text-sm font-semibold text-[#4E3936]">
                    Nama Kategori
                </label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Kopi, Non-Kopi, Makanan"
                    required
                    maxlength="100"
                    class="category-input w-full rounded-md border px-4 py-3 text-sm focus:border-[#465A3D] focus:ring-[#465A3D]"
                >
            </div>

            <div>
                <label for="description" class="mb-2 block text-sm font-semibold text-[#4E3936]">
                    Deskripsi
                </label>
                <textarea
                    name="description"
                    id="description"
                    rows="3"
                    placeholder="Keterangan kategori (opsional)"
                    class="category-input w-full rounded-md border px-4 py-3 text-sm focus:border-[#465A3D] focus:ring-[#465A3D]"
                >{{ old('description') }}</textarea>
            </div>

            <div>
                <label for="is_active" class="mb-2 block text-sm font-semibold text-[#4E3936]">
                    Status Kategori
                </label>
                <select
                    name="is_active"
                    id="is_active"
                    required
                    class="category-input w-full rounded-md border px-4 py-3 text-sm focus:border-[#465A3D] focus:ring-[#465A3D]"
                >
                    <option value="1" selected>Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>

            <button
                type="submit"
                class="primary-button rounded-md px-5 py-3 text-sm font-semibold transition"
            >
                + Tambah Kategori
            </button>
        </form>
    </div>

    {{-- Daftar Kategori --}}
    <div class="category-panel overflow-hidden rounded-md border shadow-sm">
        <div class="border-b border-[#828854]/40 px-6 py-5">
            <h2 class="text-lg font-bold text-[#4E3936]">
                Daftar Kategori
            </h2>
            <p class="mt-1 text-sm text-[#4E3936]/70">
                Seluruh kategori yang tersedia dalam sistem.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="category-table w-full min-w-[750px] text-left text-sm">
                <thead>
                    <tr>
                        <th class="px-6 py-4 font-semibold">No.</th>
                        <th class="px-6 py-4 font-semibold">Nama Kategori</th>
                        <th class="px-6 py-4 font-semibold">Deskripsi</th>
                        <th class="px-6 py-4 font-semibold">Jumlah Menu</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#828854]/30">
                    @forelse ($categories as $category)
                        <tr class="text-[#4E3936]">
                            <td class="px-6 py-4 text-[#4E3936]/60">
                                {{ $categories->firstItem() + $loop->index }}
                            </td>

                            <td class="px-6 py-4 font-semibold text-[#4E3936]">
                                {{ $category->name }}
                            </td>

                            <td class="px-6 py-4 text-[#4E3936]/70">
                                {{ $category->description ?: '-' }}
                            </td>

                            <td class="px-6 py-4 text-[#4E3936]">
                                {{ $category->menus_count }} menu
                            </td>

                            <td class="px-6 py-4">
                                @if ($category->is_active)
                                    <span class="rounded-md bg-[#465A3D]/10 px-3 py-1 text-xs font-semibold text-[#465A3D]">
                                        Aktif
                                    </span>
                                @else
                                    <span class="rounded-md bg-[#4E3936]/5 px-3 py-1 text-xs font-semibold text-[#4E3936]">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button
                                        type="button"
                                        onclick="document.getElementById('edit-category-{{ $category->id }}').classList.toggle('hidden')"
                                        class="edit-button rounded-md border px-3 py-2 text-xs font-semibold"
                                    >
                                        Edit
                                    </button>

                                    <form
                                        action="{{ route('manajer.categories.destroy', $category) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus kategori ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="delete-button rounded-md border px-3 py-2 text-xs font-semibold"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Form Edit --}}
                        <tr id="edit-category-{{ $category->id }}" class="hidden bg-[#F1ECD8]/70">
                            <td colspan="6" class="px-6 py-5">
                                <form
                                    action="{{ route('manajer.categories.update', $category) }}"
                                    method="POST"
                                    class="space-y-4"
                                >
                                    @csrf
                                    @method('PUT')

                                    <h3 class="font-bold text-[#4E3936]">
                                        Edit: {{ $category->name }}
                                    </h3>

                                    <div>
                                        <label class="mb-2 block text-sm font-semibold text-[#4E3936]">
                                            Nama Kategori
                                        </label>
                                        <input
                                            type="text"
                                            name="name"
                                            value="{{ $category->name }}"
                                            required
                                            maxlength="100"
                                            class="category-input w-full rounded-md border px-4 py-3 text-sm focus:border-[#465A3D] focus:ring-[#465A3D]"
                                        >
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-semibold text-[#4E3936]">
                                            Deskripsi
                                        </label>
                                        <textarea
                                            name="description"
                                            rows="2"
                                            class="category-input w-full rounded-md border px-4 py-3 text-sm focus:border-[#465A3D] focus:ring-[#465A3D]"
                                        >{{ $category->description }}</textarea>
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-semibold text-[#4E3936]">
                                            Status
                                        </label>
                                        <select
                                            name="is_active"
                                            required
                                            class="category-input w-full rounded-md border px-4 py-3 text-sm focus:border-[#465A3D] focus:ring-[#465A3D]"
                                        >
                                            <option value="1" @selected($category->is_active)>Aktif</option>
                                            <option value="0" @selected(!$category->is_active)>Nonaktif</option>
                                        </select>
                                    </div>

                                    <div class="flex gap-2">
                                        <button
                                            type="submit"
                                            class="primary-button rounded-md px-5 py-2.5 text-sm font-semibold"
                                        >
                                            Simpan Perubahan
                                        </button>

                                        <button
                                            type="button"
                                            onclick="document.getElementById('edit-category-{{ $category->id }}').classList.add('hidden')"
                                            class="secondary-button rounded-md border px-5 py-2.5 text-sm font-semibold"
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
                                <p class="font-semibold text-[#4E3936]">
                                    Belum ada kategori.
                                </p>
                                <p class="mt-1 text-sm text-[#4E3936]/70">
                                    Tambahkan kategori pertama menggunakan formulir di atas.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
            <div class="border-t border-[#828854]/40 px-6 py-4">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
