@extends('layouts.admin')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center gap-3">
            📚 Manajemen Pembagian Sesi Siswa (Kuota)
        </h1>
        <p class="text-gray-500 text-sm md:text-base mt-1">
            Pilih jadwal dan tentukan siswa mana yang masuk ke sesi tersebut (maks. 20 siswa).
        </p>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="p-3 rounded-lg bg-green-100 text-green-800 border border-green-300">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-3 rounded-lg bg-red-100 text-red-800 border border-red-300">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- Filter Jurusan --}}
    @php
        $labelJurusan = 'Semua Siswa';
        if ($jurusanFilter === 'TKJ')  $labelJurusan = 'TKJ';
        if ($jurusanFilter === 'TBSM') $labelJurusan = 'TBSM';
    @endphp

    <div class="bg-white shadow-md border rounded-xl px-5 py-4">
        <form action="{{ route('admin.sesi.index') }}" method="GET"
              class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

            <div>
                <p class="font-semibold text-gray-700">Filter Jurusan</p>
                <p class="text-xs text-gray-500 mt-1">
                    Pilih jurusan untuk mempermudah pemilihan siswa.
                </p>
            </div>

            <div class="flex items-center gap-3">
                {{-- DROPDOWN CUSTOM FILTER JURUSAN --}}
                <div class="relative w-full md:w-56" id="jurusanFilterWrapper">
                    <input type="hidden" name="jurusan_filter" id="jurusan_filter_input" value="{{ $jurusanFilter }}">

                    <button type="button"
                        onclick="toggleJurusanFilter()"
                        class="w-full flex items-center justify-between gap-2 px-3 py-2.5 border rounded-lg bg-gray-50 hover:bg-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <span id="jurusan_filter_label" class="truncate text-gray-700">
                            {{ $labelJurusan }}
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                  clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div id="jurusan_filter_list"
                        class="absolute left-0 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-xl overflow-hidden hidden z-30 text-sm">
                        <button type="button" class="w-full text-left px-3 py-2 hover:bg-blue-50"
                            data-value="all" data-label="Semua Siswa"
                            onclick="pickJurusanFilter(this)">
                            Semua Siswa
                        </button>
                        <button type="button" class="w-full text-left px-3 py-2 hover:bg-blue-50"
                            data-value="TKJ" data-label="TKJ"
                            onclick="pickJurusanFilter(this)">
                            TKJ
                        </button>
                        <button type="button" class="w-full text-left px-3 py-2 hover:bg-blue-50"
                            data-value="TBSM" data-label="TBSM"
                            onclick="pickJurusanFilter(this)">
                            TBSM
                        </button>
                    </div>
                </div>

                @if ($jurusanFilter !== 'all')
                    <span class="hidden md:inline text-blue-700 text-sm font-medium">
                        Menampilkan: {{ $jurusanFilter }}
                    </span>
                @endif
            </div>
        </form>
    </div>

    <form action="{{ route('admin.sesi.store') }}" method="POST" class="space-y-6">
    @csrf

        {{-- Card Jadwal --}}
        <div class="bg-white shadow-md border rounded-xl px-5 py-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <p class="font-semibold text-gray-700">1. Pilih Jadwal/Sesi</p>
                    <p class="text-xs text-gray-500 mt-1">
                        Pilih jadwal praktikum yang akan diisi oleh siswa.
                    </p>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-3 mt-4">

                {{-- DROPDOWN CUSTOM JADWAL --}}
                <div class="relative w-full md:flex-1" id="jadwalDropdownSesi">
                    {{-- input asli untuk dikirim ke backend --}}
                    <input type="hidden" name="jadwal_id" id="jadwal_id" value="{{ old('jadwal_id') }}">

                    @php
                        $selectedJadwalLabel = '-- Pilih Jadwal --';
                        $selectedJadwalId = old('jadwal_id');
                        if ($selectedJadwalId) {
                            foreach ($jadwals as $j) {
                                if ($j->id == $selectedJadwalId) {
                                    $selectedJadwalLabel =
                                        '['.$j->jurusan.'] '.$j->hari.', '.
                                        substr($j->waktu_mulai,0,5).' - '.substr($j->waktu_selesai,0,5).
                                        ' | '.$j->mata_pelajaran.' ('.$j->ruang_lab.')';
                                    break;
                                }
                            }
                        }
                    @endphp

                    {{-- tombol utama (seperti select) --}}
                    <button type="button"
                        onclick="toggleJadwalListSesi()"
                        class="p-3 border rounded-lg w-full bg-white flex items-center justify-between gap-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm md:text-base">
                        <span id="jadwal_selected_label_sesi" class="text-gray-700 truncate">
                            {{ $selectedJadwalLabel }}
                        </span>

                        {{-- icon panah --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0"
                             viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                  clip-rule="evenodd" />
                        </svg>
                    </button>

                    {{-- list jadwal --}}
                    <div id="jadwal_list_sesi"
                        class="absolute left-0 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-xl max-h-60 overflow-y-auto hidden z-30 text-sm">
                        @foreach ($jadwals as $jadwal)
                            @php
                                $label = '['.$jadwal->jurusan.'] '.$jadwal->hari.', '.
                                         substr($jadwal->waktu_mulai,0,5).' - '.substr($jadwal->waktu_selesai,0,5).
                                         ' | '.$jadwal->mata_pelajaran.' ('.$jadwal->ruang_lab.')';
                            @endphp

                            <button type="button"
                                class="w-full text-left px-3 py-2 hover:bg-blue-50 {{ old('jadwal_id') == $jadwal->id ? 'bg-blue-50 font-semibold' : '' }}"
                                data-id="{{ $jadwal->id }}"
                                data-label="{{ $label }}"
                                onclick="pickJadwalSesi(this)">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <button type="button"
                    onclick="loadSessionData()"
                    class="px-5 py-3 bg-[#0D47C9] text-white rounded-lg hover:bg-blue-700 shadow text-sm md:text-base md:w-auto w-full">
                    Muat Siswa
                </button>
            </div>
        </div>

    {{-- Tabel Siswa dengan Fitur Pilih Berdasarkan Jumlah --}}
<div class="bg-white shadow-md border rounded-xl">
    <div class="px-5 py-3 border-b bg-gray-50">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h3 class="font-semibold text-gray-800">2. Tentukan Peserta Sesi</h3>
                <p class="text-xs text-gray-500 mt-0.5">Pilih siswa secara manual atau otomatis</p>
            </div>
            
            {{-- FITUR PILIH BERDASARKAN JUMLAH - LAYOUT FINAL --}}
            <div class="flex items-center gap-3">
                {{-- Counter Siswa Terpilih (Hidden by default) --}}
                <div id="selected_count_display" class="text-sm font-medium text-gray-700 hidden">
                    <span class="font-bold text-blue-600" id="selected_count">0</span>/<span id="total_count">0</span> dipilih
                </div>
                
                {{-- Input di tengah --}}
                <input type="number" 
                    id="select_count"
                    min="1"
                    placeholder="Jumlah"
                    class="w-24 px-3 py-2 border border-gray-300 rounded-lg text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    oninput="validateSelectCount(this)"
                    onkeypress="if(event.key === 'Enter') { event.preventDefault(); selectByCount(); }">
                
                {{-- Button Terapkan di pojok kanan --}}
                <button type="button"
                    onclick="selectByCount()"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
                    Terapkan
                </button>
            </div>
        </div>
    </div>

    {{-- SCROLL RESPONSIF --}}
    <div class="overflow-x-auto w-full">
        <table class="min-w-full divide-y divide-gray-200 text-xs md:text-sm">
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

            <tbody class="divide-y">
                @foreach ($siswas as $siswa)
                <tr class="hover:bg-gray-50">
                    <td class="p-3 text-center">
                        <input type="checkbox"
                            name="siswa_ids[]"
                            value="{{ $siswa->id }}"
                            data-siswa-id="{{ $siswa->id }}"
                            class="siswa-checkbox w-4 h-4 md:w-5 md:h-5"
                            onchange="updateSelectAllState()">
                    </td>

                    <td class="p-3 whitespace-nowrap">{{ $siswa->nis }}</td>
                    <td class="p-3">{{ $siswa->nama }}</td>
                    <td class="p-3 whitespace-nowrap">{{ $siswa->kelas }}</td>
                    <td class="p-3 whitespace-nowrap">{{ $siswa->jurusan }}</td>

                    <td class="p-3" id="status-{{ $siswa->id }}">
                        <span class="text-gray-400">Belum dimuat</span>
                    </td>
                </tr>
                @endforeach

                @if ($siswas->count() == 0)
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-500">
                        Tidak ada data siswa.
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

{{-- SCRIPT LENGKAP --}}
<script>
    const mappingData = @json($mappingSesi);
    const allJadwals = @json($jadwals->keyBy('id'));

    // ===== VALIDASI INPUT JUMLAH =====
    function validateSelectCount(input) {
        const allCheckboxes = document.querySelectorAll('.siswa-checkbox');
        const availableCount = Array.from(allCheckboxes).filter(cb => !cb.disabled).length;
        
        // Batasi input tidak boleh melebihi jumlah siswa yang tersedia
        if (parseInt(input.value) > availableCount) {
            input.value = availableCount;
        }
        
        // Tidak boleh negatif atau 0
        if (parseInt(input.value) < 1) {
            input.value = '';
        }
    }

    // ===== PILIH BERDASARKAN JUMLAH =====
    function selectByCount() {
        const countInput = document.getElementById('select_count');
        const targetCount = parseInt(countInput.value) || 0;
        
        if (targetCount === 0 || targetCount < 1) {
            alert('⚠️ Masukkan jumlah siswa yang ingin dipilih (minimal 1)');
            countInput.focus();
            return;
        }
        
        const allCheckboxes = document.querySelectorAll('.siswa-checkbox');
        const enabledCheckboxes = Array.from(allCheckboxes).filter(cb => !cb.disabled);
        
        if (targetCount > enabledCheckboxes.length) {
            alert(`⚠️ Jumlah siswa yang tersedia hanya ${enabledCheckboxes.length} siswa.\nAnda meminta ${targetCount} siswa.`);
            countInput.value = enabledCheckboxes.length;
            return;
        }
        
        // Reset semua checkbox dulu
        enabledCheckboxes.forEach(cb => cb.checked = false);
        
        // Pilih sejumlah checkbox sesuai target
        for (let i = 0; i < targetCount && i < enabledCheckboxes.length; i++) {
            enabledCheckboxes[i].checked = true;
        }
        
        // Update counter
        updateSelectedCount();
        
        // Clear input setelah berhasil
        countInput.value = '';
    }

    // ===== UPDATE COUNTER SISWA TERPILIH =====
    function updateSelectedCount() {
        const checkedCount = document.querySelectorAll('.siswa-checkbox:checked').length;
        const totalCount = document.querySelectorAll('.siswa-checkbox:not([disabled])').length;
        
        const countDisplay = document.getElementById('selected_count');
        const totalDisplay = document.getElementById('total_count');
        const countWrapper = document.getElementById('selected_count_display');
        
        if (countDisplay) {
            countDisplay.textContent = checkedCount;
        }
        if (totalDisplay) {
            totalDisplay.textContent = totalCount;
        }
        
        // Tampilkan counter hanya jika ada siswa terpilih
        if (countWrapper) {
            if (checkedCount > 0) {
                countWrapper.classList.remove('hidden');
            } else {
                countWrapper.classList.add('hidden');
            }
        }
    }

    // ===== UPDATE STATE (TANPA MASTER CHECKBOX) =====
    function updateSelectAllState() {
        updateSelectedCount();
    }

    // ===== DROPDOWN FILTER JURUSAN =====
    function toggleJurusanFilter() {
        const list = document.getElementById('jurusan_filter_list');
        if (list) list.classList.toggle('hidden');
    }

    function pickJurusanFilter(btn) {
        const value = btn.dataset.value;
        const label = btn.dataset.label;

        document.getElementById('jurusan_filter_input').value = value;
        document.getElementById('jurusan_filter_label').textContent = label;

        const list = document.getElementById('jurusan_filter_list');
        if (list) list.classList.add('hidden');

        btn.closest('form').submit();
    }

    // ===== DROPDOWN CUSTOM JADWAL SESI =====
    function toggleJadwalListSesi() {
        const list = document.getElementById('jadwal_list_sesi');
        if (list) list.classList.toggle('hidden');
    }

    function pickJadwalSesi(btn) {
        const id = btn.dataset.id;
        const label = btn.dataset.label;

        document.getElementById('jadwal_id').value = id;
        document.getElementById('jadwal_selected_label_sesi').textContent = label;

        const list = document.getElementById('jadwal_list_sesi');
        if (list) list.classList.add('hidden');
    }

    // ===== KLIK DI LUAR DROPDOWN =====
    document.addEventListener('click', function(e) {
        const jurusanWrapper = document.getElementById('jurusanFilterWrapper');
        const jurusanList = document.getElementById('jurusan_filter_list');
        if (jurusanWrapper && jurusanList && !jurusanWrapper.contains(e.target)) {
            jurusanList.classList.add('hidden');
        }

        const jadwalWrapper = document.getElementById('jadwalDropdownSesi');
        const jadwalList = document.getElementById('jadwal_list_sesi');
        if (jadwalWrapper && jadwalList && !jadwalWrapper.contains(e.target)) {
            jadwalList.classList.add('hidden');
        }
    });

    // ===== LOAD SESSION DATA =====
    function loadSessionData() {
        // Reset status
        document.querySelectorAll('[id^="status-"]').forEach(el => {
            el.innerHTML = '<span class="text-gray-400">Tidak terdaftar</span>';
        });

        // Uncheck semua dan enable semua
        document.querySelectorAll('.siswa-checkbox').forEach(ch => {
            ch.checked = false;
            ch.disabled = false;
        });

        // Reset input jumlah
        const selectCountInput = document.getElementById('select_count');
        if (selectCountInput) {
            selectCountInput.value = '';
        }

        const selectedJadwalId = document.getElementById('jadwal_id').value;
        if (!selectedJadwalId) {
            alert('⚠️ Silakan pilih jadwal terlebih dahulu!');
            updateSelectedCount();
            return;
        }

        // Proses mapping data
        document.querySelectorAll('.siswa-checkbox').forEach(checkbox => {
            const siswaId = checkbox.dataset.siswaId;
            let foundInSelectedJadwal = false;

            for (const jadwalId in mappingData) {
                const found = mappingData[jadwalId].find(m => m.siswa_id == siswaId);

                if (found) {
                    const status = document.getElementById('status-' + siswaId);

                    if (jadwalId == selectedJadwalId) {
                        // Siswa sudah terdaftar di jadwal ini
                        checkbox.checked = true;
                        foundInSelectedJadwal = true;
                        status.innerHTML = '<span class="text-blue-600 font-semibold">Sesi ini</span>';
                    } else {
                        // Siswa terdaftar di jadwal lain
                        const j = allJadwals[jadwalId];
                        if (j) {
                            status.innerHTML = `<span class="text-orange-600 font-medium">Terdaftar di ${j.mata_pelajaran}</span>`;
                        }
                    }
                }
            }
        });
        
        // Update counter setelah load data
        updateSelectedCount();
    }
    
    // Initialize: sembunyikan counter saat page load
    document.addEventListener('DOMContentLoaded', function() {
        const countWrapper = document.getElementById('selected_count_display');
        if (countWrapper) {
            countWrapper.classList.add('hidden');
        }
    });
</script>

        {{-- Tombol Submit --}}
        <div>
            <button type="submit"
                class="px-6 py-3 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition text-sm md:text-base">
                Simpan Pembagian Sesi
            </button>
        </div>

    </form>
</div>

{{-- STYLE KECIL UNTUK DROPDOWN --}}
<style>
    #jadwal_list_sesi button,
    #jurusan_filter_list button {
        transition: background 0.15s;
    }
</style>

{{-- SCRIPT --}}
<script>
    const mappingData = @json($mappingSesi);
    const allJadwals = @json($jadwals->keyBy('id'));

    // ===== DROPDOWN FILTER JURUSAN =====
    function toggleJurusanFilter() {
        const list = document.getElementById('jurusan_filter_list');
        if (list) list.classList.toggle('hidden');
    }

    function pickJurusanFilter(btn) {
        const value = btn.dataset.value;
        const label = btn.dataset.label;

        document.getElementById('jurusan_filter_input').value = value;
        document.getElementById('jurusan_filter_label').textContent = label;

        const list = document.getElementById('jurusan_filter_list');
        if (list) list.classList.add('hidden');

        // submit form filter
        btn.closest('form').submit();
    }

    // ===== DROPDOWN CUSTOM JADWAL SESI =====
    function toggleJadwalListSesi() {
        const list = document.getElementById('jadwal_list_sesi');
        if (list) list.classList.toggle('hidden');
    }

    function pickJadwalSesi(btn) {
        const id = btn.dataset.id;
        const label = btn.dataset.label;

        document.getElementById('jadwal_id').value = id;
        document.getElementById('jadwal_selected_label_sesi').textContent = label;

        const list = document.getElementById('jadwal_list_sesi');
        if (list) list.classList.add('hidden');
    }

    // klik di luar dropdown -> tutup dua-duanya
    document.addEventListener('click', function(e) {
        const jurusanWrapper = document.getElementById('jurusanFilterWrapper');
        const jurusanList = document.getElementById('jurusan_filter_list');
        if (jurusanWrapper && jurusanList && !jurusanWrapper.contains(e.target)) {
            jurusanList.classList.add('hidden');
        }

        const jadwalWrapper = document.getElementById('jadwalDropdownSesi');
        const jadwalList = document.getElementById('jadwal_list_sesi');
        if (jadwalWrapper && jadwalList && !jadwalWrapper.contains(e.target)) {
            jadwalList.classList.add('hidden');
        }
    });

    // ===== LOGIKA MUAT SESI (sama seperti sebelumnya) =====
    function loadSessionData() {
        // reset status
        document.querySelectorAll('[id^="status-"]').forEach(el => {
            el.innerHTML = '<span class="text-gray-400">Tidak terdaftar</span>';
        });

        // uncheck semua
        document.querySelectorAll('input[type="checkbox"]').forEach(ch => ch.checked = false);

        const selectedJadwalId = document.getElementById('jadwal_id').value;
        if (!selectedJadwalId) return;

        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            const siswaId = checkbox.dataset.siswaId;

            for (const jadwalId in mappingData) {
                const found = mappingData[jadwalId].find(m => m.siswa_id == siswaId);

                if (found) {
                    const status = document.getElementById('status-' + siswaId);

                    if (jadwalId == selectedJadwalId) {
                        checkbox.checked = true;
                        status.innerHTML =
                            '<span class="text-blue-600 font-semibold">Sesi ini</span>';
                    } else {
                        const j = allJadwals[jadwalId];
                        status.innerHTML =
                            `<span class="text-orange-600 font-medium">Terdaftar di ${j.mata_pelajaran}</span>`;
                    }
                }
            }
        });
    }
</script>

@endsection
