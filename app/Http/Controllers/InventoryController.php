<?php

namespace App\Http\Controllers;

use App\Models\Shelf;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::join('shelves', 'shelves.warehouse_id', '=', 'warehouses.id')
            ->select([
                'warehouses.id as warehouse_id',
                'warehouses.name as warehouse_name',
                'shelves.id as shelf_id',
                'shelves.name as shelf_name',
            ])
            ->get();

        return view('/user/inventory', compact('warehouses'));
    }

    public function fetchInventories(Request $request)
    {
        if ($request->ajax()) {
            $query = Shelf::join('warehouses', 'warehouses.id', '=', 'shelves.warehouse_id')
                ->join('shelf_products', 'shelf_products.shelf_id', '=', 'shelves.id')
                ->join('products', 'shelf_products.product_id', '=', 'products.id')
                ->select([
                    'warehouses.name as warehouse_name',
                    'shelves.name as shelf_name',
                    'products.name as product_name',
                    DB::raw('SUM(shelf_products.quantity) as stock_quantity'),
                ])
                ->groupBy('warehouses.name', 'shelves.name', 'products.name');


            if ($request->has('warehouse') && $request->warehouse != '') {
                $query->where('warehouses.id', $request->warehouse);
                if ($request->has('shelf') && $request->shelf != '') {
                    $query->where('shelves.id', $request->shelf);
                }
            }

            $purchaseOrderDetails = $query->get();

            return DataTables::of($purchaseOrderDetails)
                ->make(true);
        }

        return abort(404);
    }
}
