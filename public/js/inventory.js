function filterWarehouses() {
    var warehouseSelect = document.getElementById('warehouseSelect');
    var selectedWarehouseId = warehouseSelect.value;

    var shelfSelect = document.getElementById('shelfSelect');
    shelfSelect.querySelectorAll('.shelf-item').forEach(function (shelfItem) {
        var shelfWarehouseId = shelfItem.getAttribute('data-warehouse-id');

        if (shelfWarehouseId === selectedWarehouseId) {
            shelfItem.hidden = false;
        } else {
            shelfItem.hidden = true;
        }
    });

    document.getElementById('shelfSelect').value = '';
}

document.addEventListener('DOMContentLoaded', function () {
    var table = $('#inventoriesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/inventories/fetch',
            type: 'GET',
            data: function (d) {
                d.warehouse = document.getElementById('warehouseSelect').value;
                d.shelf = document.getElementById('shelfSelect').value;
            }
        },
        columns: [
            { data: 'warehouse_name' },
            { data: 'shelf_name' },
            { data: 'product_name' },
            { data: 'stock_quantity' },
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

    document.getElementById('warehouseSelect').addEventListener('change', function () {
        table.ajax.reload();
    });

    document.getElementById('shelfSelect').addEventListener('change', function () {
        table.ajax.reload();
    });
});
