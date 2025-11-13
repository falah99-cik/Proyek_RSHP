<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Karyawan - RSHP Unair</title>

    <link rel="stylesheet" href="{{ asset('assets/css/auth/login.css') }}">

</head>

<body>
    <div class="bubbles">
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
    </div>

    <div class="login-container">
        <div class="login-header">
            <img src="https://rshp.unair.ac.id/wp-content/uploads/2024/06/UNIVERSITAS-AIRLANGGA-scaled.webp" alt="Logo Universitas Airlangga" />
        </div>
        <p class="subtitle">Silakan login untuk mengakses dashboard Anda.</p>

        @if ($errors->any())
            <div class="alert error" style="
                background-color: #ffe5e5; 
                color: #cc0000; 
                padding: 10px; 
                border-radius: 8px; 
                margin-bottom: 20px; 
                border: 1px solid #ff9999;
                text-align: center;
            ">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" value="{{ old('email') }}" required />
            </div>
            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password" required />
            </div>
            <button type="submit" class="login-btn">Masuk</button>
        </form>

        <a href="{{ url('/') }}" class="back-btn">Kembali ke Beranda</a>
    </div>
</body>

</html>