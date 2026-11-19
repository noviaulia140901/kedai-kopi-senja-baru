<x-guest-layout>
    <div class="w-full">

        <!-- Judul -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-[#493225]">
                Login
            </h2>
            <p class="text-sm text-[#888888] mt-2">
                Silakan masuk menggunakan akun staf Anda.
            </p>
        </div>

        <!-- Pesan Berhasil -->
        @if (session('status'))
            <div class="mb-5 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <!-- Pesan Kesalahan -->
        @if ($errors->any())
            <div class="mb-5 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <p class="font-semibold">Login belum berhasil.</p>
                <p>{{ $errors->first() }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-semibold text-[#493225] mb-2">
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
                    class="w-full rounded-md border border-[#D8D0CA] bg-white px-4 py-3 text-sm text-[#292929] placeholder:text-[#A0A0A0] outline-none focus:border-[#805238] focus:ring-2 focus:ring-[#805238]/15"
                >
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-[#493225] mb-2">
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
                        class="w-full rounded-md border border-[#D8D0CA] bg-white px-4 py-3 pr-16 text-sm text-[#292929] placeholder:text-[#A0A0A0] outline-none focus:border-[#805238] focus:ring-2 focus:ring-[#805238]/15"
                    >

                    <button
                        type="button"
                        id="togglePassword"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-semibold text-[#805238]"
                    >
                        Lihat
                    </button>
                </div>
            </div>

            <!-- Ingat Saya dan Lupa Password -->
            <div class="flex items-center justify-between gap-3">
                <label for="remember_me" class="flex items-center gap-2 text-sm text-[#666666]">
                    <input
                        id="remember_me"
                        type="checkbox"
                        name="remember"
                        class="rounded border-[#BDBDBD] text-[#805238] focus:ring-[#805238]"
                    >
                    Ingat Saya
                </label>

                @if (Route::has('password.request'))
                    <a
                        href="{{ route('password.request') }}"
                        class="text-sm font-medium text-[#805238] hover:underline"
                    >
                        Lupa Password?
                    </a>
                @endif
            </div>

            <!-- Tombol Login -->
            <button
                type="submit"
                class="w-full rounded-md bg-[#493225] py-3 text-sm font-semibold text-white transition hover:bg-[#654632] focus:outline-none focus:ring-2 focus:ring-[#805238] focus:ring-offset-2"
            >
                Masuk
            </button>

        </form>

    </div>

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
