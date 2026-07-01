<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Order List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto py-8 px-4">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Order List</h1>
                <p class="text-gray-500 mt-1">Manage all customer orders</p>
            </div>
        </div>

        <!-- Order Table -->
        <div class="bg-white rounded-xl shadow overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left">Order ID</th>
                        <th class="px-6 py-4 text-left">Customer</th>
                        <th class="px-6 py-4 text-left">Phone</th>
                        <th class="px-6 py-4 text-left">Items</th>
                        <th class="px-6 py-4 text-left">Total</th>
                        <th class="px-6 py-4 text-left">Payment</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-left">Date</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>

                <tbody id="orderTableBody">
                    <!-- Order -->
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<script>
    async function showOrders(){
        const response = await fetch(`api/admin/orders/list`, {
            method : 'GET',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json',
            }
        });
        const data = await response.json();
        //console.log(data);

        let html = '';

        data.data.forEach(orders => {
            let createdAt = new Date(orders.created_at);
            let formattedDate = createdAt.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });

            html += `
                <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold">#${orders.id}</td>
                        <td class="px-6 py-4">${orders.user.name}</td>
                        <td class="px-6 py-4">${orders.user.phone}</td>
                        <td class="px-6 py-4">${orders.total_items}</td>
                        <td class="px-6 py-4 font-semibold text-green-600">$${orders.payable_amount}</td>
                        <td class="px-6 py-4">Now It is Static</td>

                        <!-- Status Dropdown -->
                        <td class="px-6 py-4">
                            <select id="order-${orders.id}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="pending" ${orders.order_status === 'pending' ? 'selected' : ''}>Pending</option>
                                <option value="processing" ${orders.order_status === 'processing' ? 'selected' : ''}>Processing</option>
                                <option value="ready_to_ship" ${orders.order_status === 'ready_to_ship' ? 'selected' : ''}>Ready to Ship</option>
                                <option value="shipped" ${orders.order_status === 'shipped' ? 'selected' : ''}>Shipped</option>
                                <option value="delivered" ${orders.order_status === 'delivered' ? 'selected' : ''}>Delivered</option>
                                <option value="refunded" ${orders.order_status === 'refunded' ? 'selected' : ''}>Refunded</option>
                                <option value="cancelled" ${orders.order_status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                            </select>
                        </td>

                        <td class="px-6 py-4">${formattedDate}</td>

                        <!-- Update Button -->
                        <td class="px-6 py-4 text-center">
                            <button onclick="updateOrderStatus(${orders.id})"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition">
                                Update
                            </button>
                        </td>
                    </tr>
            `;
            
        });
        document.getElementById('orderTableBody').innerHTML = html;
    }

    showOrders();

    async function updateOrderStatus(orderId){
        const newStatus = document.getElementById(`order-${orderId}`).value;
        //console.log(newStatus);
        const response = await fetch(`api/admin/order/status/update/${orderId}`,{
            method : 'POST',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json',
                'Content-Type' : 'application/json'
            },
            body : JSON.stringify({
                order_status : newStatus
            })
        });

        showOrders();
    }
</script>