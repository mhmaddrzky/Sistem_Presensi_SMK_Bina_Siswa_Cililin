@extends('layouts.admin')

@section('content')
    <h1>Buat Akun Staf Baru</h1>
    
    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif
    
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        
        <h3>Detail Pengguna</h3>
        
        <div style="margin-bottom: 15px;">
            <label for="role">Role/Jabatan:</label>
            <select name="role" id="role" required>
                <option value="">-- Pilih Jabatan --</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>{{ $role }}</option>
                @endforeach
            </select>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="nama">Nama Lengkap:</label>
            <input type="text" name="nama" value="{{ old('nama') }}" required>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="id_pengelola">ID Pengelola (NIP/ID Unik):</label>
            <input type="text" name="id_pengelola" value="{{ old('id_pengelola') }}" required>
            <small>Contoh: G001 atau NIP Guru.</small>
        </div>

        <h3>Kredensial Login</h3>

        <div style="margin-bottom: 15px;">
            <label for="username">Username:</label>
            <input type="text" name="username" value="{{ old('username') }}" required>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="password">Password:</label>
            <input type="password" name="password" required>
        </div>
        
        <button type="submit" style="padding: 10px 20px;">Buat Akun Staf</button>
    </form>
@endsection