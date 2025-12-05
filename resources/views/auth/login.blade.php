<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Sistem Presensi</title>

    {{-- Tailwind CDN (AMAN, TIDAK PAKAI VITE) --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-b from-blue-700 to-blue-500">

    {{-- CARD LOGIN --}}
    <div class="bg-white w-[380px] rounded-2xl shadow-2xl px-8 py-10 text-center">

        {{-- LOGO SEKOLAH --}}
        <img src="{{ asset('assets/img/logo-smk.png') }}"
             alt="Logo Sekolah"
             class="w-[70px] mx-auto mb-2">

        {{-- JUDUL --}}
        <h2 class="text-[19px] font-bold mb-1">Login Sistem Presensi</h2>

        {{-- SUB JUDUL --}}
        <p class="text-[12px] text-gray-500 mb-4">
            SMK Bina Siswa 2 Cililin
        </p>

        {{-- NOTIFIKASI --}}
        @if(session('success'))
            <p class="text-green-600 text-sm mb-2">{{ session('success') }}</p>
        @endif

        @if(session('error'))
            <p class="text-red-600 text-sm mb-2">{{ session('error') }}</p>
        @endif

        {{-- FORM LOGIN --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- USERNAME --}}
            <input type="text" name="username" value="{{ old('username') }}"
                placeholder="Username"
                class="w-full bg-gray-100 border rounded-xl px-4 py-[10px] mb-3 text-[13px] focus:outline-none focus:border-blue-600"
                required>

            @error('username')
                <p class="text-red-500 text-[11px] text-left mb-1">{{ $message }}</p>
            @enderror

            {{-- PASSWORD --}}
            <input type="password" name="password"
                placeholder="Password"
                class="w-full bg-gray-100 border rounded-xl px-4 py-[10px] mb-4 text-[13px] focus:outline-none focus:border-blue-600"
                required>

            {{-- TOMBOL LOGIN --}}
            <button type="submit"
                class="w-full bg-[#5b9bd5] hover:bg-[#3f7fc0] text-white rounded-xl py-[10px] text-[13px] font-bold">
                Login
            </button>
        </form>

        {{-- LINK REGISTER --}}
        <p class="text-[11px] mt-3">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-blue-600 font-semibold">
                Daftar di sini
            </a>
        </p>
    </div>

</body>
</html>
