<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product Image</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow">
        <h1 class="text-2xl font-bold mb-6">Add Product Image</h1>

        <div id="errorBox" class="text-red-500 mb-3"></div>

        <form id="productImageFrom" enctype="multipart/form-data">
            <!-- Product Name -->
            <div class="mb-5">
                <label class="block font-semibold mb-2">Product Name</label>

                <select class="w-full border p-3 rounded-lg focus:outline-blue-400" id="productName">
                </select>
            </div>

            <!-- Image -->
            <div class="mb-5">
                <label class="block font-semibold mb-2">Image</label>
                <input id="image_path" type="file" class="w-full border p-3 rounded-lg">
            </div>

            <!-- Default Checkbox -->
            <div class="mb-5 flex items-center gap-3">
                <input id="is_primary" type="checkbox" class="w-5 h-5">
                <label class="font-semibold"> Make Default Image</label>
            </div>

            <!-- Button -->
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                Save Image
            </button>
        </form>
    </div>
</body>

</html>

<script>
    async function showProduct() {
        const response = await fetch('api/product/vendor', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        console.log(data);

        let html = '';
        data.data.forEach(product => {
            html += `<option value="${product.id}">${product.name}</option>`
        })
        document.getElementById('productName').innerHTML = html;
    }

    showProduct();

    document.getElementById('productImageFrom').addEventListener('submit', async function(e){
        e.preventDefault();
        const formData = new FormData();
        formData.append('product_id', document.getElementById('productName').value);
        formData.append('image_path', document.getElementById('image_path').files[0]);
        formData.append('is_primary', document.getElementById('is_primary').checked ? 1 : 0);

        const response = await fetch('api/product/images', {
            method : 'POST',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            },
            body : formData
        });

        const data = await response.json();

        if(data.success){
            window.location.href = '/vendor-products-images';
        } else {
            document.getElementById('errorBox').innerHTML = data.message;
        }
    })
</script>
