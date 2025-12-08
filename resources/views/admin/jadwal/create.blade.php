@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- HEADER + TOMBOL KEMBALI --}}
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-blue-900">
                Tambah Jadwal Baru
            </h1>
            <p class="text-sm text-slate-600">
                Lengkapi informasi jadwal praktikum laboratorium.
            </p>
        </div>

        <a href="{{ route('admin.jadwal.index') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-blue-700 hover:text-blue-900">
            {{-- panah kiri kecil --}}
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
                ⚠️ Gagal menyimpan jadwal. Mohon periksa kesalahan di bawah ini:
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
        <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- GRID 2 KOLOM --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- KOLOM KIRI: Informasi Dasar --}}
                <div class="space-y-4">
                    <h2 class="text-sm font-semibold text-slate-700 border-b border-dashed border-slate-200 pb-2">
                        Informasi Dasar
                    </h2>

                    {{-- Mata Pelajaran --}}
                    <div>
                        <label for="mata_pelajaran" class="block text-xs font-semibold text-slate-700 mb-1">
                            Mata Pelajaran
                        </label>
                        <input type="text" id="mata_pelajaran" name="mata_pelajaran"
                               value="{{ old('mata_pelajaran') }}" required
                               class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('mata_pelajaran')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama Guru --}}
                    <div>
                        <label for="nama_guru" class="block text-xs font-semibold text-slate-700 mb-1">
                            Nama Guru
                        </label>
                        <input type="text" id="nama_guru" name="nama_guru"
                               value="{{ old('nama_guru') }}" required
                               class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('nama_guru')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jurusan --}}
                    <div>
                        <label for="jurusan" class="block text-xs font-semibold text-slate-700 mb-1">
                            Jurusan
                        </label>
                        <select id="jurusan" name="jurusan" required
                                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                       bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Jurusan --</option>
                            <option value="TKJ"  {{ old('jurusan') == 'TKJ'  ? 'selected' : '' }}>TKJ</option>
                            <option value="TBSM" {{ old('jurusan') == 'TBSM' ? 'selected' : '' }}>TBSM</option>
                        </select>
                        @error('jurusan')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Laboratorium --}}
                    <div>
                        <label for="ruang_lab" class="block text-xs font-semibold text-slate-700 mb-1">
                            Laboratorium
                        </label>
                        <input type="text" id="ruang_lab" name="ruang_lab"
                               value="{{ old('ruang_lab') }}" required
                               class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('ruang_lab')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- KOLOM KANAN: Detail Sesi & Waktu --}}
                <div class="space-y-4">
                    <h2 class="text-sm font-semibold text-slate-700 border-b border-dashed border-slate-200 pb-2">
                        Detail Sesi &amp; Waktu
                    </h2>

                    {{-- Hari --}}
                    <div>
                        <label for="hari" class="block text-xs font-semibold text-slate-700 mb-1">
                            Hari
                        </label>
                        <select id="hari" name="hari" required
                                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                       bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Hari --</option>
                            @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $hari)
                                <option value="{{ $hari }}" {{ old('hari') == $hari ? 'selected' : '' }}>
                                    {{ $hari }}
                                </option>
                            @endforeach
                        </select>
                        @error('hari')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Waktu Mulai + Selesai --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="waktu_mulai" class="block text-xs font-semibold text-slate-700 mb-1">
                                Waktu Mulai
                            </label>
                            <input type="time" id="waktu_mulai" name="waktu_mulai"
                                   value="{{ old('waktu_mulai') }}" required
                                   class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('waktu_mulai')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="waktu_selesai" class="block text-xs font-semibold text-slate-700 mb-1">
                                Waktu Selesai
                            </label>
                            <input type="time" id="waktu_selesai" name="waktu_selesai"
                                   value="{{ old('waktu_selesai') }}" required
                                   class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('waktu_selesai')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Kapasitas --}}
                    <div>
                        <label for="kapasitas" class="block text-xs font-semibold text-slate-700 mb-1">
                            Kapasitas Siswa <span class="text-slate-400">(maks. 20)</span>
                        </label>
                        <input type="number" id="kapasitas" name="kapasitas"
                               value="{{ old('kapasitas', 20) }}" min="1" max="20" required
                               class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('kapasitas')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi Sesi --}}
                    <div>
                        <label for="sesi" class="block text-xs font-semibold text-slate-700 mb-1">
                            Deskripsi Sesi <span class="text-slate-400">(contoh: Pagi / Siang)</span>
                        </label>
                        <input type="text" id="sesi" name="sesi"
                               value="{{ old('sesi') }}"
                               placeholder="Opsional"
                               class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('sesi')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- FOOTER FORM: KEMBALI + SUBMIT --}}
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
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg
                               bg-blue-700 text-white text-sm font-semibold shadow-sm
                               hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    Simpan Jadwal Berulang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
