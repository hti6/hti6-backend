<?php

namespace Database\Seeders\Important;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::factory([
            'name' => 'TestAdmin',
            'login' => 'admin',
            'password' => Hash::make('admin')
        ])->create();
    }
}
