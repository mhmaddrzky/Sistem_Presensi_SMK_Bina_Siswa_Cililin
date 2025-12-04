@extends('layouts.admin')

@section('content')
    <h1 style="color: #1f3a93;">Daftar Akun Pengelola dan Staf</h1>
    <p>Manajemen akun Guru, Asisten Lab, dan Kepala Sekolah.</p>
    
    <a href="{{ route('admin.users.create') }}" style="padding: 10px 15px; background: #33a33a; color: white; text-decoration: none; border-radius: 4px;">
        + Tambah Akun Staf Baru
    </a>

    <hr style="margin: 20px 0;">

    @if(session('success'))
        <p style="color: green; font-weight: bold;">✅ {{ session('success') }}</p>
    @endif
    @if(session('error'))
        <p style="color: red; font-weight: bold;">❌ {{ session('error') }}</p>
    @endif

    <table border="1" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="padding: 10px;">ID Pengelola</th>
                <th style="padding: 10px;">Nama</th>
                <th style="padding: 10px;">Username</th>
                <th style="padding: 10px;">Role</th>
                <th style="padding: 10px;">Status</th>
                <th style="padding: 10px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                @php
                    $isAdmin = $user->role === 'Admin';
                    // Ambil detail Admin/Staf dari relasi admin
                    $adminDetail = $user->admin ?? null; 
                    
                    // Tentukan warna status
                    $roleColor = match($user->role) {
                        'Kepsek' => 'darkred',
                        'Admin' => 'darkblue',
                        'Guru', 'AsistenLab' => 'darkgreen',
                        default => 'black'
                    };
                @endphp
                
                <tr>
                    <td style="padding: 8px;">{{ $adminDetail->id_admin ?? 'N/A' }}</td>
                    <td style="padding: 8px;">{{ $adminDetail->nama ?? 'Akun Siswa/System' }}</td>
                    <td style="padding: 8px;">{{ $user->username }}</td>
                    <td style="padding: 8px; font-weight: bold; color: {{ $roleColor }};">{{ $user->role }}</td>
                    <td style="padding: 8px;">Aktif</td>
                    <td style="padding: 8px;">
                        @if ($isAdmin)
                            <span style="color: #666;">Utama</span>
                        @else
                            {{-- Placeholder untuk Edit/Hapus User (Fitur Lanjutan) --}}
                            <a href="#" style="color: blue;">Edit</a> | Hapus
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 15px;">Tidak ada akun staf yang terdaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection