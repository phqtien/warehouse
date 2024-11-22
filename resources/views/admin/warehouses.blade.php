@extends('layouts/master')

@section('content')
<div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header d-flex align-items-center justify-content-between py-3">
            <h6 class="m-0 font-weight-bold text-primary">Warehouses Management</h6>
            <button type="button" class="btn btn-primary" id="showNewWarehouseBtn">New Warehouse</button>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="warehousesTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Address</th>
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

<!-- Modal for New Warehouse -->
<div class="modal fade" id="newWarehouseModal" tabindex="-1" aria-labelledby="newWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newWarehouseModalLabel">New Warehouse</h5>
                <button type="button" class="close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <form id="newWarehouseForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="newWarehouseName" placeholder="Name" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="newWarehouseAddress" placeholder="Address" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="submitNewWarehouseBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Edit Warehouse -->
<div class="modal fade" id="editWarehouseModal" tabindex="-1" aria-labelledby="editWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editWarehouseModalLabel">Edit Warehouse</h5>
                <button type="button" class="close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <form id="editWarehouseForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="editWarehouseName" placeholder="Name" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="editWarehouseAddress" placeholder="Address" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" id="deleteWarehouseBtn" data-bs-dismiss="modal">Delete</button>
                    <div>
                        <button type="submit" class="btn btn-primary" id="saveEditWarehouseBtn">Save</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Modal for Delete Confirmation -->
<div class="modal fade modal" id="deleteWarehouseModal" tabindex="-1" aria-labelledby="deleteWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteWarehouseModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this warehouse?
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteWarehouseBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/warehouses.js') }}"></script>
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