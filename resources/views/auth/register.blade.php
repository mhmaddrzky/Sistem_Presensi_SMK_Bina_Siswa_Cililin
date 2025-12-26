<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi | Sistem Presensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: url('{{ asset("assets/img/sekolah.png") }}') center/cover no-repeat;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center relative py-8">

    {{-- OVERLAY BACKGROUND --}}
    <div class="absolute inset-0 bg-blue-700 bg-opacity-70 backdrop-blur-[3px]"></div>

    {{-- CARD REGISTRASI --}}
    <div class="relative w-[92%] max-w-[420px]
                bg-white/60 backdrop-blur-md
                px-8 py-10 rounded-3xl
                shadow-[0_15px_45px_rgba(0,0,0,0.25)]
                border border-white/40">

        {{-- LOGO SEKOLAH --}}
        <img src="{{ asset('assets/img/logo-smk.png') }}"
             class="w-20 h-20 mx-auto mb-4 object-contain"
             alt="Logo SMK">

        {{-- JUDUL --}}
        <h2 class="text-xl font-extrabold text-gray-900 text-center mb-1">
            Registrasi Siswa
        </h2>
        <p class="text-sm text-gray-700 text-center mb-6">
            Data akan diverifikasi oleh admin
        </p>

        {{-- NOTIFIKASI SUCCESS --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- NOTIFIKASI ERROR UMUM --}}
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- FORM REGISTRASI (novalidate untuk disable HTML5 validation) --}}
        <form method="POST" action="{{ route('register') }}" novalidate class="space-y-4">
            @csrf

            {{-- NAMA LENGKAP --}}
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="nama"
                       name="nama"
                       value="{{ old('nama') }}"
                       placeholder="Masukkan nama lengkap"
                       class="w-full bg-white/80 border @error('nama') border-red-500 @else border-gray-300 @enderror
                              rounded-xl px-4 py-3 text-sm shadow-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              placeholder:text-gray-400">
                
                @error('nama')
                    <p class="text-red-600 text-xs mt-1.5 ml-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- USERNAME --}}
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                    Username <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="username"
                       name="username"
                       value="{{ old('username') }}"
                       placeholder="Buat username unik (contoh: dimas123)"
                       class="w-full bg-white/80 border @error('username') border-red-500 @else border-gray-300 @enderror
                              rounded-xl px-4 py-3 text-sm shadow-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              placeholder:text-gray-400">
                
                @error('username')
                    <p class="text-red-600 text-xs mt-1.5 ml-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- NIS --}}
            <div>
                <label for="nis" class="block text-sm font-medium text-gray-700 mb-1">
                    NIS (Nomor Induk Siswa) <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="nis"
                       name="nis"
                       value="{{ old('nis') }}"
                       placeholder="Contoh: 12345 (minimal 3 digit)"
                       maxlength="20"
                       inputmode="numeric"
                       class="w-full bg-white/80 border @error('nis') border-red-500 @else border-gray-300 @enderror
                              rounded-xl px-4 py-3 text-sm shadow-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              placeholder:text-gray-400">
                
                @error('nis')
                    <p class="text-red-600 text-xs mt-1.5 ml-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- KONFIRMASI NIS --}}
            <div>
                <label for="nis_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                    Konfirmasi NIS <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="nis_confirmation"
                       name="nis_confirmation"
                       placeholder="Ketik ulang NIS yang sama"
                       maxlength="20"
                       inputmode="numeric"
                       class="w-full bg-white/80 border @error('nis_confirmation') border-red-500 @else border-gray-300 @enderror
                              rounded-xl px-4 py-3 text-sm shadow-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              placeholder:text-gray-400">
                
                @error('nis_confirmation')
                    <p class="text-red-600 text-xs mt-1.5 ml-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- KELAS --}}
            <div>
                <label for="kelas" class="block text-sm font-medium text-gray-700 mb-1">
                    Kelas <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="kelas"
                       name="kelas"
                       value="{{ old('kelas') }}"
                       placeholder="Contoh: X-A, XI-B, XII-C"
                       maxlength="10"
                       class="w-full bg-white/80 border @error('kelas') border-red-500 @else border-gray-300 @enderror
                              rounded-xl px-4 py-3 text-sm shadow-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              placeholder:text-gray-400">
                
                @error('kelas')
                    <p class="text-red-600 text-xs mt-1.5 ml-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- JURUSAN --}}
            <div>
                <label for="jurusan" class="block text-sm font-medium text-gray-700 mb-1">
                    Jurusan <span class="text-red-500">*</span>
                </label>
                <select id="jurusan"
                        name="jurusan"
                        class="w-full bg-white/80 border @error('jurusan') border-red-500 @else border-gray-300 @enderror
                               rounded-xl px-4 py-3 text-sm shadow-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Jurusan --</option>
                    <option value="TKJ" {{ old('jurusan') == 'TKJ' ? 'selected' : '' }}>
                        Teknik Komputer dan Jaringan (TKJ)
                    </option>
                    <option value="TBSM" {{ old('jurusan') == 'TBSM' ? 'selected' : '' }}>
                        Teknik Bisnis Sepeda Motor (TBSM)
                    </option>
                </select>
                
                @error('jurusan')
                    <p class="text-red-600 text-xs mt-1.5 ml-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- BUTTON DAFTAR --}}
            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800
                           text-white py-3 rounded-xl text-sm font-semibold
                           shadow-md hover:shadow-lg transition-all duration-200
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Daftar Sekarang
            </button>
        </form>

        {{-- LINK LOGIN --}}
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-700">
                Sudah punya akun?
                <a href="{{ route('login') }}" 
                   class="text-blue-700 font-semibold hover:text-blue-800 hover:underline transition">
                    Login di sini
                </a>
            </p>
        </div>
    </div>

</body>
</html>