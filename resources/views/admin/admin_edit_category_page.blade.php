<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-5">


    <div class="w-full max-w-xl bg-white rounded-xl shadow-lg p-6">


        <h2 class="text-2xl font-bold mb-6">
            Edit Category
        </h2>

        <div id="errorBox" class="text-red-500 mb-3"></div>

        <form id="categoryForm" enctype="multipart/form-data">
        </form>

    </div>


</body>

</html>

<script>
    async function showCategory() {
        const slug = window.location.pathname.split('/').pop();
        const response = await fetch(`/api/admin/categories/${slug}`, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        document.getElementById('categoryForm').innerHTML = `
         <!-- Category Name -->
            <div class="mb-5">

                <label class="block text-gray-700 mb-2">
                    Category Name
                </label>

                <input type="text" id="category_name" value="${data.categorie[0].name}" placeholder="Enter category name"
                    class="w-full border border-gray-300 p-3 rounded-lg
                focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>



            <!-- Parent Category -->
            <div class="mb-6">


                <label class="block text-gray-700 mb-2">
                    Parent Category
                </label>

                <select id="parend_id"
                    class="w-full border border-gray-300 p-3 rounded-lg
                    focus:outline-none focus:ring-2 focus:ring-blue-400">

                        <option value="">
                            Select Parent Category
                        </option>

                        ${data.categories.map(item => `
                            <option value="${item.id}"
                                ${item.id == data.categorie[0].parent_id ? 'selected' : ''}>               
                                ${item.name}                
                            </option>
                        `)}
                        
                </select>


            </div>




            <!-- Buttons -->
            <div class="flex justify-end gap-3">


                <button type="button" class="px-5 py-2 bg-gray-300 rounded-lg
                hover:bg-gray-400">
                    Cancel
                </button>


                <button type="submit"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg
                hover:bg-blue-700">
                    Update Category
                </button>

            </div>
        `;
    }

    showCategory();

    document.getElementById('categoryForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        let formData = new FormData();
        formData.append('name', document.getElementById('category_name').value);
        formData.append('parent_id', document.getElementById('parend_id').value);
        formData.append('_method', 'PUT');

        const slug = window.location.pathname.split('/').pop();
        const response = await fetch(`/api/admin/categories/${slug}`, {
            method : 'POST',
            headers : {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            window.location.href = '/admin-category';
        } else {
            document.getElementById('errorBox').innerHTML = data.message
        }
    })
</script>
