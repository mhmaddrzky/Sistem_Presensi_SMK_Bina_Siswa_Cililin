@extends('layouts.admin')

@section('content')

{{-- Title --}}
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
            {{-- input asli yang dikirim ke server --}}
            <input type="hidden" name="jadwal_id" id="jadwal_id_input" value="{{ request('jadwal_id') }}">

            {{-- tombol utama (seperti select) --}}
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

                {{-- icon panah bawah --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                          clip-rule="evenodd" />
                </svg>
            </button>

            {{-- daftar pilihan jadwal --}}
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

    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
        Daftar Peserta Sesi: <span class="text-blue-600">{{ $jadwalTerpilih->hari }}</span>
    </h2>

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

                <tbody class="divide-y">
                    @forelse ($rekapKoreksi as $i => $koreksi)
                        @php
                            $currentStatus = $koreksi['status_otomatis'] ?? 'Alfa';
                        @endphp

                        <tr class="hover:bg-gray-50">
                            {{-- DATA: TAMPILKAN NIS --}}
                            <td class="p-3 whitespace-nowrap text-center">
                                {{ $koreksi['nis'] }}
                            </td>

                            <td class="p-3 whitespace-nowrap text-center">{{ $koreksi['nama'] }}</td>
                            <td class="p-3 whitespace-nowrap text-center">{{ $koreksi['kelas'] }}</td>

                            <td class="p-3 font-bold whitespace-nowrap text-center">
                                <span id="label_otomatis_{{ $i }}"
                                      class="px-3 py-1 rounded-lg text-white
                                      {{ $currentStatus == 'Hadir' ? 'bg-green-500' : '' }}
                                      {{ $currentStatus == 'Izin' ? 'bg-yellow-500' : '' }}
                                      {{ $currentStatus == 'Sakit' ? 'bg-blue-500' : '' }}
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

{{-- STYLE KECIL UNTUK BULATAN STATUS --}}
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
    .status-i { background-color: #fbbf24; color: #fff; }
    .status-s { background-color: #3b82f6; color: #fff; }
    .status-a { background-color: #ef4444; color: #fff; }
</style>

{{-- SCRIPT DROPDOWN + STATUS --}}
<script>
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

    function pickKoreksi(row, status) {
        const input = document.getElementById('status_input_' + row);
        if (input) {
            input.value = status;
        }
        const btns = document.querySelectorAll('.status-dot[data-row="' + row + '"]');
        btns.forEach(btn => {
            btn.classList.remove('status-h', 'status-i', 'status-s', 'status-a');
        });
        const active = document.querySelector('.status-dot[data-row="' + row + '"][data-status="' + status + '"]');
        if (!active) return;

        if (status === 'Hadir') active.classList.add('status-h');
        else if (status === 'Izin') active.classList.add('status-i');
        else if (status === 'Sakit') active.classList.add('status-s');
        else if (status === 'Alfa') active.classList.add('status-a');
    }
</script>

@endsection