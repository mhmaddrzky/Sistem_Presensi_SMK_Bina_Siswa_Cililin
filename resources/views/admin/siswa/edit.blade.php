@extends('layouts.admin')

@section('content')

{{-- ================= WRAPPER ================= --}}
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

    {{-- ERROR --}}
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

    {{-- ================= CARD ================= --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 px-5 py-6 md:px-7 md:py-7">

        <form id="form-siswa"
              action="{{ route('admin.siswa.update', $siswa->id) }}"
              method="POST">
            @csrf
            @method('PUT')

            {{-- GRID FORM --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- KIRI --}}
                <div class="space-y-4">
                    <h2 class="text-sm font-semibold text-slate-700 border-b border-dashed pb-2">
                        Identitas Siswa
                    </h2>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Nama Lengkap
                        </label>
                        <input type="text" name="nama"
                               value="{{ old('nama', $siswa->nama) }}"
                               required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            NIS
                        </label>
                        <input type="text" name="nis"
                               value="{{ old('nis', $siswa->nis) }}"
                               required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                {{-- KANAN --}}
                <div class="space-y-4">
                    <h2 class="text-sm font-semibold text-slate-700 border-b border-dashed pb-2">
                        Akademik & Status
                    </h2>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Kelas
                        </label>
                        <input type="text" name="kelas"
                               value="{{ old('kelas', $siswa->kelas) }}"
                               required
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
                            <option value="TKJ" {{ old('jurusan', $siswa->jurusan) == 'TKJ' ? 'selected' : '' }}>TKJ</option>
                            <option value="TBSM" {{ old('jurusan', $siswa->jurusan) == 'TBSM' ? 'selected' : '' }}>TBSM</option>
                        </select>
                    </div>
                </div>

            </div>

            {{-- ================= FOOTER FORM ================= --}}
            <div class="flex items-center justify-between mt-8 pt-6 border-t border-slate-200">

                <a href="{{ route('admin.siswa.index') }}"
                   class="text-sm font-medium text-slate-600 hover:text-slate-900">
                    ← Kembali ke daftar siswa
                </a>

                <button type="submit"
                        class="px-6 py-2.5 rounded-lg bg-green-600 text-white text-sm font-semibold
                               shadow-sm hover:bg-green-700 focus:ring-2 focus:ring-blue-500">
                    Simpan Perubahan
                </button>

            </div>

        </form>
    </div>
</div>

@endsection
