
@extends('layouts.kasir')

@section('page-title', 'Buat Pesanan')

@section('content')
@php
    $menuCategories = $menus->map(fn ($menu) => $menu->category?->name)->filter()->unique()->values();
@endphp

<style>
    body:has(.cashier-order-page) main { background: #F1ECD8; }

    .cashier-order-page { color: #4E3936; }
    .cashier-order-page .order-banner {
        background: #465A3D; color: #F1ECD8; border-radius: 20px; padding: 28px;
    }
    .cashier-order-page .order-panel {
        background: #F1ECD8; border: 1px solid rgb(130 136 84 / 40%);
        border-radius: 16px; padding: 20px 24px;
        box-shadow: 0 2px 5px rgb(78 57 54 / 5%); overflow: hidden;
    }
    .cashier-order-page h2 { color: #4E3936; }

    .cashier-order-page #searchMenu,
    .cashier-order-page #orderNotes {
        width: 100%; background: #fffdf5; color: #4E3936;
        border: 1px solid #828854; border-radius: 12px; padding: 10px 12px;
    }
    .cashier-order-page #searchMenu:focus,
    .cashier-order-page #orderNotes:focus {
        outline: none; border-color: #465A3D;
        box-shadow: 0 0 0 2px rgb(70 90 61 / 15%);
    }

    .cashier-order-page .category-filter,
    .cashier-order-page button[data-cart-action] {
        border: 1px solid #828854 !important; border-radius: 10px !important;
        font-weight: 600; transition: .2s ease;
    }
    .cashier-order-page .category-filter { padding: 8px 15px !important; }
    .cashier-order-page .category-filter.bg-senja-olive {
        background: #465A3D !important; color: #F1ECD8 !important;
    }
    .cashier-order-page .category-filter.bg-white {
        background: #fffdf5 !important; color: #4E3936 !important;
    }
    .cashier-order-page .category-filter:hover { border-color: #465A3D !important; }

    .cashier-order-page .menu-card {
        background: #fffdf5 !important; color: #4E3936 !important;
        border: 1px solid rgb(130 136 84 / 55%) !important;
        border-radius: 16px !important; padding: 12px !important;
        transition: .2s ease;
    }
    .cashier-order-page .menu-card:hover:not(:disabled) {
        background: #F1ECD8 !important; border-color: #465A3D !important;
        box-shadow: 0 4px 10px rgb(78 57 54 / 8%);
    }
    .cashier-order-page .menu-card:focus {
        outline: none; box-shadow: 0 0 0 2px rgb(70 90 61 / 20%);
    }
    .cashier-order-page .menu-card:disabled { background: #eee9d7 !important; opacity: .7; }

    .cashier-order-page .stock-badge {
        background: #465A3D !important; color: #F1ECD8 !important;
        border: 1px solid #F1ECD8;
    }
    .cashier-order-page #cartItems > div { border-color: rgb(130 136 84 / 35%); }
    .cashier-order-page .order-panel .border-gray-300,
    .cashier-order-page .order-panel .border-gray-400 { border-color: rgb(130 136 84 / 45%); }

    .cashier-order-page button[data-cart-action] {
        border-radius: 8px !important; color: #465A3D !important; background: #fffdf5 !important;
    }
    .cashier-order-page button[data-cart-action]:hover {
        background: #465A3D !important; color: #F1ECD8 !important;
    }
    .cashier-order-page .order-panel input[type="radio"] { accent-color: #465A3D; }
    .cashier-order-page .order-panel label:has(input:checked) {
        background: rgb(130 136 84 / 15%); border-color: #465A3D;
    }

    .cashier-order-page .order-primary-button,
    .cashier-order-page .order-secondary-button {
        border-radius: 12px; padding: 12px 16px; font-weight: 700; transition: .2s ease;
    }
    .cashier-order-page .order-primary-button {
        background: #465A3D; color: #F1ECD8; border: 1px solid #465A3D;
    }
    .cashier-order-page .order-primary-button:hover { background: #35472F; }
    .cashier-order-page .order-secondary-button {
        background: #F1ECD8; color: #465A3D; border: 1px solid #828854;
    }
    .cashier-order-page .order-secondary-button:hover {
        background: #465A3D; color: #F1ECD8;
    }

    .cashier-order-page .notice-success,
    .cashier-order-page .notice-error {
        background: #F1ECD8; border-radius: 12px; padding: 16px;
    }
    .cashier-order-page .notice-success { color: #465A3D; border: 1px solid #465A3D; }
    .cashier-order-page .notice-error { color: #640017; border: 1px solid #640017; }
    .cashier-order-page .menu-entry[hidden] { display: none !important; }
</style>

<div class="cashier-order-page space-y-6">

    <div class="order-banner">
        <p class="text-sm font-semibold tracking-wide text-[#EFE8C7]">KEDAI KOPI SENJA</p>
        <h1 class="mt-2 text-2xl font-bold">Buat Pesanan</h1>
        <p class="mt-2 text-sm text-[#F1ECD8]/85">Pilih menu dan atur pesanan pelanggan.</p>
    </div>

    @if (session('success'))
        <div class="notice-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="notice-error" role="alert">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form id="orderForm" method="POST" action="{{ route('kasir.orders.store') }}">
        @csrf

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

            {{-- DAFTAR MENU --}}
            <section class="space-y-5 xl:col-span-2" aria-labelledby="menu-heading">
                <div>
                    <h2 id="menu-heading" class="text-xl font-bold">Pilih Menu</h2>
                    <p class="mt-1 text-sm text-[#4E3936]/70">Pilih menu yang ingin dimasukkan ke pesanan.</p>
                </div>

                <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
                    <label class="sr-only" for="searchMenu">Cari menu</label>
                    <input type="search" id="searchMenu" placeholder="Cari menu..." class="min-w-0 flex-1">

                    <div class="flex flex-wrap gap-2" aria-label="Filter kategori">
                        <button type="button" class="category-filter bg-senja-olive text-white"
                            data-category-filter="*" aria-pressed="true">Semua</button>

                        @foreach ($menuCategories as $category)
                            <button type="button" class="category-filter bg-white text-senja-brown"
                                data-category-filter="{{ strtolower($category) }}" aria-pressed="false">
                                {{ $category }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div id="menuGrid" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse ($menus as $menu)
                        @php
                            $capacities = $menu->recipes->map(function ($recipe) {
                                $ingredient = $recipe->ingredient;
                                $required = (int) round((float) $recipe->quantity * 1000);

                                if (!$ingredient || !$ingredient->is_active || $required < 1) return 0;

                                $stock = (int) round((float) $ingredient->current_stock * 1000);
                                return intdiv(max(0, $stock), $required);
                            });

                            $available = $menu->recipes->isEmpty() ? 0 : (int) $capacities->min();
                            $orderable = $available > 0 && $menu->availability_status === 'Tersedia';
                            $selected = old('items.'.$menu->id.'.menu_id');
                        @endphp

                        <div class="menu-entry">
                            <input type="checkbox" class="menu-check sr-only"
                                name="items[{{ $menu->id }}][menu_id]" value="{{ $menu->id }}"
                                data-menu-id="{{ $menu->id }}" @checked($selected)
                                @disabled(!$orderable) aria-hidden="true" tabindex="-1">

                            <input type="hidden" class="menu-quantity"
                                name="items[{{ $menu->id }}][quantity]"
                                value="{{ old('items.'.$menu->id.'.quantity', 1) }}"
                                data-menu-id="{{ $menu->id }}" @disabled(!$selected || !$orderable)>

                            <button type="button" class="menu-card w-full text-left disabled:cursor-not-allowed"
                                data-menu-card data-menu-id="{{ $menu->id }}"
                                data-menu-name="{{ strtolower($menu->name) }}"
                                data-category="{{ strtolower($menu->category?->name ?? '') }}"
                                data-price="{{ number_format((float) $menu->selling_price, 2, '.', '') }}"
                                data-max-stock="{{ $available }}"
                                aria-pressed="{{ $selected ? 'true' : 'false' }}" @disabled(!$orderable)>

                                <span class="relative block h-24 overflow-hidden rounded-lg border border-[#828854]/40 bg-[#eee9d7]">
                                    @if ($menu->image)
                                        <img src="{{ asset('storage/' . $menu->image) }}" alt=""
                                            class="h-full w-full object-cover">
                                    @else
                                        <span class="flex h-full items-center justify-center text-xs font-semibold text-[#828854]">
                                            MENU
                                        </span>
                                    @endif

                                    <span class="stock-badge absolute right-2 top-2 flex h-9 min-w-9 items-center justify-center rounded-full px-2 text-xs font-bold"
                                        data-stock-badge aria-label="{{ $available }} porsi tersedia">
                                        {{ $available > 0 ? $available : 'HABIS' }}
                                    </span>
                                </span>

                                <span class="mt-3 block font-bold" data-menu-name-visible>{{ $menu->name }}</span>
                                <span class="mt-1 block text-sm font-semibold text-[#465A3D]">
                                    Rp {{ number_format($menu->selling_price, 0, ',', '.') }}
                                </span>
                                <span class="mt-3 block text-xs text-[#4E3936]/70" data-menu-hint>
                                    {{ $orderable ? 'Klik kartu = tambah 1 ke pesanan' : 'Tidak bisa dipesan' }}
                                </span>
                            </button>
                        </div>
                    @empty
                        <div class="col-span-full rounded-xl border border-[#828854]/40 bg-[#fffdf5] p-8 text-center text-[#4E3936]/70">
                            Belum ada menu aktif.
                        </div>
                    @endforelse

                    @if ($menus->isNotEmpty())
                        <p id="noMatchingMenus" class="col-span-full py-6 text-center text-sm text-[#4E3936]/60" hidden>
                            Menu tidak ditemukan.
                        </p>
                    @endif
                </div>
            </section>

            {{-- PANEL PESANAN --}}
            <aside class="order-panel flex flex-col xl:min-h-[42rem]">
                <div class="border-b border-[#828854]/40 pb-4">
                    <h2 class="text-lg font-bold">Pesanan Baru</h2>
                    <p class="mt-1 text-xs text-[#4E3936]/65">Daftar menu yang dipilih pelanggan.</p>
                </div>

                <div class="mt-4 grid grid-cols-[minmax(0,1fr)_auto_auto] gap-2 border-b border-[#828854]/40 pb-3 text-xs font-bold">
                    <span>Menu</span><span>Jumlah</span><span>Subtotal</span>
                </div>

                <div id="cartItems" class="min-h-32 flex-1 space-y-3 py-3">
                    <p class="py-6 text-center text-sm text-[#4E3936]/60">Belum ada menu dipilih.</p>
                </div>

                <div class="space-y-3 border-t border-[#828854]/50 pt-4 text-sm">
                    <div class="flex justify-between gap-3"><span>Subtotal</span><span id="subtotal" class="font-semibold">Rp 0</span></div>
                    <div class="flex justify-between gap-3"><span>Biaya Layanan (5%)</span><span id="serviceCharge">Rp 0</span></div>
                    <div class="flex justify-between gap-3"><span>Pajak (10%)</span><span id="tax">Rp 0</span></div>
                    <div class="flex justify-between gap-3 border-t border-[#828854]/50 pt-3 text-base font-bold">
                        <span>Total Bayar</span><span id="total" class="text-[#465A3D]">Rp 0</span>
                    </div>
                </div>

                <fieldset class="mt-5">
                    <legend class="mb-3 text-sm font-bold">Metode Pembayaran</legend>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach (['tunai' => 'Tunai', 'qris' => 'QRIS'] as $value => $label)
                            <label class="flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-[#828854] bg-[#fffdf5] px-2 py-3 text-sm font-semibold">
                                <input type="radio" name="payment_method" value="{{ $value }}"
                                    @checked(old('payment_method', 'tunai') === $value)>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                <div class="mt-4">
                    <label for="orderNotes" class="mb-2 block text-sm font-semibold">Catatan Pesanan</label>
                    <textarea id="orderNotes" name="notes" rows="2"
                        placeholder="Tambahkan catatan jika diperlukan...">{{ old('notes') }}</textarea>
                </div>

                <div class="mt-5 space-y-2">
                    <button type="submit" class="order-primary-button w-full">Simpan Pesanan</button>
                    <button type="button" id="clearCart" class="order-secondary-button w-full">Kosongkan</button>
                </div>
            </aside>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const cards = [...document.querySelectorAll('[data-menu-card]')];
    const entries = [...document.querySelectorAll('.menu-entry')];
    const checkboxes = new Map([...document.querySelectorAll('.menu-check')].map(el => [el.dataset.menuId, el]));
    const quantities = new Map([...document.querySelectorAll('.menu-quantity')].map(el => [el.dataset.menuId, el]));

    const cart = document.getElementById('cartItems');
    const search = document.getElementById('searchMenu');
    const filters = [...document.querySelectorAll('.category-filter')];
    const noResults = document.getElementById('noMatchingMenus');
    const rupiah = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 });

    let activeCategory = '*';
    const formatRupiah = amount => 'Rp ' + rupiah.format(amount);

    function cartButton(label, action, id, disabled = false) {
        const button = document.createElement('button');
        Object.assign(button, { type: 'button', textContent: label, disabled });
        button.dataset.cartAction = action;
        button.dataset.menuId = id;
        button.className = 'flex h-8 w-8 items-center justify-center rounded-lg border border-[#828854] font-bold text-[#465A3D] transition hover:bg-[#F1ECD8] disabled:cursor-not-allowed disabled:opacity-40';
        button.setAttribute('aria-label', action === 'increase' ? 'Tambah jumlah' : 'Kurangi jumlah');
        return button;
    }

    function updateCart() {
        let subtotal = 0;
        const selected = [];

        cards.forEach(card => {
            const id = card.dataset.menuId;
            const checkbox = checkboxes.get(id);
            const input = quantities.get(id);
            const maximum = Number.parseInt(card.dataset.maxStock, 10) || 0;
            const badge = card.querySelector('[data-stock-badge]');

            if (maximum < 1) {
                checkbox.checked = false;
                checkbox.disabled = input.disabled = card.disabled = true;
                input.value = '1';
                card.setAttribute('aria-pressed', 'false');
                badge.textContent = 'HABIS';
                card.classList.remove('bg-senja-cream');
                return;
            }

            checkbox.disabled = card.disabled = false;
            let quantity = 0;

            if (checkbox.checked) {
                quantity = Math.min(maximum, Math.max(1, Number.parseInt(input.value, 10) || 1));
                input.value = String(quantity);
                input.disabled = false;

                const price = Number.parseFloat(card.dataset.price) || 0;
                const amount = price * quantity;
                subtotal += amount;

                selected.push({
                    id,
                    name: card.querySelector('[data-menu-name-visible]').textContent.trim(),
                    quantity,
                    maximum,
                    amount
                });
            } else {
                input.value = '1';
                input.disabled = true;
            }

            const remaining = maximum - quantity;
            badge.textContent = remaining > 0 ? String(remaining) : 'HABIS';
            badge.setAttribute('aria-label', remaining + ' porsi tersedia');
            card.setAttribute('aria-pressed', checkbox.checked ? 'true' : 'false');
            card.classList.toggle('bg-senja-cream', checkbox.checked);
        });

        cart.replaceChildren();

        if (!selected.length) {
            const empty = document.createElement('p');
            empty.className = 'py-6 text-center text-sm text-[#4E3936]/60';
            empty.textContent = 'Belum ada menu dipilih.';
            cart.appendChild(empty);
        } else {
            selected.forEach(item => {
                const row = document.createElement('div');
                row.className = 'grid grid-cols-[minmax(0,1fr)_auto_auto] items-center gap-2 border-b border-[#828854]/35 pb-3 text-sm';

                const name = document.createElement('span');
                name.className = 'min-w-0 break-words font-medium';
                name.textContent = item.name;

                const controls = document.createElement('div');
                controls.className = 'flex items-center gap-1';
                controls.appendChild(cartButton('-', 'decrease', item.id));

                const count = document.createElement('span');
                count.className = 'min-w-5 text-center';
                count.textContent = String(item.quantity);
                controls.appendChild(count);
                controls.appendChild(cartButton('+', 'increase', item.id, item.quantity >= item.maximum));

                const amount = document.createElement('span');
                amount.className = 'whitespace-nowrap text-right font-semibold';
                amount.textContent = formatRupiah(item.amount);

                row.append(name, controls, amount);
                cart.appendChild(row);
            });
        }

        subtotal = Math.round(subtotal * 100) / 100;
        const service = Math.round(subtotal * .05 * 100) / 100;
        const tax = Math.round((subtotal + service) * .10 * 100) / 100;

        document.getElementById('subtotal').textContent = formatRupiah(subtotal);
        document.getElementById('serviceCharge').textContent = formatRupiah(service);
        document.getElementById('tax').textContent = formatRupiah(tax);
        document.getElementById('total').textContent = formatRupiah(subtotal + service + tax);
    }

    function filterMenus() {
        const keyword = search.value.trim().toLowerCase();
        let count = 0;

        entries.forEach(entry => {
            const card = entry.querySelector('[data-menu-card]');
            const visible = card.dataset.menuName.includes(keyword)
                && (activeCategory === '*' || card.dataset.category === activeCategory);

            entry.hidden = !visible;
            if (visible) count++;
        });

        if (noResults) noResults.hidden = count > 0;
    }

    cards.forEach(card => card.addEventListener('click', () => {
        const id = card.dataset.menuId;
        const checkbox = checkboxes.get(id);
        const input = quantities.get(id);
        const maximum = Number.parseInt(card.dataset.maxStock, 10) || 0;
        const current = checkbox.checked ? (Number.parseInt(input.value, 10) || 0) : 0;

        if (checkbox.disabled || current >= maximum) return;

        checkbox.checked = true;
        input.value = String(current + 1);
        updateCart();
    }));

    cart.addEventListener('click', event => {
        if (!(event.target instanceof Element)) return;

        const button = event.target.closest('button[data-cart-action]');
        if (!button || button.disabled) return;

        const id = button.dataset.menuId;
        const checkbox = checkboxes.get(id);
        const input = quantities.get(id);
        const current = Number.parseInt(input.value, 10) || 1;

        if (button.dataset.cartAction === 'increase') {
            input.value = String(current + 1);
        } else if (current === 1) {
            checkbox.checked = false;
        } else {
            input.value = String(current - 1);
        }

        updateCart();
    });

    filters.forEach(button => button.addEventListener('click', () => {
        activeCategory = button.dataset.categoryFilter;

        filters.forEach(filter => {
            const active = filter === button;
            filter.setAttribute('aria-pressed', active ? 'true' : 'false');
            filter.classList.toggle('bg-senja-olive', active);
            filter.classList.toggle('text-white', active);
            filter.classList.toggle('bg-white', !active);
            filter.classList.toggle('text-senja-brown', !active);
        });

        filterMenus();
    }));

    search.addEventListener('input', filterMenus);

    document.getElementById('clearCart').addEventListener('click', () => {
        checkboxes.forEach((checkbox, id) => {
            checkbox.checked = false;
            quantities.get(id).value = '1';
            quantities.get(id).disabled = true;
        });
        updateCart();
    });

    updateCart();
    filterMenus();
</script>
@endpush
