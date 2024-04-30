<?php

namespace App\Http\Controllers;

use App\Consts\CommonApplyConst;
use App\Http\Requests\TO2024Request;
use App\Service\CommonApplyService;
use App\Service\ImageUploaderService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{DB, Log, Redirect};
use Illuminate\Routing\Redirector;
use Mail;
use Route;

class TryOn2024Controller extends Controller
{
    private int $applyType;
    protected string $secretariat = '';
    private string $email;
    private CommonApplyService $commonApplyService;
    private imageUploaderService $imageUploaderService;

    function __construct()
    {
        $this->applyType = CommonApplyConst::APPLY_TYPE_TRY_ON_2024;
        $this->commonApplyService = new CommonApplyService($this->applyType);
        $this->imageUploaderService = new ImageUploaderService();
        // 申込期間外であればエラー画面に遷移
        if (Route::currentRouteName() <> 'try-on-2024.outsidePeriod') {
            if (!$this->commonApplyService->checkApplicationDuration()) {
                Redirect::route('try-on-2024.outsidePeriod')->send();
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('try_on2024.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function complete(): View
    {
        return view('try_on2024.complete');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TO2024Request $request
     * @return Application|Redirector|RedirectResponse
     */
    public function store(TO2024Request $request)
    {
        try {
            // 画像処理
            $fileName = $this->imgUpload($request);

            // 応募内容を登録
            $this->insertApplication($request, $fileName);

            // report メール
            $this->sendReportMail($request);

            // thank you メール
            $this->sendThankYouMail($request);

            DB::commit();
            return redirect('/try-on-2024/complete');
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            $errorMessage = 'エラーが発生しました。管理者にお問い合わせください';
            return view('celebration_seat.error', compact('errorMessage'));
        }
    }

    /**
     * 画像のバリデーションを確認してアップロードし、ファイル名を返却する
     * @param TO2024Request $request
     * @return string
     * @throws Exception
     */
    private function imgUpload(TO2024Request $request): string
    {
        $dirName = CommonApplyConst::IMG_DIR[$this->applyType];
        return $this->imageUploaderService->imgCheckAndUpload($request->image, $dirName);
    }

    /**
     * 申し込み内容をDBに登録
     *
     */
    private function insertApplication(TO2024Request $request, string $fileName): void
    {
        $originalColumns = [
            'img_pass' => $fileName,
        ];
        $this->commonApplyService->insertCommonApply($request, $originalColumns);
    }

    /**
     * 申し込み者に自動返信メールを送信
     * @param TO2024Request $request
     * @return void
     */
    private function sendThankYouMail(TO2024Request $request): void
    {
        $this->email = $request->email;
        Log::info('sendThankYouMail');
        $data = [
            'customerName' => $request->f_name . $request->l_name
        ];
        Mail::send('emails.try_on2024.thankYouMail', $data, function ($message) {
            $message->to($this->email)
                ->from('info@newbalance-campaign.jp')
                ->bcc('fujisawareon@yahoo.co.jp')
                ->subject('お申込みありがとうございました。');
        });
    }

    /**
     * レポートメールを送信
     * @param TO2024Request $request
     * @return void
     */
    private function sendReportMail(TO2024Request $request): void
    {
        $data = [
            'name' => $request->f_name . ' ' . $request->l_name,
            'read' => $request->f_read . ' ' . $request->l_read,
            'age' => $request->age,
            'zip' => $request->zip21 . '-' . $request->zip22,
            'streetAddress' => $request->pref21 . ' ' . $request->address21 . ' ' . $request->street21,
            'tel' => $request->tel,
            'email' => $request->email,
            'url' => url('') . '/admin',
        ];
        Mail::send('emails.try_on2024.reportMail', $data, function ($message) {
            $message->to("nbrun@fluss.co.jp")
                ->from("info@newbalance-campaign.jp")
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject("Running TRY ON 2024 キャンペーン に申込がありました");
        });
    }

    /**
     * @return View
     */
    public function outsidePeriod(): View
    {
        $checkMessage = $this->commonApplyService->getDurationMessage();
        return view('try_on2024.notApplicationPeriod', compact('checkMessage'));
    }
}
