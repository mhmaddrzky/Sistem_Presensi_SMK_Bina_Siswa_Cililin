@extends('layouts.siswa')

@section('content')
    {{-- ================= SECTION SAMBUTAN ================= --}}
    <div class="bg-white rounded-xl shadow-md p-8 mb-6">
        <div class="flex items-center justify-between">
            
            {{-- Konten teks di tengah --}}
            <div class="flex-1 text-center">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">
                    SELAMAT DATANG, <span class="text-blue-600">{{ strtoupper(auth()->user()->siswa->nama ?? auth()->user()->username) }}</span>
                </h1>
                {{-- Quote motivasi --}}
                <p class="text-gray-600 italic text-sm leading-relaxed">
                    My Father Always Told Me That All Businessmen Were Sons Of Bitches,<br>
                    But I Never Believed It Till Now<br>
                    <span class="text-gray-500">- John F. Kennedy</span>
                </p>
            </div>
            
            {{-- Ilustrasi SVG di kanan (hidden di mobile) --}}
            <div class="hidden md:block ml-6">
                <svg class="w-32 h-32" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    {{-- Matahari --}}
                    <circle cx="75" cy="25" r="15" fill="#FCD34D"/>
                    {{-- Awan --}}
                    <ellipse cx="70" cy="35" rx="8" ry="5" fill="#EC4899" opacity="0.6"/>
                    <ellipse cx="78" cy="35" rx="8" ry="5" fill="#EC4899" opacity="0.6"/>
                    {{-- Server/Database --}}
                    <rect x="35" y="45" width="30" height="35" rx="3" fill="#60A5FA"/>
                    <line x1="40" y1="52" x2="60" y2="52" stroke="white" stroke-width="1.5"/>
                    <line x1="40" y1="58" x2="60" y2="58" stroke="white" stroke-width="1.5"/>
                    <line x1="40" y1="64" x2="60" y2="64" stroke="white" stroke-width="1.5"/>
                    <line x1="40" y1="70" x2="60" y2="70" stroke="white" stroke-width="1.5"/>
                    {{-- Dekorasi bulat --}}
                    <circle cx="20" cy="70" r="8" fill="#EC4899" opacity="0.7"/>
                    <circle cx="85" cy="60" r="6" fill="#FCD34D" opacity="0.7"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- ================= ALERT SUCCESS (jika ada) ================= --}}
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg flex items-center">
        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span class="font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    {{-- ================= GRID 2 KOLOM (Pengumuman & Aktivitas) ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- CARD PENGUMUMAN --}}
        <div class="bg-white rounded-xl shadow-md p-6">
            {{-- Header card dengan icon --}}
            <div class="flex items-center gap-3 mb-4 pb-3 border-b-2 border-gray-200">
                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                    <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                </svg>
                <h2 class="text-xl font-bold text-gray-800">PENGUMUMAN</h2>
            </div>
            
            {{-- Konten pengumuman (bisa dinamis dari database) --}}
            <div class="text-gray-600 text-sm leading-relaxed">
                <p class="mb-2">🔔 Tidak ada pengumuman saat ini.</p>
                <p class="text-xs text-gray-400 italic">Periksa kembali nanti untuk informasi terbaru.</p>
            </div>
        </div>

        {{-- CARD AKTIVITAS SAYA --}}
        <div class="bg-white rounded-xl shadow-md p-6">
            {{-- Header card dengan icon --}}
            <div class="flex items-center gap-3 mb-4 pb-3 border-b-2 border-gray-200">
                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <h2 class="text-xl font-bold text-gray-800">AKTIVITAS SAYA</h2>
            </div>

            {{-- List aktivitas dengan link --}}
            <ul class="space-y-3">
                <li class="flex items-start gap-2">
                    <span class="text-blue-600 font-bold">•</span>
                    <a href="{{ route('siswa.presensi.form') }}" class="text-blue-600 hover:underline text-sm">
                        Jadwal 
                    </a>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-blue-600 font-bold">•</span>
                    <a href="{{ route('siswa.riwayat.index') }}" class="text-blue-600 hover:underline text-sm">
                        Kehadiran
                    </a>
                </li>
            </ul>
        </div>

    </div>

@endsection