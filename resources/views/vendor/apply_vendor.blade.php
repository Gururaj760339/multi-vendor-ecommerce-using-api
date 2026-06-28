<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Apply</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white p-6 rounded-2xl shadow-lg">

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
            Vendor Apply Form
        </h2>

        <div id="errorBox" class="text-red-500 mb-3"></div>

        <form id="vendorForm" class="space-y-4">

            <!-- Shop Name -->
            <div>
                <label class="block text-gray-700 mb-1">Shop Name</label>
                <input type="text" id="shop_name" name="shop_name" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none"
                    placeholder="Enter your shop name">
            </div>

            <!-- Description -->
            <div>
                <label class="block text-gray-700 mb-1">Description</label>
                <textarea id="description" name="description" required rows="4"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none"
                    placeholder="Write about your shop"></textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-lg font-semibold transition">
                Apply Now
            </button>

        </form>
    </div>

</body>

</html>

<script>
    document.getElementById('vendorForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData();

        formData.append('shop_name', document.getElementById('shop_name').value);
        formData.append('description', document.getElementById('description').value);

        const response = await fetch('api/vendor/apply', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();

        if(data.success){
            window.location.href = '/loginpage';
        } else {
            document.getElementById('errorBox').innerHTML = data.message
        }
    })
</script>
