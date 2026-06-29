<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CuponController;
use App\Http\Controllers\orderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/user/profile', [UserController::class, 'profile'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::get('/user/show/edit-profile/{id}', [UserController::class, 'editProfile'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::post('/user/edit-profile/{id}', [UserController::class, 'updateProfile'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::post('/user/password', [UserController::class, 'changePassword']);
Route::post('/user/add/addresses', [UserController::class, 'storeAddress'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::get('/user/addresses', [UserController::class, 'getAddresses'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::get('/user/edit/addresses/{addressId}', [UserController::class, 'showEditAddresses'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::post('/user/update/addresses/{addressId}', [UserController::class, 'updateAddress'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::delete('/user/addresses/{id}', [UserController::class, 'destroyAddress'])->middleware(['auth:sanctum', 'can:isCustomer']);

Route::post('/vendor/apply', [VendorController::class, 'applyVendor'])->middleware(['auth:sanctum', 'can:isCustomer']);;
Route::get('/vendor/profile', [VendorController::class, 'vendorProfile'])->middleware(['auth:sanctum', 'can:isVendor']);
Route::post('/vendor/profile/update/{id}', [VendorController::class, 'vendorProfileUpdate'])->middleware(['auth:sanctum', 'can:isVendor']);
Route::get('/vendor/earning', [VendorController::class, 'vendorEarningHistroy'])->middleware(['auth:sanctum', 'can:isVendor']);
Route::post('/vendor/withdraw/request', [VendorController::class, 'vendorWithdraw'])->middleware(['auth:sanctum', 'can:isVendor']);
Route::get('/vendor/dashboard', [VendorController::class, 'vendorDashboard'])->middleware(['auth:sanctum', 'can:isVendor']);
Route::get('/vendor/withdraw/histroy', [VendorController::class, 'withdrawHistroy'])->middleware(['auth:sanctum', 'can:isVendor']);
Route::get('/public/shop/{slug}', [VendorController::class, 'publicShop'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::get('/vendor/products/{slug}', [VendorController::class, 'getVendorProducts'])->middleware(['auth:sanctum', 'can:isCustomer']);

Route::apiResource('/admin/categories', CategoryController::class)
->except(['index'])
->middleware(['auth:sanctum', 'can:isAdmin']);

Route::get('/admin/categories', [CategoryController::class, 'index'])
->middleware(['auth:sanctum', 'can:isAdminOrVendor']);

Route::get('/categories', [CategoryController::class, 'childCategory'])->middleware(['auth:sanctum', 'can:isVendor']);

Route::post('/product/store', [ProductController::class, 'productStore'])->middleware(['auth:sanctum', 'can:isVendor']);
Route::get('/product/vendor/edit/{id}', [ProductController::class, 'vendorProductEdit'])->middleware(['auth:sanctum', 'can:isVendor']);
Route::post('/product/update/{id}', [ProductController::class, 'productUpdate'])->middleware(['auth:sanctum', 'can:isVendor']);
Route::delete('/product/delete/{id}', [ProductController::class, 'productDelete'])->middleware(['auth:sanctum', 'can:isVendor']);
Route::get('/products/images', [ProductController::class, 'productImage'])->middleware(['auth:sanctum', 'can:isVendor']);
Route::post('/product/images', [ProductController::class, 'productImageStore'])->middleware(['auth:sanctum', 'can:isVendor']);
Route::delete('/product/images/delete/{id}', [ProductController::class, 'productImageDelete'])->middleware(['auth:sanctum', 'can:isVendor']);
Route::get('/product/vendor', [ProductController::class, 'vendorProduct'])->middleware(['auth:sanctum', 'can:isVendor']);
Route::get('/product/customer', [ProductController::class, 'CustomerProduct'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::get('/product/{slug}', [ProductController::class, 'CustomerSingleProduct'])->middleware(['auth:sanctum', 'can:isCustomer']);

Route::post('/wishlist/store', [WishlistController::class, 'wishlistStore'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::get('/wishlist', [WishlistController::class, 'wishlistShow'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::delete('/wishlist/delete/{id}', [WishlistController::class, 'wishlistDelete'])->middleware(['auth:sanctum', 'can:isCustomer']);

Route::get('/cupons', [CuponController::class, 'cupons'])->middleware(['auth:sanctum', 'can:isVendor']);
Route::get('/cupon/edit/{id}', [CuponController::class, 'singleCupons'])->middleware(['auth:sanctum', 'can:isVendor']);
Route::post('/cupon/create', [CuponController::class, 'cuponStore'])->middleware(['auth:sanctum', 'can:isVendor']);
Route::post('/cupon/update/{id}', [CuponController::class, 'cuponUpdate'])->middleware(['auth:sanctum', 'can:isVendor']);
Route::delete('/cupon/delete/{id}', [CuponController::class, 'cuponDelete'])->middleware(['auth:sanctum', 'can:isVendor']);
Route::post('/cupon/valid/{code}', [CuponController::class, 'validateCupon'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::get('/cupon/list', [CuponController::class, 'cuponList'])->middleware(['auth:sanctum', 'can:isAdmin']);

Route::post('/cart/add', [CartController::class, 'cartStore'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::get('/carts', [CartController::class, 'carts'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::post('/cart/update', [CartController::class, 'cartUpdate'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::delete('/cart/delete', [CartController::class, 'cartDelete'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::delete('/cart/full/delete', [CartController::class, 'cartFullDelete'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::post('/cart/coupon/applycart', [CartController::class, 'applyCoupon'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::get('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->middleware(['auth:sanctum', 'can:isCustomer']);


Route::post('/order/create', [orderController::class, 'orderStore'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::get('/orders', [orderController::class, 'allOrders'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::get('/order/single/{id}', [orderController::class, 'singleOrder'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::post('/orders/cancel/{id}', [orderController::class, 'orderCancel'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::get('/ordersitems/{id}', [orderController::class, 'vendorOrderItems'])->middleware(['auth:sanctum', 'can:isVendor']);
Route::post('/ordersitems/status/update/{id}', [orderController::class, 'updateStatus'])->middleware(['auth:sanctum', 'can:isVendor']);
Route::get('/admin/orders/list', [orderController::class, 'adminAllOrder'])->middleware(['auth:sanctum', 'can:isAdmin']);
Route::post('/admin/order/status/update/{id}', [orderController::class, 'adminUpdateStatus'])->middleware(['auth:sanctum', 'can:isAdmin']);

Route::post('/payment/create/{id}', [PaymentController::class, 'placeCodOrder'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::post('/payment/status/update/{id}', [PaymentController::class, 'paymentStatusUpdate'])->middleware(['auth:sanctum', 'can:isAdmin']);
Route::get('/payment/histroy', [PaymentController::class, 'paymentHistroy'])->middleware(['auth:sanctum', 'can:isCustomer']);

Route::post('/review/post/{id}', [ReviewController::class, 'reviewStore'])->middleware(['auth:sanctum', 'can:isCustomer']);
Route::get('/product/{id}/reviews', [ReviewController::class, 'singleProductReview']);
Route::post('/review/status/update/{id}', [ReviewController::class, 'reviewStatusUpdate'])->middleware(['auth:sanctum', 'can:isAdmin']);
Route::delete('/review/delete/{id}', [ReviewController::class, 'reviewDelete']);//->middleware(['auth:sanctum', 'can:isAdmin']);
Route::get('/reviews/admin', [ReviewController::class, 'adminPanelReview'])->middleware(['auth:sanctum', 'can:isAdmin']);

Route::get('/admin/dashboard', [adminController::class, 'adminDashboard'])->middleware(['auth:sanctum', 'can:isAdmin']);
Route::get('/admin/vendors/list', [adminController::class, 'vendorShow'])->middleware(['auth:sanctum', 'can:isAdmin']);
Route::post('/admin/vendor/approve/{id}', [adminController::class, 'approveVendor'])->middleware(['auth:sanctum', 'can:isAdmin']);
Route::post('/admin/vendor/suspande/{id}', [adminController::class, 'suspendVendor'])->middleware(['auth:sanctum', 'can:isAdmin']);
Route::get('/admin/pending/products', [adminController::class, 'pendingProductList'])->middleware(['auth:sanctum', 'can:isAdmin']);
Route::post('/admin/active/products/{id}', [adminController::class, 'activeProduct'])->middleware(['auth:sanctum', 'can:isAdmin']);
Route::post('/admin/reject/products/{id}', [adminController::class, 'rejectProduct'])->middleware(['auth:sanctum', 'can:isAdmin']);
Route::get('/admin/pending/withdrawals', [adminController::class, 'pendingWithdraw'])->middleware(['auth:sanctum', 'can:isAdmin']);
Route::post('/admin/withdrawals/status/update/{id}', [adminController::class, 'withdrawApproved'])->middleware(['auth:sanctum', 'can:isAdmin']);
Route::get('/admin/allusers', [adminController::class, 'allUsers'])->middleware(['auth:sanctum', 'can:isAdmin']);
Route::get('/admin/revenue/reports', [adminController::class, 'adminRevenueReport'])->middleware(['auth:sanctum', 'can:isAdmin']);
Route::get('/admin/top/vendor', [adminController::class, 'topVendorReport'])->middleware(['auth:sanctum', 'can:isAdmin']);
Route::get('/admin/top/product', [adminController::class, 'topProductReport'])->middleware(['auth:sanctum', 'can:isAdmin']);
