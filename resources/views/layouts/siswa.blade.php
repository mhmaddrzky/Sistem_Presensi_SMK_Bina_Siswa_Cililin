<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Siswa | Sistem Presensi</title>
    {{-- Tailwind CDN untuk styling --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    
    {{-- ================= HEADER SECTION ================= --}}
    <header class="bg-gradient-to-r from-blue-700 to-blue-500 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-6 py-4">
            
            {{-- Baris pertama: Logo + Nama Sekolah + User Dropdown --}}
            <div class="flex justify-between items-center">
                
                {{-- Logo dan Nama Sekolah --}}
                <div class="flex items-center gap-4">
                    <img src="{{ asset('assets/img/logo-smk.png') }}"
                         class="w-14 h-14 rounded-lg shadow-md" 
                         alt="Logo SMK">
                    <div class="leading-tight">
                        <p class="text-xs opacity-90 uppercase tracking-wide">Sistem Presesnsi Laboratorium</p>
                        <h1 class="text-xl font-bold">SMK BINA SISWA 2 CILILIN</h1>
                    </div>
                </div>
                
                {{-- User Avatar dengan Dropdown --}}
                <div class="relative">
                    {{-- Button icon user --}}
                    <button onclick="toggleDropdown()" class="w-11 h-11 rounded-full bg-white text-blue-700 flex items-center justify-center shadow-md hover:shadow-lg transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    
                    {{-- Dropdown menu user (hidden by default) --}}
                    <div id="userDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl py-2 z-50">
                        {{-- Informasi user --}}
                        <div class="px-4 py-3 border-b border-gray-200">
                            <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->siswa->nama ?? auth()->user()->username }}</p>
                            <p class="text-xs text-gray-500">{{ '@' . auth()->user()->username }}</p>
                        </div>
                        {{-- Form logout --}}
                        <form method="POST" action="{{ route('logout') }}" class="px-2 py-2">
                            @csrf
                            <button class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Baris kedua: Navbar Menu (sejajar dengan nama SMK) --}}
            <nav class="bg-blue-600 bg-opacity-30 backdrop-blur-sm mt-3 -mx-6 px-6">
                {{-- margin-left: 72px untuk sejajar dengan nama SMK (56px logo + 16px gap) --}}
                <div class="flex gap-8 py-3 text-white text-sm font-semibold" style="margin-left: 72px;">
                    <a href="{{ route('siswa.dashboard') }}"
                       class="hover:underline transition @if(request()->routeIs('siswa.dashboard')) underline font-bold @endif">
                        HOME
                    </a>
                    <a href="{{ route('siswa.presensi.form') }}"
                       class="hover:underline transition @if(request()->routeIs('siswa.presensi.form')) underline font-bold @endif">
                        JADWAL
                    </a>
                    <a href="{{ route('siswa.riwayat.index') }}"
                       class="hover:underline transition @if(request()->routeIs('siswa.riwayat.index')) underline font-bold @endif">
                        PRESENSI
                    </a>
                </div>
            </nav>
        </div>
    </header>

    {{-- ================= MAIN CONTENT AREA ================= --}}
    <main class="max-w-7xl mx-auto px-6 py-8">
        @yield('content')
    </main>

    {{-- ================= FOOTER ================= --}}
    <footer class="text-center py-4 text-gray-500 text-sm mt-12">
        Sistem Presensi Laboratorium | {{ date('Y') }}
    </footer>

    {{-- ================= JAVASCRIPT ================= --}}
    <script>
        // Fungsi untuk toggle dropdown user
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown ketika klik di luar area dropdown
        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById('userDropdown');
            const button = e.target.closest('button[onclick="toggleDropdown()"]');
            if (!button && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Auto refresh setiap 60 detik untuk update status presensi real-time
        setTimeout(function(){
            window.location.reload(1);
        }, 60000); 
    </script>
    
</body>
</html>