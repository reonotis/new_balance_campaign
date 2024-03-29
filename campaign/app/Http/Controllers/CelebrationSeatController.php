<?php

namespace App\Http\Controllers;

use App\Consts\CommonApplyConst;
use App\Http\Requests\CelebrationSeatRequest;
use App\Service\CommonApplyService;
use App\Service\ImageUploaderService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\{DB, Log, Redirect};
use Mail;
use Route;

class CelebrationSeatController extends Controller
{
    private int $applyType;
    protected string $secretariat = '';
    private string $email;
    private CommonApplyService $commonApplyService;
    private imageUploaderService $imageUploaderService;

    function __construct()
    {
        $this->applyType = CommonApplyConst::APPLY_TYPE_CELEBRATION_SEAT;
        $this->commonApplyService = new CommonApplyService($this->applyType);
        $this->imageUploaderService = new ImageUploaderService();
        // 申込期間外であればエラー画面に遷移
        if (Route::currentRouteName() <> 'celebration-seat.outsidePeriod') {
            if (!$this->commonApplyService->checkApplicationDuration()) {
                Redirect::route('celebration-seat.outsidePeriod')->send();
            }
        }
        $this->secretariat = config('mail.secretariat');
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('celebration_seat.index');
    }

    /**
     * @return View
     */
    public function complete(): View
    {
        return view('celebration_seat.complete');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CelebrationSeatRequest $request
     * @return Application|Redirector|RedirectResponse
     */
    public function store(CelebrationSeatRequest $request)
    {
        try {
            DB::beginTransaction();

            // 画像処理
            $fileName = $this->imgUpload($request);

            // 応募内容を登録
            $this->insertApplication($request, $fileName);

            // report メール
            $this->sendReportMail($request);

            // thank you メール
            $this->sendThankYouMail($request);

            DB::commit();
            return redirect('/celebration-seat/complete');
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $errorMessage = 'エラーが発生しました。管理者にお問い合わせください';
            return view('celebration_seat.error', compact('errorMessage'));
        }
    }

    /**
     * 画像のバリデーションを確認してアップロードし、ファイル名を返却する
     * @param CelebrationSeatRequest $request
     * @return string
     * @throws Exception
     */
    private function imgUpload(CelebrationSeatRequest $request): string
    {
        $dirName = CommonApplyConst::IMG_DIR[$this->applyType];
        return $this->imageUploaderService->imgCheckAndUpload($request->image, $dirName);
    }

    /**
     * 申し込み内容をDBに登録
     * @param CelebrationSeatRequest $request
     * @param string $fileName
     * @return void
     */
    private function insertApplication(CelebrationSeatRequest $request, string $fileName): void
    {
        $originalColumns = [
            'img_pass' => $fileName,
        ];
        $this->commonApplyService->insertCommonApply($request, $originalColumns);
    }

    /**
     * 申し込み者に自動返信メールを送信
     * @param CelebrationSeatRequest $request
     * @return void
     */
    private function sendThankYouMail(CelebrationSeatRequest $request): void
    {
        $this->email = $request->email;
        Log::info('sendThankYouMail');
        $data = [
            'customerName' => $request->f_name . $request->l_name
        ];
        Mail::send('emails.celebration_seat.thankYouMail', $data, function ($message) {
            $message->to($this->email)
                ->from('info@newbalance-campaign.jp')
                ->bcc('fujisawareon@yahoo.co.jp')
                ->subject('ご応募ありがとうございました。');
        });
    }

    /**
     * レポートメールを送信
     * @param CelebrationSeatRequest $request
     * @return void
     */
    private function sendReportMail(CelebrationSeatRequest $request): void
    {
        Log::info('sendReportMail');
        $data = [
            'name' => $request->f_name . ' ' . $request->l_name,
            'read' => $request->f_read . ' ' . $request->l_read,
            'zip' => $request->zip21 . '-' . $request->zip22,
            'streetAddress' => $request->pref21 . ' ' . $request->address21 . ' ' . $request->street21,
            'tel' => $request->tel,
            'email' => $request->email,
            'choice_1' => CommonApplyConst::CHOICE_1[$this->applyType][$request->choice_1],
            'url' => url('') . '/admin',
        ];
        Mail::send('emails.celebration_seat.reportMail', $data, function ($message) {
            $message->to("nb_nwm2024@fluss.co.jp")
                ->from("info@newbalance-campaign.jp")
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject("Run your way. Celebration Sheet.に申込がありました");
        });
    }

    /**
     * 申込期間外画面を表示
     * @return View
     */
    public function outsidePeriod(): View
    {
        $checkMessage = $this->commonApplyService->getDurationMessage();
        return view('celebration_seat.notApplicationPeriod', compact('checkMessage'));
    }
}
