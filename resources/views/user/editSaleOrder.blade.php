@extends('layouts/master')

@section('content')
<div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between py-3">
            <h6 class="m-0 font-weight-bold text-primary" id="editSaleOrderModalLabel">Edit Sale Order</h6>
            <button type="button" class="btn btn-danger" id="showConfirmDeleteModalBtn">Delete Order</button>
        </div>
        <div class="card-body">
            <form id="editSaleOrderForm">
                <div class="mb-3">
                    <div class="col col-md-6 mb-3 p-0">
                        <input type="text" class="form-control" id="customer-name" value="{{ $saleOrder->name }}" placeholder="Customer name" required>
                    </div>
                    <div class="col col-md-6 mb-3 p-0">
                        <input type="text" class="form-control" id="customer-phone" value="{{ $saleOrder->phone }}" placeholder="Customer phone" required>
                    </div>
                    <div class="col col-md-6 mb-3 p-0">
                        <input type="text" class="form-control" id="customer-address" value="{{ $saleOrder->address }}" placeholder="Customer address" required>
                    </div>

                    <div class="mb-3 col col-md-6 mb-3 p-0 d-flex justify-content-between">
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="status-pending" name="status" value="Pending" {{ $saleOrder->status == 'Pending' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status-pending">Pending</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="status-confirm" name="status" value="Confirm" {{ $saleOrder->status == 'Confirm' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status-confirm">Confirm</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="status-done" name="status" value="Done" {{ $saleOrder->status == 'Done' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status-done">Done</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="status-cancel" name="status" value="Cancel" {{ $saleOrder->status == 'Cancel' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status-cancel">Cancel</label>
                        </div>
                    </div>

                    <table class="table table-striped    table-bordered" id="productTable">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Warehouse</th>
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
                                    <input type="text" class="form-control" placeholder="Product name" value="{{ $product->product_name }}" oninput="searchProductByName(this)">
                                </td>
                                <td class="align-content-center product-price">{{ $product->product_price }}</td>
                                <td>
                                    <select class="form-control warehouse-select" data-warehouse_id="{{ $product->warehouse_id }}" onchange="updateShelves(this)">
                                        <option value="">Select warehouse</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control shelf-select" data-shelf_id="{{ $product->shelf_id }}">
                                        <option value="">Select shelf</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="w-50 form-control product-quantity" min="0" value="{{ $product->quantity }}" oninput="calculateTotal(this)">
                                </td>
                                <td class="align-content-center product-total">{{ $product->product_price * $product->quantity }}</td>
                                <td><button class="btn btn-danger delete-product-btn" onclick="deleteRow(this)">Delete</button></td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-secondary" onclick="addProductRow()">Add</button>
                        <h5 class="m-0">Order Total: <span id="order-total">0.00</span></h5>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="submitEditSaleOrderBtn" onclick="updateSaleOrder('{{ $saleOrder->id }}')">Submit</button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Modal for Delete Confirmation -->
<div class="modal fade modal" id="deleteSaleOrderModal" tabindex="-1" aria-labelledby="deleteSaleOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteSaleOrderModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this sale order?
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteSaleOrderBtn" onclick="deleteSaleOrder('{{ $saleOrder->id }}')">Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/editSaleOrder.js') }}"></script>
@endsection

@push('styles')
<!-- Custom styles for this page -->
<link href="{{ asset('theme/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
@endpush