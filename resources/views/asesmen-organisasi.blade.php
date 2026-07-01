<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiSehat - Asesmen Organisasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="{{ asset('css/responsive-layout.css') }}" rel="stylesheet">
    
    <style>
        /* Reset & Base */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f4f7f6; overflow: hidden; }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 280px; background-color: #ffffff; border-right: 1px solid #f1f5f9;
            display: flex; flex-direction: column; padding: 25px 15px; flex-shrink: 0; z-index: 10;
        }
        .logo-box { display: flex; justify-content: center; align-items: center; margin-bottom: 25px; width: 100%; }
        .logo-main { width: 150px; height: auto; display: block; }

        .btn-primary-custom {
            background-color: #0a4ebd; color: white; border: none; padding: 12px; border-radius: 8px;
            cursor: pointer; font-weight: 700; font-size: 13px; margin-bottom: 20px; width: 100%; transition: 0.3s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-primary-custom:hover { background-color: #083ba1; transform: translateY(-1px); }
                .sidebar-divider { border: none; border-top: 1.5px solid #e5e7eb; margin-top: 10px; margin-bottom: 15px; width: 100%; }

        .nav-list { list-style: none; flex-grow: 1; margin-top: 10px; }
        .nav-item { 
            display: flex; align-items: center; gap: 12px; padding: 12px 15px; 
            margin-bottom: 8px; border-radius: 10px; cursor: pointer; 
            color: #718096; transition: all 0.3s ease; 
            font-weight: 500;
        }
        .nav-icon {
            width: 20px; height: 20px; object-fit: contain;
            filter: grayscale(100%); opacity: 0.6; transition: 0.3s;
        }
        .nav-item span { font-size: 14px; line-height: normal; }
        
        .nav-item.active { 
            background-color: #eef2ff; 
            color: #1d4ed8; 
            font-weight: 700;
        }
        .nav-item.active .nav-icon { 
            filter: none; 
            opacity: 1; 
            filter: brightness(0) saturate(100%) invert(26%) sepia(89%) saturate(1966%) hue-rotate(211deg) brightness(97%) contrast(101%);
        }
        
        .nav-item:hover:not(.active) { background-color: #f8fafc; color: #1e293b; }
        .nav-item:hover .nav-icon { opacity: 0.9; filter: grayscale(0%); }

        .profile-card { 
            margin-top: auto; 
            padding: 12px 15px; 
            background-color: #eef2ff; 
            border-radius: 12px; 
            display: flex; 
            align-items: center; 
            gap: 12px;
            cursor: pointer;
            transition: 0.3s;
        }
        .profile-card:hover { background-color: #e0e7ff; }
        .avatar-sidebar { width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; overflow: hidden; }
        .avatar-sidebar img { width: 100%; height: 100%; object-fit: cover; }

        .profile-info { flex-grow: 1; text-align: left; }
        .profile-info h4 { font-size: 14px; color: #1e293b; font-weight: 800; line-height: 1.2; }
        .profile-info p { font-size: 11px; color: #64748b; font-weight: 600; }
        
        .logout-btn-small {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
        }
        .logout-btn-small:hover {
            background-color: #fee2e2;
        }
        .logout-btn-small svg {
            width: 20px;
            height: 20px;
        }

        .flash-info-banner {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        /* ================= MAIN CONTENT ================= */
        .content-area { flex-grow: 1; padding: 30px 40px; overflow-y: auto; scroll-behavior: smooth; display: flex; flex-direction: column; gap: 20px; }
        
        /* Header & Progress Bar */
        .header-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 18px; }
        .header-title-wrapper { display: flex; flex-direction: column; gap: 5px;}
        .kuesioner-label { font-size: 14px; font-weight: 700; color: #64748b; letter-spacing: 1px; text-transform: uppercase; }
        .header-title-wrapper h1 { font-size: 24px; color: #1e293b; font-weight: 800; }
        .header-title-wrapper p { font-size: 13px; color: #64748b; max-width: 600px; line-height: 1.5; }
        
        .estimasi-waktu { 
            display: inline-flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; 
            color: #475569; border: 1px solid #cbd5e1; padding: 8px 16px; border-radius: 20px; background-color: #ffffff;
        }

        .progress-card { background: #ffffff; border-radius: 12px; padding: 20px 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; }
        .progress-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
        .progress-header h3 { font-size: 14px; font-weight: 700; color: #1e293b; }
        .progress-count { font-size: 13px; font-weight: 700; color: #2563eb; }
        .progress-track { width: 100%; height: 8px; background-color: #e2e8f0; border-radius: 4px; overflow: hidden; margin-bottom: 10px; }
        .progress-fill { height: 100%; background-color: #2563eb; border-radius: 4px; transition: width 0.3s ease; }
        .progress-info { font-size: 12px; color: #64748b; }

        /* Main Grid */
        .asesmen-grid { display: grid; grid-template-columns: 260px 1fr; gap: 25px; align-items: flex-start; margin-top: 20px; }

        /* Tips Panel */
        .tips-panel { background: transparent; }
        .tips-panel h4 { font-size: 11px; font-weight: 800; color: #64748b; margin-bottom: 10px; letter-spacing: 0.5px; }
        .tips-card { background: #EEF4FF; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; }
        .tips-card h4 { font-size: 11px; font-weight: 800; color: #0d47a1; margin-bottom: 12px; letter-spacing: 0.5px; }
        .tips-card p { font-size: 12px; color: #475569; line-height: 1.6; }

        .tips-card--karyawan { background: #EEF2FF; }
        .tips-card--karyawan .petunjuk-judul {
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 12px;
            letter-spacing: 0;
            text-transform: none;
        }
        .tips-card--karyawan .petunjuk-intro {
            font-size: 12px;
            color: #334155;
            line-height: 1.65;
            margin-bottom: 0;
        }
        .petunjuk-skala {
            list-style: none;
            margin: 14px 0 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .petunjuk-skala-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 12px;
            color: #334155;
            line-height: 1.45;
        }
        .petunjuk-skala-num {
            flex-shrink: 0;
            width: 26px;
            height: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #dbeafe;
            color: #1e40af;
            font-weight: 800;
            font-size: 12px;
            border-radius: 4px;
        }
        .petunjuk-skala-item strong { color: #0f172a; font-weight: 800; }

        /* Questions Box */
        .questions-box { 
            background: #ffffff; border-radius: 12px; padding: 30px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #e2e8f0; 
            position: relative; overflow: hidden;
        }
        .questions-box::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 8px;
            background-color: #004AC6;
        }
        .q-section-title { margin-bottom: 30px; }
        .q-section-title h2 { font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 5px; }
        .q-section-title p { font-size: 13px; color: #64748b; }

        .question-item { background: #D4E4FA; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .question-text { font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 15px; line-height: 1.5; }
        .options-group { display: flex; gap: 15px; }
        
        .option-btn { 
            flex: 1; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; 
            padding: 10px 15px; font-size: 13px; font-weight: 600; color: #475569; 
            cursor: pointer; transition: all 0.2s; text-align: center;
        }
        .option-btn:hover { border-color: #94a3b8; background: #f1f5f9; }
        .option-btn.selected { background: #e0e7ff; border-color: #4f46e5; color: #4f46e5; }

        /* Navigation Buttons */
        .nav-buttons { display: flex; justify-content: space-between; align-items: center; margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 20px;}
        .btn-outline { background: transparent; border: 1px solid #cbd5e1; color: #475569; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; transition: 0.2s; }
        .btn-outline:hover { background: #f1f5f9; color: #1e293b; }
        .btn-primary { background-color: #2563eb; color: white; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; transition: 0.2s; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2); }
        .btn-primary:hover { background-color: #1d4ed8; }

        /* ================= MODAL SELESAI ================= */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(4px);
            display: flex; justify-content: center; align-items: center;
            z-index: 9999; opacity: 0; visibility: hidden; transition: all 0.3s;
        }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-box {
            background: #ffffff; padding: 40px; border-radius: 16px;
            width: 90%; max-width: 500px; text-align: center;
            transform: translateY(-30px); transition: 0.3s;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .modal-overlay.active .modal-box { transform: translateY(0); }
        .modal-icon { width: 60px; height: 60px; background-color: #10b981; color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin: 0 auto 20px; }
        .modal-icon svg { width: 30px; height: 30px; }
        
        .modal-title { font-size: 22px; font-weight: 800; color: #1e293b; margin-bottom: 15px; }
        .modal-desc { font-size: 14px; color: #64748b; line-height: 1.6; margin-bottom: 25px; }

        .status-box { display: flex; justify-content: space-between; align-items: center; background: #f8fafc; border-radius: 12px; padding: 20px; margin-bottom: 30px; text-align: left; }
        .status-item h6 { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px; letter-spacing: 0.5px; }
        .status-item p { font-size: 13px; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 6px; }
        .status-item p .check-icon { width: 14px; height: 14px; background: #10b981; color: white; border-radius: 50%; display: inline-flex; justify-content: center; align-items: center; font-size: 8px; }

        .modal-actions { display: flex; gap: 15px; }
        .btn-modal-primary { flex: 1; background-color: #2563eb; color: white; border: none; padding: 12px; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; transition: 0.2s; }
        .btn-modal-primary:hover { background-color: #1d4ed8; }
        .btn-modal-outline { flex: 1; background-color: transparent; color: #2563eb; border: 1px solid #2563eb; padding: 12px; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; transition: 0.2s; display: flex; justify-content: center; align-items: center; gap: 6px; }
        .btn-modal-outline:hover { background-color: #eff6ff; }
        a.btn-modal-outline {
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            box-sizing: border-box;
        }

        /* Role Status Bar */
        .role-status-bar {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .role-status-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            transition: 0.3s;
        }
        .role-status-chip.completed {
            background: #ecfdf5;
            border-color: #10b981;
            color: #047857;
        }
        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #cbd5e1;
        }
        .completed .status-indicator {
            background: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }

        /* ================= WELCOME SCREEN ================= */
        .welcome-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
            padding: 40px 0;
        }
        .welcome-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px;
            max-width: 680px;
            width: 100%;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            border: 1px solid #e2e8f0;
            text-align: center;
        }
        .welcome-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eff6ff;
            color: #2563eb;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
        }
        .welcome-title {
            font-size: 26px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 12px;
            line-height: 1.3;
        }
        .welcome-desc {
            font-size: 13.5px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 30px;
            max-width: 580px;
            margin-left: auto;
            margin-right: auto;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 35px;
            text-align: left;
        }
        .info-item {
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            padding: 18px;
            display: flex;
            gap: 15px;
            align-items: flex-start;
        }
        .info-icon-box {
            width: 40px;
            height: 40px;
            background: #eef2ff;
            color: #2563eb;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .info-item-text h5 {
            font-size: 13.5px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }
        .info-item-text p {
            font-size: 11.5px;
            color: #64748b;
            line-height: 1.5;
        }
        .btn-start-assessment {
            background-color: #2563eb;
            color: #ffffff;
            border: none;
            padding: 14px 35px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .btn-start-assessment:hover {
            background-color: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
        }
        .btn-start-assessment:active {
            transform: translateY(0);
        }

        /* ================= COMPLETED STATE ================= */
        .assessment-completed-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            width: 100%;
            animation: fadeIn 0.5s ease-out;
        }
        .completed-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            border: 1px solid #f1f5f9;
            padding: 40px;
            max-width: 650px;
            width: 100%;
            text-align: center;
        }
        .completed-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ecfdf5;
            color: #10b981;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 24px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .completed-title {
            font-size: 26px;
            color: #1e293b;
            font-weight: 800;
            margin-bottom: 16px;
            line-height: 1.3;
        }
        .completed-desc {
            font-size: 15px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 32px;
        }
        .status-summary-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 35px;
            background: #f8fafc;
            padding: 24px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            text-align: left;
        }
        @media (max-width: 500px) {
            .status-summary-grid {
                grid-template-columns: 1fr;
            }
        }
        .summary-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .summary-label {
            font-size: 11px;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .summary-value {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .summary-value.status-finished {
            color: #10b981;
        }
        .dot-indicator {
            width: 8px;
            height: 8px;
            background-color: #10b981;
            border-radius: 50%;
            display: inline-block;
            animation: pulse-dot 1.5s infinite;
        }
        @keyframes pulse-dot {
            0% { opacity: 0.4; }
            50% { opacity: 1; }
            100% { opacity: 0.4; }
        }
        .completed-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
        }
        @media (max-width: 550px) {
            .completed-actions {
                flex-direction: column;
            }
        }
        .btn-completed-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: #2563eb;
            color: #ffffff;
            border: none;
            padding: 14px 28px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }
        .btn-completed-primary:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
        }
        .btn-completed-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: transparent;
            color: #2563eb;
            border: 1px solid #2563eb;
            padding: 14px 28px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-completed-outline:hover {
            background: #eff6ff;
            transform: translateY(-1px);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo-box">
            <img src="{{ asset('images/sisehat_logo.svg') }}" alt="SiSehat" class="logo-main" onerror="this.style.display='none'; this.insertAdjacentHTML('afterend', '<h2 style=\'color:#0d47a1; font-weight:900;\'>SISEHAT</h2>');">
        </div>
        
        @if(auth()->user()->role !== 'employee')
        <button class="btn-primary-custom" onclick="window.location='{{ route('dashboard.tambah-umkm') }}'">+ Tambahkan UMKM Anda</button>
        @endif
        <div class="sidebar-divider"></div>
        
        <ul class="nav-list">
            @if(auth()->user()->role !== 'employee')
            <li class="nav-item" onclick="window.location='{{ route('dashboard') }}'">
                <img src="{{ asset('images/Beranda_logo.svg') }}" class="nav-icon" onerror="this.src='https://cdn-icons-png.flaticon.com/512/25/25694.png'">
                <span>Beranda</span>
            </li>
            @endif
            <li class="nav-item active" onclick="window.location='{{ route('dashboard.asesmen') }}'">
                <img src="{{ asset('images/Asesmen_Organisasi_logo.svg') }}" class="nav-icon" onerror="this.src='https://cdn-icons-png.flaticon.com/512/1161/1161388.png'">
                <span>Asesmen Organisasi</span>
            </li>
            @if(auth()->user()->role !== 'employee')
            <li class="nav-item" onclick="window.location='{{ route('dashboard.faktor') }}'">
                <img src="{{ asset('images/6_Faktor_Penilaian_logo.svg') }}" class="nav-icon" onerror="this.src='https://cdn-icons-png.flaticon.com/512/138/138439.png'">
                <span>6 Faktor Penilaian</span>
            </li>
            <li class="nav-item" onclick="window.location='{{ route('dashboard.rekomendasi') }}'">
                <img src="{{ asset('images/Rekomendasi_logo.svg') }}" class="nav-icon" onerror="this.src='https://cdn-icons-png.flaticon.com/512/151/151300.png'">
                <span>Rekomendasi</span>
            </li>
            <li class="nav-item" onclick="window.location='{{ route('dashboard.tambah-umkm') }}'">
                <img src="{{ asset('images/Tambah_Data_UMKM_logo.svg') }}" class="nav-icon" onerror="this.src='https://cdn-icons-png.flaticon.com/512/2990/2990315.png'">
                <span>Tambah Data UMKM</span>
            </li>
            <li class="nav-item" onclick="window.location='{{ route('dashboard.tambah-karyawan') }}'">
                <img src="{{ asset('images/Tambah_Data_Karyawan_logo.svg') }}" class="nav-icon" style="width: 24px; height: 24px;" onerror="this.src='https://cdn-icons-png.flaticon.com/512/3135/3135715.png'">
                <span>Tambah Data Karyawan</span>
            </li>
            <li class="nav-item" onclick="window.location='{{ route('dashboard.manajemen-umkm') }}'">
                <img src="{{ asset('images/Manajemen_UMKM_logo.svg') }}" class="nav-icon" onerror="this.src='https://cdn-icons-png.flaticon.com/512/104/104646.png'">
                <span>Manajemen UMKM</span>
            </li>
            @endif
        </ul>

        <div class="profile-card">
            <div class="avatar-sidebar">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Riski') }}&background=6366f1&color=fff&bold=true" class="nav-icon">
            </div>
            <div class="profile-info">
                <h4>{{ auth()->user()->name ?? 'Riski' }}</h4>
                <x-sidebar-role-subtitle />
            </div>
            <button class="logout-btn-small" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Keluar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>

    </div>

    <div class="content-area">
        @if(session('info'))
        <div class="flash-info-banner">{{ session('info') }}</div>
        @endif

        @if(isset($alreadyFinished) && $alreadyFinished)
            <!-- Tampilan Halaman Asesmen Sudah Selesai (Dedicated View) -->
            <div class="assessment-completed-container">
                <div class="completed-card">
                    <div class="completed-badge">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        Asesmen Selesai
                    </div>
                    
                    <h1 class="completed-title">Asesmen Kesehatan Organisasi</h1>
                    
                    @if(auth()->user()->role === 'employee')
                        <p class="completed-desc">
                            Terima kasih, <strong>{{ auth()->user()->name }}</strong>. Jawaban kuesioner Anda telah berhasil disimpan dan digabungkan dengan asesmen pemilik UMKM untuk penyusunan rekomendasi organisasi.
                        </p>
                    @else
                        <p class="completed-desc">
                            Terima kasih, <strong>{{ auth()->user()->name }}</strong>. Seluruh data asesmen untuk perusahaan/UMKM Anda telah berhasil disimpan dan sedang dianalisis oleh sistem kami.
                        </p>
                    @endif

                    <div class="status-summary-grid">
                        <div class="summary-item">
                            <span class="summary-label">Status Asesmen</span>
                            <span class="summary-value status-finished">
                                <span class="dot-indicator"></span> Selesai
                            </span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Tanggal Pengisian</span>
                            <span class="summary-value">
                                {{ \Carbon\Carbon::parse($activeAssessment->finished_at ?? $activeAssessment->updated_at ?? now())->translatedFormat('d F Y') }}
                            </span>
                        </div>
                        @if(auth()->user()->role === 'owner' && isset($totalEmployees) && $totalEmployees > 0)
                        <div class="summary-item">
                            <span class="summary-label">Partisipasi Karyawan</span>
                            <span class="summary-value">
                                {{ $employeesFinishedCount }} dari {{ $totalEmployees }} Karyawan
                            </span>
                        </div>
                        @endif
                    </div>

                    <div class="completed-actions">
                        <a href="{{ route('dashboard') }}" class="btn-completed-primary">
                            Kembali ke Beranda
                        </a>
                        <a href="{{ route('dashboard.asesmen-ringkasan-pdf') }}" class="btn-completed-outline" target="_blank" rel="noopener noreferrer">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            Unduh Ringkasan (PDF)
                        </a>
                    </div>
                </div>
            </div>
        @else
            @php
                $showWelcome = isset($sections) && count($sections) > 0;
            @endphp

            <!-- Welcome / Confirmation Screen -->
            <div id="welcomeContainer" class="welcome-container" style="{{ $showWelcome ? 'display: flex;' : 'display: none;' }}">
                <div class="welcome-card">
                    <div class="welcome-badge">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path><path d="m9 12 2 2 4-4"></path></svg>
                        Siap Memulai
                    </div>
                    <h1 class="welcome-title">Asesmen Kesehatan Organisasi</h1>
                    <p class="welcome-desc">
                        Evaluasi komprehensif terhadap 6 faktor kunci budaya dan operasional perusahaan Anda. Jawaban Anda akan membantu kami menyusun rekomendasi kesehatan organisasi yang tepat dan akurat.
                    </p>

                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-icon-box">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            </div>
                            <div class="info-item-text">
                                <h5>Estimasi Waktu</h5>
                                <p>Memerlukan waktu sekitar 15 menit untuk menyelesaikan seluruh pertanyaan.</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon-box">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
                            </div>
                            <div class="info-item-text">
                                <h5>6 Faktor Evaluasi</h5>
                                <p>Nilai organisasi, kinerja ekonomi, lingkungan kerja, kepemimpinan, kepuasan kerja, & manajemen.</p>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn-start-assessment" id="btnStartAssessment">
                        Mulai Asesmen Sekarang
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </button>
                </div>
            </div>

            <!-- Actual Assessment Content -->
            <div id="assessmentContent" style="{{ !$showWelcome ? 'display: block;' : 'display: none;' }}">
                <div class="header-top">
                    <div class="header-title-wrapper">
                        <span class="kuesioner-label">KUESIONER</span>
                        <h1>Asesmen Kesehatan Organisasi</h1>
                        <p>Evaluasi komprehensif terhadap 6 faktor kunci budaya dan operasional perusahaan Anda. Jawablah sesuai dengan kondisi aktual.</p>
                    </div>
                    <div class="estimasi-waktu">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        Estimasi Waktu: 15 Menit
                    </div>
                </div>

                <div class="role-status-bar">
                    <div id="chipOwner" class="role-status-chip {{ $ownerFinished ? 'completed' : '' }}">
                        <div class="status-indicator"></div>
                        <span id="textOwner">Pemilik: {{ $ownerFinished ? 'Sudah Mengisi' : 'Belum Mengisi' }}</span>
                    </div>
                    @if(isset($totalEmployees) && $totalEmployees > 0)
                    <div id="chipEmployees" class="role-status-chip {{ $employeesFinishedCount >= $totalEmployees ? 'completed' : '' }}">
                        <div class="status-indicator"></div>
                        <span id="textEmployees">Karyawan: {{ $employeesFinishedCount }}/{{ $totalEmployees }} Selesai</span>
                    </div>
                    @endif
                </div>

                <div class="progress-card">
                    <div class="progress-header">
                        <h3>Progres Asesmen</h3>
                        <span class="progress-count" id="progressCount">6 / 18 Pertanyaan Selesai</span>
                    </div>
                    <div class="progress-track">
                        <div class="progress-fill" id="progressBar" style="width: 33.33%;"></div>
                    </div>
                    <div class="progress-info" id="progressInfo">Anda sedang mengisi bagian "Nilai Organisasi"</div>
                </div>

                <div class="asesmen-grid">
                    <div class="tips-panel">
                        @if(auth()->user()->role === 'employee')
                        <div class="tips-card tips-card--karyawan">
                            <h4 class="petunjuk-judul">Petunjuk Pengisian</h4>
                            <p class="petunjuk-intro">Evaluasi sejauh mana organisasi Anda mampu mengeksekusi strategi yang telah direncanakan. Pilih skala 1 hingga 5 yang paling mencerminkan kondisi aktual.</p>
                            <ul class="petunjuk-skala">
                                <li class="petunjuk-skala-item">
                                    <span class="petunjuk-skala-num">1</span>
                                    <span>Sangat tidak setuju / Tidak pernah dilakukan.</span>
                                </li>
                                <li class="petunjuk-skala-item">
                                    <span class="petunjuk-skala-num">2</span>
                                    <span>Tidak setuju / Jarang dilakukan.</span>
                                </li>
                                <li class="petunjuk-skala-item">
                                    <span class="petunjuk-skala-num">3</span>
                                    <span>Netral / Kadang dilakukan.</span>
                                </li>
                                <li class="petunjuk-skala-item">
                                    <span class="petunjuk-skala-num">4</span>
                                    <span>Setuju / Sering dilakukan.</span>
                                </li>
                                <li class="petunjuk-skala-item">
                                    <span class="petunjuk-skala-num">5</span>
                                    <span>Sangat setuju / Selalu konsisten dilakukan.</span>
                                </li>
                            </ul>
                        </div>
                        @else
                        <div class="tips-card">
                            <h4>TIPS PENGISIAN</h4>
                            <p>Evaluasi sejauh mana organisasi Anda mampu mengeksekusi strategi yang telah direncanakan. Pilih <b>Tidak</b>, <b>Sedang Proses</b>, atau <b>Ya</b> yang di mana merupakan kondisi aktual.</p>
                        </div>
                        @endif
                    </div>

                    <div class="questions-box">
                        <div class="q-section-title">
                            <h2 id="sectionTitle">Nilai Organisasi</h2>
                            <p id="sectionDesc">Evaluasi sejauh mana usaha anda berjalan.</p>
                        </div>

                        <div id="questionsContainer">
                            <!-- Pertanyaan akan di-render di sini oleh JavaScript -->
                        </div>

                        <div class="nav-buttons">
                            <button class="btn-outline" id="btnBack" style="visibility: hidden;">Kembali</button>
                            <button class="btn-primary" id="btnNext">Lanjut ke Kinerja Ekonomi →</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Popup Selesai -->
    <div class="modal-overlay" id="successModal">
        <div class="modal-box">
            <div class="modal-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <h3 class="modal-title">Asesmen Selesai! Terima Kasih, {{ explode(' ', auth()->user()->name ?? 'Ahmad')[0] }}</h3>
            @if(auth()->user()->role === 'employee')
            <p class="modal-desc">Jawaban Anda telah disimpan dan digabung dengan asesmen pemilik. Anda dapat mengunduh ringkasan jawaban dalam bentuk PDF. Tekan <b>Selesai</b> untuk menutup jendela ini.</p>
            @else
            <p class="modal-desc">Data Anda telah berhasil disimpan dan sedang dianalisis oleh sistem kami. Anda sekarang dapat melihat gambaran lengkap kesehatan organisasi Anda.</p>
            @endif
            
            <div class="status-box">
                <div class="status-item">
                    <h6>STATUS</h6>
                    <p><span class="check-icon">✓</span> Selesai</p>
                </div>
                <div class="status-item">
                    <h6>TANGGAL</h6>
                    <p>{{ date('d F Y') }}</p>
                </div>
                <div class="status-item">
                    <h6>WAKTU PENGERJAAN</h6>
                    <p id="realDuration">Menghitung...</p>
                </div>
            </div>

            <div class="modal-actions">
                @if(auth()->user()->role === 'employee')
                <button type="button" class="btn-modal-primary" id="btnModalSelesaiKaryawan">Selesai</button>
                <a href="{{ route('dashboard.asesmen-ringkasan-pdf') }}" class="btn-modal-outline" target="_blank" rel="noopener noreferrer">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    Unduh PDF
                </a>
                @else
                <button type="button" class="btn-modal-primary" onclick="window.location='{{ route('dashboard') }}'">Kembali ke Beranda</button>
                <a href="{{ route('dashboard.asesmen-ringkasan-pdf') }}" class="btn-modal-outline" target="_blank" rel="noopener noreferrer">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    Unduh Ringkasan (PDF)
                </a>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            let startTime = new Date(); // Catat waktu mulai default
            const sections = @json($sections);
            const totalQuestions = sections.reduce((acc, s) => acc + s.questions.length, 0);

            let currentStep = 0;
            const answers = {}; // Store answers { "OH1": "Ya", ... }

            // Elements
            const questionsContainer = document.getElementById('questionsContainer');
            const sectionTitle = document.getElementById('sectionTitle');
            const sectionDesc = document.getElementById('sectionDesc');
            const btnNext = document.getElementById('btnNext');
            const btnBack = document.getElementById('btnBack');
            const progressCount = document.getElementById('progressCount');
            const progressBar = document.getElementById('progressBar');
            const progressInfo = document.getElementById('progressInfo');
            const modal = document.getElementById('successModal');

            // Welcome Elements
            const welcomeContainer = document.getElementById('welcomeContainer');
            const assessmentContent = document.getElementById('assessmentContent');
            const btnStartAssessment = document.getElementById('btnStartAssessment');

            if (btnStartAssessment) {
                btnStartAssessment.addEventListener('click', function() {
                    if (welcomeContainer) welcomeContainer.style.display = 'none';
                    if (assessmentContent) assessmentContent.style.display = 'block';
                    startTime = new Date(); // Reset waktu mulai saat benar-benar mulai mengisi
                });
            }

            function updateProgress() {
                const answeredCount = Object.keys(answers).length;
                progressCount.textContent = `${answeredCount} / ${totalQuestions} Pertanyaan Selesai`;
                progressBar.style.width = `${(answeredCount / totalQuestions) * 100}%`;
            }

            function renderStep() {
                const section = sections[currentStep];
                
                // Update Texts
                sectionTitle.textContent = section.title;
                sectionDesc.textContent = section.desc;
                btnNext.textContent = (currentStep === sections.length - 1) ? "Selesai" : "Lanjut →";
                
                updateProgress();
                progressInfo.textContent = `Anda sedang mengisi bagian "${section.title}"`;

                // Update Back Button Visibility
                btnBack.style.visibility = currentStep === 0 ? 'hidden' : 'visible';

                // Render Questions
                questionsContainer.innerHTML = '';
                section.questions.forEach((q, index) => {
                    const selectedVal = answers[q.id] || null;

                    let optionsHTML = '';
                    const qIdNum = parseInt(q.id.replace('OH', ''));

                    if (qIdNum <= 6) {
                        // 3 Opsi (OH1 - OH6)
                        if (q.id === 'OH6') {
                            optionsHTML = `
                                <button class="option-btn ${selectedVal === 'Modal Sendiri' ? 'selected' : ''}" data-q="${q.id}" data-val="Modal Sendiri">Modal Sendiri</button>
                                <button class="option-btn ${selectedVal === 'Keluarga' ? 'selected' : ''}" data-q="${q.id}" data-val="Keluarga">Keluarga</button>
                                <button class="option-btn ${selectedVal === 'Bank / Kredit' ? 'selected' : ''}" data-q="${q.id}" data-val="Bank / Kredit">Bank / Kredit</button>
                            `;
                        } else {
                            optionsHTML = `
                                <button class="option-btn ${selectedVal === 'Tidak' ? 'selected' : ''}" data-q="${q.id}" data-val="Tidak">Tidak</button>
                                <button class="option-btn ${selectedVal === 'Sedang Proses' ? 'selected' : ''}" data-q="${q.id}" data-val="Sedang Proses">Sedang Proses</button>
                                <button class="option-btn ${selectedVal === 'Ya' ? 'selected' : ''}" data-q="${q.id}" data-val="Ya">Ya</button>
                            `;
                        }
                    } else {
                        // 5 Opsi (OH7 - OH35)
                        optionsHTML = `
                            <button class="option-btn ${selectedVal === 'Sangat Tidak Setuju' ? 'selected' : ''}" data-q="${q.id}" data-val="Sangat Tidak Setuju">Sangat Tidak Setuju</button>
                            <button class="option-btn ${selectedVal === 'Tidak Setuju' ? 'selected' : ''}" data-q="${q.id}" data-val="Tidak Setuju">Tidak Setuju</button>
                            <button class="option-btn ${selectedVal === 'Ragu-ragu' ? 'selected' : ''}" data-q="${q.id}" data-val="Ragu-ragu">Ragu-ragu</button>
                            <button class="option-btn ${selectedVal === 'Setuju' ? 'selected' : ''}" data-q="${q.id}" data-val="Setuju">Setuju</button>
                            <button class="option-btn ${selectedVal === 'Sangat Setuju' ? 'selected' : ''}" data-q="${q.id}" data-val="Sangat Setuju">Sangat Setuju</button>
                        `;
                    }

                    const qHTML = `
                        <div class="question-item">
                            <div class="question-text">${index + 1}. ${q.text}</div>
                            <div class="options-group">
                                ${optionsHTML}
                            </div>
                        </div>
                    `;
                    questionsContainer.insertAdjacentHTML('beforeend', qHTML);
                });

                // Attach Event Listeners to Options
                document.querySelectorAll('.option-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const qId = this.getAttribute('data-q');
                        const val = this.getAttribute('data-val');
                        
                        // Save answer
                        answers[qId] = val;

                        // Remove selected class from siblings
                        const parent = this.parentElement;
                        parent.querySelectorAll('.option-btn').forEach(b => b.classList.remove('selected'));
                        
                        // Add selected class to clicked
                        this.classList.add('selected');

                        // Update Progress Real-time
                        updateProgress();
                    });
                });
            }

            // Next Button Logic
            btnNext.addEventListener('click', () => {
                // Cek apakah semua pertanyaan di step saat ini sudah diisi
                const currentQuestions = sections[currentStep].questions;
                for (let i = 0; i < currentQuestions.length; i++) {
                    if (!answers[currentQuestions[i].id]) {
                        alert('Mohon jawab semua pertanyaan di bagian ini terlebih dahulu.');
                        return;
                    }
                }

                if (currentStep < sections.length - 1) {
                    currentStep++;
                    renderStep();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    // Selesai - Kirim Data ke Server
                    fetch('{{ route('dashboard.simpan-asesmen') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ answers: answers })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Hitung waktu pengerjaan
                            const endTime = new Date();
                            const diff = Math.abs(endTime - startTime);
                            const minutes = Math.floor(diff / 1000 / 60);
                            const seconds = Math.floor((diff / 1000) % 60);
                            
                            let durationText = "";
                            if (minutes > 0) {
                                durationText = `${minutes} Menit ${seconds} Detik`;
                            } else {
                                durationText = `${seconds} Detik`;
                            }
                            
                            document.getElementById('realDuration').textContent = durationText;

                            // Update status pemilik secara dinamis
                            const chipOwner = document.getElementById('chipOwner');
                            const textOwner = document.getElementById('textOwner');
                            if (chipOwner && textOwner) {
                                if (data.owner_finished) {
                                    chipOwner.classList.add('completed');
                                    textOwner.textContent = 'Pemilik: Sudah Mengisi';
                                } else {
                                    chipOwner.classList.remove('completed');
                                    textOwner.textContent = 'Pemilik: Belum Mengisi';
                                }
                            }

                            // Update status karyawan secara dinamis
                            const chipEmployees = document.getElementById('chipEmployees');
                            const textEmployees = document.getElementById('textEmployees');
                            if (chipEmployees && textEmployees) {
                                textEmployees.textContent = `Karyawan: ${data.employees_finished_count}/${data.total_employees} Selesai`;
                                if (data.employees_finished_count >= data.total_employees) {
                                    chipEmployees.classList.add('completed');
                                } else {
                                    chipEmployees.classList.remove('completed');
                                }
                            }

                            modal.classList.add('active');
                        } else {
                            alert('Terjadi kesalahan saat menyimpan data.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal menyambung ke server.');
                    });
                }
            });

            // Back Button Logic
            btnBack.addEventListener('click', () => {
                if (currentStep > 0) {
                    currentStep--;
                    renderStep();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });

            // Selesai Button in Modal (untuk peran karyawan)
            const btnModalSelesaiKaryawan = document.getElementById('btnModalSelesaiKaryawan');
            if (btnModalSelesaiKaryawan) {
                btnModalSelesaiKaryawan.addEventListener('click', function() {
                    modal.classList.remove('active');
                    showAsesmenSelesaiState();
                });
            }

            function showAsesmenSelesaiState() {
                // Sembunyikan elemen progres jika tidak ada pertanyaan
                const progressCard = document.querySelector('.progress-card');
                if (progressCard) progressCard.style.display = 'none';
                
                const questionsBox = document.querySelector('.questions-box');
                if (questionsBox) {
                    questionsBox.innerHTML = `
                        <div style="text-align: center; padding: 40px 20px;">
                            <div style="width: 80px; height: 80px; background: #eef2ff; color: #2563eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            </div>
                            <h2 style="color: #1e293b; font-weight: 800; margin-bottom: 10px;">Asesmen Telah Selesai</h2>
                            <p style="color: #64748b; font-size: 14px; line-height: 1.6;">Anda telah menyelesaikan seluruh bagian pertanyaan untuk karyawan. Jawaban Anda telah tersimpan dengan aman.</p>
                        </div>
                    `;
                }
            }

            // Initial Render
            if (sections.length > 0) {
                renderStep();
            } else {
                showAsesmenSelesaiState();
            }
        });
    </script>
    <script src="{{ asset('js/responsive-sidebar.js') }}"></script>
</body>
</html>
