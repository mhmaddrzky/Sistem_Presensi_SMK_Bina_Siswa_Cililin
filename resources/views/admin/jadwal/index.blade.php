@extends('layouts.admin')

@section('content')
    {{-- Ambil Role User di View --}}
    @php
        $userRole = Auth::user()->role;
        // 🛑 FIX UTAMA: Definisi izin untuk CRUD Jadwal
        $allowedToManage = in_array($userRole, ['Admin', 'Guru', 'AsistenLab']); 
    @endphp

    <h1>Kelola Jadwal Laboratorium</h1>
    
    {{-- 🛑 Tombol Tambah Jadwal --}}
    @if ($allowedToManage)
        <a href="{{ route('admin.jadwal.create') }}" style="padding: 10px; background: green; color: white; text-decoration: none;">+ Tambah Jadwal Baru</a>
    @endif

    <hr>
    
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <table border="1" style="width: 100%; margin-top: 20px;">
        <thead>
            <tr>
                <th>Hari</th> 
                <th>Waktu Sesi</th> 
                <th>Mata Pelajaran</th> 
                <th>Guru</th> 
                <th>Ruang Lab</th>
                <th>Dibuat Oleh</th>
                <th>Aksi</th>
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
                    <td>
                        {{-- Tautan Lihat Detail SELALU ADA --}}
                        
                        {{-- 🛑 Tombol Edit dan Hapus hanya tampil jika ada izin manage --}}
                        @if ($allowedToManage)
                            | <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}">Edit</a>
                            
                            <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus jadwal ini?')">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Belum ada jadwal yang tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection