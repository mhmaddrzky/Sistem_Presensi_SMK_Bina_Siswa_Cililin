<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Sistem Presensi</title>

    {{-- TAILWIND --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: url('{{ asset("assets/img/sekolah.png") }}') center/cover no-repeat;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center relative">

    {{-- OVERLAY + BLUR --}}
    <div class="absolute inset-0 bg-blue-700 bg-opacity-70 backdrop-blur-[3px]"></div>

    {{-- CARD LOGIN --}}
    <div class="relative w-[90%] max-w-[410px] 
                bg-white/60 backdrop-blur-md
                px-8 py-10 rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.2)]
                border border-white/40">

        {{-- LOGO --}}
        <img src="{{ asset('assets/img/logo-smk.png') }}"
             class="w-[75px] mx-auto mb-3">

        {{-- JUDUL --}}
        <h2 class="text-[20px] font-extrabold text-gray-900 text-center">
            SMK BINA SISWA 2 CILILIN
        </h2>

        <p class="text-[13px] text-gray-600 text-center mb-5">
            Login Sistem Presensi
        </p>

        {{-- NOTIFIKASI --}}
        @if(session('success'))
            <p class="text-green-600 text-center mb-3 text-sm">
                {{ session('success') }}
            </p>
        @endif

        @if(session('error'))
            <p class="text-red-600 text-center mb-3 text-sm">
                {{ session('error') }}
            </p>
        @endif

        {{-- FORM LOGIN --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- USERNAME (SUDAH RAPIH) --}}
            <input type="text" 
                name="username" 
                value="{{ old('username') }}"
                placeholder="Username"
                class="w-full bg-gray-100 border border-gray-300 rounded-xl 
                                       px-4 py-3 mb-3 text-[13px] shadow-sm 
                                       focus:outline-none focus:border-blue-600"
                required>

            {{-- PASSWORD + ICON (UKURAN SAMA DENGAN USERNAME) --}}
            <div class="relative mb-5">
                <input 
                    id="password"
                    type="password" 
                    name="password"
                    placeholder="Password"
                    class="w-full bg-gray-100 border border-gray-300 rounded-xl 
                           px-4 py-3 text-[13px] shadow-sm
                           focus:outline-none focus:border-blue-600"
                    required
                >

                {{-- BUTTON ICON --}}
                <button 
                    type="button"
                    onclick="togglePassword()"
                    class="absolute right-3 top-3 text-gray-600"
                >
                    {{-- Mata terbuka --}}
                    <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" 
                        class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 
                              8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 
                              7-4.478 0-8.268-2.943-9.542-7z" />
                    </svg>

                    {{-- Mata tertutup --}}
                    <svg id="eyeClose" xmlns="http://www.w3.org/2000/svg" 
                        class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 
                              0-8.268-2.943-9.542-7a9.967 9.967 0 012.293-3.95M6.18 
                              6.18C7.797 5.147 9.82 4.5 12 4.5c4.478 0 
                              8.268 2.943 9.542 7a9.97 9.97 0 01-4.308 
                              5.568M6.18 6.18L3 3m3.18 3.18l12.64 12.64M21 
                              21l-3.46-3.46" />
                    </svg>
                </button>
            </div>

            {{-- BUTTON LOGIN --}}
            <button type="submit"
                class="w-full py-3 rounded-xl text-white text-[14px] font-semibold
                       bg-[#2163F6] hover:bg-blue-700 transition shadow-md">
                Login
            </button>
        </form>

        {{-- REGISTER --}}
        <p class="text-[12px] text-center mt-4">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-blue-700 font-semibold">
                Daftar di sini
            </a>
        </p>
    </div>

    {{-- SCRIPT TOGGLE PASSWORD --}}
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClose = document.getElementById('eyeClose');

            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClose.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeClose.classList.add('hidden');
                eyeOpen.classList.remove('hidden');
            }
        }
    </script>

</body>
</html>
