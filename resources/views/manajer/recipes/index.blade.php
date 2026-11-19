@extends('layouts.manajer')

@section('content')
<div class="space-y-6">

    {{-- Judul halaman --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Resep</h1>
            <p class="mt-1 text-sm text-gray-600">
                Atur bahan baku dan jumlah pemakaian untuk setiap menu.
            </p>
        </div>
        <div class="border-2 border-gray-800 px-4 py-2 text-sm font-semibold">
            Total: {{ $recipes->total() }} resep
        </div>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="border border-green-700 bg-green-50 p-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="border border-red-700 bg-red-50 p-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="border border-red-700 bg-red-50 p-3 text-sm text-red-800">
            <p class="mb-2 font-bold">Periksa kembali data berikut:</p>
            <ul class="list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form tambah resep --}}
    <section class="border-2 border-gray-800 bg-white">
        <div class="border-b-2 border-gray-800 bg-gray-100 px-5 py-4">
            <h2 class="text-lg font-bold text-gray-900">Tambah Bahan Resep</h2>
            <p class="mt-1 text-sm text-gray-600">
                Pilih menu, bahan baku, dan jumlah pemakaiannya.
            </p>
        </div>

        <form action="{{ route('manajer.recipes.store') }}" method="POST" class="space-y-5 p-5">
            @csrf

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="menu_id" class="mb-2 block text-sm font-semibold text-gray-800">
                        Menu
                    </label>
                    <select
                        name="menu_id"
                        id="menu_id"
                        required
                        class="w-full border-2 border-gray-400 bg-white px-3 py-3 text-sm focus:border-gray-900 focus:outline-none"
                    >
                        <option value="">Pilih menu</option>
                        @foreach ($menus as $menu)
                            <option value="{{ $menu->id }}" @selected(old('menu_id') == $menu->id)>
                                {{ $menu->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="ingredient_id" class="mb-2 block text-sm font-semibold text-gray-800">
                        Bahan Baku
                    </label>
                    <select
                        name="ingredient_id"
                        id="ingredient_id"
                        required
                        class="w-full border-2 border-gray-400 bg-white px-3 py-3 text-sm focus:border-gray-900 focus:outline-none"
                    >
                        <option value="">Pilih bahan baku</option>
                        @foreach ($ingredients as $ingredient)
                            <option value="{{ $ingredient->id }}" @selected(old('ingredient_id') == $ingredient->id)>
                                {{ $ingredient->name }} ({{ $ingredient->base_unit }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="quantity" class="mb-2 block text-sm font-semibold text-gray-800">
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
                        class="w-full border-2 border-gray-400 px-3 py-3 text-sm focus:border-gray-900 focus:outline-none"
                    >
                </div>
            </div>

            <div class="border-t border-gray-300 pt-4">
                <button
                    type="submit"
                    class="border-2 border-gray-900 bg-gray-900 px-6 py-3 text-sm font-bold text-white hover:bg-gray-700"
                >
                    + Tambah Bahan Resep
                </button>
            </div>
        </form>
    </section>

    {{-- Daftar resep --}}
    <section class="border-2 border-gray-800 bg-white">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b-2 border-gray-800 bg-gray-100 px-5 py-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Daftar Resep</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Daftar bahan baku yang digunakan pada setiap menu.
                </p>
            </div>
            <span class="text-sm text-gray-600">
                {{ $recipes->firstItem() ?? 0 }}–{{ $recipes->lastItem() ?? 0 }}
                dari {{ $recipes->total() }} data
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px] text-left text-sm">
                <thead class="border-b-2 border-gray-800 bg-gray-200 text-gray-900">
                    <tr>
                        <th class="px-5 py-4 font-bold">No.</th>
                        <th class="px-5 py-4 font-bold">Menu</th>
                        <th class="px-5 py-4 font-bold">Bahan Baku</th>
                        <th class="px-5 py-4 font-bold">Jumlah Pemakaian</th>
                        <th class="px-5 py-4 text-center font-bold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-300">
                    @forelse ($recipes as $recipe)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4">
                                {{ $recipes->firstItem() + $loop->index }}
                            </td>
                            <td class="px-5 py-4 font-semibold text-gray-900">
                                {{ $recipe->menu->name }}
                            </td>
                            <td class="px-5 py-4">
                                {{ $recipe->ingredient->name }}
                            </td>
                            <td class="px-5 py-4">
                                {{ $recipe->quantity }} {{ $recipe->ingredient->base_unit }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="flex flex-wrap items-center justify-center gap-3">
                                    <button
                                        type="button"
                                        onclick="document.getElementById('edit-recipe-{{ $recipe->id }}').classList.toggle('hidden')"
                                        class="border border-gray-800 px-3 py-2 font-semibold text-gray-900 hover:bg-gray-100"
                                    >
                                        Edit
                                    </button>

                                    <form
                                        action="{{ route('manajer.recipes.destroy', $recipe) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus bahan resep ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="border border-red-700 px-3 py-2 font-semibold text-red-700 hover:bg-red-50"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Form edit --}}
                        <tr id="edit-recipe-{{ $recipe->id }}" class="hidden bg-gray-100">
                            <td colspan="5" class="border-y border-gray-400 p-5">
                                <div class="mb-4">
                                    <h3 class="font-bold text-gray-900">Edit Bahan Resep</h3>
                                    <p class="mt-1 text-sm text-gray-600">
                                        Perbarui menu, bahan baku, atau jumlah pemakaian.
                                    </p>
                                </div>

                                <form
                                    action="{{ route('manajer.recipes.update', $recipe) }}"
                                    method="POST"
                                    class="space-y-4"
                                >
                                    @csrf
                                    @method('PUT')

                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                        <div>
                                            <label class="mb-2 block text-sm font-semibold">Menu</label>
                                            <select
                                                name="menu_id"
                                                required
                                                class="w-full border-2 border-gray-400 bg-white px-3 py-3"
                                            >
                                                @foreach ($menus as $menu)
                                                    <option value="{{ $menu->id }}" @selected($recipe->menu_id == $menu->id)>
                                                        {{ $menu->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-semibold">Bahan Baku</label>
                                            <select
                                                name="ingredient_id"
                                                required
                                                class="w-full border-2 border-gray-400 bg-white px-3 py-3"
                                            >
                                                @foreach ($ingredients as $ingredient)
                                                    <option value="{{ $ingredient->id }}" @selected($recipe->ingredient_id == $ingredient->id)>
                                                        {{ $ingredient->name }} ({{ $ingredient->base_unit }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-semibold">Jumlah Pemakaian</label>
                                            <input
                                                type="number"
                                                name="quantity"
                                                value="{{ $recipe->quantity }}"
                                                min="0.001"
                                                step="0.001"
                                                required
                                                class="w-full border-2 border-gray-400 px-3 py-3"
                                            >
                                        </div>
                                    </div>

                                    <button
                                        type="submit"
                                        class="border-2 border-gray-900 bg-gray-900 px-5 py-3 text-sm font-bold text-white hover:bg-gray-700"
                                    >
                                        Simpan Perubahan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-gray-500">
                                Belum ada data resep.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t-2 border-gray-800 p-4">
            {{ $recipes->links() }}
        </div>
    </section>

</div>
@endsection
