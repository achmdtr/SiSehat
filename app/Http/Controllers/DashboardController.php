<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Assessment;
use App\Models\Factor;
use App\Models\Umkm;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    /**
     * Teks pertanyaan tampilan bahasa Indonesia (fallback ke kolom DB bila tidak ada).
     *
     * @return array<string, string>
     */
    protected function questionTextTranslations(): array
    {
        return [
            'OH1' => 'Apakah usaha Anda memiliki Nomor Induk Berusaha (NIB)?',
            'OH2' => 'Apakah Anda pernah menerima bantuan pendanaan dari pemerintah?',
            'OH3' => 'Apakah Anda pernah mengikuti kegiatan pemerintah (pelatihan, workshop, pameran)?',
            'OH4' => 'Apakah Anda bekerja sama dengan pedagang atau usaha lain untuk mengembangkan bisnis?',
            'OH5' => 'Apakah Anda menggunakan pemasaran digital (media sosial, web, iklan) untuk promosi?',
            'OH6' => 'Apa sumber modal utama keuangan usaha Anda?',
            'OH10' => 'Apakah pemimpin Anda terlibat langsung dalam pekerjaan sehari-hari?',
            'OH11' => 'Apakah pemimpin Anda aktif berpartisipasi dalam melatih karyawan baru?',
            'OH12' => 'Apakah pemimpin memperlakukan karyawan dengan adil saat memberi sanksi atau penghargaan?',
            'OH7' => 'Apakah pemimpin mengenal karyawan secara personal?',
            'OH8' => 'Apakah pemimpin menjadi panutan bagi karyawan?',
            'OH9' => 'Apakah pemimpin terbuka dalam menerapkan ide atau saran dari karyawan?',
            'OH13' => 'Apakah tempat kerja bebas dari kecelakaan kerja?',
            'OH14' => 'Apakah karyawan puas dengan jam kerja yang ditetapkan perusahaan?',
            'OH15' => 'Apakah beban kerja karyawan dapat dikelola dengan baik?',
            'OH16' => 'Apakah kondisi fisik tempat kerja (suara, cahaya, suhu) terasa nyaman?',
            'OH17' => 'Apakah sebagian besar karyawan bertanggung jawab dan jarang absen tanpa alasan?',
            'OH18' => 'Apakah karyawan merasa bebas mendiskusikan kesulitan kerja dengan pemimpin atau rekan?',
            'OH19' => 'Apakah suasana di tempat kerja terasa positif dan mendukung?',
            'OH20' => 'Apakah semangat kerja karyawan secara umum tinggi?',
            'OH21' => 'Apakah karyawan saling membantu saat dibutuhkan?',
            'OH22' => 'Apakah tingkat kepercayaan antar karyawan sangat kuat?',
            'OH23' => 'Apakah kerjasama tim berjalan efektif?',
            'OH24' => 'Apakah karyawan saling menghormati satu sama lain?',
            'OH25' => 'Apakah karyawan memandang pekerjaan mereka sebagai bentuk pengabdian atau ibadah?',
            'OH26' => 'Apakah modal kerja mencukupi untuk operasional harian?',
            'OH27' => 'Apakah perusahaan mampu membayar gaji karyawan tepat waktu?',
            'OH28' => 'Apakah peralatan atau mesin dalam kondisi baik dan berfungsi optimal?',
            'OH29' => 'Apakah rantai pasok usaha berjalan lancar tanpa gangguan berarti?',
            'OH30' => 'Apakah permintaan produk atau jasa dapat diprediksi dengan baik?',
            'OH31' => 'Apakah kualitas hubungan jangka panjang dengan pelanggan sudah baik?',
            'OH32' => 'Apakah kemampuan usaha menjangkau pasar atau pelanggan baru sudah sangat baik?',
            'OH33' => 'Apakah pertumbuhan penjualan dalam setahun terakhir sangat memuaskan?',
            'OH34' => 'Apakah kewajiban usaha (pinjaman atau hutang) sangat terkendali?',
            'OH35' => 'Apakah kondisi arus kas usaha sangat sehat dan stabil?',
        ];
    }

    public function index()
    {
        $user = auth()->user();
        $id_user = $user->id_user;
        $role = $user->role;

        // Base Query untuk Assessments
        // Jika admin, ambil semua data global.
        // Jika owner/employee, ambil semua data asesmen yang ada di UMKM mereka (kolektif)
        if ($role === 'admin') {
            $assessmentQuery = Assessment::query();
        } else {
            $assessmentQuery = Assessment::where('id_umkm', $user->id_umkm);
        }

        // Statistik
        $stats = [
            'total_umkm' => Umkm::count(),
            'total_responden' => Assessment::count(),
            'total_kesehatan' => round(((clone $assessmentQuery)->avg('total_score') ?? 0) * 20),
        ];

        // Data Radar Chart (Faktor) - Dikalikan 20 agar jadi skala 100
        $avgFactors = (clone $assessmentQuery)
            ->select(
                DB::raw('AVG(score_ov) * 20 as ov'),
                DB::raw('AVG(score_ldi) * 20 as ldi'),
                DB::raw('AVG(score_ins) * 20 as ins'),
                DB::raw('AVG(score_ops) * 20 as ops'),
                DB::raw('AVG(score_weq) * 20 as weq'),
                DB::raw('AVG(score_ect) * 20 as ect')
            )->first();

        $data_factors = [
            round($avgFactors->ov ?? 0),
            round($avgFactors->ldi ?? 0),
            round($avgFactors->ins ?? 0),
            round($avgFactors->ops ?? 0),
            round($avgFactors->weq ?? 0),
            round($avgFactors->ect ?? 0),
        ];

        // Ranking UMKM (Selalu Global - Dikalikan 20)
        $top_umkm = Assessment::join('umkm', 'assessments.id_umkm', '=', 'umkm.id_umkm')
            ->select('umkm.nama_umkm', DB::raw('AVG(total_score) * 20 as total_score'))
            ->groupBy('umkm.id_umkm', 'umkm.nama_umkm')
            ->orderByDesc('total_score')
            ->limit(3)
            ->get();

        $bottom_umkm = Assessment::join('umkm', 'assessments.id_umkm', '=', 'umkm.id_umkm')
            ->select('umkm.nama_umkm', DB::raw('AVG(total_score) * 20 as total_score'))
            ->groupBy('umkm.id_umkm', 'umkm.nama_umkm')
            ->orderBy('total_score')
            ->limit(3)
            ->get();

        // Data Industri (Tipe Bisnis)
        $industryCounts = Umkm::select('industry', DB::raw('count(*) as total'))
            ->groupBy('industry')
            ->pluck('total', 'industry')
            ->toArray();

        // Data Usia Perusahaan (usia_usaha)
        $ageCounts = Umkm::select('usia_usaha', DB::raw('count(*) as total'))
            ->groupBy('usia_usaha')
            ->pluck('total', 'usia_usaha')
            ->toArray();

        $data_age = [
            $ageCounts[1] ?? 0,
            $ageCounts[2] ?? 0,
            $ageCounts[3] ?? 0,
        ];

        $data_industry = [
            $industryCounts[1] ?? 0,
            $industryCounts[2] ?? 0,
            $industryCounts[3] ?? 0,
        ];

        // Tentukan status kesehatan berdasarkan skor (skala 100)
        $status_kesehatan = 'KURANG';
        if ($stats['total_kesehatan'] >= 75) {
            $status_kesehatan = 'BAIK';
        } elseif ($stats['total_kesehatan'] >= 50) {
            $status_kesehatan = 'CUKUP';
        }

        // Ambil Insight Dinamis dari tabel Recommendations
        // 1. Identifikasi faktor terlemah (skala 1-5)
        $factorAverages = [
            1 => $avgFactors->ov / 20,
            2 => $avgFactors->ldi / 20,
            3 => $avgFactors->ins / 20,
            4 => $avgFactors->ops / 20,
            5 => $avgFactors->weq / 20,
            6 => $avgFactors->ect / 20,
        ];

        // Temukan ID faktor dengan skor terendah
        asort($factorAverages);
        $weakestFactorId = key($factorAverages);
        $weakestScore = current($factorAverages);

        // 2. Ambil data rekomendasi berdasarkan skor faktor tersebut
        $recommendation = DB::table('recommendations')
            ->where('id_factor', $weakestFactorId)
            ->where('min_score', '<=', $weakestScore)
            ->where('max_score', '>=', $weakestScore)
            ->first();

        $insightText = $recommendation ? $recommendation->insight_text : 'Fokuslah pada perbaikan faktor-faktor dengan skor terendah untuk meningkatkan efisiensi operasional.';
        
        // Tambahkan nama faktor ke insight jika perlu
        $factorNames = [
            1 => 'Nilai Organisasi',
            2 => 'Keterlibatan Pemimpin',
            3 => 'Sumber Daya Institusi',
            4 => 'Stabilitas Operasional',
            5 => 'Kualitas Tempat Kerja',
            6 => 'Kinerja Ekonomi',
        ];
        $weakestFactorName = $factorNames[$weakestFactorId] ?? 'Faktor Utama';

        $data = [
            'skor_kesehatan' => $stats['total_kesehatan'],
            'status_kesehatan' => $status_kesehatan,
            'insight_kritis' => "<b>⚠️ Insight Kritis ({$weakestFactorName}):</b> " . $insightText,
            'total_responden' => $stats['total_responden'],
            'total_umkm' => $stats['total_umkm'],
            'faktor_dianalisis' => Factor::count(),
            'data_radar' => $data_factors,
            'data_factors' => $data_factors,
            'data_tipe_bisnis' => $data_industry,
            'top_umkm' => $top_umkm,
            'bottom_umkm' => $bottom_umkm,
            'data_age' => $data_age,
        ];

        return view('dashboard', compact('data'));
    }

    public function enamFaktor()
    {
        $user = auth()->user();
        $id_umkm = $user->id_umkm;
        $role = $user->role;

        // Base Query untuk Assessments berdasarkan UMKM
        $assessmentQuery = Assessment::where('id_umkm', $id_umkm);

        // Ambil rata-rata per faktor
        $avgFactors = (clone $assessmentQuery)
            ->select(
                DB::raw('AVG(score_ov) as ov'),
                DB::raw('AVG(score_ldi) as ldi'),
                DB::raw('AVG(score_ins) as ins'),
                DB::raw('AVG(score_ops) as ops'),
                DB::raw('AVG(score_weq) as weq'),
                DB::raw('AVG(score_ect) as ect')
            )->first();

        // Ambil rata-rata total (skala 100)
        $avgScore = ((clone $assessmentQuery)->avg('total_score') ?? 0) * 20;

        // Metadata untuk looping di view
        $factors = [
            [
                'id' => 1,
                'title' => 'Nilai Organisasi',
                'score' => round($avgFactors->ov ?? 0, 2),
                'percentage' => round(($avgFactors->ov ?? 0) * 20, 1),
                'desc' => 'Nilai-nilai inti organisasi cukup terinternalisasi, namun perlu penyelarasan lebih lanjut untuk mendorong budaya kerja yang optimal.',
                'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>'
            ],
            [
                'id' => 2,
                'title' => 'Keterlibatan Pemimpin',
                'score' => round($avgFactors->ldi ?? 0, 2),
                'percentage' => round(($avgFactors->ldi ?? 0) * 20, 1),
                'desc' => 'Keterlibatan pimpinan ada namun belum konsisten. Komunikasi dan arahan strategis perlu ditingkatkan untuk memotivasi tim.',
                'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>'
            ],
            [
                'id' => 3,
                'title' => 'Sumber Daya Institusi',
                'score' => round($avgFactors->ins ?? 0, 2),
                'percentage' => round(($avgFactors->ins ?? 0) * 20, 1),
                'desc' => 'Kekurangan sumber daya krusial menghambat operasional. Diperlukan alokasi ulang aset dan investasi pada infrastruktur esensial.',
                'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>'
            ],
            [
                'id' => 4,
                'title' => 'Stabilitas Operasional',
                'score' => round($avgFactors->ops ?? 0, 2),
                'percentage' => round(($avgFactors->ops ?? 0) * 20, 1),
                'desc' => 'Proses operasional rentan terhadap gangguan. SOP perlu ditinjau ulang untuk meminimalisir kesalahan dan meningkatkan efisiensi.',
                'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>'
            ],
            [
                'id' => 5,
                'title' => 'Kualitas Tempat Kerja',
                'score' => round($avgFactors->weq ?? 0, 2),
                'percentage' => round(($avgFactors->weq ?? 0) * 20, 1),
                'desc' => 'Lingkungan kerja cukup memadai namun masih ada ruang perbaikan untuk kesejahteraan karyawan dan fasilitas pendukung.',
                'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>'
            ],
            [
                'id' => 6,
                'title' => 'Kinerja Ekonomi',
                'score' => round($avgFactors->ect ?? 0, 2),
                'percentage' => round(($avgFactors->ect ?? 0) * 20, 1),
                'desc' => 'Kinerja finansial berada di bawah target yang diharapkan. Diperlukan evaluasi strategi bisnis dan penekanan biaya secara signifikan.',
                'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>'
            ],
        ];

        $data = [
            'skor_kesehatan' => round($avgScore),
            'factors' => $factors
        ];

        return view('enam-faktor', compact('data'));
    }

    public function rekomendasi()
    {
        $user = auth()->user();
        $id_umkm = $user->id_umkm;
        $role = $user->role;

        // Base Query berdasarkan UMKM
        $assessmentQuery = Assessment::where('id_umkm', $id_umkm);

        $avgScore = ((clone $assessmentQuery)->avg('total_score') ?? 0) * 20;
        
        // Ambil rata-rata per faktor (dikalikan 20)
        $avgFactors = (clone $assessmentQuery)
            ->select(
                DB::raw('AVG(score_ov) * 20 as ov'),
                DB::raw('AVG(score_ldi) * 20 as ldi'),
                DB::raw('AVG(score_ins) * 20 as ins'),
                DB::raw('AVG(score_ops) * 20 as ops'),
                DB::raw('AVG(score_weq) * 20 as weq'),
                DB::raw('AVG(score_ect) * 20 as ect')
            )->first();

        // Tentukan status berdasarkan skor (skala 100)
        $status = 'KONDISI KURANG';
        if ($avgScore >= 75) $status = 'KONDISI BAIK';
        elseif ($avgScore >= 50) $status = 'KONDISI CUKUP';

        $factorsArray = [
            ['name' => 'Nilai Organisasi', 'score' => $avgFactors->ov, 'desc' => 'Mempertahankan budaya positif yang selaras dengan visi strategis.'],
            ['name' => 'Keterlibatan Pemimpin', 'score' => $avgFactors->ldi, 'desc' => 'Keterlibatan pemimpin dalam mendukung operasional harian.'],
            ['name' => 'Sumber Daya Institusi', 'score' => $avgFactors->ins, 'desc' => 'Ketersediaan sumber daya esensial untuk mendukung kerja.'],
            ['name' => 'Stabilitas Operasional', 'score' => $avgFactors->ops, 'desc' => 'Kelancaran proses dan prosedur operasional.'],
            ['name' => 'Kualitas Tempat Kerja', 'score' => $avgFactors->weq, 'desc' => 'Kondisi lingkungan kerja dan kesejahteraan karyawan.'],
            ['name' => 'Kinerja Ekonomi', 'score' => $avgFactors->ect, 'desc' => 'Performa finansial dan efisiensi biaya.'],
        ];

        usort($factorsArray, fn($a, $b) => $b['score'] <=> $a['score']);

        $data = [
            'skor_kesehatan' => round($avgScore),
            'status' => $status,
            'sorted_factors' => $factorsArray
        ];

        return view('rekomendasi', compact('data')); 
    }

    public function tambahUmkm()
    {
        $user = auth()->user();
        
        // Proteksi: Employee tidak boleh tambah UMKM
        if ($user->role === 'employee') {
            abort(403, 'Akses Ditolak. Karyawan tidak memiliki izin untuk menambah UMKM.');
        }

        return view('tambah-umkm');
    }
    
    public function simpanUmkm(Request $request)
    {
        $user = auth()->user();
        
        // Proteksi: Employee tidak boleh simpan UMKM
        if ($user->role === 'employee') {
            return response()->json([
                'success' => false,
                'message' => 'Akses Ditolak. Karyawan tidak diizinkan mendaftarkan UMKM.'
            ], 403);
        }

        $validated = $request->validate([
            'nama_umkm' => 'required|string|max:255',
            'industry' => 'required',
            'usia_usaha' => 'required|numeric',
        ]);

        try {
            // Cek apakah user sudah memiliki UMKM
            $existingUmkm = Umkm::where('id_user', $user->id_user)->first();
            
            if ($existingUmkm) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memiliki satu UMKM terdaftar. Anda hanya diperbolehkan memiliki satu usaha.'
                ], 400);
            }

            $umkm = Umkm::create([
                'nama_umkm' => $validated['nama_umkm'],
                'industry' => $validated['industry'],
                'usia_usaha' => $validated['usia_usaha'],
                'id_user' => $user->id_user,
            ]);

            // Update user's id_umkm agar terhubung dengan UMKM baru
            $user->update(['id_umkm' => $umkm->id_umkm]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function tambahKaryawan()
    {
        return view('tambah-karyawan');
    }

    public function simpanKaryawan(Request $request)
    {
        $owner = auth()->user();

        $validator = Validator::make($request->all(), [
            'nama_user' => 'required|string|max:255',
            'gender' => 'required',
            'age' => 'required|numeric',
            'password' => 'required|string|min:8',
        ], [
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal harus 8 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        // Logika Pengelompokan Umur Otomatis
        $ageInput = $validated['age'];
        $ageCategory = 3; // Default > 40
        if ($ageInput < 30) {
            $ageCategory = 1;
        } elseif ($ageInput <= 40) {
            $ageCategory = 2;
        }

        try {
            User::create([
                'nama_user' => $validated['nama_user'],
                'gender' => $validated['gender'],
                'age' => $ageCategory, // Simpan kategori
                'password' => bcrypt($validated['password']),
                'role' => 'employee',
                'id_umkm' => $owner->id_umkm, // Otomatis ke UMKM milik owner
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal membuat akun: ' . $e->getMessage()
            ], 500);
        }
    }

    public function manajemenUmkm()
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Ambil data UMKM yang dimiliki oleh user ini
        $umkm = Umkm::where('id_user', $user->id_user)->get();

        return view('manajemen-umkm', compact('umkm'));
    }

    public function manajemenKaryawan($id)
    {
        // Ambil info UMKM
        $umkm = Umkm::findOrFail($id);
        
        // Ambil karyawan yang terdaftar di UMKM ini
        $karyawan = User::where('id_umkm', $id)
                        ->where('role', 'employee')
                        ->get();

        return view('manajemen-karyawan', compact('karyawan', 'umkm'));
    }

    public function asesmenOrganisasi()
    {
        $user = auth()->user();
        $role = $user->role;
        $id_umkm = $user->id_umkm;

        // Cek apakah ada asesmen yang sedang berjalan
        $activeAssessment = Assessment::where('id_umkm', $id_umkm)
            ->where('status', 'Menunggu')
            ->first();

        // Jika user sudah menyelesaikan bagiannya di asesmen yang sedang berjalan
        if ($activeAssessment) {
            if (($role === 'owner' && $activeAssessment->owner_finished) || 
                ($role === 'employee' && $activeAssessment->employee_finished)) {
                
                if ($role === 'employee') {
                    // Jangan redirect ke dashboard karena akan kena middleware loop.
                    // Tampilkan saja view asesmen dengan state sudah selesai (kosongkan sections).
                    return view('asesmen-organisasi', [
                        'sections' => [],
                        'alreadyFinished' => true
                    ]);
                }
                
                return redirect()->route('dashboard')->with('info', 'Anda sudah mengisi bagian asesmen Anda. Menunggu pihak lain melengkapi.');
            }
        }

        // Filter pertanyaan berdasarkan role
        $questions = DB::table('questions')
            ->where(function($query) use ($role) {
                $query->where('target_role', $role)
                      ->orWhere('target_role', 'both');
            })
            ->orderBy('id_factor')
            ->orderBy('id_question')
            ->get();

        $factorMeta = [
            1 => ['title' => 'Nilai Organisasi', 'desc' => 'Evaluasi nilai-nilai inti dan budaya operasional usaha anda.'],
            2 => ['title' => 'Keterlibatan Pemimpin', 'desc' => 'Evaluasi peran aktif pemimpin dalam operasional UMKM.'],
            3 => ['title' => 'Kualitas Tempat Kerja', 'desc' => 'Evaluasi kenyamanan dan keamanan lingkungan usaha.'],
            4 => ['title' => 'Nilai Organisasi & Budaya', 'desc' => 'Evaluasi suasana kerja dan kerjasama tim.'],
            5 => ['title' => 'Stabilitas Operasional', 'desc' => 'Evaluasi kelancaran prosedur dan pencatatan usaha.'],
            6 => ['title' => 'Kinerja Ekonomi', 'desc' => 'Evaluasi pertumbuhan finansial dan target usaha.'],
        ];

        $translations = $this->questionTextTranslations();

        $sections = [];
        foreach ($questions as $q) {
            $fId = $q->id_factor;
            if (!isset($sections[$fId])) {
                $sections[$fId] = [
                    'title' => $factorMeta[$fId]['title'] ?? 'Faktor ' . $fId,
                    'desc' => $factorMeta[$fId]['desc'] ?? '',
                    'questions' => []
                ];
            }
            $sections[$fId]['questions'][] = [
                'id' => $q->id_question,
                'text' => $translations[$q->id_question] ?? $q->teks_pertanyaan
            ];
        }

        // Re-index to numeric array for JS
        $sections = array_values($sections);

        return view('asesmen-organisasi', compact('sections'));
    }

    public function detailFaktor($id)
    {
        $user = auth()->user();
        if (!$user) return redirect()->route('login');
        
        $id_umkm = $user->id_umkm;

        // Ambil asesmen terbaru untuk UMKM ini
        $latestAssessment = Assessment::where('id_umkm', $id_umkm)
            ->where('status', 'Selesai')
            ->latest()
            ->first();

        if (!$latestAssessment) {
            return redirect()->route('dashboard.faktor')->with('error', 'Belum ada data asesmen yang selesai untuk UMKM Anda.');
        }

        // Ambil meta data faktor
        $factorMeta = [
            1 => ['title' => 'Nilai Organisasi', 'col' => 'score_ov', 'desc' => 'Faktor ini mengevaluasi nilai-nilai inti dan budaya operasional yang mendasari praktik etika dan interaksi profesional di dalam organisasi.'],
            2 => ['title' => 'Keterlibatan Pemimpin', 'col' => 'score_ldi', 'desc' => 'Mengevaluasi peran aktif pemimpin dalam operasional dan bimbingan karyawan.'],
            3 => ['title' => 'Sumber Daya Institusi', 'col' => 'score_ins', 'desc' => 'Mengevaluasi ketersediaan dan kualitas sumber daya pendukung usaha.'],
            4 => ['title' => 'Stabilitas Operasional', 'col' => 'score_ops', 'desc' => 'Mengevaluasi kelancaran prosedur dan konsistensi operasional.'],
            5 => ['title' => 'Kualitas Tempat Kerja', 'col' => 'score_weq', 'desc' => 'Mengevaluasi kenyamanan dan keamanan lingkungan kerja.'],
            6 => ['title' => 'Kinerja Ekonomi', 'col' => 'score_ect', 'desc' => 'Mengevaluasi aspek finansial dan pertumbuhan ekonomi usaha.'],
        ];

        $id = (int) $id;
        if (!isset($factorMeta[$id])) {
            abort(404);
        }

        $meta = $factorMeta[$id];
        $column = $meta['col'];
        $score = (float) ($latestAssessment->$column ?? 0);

        // Ambil pertanyaan untuk faktor ini untuk sub-indicators
        $questions = DB::table('questions')->where('id_factor', $id)->get();
        $answers = json_decode($latestAssessment->answers, true) ?? [];
        $translations = $this->questionTextTranslations();

        $subIndicators = [];
        $chartData = [];
        $scoreMap3 = ['Tidak' => 1, 'Sedang Proses' => 2, 'Ya' => 3, 'Modal Sendiri' => 1, 'Keluarga' => 2, 'Bank / Kredit' => 3];
        $scoreMap5 = ['Sangat Tidak Setuju' => 1, 'Tidak Setuju' => 2, 'Ragu-ragu' => 3, 'Setuju' => 4, 'Sangat Setuju' => 5];

        foreach ($questions as $q) {
            $qId = $q->id_question;
            $qIdNum = (int) str_replace('OH', '', $qId);
            $val = $answers[$qId] ?? null;
            
            $s = 1.0; // Default
            if ($val) {
                if ($qIdNum <= 6) {
                    $raw = $scoreMap3[$val] ?? 1;
                    $s = (($raw - 1) / 2.0) * 4 + 1;
                } else {
                    $s = (float) ($scoreMap5[$val] ?? 1);
                }
            }
            
            $cat = ($s >= 3.75) ? 'Tinggi' : (($s >= 2.5) ? 'Sedang' : 'Rendah');
            $subIndicators[] = [
                'name' => $translations[$q->id_question] ?? $q->teks_pertanyaan,
                'score' => number_format($s, 2),
                'category' => $cat
            ];
            $chartData[] = round($s, 2);
        }

        $faktor = [
            'id' => $id,
            'title' => $meta['title'],
            'description' => $meta['desc'],
            'score' => number_format($score, 2),
            'category_percentage' => round($score * 20),
            'category_label' => ($score >= 3.67) ? 'Baik' : (($score >= 2.34) ? 'Sedang' : 'Kurang'),
            'sub_indicators' => $subIndicators,
            'chart_data' => $chartData
        ];

        return view('faktor-detail', compact('faktor'));
    }

    public function simpanAsesmen(Request $request)
    {
        try {
            $user = auth()->user();
            $newAnswers = $request->input('answers');
            $id_umkm = $user->id_umkm;

            // Cari asesmen yang sedang berjalan (status Menunggu) untuk UMKM ini
            $assessment = Assessment::where('id_umkm', $id_umkm)
                ->where('status', 'Menunggu')
                ->first();

            if (!$assessment) {
                // Jika belum ada, buat record baru
                $assessment = new Assessment();
                $assessment->id_umkm = $id_umkm;
                $assessment->id_user = $user->id_user; // Tetap simpan id_user utama
                $assessment->status = 'Menunggu';
                $assessment->started_at = now();
                $assessment->answers = json_encode($newAnswers);
            } else {
                // Jika sudah ada, gabungkan jawaban baru dengan yang lama
                $existingAnswers = json_decode($assessment->answers, true) ?? [];
                $mergedAnswers = array_merge($existingAnswers, $newAnswers);
                $assessment->answers = json_encode($mergedAnswers);
            }

            // Tandai siapa yang menyelesaikan bagiannya
            if ($user->role === 'owner') {
                $assessment->owner_finished = true;
                $assessment->id_owner = $user->id_user;
            } else {
                $assessment->employee_finished = true;
                $assessment->id_employee = $user->id_user;
            }

            // Hitung skor sementara/akhir dari semua jawaban yang ada saat ini
            $allAnswers = json_decode($assessment->answers, true);
            $this->calculateAndSaveScores($assessment, $allAnswers);

            // Cek apakah sudah lengkap untuk mengubah status
            if ($assessment->owner_finished && $assessment->employee_finished) {
                $assessment->status = 'Selesai';
                $assessment->finished_at = now();
                $assessment->save();
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan asesmen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper untuk menghitung skor dari gabungan jawaban Owner & Employee
     */
    private function calculateAndSaveScores($assessment, $answers)
    {
        $scoreMap3 = [
            'Tidak' => 1, 'Sedang Proses' => 2, 'Ya' => 3,
            'Modal Sendiri' => 1, 'Keluarga' => 2, 'Bank / Kredit' => 3
        ];
        
        $scoreMap5 = [
            'Sangat Tidak Setuju' => 1,
            'Tidak Setuju' => 2,
            'Ragu-ragu' => 3,
            'Setuju' => 4,
            'Sangat Setuju' => 5
        ];
        
        $allQuestions = DB::table('questions')->get();
        $factorScores = [1 => [], 2 => [], 3 => [], 4 => [], 5 => [], 6 => []];
        
        foreach ($allQuestions as $q) {
            $fId = $q->id_factor;
            $qId = $q->id_question;
            $qIdNum = (int) str_replace('OH', '', $qId);
            
            if (isset($answers[$qId])) {
                $val = $answers[$qId];
                if ($qIdNum <= 6) {
                    $rawScore = $scoreMap3[$val] ?? 1;
                    $score = (($rawScore - 1) / 2.0) * 4 + 1;
                } else {
                    $score = $scoreMap5[$val] ?? 1;
                }
                $factorScores[$fId][] = $score;
            }
        }

        $getAvg = function($scores) {
            return count($scores) > 0 ? array_sum($scores) / count($scores) : 1;
        };

        $assessment->score_ov = $getAvg($factorScores[1]);
        $assessment->score_ldi = $getAvg($factorScores[2]);
        $assessment->score_ins = $getAvg($factorScores[3]);
        $assessment->score_ops = $getAvg($factorScores[4]);
        $assessment->score_weq = $getAvg($factorScores[5]);
        $assessment->score_ect = $getAvg($factorScores[6]);

        $assessment->total_score = ($assessment->score_ov + $assessment->score_ldi + $assessment->score_ins + $assessment->score_ops + $assessment->score_weq + $assessment->score_ect) / 6;
        
        // Penentuan Kategori berdasarkan Skor (1-5)
        if ($assessment->total_score <= 2.33) {
            $assessment->kategori_kuartil = 'Kurang';
        } elseif ($assessment->total_score <= 3.66) {
            $assessment->kategori_kuartil = 'Cukup';
        } else {
            $assessment->kategori_kuartil = 'Baik';
        }
        
        $assessment->save();
    }

    /**
     * Unduh ringkasan jawaban asesmen UMKM terkini (PDF).
     */
    public function unduhRingkasanAsesmenPdf()
    {
        $user = auth()->user();
        if (! $user || ! $user->id_umkm) {
            abort(403);
        }

        $assessment = Assessment::where('id_umkm', $user->id_umkm)
            ->whereIn('status', ['Menunggu', 'Selesai'])
            ->orderByDesc('started_at')
            ->first();

        if (! $assessment) {
            abort(404, 'Belum ada data asesmen.');
        }

        $answers = json_decode($assessment->answers, true) ?? [];
        $translations = $this->questionTextTranslations();

        $rows = [];
        $questions = DB::table('questions')->orderBy('id_factor')->orderBy('id_question')->get();
        foreach ($questions as $q) {
            $qid = $q->id_question;
            $rows[] = [
                'id' => $qid,
                'pertanyaan' => $translations[$qid] ?? $q->teks_pertanyaan,
                'jawaban' => $answers[$qid] ?? '—',
            ];
        }

        $filename = 'Ringkasan_Asesmen_'.preg_replace('/[^A-Za-z0-9_-]+/', '_', $user->nama_user).'_'.date('Y-m-d').'.pdf';

        return Pdf::loadView('pdf.asesmen-ringkasan', [
            'nama' => $user->nama_user,
            'peran' => $user->role === 'employee' ? 'Karyawan' : 'Pemilik UMKM',
            'tanggalCetak' => now()->format('d/m/Y H:i'),
            'statusAsesmen' => $assessment->status,
            'rows' => $rows,
        ])
            ->setPaper('a4', 'portrait')
            ->download($filename);
    }
}