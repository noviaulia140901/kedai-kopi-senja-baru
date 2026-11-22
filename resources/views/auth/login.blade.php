<x-guest-layout>
    <style>
        body:has(.login-page) {
            background-color: #F8F1DC;
            color: #5A4636;
        }

        body:has(.login-page) main > div:first-child h1,
        body:has(.login-page) main > p {
            color: #5A4636;
        }

        body:has(.login-page) main > section {
            background-color: #F8F1DC;
            border-color: #668B4A;
            border-radius: 0.75rem;
        }
    </style>

    <div class="login-page w-full">

        <!-- Judul Login -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-[#5A4636]">
                Login
            </h2>
            <p class="text-sm text-[#5A4636]/70 mt-2">
                Silakan masuk menggunakan akun staf Anda.
            </p>
        </div>

        <!-- Pesan berhasil -->
        @if (session('status'))
            <div class="mb-5 rounded-lg border border-[#668B4A] bg-[#668B4A]/10 px-4 py-3 text-sm text-[#5A4636]">
                {{ session('status') }}
            </div>
        @endif

        <!-- Pesan kesalahan -->
        @if ($errors->any())
            <div class="mb-5 rounded-lg border border-[#5A4636]/40 bg-[#5A4636]/5 px-4 py-3 text-sm text-[#5A4636]">
                <p class="font-semibold">Login belum berhasil.</p>
                <p>{{ $errors->first() }}</p>
            </div>
        @endif

        <!-- Form Login -->
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Input Username -->
            <div>
                <label for="username" class="block text-sm font-semibold text-[#5A4636] mb-2">
                    Username
                </label>

                <input
                    id="username"
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Masukkan username"
                    class="w-full rounded-lg border border-[#668B4A]/50 bg-white px-4 py-3 text-sm text-[#5A4636] placeholder:text-[#5A4636]/50 outline-none focus:border-[#668B4A] focus:ring-2 focus:ring-[#668B4A]/30"
                >
            </div>

            <!-- Input Password dan Tombol Tampilkan Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-[#5A4636] mb-2">
                    Password
                </label>

                <div class="relative">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Masukkan password"
                        class="w-full rounded-lg border border-[#668B4A]/50 bg-white px-4 py-3 pr-16 text-sm text-[#5A4636] placeholder:text-[#5A4636]/50 outline-none focus:border-[#668B4A] focus:ring-2 focus:ring-[#668B4A]/30"
                    >

                    <button
                        type="button"
                        id="togglePassword"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-semibold text-[#5A4636] hover:text-[#668B4A]"
                    >
                        Lihat
                    </button>
                </div>
            </div>

            <!-- Pilihan Ingat Saya -->
            <div class="flex items-center">
                <label for="remember_me" class="flex items-center gap-2 text-sm text-[#5A4636]">
                    <input
                        id="remember_me"
                        type="checkbox"
                        name="remember"
                        class="rounded border-[#668B4A] text-[#668B4A] focus:ring-[#668B4A]"
                    >
                    Ingat Saya
                </label>
            </div>

            <!-- Tombol Masuk -->
            <button
                type="submit"
                class="w-full rounded-lg !bg-[#668B4A] py-3 text-sm font-semibold !text-white transition hover:!bg-[#5A4636] hover:!text-[#F8F1DC] focus:outline-none focus:ring-2 focus:ring-[#668B4A] focus:ring-offset-2"
            >
                Masuk
            </button>

        </form>

    </div>

    <!-- Fungsi Tampilkan dan Sembunyikan Password -->
    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';

            passwordInput.type = isPassword ? 'text' : 'password';
            togglePassword.textContent = isPassword ? 'Sembunyikan' : 'Lihat';
        });
    </script>
</x-guest-layout>
