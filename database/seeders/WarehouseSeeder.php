<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;use App\Models\Warehouse;


class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            ['name' => 'Warehouse 1', 'address' => 'City A'],
            ['name' => 'Warehouse 2', 'address' => 'City B'],
            ['name' => 'Warehouse 3', 'address' => 'City C'],
            ['name' => 'Warehouse 4', 'address' => 'City D'],
            ['name' => 'Warehouse 5', 'address' => 'City E'],
            ['name' => 'Warehouse 6', 'address' => 'City F'],
            ['name' => 'Warehouse 7', 'address' => 'City G'],
            ['name' => 'Warehouse 8', 'address' => 'City H'],
            ['name' => 'Warehouse 9', 'address' => 'City I'],
            ['name' => 'Warehouse 10', 'address' => 'City J'],
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::create($warehouse);
        }
    }
}
