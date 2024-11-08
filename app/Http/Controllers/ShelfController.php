<?php

namespace App\Http\Controllers;

use App\Models\Shelf;
use App\Models\Warehouse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class ShelfController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::All();
        return view('/admin/shelves', compact('warehouses'));
    }

    public function fetchShelfs(Request $request)
    {
        if ($request->ajax()) {
            $shelves = Shelf::join('warehouses', 'warehouses.id', '=', 'shelves.warehouse_id')
                ->select(['shelves.id', 'warehouses.name as warehouse_name', 'warehouses.id as warehouse_id', 'shelves.name', 'shelves.capacity', 'shelves.created_at']);

            return DataTables::of($shelves)
                ->filterColumn('warehouse_name', function ($query, $keyword) {
                    $query->where('warehouses.name', 'like', "%{$keyword}%");
                })
                ->editColumn('created_at', function ($shelf) {
                    return $shelf->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
                })
                ->make(true);
        }

        return abort(404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:0',
        ]);

        Shelf::create([
            'warehouse_id' => $request->warehouse_id,
            'name' => $request->name,
            'capacity' => $request->capacity,
        ]);

        return response()->json([
            'message' => 'Shelf created successfully.',
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'warehouse_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:0',
        ]);

        $shelf = Shelf::findOrFail($id);

        $shelf->update([
            'warehouse_id' => $request->warehouse_id,
            'name' => $request->name,
            'capacity' => $request->capacity,
        ]);

        return response()->json([
            'message' => 'Shelf updated successfully.',
        ], 200);
    }

    public function destroy($id)
    {
        $user = Shelf::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'Shelf deleted successfully.'
        ], 200);
    }
}
