document.addEventListener('DOMContentLoaded', function () {
    var table = $('#purchaseOrderDetailsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/purchase-order-details/fetch',
            type: 'GET',
            data: function (d) {
                d.status = document.getElementById('statusFilter').value;
            }
        },
        columns: [
            { data: 'id' },
            { data: 'order_date' },
            { data: 'status' },
            { data: 'product_name' },
            { data: 'price' },
            { data: 'quantity' },
            { data: 'created_at' },
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

    document.getElementById('statusFilter').addEventListener('change', function () {
        table.ajax.reload();
    });
});
