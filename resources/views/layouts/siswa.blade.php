<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Siswa | Sistem Presensi</title>
    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen font-sans">

{{-- ================= HEADER SECTION ================= --}}

<header class="bg-[#0B57D0] text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 md:px-6 py-3 md:py-4">

        {{-- BARIS 1: Logo + Nama Sekolah + User/Hamburger --}}
        <div class="flex items-center justify-between gap-3">

            {{-- KIRI: Logo dan Nama Sekolah --}}
            <div class="flex items-center gap-3 md:gap-4">
                <img src="{{ asset('assets/img/logo-smk.png') }}"
                     class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-white/10 p-1 object-contain shadow-sm"
                     alt="Logo SMK">
                <div class="leading-tight">
                    <p class="text-[10px] md:text-xs opacity-90 uppercase tracking-wide">
                        Sistem Presensi Laboratorium
                    </p>
                    <h1 class="text-lg md:text-xl font-bold">
                        SMK BINA SISWA 2 CILILIN
                    </h1>
                </div>
            </div>

            {{-- KANAN: Avatar (desktop) + Hamburger (mobile) --}}
            <div class="flex items-center gap-3">

                {{-- A. BUTTON USER (Hanya di Desktop) --}}
                <div class="relative hidden md:block">
                    <button id="user-desktop-btn"
                            class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center
                                   hover:bg-white/30 transition text-white border border-white/20">
                        {{-- Icon User --}}
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4 0-7 2-7 4v1h14v-1c0-2-3-4-7-4z" />
                        </svg>
                    </button>

                    {{-- DROPDOWN MENU (Desktop) --}}
                    <div id="user-desktop-dropdown"
                         class="hidden absolute right-0 mt-3 w-72 bg-white rounded-xl shadow-xl z-50 overflow-hidden ring-1 ring-black/5">
                        
                        {{-- Header Dropdown --}}
                        <div class="px-5 py-4 bg-[#0B57D0] text-white border-b border-white/10">
                            <p class="text-sm font-bold truncate">
                                {{ auth()->user()->siswa->nama ?? auth()->user()->username }}
                            </p>
                            <div class="flex items-center gap-2 mt-1">
                                <p class="text-xs text-blue-100/90 font-mono">
                                    {{ '@' . auth()->user()->username }}
                                </p>
                                <span class="px-1.5 py-0.5 text-[10px] font-bold bg-white/20 rounded uppercase tracking-wider">
                                    SISWA
                                </span>
                            </div>
                        </div>
                        
                        {{-- Isi Menu Dropdown --}}
                        <div class="py-2">
                            <a href="{{ route('siswa.riwayat.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors text-sm font-medium text-gray-700">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                Riwayat Presensi
                            </a>
                        </div>
                        
                        <div class="border-t border-gray-100"></div>
                        
                        <form method="POST" action="{{ route('logout') }}" class="py-1">
                            @csrf
                            <button class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-red-50 transition-colors text-left group">
                                <div class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center group-hover:bg-red-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-red-600 group-hover:text-red-700">Logout</p>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- B. HAMBURGER BUTTON (Hanya di Mobile) --}}
                <button id="nav-toggle"
                        class="md:hidden p-2 rounded border border-white/60 hover:bg-white/10 transition relative">
                    <div class="space-y-1">
                        <span class="block w-5 h-0.5 bg-white"></span>
                        <span class="block w-5 h-0.5 bg-white"></span>
                        <span class="block w-5 h-0.5 bg-white"></span>
                    </div>
                </button>
            </div>
        </div>

        {{-- BARIS 2: NAV DESKTOP --}}
        <nav class="hidden md:block mt-3 border-t border-white/20 pt-2">
            <div class="flex gap-8 text-xs md:text-sm font-semibold tracking-wider">
                <a href="{{ route('siswa.dashboard') }}"
                   class="pb-1 border-b-2 transition
                        {{ request()->routeIs('siswa.dashboard') ? 'border-white' : 'border-transparent hover:border-white/50' }}">
                    HOME
                </a>
                <a href="{{ route('siswa.presensi.form') }}"
                   class="pb-1 border-b-2 transition
                        {{ request()->routeIs('siswa.presensi.form') ? 'border-white' : 'border-transparent hover:border-white/50' }}">
                    JADWAL
                </a>
                <a href="{{ route('siswa.presensi.index') }}"
                   class="pb-1 border-b-2 transition
                        {{ request()->routeIs('siswa.presensi.index') ? 'border-white' : 'border-transparent hover:border-white/50' }}">
                    PRESENSI
                </a>
            </div>
        </nav>

        {{-- MOBILE MENU CONTENT --}}
        <div id="mobile-menu" class="md:hidden hidden mt-3 bg-[#0B57D0] border-t border-white/20 -mx-4 px-4 pb-4 shadow-inner">
            
            {{-- 1. HEADER INFO USER (MOBILE) --}}
            <div class="py-4 border-b border-white/10 flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center shrink-0 border border-white/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4 0-7 2-7 4v1h14v-1c0-2-3-4-7-4z" />
                    </svg>
                </div>
                <div class="flex-1 overflow-hidden">
                    <p class="text-white text-sm font-bold truncate">
                        {{ auth()->user()->siswa->nama ?? auth()->user()->username }}
                    </p>
                    <div class="flex items-center gap-2 mt-0.5">
                        <p class="text-blue-100 text-xs font-mono">
                            {{ '@' . auth()->user()->username }}
                        </p>
                        <span class="px-1.5 py-0.5 text-[9px] font-bold bg-white/20 text-white rounded uppercase tracking-wider">
                            SISWA
                        </span>
                    </div>
                </div>
            </div>

            {{-- 2. LINK NAVIGASI MOBILE --}}
            <nav class="space-y-1 text-[11px] font-semibold uppercase tracking-wider">
                <a href="{{ route('siswa.dashboard') }}"
                   class="block rounded-lg px-3 py-2 transition
                       {{ request()->routeIs('siswa.dashboard') ? 'bg-white/15' : 'hover:bg-white/10' }}">
                    HOME
                </a>
                <a href="{{ route('siswa.presensi.form') }}"
                   class="block rounded-lg px-3 py-2 transition
                       {{ request()->routeIs('siswa.presensi.form') ? 'bg-white/15' : 'hover:bg-white/10' }}">
                    JADWAL
                </a>
                <a href="{{ route('siswa.presensi.index') }}"
                   class="block rounded-lg px-3 py-2 transition
                       {{ request()->routeIs('siswa.presensi.index') ? 'bg-white/15' : 'hover:bg-white/10' }}">
                    PRESENSI
                </a>
                <a href="{{ route('siswa.riwayat.index') }}"
                   class="block rounded-lg px-3 py-2 transition flex items-center gap-2
                       {{ request()->routeIs('siswa.riwayat.index') ? 'bg-white/15' : 'hover:bg-white/10' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Riwayat Presensi
                </a>

                {{-- 3. LOGOUT BUTTON MOBILE --}}
                <form method="POST" action="{{ route('logout') }}" class="pt-3 mt-2 border-t border-white/20">
                    @csrf
                    <button type="submit"
                            class="w-full text-left rounded-lg px-3 py-2 text-[11px] flex items-center gap-2
                                   text-red-100 hover:bg-red-500/70 hover:text-white transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </nav>
        </div>

    </div>
</header>

{{-- ================= MAIN CONTENT AREA ================= --}}
<main class="max-w-7xl mx-auto px-4 md:px-6 py-6 md:py-8">
    @yield('content')
</main>

{{-- ================= FOOTER ================= --}}
<footer class="text-center py-4 text-gray-500 text-sm mt-12">
    Sistem Presensi Laboratorium | {{ date('Y') }}
</footer>

{{-- ================= JAVASCRIPT ================= --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Desktop user dropdown toggle
        const userBtn = document.getElementById('user-desktop-btn');
        const userDropdown = document.getElementById('user-desktop-dropdown');

        if (userBtn && userDropdown) {
            userBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!userBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.add('hidden');
                }
            });
        }

        // Mobile nav toggle
        const navToggle = document.getElementById('nav-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        if (navToggle && mobileMenu) {
            navToggle.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
    });
</script>

</body>
</html>