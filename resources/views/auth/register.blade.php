<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Siswa</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-b from-blue-700 to-blue-500">

    {{-- CARD REGISTER --}}
    <div class="bg-white w-[380px] rounded-2xl shadow-2xl px-7 py-7 text-center">

        {{-- LOGO --}}
        <img src="{{ asset('assets/img/logo-smk.png') }}"
             class="w-[65px] mx-auto mb-1">

        {{-- JUDUL --}}
        <h2 class="text-[18px] font-bold mb-1">Registrasi Siswa</h2>

        {{-- SUB JUDUL --}}
        <p class="text-[11px] text-gray-500 mb-3">
            Data akan diverifikasi oleh admin
        </p>

        {{-- FORM --}}
        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- NAMA --}}
            <input type="text" name="nama" placeholder="Nama Lengkap"
                class="auth w-full bg-gray-100 border rounded-xl px-3 py-[9px] mb-2 text-[12.5px]"
                required>

            {{-- USERNAME --}}
            <input type="text" name="username" placeholder="Username"
                class="w-full bg-gray-100 border rounded-xl px-3 py-[9px] mb-2 text-[12.5px]"
                required>

            {{-- NIS --}}
            <input type="text" name="nis" placeholder="NIS"
                class="w-full bg-gray-100 border rounded-xl px-3 py-[9px] mb-2 text-[12.5px]"
                required>

            {{-- KONFIRMASI NIS --}}
            <input type="text" name="nis_confirmation" placeholder="Konfirmasi NIS"
                class="w-full bg-gray-100 border rounded-xl px-3 py-[9px] mb-2 text-[12.5px]"
                required>

            {{-- KELAS --}}
            <input type="text" name="kelas" placeholder="Kelas"
                class="w-full bg-gray-100 border rounded-xl px-3 py-[9px] mb-2 text-[12.5px]"
                required>

            {{-- JURUSAN --}}
            <select name="jurusan"
                class="w-full bg-gray-100 border rounded-xl px-3 py-[9px] mb-3 text-[12.5px]"
                required>
                <option value="">-- Pilih Jurusan --</option>
                <option value="TKJ">TKJ</option>
                <option value="TBSM">TBSM</option>
            </select>

            {{-- TOMBOL DAFTAR --}}
            <button type="submit"
                class="w-full bg-[#5b9bd5] hover:bg-[#3f7fc0] text-white rounded-xl py-[10px] text-[13px] font-bold">
                DAFTAR
            </button>
        </form>

        {{-- LINK LOGIN --}}
        <p class="text-[11px] mt-3">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-blue-600 font-semibold">
                Login di sini
            </a>
        </p>

    </div>

</body>
</html>
