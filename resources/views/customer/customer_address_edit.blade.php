<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Address</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white p-6 rounded-2xl shadow-lg">

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
            Edit Address
        </h2>

        <div id="errorBox" class="text-red-500 mb-3"></div>

        <form class="space-y-4" id="editForm">
        </form>

    </div>

</body>

</html>

<script>
    async function showEditAddress() {
        const addressId = new URLSearchParams(window.location.search).get('id');

        const response = await fetch(`api/user/edit/addresses/${addressId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
        });

        const data = await response.json();

        document.getElementById('editForm').innerHTML = `
            <div>
            <label class="block text-gray-700 mb-1">Address Line 1</label>
            <input 
                id="address_line1"
                type="text"
                name="address_line1"
                value="${data.data[0].address_line1}"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            >
        </div>

        <!-- Address Line 2-->

        <div>
            <label class="block text-gray-700 mb-1">Address Line 2</label>
            <input
                id="address_line2"
                type="text"
                name="address_line2"
                value="${data.data[0].address_line2}"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            >
        </div>

        <!-- City -->
        <div>
            <label class="block text-gray-700 mb-1">City</label>
            <input 
                id="city"
                type="text"
                name="city"
                value="${data.data[0].city}"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            >
        </div>

        <!-- Postal Code -->
        <div>
            <label class="block text-gray-700 mb-1">Postal Code</label>
            <input 
                id="postal_code"
                type="text"
                name="postal_code"
                value="${data.data[0].postal_code}"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            >
        </div>

        <div>
            <label class="block text-gray-700 mb-1">Phone</label>
            <input 
                id="phone"
                type="text"
                name="Phone"
                value="${data.data[0].phone}"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            >
        </div>

        <!-- Default Checkbox -->
        <div class="flex items-center space-x-2">
            <input type="checkbox" id="is_default" class="w-4 h-4">
            <label class="text-gray-700">Set as default address</label>
        </div>

        <!-- Buttons -->
        <div class="flex space-x-3">

            <button 
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition"
            >
                Update
            </button>

            <button 
                onclick="showAddress()"
                type="button"
                class="w-full bg-gray-400 hover:bg-gray-500 text-white py-2 rounded-lg font-semibold transition"
            >
                Cancel
            </button>

        </div>
            `;

        if (data.data[0].is_default === 1) {
            document.getElementById('is_default').checked = true;
        } else {
            document.getElementById('is_default').checked = false;
        }
    }

    showEditAddress();

    document.getElementById('editForm').addEventListener('submit', async function(e){
        e.preventDefault();

        const formData = new FormData();

        formData.append('address_line1', document.getElementById('address_line1').value);
        formData.append('address_line2', document.getElementById('address_line2').value);
        formData.append('city', document.getElementById('city').value);
        formData.append('postal_code', document.getElementById('postal_code').value);
        formData.append('phone', document.getElementById('phone').value);

        const checkBox = document.getElementById('is_default').checked;

        if(checkBox){
            formData.append('is_default', 1);
        } else {
            formData.append('is_default', 0);
        }

        const addressId = new URLSearchParams(window.location.search).get('id');
        const response = await fetch(`api/user/update/addresses/${addressId}`, {
            method : 'POST',
            headers : {
                'Accept' : 'application/json',
                'Authorization' : 'Bearer '+localStorage.getItem('token')
            },
            body : formData
        });

        const data = await response.json();

        if(data.success){
            window.location.href = "http://127.0.0.1:8000/all-address";
        } else {
            document.getElementById('errorBox').innerHTML = data.message;
        }
    });

    function showAddress(){
        window.location.href = 'all-address';
    }
</script>
