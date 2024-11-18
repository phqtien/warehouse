function filterShelves() {
    var warehouseSelect = document.getElementById('warehouseSelect');
    var selectedWarehouseId = warehouseSelect.value;

    var shelvesUl = document.getElementById('shelvesUl');
    shelvesUl.querySelectorAll('.shelf-item').forEach(function (shelfItem) {
        var shelfWarehouseId = shelfItem.getAttribute('data-warehouse-id');

        if (shelfWarehouseId === selectedWarehouseId) {
            shelfItem.style.display = '';
        } else {
            shelfItem.style.display = 'none';
        }
    });

    document.querySelectorAll('.shelf-select').forEach(function (shelfSelect) {
        shelfSelect.value = 'Select shelf';
    });
}

function openShelfModal(button) {
    window.currentShelf = button;
    $('#shelfModal').modal('show');
}

function selectShelf(shelfItem) {
    var shelfName = shelfItem.getAttribute('data-shelf-name');
    var shelfId = shelfItem.getAttribute('data-shelf-id');

    window.currentShelf.value = shelfName;
    window.currentShelf.dataset.shelf_id = shelfId;

    $('#shelfModal').modal('hide');
}

document.addEventListener('DOMContentLoaded', function () {
    updateOrderTotal();

    const deletePurchaseOrderModal = new bootstrap.Modal(document.getElementById('deletePurchaseOrderModal'));

    document.getElementById('showConfirmDeleteModalBtn').addEventListener('click', function () {
        deletePurchaseOrderModal.show();
    });
});

function addProductRow() {
    const productBody = document.getElementById('product-body');
    const rowCount = productBody.getElementsByTagName('tr').length;
    const newRow = `
            <tr>
                <td class="align-content-center">${rowCount + 1}</td>
                <td>
                    <input type="text" class="form-control" placeholder="Product name" oninput="searchProductByName(this)">
                </td>
                <td class="align-content-center product-price"></td>
                <td>
                    <input type="button" class="form-control bg-white shelf-select" value="Select shelf" readonly onclick="openShelfModal(this)">
                </td>
                <td>
                    <input type="number" class="w-50 form-control product-quantity" min="0" oninput="calculateTotal(this)">
                </td>
                <td class="align-content-center product-total"></td>
                <td><button class="btn btn-danger delete-product-btn" onclick="deleteRow(this)">Delete</button></td>
            </tr>
        `;
    productBody.insertAdjacentHTML('beforeend', newRow);
}

function deleteRow(button) {
    var row = button.parentNode.parentNode;

    row.parentNode.removeChild(row);

    var table = document.getElementById('productTable');
    var rows = table.getElementsByTagName('tr');

    for (var i = 1; i < rows.length; i++) {
        var orderCell = rows[i].getElementsByTagName('td')[0];
        orderCell.innerText = i;
    }

    updateOrderTotal();
}


function searchProductByName(input) {
    const productName = input.value;
    const row = input.closest('tr');

    axios.get(`/purchase-orders/search-product-by-name?name=${productName}`)
        .then(response => {
            const product = response.data.product;
            const priceCell = row.querySelector('.product-price');
            const deleteProductBtn = row.querySelector('.delete-product-btn');

            if (product) {
                priceCell.innerText = product.price;
                deleteProductBtn.dataset.id = product.id;
            } else {
                priceCell.innerText = '';
            }
            calculateTotal(input);
        })
        .catch(() => {
            row.querySelector('.product-price').innerText = '';
            calculateTotal(input);
        });
}

function getWarehouseId() {
    const warehouseSelect = document.getElementById('warehouseSelect');
    const selectedOption = warehouseSelect.options[warehouseSelect.selectedIndex];
    return parseInt(selectedOption.getAttribute('data-id')) || 0;
}

function calculateTotal(input) {
    const row = input.closest('tr');
    const price = parseFloat(row.querySelector('.product-price').innerText) || 0;
    let quantity = parseInt(input.value) || 0;

    if (input.classList.contains('product-quantity')) {
        const minQuantity = 0;

        if (quantity < minQuantity) {
            quantity = minQuantity;
            input.value = minQuantity;
            alert("The number cannot be less than 0");
        }
    }

    const total = price * quantity;
    row.querySelector('.product-total').innerText = total.toFixed(2);
    updateOrderTotal();
}

function updateOrderTotal() {
    const rows = document.querySelectorAll('#product-body tr');
    let grandTotal = Array.from(rows).reduce((total, row) => {
        return total + (parseFloat(row.querySelector('.product-total').innerText) || 0);
    }, 0);

    document.getElementById('order-total').innerText = grandTotal.toFixed(2);
}

function editPurchaseOrder(id) {
    const warehouseId = getWarehouseId();
    const orderDate = document.getElementById('order-date').value;
    const orderStatus = document.querySelector('input[name="status"]:checked').value
    const products = [];
    const rows = document.querySelectorAll('#product-body tr');

    rows.forEach(row => {
        const productId = row.querySelector('.delete-product-btn').dataset.id;
        const shelfId = row.querySelector('.shelf-select').dataset.shelf_id;
        const productPrice = parseFloat(row.querySelector('.product-price').innerText) || 0;
        const productQuantity = parseInt(row.querySelector('input[type="number"]').value) || 0;

        if (productId && productQuantity > 0) {
            products.push({
                id: productId,
                shelf_id: shelfId,
                price: productPrice,
                quantity: productQuantity,
            });
        }
    });

    axios.put(`/purchase-orders/${id}`, {
        warehouse_id: warehouseId,
        order_date: orderDate,
        status: orderStatus,
        products: products
    })
        .then(response => {
            console.log(response);
            alert('Order update successfully!');
            window.location.href = '/purchase-orders';

        })
        .catch(error => {
            console.log(error);
        });
}

function deletePurchaseOrder(id) {
    axios.delete(`/purchase-orders/${id}`)
        .then(response => {
            console.log(response);
            window.location.href = '/purchase-orders';
        })
        .catch(error => {
            console.log(error);
        });
}
