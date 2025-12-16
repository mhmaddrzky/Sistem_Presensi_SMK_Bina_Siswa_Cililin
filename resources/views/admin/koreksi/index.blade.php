@extends('layouts.admin')

@section('content')

{{-- Title Halaman --}}
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
        📊 Koreksi Kehadiran
    </h1>
    <p class="text-gray-500">Ubah status Alfa menjadi Sakit atau Izin sesuai kebutuhan.</p>
</div>

{{-- Notifikasi --}}
@if(session('success'))
    <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700 border border-green-300">
        ✅ {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 border border-red-300">
        ❌ {{ session('error') }}
    </div>
@endif

{{-- Card Filter Jadwal --}}
<div class="bg-white shadow-md rounded-xl border card-responsive mb-6">
    <form action="{{ route('admin.koreksi.index') }}" method="GET"
          class="space-y-3 px-4 pt-4 pb-2" id="form_koreksi_jadwal">

        <label class="font-semibold text-gray-700 block mb-1">
            Pilih Jadwal yang Akan Dikoreksi:
        </label>

        {{-- DROPDOWN CUSTOM --}}
        <div class="relative" id="jadwalDropdown">
            <input type="hidden" name="jadwal_id" id="jadwal_id_input" value="{{ request('jadwal_id') }}">

            <button type="button"
                    class="w-full p-3 rounded-lg border border-gray-300 bg-white flex items-center justify-between gap-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    onclick="toggleJadwalList()">

                @php
                    $selectedLabel = '-- Pilih Jadwal --';
                    if(request('jadwal_id')){
                        foreach($jadwals as $j){
                            if($j->id == request('jadwal_id')){
                                $selectedLabel = '['.$j->jurusan.'] '.$j->hari.', '.substr($j->waktu_mulai,0,5).' - '.substr($j->waktu_selesai,0,5).' | '.$j->mata_pelajaran.' ('.$j->ruang_lab.')';
                                break;
                            }
                        }
                    }
                @endphp

                <span id="jadwal_selected_label" class="text-gray-700 truncate">
                    {{ $selectedLabel }}
                </span>

                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                          clip-rule="evenodd" />
                </svg>
            </button>

            <div id="jadwal_list"
                 class="absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto hidden z-20 text-sm">

                @foreach($jadwals as $jadwal)
                    @php
                        $label = '['.$jadwal->jurusan.'] '.$jadwal->hari.', '.substr($jadwal->waktu_mulai,0,5).' - '.substr($jadwal->waktu_selesai,0,5).' | '.$jadwal->mata_pelajaran.' ('.$jadwal->ruang_lab.')';
                    @endphp

                    <button type="button"
                            class="w-full text-left px-3 py-2 hover:bg-blue-50 {{ request('jadwal_id') == $jadwal->id ? 'bg-blue-50 font-semibold' : '' }}"
                            data-id="{{ $jadwal->id }}"
                            data-label="{{ $label }}"
                            onclick="pickJadwal(this)">
                        {{ $label }}
                    </button>
                @endforeach

            </div>
        </div>
    </form>
</div>

{{-- Jika jadwal dipilih --}}
@if(isset($jadwalTerpilih))

    {{-- HEADER TABEL --}}
    <div class="mb-4">
        
        {{-- 1. JUDUL DI ATAS --}}
        <h2 class="text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
            Daftar Peserta Sesi: <span class="text-blue-600">{{ $jadwalTerpilih->hari }}</span>
        </h2>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            {{-- 2. SEARCH INPUT (POJOK KIRI, STYLE SAMA DENGAN SESI) --}}
            <div class="relative w-full md:w-72">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" id="searchKoreksi" onkeyup="filterKoreksi()"
                    placeholder="Cari Nama atau Kelas..."
                    class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- 3. CHECKBOX TANDAI SEMUA (KANAN) --}}
            <label class="flex items-center gap-2 cursor-pointer bg-blue-50 px-3 py-2 rounded-lg border border-blue-100 hover:bg-blue-100 transition">
                <input type="checkbox" id="checkAllHadir" onchange="toggleAllHadir()"
                    class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                <span class="text-sm font-semibold text-blue-700">Tandai Semua Hadir</span>
            </label>
        </div>
    </div>

    <form action="{{ route('admin.koreksi.store') }}" method="POST" class="space-y-5">
        @csrf
        <input type="hidden" name="jadwal_id" value="{{ $jadwalTerpilih->id }}">

        {{-- Table Responsif --}}
        <div class="overflow-x-auto bg-white shadow-md rounded-xl border">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#0D47C9] text-white">
                    <tr>
                        <th class="p-3 text-center font-semibold">NIS</th>
                        <th class="p-3 text-center font-semibold">Nama</th>
                        <th class="p-3 text-center font-semibold">Kelas</th>
                        <th class="p-3 text-center font-semibold">Status Otomatis</th>
                        <th class="p-3 text-center font-semibold">Koreksi Menjadi</th>
                    </tr>
                </thead>

                <tbody class="divide-y" id="tableKoreksiBody">
                    @forelse ($rekapKoreksi as $i => $koreksi)
                        @php
                            $currentStatus = $koreksi['status_otomatis'] ?? 'Alfa';
                        @endphp

                        <tr class="hover:bg-gray-50 koreksi-row">
                            <td class="p-3 whitespace-nowrap text-center col-nis">
                                {{ $koreksi['nis'] }}
                            </td>

                            <td class="p-3 whitespace-nowrap text-center col-nama">{{ $koreksi['nama'] }}</td>
                            <td class="p-3 whitespace-nowrap text-center col-kelas">{{ $koreksi['kelas'] }}</td>

                            <td class="p-3 font-bold whitespace-nowrap text-center">
                                <span id="label_otomatis_{{ $i }}"
                                      class="px-3 py-1 rounded-lg text-white
                                      {{ $currentStatus == 'Hadir' ? 'bg-green-500' : '' }}
                                      {{ $currentStatus == 'Izin' ? 'bg-blue-500' : '' }}
                                      {{ $currentStatus == 'Sakit' ? 'bg-yellow-500' : '' }}
                                      {{ $currentStatus == 'Alfa' ? 'bg-red-500' : '' }}">
                                    {{ $currentStatus }}
                                </span>
                            </td>

                            {{-- Koreksi Menjadi --}}
                            <td class="p-3 whitespace-nowrap">
                                <input type="hidden" name="koreksi[{{ $i }}][siswa_id]" value="{{ $koreksi['siswa_id'] }}">
                                
                                <input type="hidden"
                                       name="koreksi[{{ $i }}][status]"
                                       id="status_input_{{ $i }}"
                                       class="status-hidden-input"
                                       value="{{ $currentStatus }}">

                                <div class="flex items-center gap-2 justify-center"> 
                                    {{-- H --}}
                                    <button type="button"
                                        class="status-dot {{ $currentStatus == 'Hadir' ? 'status-h' : '' }}"
                                        data-row="{{ $i }}" data-status="Hadir" onclick="pickKoreksi({{ $i }}, 'Hadir')"> H
                                    </button>
                                    {{-- I --}}
                                    <button type="button"
                                        class="status-dot {{ $currentStatus == 'Izin' ? 'status-i' : '' }}"
                                        data-row="{{ $i }}" data-status="Izin" onclick="pickKoreksi({{ $i }}, 'Izin')"> I
                                    </button>
                                    {{-- S --}}
                                    <button type="button"
                                        class="status-dot {{ $currentStatus == 'Sakit' ? 'status-s' : '' }}"
                                        data-row="{{ $i }}" data-status="Sakit" onclick="pickKoreksi({{ $i }}, 'Sakit')"> S
                                    </button>
                                    {{-- A --}}
                                    <button type="button"
                                        class="status-dot {{ $currentStatus == 'Alfa' ? 'status-a' : '' }}"
                                        data-row="{{ $i }}" data-status="Alfa" onclick="pickKoreksi({{ $i }}, 'Alfa')"> A
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-5 text-center text-gray-500">
                                Tidak ada data siswa untuk sesi ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="px-6 py-3 bg-[#0D47C9] text-white rounded-lg shadow hover:bg-[#0D47C9] text-white transition w-full sm:w-auto">
            Simpan Absen 
        </button>
    </form>

@else
    <p class="text-gray-500">Silakan pilih jadwal yang akan dikoreksi dari dropdown di atas.</p>
@endif

{{-- STYLE BULATAN STATUS --}}
<style>
    .status-dot {
        width: 2rem;
        height: 2rem;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e5e7eb;
        font-size: 0.75rem;
        font-weight: 600;
        color: #374151;
        cursor: pointer;
        transition: 0.15s;
    }
    .status-dot:hover {
        transform: scale(1.05);
    }
    .status-h { background-color: #22c55e; color: #fff; }
    .status-i { background-color: #3b82f6; color: #fff; }
    .status-s { background-color: #fbbf24; color: #fff; }
    .status-a { background-color: #ef4444; color: #fff; }
</style>

<script>
    // ===== SEARCH FUNCTION (HANYA NAMA & KELAS) =====
    function filterKoreksi() {
        const input = document.getElementById('searchKoreksi');
        const filter = input.value.toLowerCase();
        const rows = document.querySelectorAll('.koreksi-row');
        let hasResult = false;

        rows.forEach(row => {
            // NIS diabaikan dalam pencarian
            const nama = row.querySelector('.col-nama').textContent.toLowerCase();
            const kelas = row.querySelector('.col-kelas').textContent.toLowerCase();

            // Cek hanya Nama dan Kelas
            if (nama.includes(filter) || kelas.includes(filter)) {
                row.style.display = "";
                hasResult = true;
            } else {
                row.style.display = "none";
            }
        });
    }

    // ===== TANDAI SEMUA HADIR =====
    function toggleAllHadir() {
        const isChecked = document.getElementById('checkAllHadir').checked;
        
        if (isChecked) {
            // Loop semua baris yang TERLIHAT saja
            const rows = document.querySelectorAll('.koreksi-row');
            rows.forEach(row => {
                if (row.style.display !== 'none') {
                    // Cari tombol 'H' di baris ini
                    const hBtn = row.querySelector('.status-dot[data-status="Hadir"]');
                    if (hBtn) {
                        // Trigger klik secara programatis
                        const rowIndex = hBtn.getAttribute('data-row');
                        pickKoreksi(rowIndex, 'Hadir');
                    }
                }
            });
        }
    }

    // ===== DROPDOWN JADWAL =====
    function toggleJadwalList() {
        const list = document.getElementById('jadwal_list');
        list.classList.toggle('hidden');
    }

    function pickJadwal(btn) {
        const id = btn.dataset.id;
        const label = btn.dataset.label;

        document.getElementById('jadwal_id_input').value = id;
        document.getElementById('jadwal_selected_label').textContent = label;
        document.getElementById('jadwal_list').classList.add('hidden');
        document.getElementById('form_koreksi_jadwal').submit();
    }

    document.addEventListener('click', function (e) {
        const dropdown = document.getElementById('jadwalDropdown');
        const list = document.getElementById('jadwal_list');
        if (dropdown && list && !dropdown.contains(e.target)) {
            list.classList.add('hidden');
        }
    });

    // ===== PICK STATUS LOGIC =====
    function pickKoreksi(row, status) {
        const input = document.getElementById('status_input_' + row);
        if (input) {
            input.value = status;
        }
        
        // Reset warna semua tombol di baris ini
        const btns = document.querySelectorAll('.status-dot[data-row="' + row + '"]');
        btns.forEach(btn => {
            btn.classList.remove('status-h', 'status-i', 'status-s', 'status-a');
        });
        
        // Warnai tombol yang aktif
        const active = document.querySelector('.status-dot[data-row="' + row + '"][data-status="' + status + '"]');
        if (!active) return;

        if (status === 'Hadir') active.classList.add('status-h');
        else if (status === 'Izin') active.classList.add('status-i');
        else if (status === 'Sakit') active.classList.add('status-s');
        else if (status === 'Alfa') active.classList.add('status-a');
        
        // Uncheck 'Check All' jika ada yang diubah manual bukan jadi Hadir
        if (status !== 'Hadir') {
            document.getElementById('checkAllHadir').checked = false;
        }
    }
</script>

@endsection