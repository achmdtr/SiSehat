<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SiSehat</title>

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
        background: #f9fafb; /* Backup background */
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
        background: #000;
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
        background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('/images/bg_login.jpg');
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
        height: 45px; /* Sesuaikan tinggi logo agar pas */
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
        flex: 1; /* Mengambil sisa ruang */
        display: flex;
        justify-content: center; 
        align-items: center;
        background: #ffffff;
        padding: clamp(30px, 5vw, 60px); /* Padding dinamis, MENGGANTIKAN padding-left yang kaku */
    }

    .form-wrapper {
        width: 100%;
        max-width: 480px; /* Disamakan dengan Register */
        display: flex;
        flex-direction: column;
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
        gap: 20px; /* Jarak antar input sedikit diperlebar agar tidak sesak */
    }

    /* Tambahan grup input untuk kerapian (opsional tapi disarankan) */
    .input-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .input-group label {
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }

    input {
        width: 100%;
        padding: 14px 16px; 
        border-radius: 8px;
        border: 1px solid #d1d5db;
        font-size: 15px;
        transition: all 0.2s ease;
        background: #fcfcfc; /* Disamakan dengan Register */
    }

    input:focus {
        border-color: #1e5eff;
        background: #ffffff;
        outline: none;
        box-shadow: 0 0 0 4px rgba(30, 94, 255, 0.1);
    }

    .error {
        color: #d93025;
        font-size: 14px;
        background: #fce8e6;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        border-left: 4px solid #d93025;
    }

    button {
        width: 100%;
        padding: 14px 16px; 
        background: #064ECC;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.2s ease, transform 0.1s ease;
        margin-top: 10px; /* Disamakan dengan Register */
    }

    button:hover {
        background: #064ECC; /* Mengikuti warna baru yang Anda masukkan */
        opacity: 0.9;
    }

    button:active {
        transform: scale(0.98); /* Efek klik */
    }

    .small {
        text-align: center;
        margin-top: 24px; /* Disamakan dengan Register */
        font-size: 14px;
        color: #555;
    }

    .small a {
        color: #064ECC;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }

    .small a:hover {
        color: #174ad6;
        text-decoration: underline;
    }

    /* 🔥 RESPONSIVE */
    @media (max-width: 900px) {
        .container {
            flex-direction: column;
        }

        .left {
            flex: none; 
            padding: 100px 30px 60px; /* Padding disesuaikan untuk mobile */
        }

        .logo {
            top: 30px;
            left: 30px;
        }

        .right {
            flex: 1; 
            padding: 40px 30px;
        }

        .form-wrapper {
            max-width: 100%; /* Memaksimalkan lebar di layar HP */
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

            <h2>Masuk</h2>
            <p>Masuk lagi untuk memulai perjalanan menuju organisasi yang lebih sehat.</p>

            @if ($errors->any())
                <div class="error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="input-group">
                    <label for="email">Username</label>
                    <input 
                        type="text" 
                        id="email"
                        name="email" 
                        placeholder="Masukkan Username"
                        value="{{ old('email') }}"
                        required
                    >
                </div>

                <div class="input-group">
                    <label for="password">Kata Sandi</label>
                    <input 
                        type="password" 
                        id="password"
                        name="password" 
                        placeholder="Minimal 8 karakter"
                        required
                    >
                </div>

                <button type="submit">Masuk</button>
            </form>

            <div class="small">
                Belum memiliki akun?
                <a href="{{ route('register') }}">Daftar</a>
            </div>

        </div>
    </div>

</div>

</body>
</html>