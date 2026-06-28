<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Image List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto bg-white rounded-xl shadow p-6">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">
                Product Image List
            </h1>

            <a href="/vendor-add-product-image" class="bg-blue-600 text-white px-5 py-3 rounded-lg hover:bg-blue-700">
                + Add Image
            </a>

        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-3">ID</th>
                        <th class="border p-3">Product Name</th>
                        <th class="border p-3">Image</th>
                        <th class="border p-3">Default</th>
                        <th class="border p-3">Action</th>
                    </tr>
                </thead>

                <tbody id="imageData">
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<script>
    async function showProductImage(){
        const response = await fetch('api/products/images', {
            method : 'GET',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });

        const data = await response.json();
        //console.log(data.data);
        let html = '';

        data.data.forEach(images => {
            html += `
                <tr>
                    <td class="border p-3 text-center">${images.id}</td>

                    <td class="border p-3">
                        <div class="flex items-center gap-3">
                        ${images.product.name}
                        </div>
                    </td>

                    <td class="border p-2">
                        <img src="storage/${images.image_path}" class="w-24 h-20 object-cover rounded">
                    </td>

                    <td class="border p-3 text-center">
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded">
                            ${images.is_primary ? 'Yes' : 'No'}
                        </span>
                    </td>

                    <td class="border p-3 text-center">
                        <button onclick="deleteImage(${images.id})" class="bg-red-600 text-white px-4 py-2 rounded">
                            Delete
                        </button>
                    </td>
                </tr>
                `;
        });
        
        document.getElementById('imageData').innerHTML = html;

    }

    showProductImage();

    async function deleteImage(id){
        const response = await fetch(`api/product/images/delete/${id}`, {
            method : 'DELETE',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });

        showProductImage();
        
    }
</script>
