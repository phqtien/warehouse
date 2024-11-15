@extends('layouts/master')

@section('content')
<div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header d-flex align-items-center justify-content-between py-3">
            <h6 class="m-0 font-weight-bold text-primary">Inventories</h6>
            <div class="d-flex w-50 align-items-center">
                <h6 class="m-0">Filter:</h6>
                <select id="warehouseSelect" class="form-control" onchange="filterWarehouses()" required>
                    @php
                    $uniqueWarehouses = $warehouses->unique('warehouse_id');
                    @endphp

                    <option value="" disabled selected>Warehouse</option>
                    @foreach($uniqueWarehouses as $warehouse)
                    <option value="{{ $warehouse->warehouse_id }}"
                        data-id="{{ $warehouse->warehouse_id }}">
                        {{ $warehouse->warehouse_name }}
                    </option>
                    @endforeach
                </select>
                <select id="shelfSelect" class="form-control" required>
                    <option value="" disabled selected>Shelf</option>
                    @foreach($warehouses as $warehouse)
                    <option class="shelf-item" value="{{ $warehouse->shelf_id }}"
                        data-warehouse-id="{{ $warehouse->warehouse_id }}"
                        data-id="{{ $warehouse->shelf_id }}">
                        {{ $warehouse->shelf_name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="inventoriesTable">
                    <thead>
                        <tr>
                            <th>Warehouse</th>
                            <th>Shelf</th>
                            <th>Product Name</th>
                            <th>Stock Quantity</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Warehouse</th>
                            <th>Shelf</th>
                            <th>Product Name</th>
                            <th>Stock Quantity</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="{{ asset('js/inventory.js') }}"></script>
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