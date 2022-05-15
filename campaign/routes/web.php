<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TryOnController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminTryOnController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');



Route::resource('try_on', TryOnController::class)->only([
    'index', 'store'
]);

// Route::group(function(){
    Route::get('/try_on/complete', [TryOnController::class, 'complete'])->name('try_on.complete');
// });


// 管理者
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::get('/try_on', [AdminTryOnController::class, 'index'])->name('admin.try_on');
});



require __DIR__.'/auth.php';
