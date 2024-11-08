<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;use App\Models\Category;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Parent
        $electronics = Category::create(['name' => 'Electronics', 'parent_id' => null]);
        $fashion = Category::create(['name' => 'Fashion', 'parent_id' => null]);
        $home = Category::create(['name' => 'Home Appliances', 'parent_id' => null]);
        $sports = Category::create(['name' => 'Sports', 'parent_id' => null]);
        $books = Category::create(['name' => 'Books', 'parent_id' => null]);

        // Electronics
        Category::create(['name' => 'Mobile Phones', 'parent_id' => $electronics->id]);
        Category::create(['name' => 'Laptops', 'parent_id' => $electronics->id]);
        Category::create(['name' => 'Televisions', 'parent_id' => $electronics->id]);
        Category::create(['name' => 'Cameras', 'parent_id' => $electronics->id]);

        // Fashion
        Category::create(['name' => 'Men\'s Clothing', 'parent_id' => $fashion->id]);
        Category::create(['name' => 'Women\'s Clothing', 'parent_id' => $fashion->id]);
        Category::create(['name' => 'Accessories', 'parent_id' => $fashion->id]);

        // Home Appliances
        Category::create(['name' => 'Kitchen Appliances', 'parent_id' => $home->id]);
        Category::create(['name' => 'Laundry Appliances', 'parent_id' => $home->id]);
        Category::create(['name' => 'Cleaning Appliances', 'parent_id' => $home->id]);

        // Sports
        Category::create(['name' => 'Outdoor Sports', 'parent_id' => $sports->id]);
        Category::create(['name' => 'Indoor Sports', 'parent_id' => $sports->id]);
        Category::create(['name' => 'Gym Equipment', 'parent_id' => $sports->id]);

        // Books
        Category::create(['name' => 'Fiction', 'parent_id' => $books->id]);
        Category::create(['name' => 'Non-fiction', 'parent_id' => $books->id]);
        Category::create(['name' => 'Children\'s Books', 'parent_id' => $books->id]);
        Category::create(['name' => 'Educational', 'parent_id' => $books->id]);
    }
}
