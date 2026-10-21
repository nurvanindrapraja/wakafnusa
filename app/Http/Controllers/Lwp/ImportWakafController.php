<?php

namespace App\Http\Controllers\Lwp;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Usage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImportWakafController extends Controller
{
    /**
     * Check if current user is Admin APP (9) or Admin PBNU (91)
     */
    private function checkPermission()
    {
        $user = Auth::guard('lwp')->user();
        if (!$user || !in_array((int)$user->LEVEL, [9, 91])) {
            abort(403, 'Akses Terbatas: Hanya Admin PBNU atau Admin APP yang dapat mengimpor data wakaf.');
        }
    }

    /**
     * Display Import Page (Upload View or Preview View)
     */
    public function index(Request $request)
    {
        $this->checkPermission();

        $sessionData = session('import_wakaf_session');
        $hasData = !empty($sessionData && !empty($sessionData['rows']));

        return view('lwp.import.index', [
            'sessionData' => $sessionData,
            'hasData' => $hasData,
        ]);
    }

    /**
     * Download Excel Template according to specified columns
     */
    public function downloadTemplate()
    {
        $this->checkPermission();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Import Wakaf');

        // Header Columns as requested:
        // kel/desa (kelurahan), kec (kecamatan), kota/kab, provinsi, penggunaan, nama, alamat, wakif, nazhir, luas, no aiw, tgl aiw, no sert/nib, tgl sertipikat, keterangan, njop
        $headers = [
            'kel/desa (kelurahan)',
            'kec (kecamatan)',
            'kota/kab',
            'provinsi',
            'penggunaan',
            'nama',
            'alamat',
            'wakif',
            'nazhir',
            'luas',
            'no aiw',
            'tgl aiw',
            'no sert/nib',
            'tgl sertipikat',
            'keterangan',
            'njop',
        ];

        // Set Headers at row 1
        $sheet->fromArray([$headers], null, 'A1');

        // Sample Data Rows (Row 2 & 3)
        $sampleRows = [
            [
                'KADIPATEN',
                'BOJONEGORO',
                'KAB. BOJONEGORO',
                'JAWA TIMUR',
                'MASJID',
                'Masjid Jami Kadipaten',
                'Jl. Pahlawan No. 10',
                'H. Ahmad Subarjo',
                'Pengurus Ranting NU Kadipaten',
                1250,
                'W.2/001/AIW/2020',
                '2020-03-15',
                '00123/2020',
                '2020-08-20',
                'Wakaf sarana ibadah masjid dan fasilitas sosial',
                150000000,
            ],
            [
                'SUKOREJO',
                'BOJONEGORO',
                'KAB. BOJONEGORO',
                'JAWA TIMUR',
                'MUSHOLLA',
                'Musholla Al-Ikhlas',
                'Jl. Diponegoro No. 4',
                'Hj. Fatimah Zahra',
                'MWCNU Bojonegoro',
                450,
                'W.2/005/AIW/2021',
                '2021-06-10',
                '00456/2021',
                '2021-11-05',
                'Wakaf tempat ibadah musholla',
                50000000,
            ]
        ];

        $sheet->fromArray($sampleRows, null, 'A2');

        // Styling Header (Green NU #006400, White Bold Text)
        $headerRange = 'A1:P1';
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->setColor(new Color('FFFFFF'));
        $sheet->getStyle($headerRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('006400');
        $sheet->getStyle($headerRange)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // Auto size columns
        foreach (range('A', 'P') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Response stream
        $filename = 'template_import_wakaf_' . date('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Upload and Parse Excel File
     */
    public function upload(Request $request)
    {
        $this->checkPermission();

        $request->validate([
            'file' => 'required|file|max:20480',
        ], [
            'file.required' => 'Berkas Excel wajib diunggah.',
            'file.max' => 'Ukuran berkas tidak boleh melebihi 20MB.',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, ['xlsx', 'xls', 'csv', 'txt'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Format berkas tidak didukung. Harap unggah berkas berekstensi .xlsx, .xls, atau .csv.'
            ], 422);
        }

        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rawRows = $sheet->toArray();
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membaca berkas Excel: ' . $e->getMessage()
            ], 422);
        }

        if (empty($rawRows) || count($rawRows) < 2) {
            return response()->json([
                'status' => 'error',
                'message' => 'Berkas Excel kosong atau tidak memiliki data baris setelah header.'
            ], 422);
        }

        $headerRow = array_shift($rawRows);
        $colIndexMap = $this->mapHeaderColumns($headerRow);

        $parsedRows = [];
        $rowNum = 1;
        $kabCountMap = [];

        foreach ($rawRows as $r) {
            // Check if entire row is empty
            $hasContent = false;
            foreach ($r as $val) {
                if ($val !== null && trim((string)$val) !== '') {
                    $hasContent = true;
                    break;
                }
            }
            if (!$hasContent) continue;

            $desaRaw = $this->extractCol($r, $colIndexMap, 'desa');
            $kecRaw = $this->extractCol($r, $colIndexMap, 'kecamatan');
            $kabRaw = $this->extractCol($r, $colIndexMap, 'kabupaten');
            $provRaw = $this->extractCol($r, $colIndexMap, 'provinsi');
            $penggunaanRaw = $this->extractCol($r, $colIndexMap, 'penggunaan');
            $namaRaw = $this->extractCol($r, $colIndexMap, 'nama');
            $alamatRaw = $this->extractCol($r, $colIndexMap, 'alamat');
            $wakifRaw = $this->extractCol($r, $colIndexMap, 'wakif');
            $nazhirRaw = $this->extractCol($r, $colIndexMap, 'nazhir');
            $luasRaw = $this->extractCol($r, $colIndexMap, 'luas');
            $noAiwRaw = $this->extractCol($r, $colIndexMap, 'no_aiw');
            $tglAiwRaw = $this->extractCol($r, $colIndexMap, 'tgl_aiw');
            $noSertRaw = $this->extractCol($r, $colIndexMap, 'no_sert');
            $tglSertRaw = $this->extractCol($r, $colIndexMap, 'tgl_sert');
            $ketRaw = $this->extractCol($r, $colIndexMap, 'keterangan');
            $njopRaw = $this->extractCol($r, $colIndexMap, 'njop');

            if ($kabRaw) {
                $cleanKabKey = strtoupper(trim(preg_replace('/^(KAB\.|KABUPATEN|KOTA)\s+/i', '', $kabRaw)));
                $kabCountMap[$cleanKabKey] = ($kabCountMap[$cleanKabKey] ?? 0) + 1;
            }

            $parsedRows[] = [
                'row_id' => $rowNum++,
                'desa' => $desaRaw,
                'kecamatan' => $kecRaw,
                'kabupaten' => $kabRaw,
                'provinsi' => $provRaw,
                'penggunaan' => $penggunaanRaw,
                'nama' => $namaRaw,
                'alamat' => $alamatRaw,
                'wakif' => $wakifRaw,
                'nazhir' => $nazhirRaw,
                'luas' => $this->parseNumber($luasRaw),
                'no_aiw' => $noAiwRaw,
                'tgl_aiw' => $this->parseDate($tglAiwRaw),
                'no_sert' => $noSertRaw,
                'tgl_sert' => $this->parseDate($tglSertRaw),
                'keterangan' => $ketRaw,
                'njop' => $this->parseNumber($njopRaw),
                // Generated columns initially null
                'nia' => null,
                'kode_lokasi' => null,
                'nama_lokasi' => null,
                'kode_peng' => null,
                'nama_peng' => null,
                'status' => 'pending', // pending, ready, incomplete
                'errors' => [],
            ];
        }

        if (empty($parsedRows)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ditemukan data yang valid pada berkas Excel.'
            ], 422);
        }

        // Determine dominant kab
        arsort($kabCountMap);
        $dominantKabName = !empty($kabCountMap) ? array_key_first($kabCountMap) : null;

        // Calculate suggested starting NIA from wk_wakaf
        $suggestedNia = $this->determineSuggestedNia($dominantKabName);

        // Store into session
        session()->put('import_wakaf_session', [
            'filename' => $file->getClientOriginalName(),
            'total_rows' => count($parsedRows),
            'is_generated' => false,
            'suggested_nia' => $suggestedNia,
            'dominant_kab' => $dominantKabName,
            'rows' => $parsedRows,
            'stats' => [
                'total' => count($parsedRows),
                'ready' => 0,
                'incomplete' => 0,
            ]
        ]);

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Berkas berhasil diunggah dan dianalisis.',
                'redirect' => route('lwp.import-wakaf.index'),
            ]);
        }

        return redirect()->route('lwp.import-wakaf.index');
    }

    /**
     * Generate NIA, Kode Lokasi, and Kode Penggunaan
     */
    public function generate(Request $request)
    {
        $this->checkPermission();

        $sessionData = session('import_wakaf_session');
        if (empty($sessionData) || empty($sessionData['rows'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sesi berkas tidak ditemukan. Silakan unggah berkas kembali.'
            ], 422);
        }

        $rows = $sessionData['rows'];
        $suggestedNia = $sessionData['suggested_nia'] ?? '1614000001';
        $userNiaInput = trim($request->input('starting_nia', ''));
        $startingNiaInt = $userNiaInput !== '' && is_numeric($userNiaInput) ? (int)$userNiaInput : (int)$suggestedNia;

        // Preload active usages
        $usages = Usage::active()->get();

        // Memory cache for location hierarchy lookups
        $locationCache = [
            'provinces' => Location::provinsi()->get(),
            'kabupatens' => [],
            'kecamatans' => [],
            'desas' => [],
        ];

        $currentNia = $startingNiaInt;
        $readyCount = 0;
        $incompleteCount = 0;
        $firstNia = null;
        $lastNia = null;

        foreach ($rows as &$r) {
            $errors = [];

            // 1. Assign NIA
            $rowNia = (string)$currentNia;
            if ($firstNia === null) $firstNia = $rowNia;
            $lastNia = $rowNia;
            $r['nia'] = $rowNia;
            $currentNia++;

            // 2. Map Kode Lokasi
            $locResult = $this->resolveLocation($r['desa'], $r['kecamatan'], $r['kabupaten'], $r['provinsi'], $locationCache);
            if ($locResult) {
                $r['kode_lokasi'] = $locResult['kode_lokasi'];
                $r['nama_lokasi'] = $locResult['nama_lokasi'];
            } else {
                $r['kode_lokasi'] = null;
                $r['nama_lokasi'] = null;
                $errors[] = 'Kode lokasi tidak ditemukan di tabel wk_lokasi (periksa ejaan desa/kecamatan).';
            }

            // 3. Map Kode Penggunaan
            $pengResult = $this->resolvePenggunaan($r['penggunaan'], $usages);
            if ($pengResult) {
                $r['kode_peng'] = $pengResult['kode_peng'];
                $r['nama_peng'] = $pengResult['nama_peng'];
            } else {
                $r['kode_peng'] = null;
                $r['nama_peng'] = null;
                $errors[] = 'Penggunaan tidak cocok dengan tabel wk_penggunaan.';
            }

            // 4. Validate completeness
            if (!empty($r['nia']) && !empty($r['kode_lokasi']) && !empty($r['kode_peng'])) {
                $r['status'] = 'ready';
                $r['errors'] = [];
                $readyCount++;
            } else {
                $r['status'] = 'incomplete';
                $r['errors'] = $errors;
                $incompleteCount++;
            }
        }
        unset($r);

        // Update session
        $stats = [
            'total' => count($rows),
            'ready' => $readyCount,
            'incomplete' => $incompleteCount,
            'start_nia' => $firstNia,
            'end_nia' => $lastNia,
        ];

        session()->put('import_wakaf_session', array_merge($sessionData, [
            'is_generated' => true,
            'starting_nia' => (string)$startingNiaInt,
            'rows' => $rows,
            'stats' => $stats,
        ]));

        return response()->json([
            'status' => 'success',
            'message' => 'Generate NIA, Kode Lokasi, dan Kode Penggunaan berhasil diselesaikan.',
            'stats' => $stats,
            'rows' => $rows,
        ]);
    }

    /**
     * Process Import into wk_wakaf and wk_hisentri
     */
    public function process(Request $request)
    {
        $this->checkPermission();

        $sessionData = session('import_wakaf_session');
        if (empty($sessionData) || empty($sessionData['rows'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada data import yang aktif. Silakan unggah berkas kembali.'
            ], 422);
        }

        $rows = $sessionData['rows'];

        // Filter only ready rows (NIA, kode_lokasi, and kode_peng must be present)
        $readyRows = array_filter($rows, function ($r) {
            return !empty($r['nia']) && !empty($r['kode_lokasi']) && !empty($r['kode_peng']);
        });

        if (empty($readyRows)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada data yang memenuhi syarat untuk diimport (NIA, kode lokasi, dan kode penggunaan harus terisi lengkap).'
            ], 422);
        }

        $connection = 'mysql_no_prefix';
        $now = now()->format('Y-m-d H:i:s');
        $userId = Auth::guard('lwp')->id() ?: 4;

        // Preload wk_cabang to resolve KODE_INDEX
        $cabangMap = DB::connection($connection)->table('wk_cabang')
            ->pluck('KODE_INDEX', 'KODE_CABANG')
            ->toArray();

        $insertedCount = 0;
        $insertedIds = [];
        $firstNia = null;
        $lastNia = null;

        DB::connection($connection)->transaction(function () use ($connection, $readyRows, $cabangMap, $userId, $now, &$insertedCount, &$insertedIds, &$firstNia, &$lastNia) {
            foreach ($readyRows as $r) {
                $nia = trim((string)$r['nia']);
                if ($firstNia === null) $firstNia = $nia;
                $lastNia = $nia;

                $kodeLokasi = $r['kode_lokasi'];
                $kabCode = substr($kodeLokasi, 0, 5);
                $kodeIndex = $cabangMap[$kabCode] ?? '16.14';
                $token = sha1($nia);

                $alamat = trim($r['alamat'] ?? '');
                if (!$alamat) {
                    $alamat = 'Desa ' . ($r['desa'] ?: '-') . ', Kec. ' . ($r['kecamatan'] ?: '-');
                }

                $wakafData = [
                    'NIA' => $nia,
                    'KODE_LOKASI' => $kodeLokasi,
                    'KODE_PENG' => $r['kode_peng'],
                    'NAMA' => $r['nama'] ?: '',
                    'ALAMAT' => $alamat,
                    'WAKIF' => $r['wakif'] ?: '-',
                    'NADZIR' => $r['nazhir'] ?: '-',
                    'LUAS' => (float)($r['luas'] ?? 0),
                    'NO_AIW' => $r['no_aiw'] ?: '',
                    'TGL_AIW' => $r['tgl_aiw'] ?: null,
                    'NO_SERT' => $r['no_sert'] ?: '',
                    'TGL_SERT' => $r['tgl_sert'] ?: null,
                    'KETERANGAN' => $r['keterangan'] ?: '',
                    'KODE_INDEX' => $kodeIndex,
                    'ID_USER' => $userId,
                    'TOKEN' => $token,
                    'NJOP' => (int)($r['njop'] ?? 0),
                    'THN_NJOP' => 0,
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
                    'LAT' => '',
                    'LNG' => '',
                ];

                // Insert into wk_wakaf
                $idWakaf = DB::connection($connection)->table('wk_wakaf')->insertGetId($wakafData);

                // Insert into wk_hisentri
                DB::connection($connection)->table('wk_hisentri')->insert([
                    'id_wakaf' => $idWakaf,
                    'id_user' => $userId,
                    'tgl_entri' => $now,
                    'fase' => 1,
                ]);

                $insertedIds[] = $idWakaf;
                $insertedCount++;
            }
        });

        // Clear session after successful import
        session()->forget('import_wakaf_session');

        $totalUploaded = count($rows);
        $skippedCount = $totalUploaded - $insertedCount;

        return response()->json([
            'status' => 'success',
            'message' => "Proses import berhasil! {$insertedCount} data aset wakaf telah dimasukkan ke dalam tabel wk_wakaf.",
            'inserted_count' => $insertedCount,
            'imported_count' => $insertedCount,
            'skipped_count' => $skippedCount,
            'first_nia' => $firstNia,
            'last_nia' => $lastNia,
            'redirect' => route('lwp.manajemen'),
        ]);
    }

    /**
     * Cancel and Clear Import Session
     */
    public function cancel(Request $request)
    {
        $this->checkPermission();

        session()->forget('import_wakaf_session');

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Proses import telah dibatalkan.',
                'redirect' => route('lwp.import-wakaf.index'),
            ]);
        }

        return redirect()->route('lwp.import-wakaf.index')->with('success', 'Proses import dibatalkan.');
    }

    /**
     * Map Excel Header Columns flexibly
     */
    private function mapHeaderColumns(array $headers): array
    {
        $colIndexMap = [];
        $aliasMap = [
            'desa' => [
                'kel/desa (kelurahan)', 'kel/desa(kelurahan)', 'kel/desa',
                'kelurahan', 'desa', 'desa/kelurahan', 'desa / kelurahan',
                'kel', 'desakel', 'key/desa', 'kec/desa'
            ],
            'kecamatan' => [
                'kec (kecamatan)', 'kec(kecamatan)', 'kec',
                'kecamatan', 'key'
            ],
            'kabupaten' => ['kota/kab', 'kab/kota', 'kabupaten', 'kota', 'kabupaten/kota', 'kab', 'kabupaten / kota'],
            'provinsi' => ['provinsi', 'prov'],
            'penggunaan' => ['penggunaan', 'peruntukan'],
            'nama' => ['nama', 'nama aset', 'nama objek'],
            'alamat' => ['alamat'],
            'wakif' => ['wakif', 'waif'],
            'nazhir' => ['nazhir', 'nadzir'],
            'luas' => ['luas', 'luas m2', 'luas (m2)'],
            'no_aiw' => ['no aiw', 'no_aiw', 'aiw', 'nomor aiw', 'aiw/apaiw'],
            'tgl_aiw' => ['tgl aiw', 'tgl_aiw', 'tanggal aiw'],
            'no_sert' => ['no sert/nib', 'no sert', 'no_sert', 'sertipikat', 'no sertifikat', 'nomor hak', 'nib'],
            'tgl_sert' => ['tgl sertipikat', 'tgl sertifikat', 'tgl_sert', 'tanggal sertipikat'],
            'keterangan' => ['keterangan', 'ket'],
            'njop' => ['njop', 'nilai njop'],
        ];

        foreach ($headers as $index => $rawH) {
            if ($rawH === null) continue;
            $cleanH = strtolower(trim((string)$rawH));
            // Also strip parentheses notes e.g. "kel/desa (kelurahan)" -> "kel/desa"
            $cleanNoParen = trim(preg_replace('/\s+/', ' ', preg_replace('/\(.*?\)/', '', $cleanH)));

            // Match against aliases
            foreach ($aliasMap as $key => $aliases) {
                if ((in_array($cleanH, $aliases) || in_array($cleanNoParen, $aliases)) && !isset($colIndexMap[$key])) {
                    $colIndexMap[$key] = $index;
                    break;
                }
            }
        }

        return $colIndexMap;
    }

    /**
     * Extract column by mapped key
     */
    private function extractCol(array $row, array $map, string $key): ?string
    {
        if (isset($map[$key]) && isset($row[$map[$key]])) {
            $val = trim((string)$row[$map[$key]]);
            return $val !== '' ? $val : null;
        }
        return null;
    }

    /**
     * Determine suggested starting NIA
     */
    private function determineSuggestedNia(?string $dominantKabName): string
    {
        $connection = 'mysql_no_prefix';
        $driver = DB::connection($connection)->getDriverName();

        // 1. Try finding max NIA in the dominant kabupaten if available
        if ($dominantKabName) {
            $clean = strtoupper(trim(preg_replace('/[^A-Z0-9]/', '', $dominantKabName)));
            $kabupatens = Location::whereRaw('LENGTH(kode_lokasi) = 5')->get();
            $kab = $kabupatens->first(function ($k) use ($clean) {
                $kClean = strtoupper(trim(preg_replace('/[^A-Z0-9]/', '', str_replace(['KAB.', 'KABUPATEN', 'KOTA'], '', $k->nama_lokasi))));
                return $kClean === $clean || str_contains($kClean, $clean) || str_contains($clean, $kClean);
            });

            if ($kab) {
                $query = DB::connection($connection)->table('wk_wakaf')
                    ->where('KODE_LOKASI', 'like', $kab->kode_lokasi . '%')
                    ->whereNotNull('NIA')
                    ->where('NIA', '!=', '');

                if ($driver === 'mysql') {
                    $query->whereRaw("NIA REGEXP '^[0-9]+$'");
                }

                $kabMax = $query->orderByRaw('CAST(NIA AS UNSIGNED) DESC')->value('NIA');

                if ($kabMax) {
                    return (string)((int)$kabMax + 1);
                }

                // If no wakaf NIA yet, try finding KODE_INDEX from wk_cabang
                $cabang = DB::connection($connection)->table('wk_cabang')
                    ->where('KODE_CABANG', $kab->kode_lokasi)
                    ->first();

                if ($cabang && $cabang->KODE_INDEX) {
                    $prefix = str_replace('.', '', $cabang->KODE_INDEX);
                    return $prefix . '000001';
                }
            }
        }

        // 2. Fallback to overall max NIA in wk_wakaf
        $queryOverall = DB::connection($connection)->table('wk_wakaf')
            ->whereNotNull('NIA')
            ->where('NIA', '!=', '');

        if ($driver === 'mysql') {
            $queryOverall->whereRaw("NIA REGEXP '^[0-9]+$'");
        }

        $overallMax = $queryOverall->orderByRaw('CAST(NIA AS UNSIGNED) DESC')->value('NIA');

        if ($overallMax) {
            return (string)((int)$overallMax + 1);
        }

        return '1614000001';
    }

    /**
     * Resolve Location hierarchically (Prov -> Kab -> Kec -> Desa)
     */
    private function resolveLocation(?string $desa, ?string $kec, ?string $kab, ?string $prov, array &$cache): ?array
    {
        $clean = function ($s) {
            if (!$s) return '';
            $s = strtoupper(trim((string)$s));
            $s = preg_replace('/^(KAB\.|KABUPATEN|KOTA|KEC\.|KECAMATAN|DESA|KEL\.|KELURAHAN)\s+/i', '', $s);
            return preg_replace('/[^A-Z0-9]/', '', $s);
        };

        $cDesa = $clean($desa);
        $cKec = $clean($kec);
        $cKab = $clean($kab);
        $cProv = $clean($prov);

        if (!$cDesa && !$cKec) return null;

        // 1. Resolve Provinsi
        $provCode = null;
        if ($cProv) {
            $pObj = $cache['provinces']->first(fn($p) => $clean($p->nama_lokasi) === $cProv);
            if ($pObj) $provCode = $pObj->kode_lokasi;
        }
        if (!$provCode) {
            // Default to 35 (Jawa Timur) if not specified or unmapped
            $provCode = '35';
        }

        // 2. Resolve Kabupaten
        $kabCode = null;
        if ($cKab) {
            if (!isset($cache['kabupatens'][$provCode])) {
                $cache['kabupatens'][$provCode] = Location::kabupaten($provCode)->get();
            }
            $kObj = $cache['kabupatens'][$provCode]->first(fn($k) => $clean($k->nama_lokasi) === $cKab);
            if ($kObj) $kabCode = $kObj->kode_lokasi;
        }

        // 3. Resolve Kecamatan
        $kecCode = null;
        if ($cKec) {
            if ($kabCode) {
                if (!isset($cache['kecamatans'][$kabCode])) {
                    $cache['kecamatans'][$kabCode] = Location::kecamatan($kabCode)->get();
                }
                $kcObj = $cache['kecamatans'][$kabCode]->first(fn($kc) => $clean($kc->nama_lokasi) === $cKec);
                if ($kcObj) $kecCode = $kcObj->kode_lokasi;
            } else {
                // Search across provinsi if kabCode is unknown
                $kcObj = Location::where('kode_lokasi', 'like', $provCode . '.%')
                    ->whereRaw('LENGTH(kode_lokasi) = 8')
                    ->get()
                    ->first(fn($kc) => $clean($kc->nama_lokasi) === $cKec);
                if ($kcObj) {
                    $kecCode = $kcObj->kode_lokasi;
                    $kabCode = substr($kecCode, 0, 5);
                }
            }
        }

        // 4. Resolve Desa
        if ($cDesa && $kecCode) {
            if (!isset($cache['desas'][$kecCode])) {
                $cache['desas'][$kecCode] = Location::kelurahan($kecCode)->get();
            }
            $dObj = $cache['desas'][$kecCode]->first(fn($d) => $clean($d->nama_lokasi) === $cDesa);
            
            // Check alias variations (e.g. SUMBEREJO KIDUL vs SUMBERJOKIDUL)
            if (!$dObj) {
                $dObj = $cache['desas'][$kecCode]->first(function($d) use ($cDesa, $clean) {
                    $c = $clean($d->nama_lokasi);
                    return str_contains($c, $cDesa) || str_contains($cDesa, $c);
                });
            }

            if ($dObj) {
                return [
                    'kode_lokasi' => $dObj->kode_lokasi,
                    'nama_lokasi' => $dObj->nama_lokasi,
                ];
            }
        }

        return null;
    }

    /**
     * Resolve Penggunaan to wk_penggunaan
     */
    private function resolvePenggunaan(?string $raw, $usages): ?array
    {
        if (!$raw) return null;
        $clean = strtoupper(trim(preg_replace('/[^a-zA-Z0-9]/', '', $raw)));

        // 1. Direct match with PENGGUNAAN
        foreach ($usages as $u) {
            $uClean = strtoupper(trim(preg_replace('/[^a-zA-Z0-9]/', '', $u->PENGGUNAAN)));
            if ($uClean === $clean) {
                return ['kode_peng' => $u->KODE_PENG, 'nama_peng' => $u->PENGGUNAAN];
            }
        }

        // 2. Common Alias Matching
        $aliases = [
            'MASJID' => '11.01',
            'MUSHOLLA' => '11.02',
            'MUSHOLA' => '11.02',
            'LANGGAR' => '11.03',
            'PESANTREN' => '12.01',
            'PONPES' => '12.01',
            'PONDOKPESANTREN' => '12.01',
            'MI' => '12.02',
            'MADRASAHIBTIDAIYAH' => '12.02',
            'MTS' => '12.03',
            'MADRASAHTSANAWIYAH' => '12.03',
            'MA' => '12.04',
            'MADRASAHALIYAH' => '12.04',
            'PERGURUANTINGGI' => '12.05',
            'TPQ' => '12.06',
            'TK' => '12.07',
            'RA' => '12.07',
            'MADRASAHDINIYAH' => '12.08',
            'MADRASAH' => '12.09',
            'PAUD' => '12.10',
            'SD' => '12.12',
            'SEKOLAHDASAR' => '12.12',
            'SMP' => '12.13',
            'SMA' => '12.14',
            'SMK' => '12.14',
            'RUMAHTSAKIT' => '13.01',
            'KLINIK' => '13.03',
            'PANTIASUHAN' => '14.01',
            'YAYASAN' => '14.02',
            'MAKAM' => '15.01',
            'KUBURAN' => '15.01',
            'KANTOR' => '16.02',
            'KANTORNU' => '17.06',
            'SAWAH' => '16.05',
            'TAMBAK' => '16.03',
            'KEBUN' => '16.04',
            'TANAHKOSONG' => '17.01',
        ];

        if (isset($aliases[$clean])) {
            $code = $aliases[$clean];
            $u = $usages->firstWhere('KODE_PENG', $code);
            if ($u) {
                return ['kode_peng' => $u->KODE_PENG, 'nama_peng' => $u->PENGGUNAAN];
            }
        }

        // 3. Substring match
        foreach ($usages as $u) {
            $uClean = strtoupper(trim(preg_replace('/[^a-zA-Z0-9]/', '', $u->PENGGUNAAN)));
            if (str_contains($clean, $uClean) || str_contains($uClean, $clean)) {
                return ['kode_peng' => $u->KODE_PENG, 'nama_peng' => $u->PENGGUNAAN];
            }
        }

        return null;
    }

    /**
     * Parse date into Y-m-d format
     */
    private function parseDate($value): ?string
    {
        if (empty($value)) return null;

        if (is_numeric($value)) {
            try {
                $dt = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                return $dt->format('Y-m-d');
            } catch (\Throwable $e) {}
        }

        $trimmed = trim((string)$value);
        if ($trimmed === '' || $trimmed === '-' || $trimmed === '0000-00-00') {
            return null;
        }

        $timestamp = strtotime(str_replace('/', '-', $trimmed));
        if ($timestamp && $timestamp > 0) {
            return date('Y-m-d', $timestamp);
        }

        return null;
    }

    /**
     * Parse numeric values (luas, njop)
     */
    private function parseNumber($value): float
    {
        if (empty($value)) return 0;
        if (is_numeric($value)) return (float)$value;

        $clean = preg_replace('/[^0-9.,]/', '', (string)$value);
        $clean = str_replace(',', '.', $clean);
        return (float)$clean;
    }
}
