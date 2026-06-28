<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vendor Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

  <div class="max-w-4xl mx-auto mt-10">

    <!-- Top Buttons -->
    <div class="flex justify-between items-center mb-4">

      <!-- Back Button -->
      <a href="javascript:history.back()"
         class="flex items-center gap-2 bg-white px-4 py-2 rounded-lg shadow hover:bg-gray-100">
        ← Back
      </a>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" id="profileMain">
    </div>

  </div>

</body>
</html>

<script>
    async function showProfile(){
        const response = await fetch('api/vendor/profile', {
            method : 'GET',
            headers : {
                'Accept' : 'application/json',
                'Authorization' : 'Bearer '+localStorage.getItem('token')
            }
        });

        const data = await response.json();

        document.getElementById('profileMain').innerHTML = `
        <!-- Edit Button -->
      <a onclick="editVendor(${data.data.id})"
         class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700">
        ✎ Edit Profile
      </a>
        <!-- Banner -->
      <div class="h-48 bg-gray-300">
        <img src=""
            id="banner"
             class="w-full h-full object-cover"
             alt="Banner">
      </div>

      <!-- Profile Section -->
      <div class="p-6">

        <div class="flex items-center space-x-4">
          <!-- Logo -->
          <img src=""
                id="logo"
               class="w-20 h-20 rounded-full border-4 border-white shadow-md"
               alt="Logo">

          <div>
            <h2 class="text-2xl font-bold text-gray-800">${data.data.shop_name}</h2>
            <p class="text-sm text-gray-500">${data.data.status} Vendor</p>
          </div>
        </div>

        <!-- Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">

          <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-500">Address</p>
            <p class="font-medium text-gray-800">${data.data.address}</p>
          </div>

          <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-500">Commission Rate</p>
            <p class="font-medium text-gray-800">${data.data.commission_rate}%</p>
          </div>

          <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-500">Status</p>
            <span class="px-3 py-1 text-sm bg-green-100 text-green-700 rounded-full">
              ${data.data.status}
            </span>
          </div>

          <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-500">Description</p>
            <p class="text-gray-700">
              ${data.data.description}
            </p>
          </div>

        </div>

      </div>
        `;

        document.getElementById('banner').src = "/storage/"+data.data.banner_url;
        document.getElementById('logo').src = "/storage/"+data.data.logo_url;
    }

    function editVendor(id){
      window.location.href = `/vendor-profile-edit?id=${id}`;
    }

    showProfile();
</script>