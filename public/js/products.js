document.addEventListener('DOMContentLoaded', function () {
    var table = $('#productsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/products/fetch',
            type: 'GET',
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'description' },
            { data: 'price' },
            { data: 'category' },
            { data: 'created_at' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function () {
                    return '<button class="btn btn-secondary editProductBtn">Edit</button>';
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

    // New Product
    const newProductModal = new bootstrap.Modal(document.getElementById('newProductModal'));

    document.getElementById('showNewProductBtn').addEventListener('click', function (event) {
        newProductModal.show()
        document.getElementById('newProductForm').reset();
    });

    document.getElementById('newProductForm').addEventListener('submit', function (event) {
        event.preventDefault();

        var newProductData = {
            name: document.getElementById('newProductName').value,
            description: document.getElementById('newProductDescription').value,
            price: document.getElementById('newProductPrice').value,
            category_id: document.getElementById('newProductCategory').value
        };

        axios.post('/products', newProductData)
            .then(response => {
                newProductModal.hide()
                console.log(response);
                refreshTable();
            })
            .catch(error => console.error(error));
    });

    // Set data for edit and delete modal
    const editProductModal = new bootstrap.Modal(document.getElementById('editProductModal'));
    const deleteProductModal = new bootstrap.Modal(document.getElementById('deleteProductModal'));

    document.getElementsByTagName('tbody')[0].addEventListener('click', function (event) {
        if (event.target.closest('.editProductBtn')) {
            var row = table.row(event.target.closest('tr'));
            var data = row.data();
            document.getElementById('editProductName').value = data.name;
            document.getElementById('editProductPrice').value = data.price;
            document.getElementById('editProductDescription').value = data.description;
            document.getElementById('editProductCategory').value = data.category_id;
            document.getElementById('saveEditProductBtn').dataset.id = data.id;
            editProductModal.show()
        }
    });

    // Edit Product
    document.getElementById('saveEditProductBtn').addEventListener('click', function (event) {
        event.preventDefault();

        var editProductData = {
            name: document.getElementById('editProductName').value,
            description: document.getElementById('editProductDescription').value,
            price: document.getElementById('editProductPrice').value,
            category_id: document.getElementById('editProductCategory').value
        };

        var productId = document.getElementById('saveEditProductBtn').dataset.id;

        axios.put(`/products/${productId}`, editProductData)
            .then(response => {
                editProductModal.hide();
                console.log(response);
                refreshTable();
            })
            .catch(error => console.error(error));
    });

    // Delete Product
    document.getElementById('deleteProductBtn').addEventListener('click', function () {
        deleteProductModal.show();
    });

    document.getElementById('confirmDeleteProductBtn').addEventListener('click', function () {
        var productId = document.getElementById('saveEditProductBtn').dataset.id;

        axios.delete(`/products/${productId}`)
            .then(response => {
                deleteProductModal.hide();
                console.log(response);
                refreshTable();
            })
            .catch(error => console.error(error));
    });
});
