<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">My Orders</h1>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-4">Order ID</th>
                        <th class="p-4">Date</th>
                        <th class="p-4">Total</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Action</th>
                    </tr>
                </thead>

                <tbody id="ordersData">

                </tbody>
            </table>
        </div>
    </div>
</body>

</html>

<script>
    async function showOrders() {
        const response = await fetch('api/orders', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        const hideButton = ['shipped', 'delivered', 'refunded', 'cancelled'];


        let html = '';
        data.data.forEach(order => {

            let date = new Date(order.created_at);
            let day = date.getDate();
            let month = date.toLocaleString('default', { month: 'short' });
            let year = date.getFullYear();
            let formattedDate = `${day} - ${month} - ${year}`;

            html += `
                <tr class="border-b">
                        <td class="p-4">${order.id}</td>
                        <td class="p-4">${formattedDate}</td>
                        <td class="p-4">$${order.total_amount - order.discount_amount}</td>

                        <td class="p-4">
                            <span class="bg-yellow-200 text-yellow-800 px-3 py-1 rounded-full">${order.order_status}</span>
                        </td>

                        <td class="p-4 flex gap-3">
                            <button onclick="singleOrder(${order.id})" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                View Details
                            </button>
                            ${
                                hideButton.includes(order.order_status) ? '' :
                                `
                                <button onclick="cancelOrder(${order.id})" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                    Cancel
                                </button>
                                `
                            }
                            
                        </td>
                    </tr>
            `;
        });

        document.getElementById('ordersData').innerHTML = html;
    }

    showOrders();

    function singleOrder(orderId){
        window.location.href = `/order?id=${orderId}`;
    }

    async function cancelOrder(orderId){
        if (confirm('Are you sure you want to cancel this order?')) {
            const response = await fetch(`api/orders/cancel/${orderId}`, {
                method : 'POST',
                'headers' : {
                    'Authorization' : 'Bearer '+localStorage.getItem('token'),
                    'Accept' : 'application/json'
                }
            });

            const data = await response.json();
            //console.log(data);
            if(data.success){
                showOrders();
            } else {
                alert('Failed to cancel order: ' + data.message);
            }
        }
    }
</script>
