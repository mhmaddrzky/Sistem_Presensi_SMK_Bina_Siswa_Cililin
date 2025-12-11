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
    // 🛑 KRITIS: Ambil Role User yang sedang login
    $userRole = Auth::check() ? Auth::user()->role : 'Guest';
@endphp

{{-- HEADER / NAVBAR --}}
<header class="bg-[#0B57D0] text-white shadow-md">

    {{-- BARIS 1: LOGO + TITLE + PROFIL (DESKTOP) + HAMBURGER (MOBILE) --}}
    <div class="max-w-6xl mx-auto px-6 lg:px-10 py-3 flex items-center justify-between">

        {{-- KIRI: LOGO + JUDUL --}}
      <div class="flex items-center gap-3">
    <img src="{{ asset('images/logosekolah.png') }}"
         class="w-12 h-12 md:w-14 md:h-14 rounded-full object-contain shadow-sm">

    <div class="leading-tight">
        {{-- Sub Judul --}}
        <p class="uppercase tracking-[0.25em] text-[8px] md:text-[9px] whitespace-nowrap">
            Sistem Presensi Laboratorium
        </p>
        
        {{-- 🛑 FIX: Perkecil font di mobile (text-base) dan paksa satu baris (whitespace-nowrap) 🛑 --}}
        <p class="font-semibold text-base md:text-xl whitespace-nowrap">
            SMK BINA SISWA 2 CILILIN
        </p>
    </div>
</div>
        {{-- KANAN: PROFIL (DESKTOP) + HAMBURGER (MOBILE) --}}
        <div class="flex items-center gap-4">

            {{-- PROFIL: HANYA DI DESKTOP / TABLET BESAR --}}
            @if (Auth::check())
            <div class="relative hidden md:block">
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

                {{-- DROPDOWN PROFIL (LOGOUT DI SINI UNTUK DESKTOP) --}}
                <div id="profile-menu"
                     class="hidden absolute right-0 mt-3 w-44 rounded-lg bg-white text-slate-800 shadow-lg py-2 text-sm z-20">

                    <div class="px-4 py-2 border-b border-slate-200 text-[11px] text-slate-500">
                        Masuk sebagai<br>
                        <span class="font-semibold">{{ Auth::user()->username }} ({{ $userRole }})</span>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="px-2 pt-1">
                        @csrf
                        <button class="w-full text-left px-3 py-2 rounded-md text-xs text-red-600 hover:bg-red-50">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- HAMBURGER: HANYA DI MOBILE --}}
            <button id="nav-toggle"
                    class="md:hidden p-2 rounded border border-white/60">
                <div class="space-y-1">
                    <span class="block w-5 h-0.5 bg-white"></span>
                    <span class="block w-5 h-0.5 bg-white"></span>
                    <span class="block w-5 h-0.5 bg-white"></span>
                </div>
            </button>
        </div>
    </div>

    {{-- BARIS 2: NAV DESKTOP --}}
    <div class="hidden md:block border-t border-white/20 bg-[#0B57D0]">
        <nav class="max-w-6xl mx-auto px-6 py-2 flex items-center justify-center gap-6
                    text-[11px] font-semibold uppercase tracking-[0.20em]">

            {{-- HOME - Tampil untuk SEMUA --}}
            <a href="{{ route('admin.dashboard') }}"
               class="pb-1 border-b-2 {{ $routeName === 'admin.dashboard'
                    ? 'border-white'
                    : 'border-transparent hover:border-white/70' }}">
                Home
            </a>

            {{-- 🛑 OPERASIONAL LINKS (DISEMBUNYIKAN DARI KEPSEK) 🛑 --}}
            @if ($userRole != 'Kepsek')
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
            @endif

            {{-- REKAP ABSEN - Tampil untuk SEMUA (Termasuk Kepsek) --}}
            <a href="{{ route('admin.laporan.index') }}"
               class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.laporan')
                    ? 'border-white'
                    : 'border-transparent hover:border-white/70' }}">
                Rekap Absen
            </a>

            {{-- MANAGEMENT AKUN (HANYA ADMIN UTAMA) --}}
            @if ($userRole === 'Admin')
                <a href="{{ route('admin.users.index') }}"
                   class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.users')
                        ? 'border-white'
                        : 'border-transparent hover:border-white/70' }}">
                    Management Akun
                </a>
            @endif
        </nav>
    </div>

    {{-- NAV MOBILE (LINK + LOGOUT) --}}
<div id="mobile-menu" class="md:hidden hidden bg-[#0B57D0] border-t border-white/20">
    <nav class="max-w-6xl mx-auto px-4 py-4 space-y-1 text-[11px] font-semibold uppercase tracking-[0.20em]">

        {{-- HOME - Tampil untuk SEMUA --}}
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center justify-between rounded-lg px-3 py-2
                 {{ $routeName === 'admin.dashboard'
                        ? 'bg-white/15'
                        : 'hover:bg-white/10' }}">
            <span>Home</span>
        </a>

        {{-- 🛑 OPERASIONAL LINKS (DISEMBUNYIKAN DARI KEPSEK) 🛑 --}}
        @if ($userRole != 'Kepsek')
            <a href="{{ route('admin.jadwal.index') }}"
               class="flex items-center justify-between rounded-lg px-3 py-2
                     {{ str_starts_with($routeName, 'admin.jadwal')
                            ? 'bg-white/15'
                            : 'hover:bg-white/10' }}">
                <span>Jadwal</span>
            </a>

            <a href="{{ route('admin.registrations.index') }}"
               class="flex items-center justify-between rounded-lg px-3 py-2
                     {{ str_starts_with($routeName, 'admin.registrations')
                            ? 'bg-white/15'
                            : 'hover:bg-white/10' }}">
                <span>Approval</span>
            </a>

            <a href="{{ route('admin.sesi.index') }}"
               class="flex items-center justify-between rounded-lg px-3 py-2
                     {{ str_starts_with($routeName, 'admin.sesi')
                            ? 'bg-white/15'
                            : 'hover:bg-white/10' }}">
                <span>Sesi</span>
            </a>

            <a href="{{ route('admin.koreksi.index') }}"
               class="flex items-center justify-between rounded-lg px-3 py-2
                     {{ str_starts_with($routeName, 'admin.koreksi')
                            ? 'bg-white/15'
                            : 'hover:bg-white/10' }}">
                <span>Absensi</span>
            </a>
        @endif

        {{-- REKAP ABSEN - Tampil untuk SEMUA (Termasuk Kepsek) --}}
        <a href="{{ route('admin.laporan.index') }}"
           class="flex items-center justify-between rounded-lg px-3 py-2
                 {{ str_starts_with($routeName, 'admin.laporan')
                        ? 'bg-white/15'
                        : 'hover:bg-white/10' }}">
            <span>Rekap Absen</span>
        </a>

        {{-- MANAGEMENT AKUN (HANYA ADMIN UTAMA) --}}
        @if ($userRole === 'Admin')
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center justify-between rounded-lg px-3 py-2
                     {{ str_starts_with($routeName, 'admin.users')
                            ? 'bg-white/15'
                            : 'hover:bg-white/10' }}">
                <span>Management Akun</span>
            </a>
        @endif

        {{-- LOGOUT DI MOBILE --}}
        <form action="{{ route('logout') }}" method="POST" class="pt-3 mt-2 border-t border-white/25">
            @csrf
            <button type="submit"
                    class="w-full text-left rounded-lg px-3 py-2 text-[11px]
                            text-red-100 hover:bg-red-500/70 hover:text-white">
                Logout
            </button>
        </form>
    </nav>
</div>

</header>

{{-- MAIN CONTENT --}}
<main class="max-w-6xl mx-auto px-6 lg:px-10 py-8">
    {{-- 🛑 Catatan: Anda harus mengarahkan Kepsek ke Halaman Laporan secara langsung di Controller/Route jika mereka mencoba mengakses Dashboard. --}}
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
        const navBtn  = document.getElementById('nav-toggle');
        const navMenu = document.getElementById('mobile-menu');

        // Dropdown profil (desktop)
        if (profBtn && profMenu) {
            profBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                profMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', () => {
                profMenu.classList.add('hidden');
            });
        }

        // Toggle menu mobile
        if (navBtn && navMenu) {
            navBtn.addEventListener('click', () => {
                navMenu.classList.toggle('hidden');
            });
        }
    });
</script>

</body>
</html>