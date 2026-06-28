<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg">

    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
        Change Password
    </h2>

    <div id="errorBox" class="text-red-500 mb-3"></div>

    <form id="changeForm" class="space-y-5" enctype="multipart/form-data">


        <!-- Email -->
        <div>
            <label class="block text-gray-700 mb-1">
                Email Address
            </label>

            <input 
                id="email"
                type="email"
                name="email"
                placeholder="Enter Your Email"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            >
        </div>


        <!-- Current Password -->
        <div>
            <label class="block text-gray-700 mb-1">
                Current Password
            </label>

            <input 
                id="current_password"
                type="password"
                name="current_password"
                placeholder="Enter current password"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            >
        </div>


        <!-- New Password -->
        <div>
            <label class="block text-gray-700 mb-1">
                New Password
            </label>

            <input 
                id="new_password"
                type="password"
                name="password"
                placeholder="Enter new password"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            >
        </div>


        <!-- Confirm Password -->
        <div>
            <label class="block text-gray-700 mb-1">
                Confirm Password
            </label>

            <input 
                id="new_password_confirmation"
                type="password"
                name="password_confirmation"
                placeholder="Confirm password"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            >
        </div>


        <button
            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold">
            Update Password
        </button>


    </form>

</div>


</body>
</html>

<script>
    function changePassword(){
        document.getElementById('changeForm').addEventListener('submit', async function(e){
            e.preventDefault();

            formData = new FormData();
            const errorBox = document.getElementById('errorBox');

            formData.append('email', document.getElementById('email').value);
            formData.append('old_password', document.getElementById('current_password').value);
            formData.append('password', document.getElementById('new_password').value);
            formData.append('password_confirmation', document.getElementById('new_password_confirmation').value);

            const response = await fetch('api/user/password', {
                method : 'POST',
                headers: {
                    'Accept' : 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if(data.success){
                window.location.href = 'http://127.0.0.1:8000/loginpage'
            } else {
                errorBox.innerHTML = data.message;
            }
        })
    }

    changePassword();
</script>