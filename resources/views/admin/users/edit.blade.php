@extends('layouts.admin')

@section('content')
<div class="max-w-md md:max-w-3xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-blue-900">
            Edit Akun Staf
        </h1>
        <p class="text-sm text-slate-600">
            Perbarui informasi akun pengelola sistem.
        </p>
    </div>

    {{-- ERROR VALIDATION --}}
    @if ($errors->any())
        <div class="rounded-xl border border-rose-300 bg-rose-50 px-4 py-3 text-sm">
            <p class="font-semibold text-rose-700 mb-2">
                ⚠️ Gagal menyimpan perubahan. Periksa kesalahan berikut:
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

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- GRID FORM --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- KOLOM KIRI: Identitas Pengelola --}}
                <div class="space-y-4">
                    <h2 class="text-sm font-semibold text-slate-700 border-b border-dashed border-slate-200 pb-2">
                        Identitas Pengelola
                    </h2>

                    {{-- ID Pengelola --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            ID Pengelola <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="id_pengelola"
                               value="{{ old('id_pengelola', $user->admin->id_admin ?? '') }}"
                               required
                               placeholder="Contoh: GR001"
                               class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>

                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nama"
                               value="{{ old('nama', $user->admin->nama ?? '') }}"
                               required
                               class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                </div>

                {{-- KOLOM KANAN: Informasi Akun --}}
                <div class="space-y-4">
                    <h2 class="text-sm font-semibold text-slate-700 border-b border-dashed border-slate-200 pb-2">
                        Informasi Akun
                    </h2>

                    {{-- Username --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="username"
                               value="{{ old('username', $user->username) }}"
                               required
                               class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>

                    {{-- Role --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Role / Jabatan <span class="text-red-500">*</span>
                        </label>
                        <select name="role" required
                                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">-- Pilih Role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}"
                                    {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                                    {{ $role }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Password Baru
                        </label>
                        <input type="password"
                               name="password"
                               placeholder="Kosongkan jika tidak diubah"
                               class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <p class="mt-1 text-[11px] text-slate-500">
                            Minimal 6 karakter. Isi hanya jika ingin mengganti password.
                        </p>
                    </div>
                </div>
            </div>

            {{-- FOOTER FORM --}}
            <div class="pt-6 border-t border-slate-100">
                <div class="flex flex-col-reverse md:flex-row md:items-center md:justify-between gap-4">
                    
                    {{-- Tombol Batal --}}
                    <a href="{{ route('admin.users.index') }}"
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Simpan Perubahan
                    </button>

                </div>
            </div>

        </form>
    </div>
</div>
@endsection