<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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
Route::controller(ApiAuthController::class)->group(function (){
   Route::get('/user','show')->name('user.show');
   Route::post('/tokens/create','createToken')->name('tokens.create');
   Route::post('/user/login','login')->name('user.login');
   Route::post('/user/logout','logout')->name('user.logout');
});

Route::middleware('auth:sanctum')->group(function (){
    Route::controller(OrderController::class)->prefix('/orders')->group(function (){
        Route::get('/','index')->name('orders.index')->middleware('can:orders.view_any');
        Route::get('/{order}','show')->name('orders.show')->middleware('can:orders.view');
        Route::post('/','store')->name('orders.store');
        Route::put('/{order}','update')->name('orders.update')->middleware('can:orders.update');
        Route::delete('/{order}','delete')->name('orders.delete')->middleware('can:orders.delete');
    });

    Route::controller(UserController::class)->prefix('/users')->group(function (){
       Route::get('/wishlist')->name('users.wishlist');
    });
    Route::controller(ProductController::class)->prefix('/products')->group(function (){
        Route::get('/','index')->name('products.index')->middleware('can:products.view_any');
        Route::get('/{product}','view')->name('products.view')->middleware('can:products.view');
        Route::get('/{product}/users-wishlist','usersWishList')->name('products.usersWishList')->middleware('can:products.view_any');
        Route::post('/','store')->name('products.store')->middleware('can:products.store');
        Route::put('/{product}','update')->name('products.update')->middleware('can:products.update');
        Route::delete('/{product}','delete')->name('products.delete')->middleware('can:products.delete');
    });

    Route::apiResource('address',AddressController::class);
    Route::apiResource('category',CategoryController::class);
    Route::apiResource('order',OrderController::class);
    Route::apiResource('delivery',DeliveryController::class);
});

