<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->prefix('admin')->controller(AdminController::class)->group(function () {
    Route::get('login','login')->name('admin.login');
    Route::get('dashboard','dashboard')->name('admin.dashboard');
    Route::get('logout','logout')->name('admin.logout');
    Route::get('profile','profile')->name('admin.profile');
    Route::post('profile/store','store')->name('admin.profile.store');
    Route::get('change_password','changePassword')->name('admin.change.password');
    Route::get('update_password','updatePassword')->name('admin.update.password');
});

Route::middleware(['auth','role:vendor'])->controller(VendorController::class)->group(function () {
    Route::get('vendor/dashboard','vendorDashboard')->name('vendor.dashboard');
    Route::get('/vendor/login','VendorLogin')->name('vendor.login');
    Route::get('vendor/logout','VendorDestroy')->name('vendor.logout');
    Route::get('vendor/profile','vendorProfile')->name('vendor.profile');
    Route::post('/vendor/profile/store','VendorProfileStore')->name('vendor.profile.store');
    Route::get('vendor/change_password','VendorChangePassword')->name('vendor.change.password');
    Route::get('vendor/update_password','VendorUpdatePassword')->name('vendor.update-password');
});

Route::middleware(['auth','role:vendor'])->controller(UserController::class)->group(function () {
    Route::get('user/dashboard','userDashboard')->name('user.dashboard');
    Route::get('/user/login','VendorLogin')->name('user.login');
    Route::get('user/logout','UserDestroy')->name('user.logout');
    Route::get('user/profile','userProfile')->name('user.profile');
    Route::post('/user/profile/store','UserProfileStore')->name('user.profile.store');
    Route::get('user/change_password','UserChangePassword')->name('user.change.password');
    Route::get('user/update_password','UserUpdatePassword')->name('user.update-password');

Route::middleware(['auth'])->prefix('brand')->group(function(){
    Route::get('/', 'AllBrand')->name('all.brand');
    Route::get('/{brand}', 'AddBrand')->name('add.brand');
    Route::post('/', 'StoreBrand')->name('brands.store');
    Route::get('/{brand}/edit', 'AddBrand')->name('edit.brand');
    Route::put('{brand}/update', 'update')->name('update.brand');
});

});





