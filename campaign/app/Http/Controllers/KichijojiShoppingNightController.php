<?php

namespace App\Http\Controllers;

use App\Consts\CommonApplyConst;
use App\Http\Requests\KichijojiShoppingNightRequest;
use App\Service\CommonApplyService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\{DB, Log, Redirect};
use Mail;
use Route;

class KichijojiShoppingNightController extends Controller
{
    private int $applyType;
    protected string $secretariat = '';
    private string $email;
    private CommonApplyService $commonApplyService;

    function __construct()
    {
        $this->applyType = CommonApplyConst::APPLY_TYPE_KICHIJOJI_SHOPPING_NIGHT;
        $this->commonApplyService = new CommonApplyService($this->applyType);
        // 申込期間外であればエラー画面に遷移
        if (Route::currentRouteName() <> 'kichijoji-shopping-night.outsidePeriod') {
            if (!$this->commonApplyService->checkApplicationDuration()) {
                Redirect::route('kichijoji-shopping-night.outsidePeriod')->send();
            }
        }
        $this->secretariat = config('mail.secretariat');
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('kichijoji_shopping_night.index');
    }

    /**
     * @return View
     */
    public function complete(): View
    {
        return view('kichijoji_shopping_night.complete');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param KichijojiShoppingNightRequest $request
     * @return Application|Redirector|RedirectResponse
     */
    public function store(KichijojiShoppingNightRequest $request)
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
            return redirect('/kichijoji-shopping-night/complete');
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $errorMessage = 'エラーが発生しました。管理者にお問い合わせください';
            return view('kichijoji_shopping_night.error', compact('errorMessage'));
        }
    }

    /**
     * 申し込み内容をDBに登録
     * @param KichijojiShoppingNightRequest $request
     * @param string $fileName
     * @return void
     */
    private function insertApplication(KichijojiShoppingNightRequest $request): void
    {
        $this->commonApplyService->insertCommonApply($request);
    }

    /**
     * 申し込み者に自動返信メールを送信
     * @param KichijojiShoppingNightRequest $request
     * @return void
     */
    private function sendThankYouMail(KichijojiShoppingNightRequest $request): void
    {
        $this->email = $request->email;
        Log::info('sendThankYouMail');
        $data = [
            'customerName' => $request->f_name . $request->l_name
        ];
        Mail::send('emails.kichijoji_shopping_night.thankYouMail', $data, function ($message) {
            $message->to($this->email)
                ->from('info@newbalance-campaign.jp')
                ->bcc('fujisawareon@yahoo.co.jp')
                ->subject('ご応募ありがとうございました。');
        });
    }

    /**
     * レポートメールを送信
     * @param KichijojiShoppingNightRequest $request
     * @return void
     */
    private function sendReportMail(KichijojiShoppingNightRequest $request): void
    {
        Log::info('sendReportMail');
        $data = [
            'name' => $request->f_name . ' ' . $request->l_name,
            'read' => $request->f_read . ' ' . $request->l_read,
            'zip' => $request->zip21 . '-' . $request->zip22,
            'streetAddress' => $request->pref21 . ' ' . $request->address21 . ' ' . $request->street21,
            'tel' => $request->tel,
            'email' => $request->email,
            'url' => url('') . '/admin',
        ];
        Mail::send('emails.kichijoji_shopping_night.reportMail', $data, function ($message) {
            $message->to("nb-platium@fluss.co.jp")
                ->from("info@newbalance-campaign.jp")
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject("Kichijoji Special Shopping Nightに申込がありました");
        });
    }

    /**
     * 申込期間外画面を表示
     * @return View
     */
    public function outsidePeriod(): View
    {
        $checkMessage = $this->commonApplyService->getDurationMessage();
        return view('kichijoji_shopping_night.notApplicationPeriod', compact('checkMessage'));
    }
}
