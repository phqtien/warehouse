<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    public function index()
    {
        $subcategories = Category::whereNotNull('parent_id')->get();
        return view('/admin/products', compact('subcategories'));
    }

    public function fetchProducts(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::join('categories', 'products.category_id', '=', 'categories.id')
                ->leftJoin('purchase_order_details', 'purchase_order_details.product_id', '=', 'products.id')
                ->leftjoin('purchase_orders', 'purchase_orders.id', '=', 'purchase_order_details.purchase_order_id')
                ->select([
                    'products.id',
                    'products.name',
                    'products.description',
                    'products.price',
                    'categories.name as category',
                    'categories.id as category_id',
                    'products.created_at',
                    DB::raw('COALESCE(SUM(CASE WHEN purchase_orders.status = "Done" THEN purchase_order_details.quantity ELSE 0 END), 0) as stock_quantity'),
                ])
                ->groupBy('products.id', 'products.name', 'products.description', 'products.price', 'categories.name', 'categories.id', 'products.created_at');

            return DataTables::of($products)
                ->filterColumn('category', function ($query, $keyword) {
                    $query->where('categories.name', 'like', "%{$keyword}%");
                })
                ->editColumn('created_at', function ($product) {
                    return $product->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
                })
                ->make(true);
        }

        return abort(404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string|max:1000',
            'category_id' => 'required|integer',
        ]);

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);

        return response()->json([
            'message' => 'Product created successfully.',
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string|max:1000',
            'category_id' => 'required|integer',
        ]);

        $product = Product::findOrFail($id);

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);

        return response()->json([
            'message' => 'Product updated successfully.',
        ], 200);
    }

    public function destroy($id)
    {
        $user = Product::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'Product deleted successfully.'
        ], 200);
    }
}
