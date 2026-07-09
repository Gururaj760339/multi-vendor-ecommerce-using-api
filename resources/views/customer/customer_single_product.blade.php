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

    <div class="max-w-7xl mx-auto py-10 px-5">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">
                Customer Reviews
            </h1>

            <button hidden id="add-review-btn" onclick="addReviewPage()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
                <i class="fa-solid fa-plus mr-2"></i>
                Add Review
            </button>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-4 text-left">Product</th>
                        <th class="p-4 text-left">Customer</th>
                        <th class="p-4 text-left">Rating</th>
                        <th class="p-4 text-left">Review</th>
                        <th class="p-4 text-left">Date</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>

                <tbody id="review-details">
                </tbody>
            </table>
        </div>
    </div>
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
        //console.log(data.data);

        let stars = '';
        for(let i = 1; i <= parseInt(data.data.reviews_avg_rating); i++){
            stars += '★';
        }

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
                    <span class="text-yellow-400 text-xl">${stars}</span>
                    <span class="ml-3 text-gray-500">(${data.data.reviews_count} Reviews)</span>
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

    function incrementQuantity(id) {
        quantity++;
        document.getElementById('qty-' + id).value = quantity;
        //console.log(quantity);
    }

    function decrementQuantity(id) {
        if (quantity > 1) {
            quantity--;
            document.getElementById('qty-' + id).value = quantity;
            //console.log(quantity);
        }
    }

    showTotalCart();
    showWishlitValue();

    function addReviewPage() {
        const slug = window.location.pathname.split('/').pop();
        window.location.href = `/customer-add-review/${slug}`;
    }

    function generateStars(rating) {
        let stars = '';

        for (let i = 1; i <= rating; i++) {
            if (i <= rating) {
                stars += `<span class="text-yellow-500">★</span>`;
            }
        }

        return stars;
    }

    async function showReviews() {

        const slug = window.location.pathname.split('/').pop();
        const response = await fetch(`/api/product/${slug}/reviews`, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        //console.log(data);

        let html = '';

        if(data.success){
        data.data.forEach(reviews => {
            let createdAt = new Date(reviews.created_at);
            let formattedDate = createdAt.getDate() + '-' + createdAt.getMonth() + '-' + createdAt
                .getFullYear();
            html += `
            <tr class="border-b hover:bg-gray-50">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                ${reviews.product.product_images.map(images => {
                                    if(images.is_primary == 1){
                                        return `
                                            <img src="/storage/${images.image_path}" class="w-14 h-14 rounded-lg object-cover">
                                        `;
                                    }
                                }).join('')}

                                <div>
                                    <h3 class="font-semibold">${reviews.product.name}</h3>
                                    <p class="text-sm text-gray-500">Product ID : #${reviews.product.id}</p>
                                </div>
                            </div>
                        </td>

                        <td class="p-4">${reviews.user.name}</td>

                        <td class="p-4">
                            ${generateStars(reviews.rating)}
                        </td>

                        <td class="p-4">${reviews.comment}</td>
                        <td class="p-4">${formattedDate}</td>

                        <td class="p-4">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                ${reviews.status}
                            </span>
                        </td>

                        <td class="p-4">
                            <div class="flex justify-center gap-2">
                                <button hidden id="delete-review-btn-${reviews.id}" onclick="deleteReview(${reviews.id})" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                                    <i class="fa-solid fa-trash"></i>
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
            `;
        });

        document.getElementById('review-details').innerHTML = html;

        showDeleteReviewButton(slug, data.data);
        }
    }

    showReviews();

    async function showAddReviewButton() {
        const slug = window.location.pathname.split('/').pop();
        const response = await fetch(`/api/product/add/reviews/button/${slug}`, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            document.getElementById('add-review-btn').hidden = false;
        }
    }

    showAddReviewButton();

    async function deleteReview(reviewId) {
        const response = await fetch(`/api/review/delete/${reviewId}`, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        });

        showReviews();
    }

    async function showDeleteReviewButton(slug, reviewList) {
        reviewList.forEach(async (review) => {
            const response = await fetch(`/api/product/delete/reviews/button/${slug}`, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    review_id: review.id
                })
            });

            const data = await response.json();

            if (data.success) {
                let btn = document.getElementById(`delete-review-btn-${review.id}`);
                if (btn) {
                    btn.hidden = false;
                }
            }
        })
    }
</script>
