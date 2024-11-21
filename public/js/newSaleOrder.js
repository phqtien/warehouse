function searchCustomerByPhone() {
    const phone = document.getElementById("customer-phone").value;

    axios.get(`/sale-orders/search-customer-by-phone?phone=${phone}`)
        .then(response => {
            const name = response.data.name;
            const email = response.data.email;
            const address = response.data.address;
            const id = response.data.id;

            document.getElementById("customer-name").value = name;
            document.getElementById("customer-email").value = email;
            document.getElementById("customer-address").value = address;
            document.getElementById("search-customer-btn").dataset.id = id;
        })
        .catch(error => {
            console.log(error);
        });
}

function searchProductByName(input) {
    const productName = input.value;
    const row = input.closest('tr');

    axios.get(`/sale-orders/search-product-by-name?name=${productName}`)
        .then(response => {
            const product = response.data.product;
            const warehouses = response.data.warehouses;
            const shelves = response.data.shelves;

            const priceCell = row.querySelector('.product-price');
            const deleteProductBtn = row.querySelector('.delete-product-btn');
            const warehouseSelect = row.querySelector('.warehouse-select');
            const shelfSelect = row.querySelector('.shelf-select');

            if (product) {
                //Update price
                priceCell.innerText = product.price;
                deleteProductBtn.dataset.id = product.id;

                // Update warehouses
                warehouseSelect.innerHTML = '<option value="">Select warehouse</option>';
                warehouses.forEach(warehouse => {
                    const option = document.createElement('option');
                    option.value = warehouse.warehouse_id;
                    option.textContent = warehouse.warehouse_name;
                    warehouseSelect.appendChild(option);
                });

                // Save data of shelves
                shelfSelect.innerHTML = '<option value="">Select shelf</option>';
                shelfSelect.dataset.shelves = JSON.stringify(shelves);
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

function updateShelves(select) {
    const row = select.closest('tr');
    const warehouseId = select.value;
    const shelfSelect = row.querySelector('.shelf-select');

    // Get data of shelves từ dataset
    const shelves = JSON.parse(shelfSelect.dataset.shelves || '[]');
    const filteredShelves = shelves.filter(shelf => shelf.warehouse_id == warehouseId);

    // Update shelves
    shelfSelect.innerHTML = '<option value="">Select shelf</option>';
    filteredShelves.forEach(shelf => {
        const option = document.createElement('option');
        option.value = shelf.shelf_id;
        option.dataset.warehouse_id = shelf.warehouse_id;
        option.dataset.quantity = shelf.quantity;
        option.textContent = `${shelf.shelf_name} (${shelf.quantity})`;
        shelfSelect.appendChild(option);
    });
}

function calculateTotal(input) {
    const row = input.closest('tr');
    const price = parseFloat(row.querySelector('.product-price').innerText) || 0;
    const shelfSelect = row.querySelector('.shelf-select');
    const selectedShelfOption = shelfSelect.options[shelfSelect.selectedIndex];
    const maxQuantity = parseInt(selectedShelfOption.dataset.quantity) || 0;
    let quantity = parseInt(input.value) || 0;

    if (input.classList.contains('product-quantity')) {
        const minQuantity = 0;

        if (quantity < minQuantity) {
            quantity = minQuantity;
            input.value = minQuantity;
            alert("The number cannot be less than 0");
        }

        if (quantity > maxQuantity) {
            quantity = minQuantity;
            input.value = minQuantity;
            alert("The number cannot be more than stock quantity of the shelf");
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
                    <select class="form-control warehouse-select" onchange="updateShelves(this)">
                        <option value="">Select warehouse</option>
                    </select>
                </td>
                <td>
                    <select class="form-control shelf-select">
                        <option value="">Select shelf</option>
                    </select>
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

function createSaleOrder() {
    const customerId = document.getElementById("search-customer-btn").dataset.id;
    const orderStatus = document.querySelector('input[name="status"]:checked').value;
    const products = [];
    const rows = document.querySelectorAll('#product-body tr');

    rows.forEach(row => {
        const productId = row.querySelector('.delete-product-btn').dataset.id;
        const shelfSelect = row.querySelector('.shelf-select');
        const selectedShelfOption = shelfSelect.options[shelfSelect.selectedIndex];
        const shelfId = selectedShelfOption.value;
        const warehouseId = selectedShelfOption.dataset.warehouse_id;
        const productPrice = parseFloat(row.querySelector('.product-price').innerText) || 0;
        const productQuantity = parseInt(row.querySelector('input[type="number"]').value) || 0;

        if (productId && productQuantity > 0) {
            products.push({
                id: productId,
                shelf_id: shelfId,
                warehouse_id: warehouseId,
                price: productPrice,
                quantity: productQuantity,
            });
        }
    });

    axios.post('/sale-orders', {
        customer_id: customerId, 
        status: orderStatus,
        products: products
    })
        .then(response => {
            console.log(response);
            alert('Order created successfully!');
            window.location.href = '/sale-orders';

        })
        .catch(error => {
            console.log(error);
        });
}