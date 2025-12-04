@extends('layouts.admin')

@section('content')
    @php
        $user = Auth::user();
        $role = $user->role;
        $adminDetail = $user->admin ?? (object)['nama' => $user->username]; // Dapatkan nama staf
        $namaSapaan = $adminDetail->nama ?? $user->username;
        
        $welcomeText = "Selamat Datang di Panel $role, $namaSapaan!";
        
        if ($role === 'Guru') {
            $welcomeText = "Selamat Datang, Guru.";
        } elseif ($role === 'AsistenLab') {
            $welcomeText = "Selamat Datang, Asisten Lab.";
        } elseif ($role === 'Admin') {
            $welcomeText = "Selamat Datang, Administrator Utama. Anda memiliki kendali penuh atas sistem.";
        }
    @endphp

    <h1 style="color: #1f3a93;">{{ $welcomeText }}</h1>
    
    <hr>
    
    <h2 style="color: #1f3a93;">Ringkasan Tugas Cepat</h2>

 <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-top: 20px;">
    
    {{-- Kartu 1: Persetujuan Registrasi --}}
    @if ($role === 'Admin' || $role === 'Guru' || $role === 'AsistenLab')
        <div style="border: 1px solid #ccc; padding: 20px; width: 31%; border-radius: 8px; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <h3>Persetujuan Siswa</h3>
            <p style="font-size: 0.9em; color: #666;">Verifikasi pendaftaran siswa baru.</p>
            <a href="{{ route('admin.registrations.index') }}" style="display: block; padding: 10px; background: #e6b800; color: #1f3a93; text-align: center; text-decoration: none; border-radius: 4px; font-weight: bold;">
                Cek Permintaan
            </a>
        </div>
    @endif

    {{-- Kartu 2: Kelola Jadwal (LAB MANAGER) --}}
    @if ($role !== 'Kepsek')
        <div style="border: 1px solid #ccc; padding: 20px; width: 31%; border-radius: 8px; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <h3>Kelola Jadwal Lab</h3>
            <p style="font-size: 0.9em; color: #666;">Tambah, Edit, atau Hapus jadwal semesteran.</p>
            <a href="{{ route('admin.jadwal.index') }}" style="display: block; padding: 10px; background: #33a33a; color: white; text-align: center; text-decoration: none; border-radius: 4px;">
                Lihat Jadwal
            </a>
        </div>
    @endif

    {{-- Kartu 3: Pembagian Sesi / Mapping (LAB MANAGER) --}}
    @if ($role !== 'Kepsek')
        <div style="border: 1px solid #ccc; padding: 20px; width: 31%; border-radius: 8px; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <h3>Pembagian Sesi & Kuota</h3>
            <p style="font-size: 0.9em; color: #666;">Tentukan siswa mana yang terdaftar di sesi/jadwal tertentu.</p>
            <a href="{{ route('admin.sesi.index') }}" style="display: block; padding: 10px; background: #4a4a8f; color: white; text-align: center; text-decoration: none; border-radius: 4px;">
                Atur Peserta
            </a>
        </div>
    @endif
    
    {{-- Kartu 4: Koreksi Presensi (OPERASIONAL) --}}
    @if ($role !== 'Kepsek')
        <div style="border: 1px solid #ccc; padding: 20px; width: 31%; border-radius: 8px; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <h3>Koreksi Kehadiran</h3>
            <p style="font-size: 0.9em; color: #666;">Validasi akhir (Hadir/Sakit/Izin).</p>
            <a href="{{ route('admin.koreksi.index') }}" style="display: block; padding: 10px; background: #dc3545; color: white; text-align: center; text-decoration: none; border-radius: 4px;">
                Koreksi Sekarang
            </a>
        </div>
    @endif
    
    {{-- Kartu 5: Rekap Laporan (SEMUA BOLEH LIHAT) --}}
    <div style="border: 1px solid #ccc; padding: 20px; width: 31%; border-radius: 8px; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h3>Rekap Absensi</h3>
        <p style="font-size: 0.9em; color: #666;">Lihat data kehadiran per minggu/bulan.</p>
        <a href="{{ route('admin.laporan.index') }}" style="display: block; padding: 10px; background: #1f3a93; color: white; text-align: center; text-decoration: none; border-radius: 4px;">
            Lihat Laporan
        </a>
    </div>
    
    {{-- Kartu 6: Manajemen Akun Staf (HANYA ADMIN UTAMA) --}}
    @if ($role === 'Admin')
        <div style="border: 1px solid #ccc; padding: 20px; width: 31%; border-radius: 8px; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <h3>Manajemen Akun Staf</h3>
            <p style="font-size: 0.9em; color: #666;">Buat akun Guru, Aslab, atau Kepsek baru.</p>
            <a href="{{ route('admin.users.index') }}" style="display: block; padding: 10px; background: #5a5a5a; color: white; text-align: center; text-decoration: none; border-radius: 4px;">
                Kelola Akun Staf
            </a>
        </div>
    @endif

</div>
    
    <h2 style="margin-top: 40px;">Status Sistem</h2>
    {{-- Tampilkan pesan sukses/error dari proses sebelumnya --}}
    @if(session('success'))
        <p style="color: green; font-weight: bold;">✅ {{ session('success') }}</p>
    @endif
    @if(session('error'))
        <p style="color: red; font-weight: bold;">❌ {{ session('error') }}</p>
    @endif

@endsection