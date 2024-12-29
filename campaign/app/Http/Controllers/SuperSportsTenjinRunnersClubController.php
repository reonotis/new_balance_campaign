<?php

namespace App\Http\Controllers;

use App\Consts\CommonApplyConst;
use App\Http\Requests\TenjinRunnersClubRequest;
use App\Models\CommonApply;
use App\Service\CommonApplyService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\{DB, Log, Redirect};
use Mail;

class SuperSportsTenjinRunnersClubController extends Controller
{
    const APPLICATION_LIMIT = 30;

    private int $apply_type;
    private CommonApplyService $apply_service;
    private $number;

    /**
     * コンストラクタ
     */
    function __construct()
    {
        $this->number = 2;
        $this->apply_type = CommonApplyConst::APPLY_TYPE_SUPER_SPORTS_TENJIN_RUNNERS_CLUB;
        $this->apply_service = new CommonApplyService($this->apply_type, $this->number);

        if ($this->checkErrorViewRedirect()) {
            Redirect::route('super-sports-tenjin-runners-club.outsidePeriod')->send();
        }
    }

    /**
     * 申込フォーム画面を表示する
     * @return View
     */
    public function index(): View
    {
        return view('super_sports_tenjin_runners_club.index');
    }

    /**
     * 申込完了画面を表示する
     * @return View
     */
    public function complete(): View
    {
        return view('super_sports_tenjin_runners_club.complete');
    }

    /**
     * 申込内容登録処理
     * @param TenjinRunnersClubRequest $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(TenjinRunnersClubRequest $request)
    {
        try {
            DB::beginTransaction();

            // 応募内容を登録
            $this->insertApplication($request);

            // reportメール
            $this->sendReportMail($request);

            // thank youメール
            $this->sendThankYouMail($request);

            DB::commit();
            Redirect::route('super-sports-tenjin-runners-club.complete')->send();

        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    /**
     * 申し込み内容をDBに登録
     * @param TenjinRunnersClubRequest $request
     */
    private function insertApplication(TenjinRunnersClubRequest $request): void
    {
        Log::info('insertApplication');

        $original_column = [
            'choice_1' => $request->how_found,
        ];
        $this->apply_service->insertCommonApply($request, $original_column);
    }

    /**
     * 申し込み者に自動返信メールを送信
     */
    private function sendThankYouMail(TenjinRunnersClubRequest $request)
    {
        Log::info('sendThankYouMail');
        $this->email = $request->email;
        $data = [
            'customer_name' => $request->f_name . $request->l_name,
        ];
        Mail::send('emails.super_sports_tenjin_runners_club.thankYouMail', $data, function ($message) {
            $message->to($this->email)
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('1/26（日）イベントへのお申込みが完了しました。');
        });
    }

    /**
     * レポートメールを送信
     */
    private function sendReportMail(TenjinRunnersClubRequest $request)
    {
        Log::info('sendReportMail');

        $data = [
            'name' => $request->f_name . ' ' . $request->l_name,
            'read' => $request->f_read . ' ' . $request->l_read,
            'age' => $request->age,
            'zip' => $request->zip21 . '-' . $request->zip22,
            'streetAddress' => $request->pref21 . ' ' . $request->address21 . ' ' . $request->street21,
            'tel' => $request->tel,
            'email' => $request->email,
            'howFound' => $request->how_found,
            'url' => url('') . '/admin',
        ];
        Mail::send('emails.super_sports_tenjin_runners_club.reportMail', $data, function ($message) {
            $message->to("nbrun@fluss.co.jp")
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('1/26（日）のイベントに申し込みがありました');
        });
    }

    /**
     * エラー画面を表示する
     */
    public function outsidePeriod()
    {
        $checkMessage = '';
        if (!$this->checkNumberApplications()) {
            $checkMessage = '応募件数が最大に達したため、申し込みを終了しました。';
        }

        if (!$this->apply_service->checkApplicationDuration()) {
            $checkMessage = $this->apply_service->getDurationMessage();
        }

        return view('super_sports_tenjin_runners_club.notApplicationPeriod', compact('checkMessage'));
    }

    /**
     * 対象期間の応募数が最大数に達していない事をチェックする
     * @return bool
     */
    private function checkNumberApplications()
    {
        $count = CommonApply::where('apply_type', $this->apply_type)
            ->where('delete_flag', 0)
            ->where('created_at', '>=', date(CommonApplyConst::APPLY_TYPE_DURATION[$this->apply_type][$this->number]['start_date_time']))
            ->count();
        if ($count >= self::APPLICATION_LIMIT) {
            return false;
        }
        return true;
    }

    /**
     * エラー画面にリダイレクトするか判断する
     * @return
     */
    private function checkErrorViewRedirect()
    {
        // 既にエラー画面に行こうとしている場合は再リダイレクトさせない
        if (\Route::currentRouteName() ==  'super-sports-tenjin-runners-club.outsidePeriod') {
            return false;
        }

        // 申込期間外の場合はリダイレクトする
        if (!$this->apply_service->checkApplicationDuration()) {
            return true;
        }

        // 最大申込数に達している場合はエラー画面に遷移
        if (!$this->checkNumberApplications()) {
            return true;
        }
    }

}
