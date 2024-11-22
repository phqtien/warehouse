<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PurchaseOrderDetailController extends Controller
{
    public function index()
    {
        return view('/user/purchaseOrderDetail');
    }

    public function fetchPurchaseOrderDetails(Request $request)
    {
        if ($request->ajax()) {
            $query = PurchaseOrderDetail::join('purchase_orders', 'purchase_order_details.purchase_order_id', '=', 'purchase_orders.id')
                ->join('products', 'purchase_order_details.product_id', '=', 'products.id')
                ->select([
                    'purchase_orders.id as id',
                    'purchase_orders.order_date',
                    'purchase_orders.status',
                    'products.name as product_name',
                    'products.price as price',
                    'purchase_order_details.quantity as quantity',
                    'purchase_orders.created_at'
                ]);

            if ($request->has('status') && $request->status != '') {
                $query->where('purchase_orders.status', $request->status);
            }

            $purchaseOrderDetails = $query->get();

            return DataTables::of($purchaseOrderDetails)
                ->editColumn('created_at', function ($purchaseOrderDetail) {
                    return $purchaseOrderDetail->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
                })
                ->make(true);
        }

        return abort(404);
    }
}
