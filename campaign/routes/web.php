<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
        CelebrationSeatController,
        GoMurakamiController,
        GolfTryOn2023Controller,
        GoFunController,
        KokuritsuArukuTokyoController,
        KichijojiShoppingNightController,
        MinatoRunnersBaseController,
        S223Controller,
        SpecialChanceCampaignController,
        TryOnController,
        TryOn2023AutumnController,
        TryOn2023Controller,
        TryOn2023FreshFormX1080v13Controller,
        TryOn2024Controller
    };
use App\Http\Controllers\Admin\{AdminCommonApplyController,
        AdminController,
        AdminTryOnController,
        AdminTryOn20232Controller,
        AdminGoMurakami2023Controller,
        AdminArukuTokyo2022Controller,
        AdminGolfTryOn2023Controller,
        AdminS223Controller
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

// S223アパレル展示会
Route::group(['prefix'=>'s223'],function(){
    Route::get('', [S223Controller::class, 'index'])->name('s223.index');
    Route::post('/store', [S223Controller::class, 'store'])->name('s223.store');
    Route::get('/complete', [S223Controller::class, 'complete'])->name('s223.complete');
    Route::get('/outsidePeriod', [S223Controller::class, 'outsidePeriod'])->name('s223.outsidePeriod');
});

// GO FUN! キャンペーン
Route::group(['prefix'=>'go_fun'],function(){
    Route::get('', [GoFunController::class, 'index'])->name('go_fun.index');
    Route::post('/store', [GoFunController::class, 'store'])->name('go_fun.store');
    Route::get('/complete', [GoFunController::class, 'complete'])->name('go_fun.complete');
    Route::get('/outsidePeriod', [GoFunController::class, 'outsidePeriod'])->name('go_fun.outsidePeriod');
});

// try_on 2023 autumn キャンペーン
Route::group(['prefix'=>'try-on-2023-autumn'],function(){
    Route::get('', [TryOn2023AutumnController::class, 'index'])->name('try-on-2023-autumn.index');
    Route::post('/store', [TryOn2023AutumnController::class, 'store'])->name('try-on-2023-autumn.store');
    Route::get('/complete', [TryOn2023AutumnController::class, 'complete'])->name('try-on-2023-autumn.complete');
    Route::get('/outsidePeriod', [TryOn2023AutumnController::class, 'outsidePeriod'])->name('try-on-2023-autumn.outsidePeriod');
});

// try_on 2023 fresh-form-1080-v13 キャンペーン
Route::group(['prefix'=>'try-on-2023-fresh-form-1080-v13'],function(){
    Route::get('', [TryOn2023FreshFormX1080v13Controller::class, 'index'])->name('try-on-2023-fresh-form-1080-v13.index');
    Route::post('/store', [TryOn2023FreshFormX1080v13Controller::class, 'store'])->name('try-on-2023-fresh-form-1080-v13.store');
    Route::get('/complete', [TryOn2023FreshFormX1080v13Controller::class, 'complete'])->name('try-on-2023-fresh-form-1080-v13.complete');
    Route::get('/outsidePeriod', [TryOn2023FreshFormX1080v13Controller::class, 'outsidePeriod'])->name('try-on-2023-fresh-form-1080-v13.outsidePeriod');
});

// スペシャルチャンス キャンペーン
Route::group(['prefix'=>'special-chance-campaign'],function(){
    Route::get('', [SpecialChanceCampaignController::class, 'index'])->name('special-chance-campaign.index');
    Route::post('/store', [SpecialChanceCampaignController::class, 'store'])->name('special-chance-campaign.store');
    Route::get('/complete', [SpecialChanceCampaignController::class, 'complete'])->name('special-chance-campaign.complete');
    Route::get('/outsidePeriod', [SpecialChanceCampaignController::class, 'outsidePeriod'])->name('special-chance-campaign.outsidePeriod');
});

// 吉祥寺ショッピングナイト
Route::group(['prefix'=>'kichijoji-shopping-night'],function(){
    Route::get('', [KichijojiShoppingNightController::class, 'index'])->name('kichijoji-shopping-night.index');
    Route::post('/store', [KichijojiShoppingNightController::class, 'store'])->name('kichijoji-shopping-night.store');
    Route::get('/complete', [KichijojiShoppingNightController::class, 'complete'])->name('kichijoji-shopping-night.complete');
    Route::get('/outsidePeriod', [KichijojiShoppingNightController::class, 'outsidePeriod'])->name('kichijoji-shopping-night.outsidePeriod');
});

// Run your way. Celebration Seat.
Route::group(['prefix'=>'celebration-seat'],function(){
    Route::get('', [CelebrationSeatController::class, 'index'])->name('celebration-seat.index');
    Route::post('/store', [CelebrationSeatController::class, 'store'])->name('celebration-seat.store');
    Route::get('/complete', [CelebrationSeatController::class, 'complete'])->name('celebration-seat.complete');
    Route::get('/outsidePeriod', [CelebrationSeatController::class, 'outsidePeriod'])->name('celebration-seat.outsidePeriod');
});

// try_on 2024 キャンペーン
Route::group(['prefix'=>'try-on-2024'],function(){
    Route::get('', [TryOn2024Controller::class, 'index'])->name('try-on-2024.index');
    Route::post('/store', [TryOn2024Controller::class, 'store'])->name('try-on-2024.store');
    Route::get('/complete', [TryOn2024Controller::class, 'complete'])->name('try-on-2024.complete');
    Route::get('/outsidePeriod', [TryOn2024Controller::class, 'outsidePeriod'])->name('try-on-2024.outsidePeriod');
});

// 管理者
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::get('/try-on-2023', [AdminTryOn20232Controller::class, 'index'])->name('admin.try-on-2023');
    Route::get('/go-murakami-2023', [AdminGoMurakami2023Controller::class, 'index'])->name('admin.go-murakami-2023');
    Route::get('/aruku-tokyo-2022', [AdminArukuTokyo2022Controller::class, 'index'])->name('admin.aruku-tokyo-2022');
    Route::get('/try_on', [AdminTryOnController::class, 'index'])->name('admin.try_on');
    Route::get('/golf-try-on-2023', [AdminGolfTryOn2023Controller::class, 'index'])->name('admin.golf-try-on-2023');
    Route::get('/s223', [AdminS223Controller::class, 'index'])->name('admin.s223');
    Route::get('/common_apply/{applyType}', [AdminCommonApplyController::class, 'index'])->name('admin.common_apply');
    Route::get('/common_apply/redirect_apply_form/{applyType}', [AdminCommonApplyController::class, 'redirectApplyForm'])->name('admin.redirect_apply_form');
    Route::get('/common_apply_csv_dl/{applyType}', [AdminCommonApplyController::class, 'csv_dl'])->name('admin.common_apply_csv_dl');
});

require __DIR__.'/auth.php';
