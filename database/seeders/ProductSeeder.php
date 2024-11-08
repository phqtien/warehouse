<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define products for each subcategory
        $subcategories = [
            // Electronics
            'Mobile Phones' => [
                ['name' => 'iPhone 14', 'description' => 'Apple smartphone with advanced features.', 'price' => 999.99, 'stock_quantity' => 50],
                ['name' => 'Samsung Galaxy S22', 'description' => 'Samsung smartphone with AMOLED display.', 'price' => 899.99, 'stock_quantity' => 40],
                ['name' => 'Google Pixel 7', 'description' => 'Google smartphone with pure Android.', 'price' => 799.99, 'stock_quantity' => 35],
            ],
            'Laptops' => [
                ['name' => 'MacBook Pro', 'description' => 'Apple laptop with M1 chip.', 'price' => 1299.99, 'stock_quantity' => 20],
                ['name' => 'Dell XPS 13', 'description' => 'Compact and powerful Windows laptop.', 'price' => 999.99, 'stock_quantity' => 25],
                ['name' => 'HP Spectre x360', 'description' => 'Convertible laptop with touch screen.', 'price' => 1199.99, 'stock_quantity' => 15],
            ],
            'Televisions' => [
                ['name' => 'LG OLED TV', 'description' => 'Ultra HD OLED TV with vibrant colors.', 'price' => 1499.99, 'stock_quantity' => 10],
                ['name' => 'Samsung QLED TV', 'description' => 'QLED TV with quantum dot technology.', 'price' => 1399.99, 'stock_quantity' => 8],
                ['name' => 'Sony Bravia', 'description' => '4K Ultra HD TV with HDR support.', 'price' => 1299.99, 'stock_quantity' => 12],
            ],
            'Cameras' => [
                ['name' => 'Canon EOS R5', 'description' => 'High-resolution mirrorless camera.', 'price' => 3899.99, 'stock_quantity' => 5],
                ['name' => 'Nikon Z6', 'description' => 'Full-frame mirrorless camera.', 'price' => 1999.99, 'stock_quantity' => 7],
                ['name' => 'Sony Alpha a7', 'description' => 'Compact mirrorless camera.', 'price' => 1799.99, 'stock_quantity' => 9],
            ],

            // Fashion
            'Men\'s Clothing' => [
                ['name' => 'Men\'s T-Shirt', 'description' => 'High-quality cotton t-shirt.', 'price' => 29.99, 'stock_quantity' => 100],
                ['name' => 'Men\'s Jeans', 'description' => 'Comfortable denim jeans.', 'price' => 49.99, 'stock_quantity' => 75],
                ['name' => 'Men\'s Jacket', 'description' => 'Warm winter jacket.', 'price' => 89.99, 'stock_quantity' => 40],
            ],
            'Women\'s Clothing' => [
                ['name' => 'Women\'s Dress', 'description' => 'Elegant evening dress.', 'price' => 69.99, 'stock_quantity' => 50],
                ['name' => 'Women\'s Blouse', 'description' => 'Casual blouse.', 'price' => 34.99, 'stock_quantity' => 80],
                ['name' => 'Women\'s Skirt', 'description' => 'Chic pleated skirt.', 'price' => 44.99, 'stock_quantity' => 60],
            ],
            'Accessories' => [
                ['name' => 'Leather Belt', 'description' => 'Genuine leather belt.', 'price' => 24.99, 'stock_quantity' => 150],
                ['name' => 'Scarf', 'description' => 'Warm wool scarf.', 'price' => 19.99, 'stock_quantity' => 100],
                ['name' => 'Sunglasses', 'description' => 'Stylish polarized sunglasses.', 'price' => 49.99, 'stock_quantity' => 90],
            ],

            // Home Appliances
            'Kitchen Appliances' => [
                ['name' => 'Blender', 'description' => 'High-power blender for smoothies.', 'price' => 59.99, 'stock_quantity' => 30],
                ['name' => 'Microwave Oven', 'description' => 'Compact microwave oven.', 'price' => 99.99, 'stock_quantity' => 25],
                ['name' => 'Coffee Maker', 'description' => 'Programmable coffee maker.', 'price' => 69.99, 'stock_quantity' => 40],
            ],
            'Laundry Appliances' => [
                ['name' => 'Washing Machine', 'description' => 'Automatic washing machine.', 'price' => 499.99, 'stock_quantity' => 15],
                ['name' => 'Dryer', 'description' => 'Efficient clothes dryer.', 'price' => 399.99, 'stock_quantity' => 10],
                ['name' => 'Iron', 'description' => 'Steam iron.', 'price' => 29.99, 'stock_quantity' => 60],
            ],
            'Cleaning Appliances' => [
                ['name' => 'Vacuum Cleaner', 'description' => 'Powerful vacuum cleaner.', 'price' => 149.99, 'stock_quantity' => 20],
                ['name' => 'Robot Vacuum', 'description' => 'Smart robot vacuum.', 'price' => 299.99, 'stock_quantity' => 12],
                ['name' => 'Mop', 'description' => 'Easy-to-use mop.', 'price' => 19.99, 'stock_quantity' => 80],
            ],

            // Sports
            'Outdoor Sports' => [
                ['name' => 'Basketball', 'description' => 'High-quality basketball.', 'price' => 19.99, 'stock_quantity' => 80],
                ['name' => 'Soccer Ball', 'description' => 'Official-size soccer ball.', 'price' => 24.99, 'stock_quantity' => 60],
                ['name' => 'Tennis Racket', 'description' => 'Durable tennis racket.', 'price' => 59.99, 'stock_quantity' => 30],
            ],
            'Indoor Sports' => [
                ['name' => 'Yoga Mat', 'description' => 'Non-slip yoga mat.', 'price' => 29.99, 'stock_quantity' => 100],
                ['name' => 'Dumbbell Set', 'description' => 'Adjustable dumbbell set.', 'price' => 99.99, 'stock_quantity' => 40],
                ['name' => 'Resistance Bands', 'description' => 'Set of resistance bands.', 'price' => 19.99, 'stock_quantity' => 120],
            ],
            'Gym Equipment' => [
                ['name' => 'Treadmill', 'description' => 'High-quality treadmill.', 'price' => 899.99, 'stock_quantity' => 10],
                ['name' => 'Exercise Bike', 'description' => 'Stationary exercise bike.', 'price' => 499.99, 'stock_quantity' => 15],
                ['name' => 'Elliptical', 'description' => 'Compact elliptical trainer.', 'price' => 699.99, 'stock_quantity' => 12],
            ],

            // Books
            'Fiction' => [
                ['name' => 'Mystery Novel', 'description' => 'A suspenseful mystery novel.', 'price' => 24.99, 'stock_quantity' => 90],
                ['name' => 'Romance Novel', 'description' => 'A heartwarming romance novel.', 'price' => 14.99, 'stock_quantity' => 100],
                ['name' => 'Science Fiction', 'description' => 'An imaginative sci-fi book.', 'price' => 19.99, 'stock_quantity' => 80],
            ],
            'Non-fiction' => [
                ['name' => 'Biography', 'description' => 'Inspiring life story.', 'price' => 29.99, 'stock_quantity' => 70],
                ['name' => 'Self-Help', 'description' => 'Guide to personal growth.', 'price' => 19.99, 'stock_quantity' => 60],
                ['name' => 'History Book', 'description' => 'Detailed history of ancient Rome.', 'price' => 24.99, 'stock_quantity' => 50],
            ],
            'Children\'s Books' => [
                ['name' => 'Children\'s Fairy Tales', 'description' => 'Collection of fairy tales.', 'price' => 9.99, 'stock_quantity' => 120],
                ['name' => 'Educational Workbook', 'description' => 'Workbook for early learners.', 'price' => 12.99, 'stock_quantity' => 100],
                ['name' => 'Picture Book', 'description' => 'Colorful book with illustrations.', 'price' => 8.99, 'stock_quantity' => 150],
            ],
            'Educational' => [
                ['name' => 'Mathematics Textbook', 'description' => 'Textbook for learning math.', 'price' => 39.99, 'stock_quantity' => 60],
                ['name' => 'Science Workbook', 'description' => 'Hands-on science workbook.', 'price' => 29.99, 'stock_quantity' => 75],
                ['name' => 'Language Arts Book', 'description' => 'Book for language development.', 'price' => 24.99, 'stock_quantity' => 80],
            ],
        ];

        // Loop through each subcategory and insert products
        foreach ($subcategories as $subcategoryName => $products) {
            $category = Category::where('name', $subcategoryName)->first();
            if ($category) {
                foreach ($products as $product) {
                    Product::create([
                        'name' => $product['name'],
                        'description' => $product['description'],
                        'price' => $product['price'],
                        'stock_quantity' => $product['stock_quantity'],
                        'category_id' => $category->id,
                    ]);
                }
            }
        }
    }
}
