<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Withdraw Request</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <div class="min-h-screen bg-gray-100 p-6">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Withdraw Request</h1>
            <p class="text-gray-500 mt-2">Submit a new withdrawal request to receive your earnings.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Side -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow">
                    <div class="border-b px-6 py-5">
                        <h2 class="text-xl font-semibold">Withdraw Details</h2>
                    </div>

                    <form class="p-6 space-y-6">
                        <!-- Amount -->
                        <div>
                            <label class="block font-medium text-gray-700 mb-2">Withdraw Amount</label>

                            <input id="withdraw-amount" type="number" placeholder="Enter amount"
                                class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>

                        <!-- Method -->
                        <div>
                            <label class="block font-medium text-gray-700 mb-2">Payment Method</label>

                            <select id="withdraw-method"
                                class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none">
                                <option disabled>Select Payment Method</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="bKash">bKash</option>
                                <option value="Nagad">Nagad</option>
                                <option value="Rocket">Rocket</option>
                            </select>
                        </div>

                        <!-- Payment Details -->
                        <div>
                            <label class="block font-medium text-gray-700 mb-2">Account Details</label>

                            <textarea id="payment_details" rows="4" placeholder="Write Account Details"
                                class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>

                        </div>
                        <!-- Buttons -->

                        <div class="flex justify-end gap-3">
                            <a href="#" class="px-6 py-3 border rounded-lg hover:bg-gray-100">Cancel</a>

                            <button type="submit" onclick="withdrawRequest()"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg">
                                <i class="fa-solid fa-paper-plane mr-2"></i>
                                Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Side -->
            <div class="space-y-6">
                <!-- Balance -->
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex justify-between items-center">
                        <div id="avilable-balance">
                        </div>

                        <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="fa-solid fa-wallet text-green-600 text-3xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Rules -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="font-bold text-lg mb-4">Withdraw Rules</h3>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex">
                            <i class="fa-solid fa-circle-check text-green-500 mt-1 mr-3"></i>
                            Minimum withdrawal amount is
                            <strong class="ml-1">$50</strong>
                        </li>

                        <li class="flex">
                            <i class="fa-solid fa-circle-check text-green-500 mt-1 mr-3"></i>
                            Requests are processed within
                            <strong class="ml-1">24-48 hours.</strong>
                        </li>

                        <li class="flex">
                            <i class="fa-solid fa-circle-check text-green-500 mt-1 mr-3"></i>
                            Incorrect payment information may delay payment.
                        </li>

                        <li class="flex">
                            <i class="fa-solid fa-circle-check text-green-500 mt-1 mr-3"></i>
                            Withdrawals are subject to admin approval.
                        </li>
                    </ul>
                </div>

                <!-- Summary -->
                <div class="bg-indigo-600 rounded-xl p-6 text-white">
                    <h3 class="text-lg font-semibold">Quick Summary</h3>

                    <div id="balance-summery" class="mt-5 space-y-3">
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    async function showVendorEarning(){
        const response = await fetch('api/vendor/withdraw/calculation', {
            method : 'GET',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });

        const data = await response.json();
        console.log(data);

        document.getElementById('avilable-balance').innerHTML = `
           <p class="text-gray-500">Available Balance</p>
            <h2 class="text-4xl font-bold text-green-600 mt-2">$${data.currentBalance ? data.currentBalance : 0}</h2>
        `;

        document.getElementById('balance-summery').innerHTML = `
            <div class="flex justify-between">
                            <span>Total Earnings</span>
                            <span>$${data.totalEarning ? data.totalEarning : 0}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Total Withdrawn</span>
                            <span>$${data.totalWithdraw ? data.totalWithdraw : 0}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Pending Withdraw</span>
                            <span>$${data.pendingWithdraw ? data.pendingWithdraw : 0}</span>
                        </div>

                        <hr class="border-indigo-400">

                        <div class="flex justify-between font-bold text-lg">
                            <span>Available</span>
                            <span>$${data.currentBalance ? data.currentBalance : 0}</span>
                        </div>
        `;
    }

    showVendorEarning();

    async function withdrawRequest(){
        let formData = new FormData();
        formData.append('amount', document.getElementById('withdraw-amount').value);
        formData.append('payment_method', document.getElementById('withdraw-method').value);
        formData.append('payment_details', document.getElementById('payment_details').value);

        const response = await fetch('api/vendor/withdraw/request', {
            method : 'POST',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            },
            body : formData
        });

        window.location.href = '/vendor-withdraw-history';
    }
</script>
