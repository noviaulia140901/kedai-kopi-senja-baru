<section class="w-full rounded-2xl border border-[#E9E0CF] bg-white p-6 md:p-8">

    {{-- JUDUL --}}
    <div class="border-b border-[#E9E0CF] pb-5">
        <p class="text-xs font-bold uppercase tracking-widest text-[#4F5B1D]">
            PENGATURAN AKUN
        </p>

        <h2 class="mt-2 text-xl font-extrabold text-[#30201B]">
            Ganti Password
        </h2>

        <p class="mt-2 text-sm text-[#806D63]">
            Perbarui password akun Anda untuk menjaga keamanan akun.
        </p>
    </div>

    {{-- FORM PASSWORD --}}
    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        {{-- PASSWORD SAAT INI --}}
        <div>
            <x-input-label
                for="update_password_current_password"
                value="Password Saat Ini"
                class="font-bold text-[#30201B]"
            />

            <x-text-input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="mt-2 block w-full rounded-xl border-[#E9E0CF] bg-[#F5F1E7] text-[#30201B] focus:border-[#4F5B1D] focus:ring-[#4F5B1D]"
                autocomplete="current-password"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('current_password')"
                class="mt-2"
            />
        </div>

        {{-- PASSWORD BARU --}}
        <div>
            <x-input-label
                for="update_password_password"
                value="Password Baru"
                class="font-bold text-[#30201B]"
            />

            <x-text-input
                id="update_password_password"
                name="password"
                type="password"
                class="mt-2 block w-full rounded-xl border-[#E9E0CF] bg-[#F5F1E7] text-[#30201B] focus:border-[#4F5B1D] focus:ring-[#4F5B1D]"
                autocomplete="new-password"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('password')"
                class="mt-2"
            />
        </div>

        {{-- KONFIRMASI PASSWORD --}}
        <div>
            <x-input-label
                for="update_password_password_confirmation"
                value="Konfirmasi Password Baru"
                class="font-bold text-[#30201B]"
            />

            <x-text-input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="mt-2 block w-full rounded-xl border-[#E9E0CF] bg-[#F5F1E7] text-[#30201B] focus:border-[#4F5B1D] focus:ring-[#4F5B1D]"
                autocomplete="new-password"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('password_confirmation')"
                class="mt-2"
            />
        </div>

        {{-- TOMBOL SIMPAN --}}
        <div class="flex flex-wrap items-center gap-4 border-t border-[#E9E0CF] pt-5">

            <x-primary-button class="rounded-xl bg-[#4F5B1D] px-6 py-3 text-sm font-bold text-white hover:bg-[#3D4716] focus:bg-[#3D4716] active:bg-[#3D4716]">
                Simpan Password
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-bold text-[#4F5B1D]"
                >
                    Password berhasil diperbarui.
                </p>
            @endif

        </div>
    </form>
</section>
