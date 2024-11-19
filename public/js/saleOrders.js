document.addEventListener('DOMContentLoaded', function () {
    var table = $('#saleOrdersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/sale-orders/fetch',
            type: 'GET',
        },
        columns: [
            { data: 'id' },
            { data: 'status' },
            { data: 'name' },
            { data: 'phone' },
            { data: 'address' },
            { data: 'created_at' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    if (row.status !== 'Done' && row.status !== 'Cancel') {
                        return `<a href="/sale-orders/edit-sale-order/${row.id}" class="btn btn-secondary">Edit</a>`;
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
