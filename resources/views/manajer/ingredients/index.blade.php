@extends('layouts.manajer')

@section('content')

<div class="w-full space-y-6">

    {{-- JUDUL HALAMAN --}}
    <section class="border-2 border-black bg-[#F3F3F3] px-6 py-5">
        <p class="text-xs font-bold uppercase tracking-widest text-[#805238]">
            DATA PERSEDIAAN
        </p>
        <h2 class="mt-2 text-2xl font-bold text-black">Kelola Bahan Baku</h2>
        <p class="mt-2 text-sm text-gray-700">
            Kelola data bahan baku, jumlah stok, dan batas minimum persediaan.
        </p>
    </section>

    {{-- PESAN BERHASIL --}}
    @if (session('success'))
        <div class="border-2 border-black bg-[#F3F3F3] px-5 py-3 text-sm font-semibold text-black">
            {{ session('success') }}
        </div>
    @endif

    {{-- PESAN GAGAL --}}
    @if (session('error'))
        <div class="border-2 border-black bg-white px-5 py-3 text-sm font-semibold text-[#A33A2B]">
            {{ session('error') }}
        </div>
    @endif

    {{-- FORM TAMBAH BAHAN --}}
    <section class="border-2 border-black bg-white">
        <div class="border-b-2 border-black px-6 py-4">
            <h3 class="text-lg font-bold text-black">Tambah Bahan Baku</h3>
            <p class="mt-1 text-sm text-gray-600">
                Masukkan informasi bahan baku baru.
            </p>
        </div>

        <form method="POST" action="{{ route('manajer.ingredients.store') }}" class="space-y-5 p-6">
            @csrf

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <div>
                    <label for="name" class="mb-2 block text-sm font-bold text-black">
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
                        class="w-full border-2 border-black px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#805238]"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="base_unit" class="mb-2 block text-sm font-bold text-black">
                        Satuan
                    </label>
                    <select
                        id="base_unit"
                        name="base_unit"
                        required
                        class="w-full border-2 border-black bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#805238]"
                    >
                        <option value="">Pilih satuan</option>
                        <option value="g" @selected(old('base_unit') === 'g')>Gram (g)</option>
                        <option value="ml" @selected(old('base_unit') === 'ml')>Mililiter (ml)</option>
                        <option value="pcs" @selected(old('base_unit') === 'pcs')>Pieces (pcs)</option>
                    </select>
                    @error('base_unit')
                        <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="current_stock" class="mb-2 block text-sm font-bold text-black">
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
                        class="w-full border-2 border-black px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#805238]"
                    >
                    @error('current_stock')
                        <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="minimum_stock" class="mb-2 block text-sm font-bold text-black">
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
                        class="w-full border-2 border-black px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#805238]"
                    >
                    @error('minimum_stock')
                        <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="mb-2 block text-sm font-bold text-black">
                        Deskripsi
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        placeholder="Keterangan bahan baku (opsional)"
                        class="w-full border-2 border-black px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#805238]"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="is_active" class="mb-2 block text-sm font-bold text-black">
                        Status Bahan
                    </label>
                    <select
                        id="is_active"
                        name="is_active"
                        required
                        class="w-full border-2 border-black bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#805238]"
                    >
                        <option value="1" @selected(old('is_active', '1') == '1')>Aktif</option>
                        <option value="0" @selected(old('is_active') == '0')>Tidak Aktif</option>
                    </select>
                    @error('is_active')
                        <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="border-t border-gray-300 pt-5">
                <button
                    type="submit"
                    class="border-2 border-black bg-black px-6 py-3 text-sm font-bold text-white hover:bg-[#493225]"
                >
                    + Tambah Bahan
                </button>
            </div>
        </form>
    </section>

    {{-- DAFTAR BAHAN --}}
    <section id="daftar-batas-minimum" class="scroll-mt-5 border-2 border-black bg-white">

        <div class="flex flex-wrap items-center justify-between gap-3 border-b-2 border-black px-6 py-5">
            <div>
                <h3 class="text-lg font-bold text-black">Daftar Bahan Baku</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Informasi stok dan batas minimum setiap bahan.
                </p>
            </div>

            <span class="border-2 border-black px-4 py-2 text-sm font-bold">
                {{ $ingredients->total() }} Bahan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px]">

                <thead class="bg-black text-white">
                    <tr>
                        <th class="px-5 py-4 text-left text-sm font-bold">Nama Bahan</th>
                        <th class="px-5 py-4 text-right text-sm font-bold">Stok</th>
                        <th class="px-5 py-4 text-right text-sm font-bold">Batas Minimum</th>
                        <th class="px-5 py-4 text-center text-sm font-bold">Status Stok</th>
                        <th class="px-5 py-4 text-center text-sm font-bold">Status Bahan</th>
                        <th class="px-5 py-4 text-center text-sm font-bold">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($ingredients as $ingredient)
                        <tr class="border-b border-black last:border-b-0 even:bg-[#F3F3F3]">

                            <td class="px-5 py-4">
                                <p class="font-bold text-black">{{ $ingredient->name }}</p>
                                <p class="mt-1 text-xs text-gray-600">
                                    {{ $ingredient->description ?: 'Tidak ada deskripsi' }}
                                </p>
                            </td>

                            <td class="px-5 py-4 text-right text-sm font-semibold text-black">
                                {{ number_format((float) $ingredient->current_stock, 3, ',', '.') }}
                                {{ $ingredient->base_unit }}
                            </td>

                            <td class="px-5 py-4 text-right text-sm text-black">
                                {{ number_format((float) $ingredient->minimum_stock, 3, ',', '.') }}
                                {{ $ingredient->base_unit }}
                            </td>

                            <td class="px-5 py-4 text-center">
                                @if ($ingredient->current_stock <= $ingredient->minimum_stock)
                                    <span class="inline-block border-2 border-black bg-[#F3F3F3] px-3 py-1 text-xs font-bold text-[#A33A2B]">
                                        Menipis
                                    </span>
                                @else
                                    <span class="inline-block border-2 border-black bg-white px-3 py-1 text-xs font-bold">
                                        Aman
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-center">
                                @if ($ingredient->is_active)
                                    <span class="inline-block border-2 border-black bg-black px-3 py-1 text-xs font-bold text-white">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-block border-2 border-black bg-white px-3 py-1 text-xs font-bold text-black">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-center">
                                <div class="flex flex-wrap justify-center gap-2">

                                    <details class="relative">
                                        <summary class="cursor-pointer list-none border-2 border-black bg-white px-3 py-2 text-xs font-bold hover:bg-gray-200">
                                            Edit
                                        </summary>

                                        <div class="absolute right-0 z-20 mt-2 w-[min(85vw,420px)] border-2 border-black bg-white p-5 text-left shadow-lg">
                                            <h4 class="mb-4 font-bold text-black">
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
                                                    <label class="mb-1 block text-xs font-bold">Nama Bahan</label>
                                                    <input
                                                        type="text"
                                                        name="name"
                                                        value="{{ $ingredient->name }}"
                                                        required
                                                        maxlength="150"
                                                        class="w-full border-2 border-black px-3 py-2 text-sm"
                                                    >
                                                </div>

                                                <div>
                                                    <label class="mb-1 block text-xs font-bold">Satuan</label>
                                                    <select name="base_unit" required class="w-full border-2 border-black bg-white px-3 py-2 text-sm">
                                                        <option value="g" @selected($ingredient->base_unit === 'g')>Gram (g)</option>
                                                        <option value="ml" @selected($ingredient->base_unit === 'ml')>Mililiter (ml)</option>
                                                        <option value="pcs" @selected($ingredient->base_unit === 'pcs')>Pieces (pcs)</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="mb-1 block text-xs font-bold">Stok Saat Ini</label>
                                                    <input
                                                        type="number"
                                                        name="current_stock"
                                                        value="{{ $ingredient->current_stock }}"
                                                        min="0"
                                                        step="0.001"
                                                        required
                                                        class="w-full border-2 border-black px-3 py-2 text-sm"
                                                    >
                                                </div>

                                                <div>
                                                    <label class="mb-1 block text-xs font-bold">Batas Minimum</label>
                                                    <input
                                                        type="number"
                                                        name="minimum_stock"
                                                        value="{{ $ingredient->minimum_stock }}"
                                                        min="0"
                                                        step="0.001"
                                                        required
                                                        class="w-full border-2 border-black px-3 py-2 text-sm"
                                                    >
                                                </div>

                                                <div>
                                                    <label class="mb-1 block text-xs font-bold">Deskripsi</label>
                                                    <textarea
                                                        name="description"
                                                        rows="2"
                                                        class="w-full border-2 border-black px-3 py-2 text-sm"
                                                    >{{ $ingredient->description }}</textarea>
                                                </div>

                                                <div>
                                                    <label class="mb-1 block text-xs font-bold">Status Bahan</label>
                                                    <select name="is_active" required class="w-full border-2 border-black bg-white px-3 py-2 text-sm">
                                                        <option value="1" @selected($ingredient->is_active)>Aktif</option>
                                                        <option value="0" @selected(!$ingredient->is_active)>Tidak Aktif</option>
                                                    </select>
                                                </div>

                                                <button
                                                    type="submit"
                                                    class="w-full border-2 border-black bg-black px-4 py-2.5 text-sm font-bold text-white hover:bg-[#493225]"
                                                >
                                                    Simpan Perubahan
                                                </button>
                                            </form>
                                        </div>
                                    </details>

                                    <form
                                        method="POST"
                                        action="{{ route('manajer.ingredients.destroy', $ingredient) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus bahan {{ addslashes($ingredient->name) }}?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="border-2 border-black bg-white px-3 py-2 text-xs font-bold text-[#A33A2B] hover:bg-gray-200"
                                        >
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <p class="font-bold text-black">Belum ada bahan baku</p>
                                <p class="mt-1 text-sm text-gray-600">
                                    Tambahkan bahan baku melalui formulir di atas.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        @if ($ingredients->hasPages())
            <div class="border-t-2 border-black px-5 py-4">
                {{ $ingredients->links() }}
            </div>
        @endif

    </section>

</div>

@endsection
