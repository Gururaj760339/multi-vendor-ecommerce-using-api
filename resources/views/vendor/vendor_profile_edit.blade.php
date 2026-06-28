<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Vendor Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-2xl bg-white rounded-2xl shadow-lg p-6">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Update Vendor Profile</h2>

            <a href="/vendor-profile" class="text-sm bg-gray-200 px-3 py-2 rounded-lg hover:bg-gray-300">
                ← Back
            </a>
        </div>

        <div id="errorBox" class="text-red-500 mb-3"></div>

        <form action="/vendor-profile/update" id="vendorProfileForm" enctype="multipart/form-data" class="space-y-4">
        </form>

    </div>

</body>

</html>

<script>
    async function showEditProfile() {

        const response = await fetch('/api/vendor/profile', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        
        document.getElementById('vendorProfileForm').innerHTML = `

        <div>
            <label class="block text-sm font-medium">Shop Name</label>
            <input type="text" id="shop_name" name="shop_name" value="${data.data.shop_name ?? ''}" class="w-full mt-1 p-3 border rounded-lg">
        </div>

        <div>
            <label class="block text-sm font-medium">Current Logo</label>
            <img id="logo_url" src="storage/${data.data.logo_url}" class="w-24 h-24 object-cover rounded mt-2">
            <input type="file" onchange="previewImage(event)" name="logo_url" accept="image/*" class="w-full mt-2 p-3 border rounded-lg">
        </div>

        <div>
            <label class="block text-sm font-medium">Current Banner</label>
            <img src="storage/${data.data.banner_url ?? ''}" id="banner_url" class="w-24 h-24 object-cover rounded mt-2">
            <input type="file" onchange="previewImage(event)" name="banner_url" accept="image/*" class="w-full mt-2 p-3 border rounded-lg">
        </div>

        <div>
            <label class="block text-sm font-medium">Address</label>
            <textarea name="address" rows="3" class="w-full mt-1 p-3 border rounded-lg">${data.data.address ?? ''}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium">Description</label>
            <textarea name="description" rows="4" class="w-full mt-1 p-3 border rounded-lg">${data.data.description ?? ''}</textarea>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg">
            Update Profile
        </button>

    `;
    }

    document.getElementById('vendorProfileForm').addEventListener('submit', async function(e){
        e.preventDefault();
        
        const id = new URLSearchParams(window.location.search).get('id');
        console.log(id);
        const formData = new FormData(this);

        const response = await fetch(`api/vendor/profile/update/${id}`, {
            method : 'POST',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            },
            body : formData
        });

        const data = await response.json();

        if(data.success){
            window.location.href = '/vendor-profile';
        } else {
            document.getElementById('errorBox').innerHTML = data.message;
        }
    })

    function previewImage(event){
        const file = event.target.files[0];

        if(file){
            const reader = new FileReader();

            reader.onload = function(e){
                document.getElementById('logo_url').src = e.target.result;
            }
            reader.onload = function(e){
                document.getElementById('banner_url').src = e.target.result;
            }

            reader.readAsDataURL(file);
        }

    }

    showEditProfile();
</script>
