@extends('layouts.admin')

@section('content')
@php
    $userRole = Auth::user()->role;
    $allowedToManage = in_array($userRole, ['Admin', 'Guru', 'AsistenLab']);
@endphp

<div class="max-w-5xl mx-auto space-y-6">

    {{-- Header judul + tombol tambah --}}
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-blue-900">
                🗓️ Kelola Jadwal Laboratorium
            </h1>
            <p class="text-sm text-slate-600">
                Atur jadwal praktikum, guru pengampu, dan ruang laboratorium.
            </p>
        </div>

        @if ($allowedToManage)
            <a href="{{ route('admin.jadwal.create') }}"
           class="inline-flex justify-center items-center px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold shadow
                  hover:bg-green-700 transition w-full md:w-auto">
            + Tambah jadwal Baru
        </a>
        @endif
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="rounded-md bg-emerald-50 border border-emerald-300 px-4 py-3 text-sm font-semibold text-emerald-700">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-md bg-rose-50 border border-rose-300 px-4 py-3 text-sm font-semibold text-rose-700">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- Tabel jadwal --}}
    @if($jadwals->isNotEmpty())
        <div class="bg-white shadow-sm rounded-2xl border border-slate-200 overflow-hidden">

            {{-- Header card --}}
            <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-700">
                    Daftar Jadwal Laboratorium
                </h2>
                <span class="inline-flex items-center rounded-full bg-slate-50 px-3 py-1 text-xs font-medium text-slate-700">
                    Total: {{ $jadwals->count() }} jadwal
                </span>
            </div>

            {{-- Tabel responsif --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs md:text-sm text-left">
                    <thead class="bg-[#0D47C9] text-white">
                        <tr class="border-b border-slate-200">
                            <th class="px-4 py-3 font-semibold uppercase tracking-wide text-white">Hari</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wide text-white">Waktu Sesi</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wide text-white">Mata Pelajaran</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wide text-white">Guru</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wide text-white">Ruang Lab</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wide text-white">Dibuat Oleh</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wide text-white text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach($jadwals as $jadwal)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ $jadwal->hari }}
                                </td>

                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ substr($jadwal->waktu_mulai, 0, 5) }} - {{ substr($jadwal->waktu_selesai, 0, 5) }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $jadwal->mata_pelajaran }}
                                </td>

                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ $jadwal->nama_guru }}
                                </td>

                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ $jadwal->ruang_lab }}
                                </td>

                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ $jadwal->admin->nama ?? 'N/A' }}
                                </td>

                                {{-- Bagian Aksi dengan Icon Buttons --}}
                                <td class="px-4 py-3">
                                    @if ($allowedToManage)
                                        <div class="flex items-center justify-center gap-2">

                                            {{-- Tombol Edit dengan Icon --}}
                                            <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 transition-colors duration-200 group"
                                            title="Edit Jadwal">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                           {{-- Tombol Hapus dengan Icon & Modal --}}
                                            <form id="delete-jadwal-{{ $jadwal->id }}"
                                                action="{{ route('admin.jadwal.destroy', $jadwal->id) }}"
                                                method="POST"
                                                class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            
                                            <button type="button"
                                                    class="btn-confirm inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors duration-200"
                                                    title="Hapus Jadwal"
                                                    data-form-id="delete-jadwal-{{ $jadwal->id }}"
                                                    data-title="Hapus Jadwal?"
                                                    data-message="Hapus jadwal {{ $jadwal->mata_pelajaran }} ({{ $jadwal->hari }}, {{ substr($jadwal->waktu_mulai, 0, 5) }}-{{ substr($jadwal->waktu_selesai, 0, 5) }})? Data presensi terkait akan tetap tersimpan."
                                                    data-btn-ok="Hapus"
                                                    data-btn-color="rose">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                            </form>

                                        </div>
                                    @else
                                        <div class="flex items-center justify-center">
                                            <span class="text-xs text-slate-400 italic">
                                                —
                                            </span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    @else
        {{-- Pesan kosong --}}
        <div class="rounded-xl border border-dashed border-slate-300 bg-white px-6 py-10 text-center">
            <p class="text-sm text-slate-500">
                ⏳ Belum ada jadwal laboratorium yang tersedia saat ini.
            </p>
        </div>
    @endif

</div>

{{-- INCLUDE MODAL KONFIRMASI --}}
@include('components.modal-confirmation')

@endsection
