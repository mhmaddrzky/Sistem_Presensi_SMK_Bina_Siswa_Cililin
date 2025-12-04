@extends('layouts.admin')

@section('content')
    <h1 style="color: #1f3a93;">Tambah Jadwal Baru</h1>

    @if ($errors->any())
    <div style="color: red; border: 1px solid red; padding: 10px; margin-top: 20px;">
        <p>⚠️ Gagal menyimpan jadwal. Mohon periksa kesalahan di bawah ini:</p>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <form action="{{ route('admin.jadwal.store') }}" method="POST" style="margin-top: 20px;">
        @csrf

        <h3 style="margin-top: 20px; color: #333;">Informasi Dasar</h3>

        {{-- INPUT: Mata Pelajaran --}}
        <div style="margin-bottom: 15px;">
            <label for="mata_pelajaran">Mata Pelajaran:</label>
            <input type="text" id="mata_pelajaran" name="mata_pelajaran" value="{{ old('mata_pelajaran') }}" required>
            @error('mata_pelajaran') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        {{-- INPUT: Nama Guru --}}
        <div style="margin-bottom: 15px;">
            <label for="nama_guru">Nama Guru:</label>
            <input type="text" id="nama_guru" name="nama_guru" value="{{ old('nama_guru') }}" required>
            @error('nama_guru') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="jurusan">Jurusan:</label>
            <select id="jurusan" name="jurusan" required>
                <option value="">-- Pilih Jurusan --</option>
                <option value="TKJ" {{ old('jurusan') == 'TKJ' ? 'selected' : '' }}>TKJ</option>
                <option value="TBSM" {{ old('jurusan') == 'TBSM' ? 'selected' : '' }}>TBSM</option>
            </select>
            @error('jurusan') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        {{-- INPUT: Laboratorium --}}
        <div style="margin-bottom: 15px;">
            <label for="ruang_lab">Laboratorium:</label>
            <input type="text" id="ruang_lab" name="ruang_lab" value="{{ old('ruang_lab') }}" required>
            @error('ruang_lab') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        <hr style="margin: 25px 0;">

        <h3 style="color: #333;">Detail Sesi & Pengulangan (Semester)</h3>
        
        {{-- 🛑 INPUT WAJIB: HARI --}}
        <div style="margin-bottom: 15px;">
            <label for="hari">Hari:</label>
            <select id="hari" name="hari" required>
                <option value="">-- Pilih Hari --</option>
                <option value="Senin" {{ old('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                <option value="Selasa" {{ old('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                <option value="Rabu" {{ old('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                <option value="Kamis" {{ old('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                <option value="Jumat" {{ old('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                <option value="Sabtu" {{ old('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                <option value="Minggu" {{ old('hari') == 'Minggu' ? 'selected' : '' }}>Minggu</option>
            </select>
            @error('hari') <span style="color: red;">{{ $message }}</span> @enderror
        </div>


        {{-- WAKTU MULAI & SELESAI --}}
        <div style="margin-bottom: 15px;">
            <label for="waktu_mulai">Waktu Mulai:</label>
            <input type="time" id="waktu_mulai" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required>
            @error('waktu_mulai') <span style="color: red;">{{ $message }}</span> @enderror
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="waktu_selesai">Waktu Selesai:</label>
            <input type="time" id="waktu_selesai" name="waktu_selesai" value="{{ old('waktu_selesai') }}" required>
            @error('waktu_selesai') <span style="color: red;">{{ $message }}</span >@enderror
        </div>

        {{-- KAPASITAS & DESKRIPSI SESI --}}
        <div style="margin-bottom: 15px;">
            <label for="kapasitas">Kapasitas Siswa (Maksimal 20):</label>
            <input type="number" id="kapasitas" name="kapasitas" value="{{ old('kapasitas', 20) }}" min="1" required>
            @error('kapasitas') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="sesi">Deskripsi Sesi (Contoh: Pagi):</label>
            <input type="text" id="sesi" name="sesi" value="{{ old('sesi') }}">
            @error('sesi') <span style="color: red;">{{ $message }}</span> @enderror
        </div>
        
        <button type="submit" style="padding: 10px 15px; background: #1f3a93; color: white; border: none; cursor: pointer;">Simpan Jadwal Berulang</button>
    </form>
@endsection