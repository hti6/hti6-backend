<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\Important\AdminSeeder;
use Database\Seeders\Important\CategorySeeder;
use Database\Seeders\Optional\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (config('app.env') == 'production') {
            $this->call([
                AdminSeeder::class,
                UserSeeder::class,
                CategorySeeder::class,
            ]);
        } else {
            $this->call([
                AdminSeeder::class,
                UserSeeder::class,
                CategorySeeder::class,
            ]);
        }
    }
}
