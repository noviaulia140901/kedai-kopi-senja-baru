@extends('layouts.manajer')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="rounded-2xl border border-[#D8C9BE] bg-white p-8 text-center shadow-sm">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#A66A45]">
            Manajer
        </p>
        <h1 class="mt-4 text-3xl font-bold text-[#34251F]">
            {{ $title ?? 'Halaman' }}
        </h1>
        <p class="mt-3 text-sm text-[#6F5548]">
            Fitur ini masih dalam tahap pengembangan dan akan dilengkapi pada tahap berikutnya.
        </p>

        <div class="mt-6 flex justify-center">
            <a href="{{ route('manajer.dashboard') }}"
               class="rounded-xl bg-[#493225] px-5 py-3 text-sm font-semibold text-white hover:bg-[#684936]">
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
