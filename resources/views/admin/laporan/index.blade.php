@extends('layouts.admin')

@section('content')
    <h1 style="color: #1f3a93;">Rekap Laporan Presensi</h1>

    {{-- Notifikasi Sukses/Gagal --}}
    @if(session('success'))
        <p style="color: green; font-weight: bold;">✅ {{ session('success') }}</p>
    @endif
    @if(session('error'))
        <p style="color: red; font-weight: bold;">❌ {{ session('error') }}</p>
    @endif

    {{-- 🛑 FORM UTAMA FILTER (MENGGABUNGKAN JURUSAN DAN PERIODE) 🛑 --}}
    <form action="{{ route('admin.laporan.index') }}" method="GET" style="margin-bottom: 20px;">
        <div style="border: 1px solid #ccc; padding: 15px; border-radius: 8px; background: #f9f9f9; display: flex; gap: 25px; align-items: flex-end;">
            
            {{-- BLOK 1: FILTER JURUSAN --}}
            <div>
                <label for="jurusan_filter" style="display: block; margin-bottom: 5px; font-weight: bold;">Filter Berdasarkan Jurusan:</label>
                <select name="jurusan_filter" id="jurusan_filter" onchange="this.form.submit()" style="padding: 8px; border-radius: 4px; border: 1px solid #ddd;">
                    <option value="all" {{ $jurusanFilter == 'all' ? 'selected' : '' }}>Semua Jurusan</option>
                    <option value="TKJ" {{ $jurusanFilter == 'TKJ' ? 'selected' : '' }}>TKJ</option>
                    <option value="TBSM" {{ $jurusanFilter == 'TBSM' ? 'selected' : '' }}>TBSM</option>
                </select>
            </div>
            
            {{-- BLOK 2: FILTER PERIODE --}}
            <div>
                <label for="periode" style="display: block; margin-bottom: 5px; font-weight: bold;">Pilih Periode Laporan:</label>
                <select name="periode" id="periode" onchange="this.form.submit()" style="padding: 8px; border-radius: 4px; border: 1px solid #ddd;">
                    <option value="mingguan" {{ $periode == 'mingguan' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="bulanan" {{ $periode == 'bulanan' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="keseluruhan" {{ $periode == 'keseluruhan' ? 'selected' : '' }}>Keseluruhan</option>
                </select>
            </div>

            <small style="color: #1f3a93; padding-bottom: 5px;">
                @if ($jurusanFilter !== 'all')
                    Hanya data **{{ $jurusanFilter }}** yang diproses.
                @endif
            </small>

        </div>
    </form>
    
    <p>Data ditampilkan untuk periode: <strong>
        @if ($periode == 'mingguan') Minggu Ini (Senin - Minggu)
        @elseif ($periode == 'bulanan') Bulan Ini
        @else Keseluruhan
        @endif
    </strong>. Angka menunjukkan **Total Sesi Hadir**.</p>
    
    <hr>
    
    {{-- 2. TABEL LAPORAN (Hanya tampil jika data ada) --}}
    @if ($dataLaporan->isNotEmpty())
    
        <h2 style="color: #333;">Data Presensi: {{ $dataLaporan->count() }} Siswa</h2>

        <table border="1" style="width: 100%; margin-top: 10px; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #1f3a93; color: white;">
                    <th style="padding: 10px;">NIS</th>
                    <th style="padding: 10px;">Nama Siswa</th>
                    <th style="padding: 10px;">Kelas</th>
                    <th style="padding: 10px;">Jurusan</th>
                    <th style="padding: 10px;">Total Sesi Hadir ({{ $totalSesiSemester ?? 16 }} Pertemuan)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dataLaporan as $data) 
                    <tr>
                        <td style="padding: 8px;">{{ $data['nis'] }}</td>
                        <td style="padding: 8px;">{{ $data['nama'] }}</td>
                        <td style="padding: 8px;">{{ $data['kelas'] }}</td>
                        <td style="padding: 8px;">{{ $data['jurusan'] }}</td>
                        {{-- Menampilkan format fraksi (Contoh: 2/16) --}}
                        <td style="padding: 8px; font-weight: bold;">{{ $data['total_kehadiran_format'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- FORM EXPORT --}}
        <form action="{{ route('admin.laporan.export') }}" method="POST" style="margin-top: 20px;">
            @csrf
            <input type="hidden" name="periode" value="{{ $periode }}">
            <input type="hidden" name="jurusan_filter" value="{{ $jurusanFilter }}">
            <button type="submit" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Export Laporan (CSV)
            </button>
        </form>

    @else
        <div style="background: #e9ecef; padding: 20px; border-radius: 5px; text-align: center;">
            <p>Tidak ada data presensi yang ditemukan untuk periode ini. Mohon pastikan filter Anda benar.</p>
        </div>
    @endif
@endsection