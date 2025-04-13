<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AkunKasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('akun_kas')->insert([
            ['nama_akun' => 'BRI','tampil'=> 'y'],
            ['nama_akun' => 'BCA', 'tampil' => 'y'],
        ]);
        DB::table('pelanggan')->insert([
            ['nama_pelanggan' => 'John Doe','alamat'=> 'Jl. Bunga Sakura No.11'],
            ['nama_pelanggan' => 'Kathrine','alamat'=> 'Jl. New York No.11'],
            ['nama_pelanggan' => 'Michael','alamat'=> 'Jl. Singapore No.11'],
            ['nama_pelanggan' => 'Alisson','alamat'=> 'Jl. Example No.11'],
        ]);
        DB::table('penjualan')->insert([
            ['nomor_nota' => 'NN1234001', 'tanggal_nota' => now(), 'nama_pelanggan' => 'Gunawan', 'alamat' => 'Jl. Sungai Nil No.33', 'total' => '5000000', 'nama_sales' => 'John Doe', 'nama_user' => 'Administrator', 'tanggal_log' => now()],
            ['nomor_nota' => 'NN1234002', 'tanggal_nota' => now(), 'nama_pelanggan' => 'Gunawan', 'alamat' => 'Jl. Sungai Nil No.34', 'total' => '5000000', 'nama_sales' => 'John Doe', 'nama_user' => 'Administrator', 'tanggal_log' => now()],
            ['nomor_nota' => 'NN1234003', 'tanggal_nota' => now(), 'nama_pelanggan' => 'Gunawan', 'alamat' => 'Jl. Sungai Nil No.35', 'total' => '5000000', 'nama_sales' => 'John Doe', 'nama_user' => 'Administrator', 'tanggal_log' => now()],
            ['nomor_nota' => 'NN1234004', 'tanggal_nota' => now(), 'nama_pelanggan' => 'Gunawan', 'alamat' => 'Jl. Sungai Nil No.36', 'total' => '5000000', 'nama_sales' => 'John Doe', 'nama_user' => 'Administrator', 'tanggal_log' => now()],
        ]);
    }
    
}
