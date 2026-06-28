<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Category CRUD</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-5xl mx-auto">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Category Management</h1>

            <!-- Add Button -->
            <a href="\admin-add-category" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                + Add Category
            </a>
        </div>

        <!-- Category Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden" id="categoryTable">
            <table class="w-full text-left">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="p-3">ID</th>
                        <th class="p-3">Category Name</th>
                        <th class="p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700" id="tableData">
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>
<script>
    async function showCategory() {
        const response = await fetch('api/admin/categories', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        console.log(data);
        let html = '';
        data.data.forEach(category => {
            html += `
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">${category.id}</td>
                        <td class="p-3">${category.name}</td>
                        <td class="p-3 text-center space-x-2">

                            <!-- Edit -->
                            <button onclick="categoryEditPage('${category.slug}')" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                Edit
                            </button>

                            <!-- Delete -->
                            <button onclick="deleteCategory('${category.slug}')" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                Delete
                            </button>

                        </td>
                    </tr>
          `;
        });
        document.getElementById('tableData').innerHTML = html;
    }

    showCategory();

    function categoryEditPage(slug){
      window.location.href = `/admin-category-edit/${slug}`;
    }

    async function deleteCategory(slug){
        const response = await fetch(`api/admin/categories/${slug}`, {
            method : 'DELETE',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token')
            }
        });

        const data = await response.json();

        if(data.success){
            showCategory();
        }
    }
</script>
