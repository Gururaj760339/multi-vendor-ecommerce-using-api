<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-6">

        <!-- Title -->
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
            Edit Profile
        </h2>

        <!-- Avatar -->
        <div class="flex flex-col items-center mb-6">
            <img id="profileImage" src="" class="w-24 h-24 rounded-full border-4 border-blue-500 object-cover">

            <label class="mt-3 cursor-pointer text-sm text-blue-600 hover:underline">
                Change Photo
                <input type="file" id="avatarInput" class="hidden" onchange="previewImage(event)">
            </label>
        </div>

        <div id="errorBox" class="text-red-500 mb-3"></div>

        <!-- Form -->
        <form class="space-y-4" id="formSection">
        </form>

    </div>

</body>

</html>

<script>
    async function showProfile() {
        const id = new URLSearchParams(window.location.search).get('id');
        const response = await fetch(`api/user/show/edit-profile/${id}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
        });

        const data = await response.json();

        document.getElementById('formSection').innerHTML = `
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Name</label>
                <input type="text" value="${data.data.name}" id="name"
                    class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter your name">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" value="${data.data.email}" id="email"
                    class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter your email">
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Phone</label>
                <input type="text" value="${data.data.phone}" id="phone"
                    class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter your phone number">
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-2">

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition">
                    Save Changes
                </button>

                <button type="button" onclick="myProfilePage()"
                    class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 rounded-lg font-semibold transition">
                    Cancel
                </button>

            </div>
            `
        document.getElementById('profileImage').src = 'storage/' + data.data.avatar

    }

    function previewImage(event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById('profileImage').src = e.target.result;
            }

            reader.readAsDataURL(file);
        }

    }

    document.getElementById('formSection').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData();

        formData.append('name', document.getElementById('name').value);
        formData.append('email', document.getElementById('email').value);
        formData.append('phone', document.getElementById('phone').value);

        const fileInput = document.getElementById('avatarInput');

        if (fileInput.files.length > 0) {
            formData.append('avatar', fileInput.files[0]);
        }

        const id = new URLSearchParams(window.location.search).get('id');

        const response = await fetch(`api/user/edit-profile/${id}`, {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            window.location.href = "http://127.0.0.1:8000/profile";
        } else {
            document.getElementById('errorBox').innerHTML = data.message;
        }
    })
    
    function myProfilePage(){
        window.location.href = "http://127.0.0.1:8000/profile";
    }


    showProfile();
</script>
