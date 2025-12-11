@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-blue-900">
            Edit Akun Staf
        </h1>
        <p class="mt-1 text-sm text-slate-600">
            Perbarui informasi akun pengelola sistem
        </p>
    </div>

    {{-- Notifikasi Error --}}
    @if($errors->any())
        <div class="rounded-md bg-red-50 border border-red-300 px-4 py-3">
            <p class="text-sm font-semibold text-red-700 mb-2">❌ Terdapat kesalahan:</p>
            <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Edit --}}
    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-5">
        @csrf
        @method('PUT')

        {{-- ID Pengelola --}}
        <div>
            <label for="id_pengelola" class="block text-sm font-semibold text-slate-700 mb-1">
                ID Pengelola <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="id_pengelola" 
                   id="id_pengelola"
                   value="{{ old('id_pengelola', $user->admin->id_admin ?? '') }}"
                   required
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <p class="mt-1 text-xs text-slate-500">Contoh: GR001, AL002, KS001</p>
        </div>

        {{-- Nama Lengkap --}}
        <div>
            <label for="nama" class="block text-sm font-semibold text-slate-700 mb-1">
                Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="nama" 
                   id="nama"
                   value="{{ old('nama', $user->admin->nama ?? '') }}"
                   required
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- Username --}}
        <div>
            <label for="username" class="block text-sm font-semibold text-slate-700 mb-1">
                Username <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="username" 
                   id="username"
                   value="{{ old('username', $user->username) }}"
                   required
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">
                Password Baru
            </label>
            <input type="password" 
                   name="password" 
                   id="password"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <p class="mt-1 text-xs text-slate-500">Kosongkan jika tidak ingin mengubah password (min. 6 karakter)</p>
        </div>

        {{-- Role --}}
        <div>
            <label for="role" class="block text-sm font-semibold text-slate-700 mb-1">
                Role / Jabatan <span class="text-red-500">*</span>
            </label>
            <select name="role" 
                    id="role" 
                    required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Pilih Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                        {{ $role }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tombol --}}
        <div class="flex gap-3 pt-4">
            <a href="{{ route('admin.users.index') }}"
               class="flex-1 text-center px-4 py-2 rounded-lg border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 transition">
                Batal
            </a>
            <button type="submit"
                    class="flex-1 px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>
@endsection