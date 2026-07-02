<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="max-w-6xl mx-auto py-10 px-4">
    <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Checkout Payment</h1>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Payment Method -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-semibold mb-6">Select Payment Method</h2>
            <div class="space-y-4">
                <!-- Cash on Delivery -->
                <label class="flex items-center justify-between border rounded-lg p-4 cursor-pointer hover:border-blue-500">
                    <div class="flex items-center gap-4">
                        <input id="payment-type-cod" value="cod" type="radio" name="payment" checked>
                        <div>
                            <h3 class="font-semibold">Cash on Delivery</h3>
                            <p class="text-sm text-gray-500">
                                Pay when your order arrives.
                            </p>
                        </div>
                    </div>
                </label>

                {{-- <!-- bKash -->
                <label class="flex items-center justify-between border rounded-lg p-4 cursor-pointer hover:border-pink-500">
                    <div class="flex items-center gap-4">
                        <input type="radio" name="payment">
                        <div>
                            <h3 class="font-semibold">bKash</h3>
                            <p class="text-sm text-gray-500">
                                Secure payment via bKash.
                            </p>
                        </div>
                    </div>

                    <span class="bg-pink-100 text-pink-600 px-3 py-1 rounded-full text-xs">
                        Popular
                    </span>
                </label>

                <!-- Nagad -->
                <label class="flex items-center justify-between border rounded-lg p-4 cursor-pointer hover:border-orange-500">
                    <div class="flex items-center gap-4">
                        <input type="radio" name="payment">
                        <div>
                            <h3 class="font-semibold">Nagad</h3>
                            <p class="text-sm text-gray-500">
                                Pay using Nagad wallet.
                            </p>
                        </div>
                    </div>
                </label>

                <!-- Rocket -->
                <label class="flex items-center justify-between border rounded-lg p-4 cursor-pointer hover:border-purple-500">
                    <div class="flex items-center gap-4">
                        <input type="radio" name="payment">
                        <div>
                            <h3 class="font-semibold">Rocket</h3>
                            <p class="text-sm text-gray-500">
                                Pay using Rocket account.
                            </p>
                        </div>
                    </div>
                </label>

                <!-- Card -->
                <label class="flex items-center justify-between border rounded-lg p-4 cursor-pointer hover:border-green-500">
                    <div class="flex items-center gap-4">
                        <input type="radio" name="payment">
                        <div>
                            <h3 class="font-semibold">Credit / Debit Card</h3>
                            <p class="text-sm text-gray-500">
                                Visa, MasterCard, American Express
                            </p>
                        </div>
                    </div>
                </label> --}}
            </div>
        </div>
        <!-- Order Summary -->
        <div id="order-summary" class="bg-white rounded-xl shadow p-6 h-fit">
        </div>
    </div>
</div>

</body>
</html>

<script>
    async function showPaymentDetails() {
        try {
            const orderId = new URLSearchParams(window.location.search).get('orderId');
            const response = await fetch(`/api/payment/details/${orderId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            });

            const data = await response.json();

            console.log(data);

            document.getElementById('order-summary').innerHTML = `
            <h2 class="text-xl font-semibold mb-6">
                Order Summary
            </h2>

            <div class="space-y-4">

                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span>$${data.data.total_amount}</span>
                </div>

                <div class="flex justify-between">
                    <span>Discount</span>
                    <span class="text-green-600">-${data.data.discount_amount}</span>
                </div>

                <hr>

                <div class="flex justify-between text-lg font-bold">
                    <span>Total</span>
                    <span class="text-blue-600">$${data.data.payable_amount}</span>
                </div>

            </div>

            <button onclick="handlePaymentMethodChange()"
                class="w-full mt-8 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition">
                Confirm Payment
            </button>

            <button 
                class="w-full mt-3 border border-gray-300 hover:bg-gray-100 py-3 rounded-lg font-semibold transition">
                Back to Checkout
            </button>
            `;
        } catch (error) {
            console.error('Error fetching payment details:', error);
        }
    }

    showPaymentDetails();

    async function handlePaymentMethodChange() {
        const payment_method = document.querySelector('input[name="payment"]:checked').value;
        const orderId = new URLSearchParams(window.location.search).get('orderId');

        const response = await fetch(`api/payment/create/${orderId}`,{
            method : 'POST',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json',
                'Content-Type' : 'application/json'
            }, 
            body : JSON.stringify({
                payment_method : payment_method
            })

            
        });

        window.location.href = '/';
    }
</script>