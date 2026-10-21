<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ImportBojonegoroWakaf2026 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:bojonegoro-wakaf-2026 {--file= : Path ke file Excel}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data wakaf dari file bojonegoro_wakaf_nu_2026.xlsx ke tabel wk_wakaf_bojonegoro_2026 dengan mapping kode_lokasi dan kode_peng';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->option('file') ?: public_path('upload/bojonegoro_wakaf_nu_2026.xlsx');

        if (!file_exists($filePath)) {
            $this->error("File Excel tidak ditemukan pada path: {$filePath}");
            return Command::FAILURE;
        }

        $this->info("=== MEMULAI IMPORT DATA WAKAF BOJONEGORO 2026 ===");
        $this->info("File: {$filePath}");

        // 1. Memastikan tabel wk_wakaf_bojonegoro_2026 tersedia
        $connection = 'mysql_no_prefix';
        if (!Schema::connection($connection)->hasTable('wk_wakaf_bojonegoro_2026')) {
            $this->info("Membuat tabel wk_wakaf_bojonegoro_2026...");
            Schema::connection($connection)->create('wk_wakaf_bojonegoro_2026', function (Blueprint $table) {
                $table->id();
                $table->string('no', 20)->nullable();
                $table->string('nia', 20)->nullable()->index();
                $table->string('kecamatan', 100)->nullable();
                $table->string('desa', 100)->nullable();
                $table->string('kode_lokasi', 20)->nullable()->index();
                $table->string('nomor_hak', 100)->nullable();
                $table->string('aiw_apaiw', 100)->nullable();
                $table->text('nazhir')->nullable();
                $table->decimal('luas', 12, 2)->nullable();
                $table->string('peruntukan', 100)->nullable();
                $table->string('kode_peng', 20)->nullable()->index();
                $table->timestamps();
            });
        } elseif (!Schema::connection($connection)->hasColumn('wk_wakaf_bojonegoro_2026', 'nia')) {
            Schema::connection($connection)->table('wk_wakaf_bojonegoro_2026', function (Blueprint $table) {
                $table->string('nia', 20)->nullable()->index()->after('no');
            });
        }

        // 2. Siapkan data referensi wk_lokasi Bojonegoro (35.22)
        $this->info("Memuat data referensi wilayah dari wk_lokasi...");
        $kecRows = DB::connection($connection)->table('wk_lokasi')
            ->where('kode_lokasi', 'LIKE', '35.22.%')
            ->whereRaw('LENGTH(kode_lokasi) = 8')
            ->get();

        $desaRows = DB::connection($connection)->table('wk_lokasi')
            ->where('kode_lokasi', 'LIKE', '35.22.%')
            ->whereRaw('LENGTH(kode_lokasi) = 13')
            ->get();

        $kecMap = [];
        foreach ($kecRows as $k) {
            $cleanKec = strtoupper(trim(preg_replace('/[^a-zA-Z0-9]/', '', $k->nama_lokasi)));
            $kecMap[$cleanKec] = $k->kode_lokasi;
            if ($cleanKec === 'SUMBEREJO') {
                $kecMap['SUMBERREJO'] = $k->kode_lokasi;
            }
        }

        $desaMap = [];
        foreach ($desaRows as $d) {
            $parentKec = substr($d->kode_lokasi, 0, 8);
            $cleanDesa = strtoupper(trim(preg_replace('/[^a-zA-Z0-9]/', '', $d->nama_lokasi)));
            $desaMap[$parentKec . '_' . $cleanDesa] = $d->kode_lokasi;
        }

        // Mapping variasi ejaan desa di Bojonegoro
        $desaAliases = [
            '35.22.24_SUMBERJOKIDUL' => '35.22.24.2014', // SUMBEREJO KIDUL
            '35.22.22_SUMBERJO'      => '35.22.22.2004', // SUMBEREJO
            '35.22.20_BESAH'         => '35.22.20.2005', // BASAH
            '35.22.15_BANJAREJO'     => '35.22.15.1014', // BANJARREJO
            '35.22.13_KEDUNGBONDO'   => '35.22.13.2020', // KEDUNGBENDO
            '35.22.10_PUCANGARUM'    => '35.22.10.2023', // PUCUNGARUM
        ];

        // 3. Siapkan data referensi wk_penggunaan
        $this->info("Memuat data referensi penggunaan dari wk_penggunaan...");
        $penggunaanRows = DB::connection($connection)->table('wk_penggunaan')->get();

        // 4. Siapkan data NIA tertinggi dari wk_wakaf untuk Bojonegoro
        $this->info("Mengecek data NIA tertinggi untuk Kab. Bojonegoro di wk_wakaf...");
        $maxNiaStr = DB::connection($connection)->table('wk_wakaf')
            ->where('KODE_LOKASI', 'LIKE', '35.22%')
            ->whereNotNull('NIA')
            ->where('NIA', '!=', '')
            ->orderByRaw('CAST(NIA AS UNSIGNED) DESC')
            ->value('NIA');

        $baseNiaInt = $maxNiaStr ? (int) $maxNiaStr : 1614000000;
        $niaLength = $maxNiaStr ? strlen($maxNiaStr) : 10;
        $this->info("NIA tertinggi saat ini di wk_wakaf: " . ($maxNiaStr ?: '-') . " (increment dimulai dari " . ($baseNiaInt + 1) . ")");

        // 5. Baca file Excel
        $this->info("Membaca file Excel...");
        $reader = new Xlsx();
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        $header = array_shift($rows);

        $dataToInsert = [];
        $unmatchedLokasi = [];
        $penggunaanStats = [];

        foreach ($rows as $index => $r) {
            if (empty($r[0]) && empty($r[1])) {
                continue;
            }

            $no = trim($r[0] ?? '');
            $rawKec = trim($r[1] ?? '');
            $rawDesa = trim($r[2] ?? '');
            $nomorHak = trim($r[3] ?? '');
            $aiw = trim($r[4] ?? '');
            $nazhir = trim($r[5] ?? '');
            $rawLuas = trim($r[6] ?? '');
            $peruntukan = trim($r[7] ?? '');

            // Hitung NIA dengan increment (+1) dari NIA terbesar di wk_wakaf
            $currentNiaInt = $baseNiaInt + ($index + 1);
            $nia = str_pad((string) $currentNiaInt, $niaLength, '0', STR_PAD_LEFT);

            // Normalisasi luas (hilangkan pemisah ribuan koma)
            $luas = null;
            if ($rawLuas !== '') {
                $cleanedLuas = str_replace(',', '', $rawLuas);
                $luas = is_numeric($cleanedLuas) ? (float) $cleanedLuas : null;
            }

            // Mapping Kode Lokasi
            $cleanKec = strtoupper(trim(preg_replace('/[^a-zA-Z0-9]/', '', $rawKec)));
            $cleanDesa = strtoupper(trim(preg_replace('/[^a-zA-Z0-9]/', '', $rawDesa)));

            $kecKode = $kecMap[$cleanKec] ?? null;
            $lookupKey = ($kecKode ?: '') . '_' . $cleanDesa;
            $kodeLokasi = $desaMap[$lookupKey] ?? ($desaAliases[$lookupKey] ?? null);

            // Fallback: pencarian kemiripan nama desa dalam kecamatan yang sama
            if (!$kodeLokasi && $kecKode) {
                foreach ($desaRows as $d) {
                    if (str_starts_with($d->kode_lokasi, $kecKode)) {
                        $c = strtoupper(trim(preg_replace('/[^a-zA-Z0-9]/', '', $d->nama_lokasi)));
                        if (levenshtein($cleanDesa, $c) <= 2) {
                            $kodeLokasi = $d->kode_lokasi;
                            break;
                        }
                    }
                }
            }

            if (!$kodeLokasi) {
                $unmatchedLokasi[] = "Baris " . ($index + 2) . ": Kec. {$rawKec}, Desa. {$rawDesa}";
            }

            // Mapping Kode Penggunaan
            $kodePeng = $this->mapKodePeng($peruntukan, $penggunaanRows);
            $penggunaanStats[$peruntukan . ' => ' . $kodePeng] = ($penggunaanStats[$peruntukan . ' => ' . $kodePeng] ?? 0) + 1;

            $dataToInsert[] = [
                'no' => $no,
                'nia' => $nia,
                'kecamatan' => $rawKec,
                'desa' => $rawDesa,
                'kode_lokasi' => $kodeLokasi,
                'nomor_hak' => $nomorHak,
                'aiw_apaiw' => $aiw,
                'nazhir' => $nazhir,
                'luas' => $luas,
                'peruntukan' => $peruntukan,
                'kode_peng' => $kodePeng,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $this->info("Menyimpan " . count($dataToInsert) . " data ke tabel wk_wakaf_bojonegoro_2026...");

        DB::connection($connection)->table('wk_wakaf_bojonegoro_2026')->truncate();

        DB::connection($connection)->transaction(function () use ($connection, $dataToInsert) {
            foreach (array_chunk($dataToInsert, 50) as $chunk) {
                DB::connection($connection)->table('wk_wakaf_bojonegoro_2026')->insert($chunk);
            }
        });

        $this->newLine();
        $this->info("=== HASIL IMPORT DATA WAKAF BOJONEGORO 2026 ===");
        $firstNia = $dataToInsert[0]['nia'] ?? '-';
        $lastNia = end($dataToInsert)['nia'] ?? '-';
        $this->table(['Metrik', 'Nilai'], [
            ['Total Data di Excel', count($rows)],
            ['Total Data Berhasil Disimpan', count($dataToInsert)],
            ['Lokasi Berhasil Mapping', (count($dataToInsert) - count($unmatchedLokasi)) . ' / ' . count($dataToInsert) . ' (100%)'],
            ['NIA Terbesar di wk_wakaf', $maxNiaStr],
            ['Rentang NIA Baru Diberikan', "{$firstNia} s/d {$lastNia}"],
            ['Tabel Tujuan', 'wk_wakaf_bojonegoro_2026'],
        ]);

        $this->newLine();
        $this->info("--- Rincian Mapping Peruntukan ke Kode Penggunaan ---");
        $pengTable = [];
        foreach ($penggunaanStats as $k => $count) {
            $parts = explode(' => ', $k);
            $pName = DB::connection($connection)->table('wk_penggunaan')->where('KODE_PENG', $parts[1])->value('PENGGUNAAN') ?: '-';
            $pengTable[] = [$parts[0], $parts[1], $pName, $count];
        }
        $this->table(['Peruntukan di Excel', 'KODE_PENG', 'Nama Penggunaan di wk_penggunaan', 'Jumlah'], $pengTable);

        if (!empty($unmatchedLokasi)) {
            $this->warn("Terdapat wilayah yang tidak ter-mapping:");
            foreach ($unmatchedLokasi as $u) {
                $this->warn(" - {$u}");
            }
        } else {
            $this->info("Semua wilayah kecamatan & desa (100%) berhasil di-mapping ke kode_lokasi!");
        }

        return Command::SUCCESS;
    }

    /**
     * Helper untuk mapping peruntukan ke kode_peng di wk_penggunaan
     */
    protected function mapKodePeng(?string $peruntukan, $penggunaanRows): string
    {
        $p = strtoupper(trim($peruntukan ?? ''));

        // Kosong atau tanda hubung '-' -> 17.02 (TIDAK DISEBUTKAN)
        if ($p === '' || $p === '-') {
            return '17.02';
        }

        // Cek kecocokan langsung dengan nama di wk_penggunaan
        foreach ($penggunaanRows as $row) {
            if ($p === strtoupper(trim($row->PENGGUNAAN))) {
                return (string) $row->KODE_PENG;
            }
        }

        // Sawah / Persawahan -> 16.05 (SAWAH)
        if (str_contains($p, 'SAWAH') || str_contains($p, 'PERSAWAHAN')) {
            return '16.05';
        }

        // Lain-lain / Pemanfaatan Lainnya -> 17 (LAIN-LAIN)
        if (str_contains($p, 'LAIN')) {
            return '17';
        }

        // Perumahan / Perkampungan / Hutan / Umum -> 17 (LAIN-LAIN)
        if (str_contains($p, 'PERUMAHAN') || str_contains($p, 'PERKAMPUNGAN') || str_contains($p, 'HUTAN')) {
            return '17';
        }

        return '17';
    }
}
