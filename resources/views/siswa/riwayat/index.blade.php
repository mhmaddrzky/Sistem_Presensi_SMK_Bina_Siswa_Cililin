@extends('layouts.siswa')

@section('content')

{{-- ================= HEADER HALAMAN PRESENSI ================= --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Presensi Siswa Praktikum</h1>
    <p class="text-gray-600 text-sm">Lakukan Absen ketika sesi dimulai</p>
</div>

{{-- ================= ALERT SUCCESS ================= --}}
@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 p-4 mb-6 rounded-xl shadow-sm flex items-center gap-3">
    <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
    </svg>
    <span class="font-semibold">{{ session('success') }}</span>
</div>
@endif

{{-- ================= ALERT ERROR ================= --}}
@if(session('error'))
<div class="bg-red-50 border border-red-200 text-red-700 p-4 mb-6 rounded-xl shadow-sm flex items-center gap-3">
    <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
    </svg>
    <span class="font-semibold">{{ session('error') }}</span>
</div>
@endif

{{-- ================= JIKA TIDAK ADA JADWAL ================= --}}
@if ($jadwalsAktif->isEmpty())
<div class="bg-yellow-50 border border-yellow-300 text-yellow-800 p-6 rounded-xl shadow-sm mb-8">
    <p class="font-semibold">
        Tidak ada jadwal aktif hari ini ({{ now()->translatedFormat('l, d F Y') }}).
    </p>
</div>
@else

{{-- ================= SECTION 1: CARDS PRESENSI (VERTIKAL) ================= --}}
<div class="space-y-4 mb-10">

@foreach ($jadwalsAktif as $jadwal)

@php
    $isHadir = $jadwal->is_hadir;
    $statusWaktu = $jadwal->waktu_status;
    $isPenuh = $jadwal->is_penuh;

    $buttonDisabled = $isHadir || $isPenuh || ($statusWaktu !== 'Sedang Berlangsung');

    // Badge color
    $badgeColor = 'bg-green-600';
    if ($statusWaktu == 'Belum Dimulai') $badgeColor = 'bg-yellow-500';
    if ($isPenuh) $badgeColor = 'bg-red-600';

    // Button text
    $buttonText = 'Presensi';
    if ($isHadir) $buttonText = 'Sudah Hadir';
    elseif ($isPenuh) $buttonText = 'Kuota Penuh';
    elseif ($statusWaktu == 'Belum Dimulai') $buttonText = 'Belum Dimulai';
@endphp

{{-- ================= CARD PRESENSI ================= --}}
<div class="bg-[#f8f9fa] rounded-2xl shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
    
    <div class="p-6">
        {{-- HEADER: Judul & Badge --}}
        <div class="flex justify-between items-start gap-4 mb-4 pb-4 border-b border-gray-200">
            <div class="min-w-0 flex-1">
                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $jadwal->mata_pelajaran }}</h3>
                <p class="text-sm text-gray-600">{{ $jadwal->nama_guru }}</p>
            </div>

            {{-- BADGE STATUS --}}
            <span class="{{ $badgeColor }} text-white text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm flex-shrink-0">
                {{ $isHadir ? 'Sudah Hadir' : ($isPenuh ? 'Kuota Penuh' : $statusWaktu) }}
            </span>
        </div>

        {{-- BODY: Detail & Button --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            
            {{-- Detail Info --}}
            <div class="space-y-2 text-sm text-gray-700 flex-1">
                <div class="flex items-center gap-2">
                    📍 <span>{{ $jadwal->ruang_lab }} (Kapasitas: {{ $jadwal->kapasitas }} siswa)</span>
                </div>
                <div class="flex items-center gap-2">
                    📅 <span>{{ $jadwal->hari }}, {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d/m/Y') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    ⏰ <span>{{ substr($jadwal->waktu_mulai, 0, 5) }} - {{ substr($jadwal->waktu_selesai, 0, 5) }}</span>
                </div>
            </div>

            {{-- Button Presensi --}}
            <div class="flex-shrink-0">
                <form action="{{ route('siswa.presensi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">

                    <button type="submit"
                        @if($buttonDisabled) disabled @endif
                        class="w-full md:w-auto px-8 py-3 rounded-xl text-sm font-semibold shadow-sm
                        transition-all duration-200
                        {{ $buttonDisabled 
                            ? 'bg-gray-400 cursor-not-allowed text-white' 
                            : 'bg-blue-600 text-white hover:bg-blue-700 hover:shadow-md' }}">
                        {{ $buttonText }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endforeach
</div>
@endif

{{-- ================= DIVIDER ================= --}}
<div class="border-t-2 border-gray-300 my-10"></div>

{{-- ================= SECTION 2: RIWAYAT PRESENSI ================= --}}
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-1">Catatan Riwayat Presensi Saya</h2>
    <p class="text-gray-600 text-sm">Daftar lengkap kehadiran Anda di laboratorium</p>
</div>

{{-- ================= TABLE RIWAYAT ================= --}}
<div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full">
            {{-- Table Head --}}
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tanggal Presensi</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Waktu Presensi</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Mata Pelajaran</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Sesi</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status Akhir</th>
                </tr>
            </thead>
            
            {{-- Table Body --}}
            <tbody class="divide-y divide-gray-200">
                @forelse($riwayats as $riwayat)
                    @php
                        $statusColor = 'text-gray-800';
                        $statusBg = 'bg-gray-100';
                        
                        if ($riwayat->status == 'Hadir') {
                            $statusColor = 'text-green-700';
                            $statusBg = 'bg-green-100';
                        } elseif ($riwayat->status == 'Sakit' || $riwayat->status == 'Izin') {
                            $statusColor = 'text-yellow-700';
                            $statusBg = 'bg-yellow-100';
                        } elseif ($riwayat->status == 'Alfa') {
                            $statusColor = 'text-red-700';
                            $statusBg = 'bg-red-100';
                        }
                    @endphp
                    
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-800">
                            {{ \Carbon\Carbon::parse($riwayat->tanggal)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800">
                            {{ $riwayat->waktu }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800 font-medium">
                            {{ $riwayat->jadwal->mata_pelajaran ?? 'Jadwal Dihapus' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ isset($riwayat->jadwal->waktu_mulai) ? substr($riwayat->jadwal->waktu_mulai, 0, 5) : 'N/A' }} - 
                            {{ isset($riwayat->jadwal->waktu_selesai) ? substr($riwayat->jadwal->waktu_selesai, 0, 5) : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="{{ $statusBg }} {{ $statusColor }} px-3 py-1 rounded-full font-semibold text-xs">
                                {{ $riwayat->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-gray-500 font-medium">Anda belum memiliki riwayat presensi.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection