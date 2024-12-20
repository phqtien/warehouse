<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shelf;
use App\Models\Warehouse;

class ShelfSeeder extends Seeder
{
    public function run()
    {
        $warehouses = Warehouse::all();

        if ($warehouses->count() > 0) {
            foreach ($warehouses as $warehouse) {
                Shelf::create([
                    'warehouse_id' => $warehouse->id,
                    'name' => 'Shelf A',
                    'capacity' => 1000,
                ]);
                Shelf::create([
                    'warehouse_id' => $warehouse->id,
                    'name' => 'Shelf B',
                    'capacity' => 1000,
                ]);
                Shelf::create([
                    'warehouse_id' => $warehouse->id,
                    'name' => 'Shelf C',
                    'capacity' => 1000,
                ]);
            }
        }
    }
}
