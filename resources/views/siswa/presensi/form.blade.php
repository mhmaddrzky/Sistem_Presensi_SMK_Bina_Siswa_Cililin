@extends('layouts.siswa')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Daftar Sesi Praktikum</h1>
    <p class="text-gray-600 text-sm">Jadwal praktikum di laboratorium komputer</p>
</div>

{{-- ================= ALERT SUCCESS ================= --}}
@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 p-4 mb-6 rounded-xl shadow-sm">
    <span class="font-semibold">{{ session('success') }}</span>
</div>
@endif

{{-- ================= ALERT ERROR ================= --}}
@if(session('error'))
<div class="bg-red-50 border border-red-200 text-red-700 p-4 mb-6 rounded-xl shadow-sm">
    <span class="font-semibold">{{ session('error') }}</span>
</div>
@endif

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

    $buttonDisabled = $isHadir || $isPenuh || ($statusWaktu !== 'Sedang Berlangsung');

    // badge
    $badgeColor = 'bg-green-600';
    if ($statusWaktu == 'Belum Dimulai') $badgeColor = 'bg-yellow-500';
    if ($isPenuh) $badgeColor = 'bg-red-600';

    // button text
    $buttonText = 'Presensi';
    if ($isHadir) $buttonText = 'Sudah Hadir';
    elseif ($isPenuh) $buttonText = 'Kuota Penuh';
    elseif ($statusWaktu == 'Belum Dimulai') $buttonText = 'Belum Dimulai';
@endphp

{{-- ================= CARD ================= --}}
<div class="bg-[#f8f9fa] rounded-2xl shadow-md border border-gray-200 overflow-hidden">

    {{-- HEADER --}}
    <div class="p-5 flex justify-between items-start gap-4 border-b border-gray-200">
        <div class="min-w-0">
            <h3 class="text-lg font-bold text-gray-900 truncate">{{ $jadwal->mata_pelajaran }}</h3>
            <p class="text-sm text-gray-600">{{ $jadwal->nama_guru }}</p>
        </div>

        {{-- BADGE --}}
        <span class="{{ $badgeColor }} text-white text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
            {{ $isHadir ? 'Sudah Hadir' : ($isPenuh ? 'Kuota Penuh' : $statusWaktu) }}
        </span>
    </div>

    {{-- BODY --}}
    <div class="p-5 space-y-2 text-sm text-gray-700">
        <div class="flex items-center gap-2">
            📍 <span>{{ $jadwal->ruang_lab }}</span>
        </div>
        <div class="flex items-center gap-2">
            📅 <span>{{ $jadwal->hari }},
                {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d/m/Y') }}
            </span>
        </div>
        <div class="flex items-center gap-2">
            ⏰ <span>{{ substr($jadwal->waktu_mulai, 0, 5) }} - {{ substr($jadwal->waktu_selesai, 0, 5) }}</span>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="p-5 bg-gray-100 border-t border-gray-200">
        <form action="{{ route('siswa.presensi.store') }}" method="POST" class="w-full text-right">
            @csrf
            <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">

            <button type="submit"
                @if($buttonDisabled) disabled @endif
                class="px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm
                transition-all duration-200
                {{ $buttonDisabled 
                    ? 'bg-gray-400 cursor-not-allowed text-white' 
                    : 'bg-blue-600 text-white hover:bg-blue-700' }}">
                {{ $buttonText }}
            </button>
        </form>
    </div>

</div>
@endforeach
</div>
@endif

@endsection
