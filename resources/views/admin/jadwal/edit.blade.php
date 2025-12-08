@extends('layouts.admin')

@section('content')
<div class="max-w-md md:max-w-3xl mx-auto space-y-6">

    {{-- HEADER + LINK KEMBALI --}}
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-blue-900">
                Edit Jadwal Laboratorium
            </h1>
            <p class="text-sm text-slate-600">
                Perbarui informasi tanggal, sesi, dan ruang laboratorium.
            </p>
        </div>

        <a href="{{ route('admin.jadwal.index') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-blue-700 hover:text-blue-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 19l-7-7 7-7" />
            </svg>
            <span>Kembali ke Daftar Jadwal</span>
        </a>
    </div>

    {{-- ERROR VALIDATION --}}
    @if ($errors->any())
        <div class="rounded-xl border border-rose-300 bg-rose-50 px-4 py-3 text-sm">
            <p class="font-semibold text-rose-700 mb-2">
                ⚠️ Gagal memperbarui jadwal. Mohon periksa kesalahan di bawah ini:
            </p>
            <ul class="list-disc list-inside text-rose-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- CARD FORM --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 px-5 py-6 md:px-7 md:py-7">

        <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- GRID 2 KOLOM DI DESKTOP --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- KOLOM KIRI --}}
                <div class="space-y-4">
                    {{-- Tanggal --}}
                    <div>
                        <label for="tanggal" class="block text-xs font-semibold text-slate-700 mb-1">
                            Tanggal
                        </label>
                        <input type="date" id="tanggal" name="tanggal"
                               value="{{ old('tanggal', $jadwal->tanggal) }}" required
                               class="block w-full rounded-full border border-slate-300 px-4 py-2.5 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('tanggal')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Sesi --}}
                    <div>
                        <label for="sesi" class="block text-xs font-semibold text-slate-700 mb-1">
                            Sesi
                        </label>
                        <input type="text" id="sesi" name="sesi"
                               value="{{ old('sesi', $jadwal->sesi) }}" required
                               placeholder="Contoh: Sesi 1 atau 07.00–09.00"
                               class="block w-full rounded-full border border-slate-300 px-4 py-2.5 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('sesi')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- KOLOM KANAN --}}
                <div class="space-y-4">
                    {{-- Ruang Lab --}}
                    <div>
                        <label for="ruang_lab" class="block text-xs font-semibold text-slate-700 mb-1">
                            Ruang Lab
                        </label>
                        <input type="text" id="ruang_lab" name="ruang_lab"
                               value="{{ old('ruang_lab', $jadwal->ruang_lab) }}" required
                               placeholder="Contoh: Lab 1"
                               class="block w-full rounded-full border border-slate-300 px-4 py-2.5 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('ruang_lab')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Catatan --}}
                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs text-slate-700">
                        <p class="font-semibold text-blue-700 mb-1">Catatan:</p>
                        <p>Pastikan semua data tanggal, sesi, dan ruang lab diisi dengan format yang benar.</p>
                    </div>
                </div>
            </div>

            {{-- FOOTER FORM --}}
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between pt-4 border-t border-slate-100">
                <a href="{{ route('admin.jadwal.index') }}"
                   class="inline-flex items-center gap-2 text-xs md:text-sm font-medium text-slate-600 hover:text-slate-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 19l-7-7 7-7" />
                    </svg>
                    <span>Kembali ke daftar jadwal</span>
                </a>

                <button type="submit"
                        class="inline-flex items-center justify-center px-6 py-2.5 rounded-full
                               bg-blue-600 text-white text-sm font-semibold shadow-sm
                               hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    Perbarui Jadwal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
