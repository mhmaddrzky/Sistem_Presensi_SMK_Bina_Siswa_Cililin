@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-blue-900">
            Tambah Jadwal Baru
        </h1>
        <p class="text-sm text-slate-600">
            Lengkapi informasi jadwal praktikum laboratorium.
        </p>
    </div>

    {{-- ERROR VALIDATION --}}
    @if ($errors->any())
        <div class="rounded-xl border border-rose-300 bg-rose-50 px-4 py-3 text-sm">
            <p class="font-semibold text-rose-700 mb-2">
                ⚠️ Gagal menyimpan jadwal. Periksa kembali data berikut:
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

            {{-- GRID FORM --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- KOLOM KIRI --}}
                <div class="space-y-4">
                    <h2 class="text-sm font-semibold text-slate-700 border-b border-dashed pb-2">
                        Informasi Dasar
                    </h2>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Mata Pelajaran
                        </label>
                        <input type="text" name="mata_pelajaran" value="{{ old('mata_pelajaran') }}" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Nama Guru
                        </label>
                        <input type="text" name="nama_guru" value="{{ old('nama_guru') }}" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Jurusan
                        </label>
                        <select name="jurusan" required
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white
                                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Jurusan --</option>
                            <option value="TKJ" {{ old('jurusan')=='TKJ'?'selected':'' }}>TKJ</option>
                            <option value="TBSM" {{ old('jurusan')=='TBSM'?'selected':'' }}>TBSM</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Laboratorium
                        </label>
                        <input type="text" name="ruang_lab" value="{{ old('ruang_lab') }}" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                {{-- KOLOM KANAN --}}
                <div class="space-y-4">
                    <h2 class="text-sm font-semibold text-slate-700 border-b border-dashed pb-2">
                        Detail Waktu
                    </h2>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Hari
                        </label>
                        <select name="hari" required
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white
                                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Hari --</option>
                            @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $hari)
                                <option value="{{ $hari }}" {{ old('hari')==$hari?'selected':'' }}>
                                    {{ $hari }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Waktu Mulai
                            </label>
                            <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Waktu Selesai
                            </label>
                            <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai') }}" required
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Kapasitas (maks. 20)
                        </label>
                        <input type="number" name="kapasitas" min="1" max="20"
                               value="{{ old('kapasitas',20) }}" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                      
                    </div>

                   <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Deskripsi Sesi
                    <span class="text-slate-400 font-normal">(Opsional)</span>
                </label>

                <input type="text"
                    name="sesi"
                    value="{{ old('sesi', $jadwal->sesi ?? '') }}"
                    placeholder="Opsional"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                            focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

                </div>
            </div>

            {{-- CATATAN --}}
            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs text-slate-700">
                <p class="font-semibold text-blue-700 mb-1">Catatan:</p>
                <p>
                    Jadwal yang dibuat akan digunakan sebagai jadwal praktikum laboratorium
                    dan diulang setiap minggu. Pastikan hari, waktu, dan kapasitas
                    sudah sesuai sebelum disimpan.
                </p>
            </div>

{{-- FOOTER FORM --}}
            <div class="pt-6 border-t border-slate-100">
                <div class="flex flex-col-reverse md:flex-row md:items-center md:justify-between gap-4">
                    
                    {{-- Tombol Batal --}}
                    <a href="{{ route('admin.jadwal.index') }}"
                       class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl
                              border border-slate-300 bg-white text-slate-700 text-sm font-semibold
                              shadow-sm hover:bg-slate-50 hover:text-slate-900 transition-all active:scale-95
                              w-full md:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batal
                    </a>

                    {{-- Tombol Simpan --}}
                    <button type="submit"
                            class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl
                                   bg-green-600 text-white text-sm font-semibold
                                   shadow-lg shadow-green-100 hover:bg-green-700 transition-all active:scale-95
                                   w-full md:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Jadwal Berulang
                    </button>

                </div>
            </div>
@endsection
