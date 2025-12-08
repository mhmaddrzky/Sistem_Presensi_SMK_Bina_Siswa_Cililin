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

                                <td class="px-4 py-3">
                                    @if ($allowedToManage)
                                        <div class="flex items-center justify-center gap-3">

                                            {{-- Edit --}}
                                            <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}"
                                               class="inline-flex items-center gap-1 text-[11px] md:text-xs font-semibold text-blue-600 hover:underline">
                                                <i class="fas fa-edit"></i>
                                                <span>Edit</span>
                                            </a>

                                            {{-- Separator --}}
                                            <span class="hidden md:inline text-slate-300">|</span>

                                            {{-- Hapus --}}
                                            <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus jadwal ini? Tindakan tidak dapat dibatalkan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 text-[11px] md:text-xs font-semibold text-rose-600 hover:underline">
                                                    <i class="fas fa-trash-alt"></i>
                                                    <span>Hapus</span>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-[11px] text-slate-400 italic">
                                            Tidak ada aksi
                                        </span>
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
@endsection
