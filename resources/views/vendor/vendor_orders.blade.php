<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Vendor Orders</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="max-w-7xl mx-auto py-8 px-4">

        <div class="bg-white rounded-xl shadow overflow-hidden">

            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h2 class="text-2xl font-bold">Orders</h2>
            </div>

            <table class="w-full">

                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-4 text-left">Order ID</th>
                        <th class="p-4 text-left">Customer</th>
                        <th class="p-4 text-left">Phone</th>
                        <th class="p-4 text-left">Date</th>
                        <th class="p-4 text-left">Items</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>

                <tbody id="orders-container">
                    
                </tbody>

            </table>

        </div>

    </div>

</body>

</html>

<script>
    async function showOrders(){
        const response =  await fetch(`api/vendor/orders`, {
            method: 'GET',
            'headers' : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json',
            }
        });

        const data = await response.json();
        console.log(data);

        let html = '';

        data.data.forEach(order => {
            let createAt = new Date(order.created_at);
            let formateDate = createAt.getDate() + '-' + (createAt.getMonth() + 1) + '-' + createAt.getFullYear(); 
            html += `
                <tr class="border-b hover:bg-gray-50">
                        <td class="p-4 font-semibold">#${order.id}</td>
                        <td class="p-4">${order.user.name}</td>
                        <td class="p-4">${order.user.phone}</td>
                        <td class="p-4">${formateDate}</td>
                        <td class="p-4">${order.total_items}</td>
                        <td class="p-4">
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm">
                                ${order.order_status}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <button onclick="orderDetails(${order.id})" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                View
                            </button>
                        </td>
                    </tr>
            `;
        });

        document.getElementById('orders-container').innerHTML = html;
    }

    showOrders();

    function orderDetails(orderId){
        window.location.href = `/vendor-order-item?order_id=${orderId}`;
    }
</script>