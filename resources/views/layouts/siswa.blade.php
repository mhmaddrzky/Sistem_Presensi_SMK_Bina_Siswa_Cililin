<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Siswa | Sistem Presensi</title>
    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

{{-- ================= HEADER SECTION ================= --}}
<header class="bg-gradient-to-r from-blue-700 to-blue-500 text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 md:px-6 py-3 md:py-4">

        {{-- Baris pertama: Logo + Nama Sekolah + User/Hamburger --}}
        <div class="flex items-center justify-between gap-3">

            {{-- Logo dan Nama Sekolah --}}
            <div class="flex items-center gap-3 md:gap-4">
                <img src="{{ asset('assets/img/logo-smk.png') }}"
                     class="w-12 h-12 md:w-14 md:h-14 rounded-lg shadow-md object-contain"
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

            {{-- Kanan: Avatar (desktop) + Hamburger (mobile) --}}
            <div class="flex items-center gap-3">

                {{-- Avatar + dropdown: hanya di md ke atas --}}
                <div class="relative hidden md:block">
                    <button id="user-desktop-btn"
                            class="w-10 h-10 md:w-11 md:h-11 rounded-full bg-white text-blue-700
                                   flex items-center justify-center shadow-md hover:shadow-lg transition">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </button>

                    {{-- Dropdown user desktop --}}
                    <div id="user-desktop-dropdown"
                         class="hidden absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl py-2 z-50">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <p class="text-sm font-semibold text-gray-800">
                                {{ auth()->user()->siswa->nama ?? auth()->user()->username }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ '@' . auth()->user()->username }}
                            </p>
                        </div>
                        
                        {{-- MENU RIWAYAT PRESENSI --}}
                        <div class="px-2 py-2">
                            <a href="{{ route('siswa.riwayat.index') }}"
                               class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                </svg>
                                Riwayat Presensi
                            </a>
                        </div>
                        
                        <div class="border-t border-gray-200"></div>
                        
                        <form method="POST" action="{{ route('logout') }}" class="px-2 py-2">
                            @csrf
                            <button
                                class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"
                                          clip-rule="evenodd"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Hamburger: hanya di mobile --}}
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

        {{-- Baris kedua: NAV DESKTOP --}}
        <nav class="hidden md:block mt-3">
            <div
                class="bg-blue-600/30 backdrop-blur-sm rounded-t-xl md:rounded-2xl px-4 md:px-6 py-2 flex gap-6 md:gap-8 text-xs md:text-sm font-semibold">
                <a href="{{ route('siswa.dashboard') }}"
                   class="hover:underline transition
                        @if(request()->routeIs('siswa.dashboard')) underline font-bold @endif">
                    HOME
                </a>
                <a href="{{ route('siswa.presensi.form') }}"
                   class="hover:underline transition
                        @if(request()->routeIs('siswa.presensi.form')) underline font-bold @endif">
                    JADWAL
                </a>
                <a href="{{ route('siswa.presensi.index') }}"
                   class="hover:underline transition
                        @if(request()->routeIs('siswa.presensi.index')) underline font-bold @endif">
                    PRESENSI
                </a>
            </div>
        </nav>

        {{-- NAV MOBILE (muncul saat hamburger diklik) --}}
        <div id="mobile-menu" class="md:hidden hidden mt-3">
            <nav class="bg-blue-600/40 backdrop-blur-sm rounded-xl px-4 py-3 space-y-1 text-[11px] font-semibold">
                <a href="{{ route('siswa.dashboard') }}"
                   class="block rounded-lg px-3 py-2
                       {{ request()->routeIs('siswa.dashboard') ? 'bg-white/20' : 'hover:bg-white/10' }}">
                    HOME
                </a>
                <a href="{{ route('siswa.presensi.form') }}"
                   class="block rounded-lg px-3 py-2
                       {{ request()->routeIs('siswa.presensi.form') ? 'bg-white/20' : 'hover:bg-white/10' }}">
                    JADWAL
                </a>
                <a href="{{ route('siswa.presensi.index') }}"
                   class="block rounded-lg px-3 py-2
                       {{ request()->routeIs('siswa.presensi.index') ? 'bg-white/20' : 'hover:bg-white/10' }}">
                    PRESENSI
                </a>

                {{-- DIVIDER --}}
                <div class="border-t border-white/30 my-2"></div>

                {{-- MENU RIWAYAT DI MOBILE --}}
                <a href="{{ route('siswa.riwayat.index') }}"
                   class="block rounded-lg px-3 py-2 flex items-center gap-2
                       {{ request()->routeIs('siswa.riwayat.index') ? 'bg-white/20' : 'hover:bg-white/10' }}">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    Riwayat Presensi
                </a>

                {{-- Info user & logout di mobile --}}
                <div class="mt-2 pt-2 border-t border-white/30 text-[11px] text-white/90">
                    <p class="mb-1">
                        {{ auth()->user()->siswa->nama ?? auth()->user()->username }}
                        <span class="block text-[10px] text-white/70">
                            {{ '@' . auth()->user()->username }}
                        </span>
                    </p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full text-left rounded-lg px-3 py-2 text-[11px]
                                       text-red-100 hover:bg-red-500/70 hover:text-white">
                            Logout
                        </button>
                    </form>
                </div>
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
        // Desktop user dropdown
        const userBtn = document.getElementById('user-desktop-btn');
        const userDropdown = document.getElementById('user-desktop-dropdown');

        if (userBtn && userDropdown) {
            userBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!userDropdown.contains(e.target)) {
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

        // Auto refresh setiap 60 detik (kalau tetap ingin)
        setTimeout(function () {
            window.location.reload();
        }, 60000);
    });
</script>

</body>
</html>