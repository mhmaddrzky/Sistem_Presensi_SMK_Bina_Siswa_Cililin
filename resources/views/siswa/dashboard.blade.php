@extends('layouts.siswa')

@section('content')
    <h1 style="color: #007bff;">Selamat Datang, {{ auth()->user()->siswa->nama ?? auth()->user()->username }}!</h1>
    <p>Kelas: {{ auth()->user()->siswa->kelas ?? 'Data Kelas Tidak Tersedia' }}</p>
    
    <hr>
    
    {{-- Tampilkan pesan sukses dari proses Presensi --}}
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            ✅ {{ session('success') }}
        </div>
    @endif
    
    <h2 style="color: #333;">Aksi Cepat</h2>
    
    <div style="display: flex; gap: 20px; margin-top: 20px;">
        
        {{-- Tombol Presensi Hari Ini --}}
        <div style="border: 1px solid #ccc; padding: 20px; width: 30%; border-radius: 8px; background: #fff;">
            <h3>Lakukan Presensi</h3>
            <p>Catat kehadiran Anda untuk jadwal laboratorium yang tersedia hari ini.</p>
            <a href="{{ route('siswa.presensi.form') }}" style="display: block; padding: 10px; background: #28a745; color: white; text-align: center; text-decoration: none; border-radius: 4px;">
                Cek Jadwal & Presensi
            </a>
        </div>

        {{-- Tombol Riwayat Presensi (Fitur Menyusul) --}}
     <div style="border: 1px solid #ccc; padding: 20px; width: 30%; border-radius: 8px; background: #fff;">
            <h3>Riwayat Presensi</h3>
            <p>Lihat catatan kehadiran Anda sebelumnya.</p>
            <a href="{{ route('siswa.riwayat.index') }}" style="display: block; padding: 10px; background: #007bff; color: white; text-align: center; text-decoration: none; border-radius: 4px;">
                Cek Riwayat
            </a>
        </div>
        
    </div>

@endsection