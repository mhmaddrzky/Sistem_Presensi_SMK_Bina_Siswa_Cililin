@extends('layouts.siswa')

@section('content')
    {{-- ================= SECTION SAMBUTAN (TIDAK BERUBAH) ================= --}}
    <div class="bg-white rounded-xl shadow-md p-8 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex-1 text-center">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">
                    SELAMAT DATANG, <span class="text-blue-600">{{ strtoupper(auth()->user()->siswa->nama ?? auth()->user()->username) }}</span>
                </h1>
                <p class="text-gray-600 italic text-sm leading-relaxed">
                    My Father Always Told Me That All Businessmen Were Sons Of Bitches,<br>
                    But I Never Believed It Till Now<br>
                    <span class="text-gray-500">- John F. Kennedy</span>
                </p>
            </div>
            <div class="hidden md:block ml-6">
                <svg class="w-32 h-32" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="75" cy="25" r="15" fill="#FCD34D"/>
                    <ellipse cx="70" cy="35" rx="8" ry="5" fill="#EC4899" opacity="0.6"/>
                    <ellipse cx="78" cy="35" rx="8" ry="5" fill="#EC4899" opacity="0.6"/>
                    <rect x="35" y="45" width="30" height="35" rx="3" fill="#60A5FA"/>
                    <line x1="40" y1="52" x2="60" y2="52" stroke="white" stroke-width="1.5"/>
                    <line x1="40" y1="58" x2="60" y2="58" stroke="white" stroke-width="1.5"/>
                    <line x1="40" y1="64" x2="60" y2="64" stroke="white" stroke-width="1.5"/>
                    <line x1="40" y1="70" x2="60" y2="70" stroke="white" stroke-width="1.5"/>
                    <circle cx="20" cy="70" r="8" fill="#EC4899" opacity="0.7"/>
                    <circle cx="85" cy="60" r="6" fill="#FCD34D" opacity="0.7"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- ================= ALERT SUCCESS ================= --}}
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg flex items-center">
        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span class="font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    {{-- ================= GRID UTAMA (EQUAL HEIGHT) ================= --}}
    {{-- 'items-stretch' memastikan kedua kolom tingginya dipaksa sama --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
        
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        {{-- CARD 1: STATISTIK SAYA --}}
        {{-- 'flex flex-col h-full' memastikan card mengisi seluruh ruang tinggi yang tersedia --}}
        <div class="bg-white rounded-xl shadow-md p-6 flex flex-col h-full">
            
            {{-- HEADER: Agar garis sejajar, struktur header disamakan persis --}}
            <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-100 h-20">
                <div class="p-2 bg-blue-50 rounded-lg flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800 leading-tight">STATISTIK</h2>
                    <p class="text-xs text-gray-500 mt-1">Ringkasan performa semester ini</p>
                </div>
            </div>
            
            {{-- CONTENT: Menggunakan flex-1 agar mengisi sisa ruang --}}
            <div class="flex flex-col sm:flex-row items-center gap-6 flex-1 justify-center">
                
                {{-- Grafik Donat --}}
                <div class="relative w-36 h-36 flex-shrink-0">
                    <canvas id="attendanceChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-xl font-bold text-gray-800">{{ $persentaseHadir }}%</span>
                        <span class="text-[10px] text-gray-500 uppercase tracking-wider">Hadir</span>
                    </div>
                </div>

                {{-- Detail Legend (Kotak-kotak berwarna sesuai request 'sebelumnya') --}}
                {{-- Grid 2 kolom yang rapi --}}
                <div class="w-full grid grid-cols-2 gap-3">
                    
                    {{-- Item Hadir --}}
                    <div class="bg-green-50 p-3 rounded-lg border border-green-100 hover:shadow-sm transition-shadow">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            <span class="text-xs text-gray-600 font-medium">Hadir</span>
                        </div>
                        <p class="text-lg font-bold text-green-700">{{ $hadir }}</p>
                    </div>

                    {{-- Item Izin --}}
                    <div class="bg-blue-50 p-3 rounded-lg border border-blue-100 hover:shadow-sm transition-shadow">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            <span class="text-xs text-gray-600 font-medium">Izin</span>
                        </div>
                        <p class="text-lg font-bold text-blue-700">{{ $izin }}</p>
                    </div>

                    {{-- Item Sakit --}}
                    <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-100 hover:shadow-sm transition-shadow">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                            <span class="text-xs text-gray-600 font-medium">Sakit</span>
                        </div>
                        <p class="text-lg font-bold text-yellow-700">{{ $sakit }}</p>
                    </div>

                    {{-- Item Alpha --}}
                    <div class="bg-red-50 p-3 rounded-lg border border-red-100 hover:shadow-sm transition-shadow">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-2 h-2 rounded-full bg-red-500"></span>
                            <span class="text-xs text-gray-600 font-medium">Alpha</span>
                        </div>
                        <p class="text-lg font-bold text-red-700">{{ $alpha }}</p>
                    </div>

                </div>
            </div>
        </div>

        {{-- CARD 2: AKTIVITAS SAYA --}}
        <div class="bg-white rounded-xl shadow-md p-6 flex flex-col h-full">
            
            {{-- HEADER: Struktur sama persis dengan Statistik, termasuk tinggi (h-20) --}}
            <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-100 h-20">
                <div class="p-2 bg-blue-50 rounded-lg flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800 leading-tight">AKTIVITAS SAYA</h2>
                    <p class="text-xs text-gray-500 mt-1">Agenda & presensi terbaru</p> {{-- Ditambahkan dummy subtitle agar alignment 100% sama --}}
                </div>
            </div>

            {{-- List aktivitas --}}
            <div class="space-y-4 flex-1">
                
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
                                } elseif ($presensiTerbaru->status == 'Izin') {
                                    $statusColor = 'text-blue-700'; 
                                    $statusBg = 'bg-blue-100';
                                } elseif ($presensiTerbaru->status == 'Sakit') {
                                    $statusColor = 'text-yellow-700';
                                    $statusBg = 'bg-yellow-100';
                                } elseif ($presensiTerbaru->status == 'Alfa') {
                                    $statusColor = 'text-red-700';
                                    $statusBg = 'bg-red-100';
                                }

                                $namaHari = $presensiTerbaru->jadwal->hari ?? '-';
                                $tanggalFormatted = \Carbon\Carbon::parse($presensiTerbaru->tanggal)->format('d/m/Y');
                            @endphp
                            <a href="{{ route('siswa.riwayat.index') }}" class="block p-4 hover:bg-blue-50 transition-colors">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-800 text-base mb-1">
                                            {{ $presensiTerbaru->jadwal->mata_pelajaran ?? 'Jadwal Dihapus' }}
                                        </p>
                                        <p class="text-sm text-gray-600 mb-2">
                                            {{ $namaHari }}, {{ $tanggalFormatted }} • {{ substr($presensiTerbaru->waktu, 0, 5) }}
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

    {{-- Javascript (TIDAK BERUBAH) --}}
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('attendanceChart').getContext('2d');
            
            const dataHadir = {{ $hadir }};
            const dataIzin = {{ $izin }};
            const dataSakit = {{ $sakit }};
            const dataAlpha = {{ $alpha }};

            const totalData = dataHadir + dataIzin + dataSakit + dataAlpha;
            const chartData = totalData === 0 ? [1] : [dataHadir, dataIzin, dataSakit, dataAlpha];
            // Warna disesuaikan agar sama dengan kotak-kotak legend (Tailwind colors)
            const chartColors = totalData === 0 
                ? ['#E5E7EB'] 
                : ['#22c55e', '#3b82f6', '#facc15', '#ef4444']; 

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Hadir', 'Izin', 'Sakit', 'Alpha'],
                    datasets: [{
                        data: chartData,
                        backgroundColor: chartColors,
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: totalData !== 0 }
                    }
                }
            });
        });
    </script>

@endsection