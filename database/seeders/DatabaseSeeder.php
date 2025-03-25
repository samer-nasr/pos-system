<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'samer',
            'email' => 'samer@live.com',
        ]);

        Brand::factory()->create([
            'name' => 'Master',
        ]);
        Brand::factory()->create([
            'name' => 'Almaza',
        ]);
        Brand::factory()->create([
            'name' => 'Wooden',
        ]);

        Category::factory()->create([
            'name' => 'Drinks',
        ]);

        Category::factory()->create([
            'name' => 'Chips',
        ]);
    }
}
