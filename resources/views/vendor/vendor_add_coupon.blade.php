<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Coupon</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white w-full max-w-lg p-8 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Add New Coupon</h2>

        <div id="errorBox" class="text-red-500 mb-3"></div>
        
        <form id="couponForm">
            <!-- Coupon Code -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Coupon Code</label>
                <input id="code" type="text" name="code" placeholder="Enter coupon code"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <!-- Coupon Type -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Type </label>
                <select name="type" id="type"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Select Type</option>
                    <option value="percent">Percentage</option>
                    <option value="fixed">Fixed Amount</option>
                </select>
            </div>

            <!-- Expiry Date -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Expiry Date</label>

                <input type="date" name="expiry_date" id="expiry_date"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <!-- Discount Value -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Discount Value</label>
                <input id="discount_value" type="number" name="discount_value" placeholder="Enter discount value"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <!-- Button -->
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition">
                Add Coupon
            </button>
        </form>
    </div>
</body>

</html>

<script>
    document.getElementById('couponForm').addEventListener('submit', async function(e){
        e.preventDefault();

        let formData = new FormData();
        formData.append('code', document.getElementById('code').value);
        formData.append('type', document.getElementById('type').value);
        formData.append('expiry_date', document.getElementById('expiry_date').value);
        formData.append('discount_value', document.getElementById('discount_value').value);

        const response = await fetch('api/cupon/create', {
            method : 'POST',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            },
            body : formData
        });

        const data = await response.json();

        if(data.success){
            window.location.href = '/vendor-coupons';
        } else {
            document.getElementById('errorBox').innerHTML = data.message;
        }

    })
</script>
