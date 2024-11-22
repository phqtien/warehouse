<?php

namespace App\Http\Controllers;

use App\Models\SaleOrderDetail;
use App\Models\SaleOrder;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SaleOrderDetailController extends Controller
{
    public function index()
    {
        return view('/user/saleOrderDetail');
    }

    public function fetchSaleOrderDetails(Request $request)
    {
        if ($request->ajax()) {
            $query = SaleOrderDetail::join('sale_orders', 'sale_order_details.sale_order_id', '=', 'sale_orders.id')
                ->join('products', 'sale_order_details.product_id', '=', 'products.id')
                ->join('warehouses', 'sale_order_details.warehouse_id', '=', 'warehouses.id')
                ->join('shelves', 'sale_order_details.shelf_id', '=', 'shelves.id')
                ->select([
                    'sale_orders.id',
                    'sale_orders.status',
                    'products.name as product_name',
                    'sale_order_details.quantity as quantity',
                    'warehouses.name as warehouse',
                    'shelves.name as shelf',
                    'sale_orders.created_at'
                ]);

            if ($request->has('status') && $request->status != '') {
                $query->where('sale_orders.status', $request->status);
            }

            $saleOrderDetails = $query->get();

            return DataTables::of($saleOrderDetails)
                ->editColumn('created_at', function ($saleOrderDetail) {
                    return $saleOrderDetail->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
                })
                ->make(true);
        }

        return abort(404);
    }
}
