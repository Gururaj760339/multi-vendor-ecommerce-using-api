<!DOCTYPE html>
<html>

<head>
    <title>Professional Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex">

    <!-- Sidebar -->
    <div class="w-64 bg-gray-900 text-white min-h-screen shadow-lg">

        <div class="p-6 border-b border-gray-700">
            <h1 class="text-2xl font-bold">
                <i class="fa-solid fa-user-shield"></i>
                Admin
            </h1>
        </div>

        <ul class="p-4 space-y-2">
            <li>
                <a href="/admin-dashboard"
                    class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg font-medium shadow hover:bg-blue-700 transition">
                    <i class="fa-solid fa-chart-line w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="/admin-users"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fa-solid fa-users w-5 text-center"></i>
                    <span>Users</span>
                </a>
            </li>

            <li>
                <a href="/admin-vendors"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fa-solid fa-store w-5 text-center"></i>
                    <span>Vendors</span>
                </a>
            </li>

            <li>
                <a href="/admin-orders"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fa-solid fa-cart-shopping w-5 text-center"></i>
                    <span>Orders</span>
                </a>
            </li>

            <li>
                <a href="/admin-category"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fa-solid fa-list w-5 text-center"></i>
                    <span>Category</span>
                </a>
            </li>

            <li>
                <a href="/admin-payments"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fa-solid fa-credit-card w-5 text-center"></i>
                    <span>Payment</span>
                </a>
            </li>

            <li>
                <a href="/admin-reviews"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fa-solid fa-star w-5 text-center"></i>
                    <span>Reviews</span>
                </a>
            </li>

            <li>
                <a href="/admin-products"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fa-solid fa-box w-5 text-center"></i>
                        Products
                </a>
            </li>
        </ul>

        <!-- Logout -->
        <div class="absolute bottom-5 w-64 px-4">
            <button onclick="logOut()" class="w-full bg-red-600 hover:bg-red-700 p-3 rounded-lg">
                <i class="fa-solid fa-right-from-bracket mr-2"></i>
                Logout
            </button>
        </div>
    </div>

    <!-- Main -->
    <div class="flex-1">
        <!-- Top Navbar -->
        <div class="bg-white shadow p-5 flex justify-between items-center">
            <h2 class="text-xl font-bold">Dashboard </h2>

            <div>
                <span class="mr-4 text-gray-600">Admin</span>
                <img class="inline w-10 h-10 rounded-full" src="https://i.pravatar.cc/100">
            </div>
        </div>

        <div class="p-6">
            <h1 class="text-3xl font-bold mb-6"> Welcome Admin 👋</h1>

            <!-- Cards -->
            <div id="card-details" class="grid md:grid-cols-5 gap-6">

            </div>

            <!-- Table -->
            <div class="mt-8 bg-white shadow rounded-xl p-6">
                <h2 class="font-bold text-xl mb-4">Recent Orders</h2>

                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="p-3 text-left">ID</th>
                            <th class="p-3 text-left">Customer</th>
                            <th class="p-3 text-left">Status</th>
                        </tr>
                    </thead>

                    <tbody id="recent-orders">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    async function logOut() {
        await fetch('api/user/password', {
            'method': 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        });

        localStorage.removeItem('token');

        window.location.href = '/loginpage';
    }
</script>

<script>
    async function showAdminDashboard() {
        const response = await fetch('api/admin/dashboard', {
            method: 'GET',
            'headers': {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        console.log(data.recent_orders);

        document.getElementById('card-details').innerHTML = `
            <div class="bg-white rounded-xl shadow p-6">
                    <i class="fa-solid fa-users text-blue-600 text-3xl"></i>
                    <h3 class="mt-3 text-gray-500">Customers</h3>
                    <p class="text-3xl font-bold">${data.total_customers}</p>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <i class="fa-solid fa-store text-green-600 text-3xl"></i>
                    <h3 class="mt-3 text-gray-500">Vendors</h3>
                    <p class="text-3xl font-bold">${data.total_vendors}</p>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <i class="fa-solid fa-cart-shopping text-purple-600 text-3xl"></i>
                    <h3 class="mt-3 text-gray-500">Orders</h3>
                    <p class="text-3xl font-bold">${data.total_orders}</p>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <i class="fa-solid fa-dollar-sign text-yellow-500 text-3xl"></i>
                    <h3 class="mt-3 text-gray-500">Revenue</h3>
                    <p class="text-3xl font-bold">$${data.total_platform_revenue}</p>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <i class="fa-solid fa-dollar-sign text-yellow-500 text-3xl"></i>
                    <h3 class="mt-3 text-gray-500">Commission</h3>
                    <p class="text-3xl font-bold">$${data.total_commission_earned}</p>
                </div>
        `;

        let html = '';
        data.recent_orders.forEach(orders => {
            html += `
                <tr>
                    <td class="p-3">${orders.id}</td>
                    <td class="p-3">${orders.user.name}</td>

                    <td class="p-3">
                        <span class="bg-green-100 text-green-600 px-3 py-1 rounded">${orders.order_status}</span>
                    </td>
                </tr>
            `;
        })

        document.getElementById('recent-orders').innerHTML = html;
    }

    showAdminDashboard();
</script>
