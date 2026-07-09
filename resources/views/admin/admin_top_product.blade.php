<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Selling Products</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="p-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Top Selling Products</h1>
                <p class="text-gray-500 mt-1">Best performing products by sales and revenue.</p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-5 border-b">
                <h2 class="text-xl font-semibold"> Top 10 Products</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-gray-600 uppercase text-sm">
                            <th class="px-6 py-4">#</th>
                            <th class="px-6 py-4">Product</th>
                            <th class="px-6 py-4">Vendor</th>
                            <th class="px-6 py-4">Category</th>
                            <th class="px-6 py-4 text-center">Sold</th>
                            <th class="px-6 py-4 text-center">Revenue</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>

                    <tbody id="products-details" class="divide-y">
                        <!-- Row -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    async function showProduct(){
        const response = await fetch('api/admin/top/product', {
            method : 'GET',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });

        const data = await response.json();

        console.log(data);
        let html = '';
        data.data.forEach(products => {
            html += `
                <tr class="hover:bg-gray-50">
                            <td class="px-6 py-5 font-semibold">${products.product.id}</td>

                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    ${products.product.product_images.map(productImage => {
                                        if(productImage.is_primary == 1){
                                            return `
                                                 <img src="/storage/${productImage.image_path}" class="w-16 h-16 rounded-lg object-cover">
                                            `;
                                        }
                                    }).join('')}
                                   
                                    <div>
                                        <h3 class="font-semibold text-gray-800">${products.product.name}</h3>
                                        <p class="text-sm text-gray-500">Product ID : #${products.product.id}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-5">${products.product.vendor.shop_name}</td>
                            <td class="px-6 py-5">${products.product.category.name}</td>
                            <td class="px-6 py-5 text-center font-bold">${products.total_quantity}</td>
                            <td class="px-6 py-5 text-center font-bold text-green-600">$${products.total_price}</td>
                            <td class="px-6 py-5 text-center">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                    ${products.product.status}
                                </span>
                            </td>
                        </tr>
            `;
        });

        document.getElementById('products-details').innerHTML = html;
    }

    showProduct();
</script>
