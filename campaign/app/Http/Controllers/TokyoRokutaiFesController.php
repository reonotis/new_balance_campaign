<?php

namespace App\Http\Controllers;

use App\Consts\CommonApplyConst;
use App\Http\Requests\TokyoRokutaiFesRequest;
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

class TokyoRokutaiFesController extends Controller
{
    const APPLICATION_LIMIT = 30;

    private int $apply_type;
    private CommonApplyService $apply_service;
    private int $number;
    private int $endurance_relay_count;
    private int $full_relay_count;

    /**
     * コンストラクタ
     */
    function __construct()
    {
        $this->number = 1;
        $this->full_relay_count = 0;
        $this->endurance_relay_count = 0;
        $this->apply_type = CommonApplyConst::APPLY_TYPE_TOKYO_ROKUTAI_FES;
        $this->apply_service = new CommonApplyService($this->apply_type, $this->number);

        if ($this->checkErrorViewRedirect()) {
            Redirect::route('tokyo-rokutai-fes-2024.outsidePeriod')->send();
        }
    }

    /**
     * 申込フォーム画面を表示する
     * @return View
     */
    public function index(): View
    {
        return view('tokyo_rokutai_fes.index', [
            'application_count_list' => [
                1 => $this->full_relay_count,
                2 => $this->endurance_relay_count,
            ]
        ]);
    }

    /**
     * 申込完了画面を表示する
     * @return View
     */
    public function complete(): View
    {
        return view('tokyo_rokutai_fes.complete');
    }

    /**
     * 申込内容登録処理
     * @param TokyoRokutaiFesRequest $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(TokyoRokutaiFesRequest $request)
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
            Redirect::route('tokyo-rokutai-fes-2024.complete')->send();

        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    /**
     * 申し込み内容をDBに登録
     * @param TokyoRokutaiFesRequest $request
     */
    private function insertApplication(TokyoRokutaiFesRequest $request): void
    {
        Log::info('insertApplication');

        $originalColumn = [
            'choice_1' => $request->preferred_event,
        ];
        $this->apply_service->insertCommonApply($request, $originalColumn);
    }

    /**
     * 申し込み者に自動返信メールを送信
     */
    private function sendThankYouMail(TokyoRokutaiFesRequest $request)
    {
        Log::info('sendThankYouMail');
        $this->email = $request->email;
        $data = [
            'customer_name' => $request->f_name . $request->l_name,
        ];
        Mail::send('emails.tokyo_rokutai_fes.thankYouMail', $data, function ($message) {
            $message->to($this->email)
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('TOKYO ROKUTAI FES 2024 New Balance Run Club Tokyoチーム参加 エントリー完了');
        });
    }

    /**
     * レポートメールを送信
     */
    private function sendReportMail(TokyoRokutaiFesRequest $request)
    {
        Log::info('sendReportMail');

        $data = [
            'name' => $request->f_name . ' ' . $request->l_name,
            'read' => $request->f_read . ' ' . $request->l_read,
            'sex' => $request->sex,
            'preferred_event' => $request->preferred_event,
            'email' => $request->email,
            'url' => url('') . '/admin',
        ];
        Mail::send('emails.tokyo_rokutai_fes.reportMail', $data, function ($message) {
            $message->to("legacyhalf.tokyo@fluss.co.jp")
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('「TOKYO ROKUTAI FES 2024 New Balance Run Club Tokyo」のイベントに申し込みがありました');
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

        return view('tokyo_rokutai_fes.notApplicationPeriod', compact('checkMessage'));
    }

    /**
     * 対象期間の応募数が最大数に達していない事をチェックする
     * @return bool
     */
    private function checkNumberApplications()
    {
        $records = CommonApply::where('apply_type', $this->apply_type)
            ->where('delete_flag', 0)
            ->where('created_at', '>=', date(CommonApplyConst::APPLY_TYPE_DURATION[$this->apply_type][$this->number]['start_date_time']))
            ->get();

        $this->full_relay_count = $records->where('choice_1', 1)->count(); // 42.195kmリレー  DAY１：9/28（土）14:00-19:00
        $this->endurance_relay_count = $records->where('choice_1', 2)->count(); // 6時間耐久リレー  DAY２：9/29（日）10:30-17:30

        if (
            $this->full_relay_count >= self::APPLICATION_LIMIT
            && $this->endurance_relay_count >= self::APPLICATION_LIMIT
        ) {
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
        // 既にエラー画面に行こうとしている場合と、申込完了画面に行く場合は、再リダイレクトさせない
        if (\Route::currentRouteName() == 'tokyo-rokutai-fes-2024.outsidePeriod'
            || \Route::currentRouteName() == 'tokyo-rokutai-fes-2024.complete') {
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
