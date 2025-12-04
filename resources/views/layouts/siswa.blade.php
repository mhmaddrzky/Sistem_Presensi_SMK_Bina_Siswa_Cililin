<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Siswa | Sistem Presensi Lab</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f8f9fa; }
        header { background: #007bff; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        header h2 { margin: 0; font-size: 1.5em; }
        nav a { color: white; text-decoration: none; margin-left: 20px; padding: 5px 10px; border-radius: 4px; }
        nav a:hover { background-color: #0056b3; }
        main { padding: 20px; }
        .logout-btn { background: #dc3545; border: none; padding: 8px 15px; cursor: pointer; color: white; border-radius: 4px; }
    </style>
</head>
<body>
    <header>
        <h2>Panel Siswa (NIS: {{ auth()->user()->siswa->nis ?? 'N/A' }})</h2>
        <nav style="display: flex; align-items: center;">
            <a href="{{ route('siswa.dashboard') }}">Dashboard</a>
            <a href="{{ route('siswa.presensi.form') }}">Presensi Hari Ini</a>
            
            {{-- Tombol Logout --}}
            <form action="{{ route('logout') }}" method="POST" style="margin-left: 20px;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </nav>
    </header>
    <main>
        @yield('content')
    </main>
    <footer style="text-align: center; padding: 10px; color: #6c757d; font-size: 0.8em; margin-top: 50px;">
        Sistem Presensi Laboratorium | {{ date('Y') }}
    </footer>

    {{-- 🛑 SCRIPT UNTUK REFRESH OTOMATIS (SETIAP 60 DETIK) --}}
    <script>
        // Memastikan status 'Sedang Berlangsung' muncul tanpa refresh manual
        setTimeout(function(){
            window.location.reload(1);
        }, 60000); 
    </script>
</body>
</html>