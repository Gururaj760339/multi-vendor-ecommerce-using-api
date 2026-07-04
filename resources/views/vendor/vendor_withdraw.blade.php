<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Withdraw History</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <div class="p-6 bg-gray-100 min-h-screen">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Withdraw History</h2>
                <p class="text-gray-500 mt-1">
                    View your withdrawal requests and create a new one.
                </p>
            </div>

            <a href="/vendor-withdraw-request" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium shadow transition">
                <i class="fa-solid fa-plus mr-2"></i>
                New Withdraw Request
            </a>
        </div>


        <!-- Withdraw History -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-5 border-b">
                <h3 class="text-xl font-semibold text-gray-800">
                    Withdraw Requests
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">#</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Amount</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Payment Method</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Account Details</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Request Date</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Status</th>
                        </tr>
                    </thead>

                    <tbody id="withdraw-details" class="divide-y">
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    async function ShowWithdrawHistroy(){
        const response = await fetch('api/vendor/withdraw/histroy', {
            method : 'GET',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });

        const data = await response.json();
        //console.log(data);
        let html = '';
        data.data.forEach(withdraw => {
            let createdAt = new Date(withdraw.created_at);
            let fommatedDate = createdAt.getDate()+ '-' +createdAt.getMonth()+ '-' +createdAt.getFullYear();
            html += `
                <tr class="hover:bg-gray-50">
                            <td class="px-6 py-5">${withdraw.id}</td>
                            <td class="px-6 py-5 font-semibold text-indigo-600">$${withdraw.amount}</td>
                            <td class="px-6 py-5">${withdraw.payment_method}</td>
                            <td class="px-6 py-5">${withdraw.payment_details}</td>
                            <td class="px-6 py-5">${fommatedDate}</td>

                            <td class="px-6 py-5">
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-medium">
                                    ${withdraw.status}
                                </span>
                            </td>
                        </tr>
            `;
        });

        document.getElementById('withdraw-details').innerHTML = html;
    }

    ShowWithdrawHistroy();
</script>
