@extends('layouts/master')

@section('content')
<div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header d-flex align-items-center justify-content-between py-3">
            <div class="">
                <h6 class="m-0 font-weight-bold text-primary mb-1">Sale Orders Management</h6>
                <div class="d-flex">
                    <select id="statusFilter" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="Pending">Pending</option>
                        <option value="Confirm">Confirm</option>
                        <option value="Done">Done</option>
                        <option value="Cancel">Cancel</option>
                    </select>
                    <button type="button" id="export-btn" class="btn btn-success">Export</button>
                </div>
            </div>
            <a href="/sale-orders/new-sale-order" class="btn btn-primary" id="showNewSaleOrderBtn">New Sale Order</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="saleOrdersTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Status</th>
                            <th>Name</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Status</th>
                            <th>Name</th>
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

<script src="{{ asset('js/saleOrders.js') }}"></script>
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