<?php

namespace App\Http\Controllers;

use App\Consts\CommonApplyConst;
use App\Http\Requests\KichijojiShoppingNightRequest;
use App\Service\CommonApplyService;
use App\Service\ImageUploaderService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{DB, Log, Redirect};
use Mail;
use Route;

class ShinsaibashiShoppingNightController extends Controller
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
        $this->apply_type = CommonApplyConst::APPLY_TYPE_SHINSAIBASHI_SHOPPING_NIGHT;
        $this->apply_service = new CommonApplyService($this->apply_type, $this->number);
        $this->image_uploader_service = new ImageUploaderService();

        if ($this->checkErrorViewRedirect()) {
            Redirect::route('shinsaibashi-shopping-night.outsidePeriod')->send();
        }
    }

    /**
     * 申込フォーム画面を表示する
     * @return View
     */
    public function index(): View
    {
        return view('shinsaibashi_shopping_night.index');
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
     * @param KichijojiShoppingNightRequest $request
     * @return RedirectResponse|void
     */
    public function store(KichijojiShoppingNightRequest $request)
    {
        try {
            DB::beginTransaction();

            // 応募内容を登録
            $this->insertApplication($request);

            // reportメール
            $this->sendReportMail($request);

            // thank youメール
            $this->sendThankYouMail($request);

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
     * @param KichijojiShoppingNightRequest $request
     * @return string
     * @throws Exception
     */
    private function imgUpload(KichijojiShoppingNightRequest $request): string
    {
        $dir_name = CommonApplyConst::IMG_DIR[$this->apply_type];
        return $this->image_uploader_service->imgCheckAndUpload($request->image, $dir_name);
    }

    /**
     * 申し込み内容をDBに登録
     * @param KichijojiShoppingNightRequest $request
     * @param string $file_name
     */
    private function insertApplication(KichijojiShoppingNightRequest $request): void
    {
        $this->apply_service->insertCommonApply($request);
    }

    /**
     * 申し込み者に自動返信メールを送信
     */
    private function sendThankYouMail(KichijojiShoppingNightRequest $request)
    {
        Log::info('sendThankYouMail');
        $email = $request->email;
        $data = [
            'customer_name' => $request->f_name . $request->l_name,
        ];
        Mail::send('emails.shinsaibashi.thankYouMail', $data, function ($message) use ($email) {
            $message->to($email)
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('ご応募ありがとうございました。');
        });
    }

    /**
     * レポートメールを送信
     */
    private function sendReportMail(KichijojiShoppingNightRequest $request)
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
            'url' => url('') . '/admin',
        ];
        Mail::send('emails.shinsaibashi.reportMail', $data, function ($message) {
            $message->to("mynb_members@fluss.co.jp")
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('「ニューバランス心斎橋店プレオープン Special Shopping Night」のイベントに応募がありました');
        });
    }

    /**
     * エラー画面を表示する
     */
    public function outsidePeriod()
    {
        $check_message = '';

        if (!$this->apply_service->checkApplicationDuration()) {
            $check_message = $this->apply_service->getDurationMessage();
        }

        return view('shinsaibashi_shopping_night.notApplicationPeriod', compact('check_message'));
    }

    /**
     * エラー画面にリダイレクトするか判断する
     * @return bool
     */
    private function checkErrorViewRedirect(): bool
    {
        // 既にエラー画面に以降としている場合は再リダイレクトさせない
        if (Route::currentRouteName() ==  'shinsaibashi-shopping-night.outsidePeriod') {
            return false;
        }

        // 申込期間外の場合はリダイレクトする
        if (!$this->apply_service->checkApplicationDuration()) {
            return true;
        }

        return false;
    }

}
