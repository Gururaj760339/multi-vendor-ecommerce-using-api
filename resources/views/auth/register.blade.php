<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register - ShopMart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white w-full max-w-md p-8 rounded-xl shadow-lg">

        <h2 class="text-3xl font-bold text-center mb-6 text-blue-600">
            Create Account
        </h2>

        <div id="errorBox" class="text-red-500 mb-3"></div>

        <form id="registerForm" enctype="multipart/form-data">

            <!-- Name -->
            <div class="mb-3">
                <label class="block mb-1 text-gray-700">Name</label>
                <input type="text" id="name"
                    class="w-full border px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter name">
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="block mb-1 text-gray-700">Email</label>
                <input type="email" id="email"
                    class="w-full border px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter email">
            </div>

            <!-- Phone -->
            <div class="mb-3">
                <label class="block mb-1 text-gray-700">Phone</label>
                <input type="text" id="phone"
                    class="w-full border px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter phone">
            </div>

            <!-- Role -->
            <div class="mb-3">
                <label class="block mb-1 text-gray-700">Role</label>
                <select id="role" class="w-full border px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="customer">Customer</option>
                </select>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="block mb-1 text-gray-700">Password</label>
                <input type="password" id="password"
                    class="w-full border px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter password">
            </div>

            <!-- Avatar -->
            <div class="mb-4">
                <label class="block mb-1 text-gray-700">Avatar</label>
                <input type="file" id="avatar" class="w-full border px-4 py-2 rounded-lg">
            </div>

            <!-- Button -->
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                Register
            </button>

        </form>

        <p class="text-center mt-4 text-gray-600">
            Already have an account?
            <a href="/loginpage" class="text-blue-600 font-semibold">Login</a>
        </p>

    </div>

</body>

</html>

<script>
    function register() {
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            let errorBox = document.getElementById('errorBox');
            let formData = new FormData();

            formData.append('name', document.getElementById('name').value);
            formData.append('email', document.getElementById('email').value);
            formData.append('phone', document.getElementById('phone').value);
            formData.append('role', document.getElementById('role').value);
            formData.append('password', document.getElementById('password').value,);
            formData.append('avatar', document.getElementById('avatar').files[0]);

            const response = await fetch('api/auth/register', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if(data.success){
                window.location.href = 'http://127.0.0.1:8000/loginpage'
            }else {
                let errors = data.message;
                errorBox.innerHTML = errors;
            }
        })
    }

    register();
</script>
