@extends('layouts.admin')

@section('content')
    <h1>Edit Jadwal Laboratorium</h1>
    
    {{-- Arahkan Form ke Route UPDATE dengan ID yang benar --}}
    <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- WAJIB: Metode HTTP untuk UPDATE --}}

        {{-- Input Tanggal --}}
        <div style="margin-bottom: 15px;">
            <label for="tanggal">Tanggal:</label>
            <input type="date" name="tanggal" value="{{ old('tanggal', $jadwal->tanggal) }}" required>
        </div>

        {{-- Input Sesi --}}
        <div style="margin-bottom: 15px;">
            <label for="sesi">Sesi:</label>
            <input type="text" name="sesi" value="{{ old('sesi', $jadwal->sesi) }}" required>
        </div>
        
        {{-- Input Ruang Lab --}}
        <div style="margin-bottom: 15px;">
            <label for="ruang_lab">Ruang Lab:</label>
            <input type="text" name="ruang_lab" value="{{ old('ruang_lab', $jadwal->ruang_lab) }}" required>
        </div>

        <button type="submit">Perbarui Jadwal</button>
    </form>
    
    <a href="{{ route('admin.jadwal.index') }}" style="display: block; margin-top: 20px;">← Kembali</a>
@endsection