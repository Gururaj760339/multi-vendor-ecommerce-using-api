<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Wishlist</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>


<body class="bg-gray-100">
    @include('customer/navbar')

    <!-- Wishlist -->
    <section class="max-w-7xl mx-auto p-6">
        <h2 class="text-3xl font-bold mb-6">❤️ My Wishlist</h2>

        <div id="mainProduct" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        </div>
    </section>
</body>

</html>
<script src="{{ asset('storage/js/navbar.js') }}"></script>
<script>
    updateAuthUI();
    showWishlitValue();

    async function showWishlistProduct(){
        const response = await fetch('api/wishlist', {
            method : 'GET',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });

        const data = await response.json();

        //console.log(data.wishlist);
        let html = '';
        data.wishlist.forEach(products => {
            html += `
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="h-48 bg-gray-200">

                    ${products.product.product_images.map(productImage => {
                        if(productImage.is_primary == 1){
                            return `
                                <img src="/storage/${productImage.image_path}"
                                class="w-full h-full object-cover">
                            `;
                        }
                    }).join('')}
                </div>

                <div class="p-4">
                    <h3 class="font-bold text-lg">${products.product.name}</h3>
                    <p class="text-blue-600 font-bold mt-2">${products.product.price}</p>

                    <div class="flex gap-2 mt-4">
                        <button class="bg-yellow-400 px-4 py-2 rounded-lg hover:bg-yellow-500">Add Cart</button>
                        <button onclick="deleteWishlistProduct(${products.id})" class="bg-red-500 text-white px-4 py-2 rounded-lg">Remove</button>
                    </div>
                </div>
            </div>
            
            `;
        });

        document.getElementById('mainProduct').innerHTML = html;
    }

    showWishlistProduct();

    async function deleteWishlistProduct(id){
        const response = await fetch(`api/wishlist/delete/${id}`, {
            method : 'DELETE',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token')
            }
        });

        showWishlistProduct();
    }
</script>