<?php

namespace App\Http\Controllers;

use App\Models\TryOn2023;
use App\Service\ImageUploaderService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\TO2023Request;
use Illuminate\Support\Facades\{DB, Log, Redirect};
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Mail;

class TryOn2023Controller extends Controller
{
    protected string $_startDateTime = "2023-05-19 00:01:00";
    protected string $_endDateTime = "2023-07-04 23:59:59";

    protected string $_f_name = "";
    protected string $_l_name = "";
    protected string $_f_read = "";
    protected string $_l_read = "";
    protected int $_age = 0;
    protected string $_zip21 = "";
    protected string $_zip22 = "";
    protected string $_pref21 = "";
    protected string $_address21 = "";
    protected string $_street21 = "";
    protected string $_tel = "";
    protected string $_email = "";
    protected string $_reason_applying = "";
    private string $_baseFileName = "";

    protected $_secretariat = "";

    function __construct()
    {
        $this->_secretariat = config('mail.secretariat');
        if (\Route::currentRouteName() <> 'try-on-2023.outsidePeriod') {
            $this->checkApplicationPeriod();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('try_on2023.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function complete(): View
    {
        return view('try_on2023.complete');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TO2023Request $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(TO2023Request $request)
    {
        // バリデーションcheck
        $request->validated();
        try {
            // クラス変数に格納する
            $this->storeVariable($request);

            // 画像処理
            $this->imgCheckAndUpload($request->image);

            // 応募内容を登録
            DB::beginTransaction();
            $this->insertApplication();

            // thank youメール
            $this->sendThankYouMail();

            // reportメール
            $this->sendReportMail();

            DB::commit();
            return redirect('/try-on-2023/complete');

        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    /**
     * クラス変数に格納する
     * @param TO2023Request $request
     */
    public function storeVariable(TO2023Request $request)
    {
        Log::info('storeVariable');
        $this->_f_name = $request->f_name;
        $this->_l_name = $request->l_name;
        $this->_f_read = $request->f_read;
        $this->_l_read = $request->l_read;
        $this->_age = $request->age;
        $this->_zip21 = $request->zip21;
        $this->_zip22 = $request->zip22;
        $this->_pref21 = $request->pref21;
        $this->_address21 = $request->address21;
        $this->_street21 = $request->street21;
        $this->_tel = $request->tel;
        $this->_email = $request->email;
        $this->_reason_applying = $request->reason_applying;
    }

    /**
     * 画像のバリデーションを確認してアップロードする
     *
     * @param  $file
     * @return void
     * @throws Exception
     */
    private function imgCheckAndUpload($file): void
    {
        Log::info('imgCheckAndUpload');

        $IMGUploader = New ImageUploaderService();
        // 登録可能な拡張子か確認して取得する
        $extension = $IMGUploader->checkFileExtension($file);

        // ファイル名の作成 => TO_ {日時} . {拡張子}
        $this->_baseFileName = sprintf(
            '%s_%s.%s',
            'TO2023',
            time(),
            $extension
        );

        // 指定されたディレクトリが存在するか確認
        $dirName = 'TO2023';
        $IMGUploader->makeDirectory($dirName);
        $IMGUploader->makeDirectory($dirName . '/resize/');
        // 画像を保存する
        $IMGUploader->imgStore($file,'public/' . $dirName, $this->_baseFileName);

    }

    /**
     * 申し込み内容をDBに登録
     *
     */
    private function insertApplication(): void
    {
        Log::info('insertApplication');
        $tryOn2023 = new TryOn2023;
        $tryOn2023->f_name = $this->_f_name;
        $tryOn2023->l_name = $this->_l_name;
        $tryOn2023->f_read = $this->_f_read;
        $tryOn2023->l_read = $this->_l_read;
        $tryOn2023->age = $this->_age;
        $tryOn2023->zip21 = $this->_zip21;
        $tryOn2023->zip22 = $this->_zip22;
        $tryOn2023->pref21 = $this->_pref21;
        $tryOn2023->address21 = $this->_address21;
        $tryOn2023->street21 = $this->_street21;
        $tryOn2023->tel = $this->_tel;
        $tryOn2023->email = $this->_email;
        $tryOn2023->img_pass = $this->_baseFileName;
        $tryOn2023->reason_applying = $this->_reason_applying;
        $tryOn2023->save();
    }

    /**
     * 申し込み者に自動返信メールを送信
     */
    private function sendThankYouMail()
    {
        Log::info('sendThankYouMail');
        $data = [
            "customerName" => $this->_f_name . $this->_l_name
        ];
        Mail::send('emails.try_on2023.thankYouMail', $data, function ($message) {
            $message->to($this->_email)
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('お申込みありがとうございます。');
        });
    }

    /**
     * レポートメールを送信
     */
    private function sendReportMail()
    {
        Log::info('sendReportMail');
        $data = [
            "name" => $this->_f_name . " " . $this->_l_name,
            "read" => $this->_f_read . " " . $this->_l_read,
            "zip" => $this->_zip21 . "-" . $this->_zip22,
            "streetAddress" => $this->_pref21 . " " . $this->_address21 . " " . $this->_street21,
            "tel" => $this->_tel,
            "email" => $this->_email,
            "reason" => $this->_reason_applying,
            "img_pass" => asset('storage/try_on2023_img_resize/' . $this->_baseFileName),
            "url" => url('') . '/admin'
        ];
        Mail::send('emails.try_on2023.reportMail', $data, function ($message) {
            $message->to("nbrun@fluss.co.jp")
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('「Running TRY ON キャンペーン」に申し込みがありました');
        });
    }

    /**
     */
    public function checkApplicationPeriod()
    {
        $now = date('Y-m-d H:i:s');

        if ($now <= $this->_startDateTime || $now >= $this->_endDateTime) {
            Redirect::route('try-on-2023.outsidePeriod')->send();
        }
    }

    /**
     * @return View
     */
    public function outsidePeriod(): View
    {
        $now = date('Y-m-d H:i:s');
        $checkMessage = '';
        if ($now <= $this->_startDateTime) {
            $checkMessage = 'まだ開始されていません';
        }
        if ($now >= $this->_endDateTime) {
            $checkMessage = '募集期間は終了しました';
        }
        return view('try_on2023.notApplicationPeriod', compact('checkMessage'));
    }

}
