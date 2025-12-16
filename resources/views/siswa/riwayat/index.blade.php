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
                        } elseif ($riwayat->status == 'Izin') {
                            $statusColor = 'text-blue-700';
                            $statusBg = 'bg-blue-100';
                        } elseif ($riwayat->status == 'Sakit') {
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