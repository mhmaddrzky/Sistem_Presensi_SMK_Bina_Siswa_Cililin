@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-blue-900 flex items-center gap-3">
            📚 Manajemen Pembagian Sesi Siswa (Kuota)
        </h1>
        <p class="text-slate-600 text-sm md:text-base mt-1">
            Pilih jadwal dan tentukan siswa mana yang masuk ke sesi tersebut.
        </p>
    </div>

    {{-- Notifikasi System --}}
    @if(session('success'))
        <div class="p-3 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 text-sm font-semibold flex items-center gap-2">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-3 rounded-lg bg-rose-50 text-rose-800 border border-rose-200 text-sm font-semibold flex items-center gap-2">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- Filter Jurusan --}}
    @php
        $labelJurusan = 'Semua Siswa';
        if ($jurusanFilter === 'TKJ')  $labelJurusan = 'TKJ';
        if ($jurusanFilter === 'TBSM') $labelJurusan = 'TBSM';
    @endphp

    <div class="bg-white shadow-sm border border-slate-200 rounded-xl px-5 py-4">
        {{-- Form Filter Jurusan --}}
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="font-semibold text-slate-700">Filter Jurusan</p>
                <p class="text-xs text-slate-500 mt-1">Pilih jurusan untuk mempermudah pemilihan siswa.</p>
            </div>

            <div class="relative w-full md:w-56" id="jurusanFilterWrapper">
                {{-- Hidden Inputs untuk Logic Filter --}}
                <form id="filterForm" action="{{ route('admin.sesi.index') }}" method="GET">
                    <input type="hidden" name="jurusan_filter" id="jurusan_filter_input" value="{{ $jurusanFilter }}">
                    <input type="hidden" name="search" id="hiddenSearchInput" value="{{ request('search') }}">
                </form>

                <button type="button" onclick="toggleJurusanFilter()"
                    class="w-full flex items-center justify-between gap-2 px-3 py-2.5 border rounded-lg bg-slate-50 hover:bg-slate-100 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <span id="jurusan_filter_label" class="truncate">{{ $labelJurusan }}</span>
                    <svg class="h-4 w-4 text-slate-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" /></svg>
                </button>
                <div id="jurusan_filter_list" class="absolute left-0 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden hidden z-30 text-sm">
                    <button type="button" class="w-full text-left px-3 py-2 hover:bg-blue-50" data-value="all" data-label="Semua Siswa" onclick="pickJurusanFilter(this)">Semua Siswa</button>
                    <button type="button" class="w-full text-left px-3 py-2 hover:bg-blue-50" data-value="TKJ" data-label="TKJ" onclick="pickJurusanFilter(this)">TKJ</button>
                    <button type="button" class="w-full text-left px-3 py-2 hover:bg-blue-50" data-value="TBSM" data-label="TBSM" onclick="pickJurusanFilter(this)">TBSM</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Simpan Sesi --}}
    <form action="{{ route('admin.sesi.store') }}" method="POST" class="space-y-6">
    @csrf

        {{-- Card Jadwal --}}
        <div class="bg-white shadow-sm border border-slate-200 rounded-xl px-5 py-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <p class="font-semibold text-slate-700">1. Pilih Jadwal/Sesi</p>
                    <p class="text-xs text-slate-500 mt-1">Pilih jadwal praktikum yang akan diisi oleh siswa.</p>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-3 mt-4">
                {{-- Dropdown Jadwal --}}
                <div class="relative w-full md:flex-1" id="jadwalDropdownSesi">
                    <input type="hidden" name="jadwal_id" id="jadwal_id" value="{{ old('jadwal_id') }}">
                    @php
                        $selectedJadwalLabel = '-- Pilih Jadwal --';
                        if (old('jadwal_id')) {
                            $j = $jadwals->firstWhere('id', old('jadwal_id'));
                            if($j) $selectedJadwalLabel = '['.$j->jurusan.'] '.$j->hari.' | '.$j->mata_pelajaran;
                        }
                    @endphp
                    <button type="button" onclick="toggleJadwalListSesi()" class="p-3 border border-slate-300 rounded-lg w-full bg-white flex items-center justify-between gap-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <span id="jadwal_selected_label_sesi" class="truncate">{{ $selectedJadwalLabel }}</span>
                        <svg class="h-4 w-4 text-slate-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" /></svg>
                    </button>
                    <div id="jadwal_list_sesi" class="absolute left-0 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 overflow-y-auto hidden z-30 text-sm">
                        @foreach ($jadwals as $jadwal)
                            <button type="button" class="w-full text-left px-3 py-2 hover:bg-blue-50 text-slate-700"
                                data-id="{{ $jadwal->id }}"
                                data-label="[{{ $jadwal->jurusan }}] {{ $jadwal->hari }} | {{ $jadwal->mata_pelajaran }}"
                                onclick="pickJadwalSesi(this)">
                                [{{ $jadwal->jurusan }}] {{ $jadwal->hari }}, {{ substr($jadwal->waktu_mulai,0,5) }} | {{ $jadwal->mata_pelajaran }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Tombol Muat Siswa --}}
                <button type="button" onclick="loadSessionData()" class="px-5 py-3 bg-[#0D47C9] text-white rounded-lg hover:bg-blue-800 shadow-md text-sm font-semibold transition-all">
                    Muat Siswa
                </button>
            </div>
        </div>

        {{-- Tabel Siswa Wrapper (ID ini penting untuk AJAX) --}}
        <div id="studentTableContainer" class="bg-white shadow-sm border border-slate-200 rounded-xl overflow-hidden">
            <div class="px-5 py-3 border-b border-slate-200 bg-slate-50/50">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 w-full">
                    
                    {{-- SEARCH BAR (Live Search) --}}
                    <div class="relative w-full md:w-80">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" id="liveSearchInput" 
                            value="{{ request('search') }}"
                            class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-white" 
                            placeholder="Cari Nama/Kelas...">
                    </div>

                    {{-- FITUR PILIH SEMUA --}}
                    <div class="flex items-center gap-2 bg-blue-50 px-4 py-2 rounded-lg border border-blue-100">
                        <input type="checkbox" id="externalSelectAll" onclick="toggleExternalSelectAll()"
                            class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer">
                        <label for="externalSelectAll" class="text-sm font-semibold text-blue-800 cursor-pointer select-none">
                            Pilih Semua
                        </label>
                        <span id="selected_count_display" class="ml-2 text-xs font-bold text-blue-600 hidden">
                            (<span id="selected_count">0</span>)
                        </span>
                    </div>
                </div>
            </div>

            {{-- CONTENT TABLE --}}
            <div id="tableContent">
                <div class="overflow-x-auto w-full">
                    <table class="min-w-full divide-y divide-gray-200 text-xs md:text-sm" id="tableSiswa">
                        <thead class="bg-[#0D47C9] text-white">
                            <tr>
                                <th class="p-3 text-center font-semibold w-16">Pilih</th>
                                <th class="p-3 text-left font-semibold">NIS</th>
                                <th class="p-3 text-left font-semibold">Nama</th>
                                <th class="p-3 text-left font-semibold">Kelas</th>
                                <th class="p-3 text-left font-semibold">Jurusan</th>
                                <th class="p-3 text-left font-semibold">Status Sesi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($siswas as $siswa)
                            <tr class="hover:bg-slate-50 transition-colors student-row">
                                <td class="p-3 text-center">
                                    <input type="checkbox"
                                        name="siswa_ids[]"
                                        value="{{ $siswa->id }}"
                                        data-siswa-id="{{ $siswa->id }}"
                                        class="siswa-checkbox w-4 h-4 md:w-5 md:h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                        onchange="updateCounterOnly()">
                                </td>
                                <td class="p-3 text-slate-700">{{ $siswa->nis }}</td>
                                <td class="p-3 text-slate-800 font-medium">{{ $siswa->nama }}</td>
                                <td class="p-3 text-slate-600">{{ $siswa->kelas }}</td>
                                <td class="p-3"><span class="bg-slate-100 px-2 py-1 rounded text-slate-700">{{ $siswa->jurusan }}</span></td>
                                <td class="p-3" id="status-{{ $siswa->id }}">
                                    <span class="text-slate-400 italic text-xs">Belum dimuat</span>
                                </td>
                            </tr>
                            @endforeach

                            @if ($siswas->count() == 0)
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-500">
                                    @if(request('search')) Tidak ditemukan data untuk "<strong>{{ request('search') }}</strong>". @else Data tidak tersedia. @endif
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

        <div>
            <button type="submit" class="px-6 py-3 bg-emerald-600 text-white rounded-lg shadow-md hover:bg-emerald-700 transition font-semibold w-full md:w-auto">
                Simpan Pembagian Sesi
            </button>
        </div>

    </form>
</div>

{{-- ========== MODAL CUSTOM "PERHATIAN" ========== --}}
<div id="customModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-40 transition-opacity" onclick="closeModal()"></div>
    <div class="bg-white rounded-xl shadow-2xl w-11/12 md:w-96 transform transition-all p-6 relative z-10">
        <div class="text-left">
            <h3 class="text-lg font-bold text-slate-900 mb-2" id="modalTitle">Perhatian</h3>
            <p class="text-sm text-slate-600 mb-6" id="modalMessage">Silakan pilih jadwal terlebih dahulu!</p>
            <div class="flex justify-end">
                <button type="button" onclick="closeModal()"
                    class="w-full bg-emerald-600 text-white font-medium py-2.5 px-4 rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-colors">
                    Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const mappingData = @json($mappingSesi);
    const allJadwals = @json($jadwals->keyBy('id'));

    // ===== 1. LIVE SEARCH LOGIC (AJAX) =====
    let searchTimeout = null;

    document.getElementById('liveSearchInput').addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        const query = this.value;
        const jurusan = document.getElementById('jurusan_filter_input').value;

        // Tunggu 500ms agar tidak request setiap ketik huruf
        searchTimeout = setTimeout(() => {
            fetchData(query, jurusan);
        }, 500);
    });

    function fetchData(search, jurusan) {
        const url = new URL(window.location.href);
        url.searchParams.set('search', search);
        url.searchParams.set('jurusan_filter', jurusan);
        url.searchParams.set('page', 1);

        window.history.pushState({}, '', url);

        fetch(url)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.getElementById('tableContent').innerHTML;
                document.getElementById('tableContent').innerHTML = newContent;
                
                // Reset checkbox select all
                document.getElementById('externalSelectAll').checked = false;
                loadSessionData(); 
            })
            .catch(err => console.error('Gagal mengambil data:', err));
    }

    // ===== 2. MODAL LOGIC =====
    function showModal(title, message) {
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalMessage').textContent = message;
        document.getElementById('customModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('customModal').classList.add('hidden');
    }

    // ===== 3. STANDARD FUNCTIONS =====
    function toggleExternalSelectAll() {
        const master = document.getElementById('externalSelectAll');
        const checkboxes = document.querySelectorAll('.siswa-checkbox');
        checkboxes.forEach(cb => {
            if (!cb.disabled) cb.checked = master.checked;
        });
        updateCounterOnly();
    }
    
    function updateCounterOnly() {
        const checkedCount = document.querySelectorAll('.siswa-checkbox:checked').length;
        const countDisplay = document.getElementById('selected_count');
        const countWrapper = document.getElementById('selected_count_display');
        
        if (countDisplay) countDisplay.textContent = checkedCount;
        if (countWrapper) {
            if (checkedCount > 0) countWrapper.classList.remove('hidden');
            else countWrapper.classList.add('hidden');
        }
    }

    function toggleJurusanFilter() { document.getElementById('jurusan_filter_list').classList.toggle('hidden'); }
    function toggleJadwalListSesi() { document.getElementById('jadwal_list_sesi').classList.toggle('hidden'); }
    
    function pickJurusanFilter(btn) {
        document.getElementById('jurusan_filter_input').value = btn.dataset.value;
        document.getElementById('jurusan_filter_label').textContent = btn.dataset.label;
        document.getElementById('jurusan_filter_list').classList.add('hidden');
        

        fetchData(document.getElementById('liveSearchInput').value, btn.dataset.value);
    }

    function pickJadwalSesi(btn) {
        document.getElementById('jadwal_id').value = btn.dataset.id;
        document.getElementById('jadwal_selected_label_sesi').textContent = btn.dataset.label;
        document.getElementById('jadwal_list_sesi').classList.add('hidden');
        loadSessionData();
    }

    document.addEventListener('click', function(e) {
        const jw = document.getElementById('jurusanFilterWrapper');
        const jl = document.getElementById('jurusan_filter_list');
        if (jw && jl && !jw.contains(e.target)) jl.classList.add('hidden');

        const sw = document.getElementById('jadwalDropdownSesi');
        const sl = document.getElementById('jadwal_list_sesi');
        if (sw && sl && !sw.contains(e.target)) sl.classList.add('hidden');
    });

    // ===== 4. LOAD SESSION DATA =====
    function loadSessionData() {
        document.querySelectorAll('[id^="status-"]').forEach(el => {
            el.innerHTML = '<span class="text-slate-400 italic text-xs">Tidak terdaftar</span>';
        });

        const selectedJadwalId = document.getElementById('jadwal_id').value;
        
        if (!selectedJadwalId) {
  
            if (window.event && window.event.type === 'click' && window.event.target.innerText.trim() === 'Muat Siswa') {
                showModal('Perhatian', 'Silakan pilih jadwal terlebih dahulu!');
            }
            return;
        }

        document.querySelectorAll('.siswa-checkbox').forEach(checkbox => {
            const siswaId = checkbox.dataset.siswaId;
            for (const jadwalId in mappingData) {
                const found = mappingData[jadwalId].find(m => m.siswa_id == siswaId);
                if (found) {
                    const status = document.getElementById('status-' + siswaId);
                    if (jadwalId == selectedJadwalId) {
                        checkbox.checked = true;
                        status.innerHTML = '<span class="text-blue-600 font-bold text-xs">Sesi ini</span>';
                    } else {
                        const j = allJadwals[jadwalId];
                        if (j) {
                            status.innerHTML = `<span class="text-orange-600 font-medium text-xs">Terdaftar di ${j.mata_pelajaran}</span>`;
                        }
                    }
                }
            }
        });
        updateCounterOnly();
    }
</script>

@endsection