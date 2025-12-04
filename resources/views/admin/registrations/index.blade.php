@extends('layouts.admin') 

@section('content')

    <h1 style="color: #1f3a93;">Daftar Permintaan Registrasi Siswa (Pending)</h1>

    {{-- Notifikasi Sukses/Gagal --}}
    @if(session('success'))
        <p style="color: green; font-weight: bold;">✅ {{ session('success') }}</p>
    @endif
    @if(session('error'))
        <p style="color: red; font-weight: bold;">❌ {{ session('error') }}</p>
    @endif

    @if($registrations->isEmpty())
        <p>Tidak ada permintaan registrasi yang menunggu persetujuan.</p>
    @else
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>ID Reg</th>
                    <th>Nama Siswa</th>
                    <th>NIS</th>
                    <th>Kelas</th>
                    
                    {{-- 🛑 FIX: TAMBAH KOLOM JURUSAN 🛑 --}}
                    <th>Jurusan</th>
                    
                    <th>Tanggal Daftar</th>
                    <th>Username Diminta</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($registrations as $reg)
                    <tr>
                        <td>{{ $reg->id_reg }}</td>
                        <td>{{ $reg->siswa->nama }}</td>
                        <td>{{ $reg->siswa->nis }}</td>
                        <td>{{ $reg->siswa->kelas }}</td>
                        
                        {{-- 🛑 FIX: TAMPILKAN DATA JURUSAN 🛑 --}}
                        <td style="font-weight: bold; color: #1f3a93;">{{ $reg->siswa->jurusan }}</td>
                        
                        <td>{{ $reg->tanggal_reg }}</td>
                        
                        {{-- MENAMPILKAN USERNAME YANG DIMINTA SISWA --}}
                        <td>
                            <strong>{{ $reg->username_request }}</strong>
                        </td>
                        
                        <td>
                            {{-- 1. Form Persetujuan --}}
                            <form method="POST" action="{{ route('admin.registrations.approve', $reg->id_reg) }}" style="display:inline-block;">
                                @csrf
                                
                                <button type="submit" 
                                        onclick="return confirm('Setujui registrasi {{ $reg->siswa->nama }} ({{ $reg->siswa->jurusan }})? Akun dibuat dengan Username: {{ $reg->username_request }}.')">
                                    Setujui
                                </button>
                            </form>

                            {{-- 2. Form Penolakan --}}
                            <form method="POST" action="{{ route('admin.registrations.reject', $reg->id_reg) }}" style="display:inline-block;">
                                @csrf
                                <button type="submit" onclick="return confirm('Tolak dan hapus data registrasi {{ $reg->siswa->nama }}?')">Tolak</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

@endsection