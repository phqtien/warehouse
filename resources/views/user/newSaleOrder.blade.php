@extends('layouts/master')

@section('content')
<div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between py-3">
            <h6 class="m-0 font-weight-bold text-primary" id="newSaleOrderModalLabel">New Sale Order</h6>
        </div>
        <div class="card-body">
            <form id="newSaleOrderForm">
                <div class="mb-3">
                    <div class="col col-md-6 mb-3 p-0">
                        <input type="text" class="form-control" id="customer-name" placeholder="Customer name" required>
                    </div>
                    <div class="col col-md-6 mb-3 p-0">
                        <input type="text" class="form-control" id="customer-phone" placeholder="Customer phone" required>
                    </div>
                    <div class="col col-md-6 mb-3 p-0">
                        <input type="text" class="form-control" id="customer-address" placeholder="Customer address" required>
                    </div>

                    <div class="mb-3 col col-md-6 mb-3 p-0 d-flex justify-content-between">
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="status-pending" name="status" value="Pending" checked>
                            <label class="form-check-label" for="status-pending">Pending</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="status-confirm" name="status" value="Confirm">
                            <label class="form-check-label" for="status-confirm">Confirm</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="status-done" name="status" value="Done">
                            <label class="form-check-label" for="status-done">Done</label>
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
                            <tr>
                                <td class="align-content-center">1</td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Product name" oninput="searchProductByName(this)">
                                </td>
                                <td class="align-content-center product-price"></td>
                                <td>
                                    <select class="form-control warehouse-select" onchange="updateShelves(this)">
                                        <option value="">Select warehouse</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control shelf-select">
                                        <option value="">Select shelf</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="w-50 form-control product-quantity" min="0" oninput="calculateTotal(this)">
                                </td>
                                <td class="align-content-center product-total"></td>
                                <td><button class="btn btn-danger delete-product-btn" onclick="deleteRow(this)">Delete</button></td>
                            </tr>
                        </tbody>
                    </table>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-secondary" onclick="addProductRow()">Add</button>
                        <h5 class="m-0">Order Total: <span id="order-total">0.00</span></h5>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="submitNewSaleOrderBtn" onclick="createSaleOrder()">Submit</button>
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
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal để chọn Warehouse -->
<div class="modal fade" id="warehouseModal" tabindex="-1" role="dialog" aria-labelledby="warehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="warehouseModalLabel">Select Warehouse</h5>
                <button type="button" class="close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <div class="modal-body">
                <ul class="list-group" id="warehousesUl">
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/newSaleOrder.js') }}"></script>
@endsection

@push('styles')
<!-- Custom styles for this page -->
<link href="{{ asset('theme/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
@endpush