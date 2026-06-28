<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-xl bg-white rounded-2xl shadow p-6">

        <h2 class="text-2xl font-bold mb-6 text-gray-800">Add Category</h2>

        <div id="errorBox" class="text-red-500 mb-3"></div>

        <form id="categoryForm">
        </form>

    </div>

</body>

</html>

<script>

    async function showParentCategory(){
        const response = await fetch('api/admin/categories', {
            method : 'GET',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });

        const data = await response.json();
        console.log(data);

        document.getElementById('categoryForm').innerHTML = `
        <!-- Category Name -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Category Name</label>
                <input type="text" placeholder="Enter category name" name="name" id="name"
                    class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" />
            </div>

            <!-- Parent Category -->
            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Parent Category</label>
                
                <select id="parent_id"
                    class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">-- Select Parent Category --</option>
                    ${
                    data.data.map(category => `
                        <option value="${category.id}">${category.name}</option>
                    `).join('')
                    }
                    
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3">
                <button type="reset" class="px-5 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                    Reset
                </button>

                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Save Category
                </button>
            </div>
        `;
    }

    showParentCategory();

    document.getElementById('categoryForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append('name', document.getElementById('name').value);
        formData.append('parent_id', document.getElementById('parent_id').value);

        const response = await fetch('api/admin/categories', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            window.location.href = '/admin-category';
        } else {
            document.getElementById('errorBox').innerHTML = data.message;
        }
    })
</script>
