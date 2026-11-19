<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Pemesanan Kasir</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-3 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="p-3 bg-red-100 text-red-800 rounded">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- FORM PEMESANAN --}}
            <div class="bg-white p-6 shadow-sm rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Daftar Menu</h3>

                <form method="POST" action="{{ route('kasir.orders.store') }}">
                    @csrf

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b text-left">
                                    <th class="p-3">Pilih</th>
                                    <th class="p-3">Nama Menu</th>
                                    <th class="p-3">Kategori</th>
                                    <th class="p-3">Harga</th>
                                    <th class="p-3">Ketersediaan</th>
                                    <th class="p-3">Jumlah</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($menus as $menu)
                                    <tr class="border-b">
                                        <td class="p-3">
                                            @if ($menu->availability_status === 'Tersedia')
                                                <input type="checkbox"
                                                    class="menu-check"
                                                    name="items[{{ $menu->id }}][menu_id]"
                                                    value="{{ $menu->id }}"
                                                    data-menu="{{ $menu->id }}"
                                                    {{ old('items.'.$menu->id.'.menu_id') ? 'checked' : '' }}>
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td class="p-3">{{ $menu->name }}</td>
                                        <td class="p-3">{{ $menu->category->name ?? '-' }}</td>
                                        <td class="p-3">Rp{{ number_format($menu->selling_price, 0, ',', '.') }}</td>
                                        <td class="p-3">{{ $menu->availability_status }}</td>

                                        <td class="p-3">
                                            @if ($menu->availability_status === 'Tersedia')
                                                <input type="number"
                                                    class="menu-quantity border rounded p-2 w-20"
                                                    name="items[{{ $menu->id }}][quantity]"
                                                    data-menu="{{ $menu->id }}"
                                                    min="1"
                                                    value="{{ old('items.'.$menu->id.'.quantity', 1) }}">
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-3 text-center">
                                            Belum ada menu aktif.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-5 space-y-4">
                        <div>
                            <label class="block mb-1">Metode Pembayaran</label>
                            <select name="payment_method" class="border rounded p-2 w-full">
                                <option value="">Pilih pembayaran</option>
                                <option value="tunai" {{ old('payment_method') === 'tunai' ? 'selected' : '' }}>Tunai</option>
                                <option value="qris" {{ old('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-1">Catatan Pesanan</label>
                            <textarea name="notes" class="border rounded p-2 w-full" rows="2">{{ old('notes') }}</textarea>
                        </div>

                        <button class="px-5 py-2 bg-gray-800 text-white rounded">
                            Buat Pesanan
                        </button>
                    </div>
                </form>
            </div>

            {{-- RIWAYAT PESANAN --}}
            <div class="bg-white p-6 shadow-sm rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Riwayat Pesanan Kasir</h3>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b text-left">
                                <th class="p-3">Kode</th>
                                <th class="p-3">Tanggal</th>
                                <th class="p-3">Jumlah Menu</th>
                                <th class="p-3">Total</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($orders as $order)
                                <tr class="border-b">
                                    <td class="p-3">{{ $order->order_code }}</td>
                                    <td class="p-3">{{ $order->ordered_at }}</td>
                                    <td class="p-3">{{ $order->items->count() }}</td>
                                    <td class="p-3">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                                    <td class="p-3">{{ ucfirst($order->status) }}</td>

                                    <td class="p-3">
                                        @if ($order->status === 'baru')
                                            <form method="POST" action="{{ route('kasir.orders.status', $order->id) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="diproses">
                                                <button class="px-3 py-1 bg-blue-600 text-white rounded">
                                                    Proses
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('kasir.orders.cancel', $order->id) }}" class="inline"
                                                onsubmit="return confirm('Batalkan pesanan ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <button class="px-3 py-1 bg-red-600 text-white rounded">
                                                    Batalkan
                                                </button>
                                            </form>

                                        @elseif ($order->status === 'diproses')
                                            <form method="POST" action="{{ route('kasir.orders.status', $order->id) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="selesai">
                                                <button class="px-3 py-1 bg-green-600 text-white rounded">
                                                    Selesaikan
                                                </button>
                                            </form>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-3 text-center">
                                        Belum ada pesanan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.menu-check').forEach(function (checkbox) {
            let jumlah = document.querySelector(
                '.menu-quantity[data-menu="' + checkbox.dataset.menu + '"]'
            );

            if (jumlah) {
                jumlah.disabled = !checkbox.checked;
            }

            checkbox.addEventListener('change', function () {
                if (jumlah) {
                    jumlah.disabled = !this.checked;

                    if (this.checked && jumlah.value < 1) {
                        jumlah.value = 1;
                    }
                }
            });
        });
    </script>

</x-app-layout>
