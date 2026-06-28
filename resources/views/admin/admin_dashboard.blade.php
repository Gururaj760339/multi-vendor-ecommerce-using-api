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


        <ul class="p-4 space-y-3">

            <li class="bg-blue-600 p-3 rounded-lg cursor-pointer">
                <i class="fa-solid fa-chart-line mr-2"></i>
                Dashboard
            </li>


            <li class="hover:bg-gray-700 p-3 rounded-lg cursor-pointer">
                <i class="fa-solid fa-users mr-2"></i>
                Users
            </li>


            <li class="hover:bg-gray-700 p-3 rounded-lg cursor-pointer">
                <i class="fa-solid fa-store mr-2"></i>
                Vendors
            </li>


            <li class="hover:bg-gray-700 p-3 rounded-lg cursor-pointer">
                <i class="fa-solid fa-cart-shopping mr-2"></i>
                Orders
            </li>

            <a href="/admin-category" class="hover:bg-gray-700 p-3 rounded-lg cursor-pointer">
                <i class="fa-solid fa-list mr-2"></i>
                Category
            </a>


            <li class="hover:bg-gray-700 p-3 rounded-lg cursor-pointer">
                <i class="fa-solid fa-gear mr-2"></i>
                Settings
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

            <h2 class="text-xl font-bold">
                Dashboard
            </h2>


            <div>

                <span class="mr-4 text-gray-600">
                    Admin
                </span>

                <img class="inline w-10 h-10 rounded-full" src="https://i.pravatar.cc/100">

            </div>


        </div>



        <div class="p-6">


            <h1 class="text-3xl font-bold mb-6">
                Welcome Admin 👋
            </h1>



            <!-- Cards -->

            <div class="grid md:grid-cols-4 gap-6">



                <div class="bg-white rounded-xl shadow p-6">

                    <i class="fa-solid fa-users text-blue-600 text-3xl"></i>

                    <h3 class="mt-3 text-gray-500">
                        Customers
                    </h3>

                    <p class="text-3xl font-bold">
                        1200
                    </p>

                </div>




                <div class="bg-white rounded-xl shadow p-6">

                    <i class="fa-solid fa-store text-green-600 text-3xl"></i>

                    <h3 class="mt-3 text-gray-500">
                        Vendors
                    </h3>

                    <p class="text-3xl font-bold">
                        45
                    </p>

                </div>




                <div class="bg-white rounded-xl shadow p-6">

                    <i class="fa-solid fa-cart-shopping text-purple-600 text-3xl"></i>

                    <h3 class="mt-3 text-gray-500">
                        Orders
                    </h3>

                    <p class="text-3xl font-bold">
                        300
                    </p>

                </div>




                <div class="bg-white rounded-xl shadow p-6">

                    <i class="fa-solid fa-dollar-sign text-yellow-500 text-3xl"></i>

                    <h3 class="mt-3 text-gray-500">
                        Revenue
                    </h3>

                    <p class="text-3xl font-bold">
                        $5000
                    </p>

                </div>



            </div>





            <!-- Table -->

            <div class="mt-8 bg-white shadow rounded-xl p-6">


                <h2 class="font-bold text-xl mb-4">
                    Recent Orders
                </h2>


                <table class="w-full">

                    <tr class="border-b">

                        <th class="p-3 text-left">
                            ID
                        </th>

                        <th class="p-3 text-left">
                            Customer
                        </th>

                        <th class="p-3 text-left">
                            Status
                        </th>


                    </tr>



                    <tr>

                        <td class="p-3">
                            #1001
                        </td>

                        <td class="p-3">
                            Rahim
                        </td>

                        <td class="p-3">
                            <span class="bg-green-100 text-green-600 px-3 py-1 rounded">
                                Completed
                            </span>
                        </td>

                    </tr>


                    <tr>

                        <td class="p-3">
                            #1002
                        </td>

                        <td class="p-3">
                            Karim
                        </td>

                        <td class="p-3">

                            <span class="bg-yellow-100 text-yellow-600 px-3 py-1 rounded">
                                Pending
                            </span>

                        </td>

                    </tr>


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
