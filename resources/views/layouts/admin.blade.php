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
    <div class="max-w-6xl mx-auto px-6 lg:px-10 py-3 flex items-center justify-between">

        {{-- KIRI: BRAND + NAV DESKTOP --}}
        <div class="flex-1 flex flex-col gap-3">
            {{-- Brand kiri atas --}}
            <div class="flex items-center gap-3">
                {{-- logo Sekolah --}}
                <img src="{{ asset('images/logosekolah.png') }}"
                     alt="logosekolah"
                     class="w-10 h-10 rounded-full bg-white/10 object-contain"> 

                <div class="leading-tight text-xs lg:text-sm">
                    <p class="uppercase tracking-[0.25em] text-[9px] lg:text-[10px]">
                        Sistem Presensi Laboratorium
                    </p>
                    <p class="font-semibold text-base lg:text-xl">
                        SMK BINA SISWA 2 CILILIN
                    </p>
                </div>
            </div>

            {{-- NAV UTAMA (DESKTOP) --}}
            <nav class="hidden md:flex items-center gap-6 text-[11px] lg:text-sm font-semibold tracking-[0.25em] uppercase mt-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="pb-1 border-b-2 {{ $routeName === 'admin.dashboard' ? 'border-white' : 'border-transparent hover:border-white/80' }}">
                    Home
                </a>

                <a href="{{ route('admin.jadwal.index') }}"
                   class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.jadwal') ? 'border-white' : 'border-transparent hover:border-white/80' }}">
                    Jadwal
                </a>

                <a href="{{ route('admin.registrations.index') }}"
                   class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.registrations') ? 'border-white' : 'border-transparent hover:border-white/80' }}">
                    Approval
                </a>

                <a href="{{ route('admin.koreksi.index') }}"
                   class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.koreksi') ? 'border-white' : 'border-transparent hover:border-white/80' }}">
                    Absensi
                </a>

                <a href="{{ route('admin.laporan.index') }}"
                   class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.laporan') ? 'border-white' : 'border-transparent hover:border-white/80' }}">
                    Rekap Absen
                </a>

                @if (Auth::user()->role === 'Admin')
                    <a href="{{ route('admin.users.index') }}"
                       class="pb-1 border-b-2 {{ str_starts_with($routeName, 'admin.users') ? 'border-white text-cyan-200' : 'border-transparent hover:border-white/80 text-cyan-200' }}">
                        Management Akun
                    </a>
                @endif
            </nav>
        </div>

        {{-- KANAN: ICON USER + LOGOUT (DESKTOP) + HAMBURGER (MOBILE) --}}
        <div class="flex items-center gap-3">

            {{-- ICON USER (SELALU KELIHATAN) --}}
            <div class="w-9 h-9 rounded-full border border-white/80 flex items-center justify-center">
                <span class="text-lg font-semibold">👤</span>
            </div>

            {{-- LOGOUT (DESKTOP) --}}
            <form action="{{ route('logout') }}" method="POST" class="hidden md:block">
                @csrf
                <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-xs font-medium transition">
                    Logout
                </button>
            </form>

            {{-- HAMBURGER (MOBILE) --}}
            <button id="nav-toggle"
                    class="md:hidden inline-flex items-center justify-center p-2 rounded border border-white/60">
                <span class="sr-only">Toggle navigation</span>
                <div class="space-y-[3px]">
                    <span class="block w-4 h-0.5 bg-white"></span>
                    <span class="block w-4 h-0.5 bg-white"></span>
                    <span class="block w-4 h-0.5 bg-white"></span>
                </div>
            </button>
        </div>
    </div>

    {{-- NAV MOBILE (MUNCUL SAAT DITEKAN HAMBURGER) --}}
    <div id="mobile-menu" class="md:hidden hidden border-t border-white/20">
        <nav class="px-6 py-3 flex flex-col gap-2 text-xs font-semibold tracking-[0.25em] uppercase bg-blue-900/90">
            <a href="{{ route('admin.dashboard') }}" class="py-1">
                Home
            </a>
            <a href="{{ route('admin.jadwal.index') }}" class="py-1">
                Jadwal
            </a>
            <a href="{{ route('admin.registrations.index') }}" class="py-1">
                Approval
            </a>
            <a href="{{ route('admin.koreksi.index') }}" class="py-1">
                Absensi
            </a>
            <a href="{{ route('admin.laporan.index') }}" class="py-1">
                Rekap Absen
            </a>
            @if (Auth::user()->role === 'Admin')
                <a href="{{ route('admin.users.index') }}" class="py-1">
                    Management Akun
                </a>
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

<main class="max-w-6xl mx-auto px-6 lg:px-10 py-8">
    @yield('content')
</main>

<footer class="mt-10 text-center text-xs text-slate-500 py-4">
    &copy; {{ date('Y') }} Sistem Presensi Laboratorium
</footer>

{{-- JS KECIL UNTUK TOGGLE MENU MOBILE --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn  = document.getElementById('nav-toggle');
        const menu = document.getElementById('mobile-menu');

        if (btn && menu) {
            btn.addEventListener('click', function () {
                menu.classList.toggle('hidden');
            });
        }
    });
</script>

</body>
</html>
