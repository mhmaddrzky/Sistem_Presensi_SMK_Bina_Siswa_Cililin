@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Judul + Tombol Tambah --}}
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-blue-900">
                Daftar Akun Pengelola & Staf
            </h1>
            <p class="text-sm text-slate-600">
                Manajemen akun Guru, Asisten Lab, dan Kepala Sekolah.
            </p>
        </div>

        <a href="{{ route('admin.users.create') }}"
           class="inline-flex justify-center items-center px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold shadow
                  hover:bg-green-700 transition w-full md:w-auto">
            + Tambah Akun Staf Baru
        </a>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="rounded-md bg-green-50 border border-green-300 px-4 py-3 text-sm font-semibold text-green-700">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-md bg-red-50 border border-red-300 px-4 py-3 text-sm font-semibold text-red-700">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- Card daftar akun --}}
    <div class="bg-white shadow-sm rounded-2xl border border-slate-200 overflow-hidden">

        {{-- Header tabel --}}
        <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-slate-700">List Akun Staf</h2>
            <span class="inline-flex items-center rounded-full bg-slate-50 px-3 py-1 text-xs font-medium text-slate-700">
                Total: {{ $users->count() }} akun
            </span>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs md:text-sm text-left">
                <thead class="bg-[#0D47C9] text-white">
                    <tr class="border-b border-slate-200">
                        <th class="px-4 py-3 font-semibold uppercase tracking-wide text-white">ID Pengelola</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wide text-white">Nama</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wide text-white">Username</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wide text-white">Role</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wide text-white">Status</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wide text-white text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        @php
                            $isAdmin = $user->role === 'Admin';
                            $adminDetail = $user->admin ?? null;

                            $roleBadgeClass = match($user->role) {
                                'Kepsek'     => 'bg-red-100 text-red-700 border-red-300',
                                'Admin'      => 'bg-blue-100 text-blue-700 border-blue-300',
                                'Guru'       => 'bg-emerald-100 text-emerald-700 border-emerald-300',
                                'AsistenLab' => 'bg-amber-100 text-amber-700 border-amber-300',
                                default      => 'bg-slate-100 text-slate-700 border-slate-300',
                            };
                        @endphp

                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ $adminDetail->id_admin ?? 'N/A' }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-900">
                                    {{ $adminDetail->nama ?? 'Akun Siswa / System' }}
                                </div>
                                <div class="text-[11px] text-slate-500">
                                    ID User: {{ $user->id }}
                                </div>
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap text-slate-800">
                                {{ $user->username }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] md:text-xs font-semibold border {{ $roleBadgeClass }}">
                                    {{ $user->role }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-[11px] md:text-xs font-medium">
                                    Aktif
                                </span>
                            </td>

                            <td class="px-4 py-3 text-center">
                                @if ($isAdmin)
                                    <span class="text-slate-500 text-xs italic">Utama</span>
                                @else
                                    <div class="flex items-center justify-center gap-3">
                                        {{-- EDIT --}}
                                        <a href="#"
                                           class="text-blue-600 font-medium hover:underline text-xs md:text-sm">
                                            Edit
                                        </a>

                                        {{-- HAPUS (masih pakai confirm biasa) --}}
                                        <button type="button"
                                                class="text-red-600 font-medium hover:underline text-xs md:text-sm"
                                                onclick="confirm('Yakin ingin menghapus akun ini? (silakan hubungkan ke route delete di backend)')">
                                            Hapus
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center px-4 py-6 text-slate-500">
                                Tidak ada akun staf yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
