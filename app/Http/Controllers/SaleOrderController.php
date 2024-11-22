<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaleOrder;
use App\Models\SaleOrderDetail;
use App\Models\Shelf;
use App\Models\Customer;
use App\Models\ShelfProduct;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SaleOrderController extends Controller
{
    public function index()
    {
        return view('/user/saleOrders');
    }

    public function newSaleOrder()
    {
        return view('/user/newSaleOrder');
    }


    public function editSaleOrder($id)
    {
        $saleOrder = SaleOrder::findOrFail($id);

        $customer = Customer::findOrFail($saleOrder->customer_id);

        $products = SaleOrderDetail::join('products', 'sale_order_details.product_id', '=', 'products.id')
            ->where('sale_order_details.sale_order_id', $id)
            ->select('sale_order_details.*', 'products.name as product_name', 'products.price as product_price')
            ->get();

        return view('/user/editSaleOrder', compact('saleOrder', 'products', 'customer'));
    }

    public function fetchSaleOrders(Request $request)
    {
        if ($request->ajax()) {
            $saleOrders = SaleOrder::join('customers', 'customers.id', '=', 'sale_orders.customer_id')
            ->select('sale_orders.*', 'customers.name as name');

            return DataTables::of($saleOrders)
                ->editColumn('created_at', function ($saleOrder) {
                    return $saleOrder->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
                })
                ->make(true);
        }

        return abort(404);
    }

    public function searchCustomerByPhone(Request $request) {
        $phone = $request->input('phone');

        $customer = Customer::where('phone', $phone)->first();

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        return response()->json([
            'name' => $customer->name,
            'email' => $customer->email,
            'address' => $customer->address,
            'id' => $customer->id,
        ]);
    }

    public function searchProductByName(Request $request)
    {
        $name = $request->input('name');

        $product = Product::where('name', $name)->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $shelves = Shelf::join('shelf_products', 'shelves.id', '=', 'shelf_products.shelf_id')
            ->join('warehouses', 'warehouses.id', '=', 'shelves.warehouse_id')
            ->where('shelf_products.product_id', $product->id)
            ->select([
                'shelves.id as shelf_id',
                'shelves.name as shelf_name',
                'warehouses.id as warehouse_id',
                'warehouses.name as warehouse_name',
                DB::raw('SUM(shelf_products.quantity) as quantity'),
            ])
            ->groupBy('warehouses.name', 'warehouses.id', 'shelves.name', 'shelves.id')
            ->get();

        $warehouses = $shelves->unique('warehouse_id')->map(function ($shelf) {
            return [
                'warehouse_id' => $shelf->warehouse_id,
                'warehouse_name' => $shelf->warehouse_name,
            ];
        })->values();

        return response()->json([
            'product' => $product,
            'warehouses' => $warehouses,
            'shelves' => $shelves,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer',
            'status' => 'required|string',
            'products' => 'required|array',
            'products.*.id' => 'required|integer',
            'products.*.shelf_id' => 'required|integer',
            'products.*.warehouse_id' => 'required|integer',
            'products.*.quantity' => 'required|integer|min:0',
        ]);

        $saleOrder = SaleOrder::create([
            'customer_id' => $request->customer_id,
            'status' => $request->status,
        ]);

        foreach ($request->products as $product) {
            SaleOrderDetail::create([
                'sale_order_id' => $saleOrder->id,
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'warehouse_id' => $product['warehouse_id'],
                'shelf_id' => $product['shelf_id'],
            ]);
        }

        foreach ($request->products as $product) {
            $shelfProduct = ShelfProduct::where('product_id', $product['id'])
                ->where('shelf_id', $product['shelf_id'])
                ->first();

            $newQuantity = $shelfProduct->quantity - $product['quantity'];

            if ($newQuantity == 0) {
                $shelfProduct->delete();
            } else {
                $shelfProduct->update([
                    'quantity' => $newQuantity,
                ]);
            }
        }

        return response()->json(['message' => 'Order created successfully!'], 201);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|integer',
            'status' => 'required|string',
            'products' => 'required|array',
            'products.*.id' => 'required|integer',
            'products.*.shelf_id' => 'required|integer',
            'products.*.warehouse_id' => 'required|integer',
            'products.*.quantity' => 'required|integer|min:0',
        ]);

        // Restore products in the last sale order
        $saleOrderDetails = SaleOrderDetail::where('sale_order_id', $id)->get();

        foreach ($saleOrderDetails as $saleOrderDetail) {
            $shelfProduct = shelfProduct::where('product_id', $saleOrderDetail->product_id)
                ->where('shelf_id', $saleOrderDetail['shelf_id'])
                ->first();

            if ($shelfProduct) {
                $shelfProduct->update([
                    'quantity' =>  $shelfProduct->quantity + $saleOrderDetail->quantity,
                ]);
            } else {
                ShelfProduct::create([
                    'shelf_id' => $saleOrderDetail->shelf_id,
                    'product_id' => $saleOrderDetail->product_id,
                    'quantity' => $saleOrderDetail->quantity,
                ]);
            }
        }

        // Update Sale Order
        $saleOrder = SaleOrder::findOrFail($id);

        $saleOrder->update([
            'customer_id' => $request->customer_id,
            'status' => $request->status,
        ]);

        $existingProductIds = SaleOrderDetail::where('sale_order_id', $id)
            ->pluck('product_id')
            ->toArray();

        // Foreach products in request
        foreach ($request->products as $product) {
            $productId = $product['id'];

            SaleOrderDetail::updateOrCreate(
                [
                    'sale_order_id' => $id,
                    'product_id' => $productId,
                ],
                [
                    'quantity' => $product['quantity'],
                    'warehouse_id' => $product['warehouse_id'],
                    'shelf_id' => $product['shelf_id'],
                ]
            );

            $existingProductIds = array_diff($existingProductIds, [$productId]);
        }

        // Delete products that are not in request
        foreach ($existingProductIds as $productId) {
            SaleOrderDetail::where('sale_order_id', $id)
                ->where('product_id', $productId)
                ->delete();
        }

        // If status is not 'Cancel', update products to shelf_products
        if ($request->status !== 'Cancel') {
            foreach ($request->products as $product) {
                $shelfProduct = ShelfProduct::where('product_id', $product['id'])
                    ->where('shelf_id', $product['shelf_id'])
                    ->first();
    
                $newQuantity = $shelfProduct->quantity - $product['quantity'];
    
                if ($newQuantity == 0) {
                    $shelfProduct->delete();
                } else {
                    $shelfProduct->update([
                        'quantity' => $newQuantity,
                    ]);
                }
            }
        }

        return response()->json([
            'message' => 'SaleOrder updated successfully.',
        ], 200);
    }

    public function destroy($id)
    {
        $saleOrderDetails = SaleOrderDetail::where('sale_order_id', $id)->get();

        foreach ($saleOrderDetails as $saleOrderDetail) {
            $shelfProduct = shelfProduct::where('product_id', $saleOrderDetail->product_id)
                ->where('shelf_id', $saleOrderDetail['shelf_id'])
                ->first();

            if ($shelfProduct) {
                $shelfProduct->update([
                    'quantity' =>  $shelfProduct->quantity + $saleOrderDetail->quantity,
                ]);
            } else {
                ShelfProduct::create([
                    'shelf_id' => $saleOrderDetail->shelf_id,
                    'product_id' => $saleOrderDetail->product_id,
                    'quantity' => $saleOrderDetail->quantity,
                ]);
            }
        }

        $order = SaleOrder::findOrFail($id);
        $order->delete();

        return response()->json([
            'message' => 'PurchaseOrder deleted successfully.'
        ], 200);
    }
}
