<table>
    <thead>
        {{-- JUDUL --}}
        <tr>
            <th colspan="6" style="text-align: center;">REKAPITULASI PRESENSI SISWA - SMK BINA SISWA 2 CILILIN</th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center;">Periode: {{ $ketPeriode }} | Filter: {{ $jurusan }}</th>
        </tr>
        
        {{-- HEADER TABEL (Warna border) --}}
        <tr>
            <th style="border: 1px solid #000000;">NIS</th>
            <th style="border: 1px solid #000000;">Nama Siswa</th>
            <th style="border: 1px solid #000000;">Kelas</th>
            <th style="border: 1px solid #000000;">Jurusan</th>
            <th style="border: 1px solid #000000;">Kehadiran</th>
            <th style="border: 1px solid #000000;">Persentase</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dataLaporan as $data)
        <tr>
            <td style="border: 1px solid #000000;">{{ $data['nis'] }}</td>
            <td style="border: 1px solid #000000;">{{ $data['nama'] }}</td>
            <td style="border: 1px solid #000000;">{{ $data['kelas'] }}</td>
            <td style="border: 1px solid #000000;">{{ $data['jurusan'] }}</td>
            <td style="border: 1px solid #000000;">{{ $data['kehadiran'] }}</td>
            <td style="border: 1px solid #000000;">{{ $data['persentase'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>