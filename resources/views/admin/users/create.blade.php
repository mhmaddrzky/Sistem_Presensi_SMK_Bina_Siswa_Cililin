@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    {{-- Judul --}}
    <div>
        <h1 class="text-2xl font-bold text-blue-900">Buat Akun Staf Baru</h1>
        <p class="text-sm text-slate-600">
            Lengkapi data staf dan kredensial login untuk akses ke sistem.
        </p>
    </div>

    {{-- Notifikasi error general --}}
    @if(session('error'))
        <div class="rounded-md bg-red-50 border border-red-300 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Error validasi --}}
    @if ($errors->any())
        <div class="rounded-md bg-red-50 border border-red-300 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Detail Pengguna --}}
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wide">
                    Detail Pengguna
                </h3>

                {{-- Role/Jabatan --}}
                <div class="space-y-1">
                    <label for="role" class="text-xs font-medium text-slate-700">
                        Role / Jabatan <span class="text-red-500">*</span>
                    </label>
                    <select name="role" id="role" required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                {{ $role }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nama --}}
                <div class="space-y-1">
                    <label for="nama" class="text-xs font-medium text-slate-700">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" id="nama"
                           value="{{ old('nama') }}" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Nama lengkap staf">
                </div>

                {{-- ID Pengelola --}}
                <div class="space-y-1">
                    <label for="id_pengelola" class="text-xs font-medium text-slate-700">
                        ID Pengelola (NIP / ID Unik) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="id_pengelola" id="id_pengelola"
                           value="{{ old('id_pengelola') }}" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Contoh: G001 atau NIP Guru">
                    <p class="text-[11px] text-slate-500">
                        Contoh: <span class="font-mono">G001</span> atau NIP Guru.
                    </p>
                </div>
            </div>

            {{-- Kredensial Login --}}
            <div class="space-y-4 border-t border-slate-200 pt-4">
                <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wide">
                    Kredensial Login
                </h3>

                {{-- Username --}}
                <div class="space-y-1">
                    <label for="username" class="text-xs font-medium text-slate-700">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="username" id="username"
                           value="{{ old('username') }}" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Username untuk login">
                </div>

                {{-- Password --}}
                <div class="space-y-1">
                    <label for="password" class="text-xs font-medium text-slate-700">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" id="password" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Minimal 6 karakter">
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('admin.users.index') }}"
                   class="text-xs font-medium text-slate-600 hover:text-slate-800">
                    ← Kembali ke daftar staf
                </a>

                <button type="submit"
                        class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold shadow hover:bg-blue-700">
                    Buat Akun Staf
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
