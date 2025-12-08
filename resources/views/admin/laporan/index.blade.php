@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- JUDUL HALAMAN --}}
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-blue-900 flex items-center gap-2">
            📊 Rekap Laporan Presensi
        </h1>
        <p class="mt-1 text-sm text-slate-600">
            Laporan total sesi hadir siswa berdasarkan jurusan dan periode yang dipilih.
        </p>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="rounded-md border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 font-semibold">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-md border border-rose-300 bg-rose-50 px-4 py-3 text-sm text-rose-800 font-semibold">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- FORM FILTER --}}
    @php
        $labelJurusan = $jurusanFilter === 'all' ? 'Semua Jurusan' : $jurusanFilter;
        $labelPeriode = [
            'mingguan'    => 'Minggu Ini',
            'bulanan'     => 'Bulan Ini',
            'keseluruhan' => 'Keseluruhan',
        ][$periode] ?? 'Minggu Ini';
    @endphp

    <form action="{{ route('admin.laporan.index') }}" method="GET" id="form_laporan_filter">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 px-4 md:px-6 py-4 flex flex-col gap-4 md:flex-row md:items-start md:justify-between">

            {{-- FILTER JURUSAN (DROPDOWN CUSTOM) --}}
            <div class="w-full md:w-1/3">
                <label class="block text-sm font-semibold text-slate-700 mb-1">
                    Filter Berdasarkan Jurusan
                </label>

                <input type="hidden" name="jurusan_filter" id="jurusan_filter_input" value="{{ $jurusanFilter }}">

                <div class="relative" id="jurusanFilterWrapper">
                    <button type="button"
                            onclick="toggleJurusanFilter()"
                            class="w-full flex items-center justify-between gap-2 rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <span id="jurusan_filter_label" class="truncate">
                            {{ $labelJurusan }}
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                  clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div id="jurusan_filter_list"
                         class="absolute left-0 mt-1 hidden w-full overflow-hidden rounded-xl border border-slate-200 bg-white text-sm shadow-lg z-30">
                        <button type="button"
                                class="w-full px-3 py-2 text-left hover:bg-blue-50"
                                data-value="all" data-label="Semua Jurusan"
                                onclick="pickJurusanFilter(this)">
                            Semua Jurusan
                        </button>
                        <button type="button"
                                class="w-full px-3 py-2 text-left hover:bg-blue-50"
                                data-value="TKJ" data-label="TKJ"
                                onclick="pickJurusanFilter(this)">
                            TKJ
                        </button>
                        <button type="button"
                                class="w-full px-3 py-2 text-left hover:bg-blue-50"
                                data-value="TBSM" data-label="TBSM"
                                onclick="pickJurusanFilter(this)">
                            TBSM
                        </button>
                    </div>
                </div>
            </div>

            {{-- FILTER PERIODE (DROPDOWN CUSTOM) --}}
            <div class="w-full md:w-1/3">
                <label class="block text-sm font-semibold text-slate-700 mb-1">
                    Pilih Periode Laporan
                </label>

                <input type="hidden" name="periode" id="periode_input" value="{{ $periode }}">

                <div class="relative" id="periodeWrapper">
                    <button type="button"
                            onclick="togglePeriodeFilter()"
                            class="w-full flex items-center justify-between gap-2 rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <span id="periode_label" class="truncate">
                            {{ $labelPeriode }}
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                  clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div id="periode_list"
                         class="absolute left-0 mt-1 hidden w-full overflow-hidden rounded-xl border border-slate-200 bg-white text-sm shadow-lg z-30">
                        <button type="button"
                                class="w-full px-3 py-2 text-left hover:bg-blue-50"
                                data-value="mingguan" data-label="Minggu Ini"
                                onclick="pickPeriodeFilter(this)">
                            Minggu Ini
                        </button>
                        <button type="button"
                                class="w-full px-3 py-2 text-left hover:bg-blue-50"
                                data-value="bulanan" data-label="Bulan Ini"
                                onclick="pickPeriodeFilter(this)">
                            Bulan Ini
                        </button>
                        <button type="button"
                                class="w-full px-3 py-2 text-left hover:bg-blue-50"
                                data-value="keseluruhan" data-label="Keseluruhan"
                                onclick="pickPeriodeFilter(this)">
                            Keseluruhan
                        </button>
                    </div>
                </div>
            </div>

            {{-- INFO PERIODE AKTIF --}}
            <div class="w-full md:w-1/3 md:text-right">
                <p class="text-sm font-semibold text-blue-900">
                    Periode Aktif
                </p>
                <p class="mt-1 text-xs md:text-sm text-slate-600">
                    @if ($periode == 'mingguan')
                        Minggu ini (Senin – Minggu)
                    @elseif ($periode == 'bulanan')
                        Bulan ini
                    @else
                        Keseluruhan data
                    @endif
                    @if ($jurusanFilter !== 'all')
                        <br><span class="font-semibold">Jurusan: {{ $jurusanFilter }}</span>
                    @endif
                </p>
            </div>
        </div>
    </form>

    <p class="text-xs md:text-sm text-slate-500 italic">
        Laporan ini menampilkan <span class="font-semibold">Total Sesi Hadir</span> siswa sesuai filter yang dipilih.
    </p>

    {{-- TABEL LAPORAN --}}
    @if ($dataLaporan->isNotEmpty())

        <div class="flex items-center justify-between gap-2">
            <h2 class="text-lg md:text-xl font-semibold text-slate-800">
                Daftar Siswa
            </h2>
            <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">
                Total: {{ $dataLaporan->count() }} siswa
            </span>
        </div>

        <div class="mt-2 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left rounded-xl overflow-hidden">
            <thead class="bg-[#0B57D0] text-white rounded-xl">
                <tr>
                    <th class="px-4 py-3">NIS</th>
                    <th class="px-4 py-3">Nama Siswa</th>
                    <th class="px-4 py-3">Kelas</th>
                    <th class="px-4 py-3">Jurusan</th>
                    <th class="px-4 py-3 text-center">Total Sesi</th>
                </tr>
            </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($dataLaporan as $data)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-slate-800 whitespace-nowrap">
                                    {{ $data['nis'] }}
                                </td>
                                <td class="px-4 py-3 text-slate-900 whitespace-nowrap">
                                    <div class="font-medium">
                                        {{ $data['nama'] }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-slate-800 whitespace-nowrap">
                                    {{ $data['kelas'] }}
                                </td>
                                <td class="px-4 py-3 text-blue-900 font-semibold whitespace-nowrap">
                                    {{ $data['jurusan'] }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center rounded-full bg-cyan-50 px-3 py-1 text-xs font-semibold text-cyan-700">
                                        {{ $data['total_kehadiran_format'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- EXPORT BUTTON --}}
        <form action="{{ route('admin.laporan.export') }}" method="POST" class="mt-4">
            @csrf
            <input type="hidden" name="periode" value="{{ $periode }}">
            <input type="hidden" name="jurusan_filter" value="{{ $jurusanFilter }}">
            <button type="submit"
                    class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm
                           hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1">
                <i class="fas fa-file-csv mr-2"></i>
                Export Data ke CSV
            </button>
        </form>

    @else
        <div class="rounded-xl border border-dashed border-slate-300 bg-white px-6 py-10 text-center">
            <p class="text-sm text-slate-500">
                🚫 Tidak ada data presensi yang ditemukan untuk kriteria filter ini.
            </p>
        </div>
    @endif
</div>

{{-- SCRIPT DROPDOWN CUSTOM --}}
<script>
    // === JURUSAN ===
    function toggleJurusanFilter() {
        const list = document.getElementById('jurusan_filter_list');
        if (list) list.classList.toggle('hidden');
    }

    function pickJurusanFilter(btn) {
        const val   = btn.dataset.value;
        const label = btn.dataset.label;

        document.getElementById('jurusan_filter_input').value = val;
        document.getElementById('jurusan_filter_label').textContent = label;

        const list = document.getElementById('jurusan_filter_list');
        if (list) list.classList.add('hidden');

        document.getElementById('form_laporan_filter').submit();
    }

    // === PERIODE ===
    function togglePeriodeFilter() {
        const list = document.getElementById('periode_list');
        if (list) list.classList.toggle('hidden');
    }

    function pickPeriodeFilter(btn) {
        const val   = btn.dataset.value;
        const label = btn.dataset.label;

        document.getElementById('periode_input').value = val;
        document.getElementById('periode_label').textContent = label;

        const list = document.getElementById('periode_list');
        if (list) list.classList.add('hidden');

        document.getElementById('form_laporan_filter').submit();
    }

    // Tutup dropdown jika klik di luar
    document.addEventListener('click', function(e) {
        const jurusanWrapper = document.getElementById('jurusanFilterWrapper');
        const jurusanList    = document.getElementById('jurusan_filter_list');
        if (jurusanWrapper && jurusanList && !jurusanWrapper.contains(e.target)) {
            jurusanList.classList.add('hidden');
        }

        const periodeWrapper = document.getElementById('periodeWrapper');
        const periodeList    = document.getElementById('periode_list');
        if (periodeWrapper && periodeList && !periodeWrapper.contains(e.target)) {
            periodeList.classList.add('hidden');
        }
    });
</script>
@endsection
