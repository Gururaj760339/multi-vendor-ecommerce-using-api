<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 text-white hidden md:block">

            <div class="p-6 border-b border-gray-700">
                <h1 class="text-2xl font-bold">
                    <i class="fa-solid fa-store"></i> Vendor
                </h1>
            </div>

            <nav class="p-5 space-y-3">
                <a onclick="showSection('dashboard')" class="block p-3 rounded-lg bg-indigo-600 cursor-pointer">
                    <i class="fa-solid fa-chart-line mr-2"></i>
                    Dashboard
                </a>

                <a href="/vendor-profile" class="block p-3 rounded-lg hover:bg-slate-700 cursor-pointer">
                    <i class="fa-solid fa-user mr-2"></i>
                    Profile
                </a>

                <a href="/vendor-products" class="block p-3 rounded-lg hover:bg-slate-700">
                    <i class="fa-solid fa-box mr-2"></i>
                    Products
                </a>

                <a href="/vendor-products-images" class="block p-3 rounded-lg hover:bg-slate-700">
                    <i class="fa-solid fa-image mr-2"></i>
                    Products Images
                </a>

                <a href="/vendor-coupons" class="block p-3 rounded-lg hover:bg-slate-700">
                    <i class="fa-solid fa-ticket mr-2"></i>
                    Coupon
                </a>

                <a href="/vendor-orders" class="block p-3 rounded-lg hover:bg-slate-700">
                    <i class="fa-solid fa-cart-shopping mr-2"></i>
                    Orders
                </a>

                <a href="/vendor-withdraw-history" class="block p-3 rounded-lg hover:bg-slate-700">
                    <i class="fa-solid fa-wallet mr-2"></i>
                    Withdraw
                </a>

                <a href="/vendor-earning-history" class="block p-3 rounded-lg hover:bg-slate-700">
                    <i class="fa-solid fa-coins mr-2"></i>
                    Earning
                </a>
            </nav>
        </aside>
        <!-- Main -->
        <div class="flex-1">
            <!-- Header -->
            <header class="bg-white shadow p-5 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-700">
                        Vendor Panel
                    </h1>
                    <p class="text-gray-500">
                        Manage your shop easily
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <h3 class="font-bold">Rahim Store</h3>
                        <p class="text-sm text-gray-500">Vendor</p>
                    </div>
                    <img src="https://i.pravatar.cc/150" class="w-12 h-12 rounded-full border">

                    <button onclick="logOut()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Logout
                    </button>
                </div>
            </header>

            <main class="p-6">
                <!-- DASHBOARD SECTION -->
                <div id="dashboard">
                    <h1 class="text-3xl font-bold mb-6">
                        Dashboard
                    </h1>
                    <div id="card-details" class="grid md:grid-cols-7 gap-6">
                    </div>

                </div>

            </main>

        </div>

    </div>

</body>

</html>

<script>
    async function logOut() {
        await fetch('api/auth/logout', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        });

        localStorage.removeItem('token');
        window.location.href = '/loginpage';
    }

    async function showVendorDashboard(){
        const response = await fetch('api/vendor/dashboard', {
            method : 'GET',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });

        const data = await response.json();
        //console.log(data);

        document.getElementById('card-details').innerHTML = `
            <div class="bg-white p-6 rounded-xl shadow">
                            <i class="fa-solid fa-box text-indigo-600 text-3xl"></i>
                            <p class="text-gray-500 mt-3">Products</p>
                            <h2 class="text-3xl font-bold">${data.total_products ? data.total_products : 0}</h2>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow">
                            <i class="fa-solid fa-cart-shopping text-green-600 text-3xl"></i>
                            <p class="text-gray-500 mt-3">Orders</p>
                            <h2 class="text-3xl font-bold">${data.total_order ? data.total_order : 0}</h2>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow">
                            <i class="fa-solid fa-dollar-sign text-yellow-500 text-3xl"></i>
                            <p class="text-gray-500 mt-3">Sales</p>
                            <h2 class="text-3xl font-bold">$${data.total_sales ? data.total_sales : 0}</h2>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow">
                            <i class="fa-solid fa-wallet text-purple-600 text-3xl"></i>
                            <p class="text-gray-500 mt-3">Earnings</p>
                            <h2 class="text-3xl font-bold">$${data.net_earnings ? data.net_earnings : 0}</h2>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow">
                            <i class="fa-solid fa-hourglass-half text-yellow-500 text-3xl"></i>                            
                            <p class="text-gray-500 mt-3">Pending Sales</p>
                            <h2 class="text-3xl font-bold">$${data.pending_total_sales ? data.pending_total_sales : 0}</h2>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow">
                            <i class="fa-solid fa-coins text-yellow-500 text-3xl"></i>                            
                            <p class="text-gray-500 mt-3">Pending Earnings</p>
                            <h2 class="text-3xl font-bold">$${data.pending_net_earnings ? data.pending_net_earnings : 0}</h2>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow">
                            <i class="fa-solid fa-money-bill-transfer text-purple-600 text-3xl"></i>                           
                            <p class="text-gray-500 mt-3">Pending Withdraw</p>
                            <h2 class="text-3xl font-bold">${data.pending_withdrawals ? data.pending_withdrawals : 0}</h2>
                        </div>
        `;
    }

    showVendorDashboard();
</script>
