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

    {{-- OVERLAY --}}
    <div class="absolute inset-0 bg-blue-700 bg-opacity-70 backdrop-blur-[3px]"></div>

    {{-- CARD LOGIN --}}
    <div class="relative w-[92%] max-w-[420px]
                bg-white/60 backdrop-blur-md
                px-8 py-10 rounded-3xl
                shadow-[0_15px_45px_rgba(0,0,0,0.25)]
                border border-white/40">

        {{-- LOGO --}}
        <img src="{{ asset('assets/img/logo-smk.png') }}"
             class="w-[70px] mx-auto mb-4">

        {{-- JUDUL --}}
        <h2 class="text-[20px] font-extrabold text-gray-900 text-center">
            SMK BINA SISWA 2 CILILIN
        </h2>

        <p class="text-[13px] text-gray-700 text-center mb-6">
            Login Sistem Presensi
        </p>

        {{-- NOTIFIKASI SESSION --}}
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
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            {{-- USERNAME --}}
            <div>
                <input type="text"
                    name="username"
                    value="{{ old('username') }}"
                    placeholder="Username"
                    class="w-full bg-gray-100 border @error('username') border-red-500 @else border-gray-300 @enderror
                           rounded-xl px-4 py-3 text-[13px] shadow-sm focus:outline-none focus:border-blue-600">
                
                {{-- ERROR --}}
                @error('username')
                    <p class="text-red-600 text-xs mt-1 ml-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div>
                <div class="relative">
                    <input id="password"
                        type="password"
                        name="password"
                        placeholder="Password"
                        class="w-full bg-gray-100 border @error('password') border-red-500 @else border-gray-300 @enderror
                               rounded-xl px-4 py-3 text-[13px] shadow-sm focus:outline-none focus:border-blue-600">

                    {{-- ICON --}}
                    <button type="button" onclick="togglePassword()"
                        class="absolute right-3 top-3 text-gray-600">
                        <svg id="eyeOpen" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 
                                  8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 
                                  7-4.478 0-8.268-2.943-9.542-7z" />
                        </svg>

                        <svg id="eyeClose" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6.18 6.18L3 3m3.18 3.18A9.965 9.965 0 0112 4.5c4.478 0 
                                8.268 2.943 9.542 7a9.97 9.97 0 01-4.308 
                                5.568M6.18 6.18A9.97 9.97 0 003.458 
                                12c1.274 4.057 5.064 7 9.542 
                                7 2.033 0 3.924-.605 5.502-1.641M21 
                                21l-3.46-3.46" />
                        </svg>
                    </button>
                </div>

                {{-- ERROR --}}
                @error('password')
                    <p class="text-red-600 text-xs mt-1 ml-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- AKUN TIDAK TERDAFTAR --}}
            @if(session('unregistered'))
                <p class="text-red-600 text-xs text-center">{{ session('unregistered') }}</p>
            @endif

            {{-- BUTTON LOGIN --}}
            <button type="submit"
                    class="w-full bg-[#2163F6] hover:bg-blue-700
                           text-white py-3 rounded-xl text-[14px] font-semibold shadow-md transition">
                Login
            </button>
        </form>

        {{-- REGISTER --}}
        <p class="text-[12px] text-center mt-5">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-blue-700 font-semibold hover:underline">
                Daftar di sini
            </a>
        </p>
    </div>

    {{-- SCRIPT --}}
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
                eyeOpen.classList.remove('hidden');
                eyeClose.classList.add('hidden');
            }
        }
    </script>

</body>
</html>
