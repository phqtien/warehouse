document.addEventListener('DOMContentLoaded', function () {
    var table = $('#saleOrdersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/sale-orders/fetch',
            type: 'GET',
            data: function (d) {
                d.status = document.getElementById('statusFilter').value;
            }
        },
        columns: [
            { data: 'id' },
            { data: 'status' },
            { data: 'name' },
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

    document.getElementById('statusFilter').addEventListener('change', function () {
        table.ajax.reload();
    });

    document.getElementById('export-btn').addEventListener('click', function () {
        var status = document.getElementById('statusFilter').value;
    
        axios.get('/sale-orders/export', {
            params: {
                status: status
            },
            responseType: 'blob'
        })
        .then(function (response) {
            var link = document.createElement('a');
            var url = window.URL.createObjectURL(new Blob([response.data]));
            link.href = url;
            link.download = 'saleOrders.xlsx';
            link.click();
        })
        .catch(function (error) {
            console.log('Error:', error);
        });
    });    
});
