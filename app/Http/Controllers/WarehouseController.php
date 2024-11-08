<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        return view('/admin/warehouses');
    }

    public function fetchWarehouses(Request $request)
    {
        if ($request->ajax()) {
            $warehouses = Warehouse::All();

            return DataTables::of($warehouses)
                ->editColumn('created_at', function ($warehouse) {
                    return $warehouse->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
                })
                ->make(true);
        }

        return abort(404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        Warehouse::create([
            'name' => $request->name,
            'address' => $request->address,
        ]);

        return response()->json([
            'message' => 'Warehouse created successfully.',
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $warehouse = Warehouse::findOrFail($id);

        $warehouse->update([
            'name' => $request->name,
            'address' => $request->address,
        ]);

        return response()->json([
            'message' => 'Warehouse updated successfully.',
        ], 200);
    }

    public function destroy($id)
    {
        $user = Warehouse::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'Warehouse deleted successfully.'
        ], 200);
    }
}
