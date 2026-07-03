<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto py-8 px-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Review Management</h1>
                <p class="text-gray-500 mt-1">Manage customer reviews</p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left">#</th>
                            <th class="px-6 py-4 text-left">Customer</th>
                            <th class="px-6 py-4 text-left">Product</th>
                            <th class="px-6 py-4 text-left">Rating</th>
                            <th class="px-6 py-4 text-left">Review</th>
                            <th class="px-6 py-4 text-left">Date</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Update Status</th>
                            <th class="px-6 py-4 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody id="review-details" class="divide-y">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    async function showReviews(){
        const response = await fetch('api/reviews/admin', {
            method : 'GET',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });

        const data = await response.json();
        //console.log(data);

        function generateStars(rating){
            let stars = '';

            for(let i = 1; i <= rating; i++){
                if(i <= rating){
                    stars += `<span class="text-yellow-500">★</span>`;
                }
            }

            return stars;
        }

        let html = '';
        data.data.forEach(reviews => {

            let createdAt = new Date(reviews.created_at);
            let formattedDate = createdAt.getDay()+ '-' +createdAt.getMonth()+ '-' +createdAt.getFullYear();
            html += `
                <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">1</td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold">${reviews.user.name}</p>
                                    <p class="text-sm text-gray-500">${reviews.user.email}</p>
                                </div>
                            </td>

                            <td class="px-6 py-4">${reviews.product.name}</td>
                            <td class="px-6 py-4 text-yellow-500">${generateStars(reviews.rating)}</td>
                            <td class="px-6 py-4 text-gray-600">${reviews.comment}</td>
                            <td class="px-6 py-4">${formattedDate}</td>

                            <td class="px-6 py-4 text-center">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                    ${reviews.status}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <select id="review-status"
                                    class="w-36 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="pending" ${reviews.status == 'pending' ? 'selected' : ''}>Pending</option>
                                    <option value="approved" ${reviews.status == 'approved' ? 'selected' : ''}>Approved</option>
                                </select>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <button onclick="updateRewiewStatus(${reviews.id})" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                                        Update
                                    </button>

                                    <button onclick="deleteReview(${reviews.id})" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
            `;
        });

        document.getElementById('review-details').innerHTML = html;
    }

    showReviews();

    async function updateRewiewStatus(reviewId){
        const newStatus = document.getElementById('review-status').value;

        const response = await fetch(`api/review/status/update/${reviewId}`, {
            method : 'POST',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json',
                'Content-Type' : 'application/json'
            },
            body : JSON.stringify({
                status : newStatus
            })
        });

        showReviews();
    }

    async function deleteReview(deleteId){
        const response = await fetch(`api/review/delete/${deleteId}`, {
            method : 'DELETE',
            headers : {
                'Authorization' : 'Bearer '+localStorage.getItem('token'),
                'Accept' : 'application/json'
            }
        });
        showReviews();
    }
</script>
