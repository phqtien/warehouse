document.addEventListener('DOMContentLoaded', function () {
    var table = $('#purchaseOrdersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/purchase-orders/fetch',
            type: 'GET',
        },
        columns: [
            { data: 'id' },
            { data: 'order_date' },
            { data: 'status' },
            { data: 'created_at' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    if (row.status !== 'Done') {
                        return `<a href="/purchase-orders/edit-purchase-order/${row.id}" class="btn btn-secondary">Edit</a>`;
                    } else {
                        return '';
                    }
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

    // Set data for edit and delete modal
    const editPurchaseOrderModal = new bootstrap.Modal(document.getElementById('editPurchaseOrderModal'));

    document.getElementsByTagName('tbody')[0].addEventListener('click', function (event) {
        if (event.target.closest('.editPurchaseOrderBtn')) {
            var row = table.row(event.target.closest('tr'));
            var data = row.data();
            document.getElementById('edit-order-date').value = data.order_date;
            document.getElementById('saveEditPurchaseOrderBtn').dataset.id = data.id;
            if (data.status === 'Pending') {
                document.getElementById('status-pending').checked = true;
            } else if (data.status === 'Confirm') {
                document.getElementById('status-confirm').checked = true;
            } else if (data.status === 'Done') {
                document.getElementById('status-done').checked = true;
            }
        }
    });

    // Edit PurchaseOrder
    document.getElementById('saveEditPurchaseOrderBtn').addEventListener('click', function (event) {
        event.preventDefault();

        var editPurchaseOrderData = {
            name: document.getElementById('editPurchaseOrderName').value,
            description: document.getElementById('editPurchaseOrderDescription').value,
            price: document.getElementById('editPurchaseOrderPrice').value,
            category_id: document.getElementById('editPurchaseOrderCategory').value
        };

        var productId = document.getElementById('saveEditPurchaseOrderBtn').dataset.id;

        axios.put(`/products/${productId}`, editPurchaseOrderData)
            .then(response => {
                editPurchaseOrderModal.hide();
                console.log(response);
                refreshTable();
            })
            .catch(error => console.error(error));
    });

    document.getElementById('confirmDeletePurchaseOrderBtn').addEventListener('click', function () {
        var productId = document.getElementById('saveEditPurchaseOrderBtn').dataset.id;

        axios.delete(`/products/${productId}`)
            .then(response => {
                deletePurchaseOrderModal.hide();
                console.log(response);
                refreshTable();
            })
            .catch(error => console.error(error));
    });
});
