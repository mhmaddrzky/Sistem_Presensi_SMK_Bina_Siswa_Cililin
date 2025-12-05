@extends('layouts.admin')

@section('content')


{{-- Judul --}}
    <h1 class="text-2xl font-bold text-gray-800">Koreksi Kehadiran</h1>
    <p class="text-gray-500 mb-6">Ubah status Alfa menjadi Sakit atau Izin.</p>
   
    @if(session('success'))
        <p style="color: green;">✅ {{ session('success') }}</p>
    @endif
    @if(session('error'))
        <p style="color: red;">❌ {{ session('error') }}</p>
    @endif

    {{-- Form Filter Jadwal --}}
    <form action="{{ route('admin.koreksi.index') }}" method="GET" style="margin-bottom: 20px;">
        <div style="border: 1px solid #ccc; padding: 15px; border-radius: 8px; background: #f9f9f9;">
            <label for="jadwal_id" style="font-weight: bold;">Pilih Jadwal yang Akan Dikoreksi:</label>
         <select name="jadwal_id" id="jadwal_id" required onchange="this.form.submit()" style="padding: 8px; border-radius: 4px; border: 1px solid #ddd; width: 100%; max-width: 500px;">
    <option value="">-- Pilih Jadwal --</option>
    @foreach($jadwals as $jadwal)
        <option
            value="{{ $jadwal->id }}"
            {{ request('jadwal_id') == $jadwal->id ? 'selected' : '' }}
        >
            {{-- 🛑 FIX FINAL: Hanya tampilkan Jurusan, Hari, Waktu, dan Ruang Lab --}}
            [{{ $jadwal->jurusan }}] {{ $jadwal->hari }}, {{ substr($jadwal->waktu_mulai, 0, 5) }} - {{ substr($jadwal->waktu_selesai, 0, 5) }} | Ruang: {{ $jadwal->ruang_lab }}
        </option>
    @endforeach
</select>
        </div>
    </form>
   
    @if (isset($jadwalTerpilih))
    <h2 style="margin-top: 30px;">Daftar Peserta Sesi: {{ $jadwalTerpilih->hari }}</h2>
   
    <form action="{{ route('admin.koreksi.store') }}" method="POST">
        @csrf
        <input type="hidden" name="jadwal_id" value="{{ $jadwalTerpilih->id }}">

        <table border="1" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th style="padding: 10px;">NIS (ID)</th> {{-- Menampilkan siswa_id/nis --}}
                    <th style="padding: 10px;">Nama</th>
                    <th style="padding: 10px;">Kelas</th>
                    <th style="padding: 10px;">Status Otomatis</th>
                    <th style="padding: 10px;">Koreksi Menjadi</th>
                </tr>
            </thead>
        <tbody>
               
                @forelse ($rekapKoreksi as $i => $koreksi)
                    <tr>
                        {{-- 1. Kolom NIS --}}
                        <td style="padding: 8px;">{{ $koreksi['siswa_id'] }}</td>
                       
                        {{-- 2. Kolom Nama Siswa --}}
                        <td style="padding: 8px;">{{ $koreksi['nama'] }}</td>
                       
                        {{-- 3. Kolom Kelas --}}
                        <td style="padding: 8px;">{{ $koreksi['kelas'] }}</td>

                        {{-- 4. Kolom Status Otomatis --}}
                        <td style="padding: 8px; font-weight: bold; color: {{ ($koreksi['status_otomatis'] == 'Hadir') ? 'green' : 'red' }};">
                            {{ $koreksi['status_otomatis'] }}
                        </td>
                       
                        {{-- 5. Kolom Dropdown Koreksi --}}
                        <td style="padding: 8px;">
                            <input type="hidden" name="koreksi[{{ $i }}][siswa_id]" value="{{ $koreksi['siswa_id'] }}">
                           
                            <select name="koreksi[{{ $i }}][status]" style="padding: 5px;" required>
                                {{-- Pilih yang sesuai dengan status awal, atau default ke Alfa --}}
                                <option value="Hadir" {{ $koreksi['status_otomatis'] == 'Hadir' ? 'selected' : '' }}>Hadir (Otomatis/Koreksi)</option>
                                <option value="Alfa" {{ $koreksi['status_otomatis'] == 'Alfa' ? 'selected' : '' }}>Alfa (Tanpa Keterangan)</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Izin">Izin</option>
                            </select>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #888; padding: 15px;">
                            Tidak ada siswa yang terdaftar di sesi ini, atau belum ada presensi yang tercatat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
       
        <button type="submit" style="margin-top: 20px; padding: 10px 20px; background: #007bff; color: white;">Simpan Koreksi Final</button>
    </form>

@else
    {{-- Jika belum memilih jadwal --}}
    <p>Silakan pilih jadwal yang akan dikoreksi dari *dropdown* di atas.</p>
@endif
@endsection
