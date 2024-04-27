<?php

namespace App\Http\Controllers;

use App\Consts\Common;
use App\Consts\CommonApplyConst;
use App\Http\Requests\KichijojiGreyDaysExclusiveRequest;
use App\Service\CommonApplyService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\{DB, Log, Redirect};
use Mail;
use Route;

class KichijojiGreyDaysExclusiveController extends Controller
{
    private int $applyType;
    protected string $secretariat = '';
    private string $email;
    private CommonApplyService $commonApplyService;

    function __construct()
    {
        $this->applyType = CommonApplyConst::APPLY_TYPE_KICHIJOJI_GREY_DAYS_EXCLUSIVE;
        $this->commonApplyService = new CommonApplyService($this->applyType);
        // 申込期間外であればエラー画面に遷移
        if (Route::currentRouteName() <> 'kichijoji-grey-days-exclusive.outsidePeriod') {
            if (!$this->commonApplyService->checkApplicationDuration()) {
                Redirect::route('kichijoji-grey-days-exclusive.outsidePeriod')->send();
            }
        }
        $this->secretariat = config('mail.secretariat');
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('kichijoji_grey_days_exclusive.index');
    }

    /**
     * @return View
     */
    public function complete(): View
    {
        return view('kichijoji_grey_days_exclusive.complete');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param KichijojiGreyDaysExclusiveRequest $request
     * @return Application|Redirector|RedirectResponse
     */
    public function store(KichijojiGreyDaysExclusiveRequest $request)
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
            return redirect('/kichijoji-grey-days-exclusive/complete');
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $errorMessage = 'エラーが発生しました。管理者にお問い合わせください';
            return view('kichijoji_grey_days_exclusive.error', compact('errorMessage'));
        }
    }

    /**
     * 申し込み内容をDBに登録
     * @param KichijojiGreyDaysExclusiveRequest $request
     * @param string $fileName
     * @return void
     */
    private function insertApplication(KichijojiGreyDaysExclusiveRequest $request): void
    {
        $this->commonApplyService->insertCommonApply($request);
    }

    /**
     * 申し込み者に自動返信メールを送信
     * @param KichijojiGreyDaysExclusiveRequest $request
     * @return void
     */
    private function sendThankYouMail(KichijojiGreyDaysExclusiveRequest $request): void
    {
        $this->email = $request->email;
        Log::info('sendThankYouMail');
        $data = [
            'customerName' => $request->f_name . $request->l_name
        ];
        Mail::send('emails.kichijoji_grey_days_exclusive.thankYouMail', $data, function ($message) {
            $message->to($this->email)
                ->from('info@newbalance-campaign.jp')
                ->bcc('fujisawareon@yahoo.co.jp')
                ->subject('ご応募ありがとうございました。Grey Days 2024 Exclusive Event');
        });
    }

    /**
     * レポートメールを送信
     * @param KichijojiGreyDaysExclusiveRequest $request
     * @return void
     */
    private function sendReportMail(KichijojiGreyDaysExclusiveRequest $request): void
    {
        Log::info('sendReportMail');
        $data = [
            'name' => $request->f_name . ' ' . $request->l_name,
            'read' => $request->f_read . ' ' . $request->l_read,
            'sex' => Common::SEX_LIST[$request->sex],
            'zip' => $request->zip21 . '-' . $request->zip22,
            'streetAddress' => $request->pref21 . ' ' . $request->address21 . ' ' . $request->street21,
            'tel' => $request->tel,
            'email' => $request->email,
            'comment' => $request->comment,
            'url' => url('') . '/admin',
        ];
        Mail::send('emails.kichijoji_grey_days_exclusive.reportMail', $data, function ($message) {
            $message->to("mynb_members@fluss.co.jp")
                ->from("info@newbalance-campaign.jp")
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject("Kichijoji Grey Days 2024 Exclusive Eventに申込がありました");
        });
    }

    /**
     * 申込期間外画面を表示
     * @return View
     */
    public function outsidePeriod(): View
    {
        $checkMessage = $this->commonApplyService->getDurationMessage();
        return view('kichijoji_grey_days_exclusive.notApplicationPeriod', compact('checkMessage'));
    }
}
