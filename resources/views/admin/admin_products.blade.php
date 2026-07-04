<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - All Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">
                    <i class="fa-solid fa-box-open text-blue-600 mr-2"></i>
                    Product Management
                </h2>
                <p class="text-gray-500 mt-1">Manage all products from vendors.</p>
            </div>
        </div>

        <!-- Product Table -->
        <div class="bg-white rounded-xl shadow overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr class="text-gray-700">
                        <th class="px-6 py-4 text-left">Image</th>
                        <th class="px-6 py-4 text-left">Product</th>
                        <th class="px-6 py-4 text-left">Category</th>
                        <th class="px-6 py-4 text-left">Vendor</th>
                        <th class="px-6 py-4 text-left">Price</th>
                        <th class="px-6 py-4 text-left">Stock</th>
                        <th class="px-6 py-4 text-left">Current Status</th>
                        <th class="px-6 py-4 text-left">Update Status</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>

                <tbody id="products-details" class="divide-y">
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<script>
    async function showAdminProduct(){
        const response = await fetch('api/admin/products', {
            method : 'GET',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });

        const data = await response.json();
        //console.log(data.data);
        let html = '';
        data.data.forEach(products => {
            html += `
                <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            ${products.product_images.map(image => {
                                if(image.is_primary == 1){
                                    return `
                                        <img src="/storage/${image.image_path}" class="w-16 h-16 rounded-lg object-cover">
                                    `;
                                }
                            })}
                            
                        </td>

                        <td class="px-6 py-4">
                            <h4 class="font-semibold">${products.name}</h4>
                        </td>

                        <td class="px-6 py-4">${products.category.name}</td>
                        <td class="px-6 py-4"> ${products.vendor.shop_name}</td>
                        <td class="px-6 py-4 font-semibold text-green-600"> $${products.price}</td>
                        <td class="px-6 py-4">${products.stock_quantity}</td>

                        <td class="px-6 py-4">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                                ${products.status}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <select id="product-status"
                                class="border rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="pending" ${products.status == 'pending' ? 'selected' : ''}>Pending</option>
                                <option value="active" ${products.status == 'active' ? 'selected' : ''}>Active</option>
                                <option value="rejected" ${products.status == 'rejected' ? 'selected' : ''}>Rejected</option>
                                <option value="inactive" ${products.status == 'inactive' ? 'selected' : ''}>Inactive</option>
                            </select>
                        </td>

                        <td class="px-6 py-4">
                            <button onclick="updateProductStatus(${products.id})"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                                Update
                            </button>
                        </td>
                    </tr>
            `;
        });

        document.getElementById('products-details').innerHTML = html;
    }

    showAdminProduct();

    async function updateProductStatus(productId){
        const newStatus = document.getElementById('product-status').value;
        const response = await fetch(`api/admin/products/status/update/${productId}`, {
            method : 'POST',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json',
                'Content-Type' : 'application/json'
            },

            body : JSON.stringify({
                status : newStatus
            })
        });

        showAdminProduct();
    }
</script>
