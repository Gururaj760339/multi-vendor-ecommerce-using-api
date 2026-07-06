<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Customers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
</head>

<body class="bg-gray-100">
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">
                <i class="fa-solid fa-users mr-2 text-blue-600"></i>
                All Customers
            </h2>
            <p class="text-gray-500 mt-1">
                Manage all registered customers.
            </p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-4 text-left">Customer</th>
                    <th class="px-6 py-4 text-left">Email</th>
                    <th class="px-6 py-4 text-left">Phone</th>
                    <th class="px-6 py-4 text-center">Orders</th>
                    <th class="px-6 py-4 text-center">Total Spent</th>           
                </tr>
            </thead>

            <tbody id="customer-details" class="divide-y">
                <!-- Customer -->
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

<script>
    async function showCustomerDetails(){
        const response = await fetch('api/admin/allusers', {
            method : 'GET',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });

        const data = await response.json();

        console.log(data);
        let html = '';

        data.data.forEach(users => {
            html += `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="/storage/${users.avatar}"
                                class="w-12 h-12 rounded-full object-cover">

                            <div>
                                <h4 class="font-semibold">${users.name}</h4>
                                <p class="text-gray-500 text-sm">ID #${users.id}</p>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">${users.email}</td>
                    <td class="px-6 py-4">${users.phone}</td>
                    <td class="px-6 py-4 text-center">${users.orders_count}</td>
                    <td class="px-6 py-4 text-center font-semibold text-green-600">$${users.orders_sum_payable_amount}</td>
                </tr>
            `;
        })
        document.getElementById('customer-details').innerHTML = html;
    }

    showCustomerDetails();
</script>