<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


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
            $users = Product::join('categories', 'products.category_id', '=', 'categories.id')
                ->select(['products.id', 'products.name', 'products.description', 'products.price', 'categories.name as category', 'categories.id as category_id', 'products.created_at']);

            return DataTables::of($users)
                ->filterColumn('category', function ($query, $keyword) {
                    $query->where('categories.name', 'like', "%{$keyword}%");
                })
                ->editColumn('created_at', function ($user) {
                    return $user->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
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
