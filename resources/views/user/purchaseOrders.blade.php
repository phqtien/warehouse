@extends('layouts/master')

@section('content')
<div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between py-3">
            <h6 class="m-0 font-weight-bold text-primary">Purchase Orders Management</h6>
            <a href="/purchase-orders/new-purchase-order" class="btn btn-primary" id="showNewPurchaseOrderBtn">New Purchase Order</a>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="purchaseOrdersTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Order Date</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Order Date</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="{{ asset('js/purchaseOrders.js') }}"></script>
@endsection

@push('styles')
<!-- Custom styles for this page -->
<link href="{{ asset('theme/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<!-- Page level plugins -->
<script src="{{ asset('theme/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('theme/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<!-- Page level custom scripts -->
<script src="{{ asset('theme/js/demo/datatables-demo.js') }}"></script>
@endpush