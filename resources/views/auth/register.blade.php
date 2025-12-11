<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Siswa</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: url('{{ asset("assets/img/sekolah.png") }}') center/cover no-repeat;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center relative">

    {{-- OVERLAY --}}
    <div class="absolute inset-0 bg-blue-700 bg-opacity-70 backdrop-blur-[3px]"></div>

    {{-- CARD --}}
    <div class="relative w-[92%] max-w-[400px]
                bg-white/60 backdrop-blur-m 
                px-6 py-6 rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.2)]
                border border-white/40 text-center">

        {{-- LOGO --}}
        <img src="{{ asset('assets/img/logo-smk.png') }}"
            class="w-[60px] mx-auto mb-1">

        {{-- JUDUL --}}
        <h2 class="text-[18px] font-extrabold text-gray-900 mb-1">
            Registrasi Siswa
        </h2>

        <p class="text-[11px] text-gray-600 mb-3">
            Data akan diverifikasi oleh admin
        </p>

        {{-- FORM --}}
        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- STYLE INPUT --}}
            @php
                $inputClass = "w-full bg-gray-100 border border-gray-300 rounded-xl px-3 py-2.5 mb-1.5
                               text-[12.5px] focus:outline-none focus:border-blue-600";
            @endphp

            {{-- NAMA --}}
            <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama Lengkap" class="{{ $inputClass }}" required>
            @error('nama')
                <p class="text-red-600 text-xs text-left mb-2 ml-1">{{ $message }}</p>
            @enderror

            {{-- USERNAME --}}
            <input type="text" name="username" value="{{ old('username') }}" placeholder="Username" class="{{ $inputClass }}" required>
            @error('username')
                <p class="text-red-600 text-xs text-left mb-2 ml-1">{{ $message }}</p>
            @enderror

            {{-- NIS --}}
            <input type="text" name="nis" value="{{ old('nis') }}" placeholder="NIS" class="{{ $inputClass }}" required>
            @error('nis')
                <p class="text-red-600 text-xs text-left mb-2 ml-1">{{ $message }}</p>
            @enderror

            {{-- KONFIRMASI NIS --}}
            <input type="text" name="nis_confirmation" placeholder="Konfirmasi NIS" class="{{ $inputClass }}" required>
            @error('nis_confirmation')
                <p class="text-red-600 text-xs text-left mb-2 ml-1">{{ $message }}</p>
            @enderror

            {{-- KELAS --}}
            <input type="text" name="kelas" value="{{ old('kelas') }}" placeholder="Kelas" class="{{ $inputClass }}" required>
            @error('kelas')
                <p class="text-red-600 text-xs text-left mb-2 ml-1">{{ $message }}</p>
            @enderror

            {{-- JURUSAN --}}
            <select name="jurusan"
                class="w-full bg-gray-100 border border-gray-300 rounded-xl px-3 py-2.5 mb-1.5
                       text-[12.5px] focus:outline-none focus:border-blue-600"
                required>
                <option value="">-- Pilih Jurusan --</option>
                <option value="TKJ" {{ old('jurusan') == 'TKJ' ? 'selected' : '' }}>TKJ</option>
                <option value="TBSM" {{ old('jurusan') == 'TBSM' ? 'selected' : '' }}>TBSM</option>
            </select>
            @error('jurusan')
                <p class="text-red-600 text-xs text-left mb-2 ml-1">{{ $message }}</p>
            @enderror

            {{-- BUTTON --}}
            <button type="submit"
                class="w-full bg-[#2163F6] hover:bg-blue-700 text-white rounded-xl py-2.5 text-[13px]
                       font-semibold shadow-md transition">
                DAFTAR
            </button>
        </form>

        <p class="text-[11px] mt-3">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-blue-700 font-semibold">
                Login di sini
            </a>
        </p>

    </div>

</body>
</html>
