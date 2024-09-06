<?php

namespace App\Http\Controllers;

use App\Consts\CommonApplyConst;
use App\Http\Requests\StepRequest;
use App\Service\CommonApplyService;
use App\Service\ImageUploaderService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{DB, Log, Redirect};
use Mail;
use Route;

class StepController extends Controller
{
    private int $apply_type;
    private CommonApplyService $apply_service;
    private int $number;
    private ImageUploaderService $image_uploader_service;

    /**
     * コンストラクタ
     */
    function __construct()
    {
        $this->number = 1;
        $this->apply_type = CommonApplyConst::APPLY_TYPE_STEP;
        $this->apply_service = new CommonApplyService($this->apply_type, $this->number);
        $this->image_uploader_service = new ImageUploaderService();

        if ($this->checkErrorViewRedirect()) {
            Redirect::route('step.outsidePeriod')->send();
        }
    }

    /**
     * 申込フォーム画面を表示する
     * @return View
     */
    public function index(): View
    {
        return view('step.index');
    }

    /**
     * 申込完了画面を表示する
     * @return View
     */
    public function complete(): View
    {
        return view('step.complete');
    }

    /**
     * 申込内容登録処理
     * @param StepRequest $request
     * @return RedirectResponse|void
     */
    public function store(StepRequest $request)
    {
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
            Redirect::route('step.complete')->send();
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    /**
     * 画像のバリデーションを確認してアップロードし、ファイル名を返却する
     * @param StepRequest $request
     * @return string
     * @throws Exception
     */
    private function imgUpload(StepRequest $request): string
    {
        $dir_name = CommonApplyConst::IMG_DIR[$this->apply_type];
        return $this->image_uploader_service->imgCheckAndUpload($request->image, $dir_name);
    }

    /**
     * 申し込み内容をDBに登録
     * @param StepRequest $request
     * @param string $file_name
     */
    private function insertApplication(StepRequest $request, string $file_name): void
    {
        $original_columns = [
            'img_pass' => $file_name,
            'choice_1' => $request->hope_gift,
        ];
        $this->apply_service->insertCommonApply($request, $original_columns);
    }

    /**
     * 申し込み者に自動返信メールを送信
     */
    private function sendThankYouMail(StepRequest $request)
    {
        Log::info('sendThankYouMail');
        $email = $request->email;
        $data = [
            'customer_name' => $request->f_name . $request->l_name,
        ];
        Mail::send('emails.step.thankYouMail', $data, function ($message) use ($email) {
            $message->to($email)
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('New Balance Running Campaignへの応募ありがとうございました。');
        });
    }

    /**
     * レポートメールを送信
     */
    private function sendReportMail(StepRequest $request)
    {
        Log::info('sendReportMail');

        $data = [
            'name' => $request->f_name . ' ' . $request->l_name,
            'read' => $request->f_read . ' ' . $request->l_read,
            'age' => $request->age,
            'zip' => $request->zip21 . '-' . $request->zip22,
            'streetAddress' => $request->pref21 . ' ' . $request->address21 . ' ' . $request->street21,
            'tel' => $request->tel,
            'email' => $request->email,
            'hope_gift' => $request->hope_gift,
            'url' => url('') . '/admin',
        ];
        Mail::send('emails.step.reportMail', $data, function ($message) {
            $message->to("nbrun@fluss.co.jp")
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('「New Balance Running Campaign」のイベントに申し込みがありました');
        });
    }

    /**
     * エラー画面を表示する
     */
    public function outsidePeriod()
    {
        $checkMessage = '';

        if (!$this->apply_service->checkApplicationDuration()) {
            $checkMessage = $this->apply_service->getDurationMessage();
        }

        return view('step.notApplicationPeriod', compact('checkMessage'));
    }

    /**
     * エラー画面にリダイレクトするか判断する
     * @return bool
     */
    private function checkErrorViewRedirect(): bool
    {
        // 既にエラー画面に以降としている場合は再リダイレクトさせない
        if (Route::currentRouteName() ==  'step.outsidePeriod') {
            return false;
        }

        // 申込期間外の場合はリダイレクトする
        if (!$this->apply_service->checkApplicationDuration()) {
            return true;
        }

        return false;
    }

}
