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
                Perbarui semua informasi yang diperlukan untuk jadwal berulang ini.
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

                {{-- KOLOM KIRI (Detail Jadwal) --}}
                <div class="space-y-4">
                    
                    <h2 class="text-sm font-semibold text-slate-700 border-b border-dashed border-slate-200 pb-2">
                        Informasi Dasar & Subjek
                    </h2>

                    {{-- Mata Pelajaran --}}
                    <div>
                        <label for="mata_pelajaran" class="block text-xs font-semibold text-slate-700 mb-1">
                            Mata Pelajaran
                        </label>
                        <input type="text" id="mata_pelajaran" name="mata_pelajaran"
                               value="{{ old('mata_pelajaran', $jadwal->mata_pelajaran) }}" required
                               placeholder="Contoh: Pemrograman Web Lanjut"
                               class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('mata_pelajaran')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama Guru --}}
                    <div>
                        <label for="nama_guru" class="block text-xs font-semibold text-slate-700 mb-1">
                            Nama Guru Pengampu
                        </label>
                        <input type="text" id="nama_guru" name="nama_guru"
                               value="{{ old('nama_guru', $jadwal->nama_guru) }}" required
                               placeholder="Contoh: Pak Muhammad Rizky"
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
                                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Jurusan --</option>
                            <option value="TKJ" {{ old('jurusan', $jadwal->jurusan) == 'TKJ' ? 'selected' : '' }}>TKJ</option>
                            <option value="TBSM" {{ old('jurusan', $jadwal->jurusan) == 'TBSM' ? 'selected' : '' }}>TBSM</option>
                        </select>
                        @error('jurusan')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kapasitas --}}
                    <div>
                        <label for="kapasitas" class="block text-xs font-semibold text-slate-700 mb-1">
                            Kapasitas Maksimal Siswa <span class="text-slate-400">(maks. 20)</span>
                        </label>
                        <input type="number" id="kapasitas" name="kapasitas"
                               value="{{ old('kapasitas', $jadwal->kapasitas) }}" required
                               placeholder="Contoh: 20" min="1" max="20"
                               class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('kapasitas')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- KOLOM KANAN (Waktu & Ruangan) --}}
                <div class="space-y-4">
                    
                    <h2 class="text-sm font-semibold text-slate-700 border-b border-dashed border-slate-200 pb-2">
                        Waktu & Lokasi Berulang
                    </h2>

                    {{-- Hari --}}
                    <div>
                        <label for="hari" class="block text-xs font-semibold text-slate-700 mb-1">
                            Hari
                        </label>
                        <select id="hari" name="hari" required
                                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Hari --</option>
                            @php
                                $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                            @endphp
                            @foreach($days as $day)
                                <option value="{{ $day }}" {{ old('hari', $jadwal->hari) == $day ? 'selected' : '' }}>{{ $day }}</option>
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
                                Waktu Mulai (HH:MM)
                            </label>
                            <input type="time" id="waktu_mulai" name="waktu_mulai"
                                   value="{{ old('waktu_mulai', $jadwal->waktu_mulai ? substr($jadwal->waktu_mulai, 0, 5) : '') }}" required
                                   class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('waktu_mulai')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="waktu_selesai" class="block text-xs font-semibold text-slate-700 mb-1">
                                Waktu Selesai (HH:MM)
                            </label>
                            <input type="time" id="waktu_selesai" name="waktu_selesai"
                                   value="{{ old('waktu_selesai', $jadwal->waktu_selesai ? substr($jadwal->waktu_selesai, 0, 5) : '') }}" required
                                   class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('waktu_selesai')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Ruang Lab --}}
                    <div>
                        <label for="ruang_lab" class="block text-xs font-semibold text-slate-700 mb-1">
                            Ruang Lab
                        </label>
                        <input type="text" id="ruang_lab" name="ruang_lab"
                               value="{{ old('ruang_lab', $jadwal->ruang_lab) }}" required
                               placeholder="Contoh: Lab 1"
                               class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('ruang_lab')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Catatan --}}
                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs text-slate-700">
                        <p class="font-semibold text-blue-700 mb-1">Catatan:</p>
                        <p>Jadwal akan diulang setiap minggu. Pastikan Hari dan Waktu sudah benar.</p>
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
                        class="inline-flex items-center justify-center px-6 py-2.5 rounded-lg
                               bg-blue-600 text-white text-sm font-semibold shadow-sm
                               hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    Perbarui Jadwal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection