@extends('layouts.kasir')

@section('page-title', 'Ganti Password')

@section('content')
<div class="cashier-password-page">

    <section class="password-panel p-6">
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </section>

</div>

<style>
    .cashier-password-page {
        color: #4E3936;
    }

    .password-panel {
        background-color: #F1ECD8 !important;
        border: 1px solid rgb(130 136 84 / 40%) !important;
        border-radius: 16px !important;
        box-shadow: 0 2px 5px rgb(78 57 54 / 5%) !important;
    }

    /* Judul dan teks */
    .password-panel h2,
    .password-panel h3,
    .password-panel p,
    .password-panel label {
        color: #4E3936 !important;
    }

    .password-panel .text-gray-900,
    .password-panel .text-gray-800,
    .password-panel .text-gray-700,
    .password-panel .text-gray-600,
    .password-panel .text-gray-500 {
        color: #4E3936 !important;
    }

    /* Input */
    .password-panel input,
    .password-panel select,
    .password-panel textarea {
        background-color: #fffdf5 !important;
        border: 1px solid #828854 !important;
        color: #4E3936 !important;
        border-radius: 12px !important;
    }

    .password-panel input:focus,
    .password-panel select:focus,
    .password-panel textarea:focus {
        border-color: #465A3D !important;
        --tw-ring-color: #465A3D !important;
    }

    /* Tombol */
    .password-panel button[type="submit"] {
        background-color: #465A3D !important;
        border-color: #465A3D !important;
        color: #F1ECD8 !important;
        border-radius: 12px !important;
    }

    .password-panel button[type="submit"]:hover {
        background-color: #35472F !important;
    }

    /* Pesan berhasil dan error */
    .password-panel .text-green-600,
    .password-panel .text-green-500 {
        color: #465A3D !important;
    }

    .password-panel .text-red-600,
    .password-panel .text-red-500 {
        color: #640017 !important;
    }
</style>
@endsection
