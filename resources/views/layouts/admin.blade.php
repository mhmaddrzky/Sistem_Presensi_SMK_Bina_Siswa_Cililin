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
<header class="bg-gradient-to-r from-blue-900 via-blue-700 to-sky-500 text-white">

    <div class="w-full px-6 lg:px-10 py-1 flex items-start justify-between">

        {{-- KIRI: LOGO + JUDUL + NAV DESKTOP --}}
        <div class="flex-1 flex flex-col gap-1">

            {{-- LOGO + JUDUL (lebih compact) --}}
            <div class="flex items-center gap-2 mt-4 mb-1">
                <img src="{{ asset('images/logosekolah.png') }}"
                     class="w-14 h-14 rounded-full bg-white/10 object-contain">

                <div class="leading-tight text-xs lg:text-sm mt-1">
                    <p class="uppercase tracking-[0.25em] text-[9px] lg:text-[10px]">
                        Sistem Presensi Laboratorium
                    </p>
                    <p class="font-semibold text-lg lg:text-2xl">
                        SMK BINA SISWA 2 CILILIN
                    </p>
                </div>
            </div>

            {{-- NAV UTAMA (compact) --}}
           <nav class="hidden md:flex justify-end items-center gap-4
                        text-[12px] lg:text-sm font-semibold tracking-[0.20em] uppercase">

                <a href="{{ route('admin.dashboard') }}"
                   class="pb-1 border-b-2 {{ $routeName === 'admin.dashboard'
                        ? 'border-white' : 'border-transparent hover:border-white/80' }}">
                    Home
                </a>

                <a href="{{ route('admin.jadwal.index') }}"
                   class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.jadwal')
                        ? 'border-white' : 'border-transparent hover:border-white/80' }}">
                    Jadwal
                </a>

                <a href="{{ route('admin.registrations.index') }}"
                   class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.registrations')
                        ? 'border-white' : 'border-transparent hover:border-white/80' }}">
                    Approval
                </a>

                <a href="{{ route('admin.koreksi.index') }}"
                   class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.koreksi')
                        ? 'border-white' : 'border-transparent hover:border-white/80' }}">
                    Absensi
                </a>

                <a href="{{ route('admin.laporan.index') }}"
                   class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.laporan')
                        ? 'border-white' : 'border-transparent hover:border-white/80' }}">
                    Rekap Absen
                </a>

                @if (Auth::user()->role === 'Admin')
                    <a href="{{ route('admin.users.index') }}"
                       class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.users')
                            ? 'border-white text-cyan-200'
                            : 'border-transparent hover:border-white/80 text-cyan-200' }}">
                        Management Akun
                    </a>
                @endif
            </nav>
        </div>

        {{-- KANAN: PROFIL --}}
        <div class="flex items-center gap-3">

            <div class="relative hidden md:block">
                <button id="profile-toggle"
                        class="w-9 h-9 rounded-full border border-white/80 flex items-center justify-center
                               hover:bg-white/10 focus:outline-none mt-1">
                    👤
                </button>

                <div id="profile-menu"
                     class="hidden absolute right-0 mt-5 w-44 rounded-lg bg-white text-slate-800 shadow-lg py-2 text-sm z-20">

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

            {{-- HAMBURGER --}}
            <button id="nav-toggle"
                    class="md:hidden p-2 rounded border border-white/60 mt-1">
                <div class="space-y-1">
                    <span class="block w-4 h-0.5 bg-white"></span>
                    <span class="block w-4 h-0.5 bg-white"></span>
                    <span class="block w-4 h-0.5 bg-white"></span>
                </div>
            </button>
        </div>

    </div>

    {{-- NAV MOBILE --}}
    <div id="mobile-menu" class="md:hidden hidden border-t border-white/20">
        <nav class="px-6 py-3 flex flex-col gap-2 text-xs font-semibold uppercase tracking-[0.25em] bg-blue-900/90">
            <a href="{{ route('admin.dashboard') }}" class="py-1">Home</a>
            <a href="{{ route('admin.jadwal.index') }}" class="py-1">Jadwal</a>
            <a href="{{ route('admin.registrations.index') }}" class="py-1">Approval</a>
            <a href="{{ route('admin.koreksi.index') }}" class="py-1">Absensi</a>
            <a href="{{ route('admin.laporan.index') }}" class="py-1">Rekap Absen</a>
            @if (Auth::user()->role === 'Admin')
                <a href="{{ route('admin.users.index') }}" class="py-1">Management Akun</a>
            @endif

            {{-- Logout di mobile --}}
            <form action="{{ route('logout') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit"
                        class="w-full bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-xs font-medium transition">
                    Logout
                </button>
            </form>
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
