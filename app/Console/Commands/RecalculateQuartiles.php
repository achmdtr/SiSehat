<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Assessment;
use Illuminate\Support\Facades\DB;

class RecalculateQuartiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assessment:recalculate-quartiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menghitung ulang kategori kuartil untuk semua data assessment yang sudah ada berdasarkan distribusi total score terbaru.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Ambil semua data total_score yang valid
        $allScores = DB::table('assessments')
            ->whereNotNull('total_score')
            ->pluck('total_score')
            ->toArray();

        if (empty($allScores)) {
            $this->info('Tidak ada data assessment untuk dihitung.');
            return;
        }

        // 2. Urutkan dari terendah ke tertinggi
        sort($allScores);
        $count = count($allScores);

        // 3. Perhitungan Posisi Kuartil (Interpolasi Linear Sederhana)
        $pos1 = ($count - 1) * 0.25;
        $pos3 = ($count - 1) * 0.75;
        
        $base1 = floor($pos1);
        $rest1 = $pos1 - $base1;
        $q1 = isset($allScores[$base1 + 1]) 
            ? $allScores[$base1] + $rest1 * ($allScores[$base1 + 1] - $allScores[$base1]) 
            : $allScores[$base1];
            
        $base3 = floor($pos3);
        $rest3 = $pos3 - $base3;
        $q3 = isset($allScores[$base3 + 1]) 
            ? $allScores[$base3] + $rest3 * ($allScores[$base3 + 1] - $allScores[$base3]) 
            : $allScores[$base3];

        $this->info("Total Data: {$count}");
        $this->info("Nilai Q1 (Batas Bawah): " . round($q1, 3));
        $this->info("Nilai Q3 (Batas Atas): " . round($q3, 3));

        // 4. Update seluruh data sesuai dengan batas Kuartil yang baru
        $assessments = Assessment::whereNotNull('total_score')->get();
        $updatedCount = 0;

        foreach ($assessments as $assessment) {
            if ($assessment->total_score <= $q1) {
                $kategori = 'Kurang';
            } elseif ($assessment->total_score <= $q3) {
                $kategori = 'Cukup';
            } else {
                $kategori = 'Baik';
            }

            if ($assessment->kategori_kuartil !== $kategori) {
                $assessment->kategori_kuartil = $kategori;
                $assessment->save();
                $updatedCount++;
            }
        }

        $this->info("Berhasil! Kategori kuartil diperbarui pada {$updatedCount} data assessment lama.");
    }
}
