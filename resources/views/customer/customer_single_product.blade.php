<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Single Product</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>


<body class="bg-gray-100">


    <!-- Navbar -->
    @include('customer.navbar')

    <!-- Product Section -->
    <div id="productMain"></div>
</body>

</html>

<script src="{{ asset('storage/js/navbar.js') }}"></script>
<script src="{{ asset('storage/js/addtocart.js') }}"></script>

<script>
    updateAuthUI();

    async function showSingleProduct() {
        const slug = window.location.pathname.split('/').pop();

        const response = await fetch(`/api/product/${slug}`, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        //console.log(data.data.id);

        document.getElementById('productMain').innerHTML = `
            <section class="max-w-7xl mx-auto p-8">
        <div class="bg-white rounded-xl shadow p-6 grid md:grid-cols-2 gap-10">
            <div>
                ${data.data.product_images.map(image => {
                    if(image.is_primary == 1){
                        return `
                        <img class="w-full h-[450px] object-cover rounded-lg" src="/storage/${image.image_path}">
                        `;
                    }
                }).join('')}

                <div class="flex gap-3 mt-5">
                    ${data.data.product_images.map(image => {
                        if(image.is_primary == 0){
                            return `
                            <img class="w-20 h-20 rounded border" src="/storage/${image.image_path}">
                            `;
                        }
                    }).join('')}
                </div>
            </div>

            <!-- Details -->
            <div>
                <h1 class="text-4xl font-bold">${data.data.name}</h1>

                <div class="flex mt-4">
                    <span class="text-yellow-400 text-xl">★★★★★</span>
                    <span class="ml-3 text-gray-500">(120 Reviews) now it is satic</span>
                </div>

                <h2 class="text-3xl font-bold text-blue-600 mt-5">$${data.data.price}</h2>

                <p class="text-gray-600 mt-5 leading-7">
                    ${data.data.description}
                </p>

                <!-- Quantity -->
                <div class="mt-6">
                    <label class="font-semibold">Quantity</label>

                    <div class="flex mt-2">
                        <button onclick="decrementQuantity(${data.data.id})" class="px-4 py-2 border">-</button>
                        <input id="qty-${data.data.id}" value="1" class="w-16 text-center border">
                        <button onclick="incrementQuantity(${data.data.id})" class="px-4 py-2 border">+</button>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="mt-8 flex gap-4">
                    <button onclick="addToCart(${data.data.id})" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700">Add To Cart</button>
                    
                    <button onclick="addWishlist(${data.data.product_images[0].product_id})" class="border border-red-500 text-red-500 px-8 py-3 rounded-lg">♡ Wishlist</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Info -->
    <section class="max-w-7xl mx-auto px-8 pb-10">
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-2xl font-bold mb-5">Product Information</h2>

            <div class="grid md:grid-cols-2 gap-5">
                <p><b>Category:</b> ${data.data.category.name}</p>
                <p><b>Stock:</b> ${data.data.stock_quantity > 0 ? 'Available' : 'Stock Out' }</p>
            </div>
        </div>
    </section>
        `;

    }

    showSingleProduct();

    let quantity = 1;

    function incrementQuantity(id){
        quantity++;
        document.getElementById('qty-' + id).value = quantity;
        //console.log(quantity);
    }

    function decrementQuantity(id){
        if(quantity > 1){
            quantity--;
            document.getElementById('qty-' + id).value = quantity;
            //console.log(quantity);
        }
    }

    showTotalCart();
    showWishlitValue();
    

</script>
