<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw Requests</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body class="bg-gray-100">

    <div class="p-8">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">
                    Withdraw Requests
                </h2>
                <p class="text-gray-500 mt-1">
                    Manage vendor withdraw requests
                </p>
            </div>

            <div
                class="bg-blue-600 text-white px-5 py-3 rounded-xl flex items-center gap-3 shadow">
                <i class="fa-solid fa-money-bill-transfer text-xl"></i>
                <span class="font-semibold">Total Requests : 12</span>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-800 text-white">

                    <tr>
                        <th class="px-6 py-4 text-left">#</th>
                        <th class="px-6 py-4 text-left">Vendor</th>
                        <th class="px-6 py-4 text-left">Amount</th>
                        <th class="px-6 py-4 text-left">Method</th>
                        <th class="px-6 py-4 text-left">Account</th>
                        <th class="px-6 py-4 text-left">Date</th>
                        <th class="px-6 py-4 text-left">Current Status</th>
                        <th class="px-6 py-4 text-left">Update Status</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-200">

                    <!-- Row 1 -->
                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-5">1</td>

                        <td class="px-6 py-5">
                            John Vendor
                        </td>

                        <td class="px-6 py-5 font-semibold text-green-600">
                            $250
                        </td>

                        <td class="px-6 py-5">
                            Bank
                        </td>

                        <td class="px-6 py-5">
                            123456789
                        </td>

                        <td class="px-6 py-5">
                            04 Jul 2026
                        </td>

                        <td class="px-6 py-5">

                            <span
                                class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                Pending
                            </span>

                        </td>

                        <td class="px-6 py-5">

                            <select
                                class="w-44 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">

                                <option selected>Pending</option>
                                <option>Approved</option>
                                <option>Rejected</option>

                            </select>

                        </td>

                        <td class="px-6 py-5 text-center">

                            <button
                                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition">
                                Update
                            </button>

                        </td>

                    </tr>

                    <!-- Row 2 -->
                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-5">2</td>

                        <td class="px-6 py-5">
                            Alex Store
                        </td>

                        <td class="px-6 py-5 font-semibold text-green-600">
                            $520
                        </td>

                        <td class="px-6 py-5">
                            bKash
                        </td>

                        <td class="px-6 py-5">
                            017XXXXXXXX
                        </td>

                        <td class="px-6 py-5">
                            03 Jul 2026
                        </td>

                        <td class="px-6 py-5">

                            <span
                                class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                Approved
                            </span>

                        </td>

                        <td class="px-6 py-5">

                            <select
                                class="w-44 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">

                                <option>Pending</option>
                                <option selected>Approved</option>
                                <option>Rejected</option>

                            </select>

                        </td>

                        <td class="px-6 py-5 text-center">

                            <button
                                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition">
                                Update
                            </button>

                        </td>

                    </tr>

                    <!-- Row 3 -->
                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-5">3</td>

                        <td class="px-6 py-5">
                            Fashion House
                        </td>

                        <td class="px-6 py-5 font-semibold text-green-600">
                            $140
                        </td>

                        <td class="px-6 py-5">
                            Nagad
                        </td>

                        <td class="px-6 py-5">
                            018XXXXXXXX
                        </td>

                        <td class="px-6 py-5">
                            02 Jul 2026
                        </td>

                        <td class="px-6 py-5">

                            <span
                                class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                Rejected
                            </span>

                        </td>

                        <td class="px-6 py-5">

                            <select
                                class="w-44 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">

                                <option>Pending</option>
                                <option>Approved</option>
                                <option selected>Rejected</option>

                            </select>

                        </td>

                        <td class="px-6 py-5 text-center">

                            <button
                                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition">
                                Update
                            </button>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</body>

</html>