<?php

namespace App\Http\Controllers;

use App\Consts\CommonApplyConst;
use App\Http\Requests\TryOnRequest;
use App\Service\CommonApplyService;
use App\Service\ImageUploaderService;
use App\Consts\Common;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\GoFunRequest;
use Illuminate\Support\Facades\{DB, Log, Redirect};
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Redirector;
use Mail;

class GoFunController extends Controller
{
    private int $applyType;
    private CommonApplyService $commonApplyService;
    private imageUploaderService $imageUploaderService;

    protected string $email = "";
    protected string $baseFileName = "";

    protected string $secretariat = "";

    function __construct()
    {
        $this->applyType = CommonApplyConst::APPLY_TYPE_GO_FUN;
        $this->commonApplyService = new CommonApplyService($this->applyType);
        $this->imageUploaderService = new ImageUploaderService();
        // 申込期間外であればエラー画面に遷移
        if (\Route::currentRouteName() <> 'go_fun.outsidePeriod') {
            if (!$this->commonApplyService->checkApplicationDuration()) {
                Redirect::route('go_fun.outsidePeriod')->send();
            }
        }
        $this->secretariat = config('mail.secretariat');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('go_fun.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function complete(): View
    {
        return view('go_fun.complete');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GoFunRequest $request
     * @return Application|Redirector|RedirectResponse
     */
    public function store(GoFunRequest $request)
    {
        // バリデーションcheck
        $request->validated();
        try {
            DB::beginTransaction();

            // 画像処理
            $fileName = $this->imgUpload($request);

            // 応募内容を登録
            $this->insertApplication($request, $fileName);

            // thank youメール
            $this->sendThankYouMail($request);

            // reportメール
            $this->sendReportMail($request);

            DB::commit();
            return redirect('/go_fun/complete');
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            dd('エラーが発生しました。管理者にお問い合わせください');
            return redirect()->back()->withInput();
        }
    }

    /**
     * 申し込み内容をDBに登録
     */
    private function insertApplication(GoFunRequest $request, string $fileName): void
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
     */
    private function sendThankYouMail(GoFunRequest $request)
    {
        $this->email = $request->email;
        Log::info('sendThankYouMail');
        $data = [
            "customerName" => $request->f_name . $request->l_name
        ];
        Mail::send('emails.go_fun.thankYouMail', $data, function ($message) {
            $message->to($this->email)
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('お申込みありがとうございます。');
        });
    }

    /**
     * レポートメールを送信
     */
    private function sendReportMail(GoFunRequest $request)
    {
        Log::info('sendReportMail');
        $data = [
            'name' => $request->f_name . ' ' . $request->l_name,
            'read' => $request->f_read . ' ' . $request->l_read,
            'birthday' => $request->birth_year . '-' . $request->birth_month . '-' . $request->birth_day,
            'sex' => Common::SEX_LIST[$request->sex],
            'zip' => $request->zip21 . '-' . $request->zip22,
            'streetAddress' => $request->pref21 . ' ' . $request->address21 . ' ' . $request->street21,
            'tel' => $request->tel,
            'email' => $request->email,
            'url' => url('') . '/admin'
        ];
        Mail::send('emails.go_fun.reportMail', $data, function ($message) {
            $message->to('nb-platium@fluss.co.jp')
                ->from('info@newbalance-campaign.jp')
                ->bcc('fujisawareon@yahoo.co.jp')
                ->subject('「New Balance GO FUN! キャンペーン」に申し込みがありました');
        });
    }

    /**
     * 画像のバリデーションを確認してアップロードし、ファイル名を返却する
     *
     * @param TryOnRequest $request
     * @return string
     * @throws Exception
     */
    private function imgUpload(GoFunRequest $request): string
    {
        $dirName = CommonApplyConst::IMG_DIR[$this->applyType];
        return $this->imageUploaderService->imgCheckAndUpload($request->image, $dirName);
    }

    /**
     * @return View
     */
    public function outsidePeriod(): View
    {
        $checkMessage = $this->commonApplyService->getDurationMessage();
        return view('go_fun.notApplicationPeriod', compact('checkMessage'));
    }

}
