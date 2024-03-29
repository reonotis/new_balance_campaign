<?php

namespace App\Http\Controllers;

use App\Consts\Common;
use App\Consts\CommonApplyConst;
use App\Http\Requests\TryOnRequest;
use App\Service\CommonApplyService;
use App\Service\ImageUploaderService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\{DB, Log, Redirect};
use Mail;
use Route;

class TryOn2023FreshFormX1080v13Controller extends Controller
{
    private int $applyType;
    protected string $secretariat = '';
    private string $email;
    private CommonApplyService $commonApplyService;
    private imageUploaderService $imageUploaderService;

    function __construct()
    {
        $this->applyType = CommonApplyConst::APPLY_TYPE_TRY_ON_2023_FRESH_FORM_1080_V13;
        $this->commonApplyService = new CommonApplyService($this->applyType);
        $this->imageUploaderService = new ImageUploaderService();
        // 申込期間外であればエラー画面に遷移
        if (Route::currentRouteName() <> 'try-on-2023-fresh-form-1080-v13.outsidePeriod') {
            if (!$this->commonApplyService->checkApplicationDuration()) {
                Redirect::route('try-on-2023-fresh-form-1080-v13.outsidePeriod')->send();
            }
        }
        $this->secretariat = config('mail.secretariat');
    }

    /**
     * @return View
     */
    public function index()
    {
        return view('try_on_2023_fresh_Form_1080v13.index');
    }

    /**
     * @return View
     */
    public function complete(): View
    {
        return view('try_on_2023_fresh_Form_1080v13.complete');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TryOnRequest $request
     * @return Application|Redirector|RedirectResponse
     */
    public function store(TryOnRequest $request)
    {
        // バリデーションcheck
        $request->validated();
        try {
            DB::beginTransaction();

            // 画像処理
            $fileName = $this->imgUpload($request);

            // 応募内容を登録
            $this->insertApplication($request, $fileName);

            // reportメール
            $this->sendReportMail($request);

            // thank youメール
            $this->sendThankYouMail($request);

            DB::commit();
            return redirect('/try-on-2023-fresh-form-1080-v13/complete');
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $errorMessage = 'エラーが発生しました。管理者にお問い合わせください';
            return view('try_on_2023_fresh_Form_1080v13.error', compact('errorMessage'));
        }
    }

    /**
     * 画像のバリデーションを確認してアップロードし、ファイル名を返却する
     *
     * @param TryOnRequest $request
     * @return string
     * @throws Exception
     */
    private function imgUpload(TryOnRequest $request): string
    {
        $dirName = CommonApplyConst::IMG_DIR[$this->applyType];
        return $this->imageUploaderService->imgCheckAndUpload($request->image, $dirName);
    }

    /**
     * 申し込み内容をDBに登録
     * @param TryOnRequest $request
     * @param string $fileName
     * @return void
     */
    private function insertApplication(TryOnRequest $request, string $fileName): void
    {
        Log::info('insertApplication');
        $originalColumn = [
            'img_pass' => $fileName,
        ];
        $this->commonApplyService->insertCommonApply($request, $originalColumn);
    }

    /**
     * 申し込み者に自動返信メールを送信
     * @param TryOnRequest $request
     * @return void
     */
    private function sendThankYouMail(TryOnRequest $request): void
    {
        $this->email = $request->email;
        Log::info('sendThankYouMail');
        $data = [
            'customerName' => $request->f_name . $request->l_name
        ];
        Mail::send('emails.try_on_2023_fresh_Form_1080v13.thankYouMail', $data, function ($message) {
            $message->to($this->email)
                ->from('info@newbalance-campaign.jp')
                ->bcc('fujisawareon@yahoo.co.jp')
                ->subject('お申込みありがとうございます。');
        });
    }

    /**
     * レポートメールを送信
     * @param TryOnRequest $request
     * @return void
     */
    private function sendReportMail(TryOnRequest $request): void
    {
        Log::info('sendReportMail');
        $data = [
            'name' => $request->f_name . ' ' . $request->l_name,
            'read' => $request->f_read . ' ' . $request->l_read,
            'sex' => Common::SEX_LIST[$request->sex],
            'age' => $request->age,
            'zip' => $request->zip21 . '-' . $request->zip22,
            'streetAddress' => $request->pref21 . ' ' . $request->address21 . ' ' . $request->street21,
            'tel' => $request->tel,
            'email' => $request->email,
            'url' => url('') . '/admin',
        ];
        Mail::send('emails.try_on_2023_fresh_Form_1080v13.reportMail', $data, function ($message) {
            $message->to('nbrun@fluss.co.jp')
                ->from('info@newbalance-campaign.jp')
                ->bcc('fujisawareon@yahoo.co.jp')
                ->subject('「Fresh Form X 1080v13 TRY ON キャンペーン」に申し込みがありました');
        });
    }

    /**
     * 申込期間外画面を表示
     * @return View
     */
    public function outsidePeriod(): View
    {
        $checkMessage = $this->commonApplyService->getDurationMessage();
        return view('try_on_2023_fresh_Form_1080v13.notApplicationPeriod', compact('checkMessage'));
    }
}
