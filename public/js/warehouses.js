document.addEventListener('DOMContentLoaded', function () {
    var table = $('#warehousesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/warehouses/fetch',
            type: 'GET',
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'address' },
            { data: 'created_at' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function () {
                    return '<button class="btn btn-secondary editWarehouseBtn">Edit</button>';
                }
            },
        ],
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        dom: "<'row mb-3'<'col-sm-6'l><'col-sm-6'f>>" +
            "<'row'<'col-sm-12't>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>"
    });

    function refreshTable() {
        table.draw();
    }

    // New Warehouse
    const newWarehouseModal = new bootstrap.Modal(document.getElementById('newWarehouseModal'));

    document.getElementById('showNewWarehouseBtn').addEventListener('click', function (event) {
        newWarehouseModal.show()
        document.getElementById('newWarehouseForm').reset();
    });

    document.getElementById('newWarehouseForm').addEventListener('submit', function (event) {
        event.preventDefault();

        var newWarehouseData = {
            name: document.getElementById('newWarehouseName').value,
            address: document.getElementById('newWarehouseAddress').value,
        };

        axios.post('/warehouses', newWarehouseData)
            .then(response => {
                newWarehouseModal.hide()
                console.log(response);
                refreshTable();
            })
            .catch(error => console.error(error));
    });

    // Set data for edit and delete modal
    const editWarehouseModal = new bootstrap.Modal(document.getElementById('editWarehouseModal'));
    const deleteWarehouseModal = new bootstrap.Modal(document.getElementById('deleteWarehouseModal'));

    document.getElementsByTagName('tbody')[0].addEventListener('click', function (event) {
        if (event.target.closest('.editWarehouseBtn')) {
            var row = table.row(event.target.closest('tr'));
            var data = row.data();
            document.getElementById('editWarehouseName').value = data.name;
            document.getElementById('editWarehouseAddress').value = data.address;
            document.getElementById('saveEditWarehouseBtn').dataset.id = data.id;
            editWarehouseModal.show()
        }
    });

    // Edit Warehouse
    document.getElementById('saveEditWarehouseBtn').addEventListener('click', function (event) {
        event.preventDefault();

        var editWarehouseData = {
            name: document.getElementById('editWarehouseName').value,
            address: document.getElementById('editWarehouseAddress').value,
        };

        var warehouseId = document.getElementById('saveEditWarehouseBtn').dataset.id;

        axios.put(`/warehouses/${warehouseId}`, editWarehouseData)
            .then(response => {
                editWarehouseModal.hide();
                console.log(response);
                refreshTable();
            })
            .catch(error => console.error(error));
    });

    // Delete Warehouse
    document.getElementById('deleteWarehouseBtn').addEventListener('click', function () {
        deleteWarehouseModal.show();
    });

    document.getElementById('confirmDeleteWarehouseBtn').addEventListener('click', function () {
        var warehouseId = document.getElementById('saveEditWarehouseBtn').dataset.id;

        axios.delete(`/warehouses/${warehouseId}`)
            .then(response => {
                deleteWarehouseModal.hide();
                console.log(response);
                refreshTable();
            })
            .catch(error => console.error(error));
    });
});
