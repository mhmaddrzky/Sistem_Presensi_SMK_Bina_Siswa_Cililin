@extends('layouts.admin')

@section('content')

    {{-- 🛑 STYLE GLOBAL: Tambahkan style untuk form container dan error box --}}
    <style>
        .form-card {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .error-box {
            background-color: #ffeaea;
            border: 1px solid #ff0000;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 8px;
        }
        /* Style untuk input dan select agar tampil standar */
        .form-control-custom, .form-select-custom {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }
    </style>

    <h1 style="color: #1f3a93; padding-bottom: 10px; border-bottom: 2px solid #eee;">
        Tambah Jadwal Baru
    </h1>

    {{-- 🛑 ERROR VALIDATION --}}
    @if ($errors->any())
        <div class="error-box">
            <p style="color: red; font-weight: bold; margin-bottom: 5px;">⚠️ Gagal menyimpan jadwal. Mohon periksa kesalahan di bawah ini:</p>
            <ul style="list-style-type: disc; margin-left: 20px; color: red;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    {{-- Container Form Utama --}}
    <div class="form-card">
        <form action="{{ route('admin.jadwal.store') }}" method="POST">
            @csrf

            {{-- 🛑 STRUKTUR UTAMA: 2 KOLOM --}}
            <div class="row">
                
                {{-- KOLOM KIRI: Informasi Dasar --}}
                <div class="col-md-6">
                    <h3 style="color: #333; border-bottom: 1px dashed #ddd; padding-bottom: 5px;">Informasi Dasar</h3>

                    {{-- INPUT: Mata Pelajaran --}}
                    <div class="form-group mb-3">
                        <label for="mata_pelajaran" class="form-label font-weight-bold">Mata Pelajaran:</label>
                        <input type="text" id="mata_pelajaran" name="mata_pelajaran" value="{{ old('mata_pelajaran') }}" required class="form-control-custom">
                        @error('mata_pelajaran') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    {{-- INPUT: Nama Guru --}}
                    <div class="form-group mb-3">
                        <label for="nama_guru" class="form-label font-weight-bold">Nama Guru:</label>
                        <input type="text" id="nama_guru" name="nama_guru" value="{{ old('nama_guru') }}" required class="form-control-custom">
                        @error('nama_guru') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    {{-- SELECT: Jurusan --}}
                    <div class="form-group mb-3">
                        <label for="jurusan" class="form-label font-weight-bold">Jurusan:</label>
                        <select id="jurusan" name="jurusan" required class="form-select-custom">
                            <option value="">-- Pilih Jurusan --</option>
                            <option value="TKJ" {{ old('jurusan') == 'TKJ' ? 'selected' : '' }}>TKJ</option>
                            <option value="TBSM" {{ old('jurusan') == 'TBSM' ? 'selected' : '' }}>TBSM</option>
                        </select>
                        @error('jurusan') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    {{-- INPUT: Laboratorium --}}
                    <div class="form-group mb-3">
                        <label for="ruang_lab" class="form-label font-weight-bold">Laboratorium:</label>
                        <input type="text" id="ruang_lab" name="ruang_lab" value="{{ old('ruang_lab') }}" required class="form-control-custom">
                        @error('ruang_lab') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- KOLOM KANAN: Detail Sesi & Waktu --}}
                <div class="col-md-6">
                    <h3 style="color: #333; border-bottom: 1px dashed #ddd; padding-bottom: 5px;">Detail Sesi & Waktu</h3>

                    {{-- SELECT: HARI --}}
                    <div class="form-group mb-3">
                        <label for="hari" class="form-label font-weight-bold">Hari:</label>
                        <select id="hari" name="hari" required class="form-select-custom">
                            <option value="">-- Pilih Hari --</option>
                            <option value="Senin" {{ old('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                            <option value="Selasa" {{ old('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                            <option value="Rabu" {{ old('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                            <option value="Kamis" {{ old('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                            <option value="Jumat" {{ old('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                            <option value="Sabtu" {{ old('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                            <option value="Minggu" {{ old('hari') == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                        </select>
                        @error('hari') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    {{-- INPUT: WAKTU MULAI & SELESAI (dalam 1 baris menggunakan flexbox) --}}
                    <div class="row">
                        <div class="col-md-6">
                             <div class="form-group mb-3">
                                <label for="waktu_mulai" class="form-label font-weight-bold">Waktu Mulai:</label>
                                <input type="time" id="waktu_mulai" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required class="form-control-custom">
                                @error('waktu_mulai') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="waktu_selesai" class="form-label font-weight-bold">Waktu Selesai:</label>
                                <input type="time" id="waktu_selesai" name="waktu_selesai" value="{{ old('waktu_selesai') }}" required class="form-control-custom">
                                @error('waktu_selesai') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                   
                    {{-- INPUT: KAPASITAS --}}
                    <div class="form-group mb-3">
                        <label for="kapasitas" class="form-label font-weight-bold">Kapasitas Siswa (Maksimal 20):</label>
                        <input type="number" id="kapasitas" name="kapasitas" value="{{ old('kapasitas', 20) }}" min="1" required class="form-control-custom">
                        @error('kapasitas') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    {{-- INPUT: DESKRIPSI SESI --}}
                    <div class="form-group mb-3">
                        <label for="sesi" class="form-label font-weight-bold">Deskripsi Sesi (Contoh: Pagi/Siang):</label>
                        <input type="text" id="sesi" name="sesi" value="{{ old('sesi') }}" class="form-control-custom" placeholder="Opsional">
                        @error('sesi') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div> {{-- Akhir Row --}}
            
            <hr style="margin: 30px 0;">

            {{-- Tombol Submit --}}
            <button type="submit" 
                    style="padding: 12px 25px; 
                           background: #1f3a93; 
                           color: white; 
                           border: none; 
                           border-radius: 8px; /* Sudut Melengkung */
                           cursor: pointer;
                           font-weight: bold;
                           box-shadow: 0 4px 6px rgba(31, 58, 147, 0.3);">
                Simpan Jadwal Berulang
            </button>
        </form>
    </div>
@endsection