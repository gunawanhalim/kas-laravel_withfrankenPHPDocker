<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClearTablesSeeder extends Seeder
{
    public function run()
    {
        // php artisan db:seed --class=ClearTablesSeeder

        DB::table('akun_kas')->delete();
        DB::table('categories')->delete();
        DB::table('kas_bank')->delete();
        DB::table('users')->delete();
        DB::table('pelanggan')->delete();
        DB::table('penjualan')->delete();
        DB::table('piutang')->delete();
        DB::table('subcategories')->delete();
    }
}
