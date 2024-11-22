@extends('layouts/master')

@section('content')
<div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header d-flex align-items-center justify-content-between py-3">
            <h6 class="m-0 font-weight-bold text-primary">Products Management</h6>
            <button type="button" class="btn btn-primary" id="showNewProductBtn">New Product</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="productsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Stock Quantity</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Stock Quantity</th>
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

<!-- Modal for New Product -->
<div class="modal fade" id="newProductModal" tabindex="-1" aria-labelledby="newProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newProductModalLabel">New Product</h5>
                <button type="button" class="close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <form id="newProductForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="newProductName" placeholder="Name" required>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" id="newProductDescription" placeholder="Description" required></textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="mb-3">
                            <input type="number" class="form-control" id="newProductPrice" placeholder="Price" min="0" required>
                        </div>
                        <div class="mb-3">
                            <select class="form-control" id="newProductCategory" required>
                                <option value="" disabled selected>Select Category</option>
                                @foreach($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="submitNewProductBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Edit Product -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                <button type="button" class="close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <form id="editProductForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="editProductName" placeholder="Name" required>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" id="editProductDescription" placeholder="Description" required></textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="mb-3">
                            <input type="number" class="form-control" id="editProductPrice" placeholder="Price" min="0" required>
                        </div>
                        <div class="mb-3">
                            <select class="form-control" id="editProductCategory" required>
                                <option value="" disabled selected>Select Category</option>
                                @foreach($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" id="deleteProductBtn" data-bs-dismiss="modal">Delete</button>
                    <div>
                        <button type="submit" class="btn btn-primary" id="saveEditProductBtn">Save</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Modal for Delete Confirmation -->
<div class="modal fade modal" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProductModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this product?
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteProductBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/products.js') }}"></script>
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