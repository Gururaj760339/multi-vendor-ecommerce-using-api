<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Payment List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="max-w-7xl mx-auto py-8 px-4">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            Payment Management
        </h1>

        <div class="text-gray-600">
            Total Payments: <span class="font-bold">25</span>
        </div>
    </div>

    <!-- Payment Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-5 py-4 text-left">Payment ID</th>
                    <th class="px-5 py-4 text-left">Order ID</th>
                    <th class="px-5 py-4 text-left">Customer</th>
                    <th class="px-5 py-4 text-left">Method</th>
                    <th class="px-5 py-4 text-left">Amount</th>
                    <th class="px-5 py-4 text-left">Current Status</th>
                    <th class="px-5 py-4 text-left">Update Status</th>
                    <th class="px-5 py-4 text-center">Action</th>
                </tr>
            </thead>

            <tbody id="payment-details">
                <!-- Row 1 -->
                
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

<script>
    async function showPayments(){
        const response = await fetch('api/payments', {
            method : 'GET',
            headers : {
                'Accept' : 'application/json',
                'Authorization' : 'Bearer '+localStorage.getItem('token')
            }
        });

        const data = await response.json();
        //console.log(data);

        let html = '';
        data.data.forEach(payments => {
            html += `
                <tr class="border-b hover:bg-gray-50">

                    <td class="px-5 py-4">#${payments.id}</td>
                    <td class="px-5 py-4">#${payments.order_id}</td>
                    <td class="px-5 py-4">${payments.order.user.name}</td>
                    <td class="px-5 py-4">${payments.payment_method}</td>
                    <td class="px-5 py-4 font-semibold">$${payments.amount}</td>

                    <td class="px-5 py-4">
                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">
                            ${payments.status}
                        </span>
                    </td>

                    <td class="px-5 py-4">
                        <select id="payment-${payments.id}" class="border rounded-lg px-3 py-2 w-full">
                            <option value="pending" ${payments.status == 'pending' ? 'selected' : ''}>Pending</option>
                            <option value="completed" ${payments.status == 'completed' ? 'selected' : ''}>Paid</option>
                            <option value="failed" ${payments.status == 'failed' ? 'selected' : ''}>Failed</option>
                            <option value="refunded" ${payments.status == 'refunded' ? 'selected' : ''}>Refunded</option>
                        </select>
                    </td>

                    <td class="px-5 py-4 text-center">
                        <button onclick="updatePaymentStatus(${payments.id})" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            Update
                        </button>
                    </td>
                </tr>
            `;
        })

        document.getElementById('payment-details').innerHTML = html;
    }

    showPayments();

    async function updatePaymentStatus(paymentId){
        const newStatus = document.getElementById(`payment-${paymentId}`).value;
        const response = await fetch(`api/payment/status/update/${paymentId}`, {
            method : 'POST',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json',
                'Content-Type' : 'application/json'
            },
            body : JSON.stringify({
                'status' : newStatus
            })
        });

        showPayments();
    }
</script>