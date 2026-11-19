@extends('layouts.manajer')

@section('content')
<div class="space-y-6">

    {{-- Judul halaman --}}
    <div>
        <p class="text-sm font-semibold uppercase tracking-wider text-gray-600">
            Manajemen Produk
        </p>
        <h1 class="mt-2 text-2xl font-bold text-gray-900">
            Kelola Menu
        </h1>
        <p class="mt-1 text-sm text-gray-600">
            Atur daftar makanan dan minuman Kedai Kopi Senja.
        </p>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="border border-green-700 bg-green-50 p-4 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="border border-red-700 bg-red-50 p-4 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="border border-red-700 bg-red-50 p-4 text-sm text-red-800">
            <p class="font-bold">Periksa kembali data berikut:</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form tambah menu --}}
    <section class="border-2 border-gray-800 bg-white">
        <div class="border-b-2 border-gray-800 bg-gray-100 px-5 py-4">
            <h2 class="text-lg font-bold text-gray-900">Tambah Menu Baru</h2>
            <p class="mt-1 text-sm text-gray-600">
                Masukkan informasi menu yang akan dijual.
            </p>
        </div>

        <form
            action="{{ route('manajer.menus.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-5 p-5"
        >
            @csrf

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-gray-800">
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
                        class="w-full border-2 border-gray-400 px-3 py-3 text-sm focus:border-gray-900 focus:outline-none"
                    >
                </div>

                <div>
                    <label for="category_id" class="mb-2 block text-sm font-semibold text-gray-800">
                        Kategori
                    </label>
                    <select
                        name="category_id"
                        id="category_id"
                        required
                        class="w-full border-2 border-gray-400 bg-white px-3 py-3 text-sm focus:border-gray-900 focus:outline-none"
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

                <div>
                    <label for="selling_price" class="mb-2 block text-sm font-semibold text-gray-800">
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
                        class="w-full border-2 border-gray-400 px-3 py-3 text-sm focus:border-gray-900 focus:outline-none"
                    >
                </div>

                <div>
                    <label for="image" class="mb-2 block text-sm font-semibold text-gray-800">
                        Gambar Menu (Opsional)
                    </label>
                    <input
                        type="file"
                        name="image"
                        id="image"
                        accept="image/*"
                        class="w-full border-2 border-gray-400 bg-white px-3 py-2.5 text-sm"
                    >
                    <p class="mt-1 text-xs text-gray-500">
                        Format gambar maksimal 2 MB.
                    </p>
                </div>

                <div>
                    <label for="is_active" class="mb-2 block text-sm font-semibold text-gray-800">
                        Status Menu
                    </label>
                    <select
                        name="is_active"
                        id="is_active"
                        required
                        class="w-full border-2 border-gray-400 bg-white px-3 py-3 text-sm focus:border-gray-900 focus:outline-none"
                    >
                        <option value="1" @selected(old('is_active', '1') == '1')>
                            Aktif
                        </option>
                        <option value="0" @selected(old('is_active') == '0')>
                            Nonaktif
                        </option>
                    </select>
                </div>

                <div>
                    <label for="description" class="mb-2 block text-sm font-semibold text-gray-800">
                        Deskripsi Menu
                    </label>
                    <textarea
                        name="description"
                        id="description"
                        rows="3"
                        placeholder="Deskripsi singkat menu (opsional)"
                        class="w-full border-2 border-gray-400 px-3 py-3 text-sm focus:border-gray-900 focus:outline-none"
                    >{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="border-t border-gray-300 pt-4">
                <button
                    type="submit"
                    class="border-2 border-gray-900 bg-gray-900 px-6 py-3 text-sm font-bold text-white hover:bg-gray-700"
                >
                    + Tambah Menu
                </button>
            </div>
        </form>
    </section>

    {{-- Daftar menu --}}
    <section class="border-2 border-gray-800 bg-white">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b-2 border-gray-800 bg-gray-100 px-5 py-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Daftar Menu</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Daftar makanan dan minuman yang terdaftar.
                </p>
            </div>
            <p class="text-sm text-gray-600">
                Total {{ $menus->total() }} menu
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left text-sm">
                <thead class="border-b-2 border-gray-800 bg-gray-200 text-gray-900">
                    <tr>
                        <th class="px-5 py-4 font-bold">No.</th>
                        <th class="px-5 py-4 font-bold">Menu</th>
                        <th class="px-5 py-4 font-bold">Kategori</th>
                        <th class="px-5 py-4 font-bold">Harga</th>
                        <th class="px-5 py-4 font-bold">Status</th>
                        <th class="px-5 py-4 text-center font-bold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-300">
                    @forelse ($menus as $menu)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4 text-gray-600">
                                {{ $menus->firstItem() + $loop->index }}
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($menu->image)
                                        <img
                                            src="{{ asset('storage/' . $menu->image) }}"
                                            alt="{{ $menu->name }}"
                                            class="h-14 w-14 border border-gray-300 object-cover"
                                        >
                                    @else
                                        <div class="flex h-14 w-14 items-center justify-center border border-gray-400 bg-gray-100 text-xs font-bold text-gray-500">
                                            MENU
                                        </div>
                                    @endif

                                    <div>
                                        <p class="font-bold text-gray-900">
                                            {{ $menu->name }}
                                        </p>
                                        <p class="mt-1 max-w-xs text-xs text-gray-600">
                                            {{ $menu->description ?: 'Tidak ada deskripsi' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-4 text-gray-700">
                                {{ $menu->category->name ?? '-' }}
                            </td>

                            <td class="px-5 py-4 font-semibold text-gray-900">
                                Rp {{ number_format((float) $menu->selling_price, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-4">
                                @if ($menu->is_active)
                                    <span class="inline-block border border-green-700 px-3 py-1 text-xs font-semibold text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-block border border-gray-500 px-3 py-1 text-xs font-semibold text-gray-600">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button
                                        type="button"
                                        onclick="document.getElementById('edit-menu-{{ $menu->id }}').classList.toggle('hidden')"
                                        class="border border-gray-800 px-3 py-2 text-xs font-semibold text-gray-900 hover:bg-gray-100"
                                    >
                                        Edit
                                    </button>

                                    <form
                                        action="{{ route('manajer.menus.destroy', $menu) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus menu ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="border border-red-700 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-50"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Form edit menu --}}
                        <tr id="edit-menu-{{ $menu->id }}" class="hidden bg-gray-100">
                            <td colspan="6" class="border-y border-gray-400 p-5">
                                <form
                                    action="{{ route('manajer.menus.update', $menu) }}"
                                    method="POST"
                                    enctype="multipart/form-data"
                                    class="space-y-5"
                                >
                                    @csrf
                                    @method('PUT')

                                    <div>
                                        <h3 class="font-bold text-gray-900">
                                            Edit Menu: {{ $menu->name }}
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-600">
                                            Perbarui informasi menu di bawah ini.
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                        <div>
                                            <label class="mb-2 block text-sm font-semibold text-gray-800">
                                                Nama Menu
                                            </label>
                                            <input
                                                type="text"
                                                name="name"
                                                value="{{ $menu->name }}"
                                                required
                                                maxlength="150"
                                                class="w-full border-2 border-gray-400 px-3 py-3 text-sm"
                                            >
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-semibold text-gray-800">
                                                Kategori
                                            </label>
                                            <select
                                                name="category_id"
                                                required
                                                class="w-full border-2 border-gray-400 bg-white px-3 py-3 text-sm"
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

                                        <div>
                                            <label class="mb-2 block text-sm font-semibold text-gray-800">
                                                Harga Jual (Rp)
                                            </label>
                                            <input
                                                type="number"
                                                name="selling_price"
                                                value="{{ $menu->selling_price }}"
                                                min="0"
                                                step="0.01"
                                                required
                                                class="w-full border-2 border-gray-400 px-3 py-3 text-sm"
                                            >
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-semibold text-gray-800">
                                                Ganti Gambar (Opsional)
                                            </label>
                                            <input
                                                type="file"
                                                name="image"
                                                accept="image/*"
                                                class="w-full border-2 border-gray-400 bg-white px-3 py-2.5 text-sm"
                                            >
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-semibold text-gray-800">
                                                Status
                                            </label>
                                            <select
                                                name="is_active"
                                                required
                                                class="w-full border-2 border-gray-400 bg-white px-3 py-3 text-sm"
                                            >
                                                <option value="1" @selected($menu->is_active)>
                                                    Aktif
                                                </option>
                                                <option value="0" @selected(!$menu->is_active)>
                                                    Nonaktif
                                                </option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-semibold text-gray-800">
                                                Deskripsi
                                            </label>
                                            <textarea
                                                name="description"
                                                rows="3"
                                                class="w-full border-2 border-gray-400 px-3 py-3 text-sm"
                                            >{{ $menu->description }}</textarea>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-2 border-t border-gray-300 pt-4">
                                        <button
                                            type="submit"
                                            class="border-2 border-gray-900 bg-gray-900 px-5 py-3 text-sm font-bold text-white hover:bg-gray-700"
                                        >
                                            Simpan Perubahan
                                        </button>

                                        <button
                                            type="button"
                                            onclick="document.getElementById('edit-menu-{{ $menu->id }}').classList.add('hidden')"
                                            class="border-2 border-gray-800 px-5 py-3 text-sm font-bold text-gray-900 hover:bg-white"
                                        >
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <p class="font-semibold text-gray-800">
                                    Belum ada menu.
                                </p>
                                <p class="mt-1 text-sm text-gray-500">
                                    Tambahkan menu pertama melalui formulir di atas.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($menus->hasPages())
            <div class="border-t-2 border-gray-800 p-4">
                {{ $menus->links() }}
            </div>
        @endif
    </section>

</div>
@endsection
