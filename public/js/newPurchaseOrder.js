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
                    <input type="number" class="w-50 form-control product-quantity" min="1" oninput="calculateTotal(this)">
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

function calculateTotal(input) {
    const row = input.closest('tr');
    const price = parseFloat(row.querySelector('.product-price').innerText) || 0;
    let quantity = parseInt(input.value) || 0;

    if (input.classList.contains('product-quantity')) {
        const minQuantity = 1;

        if (quantity < minQuantity) {
            quantity = minQuantity;
            input.value = minQuantity;
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

function createPurchaseOrder() {
    const orderDate = document.getElementById('order-date').value;
    const orderStatus = document.querySelector('input[name="status"]:checked').value
    const products = [];
    const rows = document.querySelectorAll('#product-body tr');

    rows.forEach(row => {
        const productId = row.querySelector('.delete-product-btn').dataset.id;
        const productPrice = parseFloat(row.querySelector('.product-price').innerText) || 0;
        const productQuantity = parseInt(row.querySelector('input[type="number"]').value) || 0;

        if (productId && productQuantity > 0) {
            products.push({
                id: productId,
                price: productPrice,
                quantity: productQuantity,
            });
        }
    });

    axios.post('/purchase-orders', {
        order_date: orderDate,
        status: orderStatus,
        products: products
    })
        .then(response => {
            console.log(response);
            alert('Order created successfully!');
            window.location.href = '/purchase-orders';

        })
        .catch(error => {
            console.log(error);
        });
}