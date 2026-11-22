
@extends('layouts.kasir')

@section('page-title', 'Daftar Pesanan')

@section('content')
@php
    $selectedStatus = request()->query('status');

    if (!in_array($selectedStatus, ['baru', 'diproses', 'selesai'], true)) {
        $selectedStatus = '';
    }

    $statusFilters = [
        ['label' => 'Semua', 'value' => ''],
        ['label' => 'Baru', 'value' => 'baru'],
        ['label' => 'Diproses', 'value' => 'diproses'],
        ['label' => 'Selesai', 'value' => 'selesai'],
    ];
@endphp

<style>
    .cashier-orders-page {
        color: #4E3936;
    }

    .cashier-orders-page .order-notice {
        background: #F1ECD8;
        border-radius: 12px;
        padding: 16px;
    }

    .cashier-orders-page .notice-success {
        color: #465A3D;
        border: 1px solid #465A3D;
    }

    .cashier-orders-page .notice-error {
        color: #640017;
        border: 1px solid #640017;
    }

    .cashier-orders-page .status-filter {
        border: 1px solid #828854;
        border-radius: 999px;
        padding: 6px 16px;
        font-size: 14px;
        font-weight: 600;
        transition: .2s ease;
    }

    .cashier-orders-page .status-filter.active {
        background: #465A3D;
        color: #F1ECD8;
        border-color: #465A3D;
    }

    .cashier-orders-page .status-filter:not(.active) {
        background: #fffdf5;
        color: #4E3936;
    }

    .cashier-orders-page .status-filter:not(.active):hover {
        background: #465A3D;
        color: #F1ECD8;
        border-color: #465A3D;
    }

    .cashier-orders-page .orders-table {
        background: #F1ECD8;
        border: 1px solid rgb(130 136 84 / 40%);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgb(78 57 54 / 5%);
    }

    .cashier-orders-page table thead {
        background: #465A3D;
        color: #F1ECD8;
    }

    .cashier-orders-page table tbody {
        color: #4E3936;
    }

    .cashier-orders-page table tbody tr {
        border-bottom: 1px solid rgb(130 136 84 / 30%);
    }

    .cashier-orders-page table tbody tr:nth-child(even) {
        background: rgb(130 136 84 / 10%);
    }

    .cashier-orders-page table tbody tr:hover {
        background: rgb(130 136 84 / 16%);
    }

    .cashier-orders-page .status-badge {
        display: inline-flex;
        border-radius: 999px;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .cashier-orders-page .status-new {
        background: #efe8c7;
        color: #4E3936;
    }

    .cashier-orders-page .status-processing {
        background: rgb(130 136 84 / 20%);
        color: #465A3D;
    }

    .cashier-orders-page .status-completed {
        background: #465A3D;
        color: #F1ECD8;
    }

    .cashier-orders-page .status-cancelled {
        background: #640017;
        color: #F1ECD8;
    }

    .cashier-orders-page .status-other {
        background: #e8e2cf;
        color: #4E3936;
    }

    .cashier-orders-page .action-button {
        display: inline-block;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
        transition: .2s ease;
    }

    .cashier-orders-page .action-primary {
        background: #465A3D;
        color: #F1ECD8;
        border: 1px solid #465A3D;
    }

    .cashier-orders-page .action-primary:hover {
        background: #35472F;
    }

    .cashier-orders-page .action-secondary {
        background: #fffdf5;
        color: #465A3D;
        border: 1px solid #828854;
    }

    .cashier-orders-page .action-secondary:hover {
        background: #465A3D;
        color: #F1ECD8;
        border-color: #465A3D;
    }

    .cashier-orders-page .action-danger {
        background: #fffdf5;
        color: #640017;
        border: 1px solid #640017;
    }

    .cashier-orders-page .action-danger:hover {
        background: #640017;
        color: #F1ECD8;
    }

    .cashier-orders-page .empty-state {
        color: #4E3936;
    }
</style>

<div class="cashier-orders-page space-y-5">

    @if (session('success'))
        <div class="order-notice notice-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="order-notice notice-error" role="alert">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <section aria-labelledby="orders-heading">
        <h3 id="orders-heading" class="text-lg font-bold text-[#4E3936]">
            Daftar Pesanan Masuk
        </h3>

        <nav class="mt-3 flex flex-wrap gap-2" aria-label="Filter status pesanan">
            @foreach ($statusFilters as $filter)
                @php
                    $isActive = $selectedStatus === $filter['value'];
                    $filterUrl = $filter['value'] === ''
                        ? route('kasir.orders.index')
                        : route('kasir.orders.index', ['status' => $filter['value']]);
                @endphp

                <a href="{{ $filterUrl }}"
                    @if ($isActive) aria-current="page" @endif
                    class="status-filter {{ $isActive ? 'active' : '' }}">
                    {{ $filter['label'] }}
                </a>
            @endforeach
        </nav>
    </section>

    <section class="orders-table" aria-label="Daftar pesanan">
        <div class="overflow-x-auto">
            <table class="min-w-[980px] w-full text-left text-sm">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap px-3 py-3 font-bold">No</th>
                        <th class="whitespace-nowrap px-3 py-3 font-bold">Waktu</th>
                        <th class="whitespace-nowrap px-3 py-3 font-bold">Sumber</th>
                        <th class="px-3 py-3 font-bold">Item</th>
                        <th class="whitespace-nowrap px-3 py-3 font-bold">Total</th>
                        <th class="whitespace-nowrap px-3 py-3 font-bold">Metode</th>
                        <th class="whitespace-nowrap px-3 py-3 font-bold">Status</th>
                        <th class="whitespace-nowrap px-3 py-3 font-bold">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($orders as $order)
                        <tr class="align-middle">
                            <td class="whitespace-nowrap px-3 py-4 font-semibold">
                                {{ $order->order_code ?? '#'.str_pad((string) $order->id, 3, '0', STR_PAD_LEFT) }}
                            </td>

                            <td class="whitespace-nowrap px-3 py-4">
                                {{ $order->ordered_at?->format('H:i') ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-3 py-4">
                                @if ($order->table_number)
                                    Meja {{ $order->table_number }} ({{ strtoupper($order->order_channel) }})
                                @else
                                    {{ ucfirst($order->order_channel) }}
                                @endif
                            </td>

                            <td class="px-3 py-4">
                                @forelse ($order->items as $item)
                                    <p>{{ $item->menu_name }} x{{ $item->quantity }}</p>
                                @empty
                                    <span class="text-[#828854]">-</span>
                                @endforelse
                            </td>

                            <td class="whitespace-nowrap px-3 py-4 font-semibold">
                                {{ number_format((float) $order->total, 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-3 py-4">
                                {{ strtoupper($order->payment_method ?? '-') }}
                            </td>

                            <td class="whitespace-nowrap px-3 py-4">
                                @if ($order->status === 'baru')
                                    <span class="status-badge status-new">Baru</span>
                                @elseif ($order->status === 'diproses')
                                    <span class="status-badge status-processing">Diproses</span>
                                @elseif ($order->status === 'selesai')
                                    <span class="status-badge status-completed">Selesai</span>
                                @elseif ($order->status === 'dibatalkan')
                                    <span class="status-badge status-cancelled">Batal</span>
                                @else
                                    <span class="status-badge status-other">
                                        {{ ucfirst($order->status ?? 'Tidak diketahui') }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-3 py-4">
                                <div class="flex flex-wrap items-center gap-2">
                                    @if ($order->status === 'baru')
                                        <form method="POST" action="{{ route('kasir.orders.status', $order->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="diproses">

                                            <button type="submit" class="action-button action-primary">
                                                Proses
                                            </button>
                                        </form>

                                        <form method="POST"
                                            action="{{ route('kasir.orders.cancel', $order->id) }}"
                                            onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="action-button action-danger">
                                                Batalkan
                                            </button>
                                        </form>
                                    @elseif ($order->status === 'diproses')
                                        <form method="POST" action="{{ route('kasir.orders.status', $order->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="selesai">

                                            <button type="submit" class="action-button action-primary">
                                                Selesai
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('kasir.orders.receipt', $order->id) }}"
                                        class="action-button action-secondary">
                                        Struk
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-state px-6 py-12 text-center">
                                <p class="font-semibold">Belum ada pesanan.</p>
                                <p class="mt-1 text-sm text-[#828854]">
                                    Pesanan yang dibuat melalui kasir akan muncul di sini.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="border-t border-[#828854]/40 px-4 py-3 sm:px-6">
                {{ $orders->links() }}
            </div>
        @endif
    </section>
</div>
@endsection
