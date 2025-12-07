@extends('layouts.siswa')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Daftar Sesi Praktikum</h1>
    <p class="text-gray-600 text-sm">Jadwal praktikum di laboratorium komputer</p>
</div>

{{-- ================= JIKA TIDAK ADA JADWAL ================= --}}
@if ($jadwals->isEmpty())
<div class="bg-yellow-50 border border-yellow-300 text-yellow-800 p-6 rounded-xl shadow-sm">
    <p class="font-semibold">
        Tidak ada jadwal aktif hari ini ({{ now()->translatedFormat('l, d F Y') }}).
    </p>
</div>
@else

{{-- ================= GRID CARD (TANPA BUTTON) ================= --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

@foreach ($jadwals as $jadwal)

@php
    $isHadir = $jadwal->is_hadir;
    $statusWaktu = $jadwal->waktu_status;
    $isPenuh = $jadwal->is_penuh;

    // Badge color
    $badgeColor = 'bg-green-600';
    if ($statusWaktu == 'Belum Dimulai') $badgeColor = 'bg-yellow-500';
    if ($isPenuh) $badgeColor = 'bg-red-600';
@endphp

{{-- ================= CARD ================= --}}
<div class="bg-[#f8f9fa] rounded-2xl shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">

    {{-- HEADER --}}
    <div class="p-5 flex justify-between items-start gap-4 border-b border-gray-200">
        <div class="min-w-0">
            <h3 class="text-lg font-bold text-gray-900 truncate">{{ $jadwal->mata_pelajaran }}</h3>
            <p class="text-sm text-gray-600">{{ $jadwal->nama_guru }}</p>
        </div>

        {{-- BADGE STATUS --}}
        <span class="{{ $badgeColor }} text-white text-xs font-semibold px-3 py-1 rounded-full shadow-sm flex-shrink-0">
            {{ $isHadir ? 'Sudah Hadir' : ($isPenuh ? 'Kuota Penuh' : $statusWaktu) }}
        </span>
    </div>

    {{-- BODY --}}
    <div class="p-5 space-y-2 text-sm text-gray-700">
        <div class="flex items-center gap-2">
            📍 <span>{{ $jadwal->ruang_lab }}</span>
        </div>
        <div class="flex items-center gap-2">
            📅 <span>{{ $jadwal->hari }}, {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d/m/Y') }}</span>
        </div>
        <div class="flex items-center gap-2">
            ⏰ <span>{{ substr($jadwal->waktu_mulai, 0, 5) }} - {{ substr($jadwal->waktu_selesai, 0, 5) }}</span>
        </div>
    </div>

</div>
@endforeach
</div>
@endif

@endsection