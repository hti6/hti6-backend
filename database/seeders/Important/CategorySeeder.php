<?php

namespace Database\Seeders\Important;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::factory([
            'name' => 'Яма',
        ])->create();
        Category::factory([
            'name' => 'Выбоина',
        ])->create();
        Category::factory([
            'name' => 'Лужа',
        ])->create();
    }
}
