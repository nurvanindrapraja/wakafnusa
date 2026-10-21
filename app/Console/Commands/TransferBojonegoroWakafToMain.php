<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferBojonegoroWakafToMain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:bojonegoro-wakaf-to-main';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer data dari tabel wk_wakaf_bojonegoro_2026 ke tabel wk_wakaf dan catat riwayat entri ke wk_hisentri';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $connection = 'mysql_no_prefix';

        $this->info("=== MEMULAI TRANSFER DATA WAKAF BOJONEGORO 2026 KE WK_WAKAF ===");

        // 1. Ambil data dari tabel sumber wk_wakaf_bojonegoro_2026
        $rows = DB::connection($connection)
            ->table('wk_wakaf_bojonegoro_2026')
            ->orderBy('id', 'asc')
            ->get();

        $totalRows = count($rows);
        if ($totalRows === 0) {
            $this->error("Tabel wk_wakaf_bojonegoro_2026 tidak memiliki data.");
            return Command::FAILURE;
        }

        $this->info("Ditemukan {$totalRows} data pada tabel wk_wakaf_bojonegoro_2026.");

        // 2. Cek apakah ada data yang sudah pernah di-insert berdasarkan NIA
        $nias = $rows->pluck('nia')->filter()->values()->toArray();
        $existingCount = DB::connection($connection)
            ->table('wk_wakaf')
            ->whereIn('NIA', $nias)
            ->count();

        if ($existingCount > 0) {
            $this->warn("Terdapat {$existingCount} data dengan NIA yang sudah ada pada tabel wk_wakaf.");
            if (!$this->confirm('Apakah Anda ingin melanjutkan dan mengabaikan/menimpa data yang sudah ada?', false)) {
                $this->info("Proses dibatalkan.");
                return Command::SUCCESS;
            }
        }

        $this->info("Memulai proses insert ke wk_wakaf dan wk_hisentri...");
        $now = now()->format('Y-m-d H:i:s');

        $insertedCount = 0;
        $insertedIds = [];

        DB::connection($connection)->transaction(function () use ($connection, $rows, $now, &$insertedCount, &$insertedIds) {
            foreach ($rows as $r) {
                // Mapping field sesuai instruksi:
                // NIA -> NIA
                // kode_lokasi -> KODE_LOKASI
                // nomor_hak -> NO_SERT
                // aiw_apaiw -> NO_AIW
                // nazhir -> NADZIR
                // luas -> LUAS
                // kode_peng -> KODE_PENG
                // peruntukan -> KETERANGAN
                // kode_index -> 16.14
                // id_user -> 4
                // token -> sha1(nia)
                $nia = trim($r->nia ?? '');
                $token = $nia !== '' ? sha1($nia) : null;

                $wakafData = [
                    'NIA' => $nia,
                    'KODE_LOKASI' => $r->kode_lokasi,
                    'NO_SERT' => $r->nomor_hak,
                    'NO_AIW' => $r->aiw_apaiw,
                    'NADZIR' => $r->nazhir,
                    'LUAS' => $r->luas,
                    'KODE_PENG' => $r->kode_peng,
                    'KETERANGAN' => $r->peruntukan,
                    'KODE_INDEX' => '16.14',
                    'ID_USER' => 4,
                    'TOKEN' => $token,
                    'ALAMAT' => 'Desa ' . $r->desa . ', Kec. ' . $r->kecamatan,
                    'NAMA' => '',
                    'WAKIF' => '-',
                    'ID_ST' => 0,
                    'ID_SP' => 0,
                    'ID_SW' => 1,
                    'ISDEL' => 0,
                    'ISOK' => 1,
                    'TGINS' => $now,
                    'ID_AIW' => 0,
                    'ID_PS' => 0,
                    'NO_BERKAS' => '',
                    'ID_KRITERIA' => 1,
                    'NJOP' => 0,
                    'THN_NJOP' => 0,
                    'LAT' => '',
                    'LNG' => '',
                ];

                // Insert ke wk_wakaf dan dapatkan ID_WAKAF
                $idWakaf = DB::connection($connection)->table('wk_wakaf')->insertGetId($wakafData);

                // Insert ke wk_hisentri
                DB::connection($connection)->table('wk_hisentri')->insert([
                    'id_wakaf' => $idWakaf,
                    'id_user' => 4,
                    'tgl_entri' => $now,
                    'fase' => 1,
                ]);

                $insertedIds[] = $idWakaf;
                $insertedCount++;
            }
        });

        $this->newLine();
        $this->info("=== TRANSFER BERHASIL DISELESAIKAN ===");
        $firstId = !empty($insertedIds) ? min($insertedIds) : '-';
        $lastId = !empty($insertedIds) ? max($insertedIds) : '-';
        $firstNia = $rows->first()->nia ?? '-';
        $lastNia = $rows->last()->nia ?? '-';

        $this->table(['Metrik', 'Nilai'], [
            ['Total Data Sumber (wk_wakaf_bojonegoro_2026)', $totalRows],
            ['Total Data Berhasil Diinsert ke wk_wakaf', $insertedCount],
            ['Total Riwayat Diinsert ke wk_hisentri', count($insertedIds)],
            ['Rentang ID_WAKAF Baru', "{$firstId} s/d {$lastId}"],
            ['Rentang NIA', "{$firstNia} s/d {$lastNia}"],
            ['ID User Penginput', '4 (NURVAN INDRA PRAJA)'],
            ['KODE_INDEX', '16.14'],
            ['Fase Hisentri', '1'],
            ['Waktu Eksekusi / tgl_entri', $now],
        ]);

        return Command::SUCCESS;
    }
}
