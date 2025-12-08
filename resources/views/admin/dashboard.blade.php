@extends('layouts.admin')

@section('content')
    @php
        $user = Auth::user();
        $role = $user->role;
        $adminDetail = $user->admin ?? (object)['nama' => $user->username];
        $namaSapaan = $adminDetail->nama ?? $user->username;

        if ($role === 'Guru') {
            $welcomeText = "Selamat Datang, Guru.";
        } elseif ($role === 'AsistenLab') {
            $welcomeText = "Selamat Datang, Asisten Lab.";
        } elseif ($role === 'Admin') {
            $welcomeText = "Selamat Datang, Administrator Utama.";
        } else {
            $welcomeText = "Selamat Datang, $namaSapaan!";
        }

        // fallback angka sementara
        $pendingApproval = $pendingApproval ?? 0;
        $hadirHariIni    = $hadirHariIni ?? 0;
        $sakitHariIni    = $sakitHariIni ?? 0;
        $izinHariIni     = $izinHariIni ?? 0;
        $alphaHariIni    = $alphaHariIni ?? 0;
        $totalSesiHariIni = $totalSesiHariIni ?? 0;

        $totalStatus = $hadirHariIni + $sakitHariIni + $izinHariIni + $alphaHariIni;
        $totalStatus = $totalStatus > 0 ? $totalStatus : 1;

        $wHadir = $hadirHariIni / $totalStatus * 100;
        $wSakit = $sakitHariIni / $totalStatus * 100;
        $wIzin  = $izinHariIni  / $totalStatus * 100;
        $wAlpha = $alphaHariIni / $totalStatus * 100;
    @endphp

    {{-- Judul --}}
    <h1 class="text-2xl font-semibold text-blue-900 mb-1">
        {{ $welcomeText }}
    </h1>
    <p class="text-sm text-slate-500 mb-5">Ringkasan aktivitas hari ini.</p>

    <hr class="border-slate-200 mb-6">

    {{-- GRID CARD --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

        {{-- CARD: Siswa Pending Approval (Tampil untuk semua role) --}}
<div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 flex flex-col gap-3">
    <div class="flex items-center justify-between">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
            Siswa Menunggu Approval
        </p>

        {{-- ICON USER-PLUS --}}
        <div class="w-8 h-8 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 4.5c1.657 0 3 1.343 3 3s-1.343 3-3 3-3-1.343-3-3 1.343-3 3-3zM19 8v6m3-3h-6M6 20v-1c0-2.761 2.239-5 5-5h2" />
            </svg>
        </div>
    </div>

    <p class="text-4xl font-semibold {{ $pendingApproval > 0 ? 'text-amber-500' : 'text-emerald-600' }}">
        {{ $pendingApproval }}
    </p>

    <p class="text-[11px] text-slate-500">
        Pendaftaran baru yang menunggu verifikasi.
    </p>
</div>


        {{-- CARD: Kehadiran Hari Ini --}}
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                    Kehadiran Hari Ini
                </p>

                {{-- ICON CHECK-CIRCLE --}}
                <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <p class="text-4xl font-semibold text-blue-700">
                {{ $hadirHariIni }}
            </p>

            <p class="text-[11px] text-slate-500">
                Total siswa yang hadir pada semua sesi hari ini.
            </p>
        </div>

        {{-- CARD: Sesi Lab Hari Ini --}}
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                    Sesi Lab Hari Ini
                </p>

                {{-- ICON BEAKER --}}
                <div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 2v6l-5 9c0 2.209 1.791 4 4 4h8c2.209 0 4-1.791 4-4l-5-9V2" />
                    </svg>
                </div>
            </div>

            <p class="text-4xl font-semibold text-indigo-600">
                {{ $totalSesiHariIni }}
            </p>

            <p class="text-[11px] text-slate-500">
                Total sesi yang berlangsung hari ini.
            </p>
        </div>

    </div>

    {{-- DISTRIBUSI STATUS --}}
    <div class="mt-10 bg-white border border-slate-200 rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-slate-800 mb-3 flex items-center gap-2">
            
            {{-- ICON CHART-BAR --}}
            <span class="w-7 h-7 bg-slate-200 text-slate-700 rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 3v18h18M8 17v-4m4 4V7m4 10v-6" />
                </svg>
            </span>

            Distribusi Status Kehadiran Hari Ini
        </h3>

        {{-- Bar Graph --}}
        <div class="mt-4 h-3 w-full rounded-full bg-slate-100 overflow-hidden flex">
            <div class="h-full bg-emerald-500" style="width: {{ $wHadir }}%"></div>
            <div class="h-full bg-amber-400" style="width: {{ $wSakit }}%"></div>
            <div class="h-full bg-orange-400" style="width: {{ $wIzin }}%"></div>
            <div class="h-full bg-rose-500" style="width: {{ $wAlpha }}%"></div>
        </div>

        {{-- Legend --}}
        <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-2 text-[11px]">
            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Hadir ({{ $hadirHariIni }})</div>
            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-amber-400"></span> Sakit ({{ $sakitHariIni }})</div>
            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-orange-400"></span> Izin ({{ $izinHariIni }})</div>
            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-rose-500"></span> Alpha ({{ $alphaHariIni }})</div>
        </div>
    </div>

@endsection
