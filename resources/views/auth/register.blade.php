<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - SiSehat</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Poppins', sans-serif;
    }

    body {
        min-height: 100vh;
        overflow-x: hidden;
        background: #f9fafb;
    }

    .container {
        display: flex;
        min-height: 100vh;
        width: 100%;
    }

    /* ================= LEFT SIDE ================= */
    .left {
        flex: 1.2;
        position: relative;
        background: #000; /* Warna dasar di balik gambar */
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: clamp(40px, 8vw, 80px);
        overflow: hidden;
    }

    .left::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.4)), url('/images/bg_login.jpg');
        background-size: cover;
        background-position: center;
        opacity: 0.6; /* Sesuaikan nilai ini (0.0 - 1.0) */
        z-index: 1;
    }

    .left > * {
        position: relative;
        z-index: 2;
    }

    .logo img {
        height: 45px;
        width: auto;
    }

    .logo {
        position: absolute;
        top: 40px;
        left: clamp(40px, 8vw, 80px);
        display: flex;
        align-items: center;
    }

    .left h1 {
        font-size: clamp(32px, 4vw, 48px);
        font-weight: 800; /* Dibuat Bold */
        max-width: 500px;
        line-height: 1.2;
        margin-bottom: 20px;
    }

    .left p {
        max-width: 450px;
        opacity: 0.9;
        font-size: clamp(15px, 1.2vw, 18px);
        line-height: 1.6;
    }

    /* ================= RIGHT SIDE ================= */
    .right {
        flex: 1;
        display: flex;
        justify-content: center; 
        align-items: center;
        background: #ffffff;
        padding: clamp(30px, 5vw, 60px);
        overflow-y: auto; /* Memungkinkan scroll jika layar kecil/pendek */
    }

    .form-wrapper {
        width: 100%;
        max-width: 480px; /* Sedikit dilebarkan untuk menampung form yang lebih banyak */
        display: flex;
        flex-direction: column;
        margin: auto 0; /* Vertikal center yang lebih baik saat ada scroll */
    }

    .form-wrapper h2 {
        font-size: clamp(28px, 3vw, 36px);
        font-weight: 700; /* Dibuat Bold */
        color: #111;
        margin-bottom: 8px;
    }

    .form-wrapper > p {
        font-size: 15px;
        color: #666;
        margin-bottom: 32px;
        line-height: 1.5;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 18px; 
    }

    .input-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        position: relative;
    }

    .input-group label {
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="number"] {
        width: 100%;
        padding: 14px 16px; 
        border-radius: 8px;
        border: 1px solid #d1d5db;
        font-size: 15px;
        transition: all 0.2s ease;
        background: #fcfcfc;
    }

    input:focus {
        border-color: #1e5eff;
        background: #ffffff;
        outline: none;
        box-shadow: 0 0 0 4px rgba(30, 94, 255, 0.1);
    }

    /* --- Custom Radio Buttons --- */
    .radio-container {
        display: flex;
        gap: 15px;
    }

    .radio-box {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #fcfcfc;
    }

    .radio-box:hover {
        border-color: #1e5eff;
    }

    .radio-box input[type="radio"] {
        accent-color: #1e5eff; /* Mengubah warna radio button bawaan browser */
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    .error-msg {
        color: #d93025;
        font-size: 13px;
        margin-top: 10px;
        line-height: 1.4;
        display: block;
    }

    button {
        width: 100%;
        padding: 14px 16px; 
        background: #0d47a1; /* Biru gelap sesuai mockup */
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.2s ease, transform 0.1s ease;
        margin-top: 10px;
    }

    button:hover {
        background: #0a367a;
    }

    button:active {
        transform: scale(0.98);
    }

    .small {
        text-align: center;
        margin-top: 24px;
        font-size: 14px;
        color: #555;
    }

    .small a {
        color: #0d47a1;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }

    .small a:hover {
        text-decoration: underline;
    }

    /* 🔥 RESPONSIVE */
    @media (max-width: 900px) {
        .container {
            flex-direction: column;
        }

        .left {
            flex: none; 
            padding: 80px 30px 40px; 
        }

        .logo {
            top: 20px;
            left: 30px;
        }

        .right {
            flex: 1; 
            padding: 40px 30px;
            overflow-y: visible;
        }
    }
    </style>
</head>
<body>

<div class="container">

    <div class="left">
        <div class="logo">
            <img src="{{ asset('images/sisehat_logo.svg') }}" alt="SiSehat Logo">
        </div>

        <h1>Pantau Kesehatan Organisasi UMKM Anda</h1>
        <p>
            Platform analitik terpadu untuk mendiagnosa, mengevaluasi,
            dan meningkatkan performa bisnis UMKM dengan presisi klinis.
        </p>
    </div>

    <div class="right">
        <div class="form-wrapper">

            <h2>Buat Akun</h2>
            <p>Mulai perjalanan menuju organisasi yang lebih sehat.</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="input-group">
                    <label for="nama_user">Username</label>
                    <input type="text" id="nama_user" name="nama_user" placeholder="Masukkan username Anda" value="{{ old('nama_user') }}" required>
                    @error('nama_user') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="input-group">
                    <label for="age">Umur</label>
                    <input type="number" id="age" name="age" placeholder="Masukkan Umur Anda" value="{{ old('age') }}" required>
                    @error('age') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="input-group">
                    <label>Jenis Kelamin</label>
                    <div class="radio-container">
                        <label class="radio-box">
                            <input type="radio" name="gender" value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'checked' : '' }} required>
                            Laki-laki
                        </label>
                        <label class="radio-box">
                            <input type="radio" name="gender" value="Perempuan" {{ old('gender') == 'Perempuan' ? 'checked' : '' }} required>
                            Perempuan
                        </label>
                    </div>
                    @error('gender') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required>
                    @error('password') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="input-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password Anda" required>
                </div>

                <button type="submit">Daftar</button>
            </form>

            <div class="small">
                Sudah punya akun?
                <a href="{{ route('login') }}">Masuk</a>
            </div>

        </div>
    </div>

</div>

</body>
</html>