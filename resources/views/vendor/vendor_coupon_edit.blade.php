<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Coupon</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Coupon</h2>

            <div id="errorBox" class="text-red-500 mb-3"></div>

            <form id="couponEditForm">
            </form>
        </div>
    </div>
</body>

</html>

<script>
    async function editCoupon() {
        const id = new URLSearchParams(window.location.search).get('id');
        const response = await fetch(`api/cupon/edit/${id}`, {
            method : 'GET',
            'headers' : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });

        const data = await response.json();
        //console.log(data.data);

        document.getElementById('couponEditForm').innerHTML =`
            <div class="mb-4">
                    <label class="block mb-2 font-medium">
                        Coupon Code
                    </label>

                    <input value="${data.data.code}" type="text" id="code"
                        class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter coupon code">
                </div>

                <!-- Discount Value -->
                <div class="mb-4">
                    <label class="block mb-2 font-medium">
                        Discount Value
                    </label>

                    <input value="${data.data.discount_value}" type="number" id="discount_value"
                        class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter discount value">
                </div>

                <!-- Type -->
                <div class="mb-4">
                    <label class="block mb-2 font-medium">
                        Discount Type
                    </label>

                    <select id="type" class="w-full border rounded-lg p-3">
                        <option value="">Select Type</option>
                        <option value="percent"  ${data.data.type == 'percent' ? 'selected' : '' }>
                            Percentage
                        </option>

                        <option value="fixed" ${data.data.type == 'fixed' ? 'selected' : '' }>
                            Fixed
                        </option>
                    </select>
                </div>

                <!-- Expiry Date -->
                <div class="mb-6">
                    <label class="block mb-2 font-medium">
                        Expiry Date
                    </label>
                    <input value="${data.data.expiry_date}" type="date" id="expiry_date" class="w-full border rounded-lg p-3">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg">
                    Update Coupon
                </button>
            `;
    }

    editCoupon();

    document.getElementById('couponEditForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        let formData = new FormData();
        formData.append('code', document.getElementById('code').value);
        formData.append('type', document.getElementById('type').value);
        formData.append('expiry_date', document.getElementById('expiry_date').value);
        formData.append('discount_value', document.getElementById('discount_value').value);

        const id = new URLSearchParams(window.location.search).get('id');
        const response = await fetch(`api/cupon/update/${id}`, {
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