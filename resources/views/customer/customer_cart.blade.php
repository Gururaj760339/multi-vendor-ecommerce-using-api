<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">My Cart</h1>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="p-4">Image</th>
                            <th class="p-4">Product</th>
                            <th class="p-4">Price</th>
                            <th class="p-4">Quantity</th>
                            <th class="p-4">Action</th>
                        </tr>
                    </thead>

                    <tbody id="cartData">
                    </tbody>
                </table>
            </div>

            <!-- Summary -->
            <div id="productSummery" class="flex justify-end mt-8">

            </div>
        </div>
    </div>
</body>

</html>

<script>
    async function showTotalCart() {
        const response = await fetch('api/carts', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        console.log(data);

        let html = '';
        data.items.forEach(products => {
            html += `
                        <tr  class="border-b hover:bg-gray-50">
                            <td class="p-4">
                                ${products.product.product_images.map(productImage => {
                                    if(productImage.is_primary == 1){
                                        return `
                                            <img src="/storage/${productImage.image_path}" class="w-20 h-20 rounded-lg object-cover">
                                        `;
                                    }
                                }).join('')}
                                
                            </td>

                            <td class="p-4">
                                <h2 class="font-bold text-lg">${products.product.name}</h2>
                            </td>

                            <td class="p-4 font-semibold">$${products.product.price}</td>

                            <td class="p-4">
                                <div class="flex items-center gap-2">

                                    <button 
                                        onclick="decreaseQty(${products.id})" id="decrementQuantity"
                                        class="bg-gray-200 px-3 py-2 rounded hover:bg-gray-300">
                                            -
                                    </button>

                                    <input type="number" id="quantity-${products.id}" value="${products.quantity}" min="1" class="border w-16 text-center rounded p-2 qty-input">

                                    <button 
                                        onclick="increaseQty(${products.id})" id="incrementQuantity"
                                        class="bg-gray-200 px-3 py-2 rounded hover:bg-gray-300">
                                        +
                                    </button>

                                </div>
                            </td>

                            <td class="p-4">
                                <div class="flex gap-2">
                                    <button onclick="updateCartQuantity(${products.id})" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                                        Update
                                    </button>

                                    <button onclick="deleteProduct(${products.id})" class="bg-red-600 text-white px-4 py-2 rounded-lg">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
            `;
        });

        document.getElementById('cartData').innerHTML = html;

        const currentCouponValue = appliedCouponCode ? appliedCouponCode : '';

        if (!data.items || data.items.length === 0) {
            document.getElementById('productSummery').innerHTML = `
                <div class="w-full md:w-96 bg-gray-50 rounded-xl p-6 shadow">
                    <h2 class="text-2xl font-bold mb-5">Cart Summary</h2>
                    <p class="text-gray-600">Your cart is empty.</p>
                </div>
            `;
            return;
        }
        document.getElementById('productSummery').innerHTML = `
        <div class="w-full md:w-96 bg-gray-50 rounded-xl p-6 shadow">
                    <h2 class="text-2xl font-bold mb-5">Cart Summary</h2>

                    <!-- Coupon Apply -->
                    <div class="mb-5">
                        <label class="block font-semibold mb-2">Apply Coupon</label>
                        <div class="flex gap-2">
                            <input id="code" type="text" placeholder="Enter coupon code"
                                class="w-full border rounded-lg p-3 outline-none focus:ring-2 focus:ring-blue-400">
                            <button onclick="applyCoupon()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 rounded-lg">
                                Apply
                            </button>
                        </div>
                    </div>

                    <hr class="mb-4">

                    <div class="flex justify-between mb-3">
                        <span>Subtotal</span>
                        <span class="font-semibold">$${data.sub_total}</span>
                    </div>

                    <div class="flex justify-between mb-3">
                        <span>Shipping Fee</span>
                        <span class="font-semibold"> $${data.shipping_fee}</span>
                    </div>

                    <div class="flex justify-between mb-3">
                        <span>Coupon Discount</span>
                        <span class="text-green-600">-$0</span>
                    </div>
                    <hr>

                    <div class="flex justify-between text-xl font-bold mt-4">
                        <span>Total</span>
                        <span>$${data.total}</span>
                    </div>

                    <button onclick="createOrder()"
                        class="w-full mt-6 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg text-lg font-semibold">
                        Checkout
                    </button>

                    <button onclick="clearAllCart(${data.items[0].user_id})"
                        class="w-full mt-3 bg-red-100 hover:bg-red-200 text-red-600 py-2 rounded-lg font-semibold border border-red-300 transition">
                        Clear Cart
                    </button>
                </div>
        `;

    }

    showTotalCart();

    let appliedCouponCode = '';

    async function applyCoupon() {
        let formData = new FormData();
        const couponInput =  document.getElementById('code');
        if(!couponInput) return;

        const couponCode = couponInput.value;
        formData.append('code', couponCode);

        const response = await fetch('api/cart/coupon/applycart', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();

        if (!data.success) {

            document.getElementById('productSummery').innerHTML = `
            <div class="w-full md:w-96 bg-red-50 rounded-xl p-6 shadow">

                <h2 class="text-xl font-bold text-red-600 mb-4">
                    ${data.message ?? 'Invalid Coupon'}
                </h2>

                <div class="flex gap-2">
                    <input id="code" 
                        type="text"
                        placeholder="Enter coupon code"
                        class="w-full border rounded-lg p-3">

                    <button onclick="applyCoupon()"
                        class="bg-blue-600 text-white px-5 rounded-lg">
                        Apply
                    </button>
                </div>

            </div>
        `;

            return;
        }

        appliedCouponCode = couponCode;

        document.getElementById('productSummery').innerHTML = `
        <div class="w-full md:w-96 bg-gray-50 rounded-xl p-6 shadow">
                    <h2 class="text-2xl font-bold mb-5">Cart Summary</h2>

                    <!-- Coupon Apply -->
                    <div class="mb-5">
                        <label class="block font-semibold mb-2">Apply Coupon</label>
                        <div class="flex gap-2">
                            <input id="code" value="${appliedCouponCode}" type="text" placeholder="Enter coupon code"
                                class="w-full border rounded-lg p-3 outline-none focus:ring-2 focus:ring-blue-400">
                            <button onclick="applyCoupon()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 rounded-lg">
                                Apply
                            </button>
                        </div>
                    </div>

                    <hr class="mb-4">

                    <div class="flex justify-between mb-3">
                        <span>Subtotal</span>
                        <span class="font-semibold">$${data.subTotal}</span>
                    </div>

                    <div class="flex justify-between mb-3">
                        <span>Shipping Fee</span>
                        <span class="font-semibold"> $${data.shippingFee}</span>
                    </div>

                    <div class="flex justify-between mb-3">
                        <span>Coupon Discount</span>
                        <span class="text-green-600">-${data.Discount_Amount}</span>
                    </div>
                    <hr>

                    <div class="flex justify-between text-xl font-bold mt-4">
                        <span>Total</span>
                        <span>$${data.Grand_Total}</span>
                    </div>

                    <button onclick="createOrder()"
                        class="w-full mt-6 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg text-lg font-semibold">
                        Checkout
                    </button>
                </div>
        `;
    }


    function increaseQty(id) {
        let quantity = document.getElementById(`quantity-${id}`);
        quantity.value = parseInt(quantity.value) + 1;
    }

    function decreaseQty(id) {
        let quantity = document.getElementById(`quantity-${id}`);
        if (quantity.value > 1) {
            quantity.value = parseInt(quantity.value) - 1;
        }
    }

    async function updateCartQuantity(productId) {
        let id = productId;
        let formData = new FormData();
        formData.append('id', id);
        formData.append('quantity', document.getElementById(`quantity-${id}`).value);

        const response = await fetch('api/cart/update', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            window.location.href = "/carts";
        }
    }

    async function deleteProduct(cartId) {
        let id = cartId;

        const response = await fetch('api/cart/delete', {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: id
            })
        });

        const data = await response.json();

        if (data.success) {
            window.location.href = "/carts";
        }
    }

    async function clearAllCart(userId) {
        const id = userId;
        //console.log(id);
        const response = await fetch('api/cart/full/delete', {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: id
            })
        });

        const data = await response.json();

        if (data.success) {
            window.location.href = '/carts';
        }
    }

    async function createOrder() {
        let couponInput = document.getElementById('code');
        let couponCode = couponInput ? couponInput.value : appliedCouponCode;
        console.log(couponCode);

        const response = await fetch('api/order/create', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                code: couponCode
            })
        });

        window.location.href = '/';

    }
</script>
