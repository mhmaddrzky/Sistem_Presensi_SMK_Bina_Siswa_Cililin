@extends('layouts.siswa')

@section('content')

{{-- ================= HEADER ================= --}}
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
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 w-48">Hari, Tanggal</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Waktu</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Mata Pelajaran</th>
                    {{-- KOLOM BARU: GURU --}}
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Guru Pengampu</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Sesi</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                </tr>
            </thead>
            
            <tbody class="divide-y divide-gray-200">
                @forelse($riwayats as $riwayat)
                    @php
                        $statusColor = 'text-gray-800';
                        $statusBg = 'bg-gray-100';
                        
                        if ($riwayat->status == 'Hadir') {
                            $statusColor = 'text-green-700'; $statusBg = 'bg-green-100';
                        } elseif ($riwayat->status == 'Izin') {
                            $statusColor = 'text-blue-700'; $statusBg = 'bg-blue-100';
                        } elseif ($riwayat->status == 'Sakit') {
                            $statusColor = 'text-yellow-700'; $statusBg = 'bg-yellow-100';
                        } elseif ($riwayat->status == 'Alfa') {
                            $statusColor = 'text-red-700'; $statusBg = 'bg-red-100';
                        }

                        // 1. AMBIL HARI DARI JADWAL
                        $namaHari = $riwayat->jadwal->hari ?? '-';

                        // 2. AMBIL TANGGAL DARI RIWAYAT 
                        $tanggalRealtime = \Carbon\Carbon::parse($riwayat->tanggal)->format('d/m/Y');
                    @endphp
                    
                    <tr class="hover:bg-gray-50 transition-colors">
                        {{-- HARI & TANGGAL --}}
                        <td class="px-6 py-4 text-sm text-gray-800 font-medium">
                            {{ $namaHari }}, {{ $tanggalRealtime }}
                        </td>

                        {{-- WAKTU --}}
                        <td class="px-6 py-4 text-sm text-gray-800">
                            {{ $riwayat->waktu }}
                        </td>

                        {{-- MATA PELAJARAN --}}
                        <td class="px-6 py-4 text-sm text-gray-800 font-bold">
                            {{ $riwayat->jadwal->mata_pelajaran ?? 'Jadwal Dihapus' }}
                        </td>

                        {{-- GURU PENGAMPU --}}
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $riwayat->jadwal->nama_guru ?? '-' }}
                        </td>

                        {{-- SESI --}}
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ isset($riwayat->jadwal->waktu_mulai) ? substr($riwayat->jadwal->waktu_mulai, 0, 5) : 'N/A' }} - 
                            {{ isset($riwayat->jadwal->waktu_selesai) ? substr($riwayat->jadwal->waktu_selesai, 0, 5) : 'N/A' }}
                        </td>

                        {{-- STATUS --}}
                        <td class="px-6 py-4 text-sm">
                            <span class="{{ $statusBg }} {{ $statusColor }} px-3 py-1 rounded-full font-semibold text-xs border border-opacity-20">
                                {{ $riwayat->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 font-medium">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span>Anda belum memiliki riwayat presensi.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection