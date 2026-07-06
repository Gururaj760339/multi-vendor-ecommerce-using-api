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
                <h2 class="text-3xl font-bold text-gray-800">Withdraw Requests</h2>
                <p class="text-gray-500 mt-1">Manage vendor withdraw requests</p>
            </div>

            <div
                class="bg-blue-600 text-white px-5 py-3 rounded-xl flex items-center gap-3 shadow">
                <i class="fa-solid fa-money-bill-transfer text-xl"></i>
                <p id="total-request" class="font-semibold"></p>
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
                        <th class="px-6 py-4 text-left">Admin Note (Optional)</th>
                        <th class="px-6 py-4 text-left">Update Status</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>

                <tbody id="withdraw-details" class="divide-y divide-gray-200">
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<script>
    
    async function showWithdawDetails(){
        const response = await fetch('api/admin/pending/withdrawals', {
            method : 'GET',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });

        const data = await response.json();
        document.getElementById('total-request').innerHTML = `Total Requests : ${data.totalPendingRequest}`;
        
        let html = '';
        data.data.forEach(withdraw => {
            let createdAt = new Date(withdraw.created_at);
            let formatedDate = createdAt.getDay()+ '-' +createdAt.getMonth()+ '-' +createdAt.getFullYear();
            html += `
                <tr class="hover:bg-gray-50">
                        <td class="px-6 py-5">${withdraw.id}</td>
                        <td class="px-6 py-5">${withdraw.vendor.user.name}</td>
                        <td class="px-6 py-5 font-semibold text-green-600">$${withdraw.amount}</td>
                        <td class="px-6 py-5">${withdraw.payment_method}</td>
                        <td class="px-6 py-5">${withdraw.payment_details}</td>
                        <td class="px-6 py-5">${formatedDate}</td>

                        <td class="px-6 py-5">
                            <span
                                class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                ${withdraw.status}
                            </span>
                        </td>

                        <td class="px-6 py-5">
                            <textarea id="admin_note"
                                class="w-64 border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                                rows="2"
                                placeholder="Write admin note...">${withdraw.admin_note ?? ''}</textarea>
                        </td>

                        <td class="px-6 py-5">
                            <select id="status"
                                class="w-44 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="pending" ${withdraw.status == 'pending' ? 'selected' : ''}>Pending</option>
                                <option value="approved" ${withdraw.status == 'approved' ? 'selected' : ''}>Approved</option>
                                <option value="rejected" ${withdraw.status == 'rejected' ? 'selected' : ''}>Rejected</option>
                            </select>
                        </td>

                        <td class="px-6 py-5 text-center">
                            <button onclick="updateWithdrawStatus(${withdraw.id})"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition">
                                Update
                            </button>
                        </td>
                    </tr>
            `;
        });

        document.getElementById('withdraw-details').innerHTML = html;
    }

    showWithdawDetails();

    async function updateWithdrawStatus(withdrawId){
        const response = await fetch(`api/admin/withdrawals/status/update/${withdrawId}`,{
            method : 'POST',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Content-Type' : 'application/json'
            },
            body : JSON.stringify({
                status : document.getElementById('status').value,
                admin_note : document.getElementById('admin_note').value
            })
        });

        showWithdawDetails();
    }
</script>