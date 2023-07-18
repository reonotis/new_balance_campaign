<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
        GoMurakamiController,
        GolfTryOn2023Controller,
        KokuritsuArukuTokyoController,
        TryOnController,
        TryOn2023Controller,
        MinatoRunnersBaseController
    };
use App\Http\Controllers\Admin\{AdminController,
        AdminTryOnController,
        AdminTryOn20232Controller,
        AdminGoMurakami2023Controller,
        AdminArukuTokyo2022Controller
    };

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

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

Route::resource('try_on', TryOnController::class)->only([
    'index', 'store'
]);
Route::get('/try_on/complete', [TryOnController::class, 'complete'])->name('try_on.complete');

// 国立までアルク東京
Route::group(['prefix'=>'aruku-tokyo-2022'],function(){
    Route::get('', [KokuritsuArukuTokyoController::class, 'index'])->name('aruku-tokyo-2022.index');
    Route::post('/store', [KokuritsuArukuTokyoController::class, 'store'])->name('aruku-tokyo-2022.store');
    Route::get('/complete', [KokuritsuArukuTokyoController::class, 'complete'])->name('aruku-tokyo-2022.complete');
    Route::get('/outsidePeriod', [KokuritsuArukuTokyoController::class, 'outsidePeriod'])->name('aruku-tokyo-2022.outsidePeriod');
});

// 村上宗隆選手応援キャンペーン
Route::group(['prefix'=>'go-murakami-2023'],function(){
    Route::get('', [GoMurakamiController::class, 'index'])->name('go-murakami-2023.index');
    Route::post('/store', [GoMurakamiController::class, 'store'])->name('go-murakami-2023.store');
    Route::get('/complete', [GoMurakamiController::class, 'complete'])->name('go-murakami-2023.complete');
    Route::get('/outsidePeriod', [GoMurakamiController::class, 'outsidePeriod'])->name('go-murakami-2023.outsidePeriod');
});

// try_on 2023
Route::group(['prefix'=>'try-on-2023'],function(){
    Route::get('', [TryOn2023Controller::class, 'index'])->name('try-on-2023.index');
    Route::post('/store', [TryOn2023Controller::class, 'store'])->name('try-on-2023.store');
    Route::get('/complete', [TryOn2023Controller::class, 'complete'])->name('try-on-2023.complete');
    Route::get('/outsidePeriod', [TryOn2023Controller::class, 'outsidePeriod'])->name('try-on-2023.outsidePeriod');
});

// minato-runners-base
Route::group(['prefix'=>'minato-runners-base'],function(){
    Route::get('', [MinatoRunnersBaseController::class, 'index'])->name('minato.index');
    Route::post('/store', [MinatoRunnersBaseController::class, 'store'])->name('minato.store');
    Route::get('/complete', [MinatoRunnersBaseController::class, 'complete'])->name('minato.complete');
    Route::get('/outsidePeriod', [MinatoRunnersBaseController::class, 'outsidePeriod'])->name('minato.outsidePeriod');
});

// golf try-on 2023
Route::group(['prefix'=>'golf-try-on-2023'],function(){
    Route::get('', [GolfTryOn2023Controller::class, 'index'])->name('golf-try-on-2023.index');
    Route::post('/store', [GolfTryOn2023Controller::class, 'store'])->name('golf-try-on-2023.store');
    Route::get('/complete', [GolfTryOn2023Controller::class, 'complete'])->name('golf-try-on-2023.complete');
    Route::get('/outsidePeriod', [GolfTryOn2023Controller::class, 'outsidePeriod'])->name('golf-try-on-2023.outsidePeriod');
});

// 管理者
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::get('/try-on-2023', [AdminTryOn20232Controller::class, 'index'])->name('admin.try-on-2023');
    Route::get('/go-murakami-2023', [AdminGoMurakami2023Controller::class, 'index'])->name('admin.go-murakami-2023');
    Route::get('/aruku-tokyo-2022', [AdminArukuTokyo2022Controller::class, 'index'])->name('admin.aruku-tokyo-2022');
    Route::get('/try_on', [AdminTryOnController::class, 'index'])->name('admin.try_on');
});

require __DIR__.'/auth.php';
