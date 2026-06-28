<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white w-full max-w-md p-8 rounded-xl shadow-lg">

        <h2 class="text-3xl font-bold text-center mb-6">
            Login
        </h2>

        <div id="errorBox" class="text-red-500 mb-3"></div>

        <form id="loginForm">
            <!-- Email -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">
                    Email
                </label>

                <input type="email" id="email" placeholder="Enter your email"
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Password -->
            <div class="mb-4">

                <label class="block text-gray-700 mb-2">
                    Password
                </label>

                <input type="password" id="password" placeholder="Enter your password"
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Forget Password -->
            <div class="text-right mb-5">
                <a href="/change-password" class="text-blue-600 hover:underline">
                    Forget Password?
                </a>
            </div>

            <!-- Login Button -->
            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700">
                Login
            </button>
        </form>

        <!-- Signup -->
        <p class="text-center mt-6 text-gray-600">
            Don't have an account?
            <a href="/register" class="text-blue-600 font-semibold hover:underline">
                New Account Signup
            </a>
        </p>
    </div>

</body>

</html>

<script>
    function login() {
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const errorBox =  document.getElementById('errorBox');
            const response = await fetch('/api/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value

                })
            });

            const data = await response.json();
            
            if(data.success){
                localStorage.setItem('token', data.token);

                if(data.data.role === 'customer'){
                    window.location.href = 'http://127.0.0.1:8000/'
                } else if(data.data.role === 'vendor'){
                    window.location.href = 'http://127.0.0.1:8000/vendor-dashboard'
                } else if(data.data.role === 'admin'){
                    window.location.href = 'http://127.0.0.1:8000/admin-dashboard'
                }
               
            }else{
                errorBox.innerHTML = data.message;
            }
        })
    }

    login();
</script>
