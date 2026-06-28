<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-5">
    <div class="w-full max-w-xl bg-white rounded-xl shadow p-6">
        <h2 class="text-2xl font-bold mb-5">Edit Product</h2>

        <div id="errorBox" class="text-red-500 mb-3"></div>

        <form id="productForm">
        </form>
    </div>
</body>

</html>


<script>
    async function showProduct() {
        const productId = new URLSearchParams(window.location.search).get('id');
        const response = await fetch(`api/product/vendor/edit/${productId}`, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        console.log(data);

        document.getElementById('productForm').innerHTML = `
        <!-- Name -->
            <div class="mb-4">
                <label class="font-semibold"> Product Name</label>
                <input id="name" type="text" value="${data.products.name}" class="w-full border p-3 rounded mt-2 focus:outline-blue-400">
            </div>

            <!--Category Name -->
            <div class="mb-6">
                <label class="font-semibold">Category Name</label>
                <select id="category_id" class="w-full border p-2 rounded">
                    <option value="">Select Category</option>
                    
                    ${data.categories.map(item => 
                        `<option value="${item.id}">${item.name}</option>`
                    ).join('')}
                </select>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="font-semibold">Description</label>
                <textarea id="description" class="w-full border p-3 rounded mt-2 h-28 focus:outline-blue-400">${data.products.description}</textarea>
            </div>

            <!-- Price -->
            <div class="mb-4">
                <label class="font-semibold">Price</label>
                <input id="price" type="number" value="${data.products.price}" class="w-full border p-3 rounded mt-2 focus:outline-blue-400">
            </div>

            <!-- Stock Quantity -->
            <div class="mb-4">
                <label class="font-semibold">Stock Quantity</label>
                <input id="stock_quantity" type="number" value="${data.products.stock_quantity}" class="w-full border p-3 rounded mt-2 focus:outline-blue-400">
            </div>

            <button class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700">
                Update Product
            </button>
        `;

    }

    showProduct();

    document.getElementById('productForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        let formData = new FormData();
        formData.append('name', document.getElementById('name').value);
        formData.append('description', document.getElementById('description').value);
        formData.append('stock_quantity', document.getElementById('stock_quantity').value);
        formData.append('price', document.getElementById('price').value);
        formData.append('category_id', document.getElementById('category_id').value);

        const id = new URLSearchParams(window.location.search).get('id');
        const response = await fetch(`api/product/update/${id}`, {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            },

            body: formData
        });

        const data = await response.json();

        if(data.success){
            window.location.href = '/vendor-products';
        } else {
            document.getElementById('errorBox').innerHTML = data.message;
        }

    })
</script>
