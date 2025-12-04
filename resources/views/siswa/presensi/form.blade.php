@extends('layouts.siswa') 

@section('content')
    <h1 style="color: #333;">Presensi Siswa Praktikum</h1>
    <p style="color: #555; margin-bottom: 30px;">Lakukan Absen ketika sesi dimulai</p>

    {{-- Notifikasi Sukses/Error --}}
    @if(session('success'))
        <div style="background: #d4edda; color: green; padding: 10px; border-radius: 5px; margin-bottom: 20px;">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="background: #f8d7da; color: red; padding: 10px; border-radius: 5px; margin-bottom: 20px;">❌ {{ session('error') }}</div>
    @endif
    
    @if ($jadwals->isEmpty())
        <div style="background: #fff3cd; padding: 15px; border-radius: 8px;">
            Tidak ada jadwal laboratorium yang aktif hari ini ({{ now()->translatedFormat('l, d F Y') }}).
        </div>
    @else
        {{-- LOOP UNTUK SETIAP JADWAL --}}
        @foreach ($jadwals as $jadwal)
            @php
                $isHadir = $jadwal->is_hadir;
                $statusWaktu = $jadwal->waktu_status; // Belum Dimulai, Sedang Berlangsung
                $isPenuh = $jadwal->is_penuh;

                $buttonDisabled = $isHadir || $isPenuh || ($statusWaktu !== 'Sedang Berlangsung');
// ...// ...
                $badgeColor = '#4CAF50'; // Hijau (Sedang Berlangsung)

                if ($statusWaktu === 'Belum Dimulai' || Str::startsWith($statusWaktu, 'Menunggu Hari')) {
                    $badgeColor = '#ffc107'; // Kuning/Oranye (Menunggu Hari/Waktu)
                } elseif ($isPenuh) {
                    $badgeColor = '#dc3545'; // Merah (Penuh)
                } elseif ($statusWaktu === 'Selesai (Waktu Terlewat)') {
                    $badgeColor = '#6c757d'; // Abu-abu (Selesai)
                }
                // ...
                
                $buttonText = 'Belum Hadir';
                if ($isHadir) {
                    $buttonText = 'Sudah Hadir';
                } elseif ($statusWaktu === 'Belum Dimulai') {
                    $buttonText = 'Belum Dimulai';
                } elseif ($isPenuh) {
                    $buttonText = 'Kuota Penuh';
                }
            @endphp

            <div style="background: #ffffff; border: 1px solid #e0e0e0; border-radius: 12px; padding: 25px; margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
                
                {{-- Baris Judul & Status --}}
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
                    <div>
                        <h3 style="margin: 0; font-size: 1.2em; color: #333;">{{ $jadwal->mata_pelajaran }}</h3>
                        <p style="margin: 5px 0 0; color: #666; font-size: 0.9em;">{{ $jadwal->nama_guru }}</p>
                    </div>
                    
                    {{-- Badge Status --}}
                    @if (!$isHadir)
                        <span style="background: {{ $badgeColor }}; color: white; padding: 5px 10px; border-radius: 5px; font-weight: bold; font-size: 0.8em;">
                            {{ $isPenuh ? 'Kuota Penuh' : $statusWaktu }} 
                        </span>
                    @else
                        <span style="background: #007bff; color: white; padding: 5px 10px; border-radius: 5px; font-weight: bold; font-size: 0.8em;">
                            Sudah Hadir
                        </span>
                    @endif
                </div>

                {{-- Detail dan Tombol --}}
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    
                    {{-- Blok Detail Kiri --}}
                    <div style="width: 60%;">
                        <div style="display: flex; align-items: center; margin-bottom: 10px;">
                            <span style="font-size: 1.2em; margin-right: 10px;">📍</span> 
                            <p style="margin: 0;">{{ $jadwal->ruang_lab }} (Kapasitas: {{ $jadwal->kapasitas }} siswa)</p>
                        </div>
                        <div style="display: flex; align-items: center; margin-bottom: 10px;">
                            <span style="font-size: 1.2em; margin-right: 10px;">📅</span> 
                            <p style="margin: 0;">{{ $jadwal->hari }}</p>
                        </div>
                        <div style="display: flex; align-items: center;">
                            <span style="font-size: 1.2em; margin-right: 10px;">⏰</span> 
                            <p style="margin: 0;">
    {{ substr($jadwal->waktu_mulai, 0, 5) }} - {{ substr($jadwal->waktu_selesai, 0, 5) }}
</p>
                        </div>
                    </div>

                    {{-- Tombol Presensi Kanan --}}
                    <div>
                        <form action="{{ route('siswa.presensi.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                            <button type="submit" 
                                    @if ($buttonDisabled) disabled @endif
                                    style="background: #007bff; color: white; border: none; padding: 15px 30px; border-radius: 8px; font-weight: bold; cursor: {{ $buttonDisabled ? 'not-allowed' : 'pointer' }}; opacity: {{ $buttonDisabled ? '0.6' : '1' }};">
                                {{ $buttonText }}
                            </button>
                        </form>
                    </div>
                </div> 
            </div> 
        @endforeach
    @endif
@endsection