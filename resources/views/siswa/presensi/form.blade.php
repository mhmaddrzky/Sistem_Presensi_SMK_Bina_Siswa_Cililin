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

{{-- ================= GRID CARD ================= --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

@foreach ($jadwals as $jadwal)

@php
    $isHadir = $jadwal->is_hadir;
    $statusWaktu = $jadwal->waktu_status;
    $isPenuh = $jadwal->is_penuh;
    $statusPresensi = $jadwal->status_presensi ?? null;

    // Default Style Badge
    $badgeClass = 'px-3 py-1.5 rounded-full text-xs font-semibold shadow-sm border';
    $badgeColor = '';
    $badgeText = '';

    // 1. CEK STATUS DARI GURU (Prioritas Utama)
    if ($statusPresensi) {
        if ($statusPresensi == 'Hadir') {
            $badgeColor = 'bg-green-600 text-white border-transparent';
            $badgeText = 'Hadir';
        } elseif ($statusPresensi == 'Sakit') {
            $badgeColor = 'bg-yellow-500 text-white border-transparent';
            $badgeText = 'Sakit';
        } elseif ($statusPresensi == 'Izin') {
            $badgeColor = 'bg-blue-500 text-white border-transparent';
            $badgeText = 'Izin';
        } elseif ($statusPresensi == 'Alfa') {
            $badgeColor = 'bg-red-600 text-white border-transparent';
            $badgeText = 'Alfa';
        }
    } 
    // 2. JIKA BELUM ABSEN, CEK STATUS WAKTU
    else {
        $badgeText = $statusWaktu;

        // --- WARNA KONTRAS DISINI ---
        if (Str::contains($statusWaktu, 'Menunggu Hari')) {
            $badgeColor = 'bg-gray-100 text-gray-500 border border-gray-200';
        
        } elseif (Str::contains($statusWaktu, 'Selesai')) {
            $badgeColor = 'bg-blue-100 text-blue-700 border-blue-200';
        
        } elseif ($statusWaktu == 'Sedang Berlangsung') {
            $badgeColor = 'bg-green-100 text-green-700 border-green-300 animate-pulse';
        
        } elseif ($statusWaktu == 'Belum Dimulai') {
            $badgeColor = 'bg-yellow-100 text-yellow-700 border-yellow-300';
            
        } elseif ($isPenuh) {
            $badgeColor = 'bg-red-100 text-red-700 border-red-300';
            $badgeText = 'Kuota Penuh';
        }
    }
@endphp

{{-- ================= CARD ITEM ================= --}}
<div class="bg-[#f8f9fa] rounded-2xl shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">

    {{-- HEADER CARD --}}
    <div class="p-5 flex justify-between items-start gap-4 border-b border-gray-200">
        <div class="min-w-0">
            <h3 class="text-lg font-bold text-gray-900 truncate">{{ $jadwal->mata_pelajaran }}</h3>
            <p class="text-sm text-gray-600">{{ $jadwal->nama_guru }}</p>
        </div>

        {{-- BADGE STATUS --}}
        <span class="{{ $badgeClass }} {{ $badgeColor }} flex-shrink-0">
            {{ $badgeText }}
        </span>
    </div>

    {{-- BODY CARD --}}
    <div class="p-5 space-y-2 text-sm text-gray-700">

        {{-- ICON LAB --}}
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/>
            </svg>
            <span>{{ $jadwal->ruang_lab }}</span>
        </div>

        {{-- ICON KALENDER --}}
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <span>{{ $jadwal->hari }}, {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai ?? now())->format('d/m/Y') }}</span>
        </div>

        {{-- ICON JAM --}}
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
            <span>{{ substr($jadwal->waktu_mulai, 0, 5) }} - {{ substr($jadwal->waktu_selesai, 0, 5) }}</span>
        </div>
    </div>

</div>
@endforeach
</div>
@endif

@endsection