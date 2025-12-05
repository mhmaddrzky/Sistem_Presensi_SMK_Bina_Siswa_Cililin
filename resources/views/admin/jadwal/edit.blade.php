@extends('layouts.admin')

@section('content')

    <h1 style="color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px;">
        Edit Jadwal Laboratorium
    </h1>
    
    {{-- Container Form --}}
    <div style="background-color: #fff; padding: 30px; border-radius: 12px !important; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);">
    
        <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST">
            @csrf
            @method('PUT') 

            {{-- Struktur Grid / Baris 2 Kolom (Menggunakan Flexbox/Grid Style Inline untuk Layout) --}}
            <div style="display: flex; gap: 25px;"> 
                
                {{-- Kolom Kiri --}}
                <div style="flex: 1;">
                    {{-- Input Tanggal --}}
                    <div style="margin-bottom: 20px;">
                        <label for="tanggal" style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Tanggal:</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', $jadwal->tanggal) }}" required
                               style="width: 100% !important; padding: 10px !important; border: 1px solid #ccc !important; 
                                      border-radius: 50px !important; box-sizing: border-box;"> 
                    </div>

                    {{-- Input Sesi --}}
                    <div style="margin-bottom: 20px;">
                        <label for="sesi" style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Sesi:</label>
                        <input type="text" name="sesi" value="{{ old('sesi', $jadwal->sesi) }}" required
                               placeholder="Contoh: Sesi 1 atau 07.00-09.00"
                               style="width: 100% !important; padding: 10px !important; border: 1px solid #ccc !important; 
                                      border-radius: 50px !important; box-sizing: border-box;">
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div style="flex: 1;">
                    {{-- Input Ruang Lab --}}
                    <div style="margin-bottom: 20px;">
                        <label for="ruang_lab" style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Ruang Lab:</label>
                        <input type="text" name="ruang_lab" value="{{ old('ruang_lab', $jadwal->ruang_lab) }}" required
                               placeholder="Contoh: Lab RPL 1"
                               style="width: 100% !important; padding: 10px !important; border: 1px solid #ccc !important; 
                                      border-radius: 50px !important; box-sizing: border-box;">
                    </div>

                    {{-- Kotak Catatan Informasi --}}
                    <div style="margin-bottom: 20px; padding: 10px; background: #f9f9f9; border-left: 3px solid #007bff; border-radius: 6px;">
                        <small style="color: #007bff;">Catatan: Pastikan semua data diisi dengan format yang benar.</small>
                    </div>
                </div>

            </div> 
            
            <hr style="border: 0; border-top: 1px solid #eee; margin: 25px 0;">

            {{-- Tombol Submit --}}
            <button type="submit" 
                    style="padding: 12px 30px !important; 
                           background-color: #007bff !important; 
                           color: white !important; 
                           border: none !important; 
                           border-radius: 50px !important; /* WAJIB MELENGKUNG */
                           cursor: pointer !important; 
                           font-size: 16px !important; 
                           font-weight: bold !important;
                           transition: background-color 0.3s ease;">
                Perbarui Jadwal
            </button>
        </form>
    </div> 
    
    {{-- Tombol Kembali --}}
    <a href="{{ route('admin.jadwal.index') }}" 
       style="display: inline-block !important; 
              margin-top: 30px !important; 
              color: #555 !important; 
              text-decoration: none !important; 
              padding: 8px 15px !important;
              border: 1px solid #ccc !important;
              border-radius: 50px !important; /* WAJIB MELENGKUNG */
              transition: background-color 0.3s ease;">
        ← Kembali ke Daftar Jadwal
    </a>
@endsection