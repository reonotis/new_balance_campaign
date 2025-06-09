<?php

namespace App\Http\Controllers;

use App\Consts\CommonApplyConst;
use App\Models\CommonApply;
use App\Service\CommonApplyService;
use App\Consts\MinatoRunnersBaseConst;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\MinatoRunnersBaseRequest;
use Illuminate\Support\Facades\{DB, Log, Redirect};
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Mail;

class MinatoRunnersBaseController extends Controller
{
    const APPLICATION_LIMIT = 35;
    // 1回目
    // protected string $_startDateTime = "2023-05-20 00:00:00";
    // protected string $_endDateTime = "2023-06-24 23:59:59";

    // 2回目
    // protected string $_startDateTime = "2023-07-01 00:00:00";
    // protected string $_endDateTime = "2023-07-22 23:59:59";

    // 3回目
    // protected string $_startDateTime = "2023-08-04 00:00:00";
    // protected string $_endDateTime = "2023-09-02 23:59:59";

    // 4回目
    // protected string $_startDateTime = "2023-09-30 00:00:00";
    // protected string $_endDateTime = "2023-10-20 23:59:59";

    // 5回目
    // protected string $_startDateTime = "2023-11-08 00:00:00";
    // protected string $_endDateTime = "2023-12-09 23:59:59";

    // 6回目
    // protected string $_startDateTime = "2024-01-09 00:00:00";
    // protected string $_endDateTime = "2024-12-17 23:59:59";

    // 7回目
    // protected string $_startDateTime = "2024-06-22 00:00:00";
    // protected string $_endDateTime = "2024-07-18 23:59:59";

    private int $apply_type;
    private int $number;

    function __construct()
    {
        $this->apply_type = CommonApplyConst::APPLY_TYPE_MINATO_RUNNERS_BASE;
        $this->number = 11;
        $this->apply_service = new CommonApplyService($this->apply_type, $this->number);

        if ($this->checkErrorViewRedirect()) {
            Redirect::route('minato.outsidePeriod')->send();
        }
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('minato_runners_base.index');
    }

    /**
     * @return View
     */
    public function complete(): View
    {
        return view('minato_runners_base.complete');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MinatoRunnersBaseRequest $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(MinatoRunnersBaseRequest $request)
    {
        try {
            // 応募内容を登録
            DB::beginTransaction();
            $this->insertApplication($request);

            // reportメール
            $this->sendReportMail($request);

            // thank youメール
            $this->sendThankYouMail($request);

            DB::commit();
            Redirect::route('minato.complete')->send();

        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    /**
     * 申し込み内容をDBに登録
     *
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
            "customerName" => $request->f_name . $request->l_name
        ];
        Mail::send('emails.minato_runners_base.thankYouMail', $data, function ($message) {
            $message->to($this->email)
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('7/6（日）イベントへのお申込みが完了しました。');
        });
    }

    /**
     * レポートメールを送信
     */
    private function sendReportMail(MinatoRunnersBaseRequest $request)
    {
        Log::info('sendReportMail');

        $data = [
            "name" => $request->f_name . " " . $request->l_name,
            "read" => $request->f_read . " " . $request->l_read,
            "age" => $request->age,
            "zip" => $request->zip21 . "-" . $request->zip22,
            "streetAddress" => $request->pref21 . " " . $request->address21 . " " . $request->street21,
            "tel" => $request->tel,
            "email" => $request->email,
            "how_found" => $request->how_found,
            "url" => url('') . '/admin'
        ];
        Mail::send('emails.minato_runners_base.reportMail', $data, function ($message) {
            $message->to("nbrun@fluss.co.jp")
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('ゼビオ名古屋みなとアクルス店 7/6（日）イベントに申し込みがありました');
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

        return view('minato_runners_base.notApplicationPeriod', compact('checkMessage'));
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
        // 既にエラー画面に以降としている場合は再リダイレクトさせない
        if (\Route::currentRouteName() ==  'minato.outsidePeriod') {
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
