@extends('layouts.admin')

@section('content')
<div class="max-w-md md:max-w-3xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-blue-900">
             Edit Data Siswa
        </h1>
        <p class="text-sm text-slate-600">
            Perbarui informasi identitas siswa di bawah ini.
        </p>
    </div>

    {{-- ERROR VALIDATION --}}
    @if ($errors->any())
        <div class="rounded-xl border border-rose-300 bg-rose-50 px-4 py-3 text-sm">
            <p class="font-semibold text-rose-700 mb-2">
                ⚠️ Gagal memperbarui data siswa
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

        <form action="{{ route('admin.siswa.update', $siswa->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- GRID FORM --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- KOLOM KIRI --}}
                <div class="space-y-4">
                    <h2 class="text-sm font-semibold text-slate-700 border-b border-dashed pb-2">
                        Identitas Siswa
                    </h2>

                    {{-- Nama --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Nama Lengkap
                        </label>
                        <input type="text" name="nama"
                               value="{{ old('nama', $siswa->nama) }}"
                               required
                               class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('nama')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIS --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            NIS
                        </label>
                        <input type="text" name="nis"
                               value="{{ old('nis', $siswa->nis) }}"
                               required
                               class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('nis')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- KOLOM KANAN --}}
                <div class="space-y-4">
                    <h2 class="text-sm font-semibold text-slate-700 border-b border-dashed pb-2">
                        Akademik & Status
                    </h2>

                    {{-- Kelas --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Kelas
                        </label>
                        <input type="text" name="kelas"
                               value="{{ old('kelas', $siswa->kelas) }}"
                               required
                               class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('kelas')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jurusan --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Jurusan
                        </label>
                        <select name="jurusan" required
                                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white
                                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Jurusan --</option>
                            <option value="TKJ" {{ old('jurusan', $siswa->jurusan) == 'TKJ' ? 'selected' : '' }}>TKJ</option>
                            <option value="TBSM" {{ old('jurusan', $siswa->jurusan) == 'TBSM' ? 'selected' : '' }}>TBSM</option>
                        </select>
                        @error('jurusan')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>


            {{-- FOOTER --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 pt-4 border-t">
                <a href="{{ route('admin.siswa.index') }}"
                   class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
                    ← Kembali ke daftar siswa
                </a>

                <button type="submit"
                        class="inline-flex items-center justify-center px-6 py-2.5 rounded-lg
                               bg-blue-600 text-white text-sm font-semibold
                               hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
