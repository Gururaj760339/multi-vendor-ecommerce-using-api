<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body class="bg-gray-100">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-6 py-5 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fa-solid fa-store mr-2 text-blue-600"></i>
                Vendor Management
            </h1>
        </div>
    </div>

    <!-- Vendor Table -->
    <div class="max-w-7xl mx-auto mt-8 px-6">
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left">#</th>
                        <th class="px-6 py-4 text-left">Vendor</th>
                        <th class="px-6 py-4 text-left">Shop</th>
                        <th class="px-6 py-4 text-left">Email</th>
                        <th class="px-6 py-4 text-left">Current Status</th>
                        <th class="px-6 py-4 text-left">Joined</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>

                <tbody id="vendors-details" class="divide-y">
                    
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<script>
    async function showVendors(){
        const response = await fetch('api/admin/vendors/list', {
            method : 'GET',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });

        const data = await response.json();
        //console.log(data);
        let html = '';
        data.data.forEach(vendors => {
            let createdAt = new Date(vendors.created_at);
            let formmatedDate = createdAt.getDate()+ '-' +createdAt.getMonth()+ '-' +createdAt.getFullYear();
            html += `
                <tr class="hover:bg-gray-50">
                        <td class="px-6 py-5">${vendors.id}</td>
                        <td class="px-6 py-5 font-semibold">${vendors.user.name}</td>
                        <td class="px-6 py-5">${vendors.shop_name}</td>
                        <td class="px-6 py-5">${vendors.user.email}</td>

                        <!-- Current Status -->
                        <td class="px-6 py-5">
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">
                                ${vendors.status}
                            </span>
                        </td>

                        <td class="px-6 py-5">${formmatedDate}</td>

                        <!-- Status Dropdown -->
                        <td class="px-6 py-5">
                            <select id="status"
                                class="w-44 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option selected disabled>Select Status</option>
                                <option value="pending" ${vendors.status == 'pending' ? 'selected' : ''}>Pending</option>
                                <option value="approved" ${vendors.status == 'approved' ? 'selected' : ''}>Approve</option>
                                <option value="suspanded" ${vendors.status == 'suspanded' ? 'selected' : ''}>Suspend</option>
                            </select>
                        </td>

                        <!-- Update Button -->
                        <td class="px-6 py-5 text-center">
                            <button onclick="updateVendorStatus(${vendors.id})" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
                                <i class="fa-solid fa-floppy-disk mr-1"></i>
                                Update
                            </button>
                        </td>
                    </tr>
            `;
        });

        document.getElementById('vendors-details').innerHTML = html;
    }

    showVendors();

    async function updateVendorStatus(vendorId){
        const newStatus = document.getElementById('status').value;
        const response = await fetch(`api/admin/vendor/status/${vendorId}`, {
            method : 'POST',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Content-Type' : 'application/json'
            },
            body : JSON.stringify({
                status : newStatus
            })
        });
        showVendors();
    }


</script>
