<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Vendors</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Top Vendors</h1>
                <p class="text-gray-500 mt-1">Best performing vendors based on sales & earnings.</p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden">

            <div class="px-6 py-4 border-b">
                <h2 class="font-bold text-xl">
                    Vendor Leaderboard
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-gray-600">
                            <th class="px-6 py-4">Vendor</th>
                            <th class="px-6 py-4">Sold Products</th>
                            <th class="px-6 py-4">Revenue</th>
                        </tr>

                    </thead>

                    <tbody id="vendor-details">
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    async function topVendor(){
        const response = await fetch('api/admin/top/vendor', {
            method : 'GET',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });

        const data = await response.json();
        //console.log(data);

        let html = '';
        data.data.forEach(vendor => {
            html += `
            <tr class="border-b hover:bg-gray-50">

                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <img src="/storage/${vendor.user.avatar}" class="w-11 h-11 rounded-full">
                                    <div>
                                        <h4 class="font-semibold">${vendor.shop_name}</h4>
                                        <p class="text-sm text-gray-500">${vendor.user.email}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-5">${vendor.order_items_count}</td>
                            <td class="px-6 py-5 font-bold text-green-600">$${vendor.vendor_earning_sum_net_amount}</td>
                        </tr>
            `;
        });

        document.getElementById('vendor-details').innerHTML = html;
    }

    topVendor();
</script>
