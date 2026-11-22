<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div>
            <x-input-label for="name" value="Nama Lengkap"/>
            <x-text-input
                id="name"
                class="block mt-1 w-full"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
            />
            <x-input-error :messages="$errors->get("name")" class="mt-2"/>
        </div>

        <div claa="mt-4">
            <x-input-label for="phone" value="Nomor-HP"/>
            <x-text-input
                 id="phone"
                 class="block mt-1 w-full"
                 type="tel"
                 name="phone"
                 :value="old('phone')"
                 required
                 autocomplete="tel"
            />
            <x-input-error :messages="$errors->get('phone')" class="mt-2"/>
        </div>

        <div class="mt-4">
            <x-input-label for="password" value"Passsword" />
            <x-text-input
                 id="passsword"
                 class="block mt-1 w-full"
                 type="password"
                 name="password"
                 required
                 autocomplete="new password"
            />
            <x-input-error :message="$errors->get('password')" class="mt-2"/>
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Password" />
            <x-text-input
                id="password_confirmation"
                class="block mt-1 w-full"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justyfy-end mt-4">
            <a
                class="underline text-sm text-gray-600 hover:text-gray-900"
                href="{{ route('login') }}"
            >

            <x-primary-button class="ms-4">
                Daftar
            </x-primary-button>
        </div
    </form>
</x-guest-layout>
