<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrasi | Sistem Presensi Laboratorium</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Tailwind via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 font-sans">

@php
    // --- LOGIKA DATA USER ---
    $user = Auth::user();
    $routeName = Route::currentRouteName();

    // Nama Lengkap (Cek apakah relasi admin ada untuk menghindari error)
    $namaLengkap = $user->admin->nama ?? $user->username;

    // Data Akun
    $username = $user->username;
    $userRole = $user->role;
    $isKepsek = $userRole === 'Kepsek';

    // REVISI: Hitung Notifikasi HANYA jika role adalah Admin
    $pendingCount = 0;
    if ($userRole === 'Admin') {
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

            {{-- PROFILE BUTTON --}}
            <div class="relative {{ $isKepsek ? '' : 'hidden md:block' }}">
                <button id="profile-toggle"
                        class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center
                               hover:bg-white/30 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="white" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4 0-7 2-7 4v1h14v-1c0-2-3-4-7-4z" />
                    </svg>

                    @if($pendingCount > 0)
                        <span class="absolute -top-1 -right-1 flex items-center justify-center min-w-[20px] h-5 px-1 text-xs font-bold text-white bg-red-600 rounded-full border-2 border-[#0B57D0] animate-pulse">
                            {{ $pendingCount > 99 ? '99+' : $pendingCount }}
                        </span>
                    @endif
                </button>

                {{-- DROPDOWN MENU (DESKTOP) --}}
                <div id="profile-menu"
                     class="hidden absolute right-0 mt-3 w-72 rounded-xl bg-white text-slate-800 shadow-xl z-50 overflow-hidden ring-1 ring-black/5">

                    {{-- HEADER DROPDOWN --}}
                    <div class="px-5 py-4 bg-[#0B57D0] text-white border-b border-white/10">
                        <p class="text-sm font-bold truncate">{{ $namaLengkap }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <p class="text-xs text-blue-100/90 font-mono">@ {{ $username }}</p>
                            <span class="px-1.5 py-0.5 text-[10px] font-bold bg-white/20 rounded uppercase tracking-wider">
                                {{ $userRole }}
                            </span>
                        </div>
                    </div>

                    {{-- MENU ITEMS --}}
                    <div class="py-2">
                        @if($userRole === 'Admin')
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
            <a href="{{ route('admin.dashboard') }}" 
               class="pb-1 border-b-2 transition-colors {{ $routeName === 'admin.dashboard' ? 'border-white' : 'border-transparent hover:border-white/70' }}">
                Home
            </a>
            <a href="{{ route('admin.jadwal.index') }}" 
               class="pb-1 border-b-2 transition-colors {{ str_starts_with($routeName, 'admin.jadwal') ? 'border-white' : 'border-transparent hover:border-white/70' }}">
                Jadwal
            </a>
            <a href="{{ route('admin.sesi.index') }}" 
               class="pb-1 border-b-2 transition-colors {{ str_starts_with($routeName, 'admin.sesi') ? 'border-white' : 'border-transparent hover:border-white/70' }}">
                Sesi
            </a>
            <a href="{{ route('admin.koreksi.index') }}" 
               class="pb-1 border-b-2 transition-colors {{ str_starts_with($routeName, 'admin.koreksi') ? 'border-white' : 'border-transparent hover:border-white/70' }}">
                Presensi
            </a>
            <a href="{{ route('admin.laporan.index') }}" 
               class="pb-1 border-b-2 transition-colors {{ str_starts_with($routeName, 'admin.laporan') ? 'border-white' : 'border-transparent hover:border-white/70' }}">
                Rekap Presensi
            </a>
            
            @if ($userRole === 'Admin')
                {{-- DROPDOWN MANAGEMENT AKUN (DESKTOP) - IMPROVED --}}
                <div class="relative group">
                    <button type="button" 
                            class="pb-1 border-b-2 transition-colors flex items-center gap-1.5 {{ (str_starts_with($routeName, 'admin.users') || str_starts_with($routeName, 'admin.siswa')) ? 'border-white' : 'border-transparent group-hover:border-white/70' }}">
                        <span>MANAGEMENT AKUN</span>
                        <svg class="w-3 h-3 transition-transform group-hover:rotate-180" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    
                    {{-- DROPDOWN MENU - SIMPLIFIED --}}
                    <div class="absolute top-full left-1/2 -translate-x-1/2 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 overflow-hidden border border-slate-200">
                        <div class="py-1">
                            <a href="{{ route('admin.users.index') }}" 
                               class="flex items-center gap-2.5 px-3 py-2 text-sm text-slate-700 hover:bg-blue-50 transition-colors {{ str_starts_with($routeName, 'admin.users') ? 'bg-blue-50 text-blue-700 font-medium' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <span>Akun Pengelola</span>
                            </a>
                            
                            <a href="{{ route('admin.siswa.index') }}" 
                               class="flex items-center gap-2.5 px-3 py-2 text-sm text-slate-700 hover:bg-green-50 transition-colors {{ str_starts_with($routeName, 'admin.siswa') ? 'bg-green-50 text-green-700 font-medium' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span>Data Siswa</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </nav>
    </div>
    @endif

    {{-- MOBILE MENU --}}
    @if(!$isKepsek)
    <div id="mobile-menu" class="md:hidden hidden bg-[#0B57D0] border-t border-white/20">
        <div class="px-4 py-4 bg-[#0B57D0] border-b border-white/10 flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center shrink-0 border border-white/30">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="white" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4 0-7 2-7 4v1h14v-1c0-2-3-4-7-4z" />
                </svg>
            </div>
            <div class="flex-1 overflow-hidden">
                <p class="text-white text-sm font-bold truncate">{{ $namaLengkap }}</p>
                <div class="flex items-center gap-2 mt-1">
                    <p class="text-blue-100 text-xs font-mono">@ {{ $username }}</p>
                    <span class="px-1.5 py-0.5 text-[9px] font-bold bg-white/20 text-white rounded uppercase tracking-wider">
                        {{ $userRole }}
                    </span>
                </div>
            </div>
        </div>

        <nav class="max-w-6xl mx-auto px-4 py-4 space-y-1 text-[11px] font-semibold uppercase tracking-[0.20em]">
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center justify-between rounded-lg px-3 py-2 {{ $routeName === 'admin.dashboard' ? 'bg-white/15' : 'hover:bg-white/10' }}">
                <span>Home</span>
            </a>
            <a href="{{ route('admin.jadwal.index') }}" 
               class="flex items-center justify-between rounded-lg px-3 py-2 {{ str_starts_with($routeName, 'admin.jadwal') ? 'bg-white/15' : 'hover:bg-white/10' }}">
                <span>Jadwal</span>
            </a>

            @if($userRole === 'Admin')
            <a href="{{ route('admin.registrations.index') }}" 
               class="flex items-center justify-between rounded-lg px-3 py-2 {{ str_starts_with($routeName, 'admin.registrations') ? 'bg-white/15' : 'hover:bg-white/10' }}">
                <span>Approval</span>
                @if($pendingCount > 0)
                    <span class="flex items-center justify-center min-w-[22px] h-5 px-1.5 text-[10px] font-bold text-white bg-red-600 rounded-full">
                        {{ $pendingCount > 99 ? '99+' : $pendingCount }}
                    </span>
                @endif
            </a>
            @endif

            <a href="{{ route('admin.sesi.index') }}" 
               class="flex items-center justify-between rounded-lg px-3 py-2 {{ str_starts_with($routeName, 'admin.sesi') ? 'bg-white/15' : 'hover:bg-white/10' }}">
                <span>Sesi</span>
            </a>
            <a href="{{ route('admin.koreksi.index') }}" 
               class="flex items-center justify-between rounded-lg px-3 py-2 {{ str_starts_with($routeName, 'admin.koreksi') ? 'bg-white/15' : 'hover:bg-white/10' }}">
                <span>Presensi</span>
            </a>
            <a href="{{ route('admin.laporan.index') }}" 
               class="flex items-center justify-between rounded-lg px-3 py-2 {{ str_starts_with($routeName, 'admin.laporan') ? 'bg-white/15' : 'hover:bg-white/10' }}">
                <span>Rekap Presensi</span>
            </a>

            @if ($userRole === 'Admin')
                {{-- MOBILE: Management Akun Dropdown - SIMPLIFIED --}}
                <button id="mobile-dropdown-toggle" type="button"
                        class="w-full flex items-center justify-between rounded-lg px-3 py-2 hover:bg-white/10 {{ (str_starts_with($routeName, 'admin.users') || str_starts_with($routeName, 'admin.siswa')) ? 'bg-white/15' : '' }}">
                    <span>MANAGEMENT AKUN</span>
                    <svg id="mobile-dropdown-icon" class="w-3.5 h-3.5 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
                
                {{-- SUB MENU - SIMPLIFIED --}}
                <div id="mobile-dropdown-menu" class="hidden space-y-0.5 pl-3 ml-3 border-l-2 border-white/30">
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center gap-2 rounded-lg px-3 py-2 text-[10px] {{ str_starts_with($routeName, 'admin.users') ? 'bg-white/15 font-semibold' : 'hover:bg-white/10' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Akun Pengelola</span>
                    </a>
                    <a href="{{ route('admin.siswa.index') }}" 
                       class="flex items-center gap-2 rounded-lg px-3 py-2 text-[10px] {{ str_starts_with($routeName, 'admin.siswa') ? 'bg-white/15 font-semibold' : 'hover:bg-white/10' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span>Data Siswa</span>
                    </a>
                </div>
            @endif

            <form action="{{ route('logout') }}" method="POST" class="pt-3 mt-2 border-t border-white/25">
                @csrf
                <button type="submit" class="w-full text-left rounded-lg px-3 py-2 text-[11px] text-red-100 hover:bg-red-500/70 hover:text-white flex items-center gap-2">
                    Logout
                </button>
            </form>
        </nav>
    </div>
    @endif
</header>

<main class="max-w-6xl mx-auto px-6 lg:px-10 py-8">
    @yield('content')
</main>

<footer class="mt-10 text-center text-xs text-slate-500 py-4">
    &copy; {{ date('Y') }} Sistem Presensi Laboratorium
</footer>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const profBtn = document.getElementById('profile-toggle');
        const profMenu = document.getElementById('profile-menu');
        const navBtn = document.getElementById('nav-toggle');
        const navMenu = document.getElementById('mobile-menu');
        const mobileDropdownToggle = document.getElementById('mobile-dropdown-toggle');
        const mobileDropdownMenu = document.getElementById('mobile-dropdown-menu');
        const mobileDropdownIcon = document.getElementById('mobile-dropdown-icon');

        // Profile dropdown
        if (profBtn && profMenu) {
            profBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                profMenu.classList.toggle('hidden');
                if (navMenu) navMenu.classList.add('hidden');
            });
            document.addEventListener('click', (e) => {
                if (!profBtn.contains(e.target) && !profMenu.contains(e.target)) {
                    profMenu.classList.add('hidden');
                }
            });
        }

        // Mobile nav toggle
        if (navBtn && navMenu) {
            navBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                navMenu.classList.toggle('hidden');
                if (profMenu) profMenu.classList.add('hidden');
            });
        }

        // Mobile dropdown toggle
        if (mobileDropdownToggle && mobileDropdownMenu && mobileDropdownIcon) {
            mobileDropdownToggle.addEventListener('click', () => {
                mobileDropdownMenu.classList.toggle('hidden');
                mobileDropdownIcon.classList.toggle('rotate-180');
            });
        }
    });
</script>

</body>
</html>