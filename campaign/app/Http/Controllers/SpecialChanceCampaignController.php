<?php

namespace App\Http\Controllers;

use App\Consts\Common;
use App\Consts\CommonApplyConst;
use App\Http\Requests\SpecialChanceRequest;
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

class SpecialChanceCampaignController extends Controller
{
    private int $applyType;
    protected string $secretariat = '';
    private string $email;
    private CommonApplyService $commonApplyService;
    private imageUploaderService $imageUploaderService;

    function __construct()
    {
        $this->applyType = CommonApplyConst::APPLY_TYPE_SPECIAL_CHANCE_CAMPAIGN;
        $this->commonApplyService = new CommonApplyService($this->applyType);
        $this->imageUploaderService = new ImageUploaderService();
        // 申込期間外であればエラー画面に遷移
        if (Route::currentRouteName() <> 'special-chance-campaign.outsidePeriod') {
            if (!$this->commonApplyService->checkApplicationDuration()) {
                Redirect::route('special-chance-campaign.outsidePeriod')->send();
            }
        }
        $this->secretariat = config('mail.secretariat');
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('special_chance_campaign.index');
    }

    /**
     * @return View
     */
    public function complete(): View
    {
        return view('special_chance_campaign.complete');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SpecialChanceRequest $request
     * @return Application|Redirector|RedirectResponse
     */
    public function store(SpecialChanceRequest $request)
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
//            $this->sendReportMail($request);

            // thank youメール
            $this->sendThankYouMail($request);

            DB::commit();
            return redirect('/special-chance-campaign/complete');
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $errorMessage = 'エラーが発生しました。管理者にお問い合わせください';
            return view('special_chance_campaign.error', compact('errorMessage'));
        }
    }

    /**
     * 画像のバリデーションを確認してアップロードし、ファイル名を返却する
     *
     * @param SpecialChanceRequest $request
     * @return string
     * @throws Exception
     */
    private function imgUpload(SpecialChanceRequest $request): string
    {
        $dirName = CommonApplyConst::IMG_DIR[$this->applyType];
        return $this->imageUploaderService->imgCheckAndUpload($request->image, $dirName);
    }

    /**
     * 申し込み内容をDBに登録
     * @param SpecialChanceRequest $request
     * @param string $fileName
     * @return void
     */
    private function insertApplication(SpecialChanceRequest $request, string $fileName): void
    {
        Log::info('insertApplication');
        $birthday = Carbon::createFromDate($request->birth_year, $request->birth_month, $request->birth_day);
        $originalColumn = [
            'img_pass' => $fileName,
            'birthday' => $birthday,
        ];
        $this->commonApplyService->insertCommonApply($request, $originalColumn);
    }

    /**
     * 申し込み者に自動返信メールを送信
     * @param SpecialChanceRequest $request
     * @return void
     */
    private function sendThankYouMail(SpecialChanceRequest $request): void
    {
        $this->email = $request->email;
        Log::info('sendThankYouMail');
        $data = [
            'customerName' => $request->f_name . $request->l_name
        ];
        Mail::send('emails.special_chance_campaign.thankYouMail', $data, function ($message) {
            $message->to($this->email)
                ->from('info@newbalance-campaign.jp')
                ->bcc('fujisawareon@yahoo.co.jp')
                ->subject('お申込みありがとうございます。');
        });
    }

    /**
     * レポートメールを送信
     * @param SpecialChanceRequest $request
     * @return void
     */
    private function sendReportMail(SpecialChanceRequest $request): void
    {
        Log::info('sendReportMail');
        $data = [
            'name' => $request->f_name . ' ' . $request->l_name,
            'read' => $request->f_read . ' ' . $request->l_read,
            'sex' => Common::SEX_LIST[$request->sex],
            'birth_day' => $request->birthday,
            'zip' => $request->zip21 . '-' . $request->zip22,
            'streetAddress' => $request->pref21 . ' ' . $request->address21 . ' ' . $request->street21,
            'email' => $request->email,
            'url' => url('') . '/admin',
        ];
        Mail::send('emails.special_chance_campaign.reportMail', $data, function ($message) {
            $message->to("nbrun@fluss.co.jp")
                ->from("info@newbalance-campaign.jp")
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject("申込がありました");
        });
    }

    /**
     * 申込期間外画面を表示
     * @return View
     */
    public function outsidePeriod(): View
    {
        $checkMessage = $this->commonApplyService->getDurationMessage();
        return view('special_chance_campaign.notApplicationPeriod', compact('checkMessage'));
    }
}
