<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ShopMart - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    @include('customer.navbar')
    <!-- HERO -->
    <section class="bg-blue-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold">
                Discover Products from Multiple Vendors
            </h2>
            <p class="mt-2 text-blue-100">
                Best deals, fast delivery, trusted sellers
            </p>
        </div>
    </section>

    <!-- PRODUCTS -->
    <section class="max-w-7xl mx-auto p-6">

        <div class="flex gap-3 mt-3 md:mt-0">

            <input type="text" placeholder="Search product..."
                class="border px-4 py-2 rounded-lg w-60 focus:outline-none focus:ring-2 focus:ring-blue-500">

            <select class="border px-4 py-2 rounded-lg">
                <option>All Categories</option>
                <option>Electronics</option>
                <option>Fashion</option>
                <option>Home</option>
            </select>

        </div>

        <h2 class="text-2xl font-bold mt-4 mb-6">🔥 Trending Products</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">

            <!-- PRODUCT CARD -->
            <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden relative group">

                <!-- Product Image -->
                <div class="h-40 bg-gray-200 relative flex items-center justify-center">

                    <!-- Wishlist -->
                    <button
                        class="absolute top-3 right-3 bg-white w-10 h-10 rounded-full shadow 
                   flex items-center justify-center text-gray-500 
                   hover:text-red-600 hover:bg-red-50 transition">

                        ♡
                    </button>

                    <img src="" class="h-full object-cover">

                </div>


                <div class="p-4">

                    <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded">
                        Vendor: Tech Store
                    </span>

                    <h3 class="font-semibold mt-2">
                        Bluetooth Speaker
                    </h3>


                    <div class="flex justify-between items-center mt-2">

                        <p class="text-blue-600 font-bold">
                            $30
                        </p>

                        <span class="text-yellow-500 text-sm">
                            ⭐ 4.6
                        </span>

                    </div>


                    <button
                        class="w-full mt-3 bg-yellow-400 text-black py-2 rounded-lg 
                       hover:bg-yellow-500 font-semibold">
                        Add to Cart
                    </button>


                </div>

            </div>

        </div>
    </section>

    <!-- ALL PRODUCTS SECTION -->
    <section class="max-w-7xl mx-auto p-6 mt-10">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">

            <h2 class="text-2xl font-bold">🛒 All Products</h2>

            <!-- SEARCH + FILTER -->


        </div>

        <!-- PRODUCT GRID -->
        <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <!-- PRODUCT CARD -->
        </div>

    </section>

    <!-- FOOTER -->
    <footer class="bg-white mt-10 border-t">
        <div class="max-w-7xl mx-auto p-6 text-center text-gray-500">
            © 2026 ShopMart. All rights reserved.
        </div>
    </footer>

</body>

</html>

<script src="{{ asset('storage/js/navbar.js') }}"></script>

<script>
    updateAuthUI();
    async function showProducts() {
        const response = await fetch('api/product/customer', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'applicaton/json'
            }
        });

        const data = await response.json();
        console.log(data.data);
        let html = '';

        data.data.forEach(products => {
            html += `
            <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden relative group">

                <!-- Product Image -->
                <div class="h-40 bg-gray-200 relative flex items-center justify-center">

                    <!-- Wishlist -->
                    <button onclick="addWishlist(${products.id})"
                        class="absolute top-3 right-3 bg-white w-10 h-10 rounded-full shadow 
                        flex items-center justify-center text-gray-500 
                        hover:text-red-600 hover:bg-red-50 transition">
                        ♡
                    </button>

                    ${products.product_images.map(product => {
                        if(product.is_primary == 1){
                            return `
                                <img onclick="singleProductPage('${products.slug}')" src="/storage/${product.image_path}" class="h-full object-cover">
                            `;
                        }
                        
                    }).join('')}

                </div>


                <div class="p-4">

                    <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded">
                        Vendor: ${products.vendor.user.name}
                    </span>

                    <h3 class="font-semibold mt-2">
                        ${products.name}
                    </h3>


                    <div class="flex justify-between items-center mt-2">

                        <p class="text-blue-600 font-bold">
                            ${products.price}
                        </p>

                        <span class="text-yellow-500 text-sm">
                            ⭐ 4.6 static acha
                        </span>

                    </div>


                    <div class="flex items-center justify-between mt-3">

                        <div class="flex items-center border rounded-lg overflow-hidden">

                            <button onclick="decreaseQty(${products.id})"
                                class="px-3 py-2 bg-gray-200 hover:bg-gray-300">
                                -
                            </button>

                            <span id="qty-${products.id}" 
                                class="px-4 font-semibold">
                                1
                            </span>

                            <button onclick="increaseQty(${products.id})"
                                class="px-3 py-2 bg-gray-200 hover:bg-gray-300">
                                +
                            </button>

                        </div>


                        <button
                            onclick="addToCart(${products.id})"
                            class="bg-yellow-400 text-black px-4 py-2 rounded-lg 
                            hover:bg-yellow-500 font-semibold">
                            Cart
                        </button>

                    </div>


                </div>

            </div>
            `;
        });

        document.getElementById('productGrid').innerHTML = html;

    }

    showProducts();

    function singleProductPage(slug) {
        window.location.href = `/customer-single-product/${slug}`;
    }

    showWishlitValue();

    let quantities = {};

    function increaseQty(id){
        if(!quantities[id]){
            quantities[id] = 1;
        }
        quantities[id]++;

        document.getElementById(`qty-${id}`).innerHTML = quantities[id];
    }

    function decreaseQty(id){
        if(!quantities[id]){
            quantities[id] = 1;
        }
        quantities[id]--;

        document.getElementById(`qty-${id}`).innerHTML = quantities[id];
    }

</script>
