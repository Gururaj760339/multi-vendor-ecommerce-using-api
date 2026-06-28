<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Product</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>


<body class="bg-gray-100 min-h-screen flex items-center justify-center p-5">
    <div class="w-full max-w-xl bg-white rounded-xl shadow p-6">
        <h2 class="text-2xl font-bold mb-5">
            Add Product
        </h2>

        <div id="errorBox" class="text-red-500 mb-3"></div>

        <form id="addProductForm">
            <!-- Name -->
            <div class="mb-4">
                <label class="font-semibold">
                    Product Name
                </label>

                <input id="name" type="text" placeholder="Enter product name"
                    class="w-full border p-3 rounded mt-2 focus:outline-blue-400">
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="font-semibold">
                    Description
                </label>

                <textarea id="description" placeholder="Enter product description"
                    class="w-full border p-3 rounded mt-2 h-28 focus:outline-blue-400"></textarea>
            </div>

            <!-- Category -->
            <div class="mb-4">
                <label class="font-semibold"> Category </label>

                <select id="category_id" class="w-full border p-3 rounded mt-2 focus:outline-blue-400">
                </select>
            </div>

            <!-- Price -->
            <div class="mb-4">
                <label class="font-semibold">
                    Price
                </label>

                <input id="price" type="number" placeholder="Enter price"
                    class="w-full border p-3 rounded mt-2 focus:outline-blue-400">
            </div>

            <!-- Stock Quantity -->
            <div class="mb-4">
                <label class="font-semibold">
                    Stock Quantity
                </label>

                <input id="stock_quantity" type="number" placeholder="Enter stock quantity"
                    class="w-full border p-3 rounded mt-2 focus:outline-blue-400">
            </div>

            <button class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700">
                Add Product
            </button>
        </form>
    </div>
</body>

</html>

<script>
    async function showCategory(){
        const response = await fetch('api/categories', {
            method : 'GET',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });
        
        const data = await response.json();
        //console.log(data);

        document.getElementById('category_id').innerHTML = `
            ${data.data.map(category => `
                <option value="${category.id}">${category.name}</option>
            `).join('')}
        `;
    }

    showCategory();

    document.getElementById('addProductForm').addEventListener('submit', async function(e){
        e.preventDefault();

        const formData = new FormData();
        formData.append('name', document.getElementById('name').value);
        formData.append('description', document.getElementById('description').value);
        formData.append('price', document.getElementById('price').value);
        formData.append('stock_quantity', document.getElementById('stock_quantity').value);
        formData.append('category_id', document.getElementById('category_id').value);

        const response = await fetch('api/product/store', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept' : 'application/json'
            },
            body : formData
        });

        const data = await  response.json();

        if(data.success){
            window.location.href = '/vendor-products';
        } else {
            document.getElementById('errorBox').innerHTML = data.message
        }
    });
</script>
