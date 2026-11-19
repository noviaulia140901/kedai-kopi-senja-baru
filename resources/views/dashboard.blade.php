<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Kasir
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800">
                    Selamat Datang, Kasir!
                </h3>

                <p class="mt-2 text-gray-600">
                    Silakan pilih fitur yang ingin digunakan.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('kasir.menus.index') }}"
                       class="inline-block bg-gray-800 text-white px-5 py-3 rounded-lg hover:bg-gray-700">
                        Pemesanan Kasir
                    </a>

                    <a href="{{ route('kasir.customers.reset-password') }}"
                       class="inline-block bg-gray-800 text-white px-5 py-3 rounded-lg hover:bg-gray-700">
                        Reset Password Pelanggan
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
