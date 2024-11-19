<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Warehouse;
use App\Models\Shelf;
use App\Models\ShelfProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;


class PurchaseOrderController extends Controller
{
    public function index()
    {
        return view('/user/purchaseOrders');
    }

    public function newPurchaseOrder()
    {
        $warehouses = Warehouse::join('shelves', 'shelves.warehouse_id', '=', 'warehouses.id')
            ->select([
                'warehouses.id as warehouse_id',
                'warehouses.name as warehouse_name',
                'shelves.id as shelf_id',
                'shelves.name as shelf_name',
            ])
            ->get();

        return view('user.newPurchaseOrder', compact('warehouses'));
    }


    public function editPurchaseOrder($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        $products = PurchaseOrderDetail::join('products', 'purchase_order_details.product_id', '=', 'products.id')
            ->where('purchase_order_details.purchase_order_id', $id)
            ->select('purchase_order_details.*', 'products.name as product_name')
            ->get();

        $warehouses = Warehouse::join('shelves', 'shelves.warehouse_id', '=', 'warehouses.id')
            ->select([
                'warehouses.id as warehouse_id',
                'warehouses.name as warehouse_name',
                'shelves.id as shelf_id',
                'shelves.name as shelf_name',
            ])
            ->get();

        return view('/user/editPurchaseOrder', compact('purchaseOrder', 'products', 'warehouses'));
    }

    public function fetchPurchaseOrders(Request $request)
    {
        if ($request->ajax()) {
            $purchaseOrders = PurchaseOrder::query();

            return DataTables::of($purchaseOrders)
                ->editColumn('created_at', function ($purchaseOrder) {
                    return $purchaseOrder->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
                })
                ->make(true);
        }

        return abort(404);
    }

    public function searchProductByName(Request $request)
    {
        $name = $request->input('name');

        $product = Product::where('name', $name)->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json(['product' => $product]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'warehouse_id' => [
                'nullable',
                'integer',
                function ($value, $fail) use ($request) {
                    if ($request->input('status') === 'Done' && !$value) {
                        $fail('The warehouse ID is required when the status is "Done".');
                    }
                },
            ],
            'order_date' => 'required|date',
            'status' => 'required|string',
            'products' => 'required|array',
            'products.*.id' => 'required|integer',
            'products.*.shelf_id' => [
                'nullable',
                'integer',
                function ($value, $fail) use ($request) {
                    if ($request->input('status') === 'Done' && !$value) {
                        $fail('The shelf ID is required when the status is "Done".');
                    }
                },
            ],
            'products.*.price' => 'required|numeric',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $purchaseOrder = PurchaseOrder::create([
            'order_date' => $request->order_date,
            'status' => $request->status,
        ]);

        foreach ($request->products as $product) {
            PurchaseOrderDetail::create([
                'purchase_order_id' => $purchaseOrder->id,
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
            ]);
        }

        // If status is 'Done', add products to shelf_products
        if ($request->status === 'Done') {
            foreach ($request->products as $product) {
                ShelfProduct::updateOrCreate(
                    [
                        'product_id' => $product['id'],
                        'shelf_id' => $product['shelf_id'],
                    ],
                    [
                        'quantity' => DB::raw('quantity + ' . $product['quantity']),
                    ]
                );
            }
        }

        return response()->json(['message' => 'Order created successfully!'], 201);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'warehouse_id' => 'required|integer',
            'order_date' => 'required|date',
            'status' => 'required|string',
            'products' => 'required|array',
            'products.*.id' => 'required|integer',
            'products.*.shelf_id' => 'required|integer',
            'products.*.price' => 'required|numeric',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $purchaseOrder = PurchaseOrder::findOrFail($id);

        $purchaseOrder->update([
            'order_date' => $request->order_date,
            'status' => $request->status,
        ]);

        $existingProductIds = PurchaseOrderDetail::where('purchase_order_id', $id)
            ->pluck('product_id')
            ->toArray();

        // Foreach products in request
        foreach ($request->products as $product) {
            $productId = $product['id'];

            PurchaseOrderDetail::updateOrCreate(
                [
                    'purchase_order_id' => $id,
                    'product_id' => $productId,
                ],
                [
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                ]
            );

            $existingProductIds = array_diff($existingProductIds, [$productId]);
        }

        // Delete products that are not in request
        foreach ($existingProductIds as $productId) {
            PurchaseOrderDetail::where('purchase_order_id', $id)
                ->where('product_id', $productId)
                ->delete();
        }

        // If status is 'Done', add products to shelf_products
        if ($request->status === 'Done') {
            foreach ($request->products as $product) {
                ShelfProduct::updateOrCreate(
                    [
                        'product_id' => $product['id'],
                        'shelf_id' => $product['shelf_id'],
                    ],
                    [
                        'quantity' => DB::raw('quantity + ' . $product['quantity']),
                    ]
                );
            }
        }

        return response()->json([
            'message' => 'PurchaseOrder updated successfully.',
        ], 200);
    }

    public function destroy($id)
    {
        $order = PurchaseOrder::findOrFail($id);
        $order->delete();

        return response()->json([
            'message' => 'PurchaseOrder deleted successfully.'
        ], 200);
    }
}
