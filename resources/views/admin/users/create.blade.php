@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    {{-- Judul --}}
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-blue-900">Buat Akun Staf Baru</h1>
        <p class="text-sm text-slate-600">
            Lengkapi data staf dan kredensial login untuk akses ke sistem.
        </p>
    </div>

    {{-- Notifikasi Error General & Validasi --}}
    @if(session('error') || $errors->any())
        <div class="rounded-xl border border-rose-300 bg-rose-50 px-4 py-3 text-sm">
            <p class="font-semibold text-rose-700 mb-2">
                ⚠️ Ada kendala dalam pembuatan akun:
            </p>
            <ul class="list-disc list-inside text-rose-700 space-y-1">
                @if(session('error')) <li>{{ session('error') }}</li> @endif
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 px-5 py-6 md:px-7 md:py-7">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Detail Pengguna --}}
            <div class="space-y-4">
                <h2 class="text-sm font-semibold text-slate-700 border-b border-dashed pb-2 uppercase tracking-wider">
                    Detail Pengguna
                </h2>

                {{-- Role/Jabatan --}}
                <div>
                    <label for="role" class="block text-xs font-semibold text-slate-700 mb-1">
                        Role / Jabatan <span class="text-red-500">*</span>
                    </label>
                    <select name="role" id="role" required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach($roles as $role)
                            {{-- LOGIKA PENGAMAN: Jangan tampilkan Superadmin --}}
                            @if($role === 'Superadmin') 
                                @continue 
                            @endif

                            <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                {{ $role }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nama --}}
                <div>
                    <label for="nama" class="block text-xs font-semibold text-slate-700 mb-1">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" id="nama"
                           value="{{ old('nama') }}" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                           placeholder="Nama lengkap staf">
                </div>

                {{-- ID Pengelola --}}
                <div>
                    <label for="id_pengelola" class="block text-xs font-semibold text-slate-700 mb-1">
                        ID Pengelola (NIP / ID Unik) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="id_pengelola" id="id_pengelola"
                           value="{{ old('id_pengelola') }}" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                           placeholder="Contoh: G001 atau NIP">
                    <p class="mt-1 text-[11px] text-slate-500 italic">
                        Gunakan NIP Guru atau ID unik pengelola laboratorium.
                    </p>
                </div>
            </div>

            {{-- Kredensial Login --}}
            <div class="space-y-4 pt-2">
                <h2 class="text-sm font-semibold text-slate-700 border-b border-dashed pb-2 uppercase tracking-wider">
                    Kredensial Login
                </h2>

                {{-- Username --}}
                <div>
                    <label for="username" class="block text-xs font-semibold text-slate-700 mb-1">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="username" id="username"
                           value="{{ old('username') }}" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                           placeholder="Username untuk login"
                           autocomplete="off">
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" id="password" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                           placeholder="Minimal 6 karakter"
                           autocomplete="new-password">
                </div>
            </div>

            {{-- FOOTER FORM (AKSI) --}}
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

                    {{-- Tombol Buat Akun --}}
                    <button type="submit"
                            class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl
                                   bg-green-600 text-white text-sm font-semibold
                                   shadow-lg shadow-green-100 hover:bg-green-700 transition-all active:scale-95
                                   w-full md:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Buat Akun Staf
                    </button>

                </div>
            </div>
        </form>
    </div>
</div>
@endsection