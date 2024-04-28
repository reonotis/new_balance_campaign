<?php

namespace App\Http\Controllers;

use App\Consts\Common;
use App\Consts\CommonApplyConst;
use App\Consts\KichijojiGrayDays5KRun;
use App\Http\Requests\KichijojiGreyDays5KRunRequest;
use App\Service\CommonApplyService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\{DB, Log, Redirect};
use Mail;
use Route;

class KichijojiGreyDays5kRunningController extends Controller
{
    private int $applyType;
    protected string $secretariat = '';
    private string $email;
    private CommonApplyService $commonApplyService;

    function __construct()
    {
        $this->applyType = CommonApplyConst::APPLY_TYPE_KICHIJOJI_GREY_DAYS_5K_RUNNING;
        $this->commonApplyService = new CommonApplyService($this->applyType);
        // 申込期間外であればエラー画面に遷移
        if (Route::currentRouteName() <> 'kichijoji-grey-days-5k-runn.outsidePeriod') {
            if (!$this->commonApplyService->checkApplicationDuration()) {
                Redirect::route('kichijoji-grey-days-5k-runn.outsidePeriod')->send();
            }
        }
        $this->secretariat = config('mail.secretariat');
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('kichijoji_grey_days_5k_runn.index');
    }

    /**
     * @return View
     */
    public function complete(): View
    {
        return view('kichijoji_grey_days_5k_runn.complete');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param KichijojiGreyDays5KRunRequest $request
     * @return Application|Redirector|RedirectResponse
     */
    public function store(KichijojiGreyDays5KRunRequest $request)
    {
        // バリデーションcheck
        $request->validated();
        try {
            DB::beginTransaction();

            // 応募内容を登録
            $this->insertApplication($request);

            // report メール
            $this->sendReportMail($request);

            // thank you メール
            $this->sendThankYouMail($request);

            DB::commit();
            return redirect('/kichijoji-grey-days-5k-runn/complete');
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $errorMessage = 'エラーが発生しました。管理者にお問い合わせください';
            return view('kichijoji_grey_days_5k_runn.error', compact('errorMessage'));
        }
    }

    /**
     * 申し込み内容をDBに登録
     * @param KichijojiGreyDays5KRunRequest $request
     * @param string $fileName
     * @return void
     */
    private function insertApplication(KichijojiGreyDays5KRunRequest $request): void
    {
        $originalColumn = [
            'choice_1' => $request->running_frequency,
            'choice_2' => $request->desired_size,
            'choice_3' => $request->shoes_size,
        ];
        $this->commonApplyService->insertCommonApply($request, $originalColumn);
    }

    /**
     * 申し込み者に自動返信メールを送信
     * @param KichijojiGreyDays5KRunRequest $request
     * @return void
     */
    private function sendThankYouMail(KichijojiGreyDays5KRunRequest $request): void
    {
        $this->email = $request->email;
        Log::info('sendThankYouMail');
        $data = [
            'customerName' => $request->f_name . $request->l_name
        ];
        Mail::send('emails.kichijoji_grey_days_5k_runn.thankYouMail', $data, function ($message) {
            $message->to($this->email)
                ->from('info@newbalance-campaign.jp')
                ->bcc('fujisawareon@yahoo.co.jp')
                ->subject('ご応募ありがとうございました。');
        });
    }

    /**
     * レポートメールを送信
     * @param KichijojiGreyDays5KRunRequest $request
     * @return void
     */
    private function sendReportMail(KichijojiGreyDays5KRunRequest $request): void
    {
        Log::info('sendReportMail');
        $data = [
            'name' => $request->f_name . ' ' . $request->l_name,
            'read' => $request->f_read . ' ' . $request->l_read,
            'zip' => $request->zip21 . '-' . $request->zip22,
            'streetAddress' => $request->pref21 . ' ' . $request->address21 . ' ' . $request->street21,
            'tel' => $request->tel,
            'sex' => Common::SEX_LIST[$request->sex],
            'email' => $request->email,
            'running_frequency' => KichijojiGrayDays5KRun::RUNNING_FREQUENCY[$request->running_frequency],
            'desired_size' => KichijojiGrayDays5KRun::DESIRED_SIZE[$request->desired_size],
            'shoes_size' => KichijojiGrayDays5KRun::SHOES_SIZE[$request->shoes_size],
            'url' => url('') . '/admin',
        ];
        Mail::send('emails.kichijoji_grey_days_5k_runn.reportMail', $data, function ($message) {
            $message->to("mynb_members@fluss.co.jp")
                ->from("info@newbalance-campaign.jp")
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject("Grey Days 2024 5K Running Eventに申込がありました");
        });
    }

    /**
     * 申込期間外画面を表示
     * @return View
     */
    public function outsidePeriod(): View
    {
        $checkMessage = $this->commonApplyService->getDurationMessage();
        return view('kichijoji_grey_days_5k_runn.notApplicationPeriod', compact('checkMessage'));
    }
}
