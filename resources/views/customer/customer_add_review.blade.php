<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Review</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body class="bg-gray-100">

    <div class="max-w-3xl mx-auto py-10 px-5">

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">

            <div class="bg-blue-600 text-white px-6 py-4">
                <h2 class="text-2xl font-bold">
                    Write a Review
                </h2>
            </div>

            <form id="reviewForm" class="p-6 space-y-6">

                <!-- Product -->

                <div class="flex items-center gap-4">

                    <img src="https://via.placeholder.com/90" class="w-20 h-20 rounded-lg object-cover">

                    <div>

                        <h3 class="text-xl font-semibold">
                            Samsung Galaxy S22
                        </h3>

                        <p class="text-gray-500">
                            Product ID : #101
                        </p>

                    </div>

                </div>

                <!-- Rating -->

                <div>
                    <label class="block mb-2 font-semibold">
                        Rating
                    </label>

                    <input type="hidden" name="rating" id="rating" value="0">

                    <div id="stars" class="flex gap-2 text-3xl text-yellow-500">

                        <i class="fa-regular fa-star cursor-pointer" data-value="1"></i>
                        <i class="fa-regular fa-star cursor-pointer" data-value="2"></i>
                        <i class="fa-regular fa-star cursor-pointer" data-value="3"></i>
                        <i class="fa-regular fa-star cursor-pointer" data-value="4"></i>
                        <i class="fa-regular fa-star cursor-pointer" data-value="5"></i>

                    </div>

                    <p class="text-sm text-gray-500 mt-2">
                        Selected Rating:
                        <span id="ratingText">0</span>
                    </p>
                </div>


                <!-- Review -->

                <div>

                    <label class="block mb-2 font-semibold">
                        Review
                    </label>

                    <textarea id="review-details" rows="6" placeholder="Write your experience..."
                        class="w-full border rounded-lg px-4 py-3 resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>

                </div>

                <!-- Buttons -->

                <div class="flex justify-end gap-3">

                    <button type="reset" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

                        Cancel

                    </button>

                    <button id="submit-review" type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

                        <i class="fa-solid fa-paper-plane mr-2"></i>

                        Submit Review

                    </button>

                </div>

            </form>

        </div>

    </div>

</body>

</html>

<script>
    const stars = document.querySelectorAll('#stars i');
    const ratingInput = document.getElementById('rating');
    const ratingText = document.getElementById('ratingText');

    stars.forEach((star) => {

        star.addEventListener('click', function() {

            const rating = this.dataset.value;

            ratingInput.value = rating;
            ratingText.innerText = rating;

            stars.forEach((s) => {
                s.classList.remove('fa-solid');
                s.classList.add('fa-regular');
            });

            for (let i = 0; i < rating; i++) {
                stars[i].classList.remove('fa-regular');
                stars[i].classList.add('fa-solid');
            }

        });

    });
    
    document.getElementById('reviewForm').addEventListener('submit',async function(e){
        e.preventDefault();

        const formData = new FormData();
        formData.append('rating', document.getElementById('rating').value);
        formData.append('comment', document.getElementById('review-details').value);
        const slug = window.location.pathname.split('/').pop();

        const response = await fetch(`/api/review/post/${slug}`, {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            },
            body: formData
        });

        window.location.href = `/customer-single-product/${slug}`;
    });

</script>
