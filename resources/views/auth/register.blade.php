<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Siswa Laboratorium</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; }
        .container { max-width: 450px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="password"], select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button[type="submit"] { background: #1f3a93; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; width: 100%; margin-top: 15px; }
        .text-muted { font-size: 0.8em; color: #666; display: block; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 style="text-align: center; color: #1f3a93;">Pendaftaran Akun Siswa</h1>
        <p style="text-align: center; color: #555;">Silakan isi data diri Anda. Akun akan aktif setelah diverifikasi oleh Admin.</p>

        {{-- Notifikasi Sukses/Gagal --}}
        @if(session('success'))
            <p style="color: green; font-weight: bold; text-align: center;">✅ {{ session('success') }}</p>
        @endif
        @if(session('error'))
            <p style="color: red; font-weight: bold; text-align: center;">❌ {{ session('error') }}</p>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Nama Lengkap --}}
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required autofocus>
                @error('nama') <span style="color: red; font-size: 0.9em;">{{ $message }}</span> @enderror
            </div>

            {{-- Username --}}
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" required>
                @error('username') <span style="color: red; font-size: 0.9em;">{{ $message }}</span> @enderror
            </div>

            {{-- NIS --}}
            <div class="form-group">
                <label for="nis">NIS (Nomor Induk Siswa)</label>
                <input type="text" id="nis" name="nis" value="{{ old('nis') }}" required>
                <small class="text-muted">NIS akan digunakan sebagai **password** default.</small>
                @error('nis') <span style="color: red; font-size: 0.9em;">{{ $message }}</span> @enderror
            </div>

            {{-- Konfirmasi NIS (Wajib sesuai NIS) --}}
            <div class="form-group">
                <label for="nis_confirmation">Konfirmasi NIS</label>
                <input type="text" id="nis_confirmation" name="nis_confirmation" required>
                @error('nis_confirmation') <span style="color: red; font-size: 0.9em;">{{ $message }}</span> @enderror
            </div>

            {{-- Kelas --}}
            <div class="form-group">
                <label for="kelas">Kelas</label>
                <input type="text" id="kelas" name="kelas" value="{{ old('kelas') }}" required> 
                @error('kelas') <span style="color: red; font-size: 0.9em;">{{ $message }}</span> @enderror
            </div>

            {{-- Jurusan (Multi-Jurusan Fix) --}}
                    <div class="form-group">
            <label for="jurusan">Jurusan:</label>
            <select name="jurusan" id="jurusan" class="form-control" required>
                <option value="">-- Pilih Jurusan --</option>
                <option value="TKJ" {{ old('jurusan') == 'TKJ' ? 'selected' : '' }}>Teknik Komputer & Jaringan (TKJ)</option>
                <option value="TBSM" {{ old('jurusan') == 'TBSM' ? 'selected' : '' }}>Teknik Bisnis Sepeda Motor (TBSM)</option>
            </select>
            @error('jurusan')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            </div>
            
            <button type="submit">DAFTAR</button>
        </form>
        
        <p style="text-align: center; margin-top: 15px;">Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
    </div>
</body>
</html>