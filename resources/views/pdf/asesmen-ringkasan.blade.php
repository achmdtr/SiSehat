@php
    $kat = strtolower($kategoriKuartil ?? 'cukup');
    if ($kat === 'baik') {
        $primaryColor = '#0d9488';
        $secondaryColor = '#0f766e';
        $accentColor = '#14b8a6';
        $bgLight = '#f0fdfa';
        $borderLight = '#ccfbf1';
    } elseif ($kat === 'kurang') {
        $primaryColor = '#dc2626';
        $secondaryColor = '#b91c1c';
        $accentColor = '#ef4444';
        $bgLight = '#fef2f2';
        $borderLight = '#fee2e2';
    } else {
        // Cukup
        $primaryColor = '#d97706';
        $secondaryColor = '#b45309';
        $accentColor = '#f59e0b';
        $bgLight = '#fffbeb';
        $borderLight = '#fef3c7';
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ringkasan Asesmen Organisasi - SiSehat</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm 1.5cm 1.5cm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #334155;
            margin: 0;
            padding: 0;
        }
        /* Top Header Line Accent */
        .top-bar {
            height: 4px;
            background: linear-gradient(to right, {{ $primaryColor }}, {{ $accentColor }});
            margin-bottom: 20px;
        }
        /* Header Section */
        .header {
            margin-bottom: 25px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 15px;
        }
        .header table {
            width: 100%;
            border-collapse: collapse;
        }
        .header td {
            vertical-align: middle;
            padding: 0;
            border: none;
        }
        .brand-title {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .brand-subtitle {
            font-size: 10px;
            color: #64748b;
            margin: 2px 0 0 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .doc-title {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            color: {{ $primaryColor }};
            margin: 0;
        }
        .doc-subtitle {
            text-align: right;
            font-size: 9px;
            color: #64748b;
            margin: 2px 0 0 0;
        }

        /* Info & Metadata Section */
        .metadata-section {
            margin-bottom: 25px;
        }
        .metadata-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }
        .metadata-table td {
            padding: 10px 15px;
            font-size: 11px;
            border: none;
        }
        .metadata-label {
            color: #64748b;
            font-weight: 500;
            width: 30%;
        }
        .metadata-value {
            color: #1e293b;
            font-weight: bold;
        }

        /* Health Score Card Section */
        .score-container {
            margin-bottom: 25px;
        }
        .score-card {
            width: 100%;
            border-collapse: collapse;
            background: {{ $bgLight }};
            border: 1.5px solid {{ $borderLight }};
            border-radius: 8px;
        }
        .score-card td {
            padding: 15px 20px;
            border: none;
        }
        .score-number {
            font-size: 32px;
            font-weight: bold;
            color: {{ $primaryColor }};
            line-height: 1;
            margin: 0;
        }
        .score-label {
            font-size: 10px;
            color: {{ $secondaryColor }};
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            font-weight: bold;
            font-size: 11px;
            border-radius: 4px;
            text-align: center;
        }
        .badge-baik {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .badge-cukup {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        .badge-kurang {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Factor Summary Grid (Using Table for layout compatibility) */
        .factors-summary {
            margin-bottom: 30px;
        }
        .section-heading {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
            border-left: 3px solid {{ $primaryColor }};
            padding-left: 8px;
        }
        .factors-table {
            width: 100%;
            border-collapse: collapse;
        }
        .factors-table td {
            width: 50%;
            padding: 6px 12px;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
        }
        .factor-item-title {
            font-weight: bold;
            color: #334155;
            font-size: 10px;
        }
        .factor-item-val {
            text-align: right;
            font-weight: bold;
            color: {{ $primaryColor }};
            font-size: 11px;
        }
        .progress-bar-bg {
            height: 5px;
            background-color: #e2e8f0;
            border-radius: 3px;
            margin-top: 4px;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 5px;
            background-color: {{ $primaryColor }};
            border-radius: 3px;
        }

        /* Question Detail Sections */
        .factor-section {
            page-break-inside: avoid;
            margin-bottom: 25px;
        }
        .factor-title {
            font-size: 11px;
            font-weight: bold;
            color: {{ $secondaryColor }};
            background-color: {{ $bgLight }};
            padding: 6px 10px;
            border-left: 3px solid {{ $primaryColor }};
            margin-bottom: 8px;
            border-top: 1px solid {{ $borderLight }};
            border-right: 1px solid {{ $borderLight }};
            border-bottom: 1px solid {{ $borderLight }};
            border-radius: 0 4px 4px 0;
        }
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .detail-table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            text-align: left;
            padding: 6px 10px;
            border-bottom: 1.5px solid #cbd5e1;
        }
        .detail-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
            font-size: 10px;
        }
        .q-id {
            font-weight: bold;
            color: #0ea5e9;
            margin-right: 4px;
        }
        .ans-value {
            font-weight: bold;
            color: #0f172a;
            text-align: right;
            white-space: nowrap;
        }
        
        /* Footer */
        .footer {
            margin-top: 30px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            font-size: 8px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Top colored bar -->
    <div class="top-bar"></div>

    <!-- Header -->
    <div class="header">
        <table>
            <tr>
                <td>
                    <h1 class="brand-title">SiSehat</h1>
                    <div class="brand-subtitle">Kesehatan Organisasi & UMKM</div>
                </td>
                <td>
                    <h2 class="doc-title">RINGKASAN ASESMEN</h2>
                    <div class="doc-subtitle">Nomor Dokumen: SH-{{ date('Ymd') }}-{{ strtoupper(substr(uniqid(), -4)) }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Metadata Information -->
    <div class="metadata-section">
        <table class="metadata-table">
            <tr>
                <td class="metadata-label">Nama UMKM</td>
                <td class="metadata-value">: {{ $namaUmkm }}</td>
                <td class="metadata-label">Tanggal Cetak</td>
                <td class="metadata-value">: {{ $tanggalCetak }}</td>
            </tr>
            <tr>
                <td class="metadata-label">Nama Pengguna</td>
                <td class="metadata-value">: {{ $nama }}</td>
                <td class="metadata-label">Peran Pengguna</td>
                <td class="metadata-value">: {{ $peran }}</td>
            </tr>
        </table>
    </div>

    <!-- Health Score Overview Card -->
    <div class="score-container">
        <table class="score-card">
            <tr>
                <td style="width: 25%; text-align: center; border-right: 1px solid {{ $borderLight }};">
                    <div class="score-number">{{ $skorKesehatan }}<span style="font-size: 16px; color: #94a3b8;">/100</span></div>
                    <div class="score-label" style="margin-top: 4px;">Indeks Kesehatan</div>
                </td>
                <td style="width: 75%; padding-left: 20px;">
                    <div style="font-weight: bold; font-size: 12px; color: #0f172a; margin-bottom: 4px;">Status Kesehatan Organisasi:</div>
                    <span class="badge badge-{{ strtolower($kategoriKuartil) === 'baik' ? 'baik' : (strtolower($kategoriKuartil) === 'cukup' ? 'cukup' : 'kurang') }}">
                        {{ strtoupper($kategoriKuartil) }}
                    </span>
                    <div style="font-size: 10px; color: #64748b; margin-top: 6px; line-height: 1.4;">
                        Indeks dihitung berdasarkan rata-rata akumulatif dari 6 faktor penilaian yang diselaraskan dengan respon karyawan & pemilik organisasi. Status kategori ditentukan secara dinamis berdasarkan sebaran kuartil data seluruh UMKM.
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Factors Summary List -->
    <div class="factors-summary">
        <div class="section-heading">Ringkasan Nilai per Dimensi</div>
        <table class="factors-table">
            <tr>
                <td>
                    <table style="width: 100%;">
                        <tr>
                            <td class="factor-item-title" style="padding:0; border:none;">1. Nilai Organisasi</td>
                            <td class="factor-item-val" style="padding:0; border:none;">{{ $factorScores['Nilai Organisasi'] }}%</td>
                        </tr>
                    </table>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: {{ $factorScores['Nilai Organisasi'] }}%;"></div>
                    </div>
                </td>
                <td>
                    <table style="width: 100%;">
                        <tr>
                            <td class="factor-item-title" style="padding:0; border:none;">4. Stabilitas Operasional</td>
                            <td class="factor-item-val" style="padding:0; border:none;">{{ $factorScores['Stabilitas Operasional'] }}%</td>
                        </tr>
                    </table>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: {{ $factorScores['Stabilitas Operasional'] }}%;"></div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <table style="width: 100%;">
                        <tr>
                            <td class="factor-item-title" style="padding:0; border:none;">2. Keterlibatan Pemimpin</td>
                            <td class="factor-item-val" style="padding:0; border:none;">{{ $factorScores['Keterlibatan Pemimpin'] }}%</td>
                        </tr>
                    </table>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: {{ $factorScores['Keterlibatan Pemimpin'] }}%;"></div>
                    </div>
                </td>
                <td>
                    <table style="width: 100%;">
                        <tr>
                            <td class="factor-item-title" style="padding:0; border:none;">5. Kualitas Tempat Kerja</td>
                            <td class="factor-item-val" style="padding:0; border:none;">{{ $factorScores['Kualitas Tempat Kerja'] }}%</td>
                        </tr>
                    </table>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: {{ $factorScores['Kualitas Tempat Kerja'] }}%;"></div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <table style="width: 100%;">
                        <tr>
                            <td class="factor-item-title" style="padding:0; border:none;">3. Sumber Daya Institusi</td>
                            <td class="factor-item-val" style="padding:0; border:none;">{{ $factorScores['Sumber Daya Institusi'] }}%</td>
                        </tr>
                    </table>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: {{ $factorScores['Sumber Daya Institusi'] }}%;"></div>
                    </div>
                </td>
                <td>
                    <table style="width: 100%;">
                        <tr>
                            <td class="factor-item-title" style="padding:0; border:none;">6. Kinerja Ekonomi</td>
                            <td class="factor-item-val" style="padding:0; border:none;">{{ $factorScores['Kinerja Ekonomi'] }}%</td>
                        </tr>
                    </table>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: {{ $factorScores['Kinerja Ekonomi'] }}%;"></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Detailed Responses Grouped by Factors -->
    <div class="section-heading" style="margin-bottom: 15px;">Detail Jawaban Pertanyaan</div>
    
    @foreach($groupedRows as $factorName => $questions)
    <div class="factor-section">
        <div class="factor-title">{{ $factorName }}</div>
        <table class="detail-table">
            <thead>
                <tr>
                    <th style="width: 10%;">Kode</th>
                    <th style="width: 65%;">Pertanyaan</th>
                    <th style="width: 25%; text-align: right;">Hasil Jawaban</th>
                </tr>
            </thead>
            <tbody>
                @foreach($questions as $q)
                <tr>
                    <td><span class="q-id">{{ $q['id'] }}</span></td>
                    <td>{{ $q['pertanyaan'] }}</td>
                    <td class="ans-value" style="text-align: right;">{{ $q['jawaban'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach

    <!-- Footer -->
    <div class="footer">
        Laporan ini digenerate secara otomatis oleh sistem SiSehat. &copy; {{ date('Y') }} SiSehat. All rights reserved.
    </div>
</body>
</html>
