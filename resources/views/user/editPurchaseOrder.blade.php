@extends('layouts/master')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header d-flex align-items-center justify-content-between py-3">
            <h6 class="m-0 font-weight-bold text-primary" id="newPurchaseOrderModalLabel">Edit Purchase Order</h6>
            <button type="button" class="btn btn-danger" id="showConfirmDeleteModalBtn">Delete Order</button>
        </div>
        <div class="card-body">
            <form id="editPurchaseOrderForm">
                <div class="mb-3">
                    <div class="col col-md-6 mb-3 p-0">
                        <select id="warehouseSelect" class="form-control" onchange="filterShelves()" required>
                            @php
                            $uniqueWarehouses = $warehouses->unique('warehouse_id');
                            @endphp

                            <option value="" disabled selected>Select warehouse</option>
                            @foreach($uniqueWarehouses as $warehouse)
                            <option value="{{ $warehouse->warehouse_id }}"
                                data-id="{{ $warehouse->warehouse_id }}">
                                {{ $warehouse->warehouse_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Order Date -->
                    <div class="col col-md-6 mb-3 p-0">
                        <input type="date" class="form-control" id="order-date" name="order-date" value="{{ $purchaseOrder->order_date }}" required>
                    </div>

                    <!-- Order Status -->
                    <div class="mb-3 col col-md-6 mb-3 p-0 d-flex justify-content-between">
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="status-pending" name="status" value="Pending" {{ $purchaseOrder->status == 'Pending' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status-pending">Pending</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="status-confirm" name="status" value="Confirm" {{ $purchaseOrder->status == 'Confirm' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status-confirm">Confirm</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="status-done" name="status" value="Done" {{ $purchaseOrder->status == 'Done' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status-done">Done</label>
                        </div>
                    </div>

                    <!-- Product Table -->
                    <table class="table table-striped table-bordered" id="productTable">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Shelf</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="product-body">
                            @foreach ($products as $index => $product)
                            <tr>
                                <td class="align-content-center">{{ $index + 1 }}</td>
                                <td>
                                    <input type="text" class="form-control" value="{{ $product->product_name }}" placeholder="Product name" oninput="searchProductByName(this)" required>
                                </td>
                                <td class="align-content-center product-price">{{ $product->price }}</td>
                                <td>
                                    <input type="button" class="form-control bg-white shelf-select" value="Select shelf" readonly onclick="openShelfModal(this)">
                                </td>
                                <td>
                                    <input type="number" class="w-50 form-control product-quantity" min="0" value="{{ $product->quantity }}" required oninput="calculateTotal(this)">
                                </td>
                                <td class="align-content-center product-total">{{ $product->price * $product->quantity }}</td>
                                <td><button class="btn btn-danger delete-product-btn" data-id="{{ $product->product_id }}" onclick="deleteRow(this)">Delete</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-secondary" onclick="addProductRow()">Add</button>
                        <h5 class="">Order Total: <span id="order-total">{{ $purchaseOrder->total }}</span></h5>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="submitNewPurchaseOrderBtn" onclick="editPurchaseOrder('{{ $purchaseOrder->id }}')">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal để chọn Shelf -->
<div class="modal fade" id="shelfModal" tabindex="-1" role="dialog" aria-labelledby="shelfModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shelfModalLabel">Select Shelf</h5>
                <button type="button" class="close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <div class="modal-body">
                <ul class="list-group" id="shelvesUl">
                    @foreach($warehouses as $warehouse)
                    <li class="list-group-item shelf-item" data-warehouse-id="{{ $warehouse->warehouse_id }}" data-shelf-id="{{ $warehouse->shelf_id }}"
                        data-shelf-name="{{ $warehouse->shelf_name }}" onclick="selectShelf(this)">
                        {{ $warehouse->shelf_name }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Delete Confirmation -->
<div class="modal fade modal" id="deletePurchaseOrderModal" tabindex="-1" aria-labelledby="deletePurchaseOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePurchaseOrderModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this purchase order?
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeletePurchaseOrderBtn" onclick="deletePurchaseOrder('{{ $purchaseOrder->id }}')">Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/editPurchaseOrder.js') }}"></script>
@endsection

@push('styles')
<!-- Custom styles for this page -->
<link href="{{ asset('theme/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush