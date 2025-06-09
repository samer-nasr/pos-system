<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Items;
use App\Models\rate;
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

        rate::create([
            'currency' => 'USD',
            'counter_currency'=> 'LBP',
            'rate'=> 89500
        ]);

        Items::factory()->create([
            'name' => 'beer 150 ml',
            'category_id' => 1,
            'brand_id' => 1,
            'price'=> 1,
            'rate_id'=> 1,
            'quantity'=> 10,
            'bar_code' => 100
        ]);

         Items::factory()->create([
            'name' => 'chips 50 g',
            'category_id' => 2,
            'brand_id' => 2,
            'price'=> 2,
            'rate_id'=> 1,
            'quantity'=> 5,
            'bar_code' => 101
        ]);
    }
}
