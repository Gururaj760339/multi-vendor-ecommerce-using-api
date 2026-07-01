<!-- NAVBAR -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center p-4">

            <h1 class="text-2xl font-bold text-blue-600">
                ShopMart
            </h1>

            <div class="hidden md:flex space-x-6 text-gray-600 font-medium">
                <a href="#" class="hover:text-blue-600">Home</a>
                <a href="#" class="hover:text-blue-600">Shop</a>
                <a href="/orders" class="hover:text-blue-600">Orders</a>
                <a href="/carts" class="hover:text-blue-600">Cart</a>
                <a href="/profile" class="hover:text-blue-600">Profile</a>
            </div>

            <div class="flex items-center gap-4">
                <a href="/edit-wishlist" class="relative group text-xl">
                    ❤️

                    <input id="wishlistCount" value="0" readonly
                        class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1 rounded-full w-6">



                    <div id="wishlistText"
                        class="absolute hidden group-hover:block top-8 right-0 
                        bg-white shadow-lg rounded-lg p-3 w-40 text-sm text-gray-600">
                        
                    </div>
                </a>

                <a href="/carts" class="relative">
                    🛒
                    <span id="shopingCart" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1 rounded-full">3</span>
                </a>

                <div id="authArea"></div>

            </div>

        </div>

    </nav>