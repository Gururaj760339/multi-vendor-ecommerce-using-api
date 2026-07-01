<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Vendor Orders</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="max-w-7xl mx-auto py-8 px-4">

        <div class="bg-white rounded-xl shadow overflow-hidden">

            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h2 class="text-2xl font-bold">Orders</h2>
            </div>

            <table class="w-full">

                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-4 text-left">Order ID</th>
                        <th class="p-4 text-left">Customer</th>
                        <th class="p-4 text-left">Phone</th>
                        <th class="p-4 text-left">Date</th>
                        <th class="p-4 text-left">Items</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>

                <tbody>

                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4 font-semibold">#1001</td>
                        <td class="p-4">John Doe</td>
                        <td class="p-4">01712345678</td>
                        <td class="p-4">01 Jul 2026</td>
                        <td class="p-4">2</td>
                        <td class="p-4">
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm">
                                Processing
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                View
                            </button>
                        </td>
                    </tr>

                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4 font-semibold">#1002</td>
                        <td class="p-4">Alice Smith</td>
                        <td class="p-4">01812345678</td>
                        <td class="p-4">02 Jul 2026</td>
                        <td class="p-4">1</td>
                        <td class="p-4">
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">
                                Delivered
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                View
                            </button>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50">
                        <td class="p-4 font-semibold">#1003</td>
                        <td class="p-4">Rahim Ahmed</td>
                        <td class="p-4">01912345678</td>
                        <td class="p-4">03 Jul 2026</td>
                        <td class="p-4">4</td>
                        <td class="p-4">
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm">
                                Cancelled
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                View
                            </button>
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</body>

</html>