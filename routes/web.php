<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('customer/customer_home_page');
});

Route::get('/register', function(){
    return view('auth/register');
});

Route::get('loginpage', function () {
    return view('auth/loginpage');
});

Route::get('/change-password', function(){
    return view('auth/change_password');
});

Route::get('/profile', function(){
    return view('customer/customer_profile');
});

Route::get('/profile-edit', function(){
    return view('customer/customer_profile_edit');
});

Route::get('all-address', function(){
    return view('customer/customer_all_address');
});

Route::get('add-customer-address', function(){
    return view('customer/add_customer_address');
});

Route::get('edit-customer-address', function(){
    return view('customer/customer_address_edit');
});

Route::get('/customer-single-product/{slug}', function(){
    return view('customer/customer_single_product');
});

Route::get('/edit-wishlist', function(){
    return view('customer/customer_wishlist');
});

Route::get('vendor-dashboard', function () {
    return view('vendor/vendor_dashboard');
});

Route::get('/vendor-apply', function(){
    return view('vendor/apply_vendor');
}); 

Route::get('/vendor-profile', function(){
    return view('vendor/vendor_profile');
}); 

Route::get('/vendor-profile-edit', function(){
    return view('vendor/vendor_profile_edit');
}); 

Route::get('/vendor-products', function(){
    return view('vendor/vendor_all_product');
}); 

Route::get('/vendor-add-product', function(){
    return view('vendor/vendor_add_product');
}); 

Route::get('/vendor-edit-product', function(){
    return view('vendor/vendor_update_product');
}); 

Route::get('/vendor-products-images', function(){
    return view('vendor/vendor_product_image');
}); 

Route::get('/vendor-add-product-image', function(){
    return view('vendor/vendor_add_product_image');
}); 

Route::get('/vendor-coupons', function(){
    return view('vendor/vendor_coupon');
}); 

Route::get('/vendor-add-coupons', function(){
    return view('vendor/vendor_add_coupon');
}); 

Route::get('/vendor-edit-coupons', function(){
    return view('vendor/vendor_coupon_edit');
}); 

Route::get('/admin-dashboard', function () {
    return view('admin/admin_dashboard');
});

Route::get('/admin-category', function () {
    return view('admin/admin_category');
});

Route::get('/admin-add-category', function () {
    return view('admin/admin_add_category');
});

Route::get('/admin-category-edit/{slug}', function(){
    return view('admin/admin_edit_category_page');
});
