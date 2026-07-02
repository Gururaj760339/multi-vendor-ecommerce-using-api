<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Heading -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Payment History</h1>
            <p class="text-gray-500 mt-1">
                View all your completed and pending payments.
            </p>
        </div>

        <!-- Summary Cards -->
        <div id="card-summery" class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        </div>

        <!-- Payment Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-5 border-b">
                <h2 class="text-xl font-semibold">
                    Recent Payments
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-4">Payment ID</th>
                            <th class="px-6 py-4">Order ID</th>
                            <th class="px-6 py-4">Method</th>
                            <th class="px-6 py-4">Transaction ID</th>
                            <th class="px-6 py-4">Amount</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Date</th>
                        </tr>

                    </thead>

                    <tbody id="payment-details" class="divide-y">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    async function showPaymentHistory() {
        const response = await fetch('api/payment/histroy', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        //console.log(data);

        document.getElementById('card-summery').innerHTML = `
            <div class="bg-white rounded-xl shadow p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500">Total Payments</p>
                        <h2 class="text-3xl font-bold mt-2">${data.totalPaymentCount}</h2>
                    </div>

                    <div class="bg-blue-100 text-blue-600 p-4 rounded-full">
                        <i class="fa-solid fa-credit-card text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500">Total Paid</p>
                        <h2 class="text-3xl font-bold mt-2 text-green-600">$${data.totalPaymentAmount}</h2>
                    </div>

                    <div class="bg-green-100 text-green-600 p-4 rounded-full">
                        <i class="fa-solid fa-dollar-sign text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500">Pending</p>
                        <h2 class="text-3xl font-bold mt-2 text-yellow-500">$${data.totalPendingPaymentAmount}</h2>
                    </div>

                    <div class="bg-yellow-100 text-yellow-500 p-4 rounded-full">
                        <i class="fa-solid fa-clock text-xl"></i>
                    </div>
                </div>
            </div>
        `;

        //console.log(data.paymentHistroy);

        let html = '';

        data.paymentHistroy.forEach(payments => {
            let createAt = new Date(payments.created_at);
            let formattedDate = createAt.getDate()+ '-' +createAt.getMonth()+ '-' +createAt.getYear(); 
            html += `
                <tr class="hover:bg-gray-50">
                            <td class="px-6 py-5 font-semibold">#${payments.id}</td>
                            <td class="px-6 py-5">#${payments.order_id}</td>
                            <td class="px-6 py-5">${payments.payment_method}</td>
                            <td class="px-6 py-5">${payments.transaction_id}</td>
                            <td class="px-6 py-5 font-semibold">$${payments.amount}</td>

                            <td class="px-6 py-5">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                    ${payments.status}
                                </span>
                            </td>

                            <td class="px-6 py-5">${formattedDate}</td>
                        </tr>
            `;
        });

        document.getElementById('payment-details').innerHTML = html;
    }

    showPaymentHistory();
</script>
