<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

function migrateData() {
    // Override SQLite connection config to point to the correct file
    Config::set('database.connections.sqlite.database', database_path('database.sqlite'));
    DB::purge('sqlite'); // Purge to apply new config

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

    // Disable foreign key checks on MySQL
    DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');

    foreach ($tables as $table) {
        echo "Migrating table: $table... ";
        
        try {
            // Get data from SQLite
            $data = DB::connection('sqlite')->table($table)->get();
            
            if ($data->isEmpty()) {
                echo "Empty, skipping.\n";
                continue;
            }

            // Clear MySQL table first
            DB::connection('mysql')->table($table)->truncate();

            // Insert in chunks
            $chunks = array_chunk($data->map(fn($item) => (array)$item)->toArray(), 100);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table($table)->insert($chunk);
            }

            echo count($data) . " rows migrated.\n";
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    // Re-enable foreign key checks
    DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');

    echo "Migration completed successfully!\n";
}

migrateData();
