<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // php artisan db:seed --class=ClearTablesSeeder
        $this->call(UserSeeder::class);
        // $this->call(AkunKasTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        // $this->call(CategoriesTableSeeder::class);
        // $this->call(SubcategoriesTableSeeder::class);
        // $this->call(AkunKasTableSeeder::class);
    }
}
