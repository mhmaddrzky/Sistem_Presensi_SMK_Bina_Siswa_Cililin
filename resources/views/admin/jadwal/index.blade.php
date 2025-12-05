@extends('layouts.admin')

@section('content')

    @php
        $userRole = Auth::user()->role;
        // Definisi izin untuk CRUD Jadwal
        $allowedToManage = in_array($userRole, ['Admin', 'Guru', 'AsistenLab']);
    @endphp

    <h1>Kelola Jadwal Laboratorium</h1>

    {{-- 1. Tombol Tambah Jadwal (Dengan Sudut Melengkung) --}}
    @if ($allowedToManage)
        <a href="{{ route('admin.jadwal.create') }}" 
           style="padding: 10px; 
                  background: green; 
                  color: white; 
                  text-decoration: none; 
                  margin-bottom: 20px; 
                  display: inline-block;
                  border-radius: 8px; /* Sudut Melengkung */
                  box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);"> 
           + Tambah Jadwal Baru
        </a>
    @endif
    
    <hr>
    
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    {{-- 2. Tampilan Data dalam Struktur Tabel yang Benar --}}
    <table border="1" style="width: 100%; margin-top: 20px; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Hari</th>
                <th>Waktu Sesi</th>
                <th>Mata Pelajaran</th>
                <th>Guru</th>
                <th>Ruang Lab</th>
                <th>Dibuat Oleh</th>
                <th style="width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwals as $jadwal)
                <tr>
                    <td>{{ $jadwal->hari }}</td>
                    <td>{{ substr($jadwal->waktu_mulai, 0, 5) }} - {{ substr($jadwal->waktu_selesai, 0, 5) }}</td>
                    <td>{{ $jadwal->mata_pelajaran }}</td>
                    <td>{{ $jadwal->nama_guru }}</td>
                    <td>{{ $jadwal->ruang_lab }}</td>
                    <td>{{ $jadwal->admin->nama ?? 'N/A' }}</td>
                    
                    {{-- Kolom Aksi (Sudah dijamin tidak tumpang tindih) --}}
                    <td style="white-space: nowrap;">
                        @if ($allowedToManage) 
                            <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}">Edit</a>
                            
                            &nbsp;&nbsp;|&nbsp;&nbsp; 
                            
                            <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Yakin ingin menghapus jadwal ini?')"
                                        style="background: none; border: none; padding: 0; color: red; cursor: pointer;">
                                    Hapus
                                </button>
                            </form>
                        @endif 
                    </td>
                </tr>
            @empty
                {{-- 🛑 Pesan "Belum ada jadwal" diletakkan dalam baris tabel (<tr>) --}}
                <tr>
                    <td colspan="7" style="text-align: center; padding: 15px;">
                        Belum ada jadwal yang tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

@endsection