@extends('layouts.admin')

@section('content')
    <h1 style="color: #1f3a93;">Manajemen Pembagian Sesi Siswa (Kuota)</h1>
    <p>Pilih Jadwal dan tentukan siswa mana yang masuk ke sesi tersebut (Maks. 20 siswa per sesi).</p>
    
    {{-- Notifikasi Sukses/Gagal --}}
    @if(session('success'))
        <div style="background: #d4edda; color: green; padding: 10px; border-radius: 5px; margin-bottom: 20px;">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="background: #f8d7da; color: red; padding: 10px; border-radius: 5px; margin-bottom: 20px;">❌ {{ session('error') }}</div>
    @endif

    {{-- 🛑 FIX 1: FORM FILTER JURUSAN SISWA 🛑 --}}
    {{-- Form ini hanya untuk memfilter daftar siswa, diarahkan ke route index --}}
    <form action="{{ route('admin.sesi.index') }}" method="GET" style="margin-bottom: 20px;">
        <div style="border: 1px solid #ccc; padding: 15px; background: #f2f2f2;">
            <label for="jurusan_filter" style="font-weight: bold;">Filter Daftar Siswa berdasarkan Jurusan:</label>
            <select name="jurusan_filter" id="jurusan_filter" onchange="this.form.submit()" style="padding: 8px;">
                <option value="all" {{ $jurusanFilter == 'all' ? 'selected' : '' }}>Semua Siswa</option>
                <option value="TKJ" {{ $jurusanFilter == 'TKJ' ? 'selected' : '' }}>TKJ</option>
                <option value="TBSM" {{ $jurusanFilter == 'TBSM' ? 'selected' : '' }}>TBSM</option>
            </select>
            @if ($jurusanFilter !== 'all')
                <small style="margin-left: 20px; color: #1f3a93;">Hanya menampilkan siswa Jurusan: **{{ $jurusanFilter }}**</small>
            @endif
        </div>
    </form>
    
    {{-- Form Utama untuk Menyimpan Pembagian Sesi --}}
    <form action="{{ route('admin.sesi.store') }}" method="POST">
        @csrf

        {{-- 🛑 FIX 2: DROPDOWN JADWAL DENGAN DETAIL LENGKAP 🛑 --}}
        <div style="margin-bottom: 20px;">
            <label for="jadwal_id" style="font-weight: bold;">1. Pilih Jadwal/Sesi:</label>
            <select name="jadwal_id" id="jadwal_id" required style="padding: 8px;">
                <option value="">-- Pilih Jadwal --</option>
                @foreach ($jadwals as $jadwal)
                    <option value="{{ $jadwal->id }}">
                        [{{ $jadwal->jurusan }}] {{ $jadwal->hari }}, {{ substr($jadwal->waktu_mulai, 0, 5) }} - {{ substr($jadwal->waktu_selesai, 0, 5) }} | {{ $jadwal->mata_pelajaran }} ({{ $jadwal->ruang_lab }}, Kuota: {{ $jadwal->kapasitas }})
                    </option>
                @endforeach
            </select>
            <button type="button" onclick="loadSessionData()" style="padding: 8px 15px;">Muat Siswa Sesi</button>
        </div>

        <h3 style="margin-top: 30px;">2. Tentukan Peserta Sesi:</h3>
        
        {{-- Tabel Siswa --}}
        <table border="1" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="padding: 10px;">Pilih</th>
                    <th style="padding: 10px;">NIS</th>
                    <th style="padding: 10px;">Nama</th>
                    <th style="padding: 10px;">Kelas</th>
                    <th style="padding: 10px;">Jurusan</th> {{-- 🛑 FIX 3: Kolom Jurusan Siswa --}}
                    <th style="padding: 10px;">Status Sesi Saat Ini</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($siswas as $siswa)
                    <tr>
                        <td style="text-align: center; padding: 8px;">
                            <input type="checkbox" name="siswa_ids[]" value="{{ $siswa->id }}" data-siswa-id="{{ $siswa->id }}">
                        </td>
                        <td style="padding: 8px;">{{ $siswa->nis }}</td>
                        <td style="padding: 8px;">{{ $siswa->nama }}</td>
                        <td style="padding: 8px;">{{ $siswa->kelas }}</td>
                        
                        {{-- 🛑 FIX 4: Tampilkan Jurusan Siswa --}}
                        <td style="padding: 8px; font-weight: bold;">{{ $siswa->jurusan }}</td>
                        
                        <td style="padding: 8px;" id="status-{{ $siswa->id }}">
                            Belum Dimuat
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">Tidak ada siswa yang ditemukan. (Mungkin karena filter jurusan yang dipilih)</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
            
        <button type="submit" style="padding: 10px 20px; margin-top: 20px; background: #28a745; color: white;">Simpan Pembagian Sesi</button>
    </form>
    
    {{-- SCRIPT JAVASCRIPT UNTUK MEMUAT DATA MAPPING --}}
    <script>
        // Data mapping dari Controller (Grouping by jadwal_id)
        const mappingData = @json($mappingSesi); 
        const allJadwals = @json($jadwals->keyBy('id')); // Ambil semua jadwal, di-key oleh ID

        function loadSessionData() {
            const selectedJadwalId = document.getElementById('jadwal_id').value;
            
            // 1. Reset semua status dan checkbox
            document.querySelectorAll('[id^="status-"]').forEach(el => {
                el.textContent = 'Tidak Terdaftar';
                el.style.color = '#6c757d';
            });
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = false;
            });
            
            if (!selectedJadwalId) return;

            // 2. Loop melalui SEMUA Siswa untuk cek status mapping
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                const siswaId = checkbox.dataset.siswaId;
                
                // Cari apakah siswa ini terdaftar di sesi LAIN
                for (const jadwalId in mappingData) {
                    // Cari record mapping di jadwal manapun
                    const found = mappingData[jadwalId].find(map => map.siswa_id == siswaId);
                    
                    if (found) {
                        // Jika sudah ditemukan mapping, tandai statusnya
                        const statusElement = document.getElementById('status-' + siswaId);
                        
                        // Jika terdaftar di jadwal yang SEDANG DIPILIH
                        if (jadwalId == selectedJadwalId) {
                            checkbox.checked = true;
                            statusElement.textContent = 'Terpilih untuk Sesi Ini';
                            statusElement.style.color = 'blue';
                            return; // Lanjut ke siswa berikutnya
                        } 
                        
                        // Jika terdaftar di jadwal LAIN
                        if (statusElement) {
                             const mappedJadwal = allJadwals[jadwalId];
                             
                             // 🛑 FIX TAMPILAN STATUS JADWAL LAIN: Lebih Detail
                             statusElement.textContent = 'Terdaftar: [' + mappedJadwal.jurusan + '] ' + mappedJadwal.mata_pelajaran + ' (' + mappedJadwal.hari + ' ' + mappedJadwal.waktu_mulai.substring(0, 5) + ')';
                             statusElement.style.color = 'orange';
                             return; // Lanjut ke siswa berikutnya
                        }
                    }
                }
            });
        }
        
        // Panggil fungsi saat dropdown jadwal berubah
        document.getElementById('jadwal_id').addEventListener('change', loadSessionData);
        
        // Muat data saat halaman pertama dimuat
        window.onload = loadSessionData;
    </script>
@endsection