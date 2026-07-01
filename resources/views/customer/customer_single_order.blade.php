<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Single Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto p-6">
        <!-- Order Header -->
        <div id="orderHeader" class="bg-white rounded-xl shadow p-6 mb-5">

        </div>

        <!-- Products -->
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-semibold mb-5">Order Items</h2>
            <div id="orderItems" class="space-y-4">
            </div>
            <!-- Total -->
            <div id="discountTotal" class="mt-6 flex justify-between text-xl font-bold">
            </div>

            <div id="afterDiscountTotal" class="mt-6 flex justify-between text-xl font-bold">
            </div>
        </div>

    </div>

    </div>
</body>

</html>

<script>
    async function orderDetails() {
        const orderId = new URLSearchParams(window.location.search).get('id');
        const response = await fetch(`api/order/single/${orderId}`, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        console.log(data);

        let createdAt = new Date(data.data[0].order_items[0].created_at);

        let date = createdAt.getDate();
        let months = createdAt.toLocaleString('default', {
            month: 'short'
        });
        let year = createdAt.getFullYear();

        let formattedDate = `${date}-${months}-${year}`;

        document.getElementById('orderHeader').innerHTML = `
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Order #${data.data[0].id}</h1>
                    <p class="text-gray-500 mt-1">Date: ${formattedDate}</p>
                </div>

                <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full">
                    ${data.data[0].order_status}
                </span>
            </div>
        `;

        let html = '';
        data.data[0].order_items.forEach(item => {
            html += `
                <!-- Item -->
                <div class="flex items-center justify-between border-b pb-4">
                    <div class="flex gap-4 items-center">
                        ${item.product.product_images.map(image => {
                            if(image.is_primary == 1){
                                return `
                                <img src="/storage/${image.image_path}" class="w-20 h-20 rounded object-cover">
                            `;
                            }                     
                        })}
                        
                        <div>
                            <h3 class="font-semibold">${item.product.name}</h3>
                            <p class="text-gray-500">Qty: ${item.quantity}</p>
                        </div>
                    </div>

                    <div class="font-bold">$${item.price * item.quantity}</div>
                </div>
            `;
        });

        document.getElementById('orderItems').innerHTML = html;

        document.getElementById('discountTotal').innerHTML = `
            <span>Total Discount: </span>
            <span>$${data.data[0].discount_amount}</span>
        `;

        document.getElementById('afterDiscountTotal').innerHTML = `
            <span>Total After Discount: </span>
            <span>$${data.data[0].total_amount - data.data[0].discount_amount}</span>
        `;
    }
    orderDetails();
</script>
