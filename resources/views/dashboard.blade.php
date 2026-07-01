<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SiSehat - Analitik UMKM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="{{ asset('css/responsive-layout.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
        .avatar { width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; overflow: hidden; }
        .avatar img { width: 100%; height: 100%; object-fit: cover; }
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

        /* ================= MAIN CONTENT ================= */
        .content-area { flex-grow: 1; padding: 30px 40px; overflow-y: auto; scroll-behavior: smooth; }
        .welcome-msg h1 { font-size: 26px; color: #1a1d23; margin-bottom: 5px; }
        .welcome-msg p { color: #6c757d; font-size: 14px; margin-bottom: 30px; }

        .glass-card { 
            background: white; padding: 25px; border-radius: 12px; 
            border: 1px solid rgba(0,0,0,0.05); 
            /* Modifikasi Box Shadow: Y=8px, Opacity=20% */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.20); 
            display: flex; flex-direction: column;
        }

        .main-wrapper { display: grid; grid-template-columns: 2fr 1fr; gap: 25px; align-items: start; }
        .left-col, .right-col { display: flex; flex-direction: column; gap: 25px; }

        .top-left-grid { display: grid; grid-template-columns: 1.3fr 1fr; gap: 25px; } 
        
        .score-box { 
            justify-content: center; 
            position: relative; 
            padding: 30px 25px; 
            background: #EEF4FF; 
            border: none; 
            min-height: 60px; 
        } 
 
        .score-box p.label { font-size: 16px; font-weight: 700; margin-bottom: 10px; color: #000000; }
        .text-baik { color: #0f5132; }
        .text-cukup { color: #664d03; }
        .text-kurang { color: #93000A; }
        .score-value { font-size: 54px; color: #1a1d23; font-weight: 800; line-height: 1; margin-bottom: 25px;}
        .status-badge { font-size: 10px; padding: 4px 12px; border-radius: 20px; font-weight: 800; position: absolute; top: 30px; right: 25px; text-transform: uppercase; }
        .badge-baik { background: #d2f4ea; color: #0f5132; }
        .badge-cukup { background: #fff3cd; color: #664d03; }
        .badge-kurang { background: #FFDAD6; color: #93000A; }
        .insight-container { margin-top: auto; background: white; border: 1px solid rgba(220, 53, 69, 0.1); border-left: 4px solid #dc3545; padding: 12px 15px; border-radius: 8px; }
        .insight-text { font-size: 12px; color: #495057; line-height: 1.5; }
        .insight-text b { color: #dc3545; }
        .mini-stats { display: flex; flex-direction: column; gap: 25px; }
        .stat-item { 
            padding: 20px 25px; 
            justify-content: center; 
            position: relative; 
            overflow: hidden;
            border-left: none; /* Hapus border standar */ 
            min-height: 60px;
        }
        /* Tambahkan bar dekoratif yang tebal dan lurus */
        .stat-item::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 8px; /* Lebih tebal */
            background-color: #004AC6;
        }
        .stat-item h3 { font-size: 28px; font-weight: 800; color: #1a1d23; margin-top: 5px; }
        .stat-icon { 
            background: #EEF4FF; 
            color: #004AC6; 
            width: 48px; 
            height: 48px; 
            border-radius: 50%; /* Lingkaran */
            border: 1px solid #d1e3ff; /* Garis tepi untuk mempertegas bentuk */
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 20px; 
            flex-shrink: 0; 
        }

        .radar-box { margin-bottom: 25px; }
        .radar-box .label { font-size: 22px; font-weight: 700; color: #1a1d23; margin-bottom: 25px; text-align: center; }
        .radar-wrap { width: 100%; height: 350px; display: flex; align-items: center; justify-content: center; }
        .radar-legend {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid #f1f5f9;
        }
        .radar-legend-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            transition: all 0.25s ease;
            user-select: none;
        }
        .radar-legend-item:hover {
            background: #eef2ff;
            border-color: #c7d2fe;
            color: #4338ca;
        }
        .radar-legend-item.active {
            background: #6554ff;
            border-color: #6554ff;
            color: #ffffff;
        }
        .radar-legend-item.active .radar-legend-dot {
            background: #ffffff;
        }
        .radar-legend-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #94a3b8;
            transition: all 0.25s ease;
        }
        .radar-legend-item:hover .radar-legend-dot {
            background: #6554ff;
        }
        .radar-legend-item .radar-legend-score {
            font-size: 11px;
            font-weight: 700;
            opacity: 0.7;
        }
        .radar-legend-item.active .radar-legend-score {
            opacity: 1;
        }

        .rank-box { background: none; border: none; box-shadow: none; padding: 0; margin-top: -20px; }
        .rank-box > p.label { font-size: 22px; font-weight: 700; color: #1a1d23; margin-bottom: 20px; }
        .rank-container { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
        
        .rank-list-card { 
            background: white; 
            padding: 15px; 
            border-radius: 12px; 
            border: 1px solid rgba(0,0,0,0.05); 
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.20); 
        }
        .rank-list-card h4 { font-size: 12px; font-weight: 800; margin-top: 15px; margin-bottom: 15px; color: #6c757d; display: flex; align-items: center; gap: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
        .rank-list-card h4.text-green { color: #2e7d32; }
        .rank-list-card h4.text-red { color: #c62828; }

        .rank-card { 
            background: #ffffff; 
            padding: 15px 20px; 
            border-radius: 10px; 
            margin-bottom: 15px; 
            display: flex; 
            align-items: center; 
            gap: 15px; 
            border: 1px solid #f1f3f5;
            position: relative;
            transition: transform 0.2s;
        }
        .rank-card:hover { transform: translateX(5px); }
        .rank-card::before {
            content: "";
            position: absolute;
            left: 0;
            top: 10px;
            bottom: 10px;
            width: 4px;
            border-radius: 0 4px 4px 0;
        }
        .rank-card.top-1::before { background: #fcc419; }
        .rank-card.top-2::before { background: #adb5bd; }
        .rank-card.top-3::before { background: #f76707; }
        .rank-card.danger::before { background: #fa5252; }

        .rank-num { 
            width: 32px; 
            height: 32px; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-weight: 800; 
            font-size: 13px; 
        }
        .rank-card.top-1 .rank-num { background: #fff9db; color: #f59f00; }
        .rank-card.top-2 .rank-num { background: #f1f3f5; color: #495057; }
        .rank-card.top-3 .rank-num { background: #fff4e6; color: #d9480f; }
        .rank-card.danger .rank-num { background: #fff5f5; color: #fa5252; }

        .rank-info { flex-grow: 1; }
        .rank-info h5 { font-size: 14px; font-weight: 700; color: #1a1d23; margin-bottom: 2px; }
        .rank-info p { font-size: 11px; color: #868e96; font-weight: 500; }
        .rank-badge { color: #dee2e6; font-size: 14px; }
        .rank-card.top-1 .rank-badge { color: #fcc419; }
        .rank-card.top-2 .rank-badge { color: #adb5bd; }
        .rank-card.top-3 .rank-badge { color: #f76707; }

        .chart-pie-box .label { font-size: 22px; font-weight: 700; color: #1a1d23; margin-bottom: 20px; text-align: center; }
        .chart-pie-box .chart-wrap { position: relative; width: 100%; height: 208px; display: flex; align-items: center; justify-content: center; }

        .factor-box .label { 
            font-size: 20px; 
            font-weight: 700; 
            color: #1a1d23; 
            margin-bottom: 20px; 
            border-bottom: 1px solid #e9ecef; 
            padding-bottom: 8px;
        }
        .custom-bar-list { display: flex; flex-direction: column; gap: 25px; width: 100%; }
        .custom-bar-item { display: flex; flex-direction: column; gap: 6px; }
        .cbi-header { display: flex; justify-content: space-between; align-items: center; }
        .cbi-label { font-size: 12px; font-weight: 600; color: #495057; } 
        .cbi-value { font-size: 12px; font-weight: 800; }
        .cbi-track { width: 100%; height: 7px; background: #e9ecef; border-radius: 4px; overflow: hidden; }
        .cbi-fill { height: 100%; border-radius: 4px; }
        
        .fill-green { background-color: #198754; } .text-green { color: #198754; }
        .fill-yellow { background-color: #ffc107; } .text-yellow { color: #ffc107; }
        .fill-red { background-color: #dc3545; } .text-red { color: #dc3545; }

        .age-box .label { font-size: 20px; font-weight: 700; color: #1a1d23; margin-top:8px; margin-bottom: 30px; text-align: center; }
        .age-box .chart-wrap { position: relative; width: 100%; height: 288px; display: flex; align-items: center; justify-content: center; }
        .age-legend { display: flex; justify-content: center; gap: 15px; margin-top: 15px; font-size: 11px; color: #6c757d; font-weight: 600; }
        .age-legend div { display: flex; align-items: center; gap: 6px; }
        .age-legend span.box { width: 10px; height: 10px; border-radius: 2px; }

    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo-box">
            <img src="{{ asset('images/sisehat_logo.svg') }}" alt="SiSehat" class="logo-main" onerror="this.style.display='none'; this.insertAdjacentHTML('afterend', '<h2 style=\'color:#004AC6; font-weight:900;\'>SISEHAT</h2>');">
        </div>
        
        @if(auth()->user()->role !== 'employee')
        <button class="btn-primary-custom" onclick="window.location='{{ route('dashboard.tambah-umkm') }}'">+ Tambahkan UMKM Anda</button>
        @endif
        <div class="sidebar-divider"></div>
        
        <ul class="nav-list">
            <li class="nav-item active">
                <img src="{{ asset('images/Beranda_logo.svg') }}" class="nav-icon" onerror="this.src='https://cdn-icons-png.flaticon.com/512/25/25694.png'">
                <span>Beranda</span>
            </li>
            <li class="nav-item" onclick="window.location='{{ route('dashboard.asesmen') }}'">
                <img src="{{ asset('images/Asesmen_Organisasi_logo.svg') }}" class="nav-icon" onerror="this.src='https://cdn-icons-png.flaticon.com/512/1161/1161388.png'">
                <span>Asesmen Organisasi</span>
            </li>
            <li class="nav-item {{ request()->is('enam-faktor') ? 'active' : '' }}" onclick="window.location='{{ route('dashboard.faktor') }}'" style="cursor: pointer;">
                <img src="{{ asset('images/6_Faktor_Penilaian_logo.svg') }}" class="nav-icon" onerror="this.src='https://cdn-icons-png.flaticon.com/512/138/138439.png'">
                <span>6 Faktor Penilaian</span>
            </li>
            <li class="nav-item" onclick="window.location='{{ route('dashboard.rekomendasi') }}'">
                <img src="{{ asset('images/Rekomendasi_logo.svg') }}" class="nav-icon" onerror="this.src='https://cdn-icons-png.flaticon.com/512/151/151300.png'">
                <span>Rekomendasi</span>
            </li>
            @if(auth()->user()->role !== 'employee')
            <li class="nav-item" onclick="window.location='{{ route('dashboard.tambah-umkm') }}'">
                <img src="{{ asset('images/Tambah_Data_UMKM_logo.svg') }}" class="nav-icon" onerror="this.src='https://cdn-icons-png.flaticon.com/512/2990/2990315.png'">
                <span>Tambah Data UMKM</span>
            </li>
            @endif
            <li class="nav-item" onclick="window.location='{{ route('dashboard.tambah-karyawan') }}'">
                <img src="{{ asset('images/Tambah_Data_Karyawan_logo.svg') }}" class="nav-icon" style="width: 24px; height: 24px;" onerror="this.src='https://cdn-icons-png.flaticon.com/512/3135/3135715.png'">
                <span>Tambah Data Karyawan</span>
            </li>
            @if(auth()->user()->role !== 'employee')
            <li class="nav-item" onclick="window.location='{{ route('dashboard.manajemen-umkm') }}'">
                <img src="{{ asset('images/Manajemen_UMKM_logo.svg') }}" class="nav-icon" onerror="this.src='https://cdn-icons-png.flaticon.com/512/104/104646.png'">
                <span>Manajemen UMKM</span>
            </li>
            @endif
        </ul>

        <div class="profile-card">
            <div class="avatar">
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
        <div class="welcome-msg">
            <h1>Selamat datang, {{ explode(' ', auth()->user()->name ?? 'Riski')[0] }}</h1>
            <p>Berikut adalah ringkasan kesehatan organisasi Anda.</p>
        </div>

        <div class="main-wrapper">
            
            <div class="left-col">
                <div class="top-left-grid">
                    <div class="glass-card score-box">
                        @php
                            $status = $data['status_kesehatan'] ?? 'KURANG';
                            $badgeClass = 'badge-kurang';
                            $statusLabel = 'PERLU PENINGKATAN';
                            
                            if ($status === 'BAIK') {
                                $badgeClass = 'badge-baik';
                                $statusLabel = 'KONDISI BAIK';
                            } elseif ($status === 'CUKUP') {
                                $badgeClass = 'badge-cukup';
                                $statusLabel = 'KONDISI CUKUP';
                            }
                        @endphp
                        <p class="label">Skor Kesehatan Rata-rata</p>
                        <span class="status-badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                        <div class="score-value">{{ $data['skor_kesehatan'] ?? 58 }}%</div>
                        <div class="insight-container">
                            <p class="insight-text">{!! $data['insight_kritis'] ?? '<b>⚠️ Insight Kritis:</b> Fokuslah pada perbaikan faktor-faktor dengan skor terendah.' !!}</p>
                        </div>
                    </div>

                    <div class="mini-stats">
                        <div class="glass-card stat-item">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <p style="font-size: 16px; color: #1a1d23; font-weight: 500; margin-bottom: 5px;">Faktor Dianalisis</p>
                                    <h3>{{ $data['faktor_dianalisis'] ?? 6 }}</h3>
                                </div>
                                <div class="stat-icon" style="background: #EEF4FF; border-radius: 50%; border: 1px solid #d1e3ff; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                                    <svg width="24" height="24" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="40" height="40" rx="8" fill="#004AC6"/>
                                        <rect x="8" y="12" width="10" height="3" rx="1.5" fill="white"/>
                                        <rect x="8" y="19" width="10" height="3" rx="1.5" fill="white"/>
                                        <rect x="8" y="26" width="10" height="3" rx="1.5" fill="white"/>
                                        <path d="M22 20L26 24L33 15" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="glass-card stat-item">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <p style="font-size: 16px; color: #1a1d23; font-weight: 500; margin-bottom: 5px;">Total Responden</p>
                                    <h3>{{ $data['total_responden'] ?? 428 }}</h3>
                                </div>
                                <div class="stat-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-card radar-box">
                    <p class="label">Gambaran Umum 6 Faktor Kesehatan Organisasi</p>
                    <div class="radar-wrap">
                        <canvas id="radarChart"></canvas>
                    </div>
                    <div id="radarLegend" class="radar-legend"></div>
                </div>

                <div class="rank-box">
                    <p class="label">Peringkat Kinerja UMKM</p>
                    <div class="rank-container">
                        <div class="rank-list-card">
                            <h4 class="text-green">💡 KINERJA TERATAS</h4>
                            @foreach($data['top_umkm'] as $index => $umkm)
                            <div class="rank-card top-{{ $index + 1 }}">
                                <div class="rank-num">{{ $index + 1 }}</div>
                                <div class="rank-info"><h5>{{ $umkm->nama_umkm }}</h5><p>Skor Kesehatan: {{ round($umkm->total_score) }}%</p></div>
                                <div class="rank-badge">🏅</div>
                            </div>
                            @endforeach
                        </div>
                        <div class="rank-list-card">
                            <h4 class="text-red">⚠️ PERHATIAN KHUSUS</h4>
                            @foreach($data['bottom_umkm'] as $umkm)
                            <div class="rank-card danger">
                                <div class="rank-num">●</div>
                                <div class="rank-info"><h5>{{ $umkm->nama_umkm }}</h5><p>Skor: {{ round($umkm->total_score) }}%</p></div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="right-col">
                <div class="glass-card chart-pie-box">
                    <p class="label">Tipe Bisnis</p>
                    <div class="chart-wrap" style="gap: 15px; justify-content: space-between; padding: 0 10px;">
                        <div style="position: relative; width: 160px; height: 160px; flex-shrink: 0;">
                            <canvas id="pieChart"></canvas>
                        </div>
                        <div id="pieLegend" style="display: flex; flex-direction: column; gap: 12px; flex-grow: 1; justify-content: center; padding-left: 10px;"></div>
                    </div>
                </div>

                <div class="glass-card factor-box" style="min-height: 440px;">
                    <p class="label">Distribusi Faktor Kesehatan</p>
                    <div class="custom-bar-list">
                        @php
                            $factorLabels = [
                                'Nilai Organisasi',
                                'Keterlibatan Pemimpin',
                                'Sumber Daya Institusi',
                                'Stabilitas Operasional',
                                'Kualitas Tempat Kerja',
                                'Kinerja Ekonomi'
                            ];
                            $getFactorColor = function($score) {
                                if ($score >= 75) return 'green';
                                if ($score >= 50) return 'yellow';
                                return 'red';
                            };
                        @endphp
                        @foreach($factorLabels as $index => $label)
                            @php
                                $score = $data['data_factors'][$index] ?? 0;
                                $color = $getFactorColor($score);
                            @endphp
                            <div class="custom-bar-item">
                                <div class="cbi-header">
                                    <span class="cbi-label">{{ $label }}</span>
                                    <span class="cbi-value text-{{ $color }}">{{ $score }}%</span>
                                </div>
                                <div class="cbi-track">
                                    <div class="cbi-fill fill-{{ $color }}" style="width: {{ $score }}%;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="glass-card age-box" style="margin-top: 10px; min-height: 448px; padding: 35px 30px;">
                    <p class="label" style="margin-top: 0px; margin-bottom: 19px;">Usia Perusahaan</p>
                    <div class="chart-wrap" style="height: 300px;">
                        <canvas id="ageChart"></canvas>
                    </div>
                    <div class="age-legend" style="margin-top: 10px;">
                        <div class="age-legend-item" data-index="0" style="cursor: pointer; display: flex; align-items: center; gap: 6px; padding: 4px 8px; border-radius: 4px; transition: all 0.2s;"><span class="box" style="width: 10px; height: 10px; border-radius: 2px; background-color: #8B5CF6; transition: all 0.2s;"></span><span class="age-label" style="transition: all 0.2s;">< 1 Tahun</span></div>
                        <div class="age-legend-item" data-index="1" style="cursor: pointer; display: flex; align-items: center; gap: 6px; padding: 4px 8px; border-radius: 4px; transition: all 0.2s;"><span class="box" style="width: 10px; height: 10px; border-radius: 2px; background-color: #F43F5E; transition: all 0.2s;"></span><span class="age-label" style="transition: all 0.2s;">1-3 Tahun</span></div>
                        <div class="age-legend-item" data-index="2" style="cursor: pointer; display: flex; align-items: center; gap: 6px; padding: 4px 8px; border-radius: 4px; transition: all 0.2s;"><span class="box" style="width: 10px; height: 10px; border-radius: 2px; background-color: #06B6D4; transition: all 0.2s;"></span><span class="age-label" style="transition: all 0.2s;">> 3 Tahun</span></div>
                    </div>
                </div>

            </div>
        </div>
        <div style="height: 40px;"></div>
    </div>

    <script>
        const dataTipeBisnis = {!! json_encode($data['data_tipe_bisnis'] ?? [28.63, 56.56, 16.82]) !!};
        const dataRadar = {!! json_encode($data['data_radar'] ?? [70, 50, 40, 55, 60, 35]) !!};
        
        const originalPieColors = ['#06B6D4', '#8B5CF6', '#F43F5E'];
        const grayPieColors = ['#BDC7D0', '#D2DCE5', '#E6EEF4'];
        const categories = ['Makanan dan Minuman', 'Fashion', 'Grosir dan Eceran'];

        const pieChart = new Chart(document.getElementById('pieChart'), {
            type: 'pie',
            data: {
                labels: categories,
                datasets: [{ 
                    data: dataTipeBisnis, 
                    backgroundColor: [...grayPieColors], 
                    hoverBackgroundColor: [...originalPieColors],
                    borderWidth: 0 
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { 
                    legend: { 
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                                let value = context.raw;
                                return ((value / total) * 100).toFixed(1) + '%';
                            },
                            labelColor: function(context) {
                                const index = context.dataIndex;
                                return {
                                    borderColor: originalPieColors[index],
                                    backgroundColor: originalPieColors[index],
                                    borderWidth: 0,
                                    borderRadius: 2
                                };
                            }
                        }
                    }
                } 
            }
        });

        // Custom HTML Legend click and alignment logic
        let activePieIndex = -1;

        function updatePieChartColors(selectedIdx) {
            const newBackgrounds = [...grayPieColors];
            if (selectedIdx !== -1) {
                newBackgrounds[selectedIdx] = originalPieColors[selectedIdx];
            }
            pieChart.data.datasets[0].backgroundColor = newBackgrounds;
            pieChart.update();

            // Update custom legend CSS styling
            const items = document.querySelectorAll('.custom-legend-item');
            items.forEach((item, idx) => {
                const dot = item.querySelector('.legend-color-dot');
                const textLabel = item.querySelector('.legend-text-label');
                const textVal = item.querySelector('.legend-text-val');
                
                if (selectedIdx === -1) {
                    dot.style.backgroundColor = grayPieColors[idx];
                    dot.style.transform = 'scale(1)';
                    textLabel.style.color = '#868e96';
                    textLabel.style.fontWeight = '500';
                    textVal.style.color = '#495057';
                } else if (idx === selectedIdx) {
                    dot.style.backgroundColor = originalPieColors[idx];
                    dot.style.transform = 'scale(1.2)';
                    textLabel.style.color = '#1a1d23';
                    textLabel.style.fontWeight = '700';
                    textVal.style.color = originalPieColors[idx];
                } else {
                    dot.style.backgroundColor = '#e9ecef';
                    dot.style.transform = 'scale(0.8)';
                    textLabel.style.color = '#adb5bd';
                    textLabel.style.fontWeight = '400';
                    textVal.style.color = '#ced4da';
                }
            });
        }

        const totalTipeBisnis = dataTipeBisnis.reduce((acc, val) => acc + val, 0);

        const legendContainer = document.getElementById('pieLegend');
        if (legendContainer) {
            legendContainer.innerHTML = categories.map((label, idx) => {
                const val = dataTipeBisnis[idx] ?? 0;
                const percentage = totalTipeBisnis > 0 ? ((val / totalTipeBisnis) * 100).toFixed(1) + '%' : '0%';
                return `
                    <div class="custom-legend-item" data-index="${idx}" style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 6px 8px; border-radius: 8px; transition: all 0.25s ease; user-select: none;">
                        <span class="legend-color-dot" style="width: 10px; height: 10px; border-radius: 50%; background-color: ${grayPieColors[idx]}; display: inline-block; flex-shrink: 0; transition: all 0.25s ease;"></span>
                        <div style="display: flex; flex-direction: column; text-align: left; line-height: 1.3;">
                            <span class="legend-text-label" style="font-size: 11.5px; font-weight: 500; color: #868e96; transition: all 0.25s ease;">${label}</span>
                            <span class="legend-text-val" style="font-size: 13px; font-weight: 700; color: #495057; transition: all 0.25s ease;">${percentage}</span>
                        </div>
                    </div>
                `;
            }).join('');

            legendContainer.querySelectorAll('.custom-legend-item').forEach(item => {
                item.addEventListener('mouseenter', function() {
                    if (activePieIndex === -1) {
                        const idx = parseInt(this.getAttribute('data-index'));
                        const hoverBackgrounds = [...grayPieColors];
                        hoverBackgrounds[idx] = originalPieColors[idx];
                        pieChart.data.datasets[0].backgroundColor = hoverBackgrounds;
                        pieChart.update();
                        
                        const dot = this.querySelector('.legend-color-dot');
                        dot.style.backgroundColor = originalPieColors[idx];
                        this.querySelector('.legend-text-label').style.color = '#1a1d23';
                    }
                });

                item.addEventListener('mouseleave', function() {
                    if (activePieIndex === -1) {
                        const idx = parseInt(this.getAttribute('data-index'));
                        pieChart.data.datasets[0].backgroundColor = [...grayPieColors];
                        pieChart.update();
                        
                        const dot = this.querySelector('.legend-color-dot');
                        dot.style.backgroundColor = grayPieColors[idx];
                        this.querySelector('.legend-text-label').style.color = '#868e96';
                    }
                });

                item.addEventListener('click', function() {
                    const idx = parseInt(this.getAttribute('data-index'));
                    if (activePieIndex === idx) {
                        activePieIndex = -1;
                    } else {
                        activePieIndex = idx;
                    }
                    updatePieChartColors(activePieIndex);
                });
            });
        }

        // 2. Radar Chart - Interactive with Indonesian labels
        const radarLabelsID = [
            'Nilai\nOrganisasi',
            'Keterlibatan\nPemimpin',
            'Sumber Daya\nInstitusi',
            'Stabilitas\nOperasional',
            'Kualitas\nTempat Kerja',
            'Kinerja\nEkonomi'
        ];
        const radarLabelsIDFull = [
            'Nilai Organisasi',
            'Keterlibatan Pemimpin',
            'Sumber Daya Institusi',
            'Stabilitas Operasional',
            'Kualitas Tempat Kerja',
            'Kinerja Ekonomi'
        ];

        // Radar chart interactive legend
        let activeRadarIndex = -1;

        const radarChart = new Chart(document.getElementById('radarChart'), {
            type: 'radar',
            data: {
                labels: radarLabelsID,
                datasets: [{ 
                    label: 'Skor', data: dataRadar, 
                    backgroundColor: 'rgba(101, 84, 255, 0.15)', 
                    borderColor: '#6554ff', 
                    pointBackgroundColor: '#6554ff', 
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    borderWidth: 2 
                }]
            },
            options: { 
                responsive: true, maintainAspectRatio: false, 
                scales: { 
                    r: { 
                        grid: { color: '#e9ecef' }, angleLines: { color: '#e9ecef' }, 
                        suggestedMin: 0, suggestedMax: 100, 
                        ticks: { display: true, font: {size: 8}, color: '#adb5bd', backdropColor: 'transparent', stepSize: 20 }, 
                        pointLabels: { 
                            font: { size: 10, weight: '500' }, 
                            color: function(context) {
                                if (activeRadarIndex === -1) return '#495057';
                                return context.index === activeRadarIndex ? '#6554ff' : '#cbd5e1';
                            }
                        } 
                    } 
                }, 
                plugins: { legend: { display: false } },
                onClick: function(evt, elements) {
                    if (elements.length > 0) {
                        const idx = elements[0].index;
                        if (activeRadarIndex === idx) {
                            activeRadarIndex = -1;
                        } else {
                            activeRadarIndex = idx;
                        }
                        updateRadarHighlight(activeRadarIndex);
                    }
                }
            }
        });
        function updateRadarHighlight(selectedIdx) {
            // Reset to 1 dataset
            radarChart.data.datasets = radarChart.data.datasets.slice(0, 1);

            const baseDataset = radarChart.data.datasets[0];
            
            if (selectedIdx === -1) {
                // No selection - normal state
                baseDataset.backgroundColor = 'rgba(101, 84, 255, 0.15)';
                baseDataset.borderColor = '#6554ff';
                baseDataset.pointBackgroundColor = '#6554ff';
                baseDataset.pointRadius = 4;
                baseDataset.borderWidth = 2;
            } else {
                // Muted base
                baseDataset.backgroundColor = 'rgba(101, 84, 255, 0.06)';
                baseDataset.borderColor = 'rgba(101, 84, 255, 0.25)';
                baseDataset.pointRadius = dataRadar.map((_, i) => i === selectedIdx ? 0 : 3);
                baseDataset.pointBackgroundColor = dataRadar.map((_, i) => i === selectedIdx ? 'transparent' : '#cbd5e1');
                baseDataset.borderWidth = 1.5;

                // Add highlight dataset for the selected point
                const highlightData = new Array(dataRadar.length).fill(null);
                highlightData[selectedIdx] = dataRadar[selectedIdx];

                radarChart.data.datasets.push({
                    label: radarLabelsIDFull[selectedIdx],
                    data: highlightData,
                    backgroundColor: 'transparent',
                    borderColor: 'transparent',
                    pointBackgroundColor: dataRadar.map((_, i) => i === selectedIdx ? '#6554ff' : 'transparent'),
                    pointBorderColor: dataRadar.map((_, i) => i === selectedIdx ? '#ffffff' : 'transparent'),
                    pointBorderWidth: dataRadar.map((_, i) => i === selectedIdx ? 3 : 0),
                    pointRadius: dataRadar.map((_, i) => i === selectedIdx ? 8 : 0),
                    pointHoverRadius: dataRadar.map((_, i) => i === selectedIdx ? 10 : 0),
                    borderWidth: 0
                });
            }

            radarChart.update();

            // Update legend items
            const items = document.querySelectorAll('.radar-legend-item');
            items.forEach((item, idx) => {
                if (selectedIdx === -1) {
                    item.classList.remove('active');
                } else if (idx === selectedIdx) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
        }

        // Render radar legend
        const radarLegendContainer = document.getElementById('radarLegend');
        if (radarLegendContainer) {
            radarLegendContainer.innerHTML = radarLabelsIDFull.map((label, idx) => {
                const score = dataRadar[idx] ?? 0;
                return `
                    <div class="radar-legend-item" data-index="${idx}">
                        <span class="radar-legend-dot"></span>
                        <span>${label}</span>
                        <span class="radar-legend-score">${score}</span>
                    </div>
                `;
            }).join('');

            radarLegendContainer.querySelectorAll('.radar-legend-item').forEach(item => {
                item.addEventListener('click', function() {
                    const idx = parseInt(this.getAttribute('data-index'));
                    if (activeRadarIndex === idx) {
                        activeRadarIndex = -1;
                    } else {
                        activeRadarIndex = idx;
                    }
                    updateRadarHighlight(activeRadarIndex);
                });
            });
        }

        // 3. Vertical Bar (Usia Perusahaan)
        const dataAgeRaw = @json($data['data_age']);
        const maxAge = Math.max(...dataAgeRaw) || 1;
        const dataAgeNormalized = dataAgeRaw.map(v => (v / maxAge) * 100);
        
        const originalAgeColors = ['#8B5CF6', '#F43F5E', '#06B6D4'];
        const grayAgeColors = ['#BDC7D0', '#D2DCE5', '#E6EEF4'];

        const ageChart = new Chart(document.getElementById('ageChart'), {
            type: 'bar',
            data: {
                labels: ['< 1 Tahun', '1-3 Tahun', '> 3 Tahun'], 
                datasets: [
                    {
                        data: [100, 100, 100],
                        backgroundColor: '#f8f9fa',
                        grouped: false, order: 2, barPercentage: 0.8, categoryPercentage: 1.0, hoverBackgroundColor: '#f8f9fa'
                    },
                    {
                        data: dataAgeNormalized, 
                        backgroundColor: [...grayAgeColors],
                        hoverBackgroundColor: [...originalAgeColors],
                        grouped: false, order: 1, barPercentage: 0.8, categoryPercentage: 1.0
                    }
                ]
            },
            options: { 
                responsive: true, maintainAspectRatio: false, 
                scales: { 
                    y: { min: 0, max: 100, ticks: { stepSize: 20, color: '#adb5bd', font: { size: 10 } }, grid: { color: '#f1f3f5', drawBorder: false }, border: { display: false } }, 
                    x: { grid: { display: false }, ticks: { display: false }, border: { display: true, color: '#e9ecef' } } 
                }, 
                plugins: { 
                    legend: { display: false }, 
                    tooltip: { 
                        enabled: true,
                        callbacks: {
                            label: function(context) {
                                // Since datasets[1] has the normalized values, we fetch raw count from dataAgeRaw
                                const index = context.dataIndex;
                                return dataAgeRaw[index] + ' UMKM';
                            }
                        }
                    } 
                },
                onClick: (event, elements) => {
                    if (elements && elements.length > 0) {
                        // Element index 0 represents the background bars, index 1 is our colored data bars
                        // We extract the correct index by getting the index of the clicked data point.
                        const activeEl = elements.find(el => el.datasetIndex === 1) || elements[0];
                        if (activeEl) {
                            const idx = activeEl.index;
                            if (activeAgeIndex === idx) {
                                activeAgeIndex = -1;
                            } else {
                                activeAgeIndex = idx;
                            }
                            updateAgeChartColors(activeAgeIndex);
                        }
                    }
                }
            }
        });

        let activeAgeIndex = -1;

        function updateAgeChartColors(selectedIdx) {
            const newBackgrounds = [...grayAgeColors];
            if (selectedIdx !== -1) {
                newBackgrounds[selectedIdx] = originalAgeColors[selectedIdx];
            }
            ageChart.data.datasets[1].backgroundColor = newBackgrounds;
            ageChart.update();

            // Update custom age legend CSS styling
            const items = document.querySelectorAll('.age-legend-item');
            items.forEach((item, idx) => {
                const box = item.querySelector('.box');
                const label = item.querySelector('.age-label');
                
                if (selectedIdx === -1) {
                    box.style.backgroundColor = originalAgeColors[idx];
                    box.style.transform = 'scale(1)';
                    label.style.color = '#6c757d';
                    label.style.fontWeight = '600';
                    item.style.backgroundColor = 'transparent';
                } else if (idx === selectedIdx) {
                    box.style.backgroundColor = originalAgeColors[idx];
                    box.style.transform = 'scale(1.2)';
                    label.style.color = '#1a1d23';
                    label.style.fontWeight = '800';
                    item.style.backgroundColor = 'rgba(0, 0, 0, 0.04)';
                } else {
                    box.style.backgroundColor = '#e9ecef';
                    box.style.transform = 'scale(0.8)';
                    label.style.color = '#adb5bd';
                    label.style.fontWeight = '400';
                    item.style.backgroundColor = 'transparent';
                }
            });
        }

        // Add event listeners to custom age legend items
        document.querySelectorAll('.age-legend-item').forEach(item => {
            item.addEventListener('click', function() {
                const idx = parseInt(this.getAttribute('data-index'));
                if (activeAgeIndex === idx) {
                    activeAgeIndex = -1;
                } else {
                    activeAgeIndex = idx;
                }
                updateAgeChartColors(activeAgeIndex);
            });
        });
    </script>
    <script src="{{ asset('js/responsive-sidebar.js') }}"></script>
</body>
</html>