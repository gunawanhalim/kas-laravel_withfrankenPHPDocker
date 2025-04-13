<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $incomeCategory = DB::table('categories')->where('kategori', 'income')->first();
        $expenseCategory = DB::table('categories')->where('kategori', 'expense')->first();
    
        DB::table('subcategories')->insert([
            ['kategori_id' => $incomeCategory->id,'name' => 'Gaji Bulanan'],
            ['kategori_id' => $incomeCategory->id, 'name' => 'Kuli'],
            ['kategori_id' => $expenseCategory->id, 'name' => 'Ongkos Makan'],
            ['kategori_id' => $expenseCategory->id, 'name' => 'Bayar Listrik'],
            ['kategori_id' => $incomeCategory->id, 'name' => 'Penjualan Propery'],
            ['kategori_id' => $incomeCategory->id, 'name' => 'Sponsor Singapore'],
            ['kategori_id' => $expenseCategory->id, 'name' => 'Gaji Karyawan'],
            ['kategori_id' => $expenseCategory->id, 'name' => 'Air dan Listrik'],
        ]);
    }
}
