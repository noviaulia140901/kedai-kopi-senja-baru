@extends('layouts.manajer')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm font-semibold tracking-widest uppercase text-[#A66A45]">
                Manajemen Menu
            </p>
            <h1 class="mt-2 text-3xl font-bold text-[#34251F]">
                Kelola Kategori
            </h1>
            <p class="mt-2 text-sm text-[#6F5548]">
                Atur kategori untuk mengelompokkan menu Kedai Kopi Senja.
            </p>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <p class="font-semibold">Periksa kembali data yang dimasukkan:</p>
            <ul class="mt-2 list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Tambah Kategori --}}
    <div class="rounded-2xl border border-[#D8C9BE] bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-[#34251F]">
            Tambah Kategori Baru
        </h2>
        <p class="mt-1 text-sm text-[#6F5548]">
            Masukkan informasi kategori yang ingin ditambahkan.
        </p>

        <form action="{{ route('manajer.categories.store') }}" method="POST" class="mt-5 space-y-4">
            @csrf

            <div>
                <label for="name" class="mb-2 block text-sm font-semibold text-[#493225]">
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
                    class="w-full rounded-xl border-[#D8C9BE] px-4 py-3 text-sm text-[#34251F] focus:border-[#A66A45] focus:ring-[#A66A45]"
                >
            </div>

            <div>
                <label for="description" class="mb-2 block text-sm font-semibold text-[#493225]">
                    Deskripsi
                </label>
                <textarea
                    name="description"
                    id="description"
                    rows="3"
                    placeholder="Keterangan kategori (opsional)"
                    class="w-full rounded-xl border-[#D8C9BE] px-4 py-3 text-sm text-[#34251F] focus:border-[#A66A45] focus:ring-[#A66A45]"
                >{{ old('description') }}</textarea>
            </div>

            <div>
                <label for="is_active" class="mb-2 block text-sm font-semibold text-[#493225]">
                    Status Kategori
                </label>
                <select
                    name="is_active"
                    id="is_active"
                    required
                    class="w-full rounded-xl border-[#D8C9BE] px-4 py-3 text-sm text-[#34251F] focus:border-[#A66A45] focus:ring-[#A66A45]"
                >
                    <option value="1" selected>Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>

            <button
                type="submit"
                class="rounded-xl bg-[#493225] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#684936]"
            >
                + Tambah Kategori
            </button>
        </form>
    </div>

    {{-- Daftar Kategori --}}
    <div class="overflow-hidden rounded-2xl border border-[#D8C9BE] bg-white shadow-sm">
        <div class="border-b border-[#E8DED6] px-6 py-5">
            <h2 class="text-lg font-bold text-[#34251F]">
                Daftar Kategori
            </h2>
            <p class="mt-1 text-sm text-[#6F5548]">
                Seluruh kategori yang tersedia dalam sistem.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[750px] text-left text-sm">
                <thead class="bg-[#F7F3ED] text-[#493225]">
                    <tr>
                        <th class="px-6 py-4 font-semibold">No.</th>
                        <th class="px-6 py-4 font-semibold">Nama Kategori</th>
                        <th class="px-6 py-4 font-semibold">Deskripsi</th>
                        <th class="px-6 py-4 font-semibold">Jumlah Menu</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#E8DED6]">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-[#FCFAF7]">
                            <td class="px-6 py-4 text-[#6F5548]">
                                {{ $categories->firstItem() + $loop->index }}
                            </td>

                            <td class="px-6 py-4 font-semibold text-[#34251F]">
                                {{ $category->name }}
                            </td>

                            <td class="px-6 py-4 text-[#6F5548]">
                                {{ $category->description ?: '-' }}
                            </td>

                            <td class="px-6 py-4 text-[#493225]">
                                {{ $category->menus_count }} menu
                            </td>

                            <td class="px-6 py-4">
                                @if ($category->is_active)
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Aktif
                                    </span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button
                                        type="button"
                                        onclick="document.getElementById('edit-category-{{ $category->id }}').classList.toggle('hidden')"
                                        class="rounded-lg border border-[#A66A45] px-3 py-2 text-xs font-semibold text-[#805238] hover:bg-[#F7F3ED]"
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
                                            class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Form Edit --}}
                        <tr id="edit-category-{{ $category->id }}" class="hidden bg-[#FCFAF7]">
                            <td colspan="6" class="px-6 py-5">
                                <form
                                    action="{{ route('manajer.categories.update', $category) }}"
                                    method="POST"
                                    class="space-y-4"
                                >
                                    @csrf
                                    @method('PUT')

                                    <h3 class="font-bold text-[#34251F]">
                                        Edit: {{ $category->name }}
                                    </h3>

                                    <div>
                                        <label class="mb-2 block text-sm font-semibold text-[#493225]">
                                            Nama Kategori
                                        </label>
                                        <input
                                            type="text"
                                            name="name"
                                            value="{{ $category->name }}"
                                            required
                                            maxlength="100"
                                            class="w-full rounded-xl border-[#D8C9BE] px-4 py-3 text-sm focus:border-[#A66A45] focus:ring-[#A66A45]"
                                        >
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-semibold text-[#493225]">
                                            Deskripsi
                                        </label>
                                        <textarea
                                            name="description"
                                            rows="2"
                                            class="w-full rounded-xl border-[#D8C9BE] px-4 py-3 text-sm focus:border-[#A66A45] focus:ring-[#A66A45]"
                                        >{{ $category->description }}</textarea>
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-semibold text-[#493225]">
                                            Status
                                        </label>
                                        <select
                                            name="is_active"
                                            required
                                            class="w-full rounded-xl border-[#D8C9BE] px-4 py-3 text-sm focus:border-[#A66A45] focus:ring-[#A66A45]"
                                        >
                                            <option value="1" @selected($category->is_active)>Aktif</option>
                                            <option value="0" @selected(!$category->is_active)>Nonaktif</option>
                                        </select>
                                    </div>

                                    <div class="flex gap-2">
                                        <button
                                            type="submit"
                                            class="rounded-xl bg-[#493225] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#684936]"
                                        >
                                            Simpan Perubahan
                                        </button>

                                        <button
                                            type="button"
                                            onclick="document.getElementById('edit-category-{{ $category->id }}').classList.add('hidden')"
                                            class="rounded-xl border border-[#D8C9BE] px-5 py-2.5 text-sm font-semibold text-[#493225] hover:bg-white"
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
                                <p class="font-semibold text-[#493225]">
                                    Belum ada kategori.
                                </p>
                                <p class="mt-1 text-sm text-[#6F5548]">
                                    Tambahkan kategori pertama menggunakan formulir di atas.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
            <div class="border-t border-[#E8DED6] px-6 py-4">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
