@extends('layouts.kasir')

@section('page-title', 'Struk Pesanan')

@section('content')
<style>
    @media print {
        @page {
            margin: 12mm;
        }

        html,
        body,
        body > div,
        body > div > div,
        main {
            height: auto !important;
            overflow: visible !important;
        }

        body {
            background: white !important;
        }

        body > div {
            display: block !important;
        }

        aside,
        header,
        nav,
        .no-print {
            display: none !important;
        }

        main {
            padding: 0 !important;
        }

        .receipt-page {
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .receipt-card {
            border: 0 !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
    }
</style>

<section class="receipt-page mx-auto w-full max-w-2xl">
    <article class="receipt-card rounded-lg border-2 border-senja-brown bg-white p-5 shadow-sm sm:p-8">
        <div class="mb-5 border-b-2 border-senja-brown pb-5 text-center">
            <h1 class="text-2xl font-bold text-senja-brown">
                Kedai Kopi Senja
            </h1>
            <p class="mt-1 text-sm text-senja-olive">
                Struk Pembayaran
            </p>
        </div>

        <dl class="mb-5 space-y-2 text-sm">
            <div class="flex justify-between gap-4">
                <dt class="text-gray-600">Kode Pesanan</dt>
                <dd class="text-right font-semibold text-senja-brown">
                    {{ $order->order_code }}
                </dd>
            </div>

            <div class="flex justify-between gap-4">
                <dt class="text-gray-600">Tanggal dan Waktu</dt>
                <dd class="text-right text-senja-brown">
                    {{ $order->ordered_at?->format('d/m/Y H:i') ?? '-' }}
                </dd>
            </div>

            <div class="flex justify-between gap-4">
                <dt class="text-gray-600">Kasir</dt>
                <dd class="text-right text-senja-brown">
                    {{ $order->cashier?->name ?? '-' }}
                </dd>
            </div>

            <div class="flex justify-between gap-4">
                <dt class="text-gray-600">Metode Pembayaran</dt>
                <dd class="text-right font-semibold text-senja-brown">
                    {{ $order->payment_method === 'qris' ? 'QRIS' : ($order->payment_method === 'tunai' ? 'Tunai' : '-') }}
                </dd>
            </div>
        </dl>

        <div class="mb-5 overflow-x-auto border-y-2 border-senja-brown py-4">
            <h2 class="mb-3 font-semibold text-senja-brown">
                Detail Pesanan
            </h2>

            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-senja-brown/30 text-left text-senja-olive">
                        <th class="py-2 pr-2 font-semibold">Menu</th>
                        <th class="whitespace-nowrap px-2 py-2 text-center font-semibold">Jumlah</th>
                        <th class="whitespace-nowrap px-2 py-2 text-right font-semibold">Harga Satuan</th>
                        <th class="whitespace-nowrap py-2 pl-2 text-right font-semibold">Subtotal</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-senja-brown/20">
                    @forelse ($order->items as $item)
                        <tr>
                            <td class="py-3 pr-2 text-senja-brown">
                                {{ $item->menu_name }}
                            </td>
                            <td class="px-2 py-3 text-center text-senja-brown">
                                {{ $item->quantity }}
                            </td>
                            <td class="whitespace-nowrap px-2 py-3 text-right text-senja-brown">
                                Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}
                            </td>
                            <td class="whitespace-nowrap py-3 pl-2 text-right font-semibold text-senja-brown">
                                Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500">
                                Tidak ada rincian menu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <dl class="space-y-3 text-sm">
            <div class="flex justify-between gap-4">
                <dt class="text-gray-600">Subtotal</dt>
                <dd class="text-right text-senja-brown">
                    Rp {{ number_format((float) $order->subtotal, 0, ',', '.') }}
                </dd>
            </div>

            <div class="flex justify-between gap-4">
                <dt class="text-gray-600">Biaya Layanan (5%)</dt>
                <dd class="text-right text-senja-brown">
                    Rp {{ number_format((float) $order->service_charge, 0, ',', '.') }}
                </dd>
            </div>

            <div class="flex justify-between gap-4">
                <dt class="text-gray-600">Pajak (10%)</dt>
                <dd class="text-right text-senja-brown">
                    Rp {{ number_format((float) $order->tax, 0, ',', '.') }}
                </dd>
            </div>

            <div class="flex justify-between gap-4 border-t-2 border-senja-brown pt-4 text-lg font-bold">
                <dt class="text-senja-brown">Total Pembayaran</dt>
                <dd class="text-right text-senja-brown">
                    Rp {{ number_format((float) $order->total, 0, ',', '.') }}
                </dd>
            </div>
        </dl>
    </article>

    <div class="no-print mt-5 flex flex-col gap-3 sm:flex-row">

        <a
            href="{{ route('kasir.orders.index') }}"
            class="flex-1 rounded-md border-2 border-senja-brown bg-white px-4 py-3 text-center font-semibold text-senja-brown transition hover:bg-senja-cream"
        >
            Kembali ke Daftar Pesanan
        </a>
    </div>
</section>
@endsection
