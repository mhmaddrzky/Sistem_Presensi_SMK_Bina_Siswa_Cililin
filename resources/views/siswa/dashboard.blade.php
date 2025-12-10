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

            {{-- List aktivitas dengan collapsible sections --}}
            <div class="space-y-4">
                
                {{-- MATERI HARI INI --}}
                <div class="border border-gray-200 rounded-lg">
                    <button onclick="toggleSection('materiSection')" class="w-full flex items-center justify-between p-3 hover:bg-gray-50 transition-colors rounded-lg">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                            </svg>
                            <span class="font-semibold text-gray-800 text-sm">Materi Hari Ini</span>
                        </div>
                        <svg id="materiArrow" class="w-5 h-5 text-gray-500 transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    
                    <div id="materiSection" class="hidden border-t border-gray-200">
                        @if(!$jadwalTerdekat)
                            <div class="p-4 text-center">
                                <p class="text-gray-500 text-sm">Tidak ada jadwal untuk hari ini</p>
                            </div>
                        @else
                            <a href="{{ route('siswa.presensi.form') }}" class="block p-4 hover:bg-blue-50 transition-colors">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-800 text-base mb-1">{{ $jadwalTerdekat->mata_pelajaran }}</p>
                                        <p class="text-sm text-gray-600 mb-2">{{ $jadwalTerdekat->nama_guru }}</p>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="10"/>
                                                <polyline points="12 6 12 12 16 14"/>
                                            </svg>
                                            <span class="text-sm text-gray-600">{{ substr($jadwalTerdekat->waktu_mulai, 0, 5) }} - {{ substr($jadwalTerdekat->waktu_selesai, 0, 5) }}</span>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- KEHADIRAN --}}
                <div class="border border-gray-200 rounded-lg">
                    <button onclick="toggleSection('kehadiranSection')" class="w-full flex items-center justify-between p-3 hover:bg-gray-50 transition-colors rounded-lg">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                            <span class="font-semibold text-gray-800 text-sm">Kehadiran</span>
                        </div>
                        <svg id="kehadiranArrow" class="w-5 h-5 text-gray-500 transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    
                    <div id="kehadiranSection" class="hidden border-t border-gray-200">
                        @if(!$presensiTerbaru)
                            <div class="p-4 text-center">
                                <p class="text-gray-500 text-sm">Belum ada riwayat presensi</p>
                            </div>
                        @else
                            @php
                                $statusColor = 'text-gray-700';
                                $statusBg = 'bg-gray-100';
                                
                                if ($presensiTerbaru->status == 'Hadir') {
                                    $statusColor = 'text-green-700';
                                    $statusBg = 'bg-green-100';
                                } elseif ($presensiTerbaru->status == 'Sakit' || $presensiTerbaru->status == 'Izin') {
                                    $statusColor = 'text-yellow-700';
                                    $statusBg = 'bg-yellow-100';
                                } elseif ($presensiTerbaru->status == 'Alfa') {
                                    $statusColor = 'text-red-700';
                                    $statusBg = 'bg-red-100';
                                }
                            @endphp
                            <a href="{{ route('siswa.riwayat.index') }}" class="block p-4 hover:bg-blue-50 transition-colors">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-800 text-base mb-1">
                                            {{ $presensiTerbaru->jadwal->mata_pelajaran ?? 'Jadwal Dihapus' }}
                                        </p>
                                        <p class="text-sm text-gray-600 mb-2">
                                            {{ \Carbon\Carbon::parse($presensiTerbaru->tanggal)->format('d/m/Y') }} • {{ substr($presensiTerbaru->waktu, 0, 5) }}
                                        </p>
                                        <span class="{{ $statusBg }} {{ $statusColor }} text-xs font-semibold px-2 py-1 rounded-full inline-block">
                                            {{ $presensiTerbaru->status }}
                                        </span>
                                    </div>
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- JavaScript untuk Toggle Sections --}}
    <script>
    function toggleSection(sectionId) {
        const section = document.getElementById(sectionId);
        const arrow = document.getElementById(sectionId.replace('Section', 'Arrow'));
        
        if (section.classList.contains('hidden')) {
            section.classList.remove('hidden');
            arrow.style.transform = 'rotate(180deg)';
        } else {
            section.classList.add('hidden');
            arrow.style.transform = 'rotate(0deg)';
        }
    }
    </script>

@endsection