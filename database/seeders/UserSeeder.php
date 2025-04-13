<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Membuat 10 user menggunakan factory
        // User::factory()->count(2)->create();
        DB::table('users')->insert([
            // password: admin -> md5
            ['name' => 'Admin', 'password' => '21232f297a57a5a743894a0e4a801fc3', 'username' => 'Administrator', 'role' => 'Manager', 'email_verified_at' => now(), 'email' => 'adminuser@gmail.com'],
            ['name' => 'Jerry', 'password' => '21232f297a57a5a743894a0e4a801fc3', 'username' => 'Jerry', 'role' => 'Admin', 'email_verified_at' => now(), 'email' => 'jerryuser@gmail.com'],
            ['name' => 'Test', 'password' => '21232f297a57a5a743894a0e4a801fc3', 'username' => 'Testing', 'role' => 'Admin', 'email_verified_at' => now(), 'email' => 'testing123@gmail.com'],
        ]);
    }
}

