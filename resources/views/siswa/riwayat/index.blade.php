@extends('layouts.siswa')

@section('content')

{{-- ================= ALERT SUCCESS ================= --}}
@if(session('success'))
<div id="successAlert" class="fixed top-4 left-1/2 -translate-x-1/2 z-[9999] bg-white border-l-4 border-green-500 px-4 py-3 rounded-lg shadow-2xl flex items-center gap-3 max-w-md w-11/12 md:w-auto opacity-100 transition-all duration-300">
    <div class="flex-shrink-0 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
    </div>
    <span class="text-gray-800 text-sm font-medium">{{ session('success') }}</span>
    <button onclick="document.getElementById('successAlert').remove()" class="ml-auto text-gray-400 hover:text-gray-600 transition-colors">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>
</div>
@endif

{{-- ================= ALERT ERROR ================= --}}
@if(session('error'))
<div id="errorAlert" class="fixed top-4 left-1/2 -translate-x-1/2 z-[9999] bg-white border-l-4 border-red-500 px-4 py-3 rounded-lg shadow-2xl flex items-center gap-3 max-w-md w-11/12 md:w-auto opacity-100 transition-all duration-300">
    <div class="flex-shrink-0 w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
    </div>
    <span class="text-gray-800 text-sm font-medium">{{ session('error') }}</span>
    <button onclick="document.getElementById('errorAlert').remove()" class="ml-auto text-gray-400 hover:text-gray-600 transition-colors">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>
</div>
@endif

{{-- ================= HEADER HALAMAN PRESENSI ================= --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Presensi Siswa Praktikum</h1>
    <p class="text-gray-600 text-sm">Lakukan Absen ketika sesi dimulai</p>
</div>

{{-- ================= JIKA TIDAK ADA JADWAL (ADMIN BELUM BUAT) ================= --}}
@if ($jadwalsAktif->isEmpty())
<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8 mb-8">
    <div class="flex flex-col items-center justify-center text-center">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Jadwal Tersedia</h3>
        <p class="text-gray-600 text-sm max-w-md">
            Jadwal presensi untuk hari ini belum dibuat. Silakan hubungi admin atau cek kembali nanti.
        </p>
    </div>
</div>

{{-- ================= JIKA SEMUA SUDAH PRESENSI ================= --}}
@elseif ($jadwalsAktif->where('is_hadir', false)->isEmpty())
<div class="bg-white border-2 border-blue-200 rounded-xl shadow-sm p-6 mb-8">
    <div class="flex items-center gap-4">
        <div class="flex-shrink-0">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="w-7 h-7 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
        <div class="flex-1">
            <h3 class="text-lg font-semibold text-gray-800 mb-1">Presensi Hari Ini Sudah Lengkap</h3>
            <p class="text-gray-600 text-sm">
                Anda sudah melakukan presensi untuk semua jadwal pada {{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>
        <div class="flex-shrink-0">
            <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">Selesai</span>
        </div>
    </div>
</div>

{{-- ================= JIKA ADA YANG BELUM PRESENSI ================= --}}
@else

{{-- ================= CARDS PRESENSI ================= --}}
<div class="space-y-4 mb-10" id="presensiCards">

@foreach ($jadwalsAktif as $jadwal)

{{-- HANYA TAMPILKAN CARD YANG BELUM DI-PRESENSI --}}
@if(!$jadwal->is_hadir)

@php
    $isHadir = $jadwal->is_hadir;
    $statusWaktu = $jadwal->waktu_status;
    $isPenuh = $jadwal->is_penuh;

    $buttonDisabled = $isHadir || $isPenuh || ($statusWaktu !== 'Sedang Berlangsung');

    $badgeColor = 'bg-green-600';
    if ($statusWaktu == 'Belum Dimulai') $badgeColor = 'bg-yellow-500';
    if ($isPenuh) $badgeColor = 'bg-red-600';

    $buttonText = 'Presensi';
    if ($isHadir) $buttonText = 'Sudah Hadir';
    elseif ($isPenuh) $buttonText = 'Kuota Penuh';
    elseif ($statusWaktu == 'Belum Dimulai') $buttonText = 'Belum Dimulai';
@endphp

{{-- ================= CARD PRESENSI ================= --}}
<div class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 presensi-card" data-jadwal-id="{{ $jadwal->id }}">
    
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

       
{{-- BODY DETAIL + BUTTON --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

    {{-- Detail Info --}}
    <div class="space-y-3 flex-1">

        {{-- Lokasi Lab --}}
        <div class="flex items-center gap-3 text-sm text-gray-700">
            <svg class="w-5 h-5 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/>
            </svg>
            <span>{{ $jadwal->ruang_lab }} (Kapasitas: {{ $jadwal->kapasitas }} siswa)</span>
        </div>

        {{-- Hari --}}
        <div class="flex items-center gap-3 text-sm text-gray-700">
            <svg class="w-5 h-5 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <span>{{ $jadwal->hari }}</span>
        </div>

        {{-- Jam --}}
        <div class="flex items-center gap-3 text-sm text-gray-700">
            <svg class="w-5 h-5 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
            <span>{{ substr($jadwal->waktu_mulai, 0, 5) }} - {{ substr($jadwal->waktu_selesai, 0, 5) }}</span>
        </div>

    </div>

    {{-- Button Presensi --}}
    <div class="flex-shrink-0 md:self-center">
        <form action="{{ route('siswa.presensi.store') }}" method="POST" class="presensi-form" data-jadwal-id="{{ $jadwal->id }}">
            @csrf
            <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">

            <button type="submit"
                @if($buttonDisabled) disabled @endif
                class="w-full md:w-auto px-8 py-3 rounded-xl text-sm font-semibold shadow-sm transition-all duration-200 {{ $buttonDisabled ? 'bg-gray-400 cursor-not-allowed text-white' : 'bg-blue-600 text-white hover:bg-blue-700 hover:shadow-md' }}">
                {{ $buttonText }}
            </button>

        </form>
    </div>
</div>
</div>
</div>


@endif

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
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tanggal Presensi</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Waktu Presensi</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Mata Pelajaran</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Sesi</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status Akhir</th>
                </tr>
            </thead>
            
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

{{-- ================= JAVASCRIPT ================= --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto hide alert setelah 2.5 detik
    const successAlert = document.getElementById('successAlert');
    const errorAlert = document.getElementById('errorAlert');
    
    if (successAlert) {
        setTimeout(() => {
            successAlert.classList.add('opacity-0', '-translate-y-4');
            setTimeout(() => successAlert.remove(), 300);
        }, 2500); 
    }
    
    if (errorAlert) {
        setTimeout(() => {
            errorAlert.classList.add('opacity-0', '-translate-y-4');
            setTimeout(() => errorAlert.remove(), 300);
        }, 2500); 
    }

    // Handle form submit untuk hide card otomatis
    document.querySelectorAll('.presensi-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const jadwalId = this.getAttribute('data-jadwal-id');
            const card = document.querySelector(`.presensi-card[data-jadwal-id="${jadwalId}"]`);
            
            if (card) {
                card.classList.add('opacity-0', '-translate-x-4');
                setTimeout(() => {
                    card.classList.add('hidden');
                }, 300);
            }
        });
    });
});
</script>

@endsection