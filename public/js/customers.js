document.addEventListener('DOMContentLoaded', function () {
    var table = $('#customersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/customers/fetch',
            type: 'GET',
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'phone' },
            { data: 'address' },
            { data: 'email' },
            { data: 'created_at' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function () {
                    return '<button class="btn btn-secondary editCustomerBtn">Edit</button>';
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

    // New Customer
    const newCustomerModal = new bootstrap.Modal(document.getElementById('newCustomerModal'));

    document.getElementById('showNewCustomerBtn').addEventListener('click', function (event) {
        newCustomerModal.show()
        document.getElementById('newCustomerForm').reset();
    });

    document.getElementById('newCustomerForm').addEventListener('submit', function (event) {
        event.preventDefault();

        var newCustomerData = {
            name: document.getElementById('newCustomerName').value,
            phone: document.getElementById('newCustomerPhone').value,
            email: document.getElementById('newCustomerEmail').value,
            address: document.getElementById('newCustomerAddress').value
        };

        axios.post('/customers', newCustomerData)
            .then(response => {
                newCustomerModal.hide()
                console.log(response);
                refreshTable();
            })
            .catch(error => console.error(error));
    });

    // Set data for edit and delete modal
    const editCustomerModal = new bootstrap.Modal(document.getElementById('editCustomerModal'));
    const deleteCustomerModal = new bootstrap.Modal(document.getElementById('deleteCustomerModal'));

    document.getElementsByTagName('tbody')[0].addEventListener('click', function (event) {
        if (event.target.closest('.editCustomerBtn')) {
            var row = table.row(event.target.closest('tr'));
            var data = row.data();
            document.getElementById('editCustomerName').value = data.name;
            document.getElementById('editCustomerPhone').value = data.phone;
            document.getElementById('editCustomerEmail').value = data.email;
            document.getElementById('editCustomerAddress').value = data.address;
            document.getElementById('saveEditCustomerBtn').dataset.id = data.id;
            editCustomerModal.show()
        }
    });

    // Edit Customer
    document.getElementById('saveEditCustomerBtn').addEventListener('click', function (event) {
        event.preventDefault();

        var editCustomerData = {
            name: document.getElementById('editCustomerName').value,
            phone: document.getElementById('editCustomerPhone').value,
            email: document.getElementById('editCustomerEmail').value,
            address: document.getElementById('editCustomerAddress').value
        };

        var customerId = document.getElementById('saveEditCustomerBtn').dataset.id;

        axios.put(`/customers/${customerId}`, editCustomerData)
            .then(response => {
                editCustomerModal.hide();
                console.log(response);
                refreshTable();
            })
            .catch(error => console.error(error));
    });

    // Delete Customer
    document.getElementById('deleteCustomerBtn').addEventListener('click', function () {
        deleteCustomerModal.show();
    });

    document.getElementById('confirmDeleteCustomerBtn').addEventListener('click', function () {
        var customerId = document.getElementById('saveEditCustomerBtn').dataset.id;

        axios.delete(`/customers/${customerId}`)
            .then(response => {
                deleteCustomerModal.hide();
                console.log(response);
                refreshTable();
            })
            .catch(error => console.error(error));
    });
});
