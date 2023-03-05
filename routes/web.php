<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiteSettingController;
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

Route::middleware(['auth','role:Super Admin|admin'])->prefix('admin')->controller(AdminController::class)->group(function () {
    Route::get('login','login')->name('admin.login');
    Route::get('dashboard','dashboard')->name('admin.dashboard');
    Route::get('logout','logout')->name('admin.logout');
    Route::get('profile','profile')->name('admin.profile');
    Route::post('profile/store','store')->name('admin.profile.store');
    Route::get('change_password','changePassword')->name('admin.change.password');
    Route::get('update_password','updatePassword')->name('admin.update.password');
});

Route::middleware(['auth','role:vendor|Super Admin'])->prefix('vendor')->controller(VendorController::class)->group(function () {
    Route::get('vendor/dashboard','vendorDashboard')->name('vendor.dashboard');
    Route::get('/vendor/login','VendorLogin')->name('vendor.login');
    Route::get('vendor/logout','VendorDestroy')->name('vendor.logout');
    Route::get('vendor/profile','vendorProfile')->name('vendor.profile');
    Route::post('/vendor/profile/store','VendorProfileStore')->name('vendor.profile.store');
    Route::get('vendor/change_password','VendorChangePassword')->name('vendor.change.password');
    Route::get('vendor/update_password','VendorUpdatePassword')->name('vendor.update-password');
});

Route::middleware(['auth','role:user|Super Admin'])->prefix('user')->controller(UserController::class)->group(function () {
    Route::get('user/dashboard','userDashboard')->name('user.dashboard');
    Route::get('/user/login','VendorLogin')->name('user.login');
    Route::get('user/logout','UserDestroy')->name('user.logout');
    Route::get('user/profile','userProfile')->name('user.profile');
    Route::post('/user/profile/store','UserProfileStore')->name('user.profile.store');
    Route::get('user/change_password','UserChangePassword')->name('user.change.password');
    Route::get('user/update_password','UserUpdatePassword')->name('user.update-password');
});

Route::middleware(['auth'])->controller(BrandController::class)->prefix('brand')->group(function(){
    Route::get('/', 'index')->name('all.brand');
    Route::get('/{brand}', 'show')->name('add.brand');
    Route::post('/', 'store')->name('brands.store');
    Route::get('/{brand}/edit', 'edit')->name('edit.brand');
    Route::put('{brand}/update', 'update')->name('update.brand');
    Route::delete('{brand}', 'destroy')->name('update.brand');
});

Route::controller(SiteSettingController::class)->prefix('site.setting')->group(function (){
   Route::get('/','siteSetting')->name('site.setting');
   Route::post('/update','siteSettingUpdate')->name('site.setting.update');
   Route::get('/seo/setting','seoSetting')->name('seo.setting');
});

Route::controller(CategoryController::class)->prefix('categories')->group(function (){
   Route::get('/','index')->name('categories.index');
   Route::get('/{category}','show')->name('categories.show');
   Route::post('/','store')->name('categories.store');
   Route::get('/{category}/edit','edit')->name('categories.edit');
   Route::put('/{category}/update','update')->name('categories.update');
   Route::delete('/{category}','destroy')->name('categories.destroy');
});





