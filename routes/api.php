<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Auth\Api\AuthController;
use App\Http\Controllers\Auth\Api\EmailVerificationController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::controller(AuthController::class)->group(function () {
    Route::get('/user', 'show')->name('users.show');
    Route::post('/tokens/create', 'createToken')->name('tokens.create');
    Route::post('/user/login', 'login')->name('users.login');
    Route::post('/user/logout', 'logout')->name('users.logout');
    Route::post('/user/register','register')->name('users.register');
});

Route::controller(EmailVerificationController::class)->group(function () {
   Route::get('/email/verify','notice')->name('verification.notice');
   Route::get('/email/verify/{id}/{hash}','verify')->name('verification.verify')->middleware(['auth:sanctum']);
   Route::post('email/verification-notification','resendEmail')->name('verification.resendEmail')->middleware(['auth:sanctum','throttle:6,1']);
});
Route::middleware('auth:sanctum')->group(function () {
Route::controller(UserController::class)->prefix('/users')->group(function () {
    Route::get('/wishlist')->name('users.wishlist');
});

Route::controller(ProductController::class)->prefix('/products')->group(function () {
    Route::get('/', 'index')->name('products.index');
    Route::get('/{product}', 'view')->name('products.view');
    Route::get('/{product}/users-wishlist', 'usersWishList')->name('products.usersWishList');
    Route::post('/', 'store')->name('products.store');
    Route::put('/{product}', 'update')->name('products.update');
    Route::delete('/{product}', 'delete')->name('products.delete');
});

Route::apiResource('order', OrderController::class);
Route::apiResource('address', AddressController::class);
Route::apiResource('category', CategoryController::class);
Route::apiResource('delivery', DeliveryController::class);
Route::apiResource('basket', BasketController::class);
Route::apiResource('brand', BrandController::class);
Route::controller(BasketController::class)->group(function () {
    Route::post('/{variationVendor}/add_to_basket', 'addToBasket')->name('basket.add-to-basket');
    Route::post('/{variationVendor}/remove_from_basket', 'removeFromBasket')->name('basket.remove-from-basket');
});
Route::apiResource('voucher', VoucherController::class);
Route::controller(VoucherController::class)->group(function () {
    Route::post('/{voucher}/apply_voucher', 'applyVoucher')->name('apply-voucher');
});
});
