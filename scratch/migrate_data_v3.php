<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

function migrateData() {
    // FORCE SQLITE PATH
    $sqlitePath = '/Users/bapenda/antigravity/siwaknu/database/database.sqlite';
    Config::set('database.connections.sqlite_migrator', [
        'driver' => 'sqlite',
        'database' => $sqlitePath,
        'prefix' => '',
    ]);
    
    $tables = [
        'roles',
        'permissions',
        'role_has_permissions',
        'users',
        'model_has_roles',
        'model_has_permissions',
        'programs',
        'donations',
        'reviews',
        'reports',
        'certificates',
        'pks',
        'disbursements',
        'activity_logs',
        'system_settings',
        'templates',
        'infaq_withdrawals',
        'nazhir_applications',
        'team_members',
    ];

    echo "Starting data migration from SQLite to MySQL...\n";
    echo "SQLite source: $sqlitePath\n";

    DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');

    foreach ($tables as $table) {
        try {
            $count = DB::connection('sqlite_migrator')->table($table)->count();
            echo "Table: $table ($count rows) ... ";
            
            if ($count === 0) {
                echo "Skipping.\n";
                continue;
            }

            $data = DB::connection('sqlite_migrator')->table($table)->get();
            
            DB::connection('mysql')->table($table)->truncate();

            $chunks = array_chunk($data->map(fn($item) => (array)$item)->toArray(), 50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table($table)->insert($chunk);
            }

            echo "DONE.\n";
        } catch (\Exception $e) {
            echo "ERROR: " . $e->getMessage() . "\n";
        }
    }

    DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');
    echo "Migration completed!\n";
}

migrateData();
