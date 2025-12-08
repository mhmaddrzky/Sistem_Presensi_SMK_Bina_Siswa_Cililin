<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrasi | Sistem Presensi Laboratorium</title>

    {{-- Tailwind via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 font-sans">

@php
    $routeName = Route::currentRouteName();
@endphp

{{-- HEADER / NAVBAR --}}
<header class="bg-[#0B57D0] text-white shadow-md">

    {{-- BARIS 1: LOGO + TITLE + PROFILE + HAMBURGER --}}
    <div class="max-w-6xl mx-auto px-6 lg:px-10 py-3 flex items-center justify-between">

        {{-- KIRI: LOGO + JUDUL --}}
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logosekolah.png') }}"
                 class="w-14 h-14 rounded-full object-contain shadow-sm">

            <div class="leading-tight">
                <p class="uppercase tracking-[0.25em] text-[9px]">
                    Sistem Presensi Laboratorium
                </p>
                <p class="font-semibold text-xl">
                    SMK BINA SISWA 2 CILILIN
                </p>
            </div>
        </div>

        {{-- KANAN: PROFIL + HAMBURGER --}}
        <div class="flex items-center gap-4">

            {{-- PROFIL (DESKTOP & MOBILE) --}}
            <div class="relative">
                <button id="profile-toggle"
                        class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center
                               hover:bg-white/30 transition">
                    {{-- ICON PROFIL --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="white" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4 0-7 2-7 4v1h14v-1c0-2-3-4-7-4z" />
                    </svg>
                </button>

                {{-- DROPDOWN PROFIL (LOGOUT DI SINI) --}}
                <div id="profile-menu"
                     class="hidden absolute right-0 mt-3 w-44 rounded-lg bg-white text-slate-800 shadow-lg py-2 text-sm z-20">

                    <div class="px-4 py-2 border-b border-slate-200 text-[11px] text-slate-500">
                        Masuk sebagai<br>
                        <span class="font-semibold">{{ Auth::user()->username }}</span>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="px-2 pt-1">
                        @csrf
                        <button class="w-full text-left px-3 py-2 rounded-md text-xs text-red-600 hover:bg-red-50">
                            Logout
                        </button>
                    </form>
                    
                </div>
            </div>

            {{-- HAMBURGER (KHUSUS MOBILE) --}}
            <button id="nav-toggle"
                    class="md:hidden p-2 rounded border border-white/60">
                <div class="space-y-1">
                    <span class="block w-4 h-0.5 bg-white"></span>
                    <span class="block w-4 h-0.5 bg-white"></span>
                    <span class="block w-4 h-0.5 bg-white"></span>
                </div>
            </button>
        </div>
    </div>

    {{-- BARIS 2: NAV DESKTOP --}}
    <div class="hidden md:block border-t border-white/20 bg-[#0B57D0]">
        <nav class="max-w-6xl mx-auto px-6 py-2 flex items-center justify-center gap-6
                    text-[11px] font-semibold uppercase tracking-[0.20em]">

            <a href="{{ route('admin.dashboard') }}"
               class="pb-1 border-b-2 {{ $routeName === 'admin.dashboard'
                    ? 'border-white'
                    : 'border-transparent hover:border-white/70' }}">
                Home
            </a>

            <a href="{{ route('admin.jadwal.index') }}"
               class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.jadwal')
                    ? 'border-white'
                    : 'border-transparent hover:border-white/70' }}">
                Jadwal
            </a>

            <a href="{{ route('admin.registrations.index') }}"
               class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.registrations')
                    ? 'border-white'
                    : 'border-transparent hover:border-white/70' }}">
                Approval
            </a>

            <a href="{{ route('admin.sesi.index') }}"
               class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.sesi')
                    ? 'border-white'
                    : 'border-transparent hover:border-white/70' }}">
                Sesi
            </a>

            <a href="{{ route('admin.koreksi.index') }}"
               class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.koreksi')
                    ? 'border-white'
                    : 'border-transparent hover:border-white/70' }}">
                Absensi
            </a>

            <a href="{{ route('admin.laporan.index') }}"
               class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.laporan')
                    ? 'border-white'
                    : 'border-transparent hover:border-white/70' }}">
                Rekap Absen
            </a>

            @if (Auth::user()->role === 'Admin')
                <a href="{{ route('admin.users.index') }}"
                   class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.users')
                        ? 'border-white'
                    : 'border-transparent hover:border-white/70' }}">
                    Management Akun
                </a>
            @endif
        </nav>
    </div>

    {{-- NAV MOBILE (TANPA LOGOUT) --}}
    <div id="mobile-menu" class="md:hidden hidden border-t border-white/20 bg-[#0B57D0]">
        <nav class="px-6 py-3 flex flex-col gap-2 text-xs font-semibold uppercase tracking-[0.25em]">
            <a href="{{ route('admin.dashboard') }}" class="py-1">Home</a>
            <a href="{{ route('admin.jadwal.index') }}" class="py-1">Jadwal</a>
            <a href="{{ route('admin.registrations.index') }}" class="py-1">Approval</a>
            <a href="{{ route('admin.sesi.index') }}" class="py-1">Sesi</a>
            <a href="{{ route('admin.koreksi.index') }}" class="py-1">Absensi</a>
            <a href="{{ route('admin.laporan.index') }}" class="py-1">Rekap Absen</a>
            @if (Auth::user()->role === 'Admin')
                <a href="{{ route('admin.users.index') }}" class="py-1">Management Akun</a>
            @endif
        </nav>
    </div>
</header>

{{-- MAIN CONTENT --}}
<main class="max-w-6xl mx-auto px-6 lg:px-10 py-8">
    @yield('content')
</main>

<footer class="mt-10 text-center text-xs text-slate-500 py-4">
    &copy; {{ date('Y') }} Sistem Presensi Laboratorium
</footer>

{{-- JS UNTUK DROPDOWN & NAV MOBILE --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const profBtn = document.getElementById('profile-toggle');
        const profMenu = document.getElementById('profile-menu');
        const navBtn  = document.getElementById('nav-toggle');
        const navMenu = document.getElementById('mobile-menu');

        if (profBtn && profMenu) {
            profBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                profMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', () => {
                profMenu.classList.add('hidden');
            });
        }

        if (navBtn && navMenu) {
            navBtn.addEventListener('click', () => {
                navMenu.classList.toggle('hidden');
            });
        }
    });
</script>

</body>
</html>
