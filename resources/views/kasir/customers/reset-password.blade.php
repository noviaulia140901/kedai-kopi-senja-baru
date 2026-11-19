<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reset Password Pelanggan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">

                <h3 class="text-lg font-semibold mb-2">
                    Reset Password Akun Pelanggan
                </h3>

                <p class="text-sm text-gray-600 mb-6">
                    Masukkan nomor HP pelanggan untuk membuat password sementara.
                </p>

                @if (session('temporary_password'))
                    <div class="mb-6 rounded-md bg-green-100 p-4 text-green-800">
                        <p class="font-semibold">
                            Password sementara berhasil dibuat.
                        </p>

                        <p class="mt-2">
                            Password:
                            <strong>{{ session('temporary_password') }}</strong>
                        </p>

                        <p class="mt-2 text-sm">
                            Sampaikan password ini kepada pelanggan.
                            Pelanggan harus mengganti password setelah login di APK.
                        </p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-md bg-red-100 p-4 text-red-700">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('kasir.customers.reset-password.store') }}">
                    @csrf

                    <div>
                        <label for="phone" class="block font-medium text-sm text-gray-700">
                            Nomor HP Pelanggan
                        </label>

                        <input
                            id="phone"
                            name="phone"
                            type="text"
                            value="{{ old('phone') }}"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                            placeholder="Masukkan nomor HP pelanggan"
                        >
                    </div>

                    <button
                        type="submit"
                        class="mt-5 rounded-md bg-gray-800 px-5 py-2 text-white hover:bg-gray-700"
                    >
                        Reset Password
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
