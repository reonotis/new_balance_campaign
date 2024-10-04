<?php

namespace App\Http\Controllers;

use App\Consts\CommonApplyConst;
use App\Http\Requests\CelebrationSeatRequest;
use App\Http\Requests\NagasakiOpeningRequest;
use App\Models\CommonApply;
use App\Service\CommonApplyService;
use App\Service\ImageUploaderService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\{DB, Log, Redirect};
use Mail;

class NagasakiOpeningController extends Controller
{
    const APPLICATION_LIMIT = 35;

    private int $apply_type;
    private CommonApplyService $apply_service;
    private $number;
    private ImageUploaderService $image_uploader_service;

    /**
     * コンストラクタ
     */
    function __construct()
    {
        $this->number = 1;
        $this->apply_type = CommonApplyConst::APPLY_TYPE_NAGASAKI_OPENING;
        $this->apply_service = new CommonApplyService($this->apply_type, $this->number);
        $this->image_uploader_service = new ImageUploaderService();

        if ($this->checkErrorViewRedirect()) {
            Redirect::route('nagasaki-opening.outsidePeriod')->send();
        }
    }

    /**
     * 申込フォーム画面を表示する
     * @return View
     */
    public function index(): View
    {
        return view('nagasaki_opening.index');
    }

    /**
     * 申込完了画面を表示する
     * @return View
     */
    public function complete(): View
    {
        return view('nagasaki_opening.complete');
    }

    /**
     * 申込内容登録処理
     * @param NagasakiOpeningRequest $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(NagasakiOpeningRequest $request)
    {
        try {
            DB::beginTransaction();

            // 画像処理
            $file_name = $this->imgUpload($request);

            // 応募内容を登録
            $this->insertApplication($request, $file_name);

            // reportメール
            $this->sendReportMail($request);

            // thank youメール
            $this->sendThankYouMail($request);

            DB::commit();
            Redirect::route('nagasaki-opening.complete')->send();

        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    /**
     * 画像のバリデーションを確認してアップロードし、ファイル名を返却する
     * @param NagasakiOpeningRequest $request
     * @return string
     * @throws Exception
     */
    private function imgUpload(NagasakiOpeningRequest $request): string
    {
        $dir_name = CommonApplyConst::IMG_DIR[$this->apply_type];
        return $this->image_uploader_service->imgCheckAndUpload($request->image, $dir_name);
    }

    /**
     * 申し込み内容をDBに登録
     * @param NagasakiOpeningRequest $request
     */
    private function insertApplication(NagasakiOpeningRequest $request, string $file_name): void
    {
        Log::info('insertApplication');

        $original_columns = [
            'img_pass' => $file_name,
        ];
        $this->apply_service->insertCommonApply($request, $original_columns);
    }

    /**
     * 申し込み者に自動返信メールを送信
     * @param NagasakiOpeningRequest $request
     */
    private function sendThankYouMail(NagasakiOpeningRequest $request)
    {
        Log::info('sendThankYouMail');
        $this->email = $request->email;
        $data = [
            'customer_name' => $request->f_name . $request->l_name,
            'choice_1' => $request->choice_1,
        ];
        Mail::send('emails.nagasaki_opening.thankYouMail', $data, function ($message) {
            $message->to($this->email)
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('「長崎ヴェルカ応援キャンペーン」への応募ありがとうございました。');
        });
    }

    /**
     * レポートメールを送信
     * @param NagasakiOpeningRequest $request
     */
    private function sendReportMail(NagasakiOpeningRequest $request)
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
            'choice_1' => $request->choice_1,
            'url' => url('') . '/admin',
        ];
        Mail::send('emails.nagasaki_opening.reportMail', $data, function ($message) {
            $message->to('mynb_members@fluss.co.jp')
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('「長崎ヴェルカ応援キャンペーン」のイベントに申し込みがありました');
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

        return view('nagasaki_opening.notApplicationPeriod', compact('checkMessage'));
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
        if (\Route::currentRouteName() ==  'nagasaki-opening.outsidePeriod') {
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
