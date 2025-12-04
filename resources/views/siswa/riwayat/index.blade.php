@extends('layouts.siswa')

@section('content')
    <h1 style="color: #1f3a93;">Catatan Riwayat Presensi Saya</h1>
    <p>Daftar lengkap kehadiran Anda di laboratorium.</p>

    <table border="1" style="width: 100%; margin-top: 20px; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="padding: 10px;">Tanggal Presensi</th>
                <th style="padding: 10px;">Waktu Presensi</th>
                <th style="padding: 10px;">Mata Pelajaran</th>
                <th style="padding: 10px;">Sesi</th>
                <th style="padding: 10px;">Status Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayats as $riwayat)
                @php
                    $statusColor = 'black';
                    if ($riwayat->status == 'Hadir') $statusColor = 'green';
                    if ($riwayat->status == 'Sakit' || $riwayat->status == 'Izin') $statusColor = '#ffc107'; // Kuning
                    if ($riwayat->status == 'Alfa') $statusColor = 'red';
                @endphp
                <tr>
                    <td style="padding: 8px;">{{ $riwayat->tanggal }}</td>
                    <td style="padding: 8px;">{{ $riwayat->waktu }}</td>
                    <td style="padding: 8px;">{{ $riwayat->jadwal->mata_pelajaran ?? 'Jadwal Dihapus' }}</td>
                    <td style="padding: 8px;">{{ $riwayat->jadwal->waktu_mulai ?? 'N/A' }} - {{ $riwayat->jadwal->waktu_selesai ?? 'N/A' }}</td>
                    <td style="padding: 8px; font-weight: bold; color: {{ $statusColor }};">
                        {{ $riwayat->status }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 15px;">Anda belum memiliki riwayat presensi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection