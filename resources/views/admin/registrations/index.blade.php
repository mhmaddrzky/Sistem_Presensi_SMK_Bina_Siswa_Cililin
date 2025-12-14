@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- HEADER JUDUL --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-blue-900">
                Daftar Permintaan Registrasi Siswa
            </h1>
            <p class="mt-1 text-sm text-slate-600">
                Kelola akun siswa yang menunggu persetujuan aktivasi.
            </p>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800 shadow-sm flex items-center gap-2">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800 shadow-sm flex items-center gap-2">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- KONTEN UTAMA --}}
    @if($registrations->isEmpty())
        {{-- State Kosong --}}
        <div class="rounded-xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center shadow-sm">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 mb-3">
                <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-sm font-medium text-slate-900">Semua Beres!</p>
            <p class="text-sm text-slate-500">Tidak ada permintaan registrasi baru saat ini.</p>
        </div>
    @else
        {{-- Tabel Data --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            
            {{-- Header Tabel --}}
            <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50/50 px-4 py-3">
                <h2 class="text-sm font-semibold text-slate-700">Antrian Pending</h2>
                
                <div class="flex items-center gap-3">
                    <span class="rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-700">
                        Total: {{ $registrations->count() }}
                    </span>

                    {{-- TOMBOL SETUJUI SEMUA --}}
                    <form id="approve-all-form" action="{{ route('admin.registrations.approveAll') }}" method="POST">
                        @csrf
                        <button type="button"
                            class="btn-confirm inline-flex items-center justify-center rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all"
                            data-form-id="approve-all-form"
                            data-title="Setujui Semua?"
                            data-message="Apakah Anda yakin ingin menyetujui SELURUH permintaan registrasi siswa yang ada di daftar ini sekaligus?"
                            data-btn-ok="Setujui Semua"
                            data-btn-color="emerald">
                            Setujui Semua
                        </button>
                    </form>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-[#0D47C9] text-white">
                        <tr>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-xs">No</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-xs">Informasi Siswa</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-xs">NIS</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-xs">Kelas</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-xs">Jurusan</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-xs">Tanggal</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-xs text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($registrations as $reg)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3 text-slate-500 w-12">{{ $loop->iteration }}</td>
                            
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-900">{{ $reg->siswa->nama }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">
                                    Req Username: <span class="font-mono text-blue-600 bg-blue-50 px-1 rounded">{{ $reg->username_request }}</span>
                                </div>
                            </td>

                            <td class="px-4 py-3 text-slate-600">{{ $reg->siswa->nis }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $reg->siswa->kelas }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700 ring-1 ring-inset ring-slate-600/20">
                                    {{ $reg->siswa->jurusan }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-500 text-xs">
                                {{ \Carbon\Carbon::parse($reg->tanggal_reg)->format('d M Y') }}
                            </td>

                            {{-- KOLOM AKSI --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    
                                    {{-- TOMBOL SETUJUI (HIJAU) --}}
                                    <form id="approve-{{ $reg->id_reg }}" action="{{ route('admin.registrations.approve', $reg->id_reg) }}" method="POST">
                                        @csrf
                                        <button type="button" 
                                            class="btn-confirm inline-flex items-center rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all"
                                            title="Setujui Siswa"
                                            data-form-id="approve-{{ $reg->id_reg }}"
                                            data-title="Setujui Registrasi?"
                                            data-message="Setujui registrasi siswa {{ $reg->siswa->nama }} ({{ $reg->siswa->kelas }})? Akun akan aktif dan bisa digunakan."
                                            data-btn-ok="Setujui"
                                            data-btn-color="emerald">
                                            Setujui
                                        </button>
                                    </form>

                                    {{-- TOMBOL TOLAK (MERAH) --}}
                                    <form id="reject-{{ $reg->id_reg }}" action="{{ route('admin.registrations.reject', $reg->id_reg) }}" method="POST">
                                        @csrf
                                        <button type="button" 
                                            class="btn-confirm inline-flex items-center rounded-lg bg-white border border-rose-200 px-3 py-1.5 text-xs font-medium text-rose-600 shadow-sm hover:bg-rose-50 hover:border-rose-300 focus:outline-none focus:ring-2 focus:ring-rose-500 transition-all"
                                            title="Tolak Registrasi"
                                            data-form-id="reject-{{ $reg->id_reg }}"
                                            data-title="Tolak Registrasi?"
                                            data-message="Tolak dan hapus permintaan registrasi dari {{ $reg->siswa->nama }}? Data akan dihapus permanen."
                                            data-btn-ok="Tolak & Hapus"
                                            data-btn-color="rose">
                                            Tolak
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>

{{-- PANGGIL COMPONENT MODAL --}}
@include('components.modal-confirmation')

@endsection