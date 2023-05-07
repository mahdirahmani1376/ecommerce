<?php

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
})->name('welcome');

Route::get('/test',function (){
    return 'test';
})->middleware('auth:basic');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('web')->group(function () {
    Route::get('/language/{locale}', function ($locale) {
        App::setLocale($locale);
        session()->put([
            'locale' => $locale,
        ]);

        return redirect()->route('welcome')->with([
            'message' => 'language changed to '.$locale.' successfully',
        ]);
    });
});

require __DIR__.'/auth.php';
