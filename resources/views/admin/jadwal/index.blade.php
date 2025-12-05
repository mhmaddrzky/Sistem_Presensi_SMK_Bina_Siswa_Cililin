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

        /* Button Tambah */
        .add-button {
            background: #28a745;
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            font-weight: 600;
            transition: background 0.3s;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
        }
        .add-button:hover {
            background: #218838;
            text-decoration: none;
        }
        .add-button i {
            margin-right: 8px;
        }

        /* Table Styling */
        .data-table-container {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 25px;
        }
        .table-jadwal {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        .table-jadwal thead th {
            background-color: #1f3a93;
            color: white;
            padding: 14px 15px;
            text-align: left;
            font-weight: 700;
        }
        .table-jadwal tbody td {
            padding: 12px 15px;
            border-top: 1px solid #dee2e6;
        }
        .table-jadwal tbody tr:nth-child(even) {
            background-color: #f8faff; /* Light blue zebra stripping */
        }
        .table-jadwal tbody tr:hover {
            background-color: #e9f5ff; /* Highlight pada hover */
        }

        /* Action Buttons Styling */
        .action-link {
            text-decoration: none;
            color: #1f3a93; /* Warna biru untuk Edit */
            font-weight: 500;
            margin-right: 10px;
        }
        .delete-button {
            background: none;
            border: none;
            padding: 0;
            color: #dc3545; /* Warna merah untuk Hapus */
            cursor: pointer;
            font-weight: 500;
        }
        .no-data-alert {
            background: #f8f9fa; 
            padding: 25px; 
            border-radius: 8px; 
            text-align: center; 
            border: 1px dashed #ced4da;
            color: #6c757d;
            font-weight: 500;
            margin-top: 20px;
        }
    </style>

    @php
        $userRole = Auth::user()->role;
        // Definisi izin untuk CRUD Jadwal
        $allowedToManage = in_array($userRole, ['Admin', 'Guru', 'AsistenLab']);
    @endphp

    <div class="page-header">
        <h1>🗓️ Kelola Jadwal Laboratorium</h1>
        @if ($allowedToManage)
            <a href="{{ route('admin.jadwal.create') }}" class="add-button"> 
                <i class="fas fa-plus-circle"></i> Tambah Jadwal Baru
            </a>
        @endif
    </div>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div style="background-color: #e6ffe6; border: 1px solid #00cc00; padding: 12px; margin-bottom: 20px; border-radius: 6px;">
            <p style="color: green; font-weight: bold; margin: 0;">✅ {{ session('success') }}</p>
        </div>
    @endif
    
    {{-- 2. Tampilan Data dalam Struktur Tabel --}}
    @if($jadwals->isNotEmpty())
        <div class="data-table-container">
            <table class="table-jadwal">
                <thead>
                    <tr>
                        <th style="width: 10%;">Hari</th>
                        <th style="width: 15%;">Waktu Sesi</th>
                        <th style="width: 25%;">Mata Pelajaran</th>
                        <th style="width: 15%;">Guru</th>
                        <th style="width: 10%;">Ruang Lab</th>
                        <th style="width: 15%;">Dibuat Oleh</th>
                        <th style="width: 10%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwals as $jadwal)
                        <tr>
                            <td>{{ $jadwal->hari }}</td>
                            <td>{{ substr($jadwal->waktu_mulai, 0, 5) }} - {{ substr($jadwal->waktu_selesai, 0, 5) }}</td>
                            <td>{{ $jadwal->mata_pelajaran }}</td>
                            <td>{{ $jadwal->nama_guru }}</td>
                            <td>{{ $jadwal->ruang_lab }}</td>
                            <td>{{ $jadwal->admin->nama ?? 'N/A' }}</td>
                            
                            {{-- Kolom Aksi --}}
                            <td style="white-space: nowrap; text-align: center;">
                                @if ($allowedToManage) 
                                    {{-- Link Edit --}}
                                    <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}" class="action-link" title="Edit Jadwal">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    
                                    {{-- Separator --}}
                                    <span style="color: #ccc;">|</span>
                                    
                                    {{-- Form Hapus --}}
                                    <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" style="display:inline-block; margin-left: 10px;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                            onclick="return confirm('Yakin ingin menghapus jadwal ini? Tindakan tidak dapat dibatalkan.')"
                                            class="delete-button" title="Hapus Jadwal">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </form>
                                @endif 
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        {{-- Pesan "Belum ada jadwal" dalam Alert Box Modern --}}
        <div class="no-data-alert">
            <p style="margin: 0;">⏳ Belum ada jadwal laboratorium yang tersedia saat ini.</p>
        </div>
    @endif

@endsection