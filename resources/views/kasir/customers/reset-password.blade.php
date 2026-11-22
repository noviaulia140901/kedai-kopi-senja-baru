
@extends('layouts.kasir')

@section('page-title', 'Reset Password Pelanggan')

@section('content')

<style>
    body:has(.kasir-reset-password-page) main {
        background-color: #F1ECD8;
    }
</style>

<div class="kasir-reset-password-page max-w-4xl">

    <div class="bg-[#F1ECD8] rounded-xl shadow-sm border border-[#828854]/40 p-6">

        <div class="mb-6">
            <h3 class="text-lg font-bold text-[#4E3936]">
                Reset Password Akun Pelanggan
            </h3>

            <p class="text-sm text-[#4E3936]/70 mt-2">
                Masukkan nomor HP pelanggan untuk membuat password sementara.
            </p>
        </div>

        @if (session('temporary_password'))
            <div class="mb-5 rounded-lg bg-[#F1ECD8] border border-[#465A3D]/40 p-4">
                <p class="text-sm font-semibold text-[#465A3D]">
                    Password sementara berhasil dibuat.
                </p>

                <p class="text-sm text-[#465A3D] mt-2">
                    Password sementara:
                    <strong>{{ session('temporary_password') }}</strong>
                </p>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 rounded-lg bg-[#F1ECD8] border border-[#640017]/40 p-4">
                @foreach ($errors->all() as $error)
                    <p class="text-sm text-[#640017]">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('kasir.customers.reset-password.store') }}">
            @csrf

            <div class="mb-6">
                <label for="phone" class="block text-sm font-semibold text-[#4E3936] mb-2">
                    Nomor HP Pelanggan
                </label>

                <input
                    id="phone"
                    name="phone"
                    type="text"
                    value="{{ old('phone') }}"
                    required
                    placeholder="Masukkan nomor HP pelanggan"
                    class="w-full rounded-lg border border-[#828854] bg-[#fffdf5] text-[#4E3936] focus:border-[#465A3D] focus:ring-[#465A3D]"
                >

                @error('phone')
                    <p class="text-sm text-[#640017] mt-2">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="px-6 py-3 rounded-lg bg-[#465A3D] text-[#F1ECD8] font-semibold hover:bg-[#35472F] transition"
            >
                Reset Password
            </button>
        </form>

    </div>

</div>

@endsection
