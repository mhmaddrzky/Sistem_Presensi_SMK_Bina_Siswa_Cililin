<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrasi | Sistem Presensi Laboratorium</title>
    {{-- Di sini Anda bisa menaruh link CSS framework seperti Bootstrap atau Tailwind --}}
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f4f7f6; }
        header { background: #1f3a93; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        header a { color: white; text-decoration: none; margin-left: 20px; padding: 5px 10px; border-radius: 4px; }
        header a:hover { background-color: #1a4d8c; }
        .nav-approval { background-color: #e6b800; color: #1f3a93; font-weight: bold; }
        .nav-approval:hover { background-color: #d8aa00; }
        main { padding: 30px; }
        .logout-btn { background: #d9534f; border: none; padding: 8px 15px; cursor: pointer; color: white; border-radius: 4px; }
    </style>
</head>
<body>

    {{-- resources/views/layouts/admin.blade.php --}}

<header style="background: #1f3a93; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center;">
    <h2>Panel Admin Lab</h2>
    <nav style="display: flex; align-items: center; font-size: 0.95em;">
        
        <a href="{{ route('admin.dashboard') }}" style="color: white; margin-left: 20px;">Dashboard</a>
        
        {{-- Manajemen Lab Operasional --}}
        <a href="{{ route('admin.jadwal.index') }}" style="color: white; margin-left: 20px;">Kelola Jadwal</a>

        {{-- Tugas Sensitif Harian --}}
        
        {{-- Laporan --}}
        
        {{-- 🛑 PEMBATASAN AKUN STAF (HANYA ADMIN UTAMA) 🛑 --}}
        @if (Auth::user()->role === 'Admin')
            <a href="{{ route('admin.users.index') }}" style="color: #6dccff; margin-left: 20px;">Management Akun</a>
        @endif
        
        {{-- Tombol Logout --}}
        <form action="{{ route('logout') }}" method="POST" style="margin-left: 30px;">
            @csrf
            <button type="submit" style="background: #d9534f; color: white; border: none; padding: 8px 15px; cursor: pointer; border-radius: 4px;">Logout</button>
        </form>
    </nav>
</header>

    <main>
        {{-- SLOT KONTEN UTAMA --}}
        @yield('content') 
    </main>

    <footer style="margin-top: 50px; text-align: center; color: #888; padding: 10px;">
        &copy; {{ date('Y') }} Sistem Presensi Laboratorium
    </footer>

</body>
</html>