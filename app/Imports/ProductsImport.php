<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToCollection, WithHeadingRow
{
    public $successCount = 0;
    public $totalCount = 0;

    public function collection(Collection $rows)
    {
        $this->totalCount = $rows->count();

        foreach ($rows as $row) {
            $validator = Validator::make($row->toArray(), [
                'name' => ['required', 'string', 'max:255'],
                'price' => ['required', 'numeric', 'min:0'],
                'category_id' => ['required', 'exists:categories,id'],
            ]);

            if ($validator->fails()) {
                continue;
            }

            Product::create([
                'name' => $row['name'],
                'description' => $row['description'] ?? null,
                'price' => $row['price'],
                'category_id' => $row['category_id'],
            ]);

            $this->successCount++;
        }
    }
}
