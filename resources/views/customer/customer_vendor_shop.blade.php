<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Shop</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body class="bg-gray-100">

    <div class="max-w-7xl mx-auto py-10 px-5">

        <!-- Shop Header -->
        <div class="bg-white rounded-3xl shadow-lg border border-gray-200 p-8 mb-8">

            <div id="vendor-shop-details-header" class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            </div>

        </div>

        <div class="mt-24 grid lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <div class="space-y-6">
                <div id="vendor-shop-details-sidebar-vendor-info" class="bg-white rounded-xl shadow p-6">
                </div>

                <div id="vendor-shop-details-sidebar-vendor-description" class="bg-white rounded-xl shadow p-6">
                </div>
            </div>

            <!-- Products -->
            <div class="lg:col-span-3">
                <!-- Product Grid -->
                <div id="products-card" class="grid md:grid-cols-2 xl:grid-cols-2 gap-6 mt-6">
                </div>

            </div>

        </div>

    </div>

</body>

</html>

<script>
    async function showVendorProfile() {
        const slug = window.location.search.substring(1);
        console.log(slug);
        const response = await fetch(`api/public/shop/${slug}`, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        console.log(data);

        let createdAt = new Date(data.vendorData.created_at);
        let vandorJoinDate = createdAt.getDate() + '-' + createdAt.getMonth() + '-' + createdAt.getFullYear();
        let today = new Date();
        let expericence = createdAt.getFullYear() - today.getFullYear();

        document.getElementById('vendor-shop-details-header').innerHTML = `
            <!-- Left -->
        <div class="flex items-center gap-6">

            <img src="/storage/${data.vendorData.user.avatar}"
                class="w-32 h-32 rounded-full object-cover border-4 border-blue-500 shadow-lg">

            <div>

                <h1 class="text-3xl font-bold text-gray-800">${data.vendorData.shop_name}</h1>

                <div class="flex flex-wrap gap-5 mt-4 text-sm text-gray-600">

                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-star text-yellow-400"></i>
                        <span>${data.average_rating}</span>
                    </span>

                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-box text-blue-600"></i>
                        <span>${data.total_products}</span>
                    </span>

                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-calendar text-green-600"></i>
                        <span>${vandorJoinDate}</span>
                    </span>

                </div>

            </div>

        </div>

        <!-- Right -->
        <div class="grid grid-cols-2 gap-4 lg:w-80">

            <div class="bg-blue-50 rounded-xl p-4 text-center">
                <h3 class="text-2xl font-bold text-blue-600">
                    ${data.total_products}
                </h3>
                <p class="text-gray-600 text-sm">
                    Products
                </p>
            </div>

            <div class="bg-yellow-50 rounded-xl p-4 text-center">
                <h3 class="text-2xl font-bold text-yellow-500">
                    ${data.average_rating}
                </h3>
                <p class="text-gray-600 text-sm">
                    Rating
                </p>
            </div>

            <div class="bg-green-50 rounded-xl p-4 text-center">
                <h3 class="text-2xl font-bold text-green-600">
                    ${data.total_sales}
                </h3>
                <p class="text-gray-600 text-sm">
                    Sales
                </p>
            </div>

            <div class="bg-purple-50 rounded-xl p-4 text-center">
                <h3 class="text-2xl font-bold text-purple-600">
                    ${expericence} Years
                </h3>
                <p class="text-gray-600 text-sm">
                    Experience
                </p>
            </div>

        </div>
        `;

        document.getElementById('vendor-shop-details-sidebar-vendor-info').innerHTML = `
            <h3 class="font-bold text-lg mb-4">Shop Information</h3>
                <div class="space-y-3 text-gray-600">

                    <p class="flex items-center">
                        <i class="fa-solid fa-location-dot text-blue-600 mr-3"></i>
                        <span>${data.vendorData.address}</span>
                    </p>

                    <p class="flex items-center">
                        <i class="fa-solid fa-phone text-green-600 mr-3"></i>
                        <span>${data.vendorData.user.phone}</span>
                    </p>

                    <p class="flex items-center">
                        <i class="fa-solid fa-envelope text-red-500 mr-3"></i>
                        <span>${data.vendorData.user.email}</span>
                    </p>

                    <p class="flex items-center">
                        <i class="fa-solid fa-clock text-yellow-500 mr-3"></i>
                        <span>Open Everyday</span>
                    </p>

                </div>
        `;

        document.getElementById('vendor-shop-details-sidebar-vendor-description').innerHTML = `
            <h3 class="font-bold text-lg mb-4">About Shop</h3>
            <p class="text-gray-600 leading-7">
                ${data.vendorData.description}
            </p>
        `;

        let html = '';
        console.log(data.vendorData.products);

        data.vendorData.products.forEach(products => {
            html += `
                ${products.product_images.map(productImage => {
                    if(productImage.is_primary == 1){
                        return `
                            <img onclick="showProduct('${products.slug}')" src="/storage/${productImage.image_path}" class="rounded-t-xl w-full h-52 object-cover">
                        `;
                    }
                }).join('')}
                        <div class="p-5">
                            <h3 onclick="showProduct('${products.slug}')" class="font-semibold text-lg">${products.name}</h3>
                            <div class="flex items-center mt-2">
                                <i class="fa-solid fa-star text-yellow-400"></i>
                                ${products.reviews_avg_rating == null ? `<span class="ml-2">0</span>` : `<span class="ml-2">${Number(products.reviews_avg_rating).toFixed(1)}</span>` }
                                
                            </div>
                            <div class="flex justify-between mt-5">
                                <span class="text-blue-600 font-bold text-xl">${products.price}</span>
                                <button onclick="showProduct('${products.slug}')" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                                    View
                                </button>
                            </div>
                        </div>
            
            `;
        });
        document.getElementById('products-card').innerHTML = html;
    }

    showVendorProfile();

    function showProduct(slug){
        window.location.href = `/customer-single-product/${slug}`;
    }
</script>
