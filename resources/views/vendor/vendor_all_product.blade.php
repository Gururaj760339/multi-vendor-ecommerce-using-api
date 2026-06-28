<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>


<body class="bg-gray-100 p-5">

    <div class="max-w-6xl mx-auto bg-white p-6 rounded-xl shadow">

        <div class="flex justify-between mb-5">
            <h1 class="text-2xl font-bold">
                Product List
            </h1>

            <a href="/vendor-add-product" class="bg-blue-600 text-white px-5 py-2 rounded">
                + Add Product
            </a>
        </div>

        <table class="w-full border-collapse border">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border p-3">ID</th>
                    <th class="border p-3">Name</th>
                    <th class="border p-3">Description</th>
                    <th class="border p-3">Price</th>
                    <th class="border p-3">Stock Quantity</th>
                    <th class="border p-3">Product Category</th>
                    <th class="border p-3">Status</th>
                    <th class="border p-3">Action</th>
                </tr>
            </thead>

            <tbody id="allProduct">
            </tbody>
        </table>
    </div>
</body>

</html>

<script>
    async function showProduct(){
        const response = await fetch('api/product/vendor', {
            method : 'GET',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });

        const data = await response.json();
        //console.log(data.data);

        let html = '';

        data.data.forEach(product => {
            html += `
                <tr>
                    <td class="border p-3">${product.id}</td>
                    <td class="border p-3">${product.name}</td>
                    <td class="border p-3">${product.description}</td>
                    <td class="border p-3">${product.price} Tk</td>
                    <td class="border p-3">${product.stock_quantity}</td>
                    <td class="border p-3">${product.category.name}</td>
                    <td class="border p-3">
                        <span class="bg-green-500 text-white px-3 py-1 rounded">
                            ${product.status}
                        </span>
                    </td>

                    <td class="border p-3">
                        <div class="flex justify-between gap-3">
                            <a onclick="editProduct(${product.id})" class="bg-yellow-500 text-white px-3 py-2 rounded">
                                Edit
                            </a>

                            <a onclick="deleteProduct(${product.id})" class="bg-red-600 text-white px-3 py-2 rounded">
                                Delete
                            </a>
                        </div>
                    </td>
                </tr>
            `;
        });

        document.getElementById('allProduct').innerHTML = html;

    }
    showProduct();

    function editProduct(productId){
        window.location.href = `/vendor-edit-product?id=${productId}`
    }

    async function deleteProduct(id){
        const response = await fetch(`api/product/delete/${id}`, {
            method : 'DELETE',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });

        const data = await response.json();

        if(data.success){
            showProduct();
        }
    }
</script>
