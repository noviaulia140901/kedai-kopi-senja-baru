
@extends('layouts.manajer')

@section('content')
@php
    // Menentukan tab laporan yang sedang dipilih.
    $tabAktif = request('tab', 'penjualan');

    // Memastikan tab yang dipilih termasuk tab yang tersedia.
    if (!in_array($tabAktif, ['penjualan', 'bahan-menipis', 'margin-menu'])) {
        $tabAktif = 'penjualan';
    }
@endphp

<style>
    body:has(.manager-reports-page) main {
        background-color: #F1ECD8;
    }

    .manager-reports-page {
        color: #4E3936;
    }

    /* Banner halaman */
    .manager-reports-page .page-banner {
        background: #465A3D;
        color: #F1ECD8;
        border-radius: 20px;
        padding: 28px;
    }

    .manager-reports-page .banner-label {
        color: #EFE8C7;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
    }

    .manager-reports-page .banner-title {
        margin-top: 8px;
        font-size: 24px;
        line-height: 1.3;
        font-weight: 700;
    }

    .manager-reports-page .banner-description {
        margin-top: 6px;
        color: rgb(241 236 216 / 85%);
        font-size: 14px;
        line-height: 1.5;
    }

    /* Tab laporan */
    .manager-reports-page .report-tabs {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
    }

    .manager-reports-page .report-tab {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 10px 20px;
        border: 1px solid #828854;
        border-radius: 12px;
        background: #fffdf5;
        color: #4E3936;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: .2s ease;
    }

    .manager-reports-page .report-tab:hover {
        background: rgb(130 136 84 / 12%);
    }

    .manager-reports-page .report-tab.active {
        border-color: #640017;
        background: #640017;
        color: #F1ECD8;
    }

    .manager-reports-page .report-tab.active:hover {
        background: #4f0012;
    }

    /* Filter periode */
    .manager-reports-page .filter-form {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 16px;
    }

    .manager-reports-page .filter-field {
        width: 100%;
    }

    .manager-reports-page .filter-label {
        display: block;
        margin-bottom: 8px;
        color: #4E3936;
        font-size: 14px;
        font-weight: 700;
    }

    .manager-reports-page .field-input {
        width: 100%;
        min-height: 48px;
        padding: 12px 16px;
        border: 1px solid #828854;
        border-radius: 12px;
        background: #fffdf5;
        color: #4E3936;
        font-size: 14px;
        outline: none;
        transition: .2s ease;
    }

    .manager-reports-page .field-input:focus {
        border-color: #465A3D;
        box-shadow: 0 0 0 3px rgb(70 90 61 / 12%);
    }

    .manager-reports-page .filter-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .manager-reports-page .primary-button,
    .manager-reports-page .secondary-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 48px;
        padding: 12px 20px;
        border: 1px solid transparent;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: .2s ease;
    }

    .manager-reports-page .primary-button {
        background: #640017;
        border-color: #640017;
        color: #F1ECD8;
    }

    .manager-reports-page .primary-button:hover {
        background: #4f0012;
        border-color: #4f0012;
    }

    .manager-reports-page .secondary-button {
        background: #fffdf5;
        border-color: #828854;
        color: #4E3936;
    }

    .manager-reports-page .secondary-button:hover {
        background: rgb(130 136 84 / 12%);
    }

    .manager-reports-page .validation-error {
        margin-top: 6px;
        color: #640017;
        font-size: 12px;
    }

    /* Panel laporan */
    .manager-reports-page .report-panel {
        overflow: hidden;
        border: 1px solid rgb(130 136 84 / 40%);
        border-radius: 16px;
        background: #F1ECD8;
        box-shadow: 0 2px 5px rgb(78 57 54 / 5%);
    }

    .manager-reports-page .panel-header {
        padding: 20px 24px;
        border-bottom: 1px solid rgb(130 136 84 / 40%);
        background: #F1ECD8;
    }

    .manager-reports-page .panel-title {
        color: #4E3936;
        font-size: 18px;
        font-weight: 700;
        line-height: 1.4;
    }

    .manager-reports-page .panel-description {
        margin-top: 6px;
        color: rgb(78 57 54 / 75%);
        font-size: 13px;
        line-height: 1.5;
    }

    .manager-reports-page .panel-content {
        padding: 24px;
    }

    /* Grafik penjualan */
    .manager-reports-page .chart-row {
        display: grid;
        grid-template-columns: 100px minmax(0, 1fr) 65px;
        align-items: center;
        gap: 12px;
    }

    .manager-reports-page .chart-name {
        overflow: hidden;
        color: #4E3936;
        font-size: 13px;
        font-weight: 500;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .manager-reports-page .chart-track {
        width: 100%;
        height: 28px;
        overflow: hidden;
        border-radius: 6px;
        background: rgb(130 136 84 / 12%);
    }

    .manager-reports-page .chart-bar {
        height: 28px;
        border-radius: 6px;
        background: #640017;
        transition: width .3s ease;
    }

    .manager-reports-page .chart-value {
        color: #4E3936;
        font-size: 12px;
        font-weight: 700;
        text-align: right;
        white-space: nowrap;
    }

    .manager-reports-page .chart-value span {
        color: rgb(78 57 54 / 70%);
        font-weight: 400;
    }

    .manager-reports-page .report-total {
        margin-top: 28px;
        padding-top: 16px;
        border-top: 1px solid rgb(130 136 84 / 40%);
        text-align: right;
    }

    .manager-reports-page .report-total-label {
        color: rgb(78 57 54 / 70%);
        font-size: 13px;
        font-weight: 500;
    }

    .manager-reports-page .report-total-value {
        margin-top: 4px;
        color: #465A3D;
        font-size: 24px;
        font-weight: 700;
    }

    /* Tabel laporan */
    .manager-reports-page .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .manager-reports-page .report-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 14px;
    }

    .manager-reports-page .report-table thead {
        background: #465A3D;
        color: #F1ECD8;
    }

    .manager-reports-page .report-table th {
        padding: 14px 16px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .manager-reports-page .report-table td {
        padding: 14px 16px;
        border-bottom: 1px solid rgb(130 136 84 / 25%);
        color: #4E3936;
    }

    .manager-reports-page .report-table tbody tr:nth-child(even) {
        background: rgb(130 136 84 / 10%);
    }

    .manager-reports-page .report-table tbody tr:hover {
        background: rgb(130 136 84 / 16%);
    }

    .manager-reports-page .report-table .text-right {
        text-align: right;
    }

    .manager-reports-page .report-table .text-center {
        text-align: center;
    }

    /* Status laporan */
    .manager-reports-page .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .manager-reports-page .status-positive {
        background: rgb(70 90 61 / 12%);
        color: #465A3D;
    }

    .manager-reports-page .status-neutral {
        background: rgb(78 57 54 / 8%);
        color: #4E3936;
    }

    .manager-reports-page .status-negative {
        background: rgb(100 0 23 / 10%);
        color: #640017;
    }

    .manager-reports-page .status-warning {
        background: rgb(130 136 84 / 12%);
        color: #62683d;
    }

    .manager-reports-page .empty-state {
        padding: 40px 16px;
        color: rgb(78 57 54 / 70%);
        font-size: 14px;
        text-align: center;
    }

    .manager-reports-page .access-note {
        padding-top: 4px;
        color: rgb(78 57 54 / 70%);
        font-size: 12px;
    }

    @media (min-width: 768px) {
        .manager-reports-page .filter-form {
            flex-direction: row;
            align-items: flex-end;
        }

        .manager-reports-page .filter-field {
            max-width: 220px;
        }

        .manager-reports-page .filter-actions {
            flex-wrap: nowrap;
        }

        .manager-reports-page .chart-row {
            grid-template-columns: 135px minmax(0, 1fr) 85px;
            gap: 16px;
        }

        .manager-reports-page .chart-name {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .manager-reports-page .page-banner {
            padding: 22px 18px;
        }

        .manager-reports-page .panel-header,
        .manager-reports-page .panel-content {
            padding: 18px;
        }

        .manager-reports-page .chart-row {
            grid-template-columns: 80px minmax(0, 1fr) 58px;
            gap: 8px;
        }

        .manager-reports-page .chart-name {
            font-size: 11px;
        }

        .manager-reports-page .chart-value {
            font-size: 11px;
        }

        .manager-reports-page .report-tab {
            flex: 1 1 auto;
            padding: 10px 12px;
            font-size: 12px;
        }
    }
</style>

<div class="manager-reports-page space-y-7">

    <!-- Banner judul halaman laporan -->
    <div class="page-banner">
        <p class="banner-label">Panel Manajer</p>

        <h1 class="banner-title">Laporan Usaha</h1>

        <p class="banner-description">
            Ringkasan penjualan, stok bahan baku, dan margin keuntungan menu.
        </p>
    </div>

    <!-- Tab untuk memilih jenis laporan -->
    <nav class="report-tabs" aria-label="Kategori laporan">

        <a href="{{ route('manajer.reports.index', array_merge(request()->except('tab'), ['tab' => 'penjualan'])) }}"
            class="report-tab {{ $tabAktif === 'penjualan' ? 'active' : '' }}">
            Penjualan per Menu
        </a>

        <a href="{{ route('manajer.reports.index', array_merge(request()->except('tab'), ['tab' => 'bahan-menipis'])) }}"
            class="report-tab {{ $tabAktif === 'bahan-menipis' ? 'active' : '' }}">
            Bahan Menipis
        </a>

        <a href="{{ route('manajer.reports.index', array_merge(request()->except('tab'), ['tab' => 'margin-menu'])) }}"
            class="report-tab {{ $tabAktif === 'margin-menu' ? 'active' : '' }}">
            Margin per Menu
        </a>
    </nav>

    <!-- Filter periode laporan -->
    <section>
        <form action="{{ route('manajer.reports.index') }}" method="GET"
            class="filter-form">

            <!-- Mempertahankan tab setelah filter dikirim -->
            <input type="hidden" name="tab" value="{{ $tabAktif }}">

            <div class="filter-field">
                <label for="tanggal_mulai" class="filter-label">
                    Periode Awal
                </label>

                <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                    value="{{ request('tanggal_mulai', $tanggalMulai) }}"
                    class="field-input">

                @error('tanggal_mulai')
                    <p class="validation-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="filter-field">
                <label for="tanggal_akhir" class="filter-label">
                    Periode Akhir
                </label>

                <input type="date" id="tanggal_akhir" name="tanggal_akhir"
                    value="{{ request('tanggal_akhir', $tanggalAkhir) }}"
                    class="field-input">

                @error('tanggal_akhir')
                    <p class="validation-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="filter-actions">
                <button type="submit" class="primary-button">
                    Tampilkan
                </button>

                <a href="{{ route('manajer.reports.index', ['tab' => $tabAktif]) }}"
                    class="secondary-button">
                    Reset
                </a>
            </div>
        </form>
    </section>

    <!-- Konten tab penjualan per menu -->
    @if ($tabAktif === 'penjualan')
        <section class="report-panel">

            <div class="panel-header">
                <h2 class="panel-title">
                    Laporan Penjualan per Menu
                    ({{ \Carbon\Carbon::parse($tanggalMulai)->format('d') }}
                    - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }})
                </h2>
            </div>

            <div class="panel-content">
                @if ($penjualan->isNotEmpty())
                    @php
                        // Mencari jumlah penjualan tertinggi untuk menentukan panjang batang.
                        $penjualanMaksimum = (float) $penjualan->max('total_terjual');
                    @endphp

                    <div class="space-y-5">
                        @foreach ($penjualan as $item)
                            @php
                                // Menghitung persentase panjang batang grafik.
                                $nilaiPenjualan = (float) $item->total_terjual;

                                $lebarBatang = $penjualanMaksimum > 0
                                    ? ($nilaiPenjualan / $penjualanMaksimum) * 100
                                    : 0;
                            @endphp

                            <div class="chart-row">

                                <!-- Nama menu -->
                                <div class="chart-name"
                                    title="{{ $item->menu_name }}">
                                    {{ $item->menu_name }}
                                </div>

                                <!-- Batang grafik penjualan -->
                                <div class="chart-track">
                                    <div class="chart-bar"
                                        style="width: {{ $lebarBatang }}%">
                                    </div>
                                </div>

                                <!-- Jumlah porsi terjual -->
                                <div class="chart-value">
                                    {{ number_format($item->total_terjual, 0, ',', '.') }}
                                    <span>porsi</span>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <!-- Total penjualan selama periode -->
                    <div class="report-total">
                        <p class="report-total-label">
                            Total Keseluruhan Penjualan
                        </p>

                        <p class="report-total-value">
                            Rp{{ number_format($penjualan->sum('total_penjualan'), 0, ',', '.') }}
                        </p>
                    </div>
                @else
                    <div class="empty-state">
                        Belum ada penjualan selesai pada periode ini.
                    </div>
                @endif
            </div>
        </section>
    @endif

    <!-- Konten tab bahan baku menipis -->
    @if ($tabAktif === 'bahan-menipis')
        <section class="report-panel">

            <div class="panel-header">
                <h2 class="panel-title">
                    Bahan Baku Menipis
                </h2>

                <p class="panel-description">
                    Bahan aktif dengan stok yang sudah mencapai atau di bawah batas minimum.
                </p>
            </div>

            <div class="panel-content">
                <div class="table-wrapper">
                    <table class="report-table" style="min-width: 500px;">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Bahan</th>
                                <th class="text-right">Stok Saat Ini</th>
                                <th class="text-right">Batas Minimum</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($bahanMenipis as $bahan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td style="font-weight: 600;">
                                        {{ $bahan->name }}
                                    </td>

                                    <td class="text-right">
                                        {{ number_format((float) $bahan->current_stock, 3, ',', '.') }}
                                        {{ $bahan->base_unit }}
                                    </td>

                                    <td class="text-right">
                                        {{ number_format((float) $bahan->minimum_stock, 3, ',', '.') }}
                                        {{ $bahan->base_unit }}
                                    </td>

                                    <td class="text-center">
                                        <span class="status-badge status-negative">
                                            Perlu Restok
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="empty-state">
                                        Tidak ada bahan baku yang menipis.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    @endif

    <!-- Konten tab margin keuntungan menu -->
    @if ($tabAktif === 'margin-menu')
        <section class="report-panel">

            <div class="panel-header">
                <h2 class="panel-title">
                    Margin Keuntungan Menu
                </h2>

                <p class="panel-description">
                    Perbandingan harga jual menu dengan Harga Pokok Produksi (HPP).
                </p>
            </div>

            <div class="panel-content">
                <div class="table-wrapper">
                    <table class="report-table" style="min-width: 650px;">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Menu</th>
                                <th class="text-right">Harga Jual</th>
                                <th class="text-right">Margin</th>
                                <th class="text-center">Keterangan</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($menus as $menu)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td style="font-weight: 600;">
                                        {{ $menu->name }}
                                    </td>

                                    <td class="text-right">
                                        Rp{{ number_format((float) $menu->selling_price, 0, ',', '.') }}
                                    </td>

                                    <td class="text-right" style="font-weight: 600;">
                                        @if ($menu->hpp_lengkap)
                                            Rp{{ number_format((float) $menu->margin, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if (!$menu->hpp_lengkap)
                                            <span class="status-badge status-warning">
                                                HPP Belum Lengkap
                                            </span>
                                        @elseif ($menu->margin > 0)
                                            <span class="status-badge status-positive">
                                                Menguntungkan
                                            </span>
                                        @elseif ($menu->margin == 0)
                                            <span class="status-badge status-neutral">
                                                Impas
                                            </span>
                                        @else
                                            <span class="status-badge status-negative">
                                                Rugi
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="empty-state">
                                        Belum ada data menu.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    @endif

    <!-- Keterangan hak akses halaman laporan -->
    <p class="access-note">
        Hanya Manajer yang bisa membuka halaman ini. Kasir/Pelanggan ditolak server.
    </p>

</div>
@endsection
