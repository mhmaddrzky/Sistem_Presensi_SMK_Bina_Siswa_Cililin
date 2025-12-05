<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Login Sistem Presensi</title>

</head>

<body>

    <h1>Login Sistem Presensi Laboratorium</h1>



    @if(session('success'))

        <p style="color: green;">{{ session('success') }}</p>

    @endif

    @if(session('error'))

        <p style="color: red;">{{ session('error') }}</p>

    @endif

    @error('username')

        <p style="color: red;">{{ $message }}</p>

    @enderror



    {{-- Form Login --}}

    <form method="POST" action="{{ route('login') }}">

        @csrf

        

        <div>

            <label for="username">Username:</label>

            <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus>

        </div>



        <div>

            <label for="password">Password:</label>

            <input type="password" id="password" name="password" required>

        </div>

        

        <button type="submit">Login</button>

    </form>

    

    <p>Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>

</body>

</html>

