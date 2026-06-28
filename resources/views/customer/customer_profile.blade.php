<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-6" id="profileDiv">

    </div>

</body>

</html>

<script>
    async function myProfile(){
        const response = await fetch('api/user/profile', {
            method : 'GET',
            headers : {
                'Authorization' : 'Bearer ' + localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });

        const data = await response.json();
        console.log(data);

        document.getElementById('profileDiv').innerHTML = `
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
            User Profile
        </h2>

        <!-- Avatar -->
        <div class="flex flex-col items-center mb-6">
            <img src="" id="avatar" class="w-24 h-24 rounded-full border-4 border-blue-500">

            <h3 class="mt-3 text-xl font-semibold text-gray-800">
                ${data.data.name}
            </h3>
        </div>

        <!-- Info -->
        <div class="space-y-3 text-gray-700">

            <div class="flex justify-between border-b pb-2">
                <span class="font-semibold">Name:</span>
                <span>${data.data.name}</span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <span class="font-semibold">Email:</span>
                <span>${data.data.email}</span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <span class="font-semibold">Phone:</span>
                <span>${data.data.phone}</span>
            </div>

        </div>

        <!-- Buttons -->
        <div class="mt-6 space-y-3">

            <!-- Edit Profile -->
                <button onclick="showProfile(${data.data.id})" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition">
                    Edit Profile
                </button>


            <!-- Address Button -->
            <form action="/all-address" enctype="multipart/form-data">
                <button
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-semibold transition">
                    Manage Address
                </button>
            </form>

        </div>
        `;

        document.getElementById('avatar').src = `storage/${data.data.avatar}`;
    }

    function showProfile(id){
        window.location.href = `/profile-edit?id=${id}`
    }

    myProfile();
</script>
