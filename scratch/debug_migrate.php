<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

function migrateData() {
    $sqlitePath = '/Users/bapenda/antigravity/siwaknu/database/database.sqlite';
    Config::set('database.connections.sqlite_migrator', [
        'driver' => 'sqlite',
        'database' => $sqlitePath,
        'prefix' => '',
    ]);
    
    $tables = [
        'users',
        'programs',
        'donations',
        // 'reviews',
        // 'reports',
        // 'certificates',
        // 'pks',
        // 'disbursements',
        // 'activity_logs',
        // 'system_settings',
        // 'templates',
        // 'infaq_withdrawals',
        // 'nazhir_applications',
        // 'team_members',
    ];

    echo "Debugging migration...\n";

    DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');

    foreach ($tables as $table) {
        try {
            $data = DB::connection('sqlite_migrator')->table($table)->get();
            echo "Table: $table, Source count: " . count($data) . "\n";
            
            if ($data->isEmpty()) continue;

            DB::connection('mysql')->table($table)->truncate();
            
            $row = (array)$data->first();
            echo "Sample row from $table: " . json_encode($row) . "\n";

            $success = DB::connection('mysql')->table($table)->insert($row);
            echo "Insert status for one row: " . ($success ? "SUCCESS" : "FAILED") . "\n";

            $countAfter = DB::connection('mysql')->table($table)->count();
            echo "Count in MySQL after one row: $countAfter\n";

        } catch (\Exception $e) {
            echo "ERROR in $table: " . $e->getMessage() . "\n";
        }
    }

    DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');
}

migrateData();
