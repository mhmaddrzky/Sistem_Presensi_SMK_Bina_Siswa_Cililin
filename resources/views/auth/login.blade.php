<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistem Presensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: url('{{ asset("assets/img/sekolah.png") }}') center/cover no-repeat;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center relative">

    {{-- OVERLAY BACKGROUND --}}
    <div class="absolute inset-0 bg-blue-700 bg-opacity-70 backdrop-blur-[3px]"></div>

    {{-- CARD LOGIN --}}
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
            SMK BINA SISWA 2 CILILIN
        </h2>
        <p class="text-sm text-gray-700 text-center mb-6">
            Sistem Presensi Laboratorium
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

        {{-- FORM LOGIN --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            {{-- USERNAME --}}
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                    Username
                </label>
                <input type="text"
                       id="username"
                       name="username"
                       value="{{ old('username') }}"
                       placeholder="Masukkan username Anda"
                       class="w-full bg-white/80 border @error('username') border-red-500 @else border-gray-300 @enderror
                              rounded-xl px-4 py-3 text-sm shadow-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              placeholder:text-gray-400"
                       autofocus>
                
                @error('username')
                    <p class="text-red-600 text-xs mt-1.5 ml-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                    Password
                </label>
                <div class="relative">
                    <input id="password"
                           type="password"
                           name="password"
                           placeholder="Masukkan password Anda"
                           class="w-full bg-white/80 border @error('password') border-red-500 @else border-gray-300 @enderror
                                  rounded-xl px-4 py-3 text-sm shadow-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                  placeholder:text-gray-400">

                    {{-- TOGGLE PASSWORD BUTTON --}}
                    <button type="button" 
                            onclick="togglePassword()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 transition">
                        <svg id="eyeOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                        </svg>

                        <svg id="eyeClose" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>

                @error('password')
                    <p class="text-red-600 text-xs mt-1.5 ml-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- BUTTON LOGIN --}}
            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800
                           text-white py-3 rounded-xl text-sm font-semibold
                           shadow-md hover:shadow-lg transition-all duration-200
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Masuk
            </button>
        </form>

        {{-- LINK REGISTRASI --}}
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-700">
                Belum punya akun?
                <a href="{{ route('register') }}" 
                   class="text-blue-700 font-semibold hover:text-blue-800 hover:underline transition">
                    Daftar di sini
                </a>
            </p>
        </div>
    </div>

    {{-- JAVASCRIPT TOGGLE PASSWORD --}}
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