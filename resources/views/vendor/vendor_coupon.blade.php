<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coupon Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="p-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="fa-solid fa-ticket text-blue-600 mr-3"></i>
                Coupon Management
            </h1>

            <a href="/vendor-add-coupons"
                class="bg-blue-600 hover:bg-blue-700 transition text-white px-5 py-3 rounded-xl shadow">
                <i class="fa-solid fa-plus mr-2"></i>
                Add Coupon
            </a>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                    <tr>
                        <th class="p-5 text-left">#</th>
                        <th class="p-5 text-left">Coupon Code</th>
                        <th class="p-5 text-left"> Discount Value</th>
                        <th class="p-5 text-left"> Discount Type</th>
                        <th class="p-5 text-left">Expiry Date</th>
                        <th class="p-5 text-left">Status</th>
                        <th class="p-5 text-left">Action</th>
                    </tr>
                </thead>

                <tbody id="couponData" class="divide-y"></tbody>
            </table>
        </div>
    </div>
</body>
</html>
<script>
    async function showCoupon(){
        const response = await fetch('api/cupons', {
            method : 'GET',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });

        const data = await response.json();

        //console.log(data);
        let html = '';
        data.data.forEach(coupon => {
            html += `
            <tr class="hover:bg-gray-50">
                        <td class="p-5">${coupon.id}</td>
                        <td class="p-5 font-bold text-blue-600">${coupon.code}</td>

                        <td class="p-5">
                            <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full">
                                ${coupon.discount_value}
                            </span>
                        </td>

                        <td class="p-5">
                            <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full">
                                ${coupon.type}
                            </span>
                        </td>

                        <td class="p-5">${coupon.expiry_date}</td>

                        <td class="p-5">
                            ${new Date(coupon.expiry_date) > new Date() ?
                                `
                                <span class="bg-green-100 text-green-700 px-4 py-1 rounded-full">
                                    Active
                                </span>
                                `
                                : 
                                `
                                <span class="bg-red-100 text-red-700 px-4 py-1 rounded-full">
                                Expired
                                </span>
                                `
                            }
                            
                        </td>

                        <td class="p-5 space-x-2">
                            <button onclick="editCouponPage(${coupon.id})" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">
                                <i class="fa-solid fa-pen"></i>
                                Edit
                            </button>

                            <button onclick="deleteCoupon(${coupon.id})" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                <i class="fa-solid fa-trash"></i>
                                Delete
                            </button>
                        </td>
                    </tr>
            `;
        });

        document.getElementById('couponData').innerHTML = html;
    }

    showCoupon();

    function editCouponPage(id){
        window.location.href = `/vendor-edit-coupons?id=${id}`
    }

    async function deleteCoupon(id) {
        const response = await fetch(`api/cupon/delete/${id}`, {
            method : 'DELETE',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });

        showCoupon();
    }
</script>