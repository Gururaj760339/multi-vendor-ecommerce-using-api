<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Review</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    @include('customer.navbar')

    <div class="max-w-3xl mx-auto py-10 px-4">

        <div class="bg-white rounded-xl shadow-lg p-8">

            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                Edit Review
            </h2>

            <form id="editReviewForm">

                <!-- Rating -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">
                        Rating
                    </label>

                    <select
                        id="rating"
                        class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                        <option value="4">⭐⭐⭐⭐ (4)</option>
                        <option value="3">⭐⭐⭐ (3)</option>
                        <option value="2">⭐⭐ (2)</option>
                        <option value="1">⭐ (1)</option>
                    </select>
                </div>

                <!-- Review -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">
                        Review
                    </label>

                    <textarea
                        id="comment"
                        rows="6"
                        class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Update your review..."></textarea>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4">

                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
                        Update Review
                    </button>

                    <a href="javascript:history.back()"
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</body>
</html>