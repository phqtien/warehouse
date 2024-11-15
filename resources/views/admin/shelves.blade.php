@extends('layouts/master')

@section('content')
<div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header d-flex align-items-center justify-content-between py-3">
            <h6 class="m-0 font-weight-bold text-primary">Shelfs Management</h6>
            <button type="button" class="btn btn-primary" id="showNewShelfBtn">New Shelf</button>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="shelvesTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Warehouse</th>
                            <th>Name</th>
                            <th>Capacity</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                        <th>ID</th>
                            <th>Warehouse</th>
                            <th>Name</th>
                            <th>Capacity</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Modal for New Shelf -->
<div class="modal fade" id="newShelfModal" tabindex="-1" aria-labelledby="newShelfModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newShelfModalLabel">New Shelf</h5>
                <button type="button" class="close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <form id="newShelfForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="newShelfName" placeholder="Name" required>
                    </div>
                    <div class="mb-3 d-flex justify-content-between">
                        <div>
                            <input type="number" class="form-control" id="newShelfCapacity" placeholder="Capacity" min="0" required>
                        </div>
                        <div>
                            <select class="form-control" id="newShelfWarehouse" required>
                                <option value="" disabled selected>Select Category</option>
                                @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="submitNewShelfBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Edit Shelf -->
<div class="modal fade" id="editShelfModal" tabindex="-1" aria-labelledby="editShelfModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editShelfModalLabel">Edit Shelf</h5>
                <button type="button" class="close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <form id="editShelfForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="editShelfName" placeholder="Name" required>
                    </div>
                    <div class="mb-3 d-flex justify-content-between">
                        <div>
                            <input type="number" class="form-control" id="editShelfCapacity" placeholder="Capacity" min="0" required>
                        </div>
                        <div>
                            <select class="form-control" id="editShelfWarehouse" required>
                                <option value="" disabled selected>Select Category</option>
                                @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" id="deleteShelfBtn" data-bs-dismiss="modal">Delete</button>
                    <div>
                        <button type="submit" class="btn btn-primary" id="saveEditShelfBtn">Save</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Modal for Delete Confirmation -->
<div class="modal fade modal" id="deleteShelfModal" tabindex="-1" aria-labelledby="deleteShelfModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteShelfModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this shelf?
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteShelfBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/shelves.js') }}"></script>
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