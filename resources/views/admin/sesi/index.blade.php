@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-blue-900 flex items-center gap-3">
            📚 Manajemen Pembagian Sesi Siswa (Kuota)
        </h1>
        <p class="text-slate-600 text-sm md:text-base mt-1">
            Pilih jadwal dan tentukan siswa mana yang masuk ke sesi tersebut (maks. 20 siswa).
        </p>
    </div>

    {{-- Notifikasi --}}
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
        <form action="{{ route('admin.sesi.index') }}" method="GET"
              class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

            <div>
                <p class="font-semibold text-slate-700">Filter Jurusan</p>
                <p class="text-xs text-slate-500 mt-1">
                    Pilih jurusan untuk mempermudah pemilihan siswa.
                </p>
            </div>

            <div class="flex items-center gap-3">
                {{-- DROPDOWN CUSTOM FILTER JURUSAN --}}
                <div class="relative w-full md:w-56" id="jurusanFilterWrapper">
                    <input type="hidden" name="jurusan_filter" id="jurusan_filter_input" value="{{ $jurusanFilter }}">

                    <button type="button"
                        onclick="toggleJurusanFilter()"
                        class="w-full flex items-center justify-between gap-2 px-3 py-2.5 border rounded-lg bg-slate-50 hover:bg-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-700">
                        <span id="jurusan_filter_label" class="truncate">
                            {{ $labelJurusan }}
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0 text-slate-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                  clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div id="jurusan_filter_list"
                        class="absolute left-0 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden hidden z-30 text-sm">
                        <button type="button" class="w-full text-left px-3 py-2 hover:bg-blue-50 text-slate-700"
                            data-value="all" data-label="Semua Siswa"
                            onclick="pickJurusanFilter(this)">
                            Semua Siswa
                        </button>
                        <button type="button" class="w-full text-left px-3 py-2 hover:bg-blue-50 text-slate-700"
                            data-value="TKJ" data-label="TKJ"
                            onclick="pickJurusanFilter(this)">
                            TKJ
                        </button>
                        <button type="button" class="w-full text-left px-3 py-2 hover:bg-blue-50 text-slate-700"
                            data-value="TBSM" data-label="TBSM"
                            onclick="pickJurusanFilter(this)">
                            TBSM
                        </button>
                    </div>
                </div>

                @if ($jurusanFilter !== 'all')
                    <span class="hidden md:inline text-blue-700 text-sm font-medium bg-blue-50 px-3 py-1 rounded-full">
                        {{ $jurusanFilter }}
                    </span>
                @endif
            </div>
        </form>
    </div>

    <form action="{{ route('admin.sesi.store') }}" method="POST" class="space-y-6">
    @csrf

        {{-- Card Jadwal --}}
        <div class="bg-white shadow-sm border border-slate-200 rounded-xl px-5 py-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <p class="font-semibold text-slate-700">1. Pilih Jadwal/Sesi</p>
                    <p class="text-xs text-slate-500 mt-1">
                        Pilih jadwal praktikum yang akan diisi oleh siswa.
                    </p>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-3 mt-4">

                {{-- DROPDOWN CUSTOM JADWAL --}}
                <div class="relative w-full md:flex-1" id="jadwalDropdownSesi">
                  
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
                        class="p-3 border border-slate-300 rounded-lg w-full bg-white flex items-center justify-between gap-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm md:text-base text-slate-700">
                        <span id="jadwal_selected_label_sesi" class="truncate">
                            {{ $selectedJadwalLabel }}
                        </span>

                        {{-- icon panah --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0 text-slate-500"
                             viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                  clip-rule="evenodd" />
                        </svg>
                    </button>

                    {{-- list jadwal --}}
                    <div id="jadwal_list_sesi"
                        class="absolute left-0 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 overflow-y-auto hidden z-30 text-sm">
                        @foreach ($jadwals as $jadwal)
                            @php
                                $label = '['.$jadwal->jurusan.'] '.$jadwal->hari.', '.
                                         substr($jadwal->waktu_mulai,0,5).' - '.substr($jadwal->waktu_selesai,0,5).
                                         ' | '.$jadwal->mata_pelajaran.' ('.$jadwal->ruang_lab.')';
                            @endphp

                            <button type="button"
                                class="w-full text-left px-3 py-2 hover:bg-blue-50 text-slate-700 {{ old('jadwal_id') == $jadwal->id ? 'bg-blue-50 font-semibold' : '' }}"
                                data-id="{{ $jadwal->id }}"
                                data-label="{{ $label }}"
                                onclick="pickJadwalSesi(this)">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- TOMBOL MUAT SISWA --}}
                <button type="button"
                    onclick="loadSessionData()"
                    class="px-5 py-3 bg-[#0D47C9] text-white rounded-lg hover:bg-blue-800 shadow-md text-sm md:text-base md:w-auto w-full font-semibold transition-all">
                    Muat Siswa
                </button>
            </div>
        </div>

        {{-- Tabel Siswa dengan Fitur Pilih Berdasarkan Jumlah --}}
        <div class="bg-white shadow-sm border border-slate-200 rounded-xl overflow-hidden">
            <div class="px-5 py-3 border-b border-slate-200 bg-slate-50/50">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <h3 class="font-semibold text-slate-800">2. Tentukan Peserta Sesi</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Pilih siswa secara manual atau otomatis</p>
                    </div>
                    
                    {{-- FITUR PILIH BERDASARKAN JUMLAH --}}
                    <div class="flex items-center gap-3">
                        <div id="selected_count_display" class="text-sm font-medium text-slate-600 hidden">
                            <span class="font-bold text-blue-700" id="selected_count">0</span>/<span id="total_count">0</span> dipilih
                        </div>
                        
                        {{-- Input Jumlah --}}
                        <input type="number" 
                            id="select_count"
                            min="1"
                            placeholder="Jumlah"
                            class="w-24 px-3 py-2 border border-slate-300 rounded-lg text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            oninput="validateSelectCount(this)"
                            onkeypress="if(event.key === 'Enter') { event.preventDefault(); selectByCount(); }">
                        
                        {{-- Button Terapkan --}}
                        <button type="button"
                            onclick="selectByCount()"
                            class="px-5 py-2 bg-[#0D47C9] text-white rounded-lg text-sm font-semibold hover:bg-blue-800 transition-all shadow-sm">
                            Terapkan
                        </button>
                    </div>
                </div>
            </div>

            {{-- TABLE SISWA --}}
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

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($siswas as $siswa)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-3 text-center">
                                <input type="checkbox"
                                    name="siswa_ids[]"
                                    value="{{ $siswa->id }}"
                                    data-siswa-id="{{ $siswa->id }}"
                                    class="siswa-checkbox w-4 h-4 md:w-5 md:h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                    onchange="updateSelectAllState()">
                            </td>

                            <td class="p-3 whitespace-nowrap text-slate-700">{{ $siswa->nis }}</td>
                            <td class="p-3 text-slate-800 font-medium">{{ $siswa->nama }}</td>
                            <td class="p-3 whitespace-nowrap text-slate-600">{{ $siswa->kelas }}</td>
                            <td class="p-3 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700 ring-1 ring-inset ring-slate-600/20">
                                    {{ $siswa->jurusan }}
                                </span>
                            </td>

                            <td class="p-3" id="status-{{ $siswa->id }}">
                                <span class="text-slate-400 italic text-xs">Belum dimuat</span>
                            </td>
                        </tr>
                        @endforeach

                        @if ($siswas->count() == 0)
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500">
                                Tidak ada data siswa yang tersedia untuk jurusan ini.
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tombol Submit --}}
        <div>
            <button type="submit"
                class="px-6 py-3 bg-emerald-600 text-white rounded-lg shadow-md hover:bg-emerald-700 transition font-semibold text-sm md:text-base w-full md:w-auto">
                Simpan Pembagian Sesi
            </button>
        </div>

    </form>
</div>

{{-- SCRIPT LENGKAP --}}
<script>
    const mappingData = @json($mappingSesi);
    const allJadwals = @json($jadwals->keyBy('id'));

    // ===== VALIDASI INPUT JUMLAH =====
    function validateSelectCount(input) {
        const allCheckboxes = document.querySelectorAll('.siswa-checkbox');
        const availableCount = Array.from(allCheckboxes).filter(cb => !cb.disabled).length;
        
        if (parseInt(input.value) > availableCount) {
            input.value = availableCount;
        }
        if (parseInt(input.value) < 1) {
            input.value = '';
        }
    }

    // ===== PILIH BERDASARKAN JUMLAH (DENGAN POPUP HIJAU) =====
    function selectByCount() {
        const countInput = document.getElementById('select_count');
        const targetCount = parseInt(countInput.value) || 0;
        
        // Validasi Kosong
        if (targetCount === 0 || targetCount < 1) {
            // Parameter: Title, Message, FormID, ButtonText, Color, Type
            window.openModal(
                'Perhatian', 
                'Mohon masukkan jumlah siswa yang ingin dipilih (minimal 1).', 
                null, 
                'Mengerti', 
                'emerald', 
                'alert'   
            );
            return;
        }
        
        const allCheckboxes = document.querySelectorAll('.siswa-checkbox');
        const enabledCheckboxes = Array.from(allCheckboxes).filter(cb => !cb.disabled);
        
        // Validasi Stok Kurang
        if (targetCount > enabledCheckboxes.length) {
            window.openModal(
                'Stok Siswa Tidak Cukup', 
                `Jumlah siswa tersedia hanya ${enabledCheckboxes.length}. Anda meminta ${targetCount} siswa.`, 
                null, 
                'Sesuaikan', 
                'emerald', // Warna tombol Hijau
                'alert'
            );
            countInput.value = enabledCheckboxes.length;
            return;
        }
        
        // Reset dan Pilih
        enabledCheckboxes.forEach(cb => cb.checked = false);
        for (let i = 0; i < targetCount && i < enabledCheckboxes.length; i++) {
            enabledCheckboxes[i].checked = true;
        }
        
        updateSelectedCount();
        countInput.value = '';
    }

    // ===== UPDATE COUNTER =====
    function updateSelectedCount() {
        const checkedCount = document.querySelectorAll('.siswa-checkbox:checked').length;
        const totalCount = document.querySelectorAll('.siswa-checkbox:not([disabled])').length;
        
        const countDisplay = document.getElementById('selected_count');
        const totalDisplay = document.getElementById('total_count');
        const countWrapper = document.getElementById('selected_count_display');
        
        if (countDisplay) countDisplay.textContent = checkedCount;
        if (totalDisplay) totalDisplay.textContent = totalCount;
        
        if (countWrapper) {
            if (checkedCount > 0) countWrapper.classList.remove('hidden');
            else countWrapper.classList.add('hidden');
        }
    }

    function updateSelectAllState() {
        updateSelectedCount();
    }

    // ===== DROPDOWN HELPER =====
    function toggleJurusanFilter() {
        document.getElementById('jurusan_filter_list').classList.toggle('hidden');
    }

    function pickJurusanFilter(btn) {
        document.getElementById('jurusan_filter_input').value = btn.dataset.value;
        document.getElementById('jurusan_filter_label').textContent = btn.dataset.label;
        document.getElementById('jurusan_filter_list').classList.add('hidden');
        btn.closest('form').submit();
    }

    function toggleJadwalListSesi() {
        document.getElementById('jadwal_list_sesi').classList.toggle('hidden');
    }

    function pickJadwalSesi(btn) {
        document.getElementById('jadwal_id').value = btn.dataset.id;
        document.getElementById('jadwal_selected_label_sesi').textContent = btn.dataset.label;
        document.getElementById('jadwal_list_sesi').classList.add('hidden');
    }

    document.addEventListener('click', function(e) {
        const jw = document.getElementById('jurusanFilterWrapper');
        const jl = document.getElementById('jurusan_filter_list');
        if (jw && jl && !jw.contains(e.target)) jl.classList.add('hidden');

        const sw = document.getElementById('jadwalDropdownSesi');
        const sl = document.getElementById('jadwal_list_sesi');
        if (sw && sl && !sw.contains(e.target)) sl.classList.add('hidden');
    });

    // ===== LOAD SESSION LOGIC =====
    function loadSessionData() {
        document.querySelectorAll('[id^="status-"]').forEach(el => {
            el.innerHTML = '<span class="text-slate-400 italic text-xs">Tidak terdaftar</span>';
        });

        document.querySelectorAll('.siswa-checkbox').forEach(ch => {
            ch.checked = false;
            ch.disabled = false;
        });

        if(document.getElementById('select_count')) document.getElementById('select_count').value = '';

        const selectedJadwalId = document.getElementById('jadwal_id').value;
        if (!selectedJadwalId) {
            // Peringatan jika belum pilih jadwal (Hijau juga)
            window.openModal('Perhatian', 'Silakan pilih jadwal terlebih dahulu!', null, 'Mengerti', 'emerald', 'alert');
            updateSelectedCount();
            return;
        }

        document.querySelectorAll('.siswa-checkbox').forEach(checkbox => {
            const siswaId = checkbox.dataset.siswaId;
            let foundInSelectedJadwal = false;

            for (const jadwalId in mappingData) {
                const found = mappingData[jadwalId].find(m => m.siswa_id == siswaId);
                if (found) {
                    const status = document.getElementById('status-' + siswaId);
                    if (jadwalId == selectedJadwalId) {
                        checkbox.checked = true;
                        foundInSelectedJadwal = true;
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
        updateSelectedCount();
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const cw = document.getElementById('selected_count_display');
        if (cw) cw.classList.add('hidden');
    });
</script>

{{-- INCLUDE COMPONENT MODAL --}}
@include('components.modal-confirmation')

@endsection