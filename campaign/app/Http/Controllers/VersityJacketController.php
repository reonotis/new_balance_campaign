<?php

namespace App\Http\Controllers;

use App\Consts\CommonApplyConst;
use App\Http\Requests\VersityJacketRequest;
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

class VersityJacketController extends Controller
{
    const APPLICATION_LIMIT = 40;

    private int $apply_type;
    private CommonApplyService $apply_service;
    private $number;

    /**
     * コンストラクタ
     */
    function __construct()
    {
        $this->number = 1;
        $this->apply_type = CommonApplyConst::APPLY_TYPE_VERSITY_JACKET;
        $this->apply_service = new CommonApplyService($this->apply_type, $this->number);

        if ($this->checkErrorViewRedirect()) {
            Redirect::route('versity-jacket.outsidePeriod')->send();
        }
    }

    /**
     * 申込フォーム画面を表示する
     * @return View
     */
    public function index(): View
    {
        // 登録済みの刺繍希望日時
        $record = $this->apply_service->getaaa();
        $exist_choice_1 = $record->pluck('choice_1')->toArray();

        return view('versity_jacket.index', [
            'exist_choice_1' => $exist_choice_1,
        ]);
    }

    /**
     * 申込完了画面を表示する
     * @return View
     */
    public function complete(): View
    {

        return view('versity_jacket.complete');
    }

    /**
     * 申込内容登録処理
     * @param VersityJacketRequest $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(VersityJacketRequest $request)
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
            Redirect::route('versity-jacket.complete')->send();

        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    /**
     * 申し込み内容をDBに登録
     * @param VersityJacketRequest $request
     */
    private function insertApplication(VersityJacketRequest $request): void
    {
        Log::info('insertApplication');

        $this->apply_service->insertCommonApply($request);
    }

    /**
     * 申し込み者に自動返信メールを送信
     */
    private function sendThankYouMail(VersityJacketRequest $request)
    {
        Log::info('sendThankYouMail');
        $this->email = $request->email;
        $data = [
            'customer_name' => $request->f_name . ' ' . $request->l_name,
            'choice_1' => $request->choice_1,
        ];
        Mail::send('emails.versity_jacket.thankYouMail', $data, function ($message) {
            $message->to($this->email)
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('ご予約ありがとうございました。');
        });
    }

    /**
     * レポートメールを送信
     */
    private function sendReportMail(VersityJacketRequest $request)
    {
        Log::info('sendReportMail');

        $data = [
            'name' => $request->f_name . ' ' . $request->l_name,
            'read' => $request->f_read . ' ' . $request->l_read,
            'choice_1' => $request->choice_1,
            'tel' => $request->tel,
            'email' => $request->email,
            'url' => url('') . '/admin',
        ];
        Mail::send('emails.versity_jacket.reportMail', $data, function ($message) {
            $message->to("mynb_members@fluss.co.jp")
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('[Brian Blakely Customs]ライブカスタマイズ体験に申し込みがありました');
        });
    }

    /**
     * エラー画面を表示する
     */
    public function outsidePeriod()
    {
        $checkMessage = '';
        // if (!$this->checkNumberApplications()) {
        //     $checkMessage = '応募件数が最大に達したため、申し込みを終了しました。';
        // }

        if (!$this->apply_service->checkApplicationDuration()) {
            $checkMessage = $this->apply_service->getDurationMessage();
        }

        return view('versity_jacket.notApplicationPeriod', compact('checkMessage'));
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
        if (\Route::currentRouteName() ==  'versity-jacket.outsidePeriod') {
            return false;
        }

        // 申込期間外の場合はリダイレクトする
        if (!$this->apply_service->checkApplicationDuration()) {
            return true;
        }

        // 最大申込数に達している場合はエラー画面に遷移
        // if (!$this->checkNumberApplications()) {
        //     return true;
        // }
    }

}
