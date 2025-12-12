<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrasi | Sistem Presensi Laboratorium</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    {{-- Tailwind via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 font-sans">

@php
    // --- LOGIKA DATA USER ---
    $user = Auth::user();
    $routeName = Route::currentRouteName();
    
    // Nama Lengkap (Prioritas: Data Admin > Username)
    $namaLengkap = $user->admin->nama ?? $user->username;
    
    // Data Akun
    $username = $user->username;
    $userRole = $user->role;
    $isKepsek = $userRole === 'Kepsek';

    // Hitung Notifikasi (Kecuali Kepsek)
    $pendingCount = 0;
    if (!$isKepsek) {
        $pendingCount = \App\Models\Registrasi::where('status', 'Pending')->count();
    }
@endphp

{{-- HEADER UTAMA --}}
<header class="bg-[#0B57D0] text-white shadow-md">

    {{-- TOP BAR --}}
    <div class="max-w-6xl mx-auto px-6 lg:px-10 py-3 flex items-center justify-between">

        {{-- LOGO AREA --}}
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logosekolah.png') }}"
                 class="w-12 h-12 md:w-14 md:h-14 rounded-full object-contain shadow-sm">
            <div class="leading-tight">
                <p class="uppercase tracking-[0.25em] text-[8px] md:text-[9px]">
                    Sistem Presensi Laboratorium
                </p>
                <p class="font-semibold text-lg md:text-xl">
                    SMK BINA SISWA 2 CILILIN
                </p>
            </div>
        </div>

        {{-- USER AREA (KANAN) --}}
        <div class="flex items-center gap-4">

            {{-- 
                PROFILE BUTTON 
            --}}
            <div class="relative {{ $isKepsek ? '' : 'hidden md:block' }}">
                <button id="profile-toggle"
                        class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center
                               hover:bg-white/30 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="white" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4 0-7 2-7 4v1h14v-1c0-2-3-4-7-4z" />
                    </svg>

                    @if($pendingCount > 0 && !$isKepsek)
                        <span class="absolute -top-1 -right-1 flex items-center justify-center min-w-[20px] h-5 px-1 text-xs font-bold text-white bg-red-600 rounded-full border-2 border-[#0B57D0] animate-pulse">
                            {{ $pendingCount > 99 ? '99+' : $pendingCount }}
                        </span>
                    @endif
                </button>

                {{-- DROPDOWN MENU (DESKTOP) --}}
                <div id="profile-menu"
                     class="hidden absolute right-0 mt-3 w-72 rounded-xl bg-white text-slate-800 shadow-xl z-50 overflow-hidden ring-1 ring-black/5">

                    {{-- 
                        HEADER DROPDOWN:
                    --}}
                    <div class="px-5 py-4 bg-[#0B57D0] text-white border-b border-white/10">
                        {{-- Nama --}}
                        <p class="text-sm font-bold truncate">{{ $namaLengkap }}</p>
                        
                        {{-- Username & Role (Sebaris & Rapi) --}}
                        <div class="flex items-center gap-2 mt-1">
                            <p class="text-xs text-blue-100/90 font-mono">@ {{ $username }}</p>
                            
                            {{-- Role Badge --}}
                            <span class="px-1.5 py-0.5 text-[10px] font-bold bg-white/20 rounded uppercase tracking-wider">
                                {{ $userRole }}
                            </span>
                        </div>
                    </div>

                    {{-- MENU ITEMS --}}
                    <div class="py-2">
                        @if(!$isKepsek)
                            <a href="{{ route('admin.registrations.index') }}" 
                               class="flex items-center justify-between px-4 py-2.5 hover:bg-gray-50 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-orange-50 text-orange-600 group-hover:bg-orange-100 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Approval</p>
                                        <p class="text-xs text-gray-500">Kelola registrasi</p>
                                    </div>
                                </div>
                                @if($pendingCount > 0)
                                    <span class="flex items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-white bg-red-600 rounded-full">
                                        {{ $pendingCount > 99 ? '99+' : $pendingCount }}
                                    </span>
                                @endif
                            </a>
                            <div class="border-t border-gray-100 my-2"></div>
                        @endif

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-red-50 transition-colors group text-left">
                                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 group-hover:bg-red-100 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-red-600 group-hover:text-red-700">Logout</p>
                                    <p class="text-xs text-red-400">Keluar dari sistem</p>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- HAMBURGER BUTTON (Mobile Only) --}}
            @if(!$isKepsek)
            <button id="nav-toggle"
                    class="md:hidden p-2 rounded border border-white/60 relative hover:bg-white/10 transition">
                @if($pendingCount > 0)
                    <span class="absolute -top-1 -right-1 flex items-center justify-center min-w-[18px] h-[18px] text-[10px] font-bold text-white bg-red-600 rounded-full border border-[#0B57D0]">
                        {{ $pendingCount > 9 ? '9+' : $pendingCount }}
                    </span>
                @endif
                <div class="space-y-1">
                    <span class="block w-5 h-0.5 bg-white"></span>
                    <span class="block w-5 h-0.5 bg-white"></span>
                    <span class="block w-5 h-0.5 bg-white"></span>
                </div>
            </button>
            @endif
        </div>
    </div>

    {{-- DESKTOP NAVBAR --}}
    @if(!$isKepsek)
    <div class="hidden md:block border-t border-white/20 bg-[#0B57D0]">
        <nav class="max-w-6xl mx-auto px-6 py-2 flex items-center justify-center gap-6 text-[11px] font-semibold uppercase tracking-[0.20em]">
            <a href="{{ route('admin.dashboard') }}" class="pb-1 border-b-2 {{ $routeName === 'admin.dashboard' ? 'border-white' : 'border-transparent hover:border-white/70' }}">Home</a>
            <a href="{{ route('admin.jadwal.index') }}" class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.jadwal') ? 'border-white' : 'border-transparent hover:border-white/70' }}">Jadwal</a>
            <a href="{{ route('admin.sesi.index') }}" class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.sesi') ? 'border-white' : 'border-transparent hover:border-white/70' }}">Sesi</a>
            <a href="{{ route('admin.koreksi.index') }}" class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.koreksi') ? 'border-white' : 'border-transparent hover:border-white/70' }}">Absensi</a>
            <a href="{{ route('admin.laporan.index') }}" class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.laporan') ? 'border-white' : 'border-transparent hover:border-white/70' }}">Rekap Absen</a>
            @if (Auth::user()->role === 'Admin')
                <a href="{{ route('admin.users.index') }}" class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.users') ? 'border-white' : 'border-transparent hover:border-white/70' }}">Management Akun</a>
            @endif
        </nav>
    </div>
    @endif

    {{-- MOBILE MENU (HAMBURGER CONTENT) --}}
    @if(!$isKepsek)
    <div id="mobile-menu" class="md:hidden hidden bg-[#0B57D0] border-t border-white/20">
        
        {{-- 
            HEADER INFO USER (MOBILE)
        --}}
        <div class="px-4 py-4 bg-[#0B57D0] border-b border-white/10 flex items-center gap-3">
            {{-- Avatar --}}
            <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center shrink-0 border border-white/30">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="white" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4 0-7 2-7 4v1h14v-1c0-2-3-4-7-4z" />
                </svg>
            </div>
            
            {{-- User Info --}}
            <div class="flex-1 overflow-hidden">
                <p class="text-white text-sm font-bold truncate">{{ $namaLengkap }}</p>
                
                <div class="flex items-center gap-2 mt-1">
                    <p class="text-blue-100 text-xs font-mono">@ {{ $username }}</p>
                    
                    {{-- Role Badge Mobile --}}
                    <span class="px-1.5 py-0.5 text-[9px] font-bold bg-white/20 text-white rounded uppercase tracking-wider">
                        {{ $userRole }}
                    </span>
                </div>
            </div>
        </div>
        
        {{-- Navigation Links --}}
        <nav class="max-w-6xl mx-auto px-4 py-4 space-y-1 text-[11px] font-semibold uppercase tracking-[0.20em]">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between rounded-lg px-3 py-2 {{ $routeName === 'admin.dashboard' ? 'bg-white/15' : 'hover:bg-white/10' }}">
                <span>Home</span>
            </a>
            <a href="{{ route('admin.jadwal.index') }}" class="flex items-center justify-between rounded-lg px-3 py-2 {{ str_starts_with($routeName, 'admin.jadwal') ? 'bg-white/15' : 'hover:bg-white/10' }}">
                <span>Jadwal</span>
            </a>
            <a href="{{ route('admin.registrations.index') }}" class="flex items-center justify-between rounded-lg px-3 py-2 {{ str_starts_with($routeName, 'admin.registrations') ? 'bg-white/15' : 'hover:bg-white/10' }}">
                <span>Approval</span>
                @if($pendingCount > 0)
                    <span class="flex items-center justify-center min-w-[22px] h-5 px-1.5 text-[10px] font-bold text-white bg-red-600 rounded-full">
                        {{ $pendingCount > 99 ? '99+' : $pendingCount }}
                    </span>
                @endif
            </a>
            <a href="{{ route('admin.sesi.index') }}" class="flex items-center justify-between rounded-lg px-3 py-2 {{ str_starts_with($routeName, 'admin.sesi') ? 'bg-white/15' : 'hover:bg-white/10' }}">
                <span>Sesi</span>
            </a>
            <a href="{{ route('admin.koreksi.index') }}" class="flex items-center justify-between rounded-lg px-3 py-2 {{ str_starts_with($routeName, 'admin.koreksi') ? 'bg-white/15' : 'hover:bg-white/10' }}">
                <span>Absensi</span>
            </a>
            <a href="{{ route('admin.laporan.index') }}" class="flex items-center justify-between rounded-lg px-3 py-2 {{ str_starts_with($routeName, 'admin.laporan') ? 'bg-white/15' : 'hover:bg-white/10' }}">
                <span>Rekap Absen</span>
            </a>
            @if (Auth::user()->role === 'Admin')
                <a href="{{ route('admin.users.index') }}" class="flex items-center justify-between rounded-lg px-3 py-2 {{ str_starts_with($routeName, 'admin.users') ? 'bg-white/15' : 'hover:bg-white/10' }}">
                    <span>Management Akun</span>
                </a>
            @endif

            {{-- LOGOUT --}}
            <form action="{{ route('logout') }}" method="POST" class="pt-3 mt-2 border-t border-white/25">
                @csrf
                <button type="submit" class="w-full text-left rounded-lg px-3 py-2 text-[11px] text-red-100 hover:bg-red-500/70 hover:text-white flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </form>
        </nav>
    </div>
    @endif

</header>

{{-- MAIN CONTENT --}}
<main class="max-w-6xl mx-auto px-6 lg:px-10 py-8">
    @yield('content')
</main>

<footer class="mt-10 text-center text-xs text-slate-500 py-4">
    &copy; {{ date('Y') }} Sistem Presensi Laboratorium
</footer>

{{-- SCRIPT --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const profBtn = document.getElementById('profile-toggle');
        const profMenu = document.getElementById('profile-menu');
        const navBtn  = document.getElementById('nav-toggle');
        const navMenu = document.getElementById('mobile-menu');

        // Dropdown toggle
        if (profBtn && profMenu) {
            profBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                profMenu.classList.toggle('hidden');
                if (navMenu) navMenu.classList.add('hidden');
            });
            document.addEventListener('click', (e) => {
                if (!profBtn.contains(e.target) && !profMenu.contains(e.target)) profMenu.classList.add('hidden');
            });
        }

        // Mobile menu toggle
        if (navBtn && navMenu) {
            navBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                navMenu.classList.toggle('hidden');
                if (profMenu) profMenu.classList.add('hidden');
            });
        }

        // Close on ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (profMenu) profMenu.classList.add('hidden');
                if (navMenu) navMenu.classList.add('hidden');
            }
        });
    });
</script>

</body>
</html>