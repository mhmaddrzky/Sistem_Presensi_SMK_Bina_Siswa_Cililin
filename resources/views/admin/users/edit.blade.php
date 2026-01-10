@extends('layouts.admin')

@section('content')
<div class="max-w-md md:max-w-3xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-blue-900">
            Edit Akun Staf
        </h1>
        <p class="text-sm text-slate-600">
            Perbarui informasi akun pengelola sistem
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

            {{-- GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- KOLOM KIRI --}}
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
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

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
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                </div>

                {{-- KOLOM KANAN --}}
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
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-xs text-slate-500">
                            Minimal 6 karakter
                        </p>
                    </div>

                    {{-- Role --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Role / Jabatan <span class="text-red-500">*</span>
                        </label>
                        <select name="role" required
                                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}"
                                    {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                                    {{ $role }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

{{-- FOOTER --}}
<div class="pt-6 border-t border-slate-100">
    <div class="flex items-center justify-between">

        <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-900">
            ← Kembali ke Manajemen Akun
        </a>


        <button type="submit"
                class="inline-flex items-center justify-center px-6 py-2.5 rounded-lg
                       bg-green-600 text-white text-sm font-semibold shadow-sm
                       hover:bg-green-700 focus:outline-none focus:ring-2
                       focus:ring-green-500 focus:ring-offset-1">
            Simpan Perubahan
        </button>
    </div>
</div>

@endsection
