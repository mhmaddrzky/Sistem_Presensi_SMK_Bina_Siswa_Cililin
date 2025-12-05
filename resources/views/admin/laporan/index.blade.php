@extends('layouts.admin')

@section('content')

    {{-- STYLE BLOK: CSS Kustom untuk Tampilan Modern dan Clean --}}
    <style>
        /* Typography & Layout */
        .page-header {
            color: #1f3a93;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 15px;
            margin-bottom: 35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
        }

        /* Filter Card */
        .filter-card {
            background-color: #ffffff;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0e0e0;
            margin-bottom: 30px;
            display: flex;
            gap: 25px;
            align-items: flex-end;
        }
        .filter-group {
            flex-grow: 1; /* Agar field bisa mengisi ruang */
        }
        .filter-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        .filter-group select {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #ced4da;
            background-color: #f8f9fa; /* Warna latar belakang select */
            cursor: pointer;
            transition: border-color 0.3s;
        }
        .filter-group select:focus {
            border-color: #1f3a93;
            box-shadow: 0 0 0 0.2rem rgba(31, 58, 147, 0.25);
            outline: none;
        }
        
        /* Table Styling */
        .data-table-container {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 20px;
        }
        .table-laporan {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        .table-laporan thead th {
            background-color: #1f3a93;
            color: white;
            padding: 14px 15px;
            text-align: left;
            font-weight: 700;
        }
        .table-laporan tbody td {
            padding: 12px 15px;
            border-top: 1px solid #dee2e6;
        }
        .table-laporan tbody tr:nth-child(even) {
            background-color: #f8faff; /* Light blue zebra stripping */
        }
        .table-laporan tbody tr:hover {
            background-color: #e9f5ff; /* Highlight pada hover */
        }
        .total-hadir {
            font-size: 15px;
            font-weight: 700;
            text-align: center;
        }

        /* Export Button */
        .export-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s, box-shadow 0.3s;
            box-shadow: 0 3px 8px rgba(40, 167, 69, 0.3);
            margin-top: 20px;
        }
        .export-btn:hover {
            background: #218838;
            box-shadow: 0 5px 10px rgba(40, 167, 69, 0.4);
        }
        .no-data-alert {
            background: #f8f9fa; 
            padding: 25px; 
            border-radius: 8px; 
            text-align: center; 
            border: 1px dashed #ced4da;
            color: #6c757d;
            font-weight: 500;
        }
    </style>

    <div class="page-header">
        <h1>📊 Rekap Laporan Presensi</h1>
    </div>

    {{-- Notifikasi Sukses/Gagal --}}
    @if(session('success'))
        <div style="background-color: #e6ffe6; border: 1px solid #00cc00; padding: 12px; margin-bottom: 20px; border-radius: 6px;">
            <p style="color: green; font-weight: bold; margin: 0;">✅ {{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div style="background-color: #ffe6e6; border: 1px solid #cc0000; padding: 12px; margin-bottom: 20px; border-radius: 6px;">
            <p style="color: red; font-weight: bold; margin: 0;">❌ {{ session('error') }}</p>
        </div>
    @endif

    {{-- 🛑 FORM FILTER (CARD UTAMA) 🛑 --}}
    <form action="{{ route('admin.laporan.index') }}" method="GET">
        <div class="filter-card">
            
            {{-- BLOK 1: FILTER JURUSAN --}}
            <div class="filter-group">
                <label for="jurusan_filter">Filter Berdasarkan Jurusan:</label>
                <select name="jurusan_filter" id="jurusan_filter" onchange="this.form.submit()">
                    <option value="all" {{ $jurusanFilter == 'all' ? 'selected' : '' }}>Semua Jurusan</option>
                    <option value="TKJ" {{ $jurusanFilter == 'TKJ' ? 'selected' : '' }}>TKJ</option>
                    <option value="TBSM" {{ $jurusanFilter == 'TBSM' ? 'selected' : '' }}>TBSM</option>
                </select>
            </div>
            
            {{-- BLOK 2: FILTER PERIODE --}}
            <div class="filter-group">
                <label for="periode">Pilih Periode Laporan:</label>
                <select name="periode" id="periode" onchange="this.form.submit()">
                    <option value="mingguan" {{ $periode == 'mingguan' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="bulanan" {{ $periode == 'bulanan' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="keseluruhan" {{ $periode == 'keseluruhan' ? 'selected' : '' }}>Keseluruhan</option>
                </select>
            </div>

            {{-- BLOK 3: STATUS/INFO PERIODE AKTIF --}}
            <div style="flex-grow: 1; align-self: flex-start; padding-top: 8px;">
                <p style="font-weight: 600; color: #1f3a93; margin-bottom: 5px;">Periode Aktif:</p>
                <span style="font-size: 14px; color: #555;">
                    @if ($periode == 'mingguan') Minggu Ini (Senin - Minggu)
                    @elseif ($periode == 'bulanan') Bulan Ini
                    @else Keseluruhan Data
                    @endif
                    @if ($jurusanFilter !== 'all')
                        <br>Jurusan: **{{ $jurusanFilter }}**
                    @endif
                </span>
            </div>

        </div>
    </form>
    
    <p style="margin-bottom: 25px; color: #555; font-style: italic;">
        Laporan ini menampilkan **Total Sesi Hadir** siswa sesuai filter yang dipilih.
    </p>
    
    {{-- 2. TABEL LAPORAN --}}
    @if ($dataLaporan->isNotEmpty())
    
        <h2 style="color: #333; font-size: 20px; margin-bottom: 10px;">Daftar Siswa: <span style="color: #1f3a93; font-weight: 700;">{{ $dataLaporan->count() }}</span> Siswa</h2>

        <div class="data-table-container">
            <table class="table-laporan">
                <thead>
                    <tr>
                        <th style="width: 10%;">NIS</th>
                        <th style="width: 35%;">Nama Siswa</th>
                        <th style="width: 15%;">Kelas</th>
                        <th style="width: 15%;">Jurusan</th>
                        <th class="total-hadir" style="width: 25%; text-align: center;">Total Sesi Hadir (Maks: {{ $totalSesiSemester ?? 16 }})</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dataLaporan as $data) 
                        <tr>
                            <td>{{ $data['nis'] }}</td>
                            <td>{{ $data['nama'] }}</td>
                            <td>{{ $data['kelas'] }}</td>
                            <td>{{ $data['jurusan'] }}</td>
                            <td class="total-hadir">
                                <span style="display: inline-block; padding: 4px 10px; border-radius: 4px; background: #e0f7fa; color: #007bb6;">
                                    {{ $data['total_kehadiran_format'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- FORM EXPORT --}}
        <form action="{{ route('admin.laporan.export') }}" method="POST">
            @csrf
            <input type="hidden" name="periode" value="{{ $periode }}">
            <input type="hidden" name="jurusan_filter" value="{{ $jurusanFilter }}">
            <button type="submit" class="export-btn">
                <i class="fas fa-file-csv"></i> Export Data ke CSV
            </button>
        </form>

    @else
        <div class="no-data-alert">
            <p style="margin: 0;">🚫 Tidak ada data presensi yang ditemukan untuk kriteria filter ini.</p>
        </div>
    @endif
@endsection