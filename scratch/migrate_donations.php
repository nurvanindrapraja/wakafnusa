<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

function migrateDonations() {
    $sqlitePath = '/Users/bapenda/antigravity/siwaknu/database/database.sqlite';
    Config::set('database.connections.sqlite_migrator', [
        'driver' => 'sqlite',
        'database' => $sqlitePath,
        'prefix' => '',
    ]);
    
    $table = 'donations';
    echo "Migrating $table...\n";

    $data = DB::connection('sqlite_migrator')->table($table)->get();
    echo "Source count: " . count($data) . "\n";

    DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');
    DB::connection('mysql')->table($table)->truncate();

    foreach ($data as $row) {
        try {
            DB::connection('mysql')->table($table)->insert((array)$row);
        } catch (\Exception $e) {
            echo "ERROR in row " . $row->id . ": " . $e->getMessage() . "\n";
        }
    }

    $countAfter = DB::connection('mysql')->table($table)->count();
    echo "Count in MySQL after migration: $countAfter\n";

    DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');
}

migrateDonations();
