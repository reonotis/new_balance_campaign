<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
        Area302RunningClubController,
        CelebrationSeatController,
        GoMurakamiController,
        GolfTryOn2023Controller,
        GoFunController,
        KokuritsuArukuTokyoController,
        KichijojiShoppingNightController,
        KichijojiGreyDaysExclusiveController,
        KichijojiGreyDays5kRunningController,
        MinatoRunnersBaseController,
        NagasakiOpeningController,
        RunClubTokyoController,
        OshmansController,
        S223Controller,
        StepController,
        ShinsaibashiShoppingNightController,
        SpecialChanceCampaignController,
        TenjinRunnersGateController,
        TokyoRokutaiFesController,
        TryOnController,
        TryOn2023AutumnController,
        TryOn2023Controller,
        TryOn2023FreshFormX1080v13Controller,
        TryOn2024Controller,
        JuniorFootball442Controller
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

// 吉祥寺 grey-days-exclusive
Route::group(['prefix'=>'kichijoji-grey-days-exclusive'],function(){
    Route::get('', [KichijojiGreyDaysExclusiveController::class, 'index'])->name('kichijoji-grey-days-exclusive.index');
    Route::post('/store', [KichijojiGreyDaysExclusiveController::class, 'store'])->name('kichijoji-grey-days-exclusive.store');
    Route::get('/complete', [KichijojiGreyDaysExclusiveController::class, 'complete'])->name('kichijoji-grey-days-exclusive.complete');
    Route::get('/outsidePeriod', [KichijojiGreyDaysExclusiveController::class, 'outsidePeriod'])->name('kichijoji-grey-days-exclusive.outsidePeriod');
});

// 吉祥寺 5k-running
Route::group(['prefix'=>'kichijoji-grey-days-5k-runn'],function(){
    Route::get('', [KichijojiGreyDays5kRunningController::class, 'index'])->name('kichijoji-grey-days-5k-running.index');
    Route::post('/store', [KichijojiGreyDays5kRunningController::class, 'store'])->name('kichijoji-grey-days-5k-running.store');
    Route::get('/complete', [KichijojiGreyDays5kRunningController::class, 'complete'])->name('kichijoji-grey-days-5k-running.complete');
    Route::get('/outsidePeriod', [KichijojiGreyDays5kRunningController::class, 'outsidePeriod'])->name('kichijoji-grey-days-5k-running.outsidePeriod');
});

// Junior Football 442 キャンペーン
Route::group(['prefix'=>'442-junior-football'],function(){
    Route::get('', [JuniorFootball442Controller::class, 'index'])->name('442-junior-football.index');
    Route::post('/store', [JuniorFootball442Controller::class, 'store'])->name('442-junior-football.store');
    Route::get('/complete', [JuniorFootball442Controller::class, 'complete'])->name('442-junior-football.complete');
    Route::get('/outsidePeriod', [JuniorFootball442Controller::class, 'outsidePeriod'])->name('442-junior-football.outsidePeriod');
});

// Area 302 running club キャンペーン
Route::group(['prefix' => 'area-302-running-club'], function () {
    Route::get('', [Area302RunningClubController::class, 'index'])->name('area-302-running-club.index');
    Route::post('/store', [Area302RunningClubController::class, 'store'])->name('area-302-running-club.store');
    Route::get('/complete', [Area302RunningClubController::class, 'complete'])->name('area-302-running-club.complete');
    Route::get('/outsidePeriod', [Area302RunningClubController::class, 'outsidePeriod'])->name('area-302-running-club.outsidePeriod');
});

// TENJIN　RUNNERS GATE
Route::group(['prefix' => 'tenjin-runners-gate'], function () {
    Route::get('', [TenjinRunnersGateController::class, 'index'])->name('tenjin-runners-gate.index');
    Route::post('/store', [TenjinRunnersGateController::class, 'store'])->name('tenjin-runners-gate.store');
    Route::get('/complete', [TenjinRunnersGateController::class, 'complete'])->name('tenjin-runners-gate.complete');
    Route::get('/outsidePeriod', [TenjinRunnersGateController::class, 'outsidePeriod'])->name('tenjin-runners-gate.outsidePeriod');
});

// 東京レガシーハーフマラソン
Route::group(['prefix' => 'run-club-tokyo'], function () {
    Route::get('', [RunClubTokyoController::class, 'index'])->name('run-club-tokyo.index');
    Route::post('/store', [RunClubTokyoController::class, 'store'])->name('run-club-tokyo.store');
    Route::get('/complete', [RunClubTokyoController::class, 'complete'])->name('run-club-tokyo.complete');
    Route::get('/outsidePeriod', [RunClubTokyoController::class, 'outsidePeriod'])->name('run-club-tokyo.outsidePeriod');
});

// オッシュマンズでのイベント 10/5（土）開催分
Route::group(['prefix' => 'oshmans'], function () {
    Route::get('', [OshmansController::class, 'index'])->name('oshmans.index');
    Route::post('/store', [OshmansController::class, 'store'])->name('oshmans.store');
    Route::get('/complete', [OshmansController::class, 'complete'])->name('oshmans.complete');
    Route::get('/outsidePeriod', [OshmansController::class, 'outsidePeriod'])->name('oshmans.outsidePeriod');
});

// TOKYO ROKUTAI FES 2024
Route::group(['prefix' => 'tokyo-rokutai-fes-2024'], function () {
    Route::get('', [TokyoRokutaiFesController::class, 'index'])->name('tokyo-rokutai-fes-2024.index');
    Route::post('/store', [TokyoRokutaiFesController::class, 'store'])->name('tokyo-rokutai-fes-2024.store');
    Route::get('/complete', [TokyoRokutaiFesController::class, 'complete'])->name('tokyo-rokutai-fes-2024.complete');
    Route::get('/outsidePeriod', [TokyoRokutaiFesController::class, 'outsidePeriod'])->name('tokyo-rokutai-fes-2024.outsidePeriod');
});

// Step様でのプレゼントキャンペーン
Route::group(['prefix' => 'step'], function () {
    Route::get('', [StepController::class, 'index'])->name('step.index');
    Route::post('/store', [StepController::class, 'store'])->name('step.store');
    Route::get('/complete', [StepController::class, 'complete'])->name('step.complete');
    Route::get('/outsidePeriod', [StepController::class, 'outsidePeriod'])->name('step.outsidePeriod');
});

// 心斎橋プレオープンショッピングナイト
Route::group(['prefix' => 'shinsaibashi-shopping-night'], function () {
    Route::get('', [ShinsaibashiShoppingNightController::class, 'index'])->name('shinsaibashi-shopping-night.index');
    Route::post('/store', [ShinsaibashiShoppingNightController::class, 'store'])->name('shinsaibashi-shopping-night.store');
    Route::get('/complete', [ShinsaibashiShoppingNightController::class, 'complete'])->name('shinsaibashi-shopping-night.complete');
    Route::get('/outsidePeriod', [ShinsaibashiShoppingNightController::class, 'outsidePeriod'])->name('shinsaibashi-shopping-night.outsidePeriod');
});

// 長崎スタジアムシティオープニングキャンペーン
Route::group(['prefix' => 'nagasaki-opening'], function () {
    Route::get('', [NagasakiOpeningController::class, 'index'])->name('nagasaki-opening.index');
    Route::post('/store', [NagasakiOpeningController::class, 'store'])->name('nagasaki-opening.store');
    Route::get('/complete', [NagasakiOpeningController::class, 'complete'])->name('nagasaki-opening.complete');
    Route::get('/outsidePeriod', [NagasakiOpeningController::class, 'outsidePeriod'])->name('nagasaki-opening.outsidePeriod');
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
    Route::get('/lottery_result_email/{applyType}', [AdminCommonApplyController::class, 'lotteryResultEmail'])->name('admin.lottery_result_email');
});

require __DIR__.'/auth.php';
