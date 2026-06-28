<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Address</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white p-6 rounded-2xl shadow-lg">

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
            Add New Address
        </h2>

        <div id="errorBox" class="text-red-500 mb-3"></div>

        <form class="space-y-4" id="addressForm">

            <!-- Address Line 1-->
            <div>
                <label class="block text-gray-700 mb-1">Address Line 1</label>
                <input id="address_line1" type="text" name="address_line1" placeholder="Enter Your Address Line 1"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <!-- Address Line 2 -->
            <div>
                <label class="block text-gray-700 mb-1">Address Line 2</label>
                <input id="address_line2" type="text" name="address_line2" placeholder="Enter Your Address Line 2"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <!-- City -->
            <div>
                <label class="block text-gray-700 mb-1">City</label>
                <input id="city" type="text" name="city" placeholder="Enter city"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <!-- Postal Code -->
            <div>
                <label class="block text-gray-700 mb-1">Postal Code</label>
                <input id="postal_code" type="text" name="postal_code" placeholder="Enter postal code"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Phone</label>
                <input id="phone" type="text" name="phone" placeholder="Enter Your Phone Number"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <!-- Default Checkbox -->
            <div class="flex items-center space-x-2">
                <input id="checkbox" type="checkbox" name="is_default" class="w-4 h-4">
                <label class="text-gray-700">Set as default address</label>
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-semibold transition">
                Save Address
            </button>

        </form>

    </div>

</body>

</html>

<script>
    
        document.getElementById('addressForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData();

            formData.append('address_line1', document.getElementById('address_line1').value);
            formData.append('address_line2', document.getElementById('address_line2').value);
            formData.append('city', document.getElementById('city').value);
            formData.append('postal_code', document.getElementById('postal_code').value);
            formData.append('phone', document.getElementById('phone').value);

            const is_default = document.getElementById('checkbox').checked;
            if(is_default){
                formData.append('is_default', 1);
            } else {
                formData.append('is_default', 0);
            }

            const response = await fetch('api/user/add/addresses', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                body : formData
            });

            const data = await response.json();

            if(data.success){
                window.location.href = 'http://127.0.0.1:8000/all-address';
            } else {
                document.getElementById('errorBox').innerHTML = data.message;
            }
        })
    
</script>
