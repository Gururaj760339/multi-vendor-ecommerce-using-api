<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Earnings</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body class="bg-gray-100">

    <div class="max-w-7xl mx-auto p-6">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    Vendor Earnings
                </h1>
                <p class="text-gray-500 mt-1">
                    View your earnings history.
                </p>
            </div>

            <a href="javascript:history.back()"
                class="inline-flex items-center gap-2 bg-gray-800 hover:bg-gray-900 text-white px-5 py-2.5 rounded-lg transition">
                <i class="fa-solid fa-arrow-left"></i>
                Back
            </a>
        </div>

        <!-- Earnings Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden">

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-4 font-semibold">#</th>
                            <th class="px-6 py-4 font-semibold">Order ID</th>
                            <th class="px-6 py-4 font-semibold">Customer</th>
                            <th class="px-6 py-4 font-semibold">Amount</th>
                            <th class="px-6 py-4 font-semibold">Commission</th>
                            <th class="px-6 py-4 font-semibold">Net Earning</th>
                            <th class="px-6 py-4 font-semibold">Date</th>
                        </tr>
                    </thead>

                    <tbody id="earning-details" class="divide-y">
                        
                    </tbody>
                </table>
            </div>

        </div>

    </div>

</body>
</html>

<script>
    async function showVendorEarning(){
        const response = await fetch('api/vendor/earning', {
            method : 'GET',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });

        const data = await response.json();
        //console.log(data);
        let html = '';
        data.data.forEach(earning => {
            let createdAt = new Date(earning.created_at);
            let formattedDate = createdAt.getDate()+ '-' +createdAt.getMonth()+ '-' +createdAt.getFullYear();
            html += `
                <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">${earning.id}</td>
                            <td class="px-6 py-4">#${earning.order.id}</td>
                            <td class="px-6 py-4">${earning.order.user.name}</td>
                            <td class="px-6 py-4 font-semibold text-blue-600">$${earning.gross_amount}</td>
                            <td class="px-6 py-4 text-red-500">$${earning.commission_amount}</td>
                            <td class="px-6 py-4 font-bold text-green-600">$${earning.net_amount}</td>
                            <td class="px-6 py-4">${formattedDate}</td>
                        </tr>
            `;
        });

        document.getElementById('earning-details').innerHTML = html;
    }

    showVendorEarning();
</script>