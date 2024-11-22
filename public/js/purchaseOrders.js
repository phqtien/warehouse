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
});
