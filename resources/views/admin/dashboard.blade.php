@extends('layouts.admin')

@section('content')
    @php
        $user = Auth::user();
        $role = $user->role;
        $adminDetail = $user->admin ?? (object)['nama' => $user->username];
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

    {{-- JUDUL SELAMAT DATANG --}}
    <h1 class="text-2xl font-semibold text-blue-900 mb-2">
        {{ $welcomeText }}
    </h1>

    <hr class="border-slate-200 mb-6">

    {{-- RINGKASAN TUGAS CEPAT --}}
    <h2 class="text-lg font-semibold text-blue-900 mb-4">
        Ringkasan Tugas Cepat
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-10">

        {{-- Kartu 1: Persetujuan Registrasi --}}
        @if ($role === 'Admin' || $role === 'Guru' || $role === 'AsistenLab')
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 flex flex-col justify-between">
                <div>
                    <h3 class="text-base font-semibold text-slate-800">Persetujuan Siswa</h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Verifikasi pendaftaran siswa baru.
                    </p>
                </div>
                <a href="{{ route('admin.registrations.index') }}"
                   class="mt-4 inline-block text-center text-xs font-semibold bg-yellow-400 text-blue-900 px-3 py-2 rounded-lg hover:bg-yellow-500 transition">
                    Cek Permintaan
                </a>
            </div>
        @endif

        {{-- Kartu 2: Kelola Jadwal --}}
        @if ($role !== 'Kepsek')
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 flex flex-col justify-between">
                <div>
                    <h3 class="text-base font-semibold text-slate-800">Kelola Jadwal Lab</h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Tambah, edit, atau hapus jadwal semesteran.
                    </p>
                </div>
                <a href="{{ route('admin.jadwal.index') }}"
                   class="mt-4 inline-block text-center text-xs font-semibold bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 transition">
                    Lihat Jadwal
                </a>
            </div>
        @endif

        {{-- Kartu 3: Pembagian Sesi / Mapping --}}
        @if ($role !== 'Kepsek')
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 flex flex-col justify-between">
                <div>
                    <h3 class="text-base font-semibold text-slate-800">Pembagian Sesi & Kuota</h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Tentukan siswa mana yang terdaftar di sesi/jadwal tertentu.
                    </p>
                </div>
                <a href="{{ route('admin.sesi.index') }}"
                   class="mt-4 inline-block text-center text-xs font-semibold bg-indigo-700 text-white px-3 py-2 rounded-lg hover:bg-indigo-800 transition">
                    Atur Peserta
                </a>
            </div>
        @endif

        {{-- Kartu 4: Koreksi Kehadiran --}}
        @if ($role !== 'Kepsek')
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 flex flex-col justify-between">
                <div>
                    <h3 class="text-base font-semibold text-slate-800">Koreksi Kehadiran</h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Validasi akhir (Hadir/Sakit/Izin).
                    </p>
                </div>
                <a href="{{ route('admin.koreksi.index') }}"
                   class="mt-4 inline-block text-center text-xs font-semibold bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-700 transition">
                    Koreksi Sekarang
                </a>
            </div>
        @endif

        {{-- Kartu 5: Rekap Laporan --}}
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 flex flex-col justify-between">
            <div>
                <h3 class="text-base font-semibold text-slate-800">Rekap Absensi</h3>
                <p class="text-xs text-slate-500 mt-1">
                    Lihat data kehadiran per minggu/bulan.
                </p>
            </div>
            <a href="{{ route('admin.laporan.index') }}"
               class="mt-4 inline-block text-center text-xs font-semibold bg-blue-900 text-white px-3 py-2 rounded-lg hover:bg-blue-800 transition">
                Lihat Laporan
            </a>
        </div>

        {{-- Kartu 6: Manajemen Akun Staf (Hanya Admin) --}}
        @if ($role === 'Admin')
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 flex flex-col justify-between">
                <div>
                    <h3 class="text-base font-semibold text-slate-800">Manajemen Akun Staf</h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Buat akun Guru, Aslab, atau Kepsek baru.
                    </p>
                </div>
                <a href="{{ route('admin.users.index') }}"
                   class="mt-4 inline-block text-center text-xs font-semibold bg-slate-700 text-white px-3 py-2 rounded-lg hover:bg-slate-800 transition">
                    Kelola Akun Staf
                </a>
            </div>
        @endif

    </div>

    {{-- STATUS SISTEM --}}
    <h2 class="text-lg font-semibold text-slate-800 mb-2">
        Status Sistem
    </h2>

    @if(session('success'))
        <p class="text-sm font-semibold text-green-600 mt-1">
            ✅ {{ session('success') }}
        </p>
    @endif

    @if(session('error'))
        <p class="text-sm font-semibold text-red-600 mt-1">
            ❌ {{ session('error') }}
        </p>
    @endif
@endsection
