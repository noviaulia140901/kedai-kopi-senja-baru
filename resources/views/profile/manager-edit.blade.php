@extends('layouts.manajer')

@section('content')
<div class="manager-profile-page space-y-6">

    {{-- Informasi Profil --}}
    <section class="profile-panel p-6">
        <div class="max-w-xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </section>

    {{-- Ganti Password --}}
    <section class="profile-panel p-6">
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </section>

</div>

<style>
    .manager-profile-page {
        color: #4E3936;
    }

    .profile-panel {
        background-color: #F1ECD8 !important;
        border: 1px solid rgb(130 136 84 / 40%) !important;
        border-radius: 16px !important;
        box-shadow: 0 2px 5px rgb(78 57 54 / 5%) !important;
    }

    /* Judul dan teks */
    .profile-panel h2,
    .profile-panel h3,
    .profile-panel p,
    .profile-panel label {
        color: #4E3936 !important;
    }

    .profile-panel .text-gray-900,
    .profile-panel .text-gray-800,
    .profile-panel .text-gray-700,
    .profile-panel .text-gray-600,
    .profile-panel .text-gray-500 {
        color: #4E3936 !important;
    }

    /* Input */
    .profile-panel input,
    .profile-panel select,
    .profile-panel textarea {
        background-color: #fffdf5 !important;
        border: 1px solid #828854 !important;
        color: #4E3936 !important;
        border-radius: 12px !important;
    }

    .profile-panel input:focus,
    .profile-panel select:focus,
    .profile-panel textarea:focus {
        border-color: #465A3D !important;
        --tw-ring-color: #465A3D !important;
    }

    /* Tombol */
    .profile-panel button[type="submit"] {
        background-color: #465A3D !important;
        border-color: #465A3D !important;
        color: #F1ECD8 !important;
        border-radius: 12px !important;
    }

    .profile-panel button[type="submit"]:hover {
        background-color: #35472F !important;
    }

    /* Pesan berhasil dan error */
    .profile-panel .text-green-600,
    .profile-panel .text-green-500 {
        color: #465A3D !important;
    }

    .profile-panel .text-red-600,
    .profile-panel .text-red-500 {
        color: #640017 !important;
    }
</style>
@endsection
