<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Order List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="max-w-7xl mx-auto py-8 px-4">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Order List</h1>
                <p class="text-gray-500 mt-1">Manage all customer orders</p>
            </div>
        </div>

        <!-- Order Table -->
        <div class="bg-white rounded-xl shadow overflow-x-auto">

            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left">Order ID</th>
                        <th class="px-6 py-4 text-left">Customer</th>
                        <th class="px-6 py-4 text-left">Phone</th>
                        <th class="px-6 py-4 text-left">Items</th>
                        <th class="px-6 py-4 text-left">Total</th>
                        <th class="px-6 py-4 text-left">Payment</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-left">Date</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>

                <tbody>

                <tbody>

                    <!-- Order -->
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold">#1001</td>
                        <td class="px-6 py-4">John Doe</td>
                        <td class="px-6 py-4">01712345678</td>
                        <td class="px-6 py-4">3</td>
                        <td class="px-6 py-4 font-semibold text-green-600">৳3,500</td>
                        <td class="px-6 py-4">COD</td>

                        <!-- Status Dropdown -->
                        <td class="px-6 py-4">
                            <select
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option selected>Pending</option>
                                <option>Confirmed</option>
                                <option>Processing</option>
                                <option>Shipped</option>
                                <option>Delivered</option>
                                <option>Cancelled</option>
                            </select>
                        </td>

                        <td class="px-6 py-4">01 Jul 2026</td>

                        <!-- Update Button -->
                        <td class="px-6 py-4 text-center">
                            <button
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition">
                                Update
                            </button>
                        </td>
                    </tr>

                </tbody>
                </tbody>
            </table>

        </div>

    </div>

</body>

</html>
