<?php

namespace App\Http\Controllers;

use App\Consts\CommonApplyConst;
use App\Http\Requests\MinatoRunnersBaseRequest;
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

class TenjinRunnersGateController extends Controller
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
        $this->number = 1;
        $this->apply_type = CommonApplyConst::APPLY_TYPE_TENJIN_RUNNERS_GATE;
        $this->apply_service = new CommonApplyService($this->apply_type, $this->number);

        if ($this->checkErrorViewRedirect()) {
            Redirect::route('tenjin-runners-gate.outsidePeriod')->send();
        }
    }

    /**
     * 申込フォーム画面を表示する
     * @return View
     */
    public function index(): View
    {
        return view('tenjin_runners_gate.index');
    }

    /**
     * 申込完了画面を表示する
     * @return View
     */
    public function complete(): View
    {
        return view('tenjin_runners_gate.complete');
    }

    /**
     * 申込内容登録処理
     * @param MinatoRunnersBaseRequest $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(MinatoRunnersBaseRequest $request)
    {
        // バリデーションcheck
        $request->validated();
        try {
            DB::beginTransaction();

            // 応募内容を登録
            $this->insertApplication($request);

            // thank youメール
            $this->sendThankYouMail($request);

            // reportメール
            $this->sendReportMail($request);

            DB::commit();
            Redirect::route('tenjin-runners-gate.complete')->send();

        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    /**
     * 申し込み内容をDBに登録
     * @param MinatoRunnersBaseRequest $request
     */
    private function insertApplication(MinatoRunnersBaseRequest $request): void
    {
        Log::info('insertApplication');

        $originalColumn = [
            'choice_1' => $request->how_found,
        ];
        $this->apply_service->insertCommonApply($request, $originalColumn);
    }

    /**
     * 申し込み者に自動返信メールを送信
     */
    private function sendThankYouMail(MinatoRunnersBaseRequest $request)
    {
        Log::info('sendThankYouMail');
        $this->email = $request->email;
        $data = [
            'customer_name' => $request->f_name . $request->l_name,
        ];
        Mail::send('emails.tenjin_runners_gate.thankYouMail', $data, function ($message) {
            $message->to($this->email)
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('8/25（日）イベントへのお申込みが完了しました。');
        });
    }

    /**
     * レポートメールを送信
     */
    private function sendReportMail(MinatoRunnersBaseRequest $request)
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
        Mail::send('emails.tenjin_runners_gate.reportMail', $data, function ($message) {
            $message->to("nbrun@fluss.co.jp")
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('「TENJIN RUNNERS GATE」のイベントに申し込みがありました');
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

        return view('tenjin_runners_gate.notApplicationPeriod', compact('checkMessage'));
    }

    /**
     * 対象期間の応募数が最大数に達していない事をチェックする
     * @return bool
     */
    private function checkNumberApplications()
    {
        $count = CommonApply::where('apply_type', CommonApplyConst::APPLY_TYPE_AREA_302_RUNNING_CLUB)
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
        // 既にエラー画面に以降としている場合は再リダイレクトさせない
        if (\Route::currentRouteName() ==  'tenjin-runners-gate.outsidePeriod') {
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
