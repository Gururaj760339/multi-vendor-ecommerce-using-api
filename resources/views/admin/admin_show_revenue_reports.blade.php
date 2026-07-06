<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Sales Report</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
</head>

<body class="bg-gray-100">
<div class="min-h-screen p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Monthly Sales Report</h1>
            <p class="text-gray-500 mt-1">View Gross Sales & Commission by Year and Month</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-md p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <!-- Year -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Select Year</label>

                <select id="filter-year"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="2027">2027</option>
                    <option selected value="2026">2026</option>
                    <option value="2025">2025</option>
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                </select>
            </div>

            <!-- Month -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Select Month </label>

                <select id="filter-month"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>

            <!-- Button -->
            <div class="md:col-span-2 flex items-end">
                <button onclick="showRevenueReport()"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl py-3 transition duration-300">
                    <i class="fa-solid fa-magnifying-glass mr-2"></i>
                    Generate Report
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div id="revenue-summery" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    </div>
</div>
</body>
</html>

<script>
    async function showRevenueReport(){
        const response = await fetch('api/admin/revenue/reports', {
            method : 'POST',
            'headers' : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json',
                'Content-Type' : 'application/json'
            },

            body : JSON.stringify({
                month : document.getElementById('filter-month').value,
                year : document.getElementById('filter-year').value
            })
        });

        const data = await response.json();

        document.getElementById('revenue-summery').innerHTML = `
        <!-- Gross Sales -->
        <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-green-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 font-medium">Gross Sales</p>
                    <h2 class="text-3xl font-bold text-gray-800 mt-2">$${data.gross_sales}</h2>
                </div>

                <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fa-solid fa-sack-dollar text-3xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Commission -->
        <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 font-medium">Commission</p>
                    <h2 class="text-3xl font-bold text-gray-800 mt-2">$${data.commission_amount}</h2>
                </div>

                <div class="w-16 h-16 rounded-full bg-purple-100 flex items-center justify-center">
                    <i class="fa-solid fa-wallet text-3xl text-purple-600"></i>
                </div>
            </div>
        </div>
        
        `;
        
    }
</script>