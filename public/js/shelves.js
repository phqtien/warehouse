document.addEventListener('DOMContentLoaded', function () {
    var table = $('#shelvesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/shelves/fetch',
            type: 'GET',
        },
        columns: [
            { data: 'id' },
            { data: 'warehouse_name' },
            { data: 'name' },
            { data: 'capacity' },
            { data: 'created_at' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function () {
                    return '<button class="btn btn-secondary editShelfBtn">Edit</button>';
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

    // New Shelf
    const newShelfModal = new bootstrap.Modal(document.getElementById('newShelfModal'));

    document.getElementById('showNewShelfBtn').addEventListener('click', function (event) {
        newShelfModal.show()
        document.getElementById('newShelfForm').reset();
    });

    document.getElementById('newShelfForm').addEventListener('submit', function (event) {
        event.preventDefault();

        var newShelfData = {
            warehouse_id: document.getElementById('newShelfWarehouse').value,
            name: document.getElementById('newShelfName').value,
            capacity: document.getElementById('newShelfCapacity').value,
        };

        axios.post('/shelves', newShelfData)
            .then(response => {
                newShelfModal.hide()
                console.log(response);
                refreshTable();
            })
            .catch(error => console.error(error));
    });

    // Set data for edit and delete modal
    const editShelfModal = new bootstrap.Modal(document.getElementById('editShelfModal'));
    const deleteShelfModal = new bootstrap.Modal(document.getElementById('deleteShelfModal'));

    document.getElementsByTagName('tbody')[0].addEventListener('click', function (event) {
        if (event.target.closest('.editShelfBtn')) {
            var row = table.row(event.target.closest('tr'));
            var data = row.data();
            document.getElementById('editShelfWarehouse').value = data.warehouse_id;
            document.getElementById('editShelfName').value = data.name;
            document.getElementById('editShelfCapacity').value = data.capacity;
            document.getElementById('saveEditShelfBtn').dataset.id = data.id;
            editShelfModal.show()
        }
    });

    // Edit Shelf
    document.getElementById('saveEditShelfBtn').addEventListener('click', function (event) {
        event.preventDefault();

        var editShelfData = {
            warehouse_id: document.getElementById('editShelfWarehouse').value,
            name: document.getElementById('editShelfName').value,
            capacity: document.getElementById('editShelfCapacity').value,
        };

        var shelfId = document.getElementById('saveEditShelfBtn').dataset.id;

        axios.put(`/shelves/${shelfId}`, editShelfData)
            .then(response => {
                editShelfModal.hide();
                console.log(response);
                refreshTable();
            })
            .catch(error => console.error(error));
    });

    // Delete Shelf
    document.getElementById('deleteShelfBtn').addEventListener('click', function () {
        deleteShelfModal.show();
    });

    document.getElementById('confirmDeleteShelfBtn').addEventListener('click', function () {
        var shelfId = document.getElementById('saveEditShelfBtn').dataset.id;

        axios.delete(`/shelves/${shelfId}`)
            .then(response => {
                deleteShelfModal.hide();
                console.log(response);
                refreshTable();
            })
            .catch(error => console.error(error));
    });
});
