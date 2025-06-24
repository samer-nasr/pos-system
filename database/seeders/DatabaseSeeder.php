<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Currency;
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
            'name' => 'admin',
            'email' => 'admin@live.com',
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => 'samer',
            'email' => 'samer@live.com',
            // 'role' => 'admin'
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

        Category::factory()->create([
            'name' => 'Chocolate',
        ]);

        Category::factory()->create([
            'name' => 'Wafers',
        ]);

        Category::factory()->create([
            'name' => 'Cigarettes',
        ]);

      

        Items::factory()->create([
            'name' => 'beer 150 ml',
            'category_id' => 1,
            'brand_id' => 1,
            'currency_id'=> 1,
            'price'=> 1,
            'rate_id'=> 1,
            'quantity'=> 10,
            'bar_code' => 100
        ]);

         Items::factory()->create([
            'name' => 'buz 150 ml',
            'category_id' => 1,
            'brand_id' => 1,
            'currency_id'=> 1,
            'price'=> 1,
            'rate_id'=> 1,
            'quantity'=> 10,
            'bar_code' => 104
        ]);

         Items::factory()->create([
            'name' => 'xxl 150 ml',
            'category_id' => 1,
            'brand_id' => 1,
            'currency_id'=> 1,
            'price'=> 1,
            'rate_id'=> 1,
            'quantity'=> 10,
            'bar_code' => 105
        ]);

         Items::factory()->create([
            'name' => 'rex 150 ml',
            'category_id' => 1,
            'brand_id' => 1,
            'currency_id'=> 1,
            'price'=> 1,
            'rate_id'=> 1,
            'quantity'=> 10,
            'bar_code' => 106
        ]);

        Items::factory()->create([
            'name' => 'chips 50 g',
            'category_id' => 2,
            'brand_id' => 2,
            'currency_id'=> 1,
            'price'=> 2,
            'rate_id'=> 1,
            'quantity'=> 5,
            'bar_code' => 101
        ]);

        Items::factory()->create([
            'name' => 'Corona 50 ml',
            'category_id' => 1,
            'brand_id' => 1,
            'currency_id'=> 1,
            'price'=> 3,
            'rate_id'=> 1,
            'quantity'=> 10,
            'bar_code' => 102
        ]);

        Items::factory()->create([
            'name' => 'Galaxy 50 g',
            'category_id' => 3,
            'brand_id' => 1,
            'currency_id'=> 1,
            'price'=> 4,
            'rate_id'=> 1,
            'quantity'=> 10,
            'bar_code' => 103
        ]);

        Currency::create([
            'name' => 'USD',
            'code'=> '$'
        ]);

        Currency::create([
            'name' => 'LBP',
            'code'=> 'L.L'
        ]);

        rate::create([
            'currency_id' => 1,
            'counter_currency_id'=> 2,
            'rate'=> 89500
        ]);

        Account::create([
            'name' => "Cash on Hand",
            'code' => "1000",
            'description' => "Physical cash in drawers"
        ]);

        Account::create([
            'name' => "Bank Account",
            'code' => "1010",
            'description' => "Business bank account balances"
        ]);

        Account::create([
            'name' => "Inventory",
            'code' => "1020",
            'description' => "Value of current stock on hand"
        ]);

        Account::create([
            'name' => "Accounts Payable",
            'code' => "2000",
            'description' => "Amounts owed to suppliers"
        ]);

        Account::create([
            'name' => "Sales Tax Payable",
            'code' => "2010",
            'description' => "VAT or sales tax collected from customers"
        ]);

        Account::create([
            'name' => "Employee Payable",
            'code' => "2020",
            'description' => "Salaries & wages owed"
        ]);

        Account::create([
            'name' => "Owner's Capital",
            'code' => "3000",
            'description' => "Initial and additional investments"
        ]);

        Account::create([
            'name' => "Retained Earnings",
            'code' => "3010",
            'description' => "Accumulated net profit"
        ]);

        Account::create([
            'name' => "Drawings",
            'code' => "3020",
            'description' => "Withdrawals by owner"
        ]);

        Account::create([
            'name' => "Sales Revenue",
            'code' => "4000",
            'description' => "Main income from product sales"
        ]);

        Account::create([
            'name' => "Service Revenue",
            'code' => "4010",
            'description' => "Revenue from services, if any"
        ]);

        Account::create([
            'name' => "Discounts Given",
            'code' => "4020",
            'description' => "Discounts allowed to customers (negative income)"
        ]);

        Account::create([
            'name' => "Other Income",
            'code' => "4030",
            'description' => "Miscellaneous income (e.g. delivery fees, late charges)"
        ]);

        Account::create([
            'name' => "Cost of Goods Sold (COGS)",
            'code' => "5000",
            'description' => "Cost of purchased goods sold"
        ]);

        Account::create([
            'name' => "Purchase Expense",
            'code' => "5010",
            'description' => "Non-inventory purchases (e.g. office supplies)"
        ]);

        Account::create([
            'name' => "Rent Expense",
            'code' => "5020",
            'description' => "Shop or office rent"
        ]);

        Account::create([
            'name' => "Utility Expense",
            'code' => "5030",
            'description' => "Electricity, water, internet"
        ]);

        Account::create([
            'name' => "Employee Wages",
            'code' => "5040",
            'description' => "Salaries & wages paid"
        ]);

        Account::create([
            'name' => "Transport & Delivery",
            'code' => "5050",
            'description' => "Shipping costs"
        ]);

        Account::create([
            'name' => "Repair & Maintenance",
            'code' => "5060",
            'description' => "Maintenance of equipment or premises"
        ]);

        Account::create([
            'name' => "Marketing Expense",
            'code' => "5070",
            'description' => "Promotions, ads, etc."
        ]);
    }
}
