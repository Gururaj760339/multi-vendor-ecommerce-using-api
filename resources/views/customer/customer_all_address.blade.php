<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Address</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-3xl mx-auto">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                My Addresses
            </h2>

            <form action="/add-customer-address" enctype="multipart/form-data">
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold">
                    + Add New
                </button>
            </form>

        </div>

        <div id="errorBox" class="text-red-500 mb-3"></div>

        <!-- Address List -->
        <div class="space-y-4" id="mainCard">
        </div>

    </div>

</body>

</html>

<script>
    async function myAddress(){
        const response = await fetch('api/user/addresses', {
            method : 'GET',
            headers : {
                'Accept' : 'application/json',
                'Authorization' : 'Bearer '+localStorage.getItem('token')
            }
        });

        const res = await response.json();

            let html = '';
            res.data.forEach(item =>{
                let isDefault = '';

                if(item.is_default === 1){
                    isDefault = 'Default Address';
                }
            html += `
            <div class="bg-white p-5 rounded-xl shadow flex justify-between items-start">

                <div>
                    <p class="font-semibold text-gray-800">${isDefault}</p>
                    <p class="text-gray-600 text-sm">
                        ${item.address_line1}
                    </p>
                    <p class="text-gray-500 text-sm">
                       ${item.address_line2}
                    </p>
                </div>

                <div class="flex space-x-2">

   
                    <button onclick="addressEditPage(${item.id})" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-sm">
                        Edit
                    </button>

                    <button onclick="DeleteAddress(${item.id})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm">
                        Delete
                    </button>

                </div>

            </div>
            `;

            });

            document.getElementById('mainCard').innerHTML = html;
    }

    function addressEditPage(id){
        window.location.href = `/edit-customer-address?id=${id}`
    }

    async function DeleteAddress(addressId) {
        const response = await fetch(`api/user/addresses/${addressId}`, {
            method : 'DELETE',
            headers : {
                'Accept' : 'application/json',
                'Authorization' : 'Bearer '+localStorage.getItem('token')
            }
        });

        const data = await response.json();

        if(data.success){
            myAddress();
        } else {
            document.getElementById('errorBox').innerHTML = data.message;
        }
    }

    myAddress();
</script>
