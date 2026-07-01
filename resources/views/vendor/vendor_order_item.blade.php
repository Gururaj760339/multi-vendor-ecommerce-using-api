<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto py-8 px-4">

        <!-- Order Header -->
        <div id="order-header" class="bg-white rounded-xl shadow p-6 mb-6 flex justify-between items-center">
        </div>

        <!-- Customer Information -->
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h3 class="text-xl font-semibold mb-4">Customer Information</h3>
            <div id="customer-info" class="grid grid-cols-2 gap-5">
            </div>
        </div>

        <!-- Vendor Products Only -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-xl font-semibold">
                    Your Products in this Order
                </h3>
            </div>

            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left p-4">Image</th>
                        <th class="text-left p-4">Product</th>
                        <th class="text-left p-4">Price</th>
                        <th class="text-left p-4">Qty</th>
                        <th class="text-left p-4">Total</th>
                        <th class="text-left p-4">Status</th>
                        <th class="text-center p-4">Action</th>
                    </tr>
                </thead>

                <tbody id="order-items" class="divide-y divide-gray-200">
                    
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>

<script>
    async function showOrderDetails() {
        const id = new URLSearchParams(window.location.search).get('order_id');
        //console.log(id);
        const response = await fetch(`api/vendor/orders/items/${id}`, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        });

        const data = await response.json();


        const createdAt = new Date(data.data[0].order.created_at);
        const formattedData = createdAt.getDate() + '-' + (createdAt.getMonth() + 1) + '-' + createdAt
    .getFullYear();
        const formattedTime = createdAt.getHours() + ':' + createdAt.getMinutes();

        document.getElementById('order-header').innerHTML = `
            <div>
                <h2 class="text-2xl font-bold">Order #${data.data[0].order.id}</h2>
                <p class="text-gray-500 mt-1">${formattedData} • ${formattedTime}</p>
            </div>

            <span class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 font-medium">
                ${data.data[0].order.order_status}
            </span>
        `;

        const address = JSON.parse(data.data[0].order.shipping_address);
        document.getElementById('customer-info').innerHTML = `
                <div>
                    <p class="text-gray-500 text-sm">Customer</p>
                    <p class="font-semibold">${data.data[0].order.user.name}</p>
                </div>

                <div>
                    <p class="text-gray-500 text-sm">Phone</p>
                    <p class="font-semibold">${data.data[0].order.user.phone}</p>
                </div>

                <div>
                    <p class="text-gray-500 text-sm">Email</p>
                    <p class="font-semibold">${data.data[0].order.user.email}</p>
                </div>

                <div>
                    <p class="text-gray-500 text-sm">Address</p>
                    <p class="font-semibold">${(address.address_line1)}</p>
                    <p class="font-semibold">${(address.address_line2)}</p>
                </div>
            `;

        //console.log(data);
        
        let html = '';
        data.data.forEach(items => {
            html += `
                <tr class="border-b hover:bg-gray-50">
                        <td class="p-4">
                            ${items.product.product_images.map(image => {
                                if(image.is_primary == 1){
                                    return `
                                        <img src="/storage/${image.image_path}" class="w-16 h-16 rounded-lg object-cover">
                                    `;
                                }
                            })}
                           
                        </td>

                        <td class="p-4 font-medium">${items.product.name}</td>
                        <td class="p-4">$${items.product.price}</td>
                        <td class="p-4">${items.quantity}</td>
                        <td class="p-4 font-semibold">$${(items.product.price * items.quantity).toFixed(2)}</td>

                        <td class="p-4">
                            <select id="item-status-${items.id}" class="border rounded-lg px-3 py-2 w-full">
                                <option value="pending" ${items.item_status === 'pending' ? 'selected' : ''}>Pending</option>
                                <option value="processing" ${items.item_status === 'processing' ? 'selected' : ''}>Processing</option>
                                <option value="ready_to_ship" ${items.item_status === 'ready_to_ship' ? 'selected' : ''}>Ready to Ship</option>
                                <option value="shipped" ${items.item_status === 'shipped' ? 'selected' : ''}>Shipped</option>
                                <option value="delivered" ${items.item_status === 'delivered' ? 'selected' : ''}>Delivered</option>
                                <option value="refunded" ${items.item_status === 'refunded' ? 'selected' : ''}>Refunded</option>
                                <option value="cancelled" ${items.item_status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                            </select>
                        </td>

                        <td class="p-4 text-center">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg" onclick="updateItemStatus(${items.id})">
                                Update
                            </button>
                        </td>
                    </tr>
            `;
        });
        document.getElementById('order-items').innerHTML = html;
    }

    showOrderDetails();

    async function updateItemStatus(itemId){
        const newStatus = document.getElementById(`item-status-${itemId}`).value;

        const response = await fetch(`api/vendor/orders/items/status/update/${itemId}`, {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ item_status: newStatus })
        });

        const data = await response.json();
        if(data.success){
           showOrderDetails(); 
        }

        showOrderDetails();
    }   
</script>
