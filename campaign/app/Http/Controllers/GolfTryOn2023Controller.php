<?php

namespace App\Http\Controllers;

use App\Models\GolfTryOn2023;
use App\Service\ImageUploaderService;
use App\Consts\Common;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\GTO2023Request;
use Illuminate\Support\Facades\{DB, Log, Redirect};
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Mail;

class GolfTryOn2023Controller extends Controller
{
    // 応募期間
    protected string $_startDateTime = "2023-07-10 00:00:00";
    protected string $_endDateTime = "2023-10-25 23:59:59";

    protected string $_f_name = "";
    protected string $_l_name = "";
    protected string $_f_read = "";
    protected string $_l_read = "";
    protected int $_age = 0;
    protected int $_sex = 0;
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
        if (\Route::currentRouteName() <> 'golf-try-on-2023.outsidePeriod') {
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
        return view('golf_try_on_2023.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function complete(): View
    {
        return view('golf_try_on_2023.complete');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GTO2023Request $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(GTO2023Request $request)
    {
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
            Redirect::route('golf-try-on-2023.complete')->send();

        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    /**
     * クラス変数に格納する
     * @param GTO2023Request $request
     */
    public function storeVariable(GTO2023Request $request)
    {
        Log::info('storeVariable');
        $this->_f_name = $request->f_name;
        $this->_l_name = $request->l_name;
        $this->_f_read = $request->f_read;
        $this->_l_read = $request->l_read;
        $this->_sex = $request->sex;
        $this->_age = $request->age;
        $this->_zip21 = $request->zip21;
        $this->_zip22 = $request->zip22;
        $this->_pref21 = $request->pref21;
        $this->_address21 = $request->address21;
        if (!empty($request->street21)) $this->_street21 = $request->street21;
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

        // TODO ファイル名の作成 => TODO_ {日時} . {拡張子}
        $this->_baseFileName = sprintf(
            '%s_%s.%s',
            'GTO2023',
            time(),
            $extension
        );

        // 指定されたディレクトリが存在するか確認
        $dirName = 'GTO2023';
        $IMGUploader->makeDirectory($dirName);
        $IMGUploader->makeDirectory($dirName . '/resize/');
        // 画像を保存する
        $IMGUploader->imgStore($file,'public/' . $dirName, $this->_baseFileName);

    }

    /**
     * 申し込み内容をDBに登録
     */
    private function insertApplication(): void
    {
        Log::info('insertApplication');
        $golfTryOn2023 = new GolfTryOn2023;
        $golfTryOn2023->f_name = $this->_f_name;
        $golfTryOn2023->l_name = $this->_l_name;
        $golfTryOn2023->f_read = $this->_f_read;
        $golfTryOn2023->l_read = $this->_l_read;
        $golfTryOn2023->age = $this->_age;
        $golfTryOn2023->sex = $this->_sex;
        $golfTryOn2023->zip21 = $this->_zip21;
        $golfTryOn2023->zip22 = $this->_zip22;
        $golfTryOn2023->pref21 = $this->_pref21;
        $golfTryOn2023->address21 = $this->_address21;
        $golfTryOn2023->street21 = $this->_street21;
        $golfTryOn2023->tel = $this->_tel;
        $golfTryOn2023->email = $this->_email;
        $golfTryOn2023->img_pass = $this->_baseFileName;
        $golfTryOn2023->reason_applying = $this->_reason_applying;
        $golfTryOn2023->save();
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
        Mail::send('emails.golf_try_on_2023.thankYouMail', $data, function ($message) {
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
            "age" => $this->_age,
            "sex" => Common::SEX_LIST[$this->_sex],
            "streetAddress" => $this->_pref21 . " " . $this->_address21 . " " . $this->_street21,
            "tel" => $this->_tel,
            "email" => $this->_email,
            "url" => url('') . '/admin'
        ];
        Mail::send('emails.golf_try_on_2023.reportMail', $data, function ($message) {
            $message->to("nbrun@fluss.co.jp")
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('「 ゴルフTRY ON キャンペーン」に申し込みがありました');
        });
    }

    /**
     */
    public function checkApplicationPeriod()
    {
        $now = date('Y-m-d H:i:s');

        if ($now <= $this->_startDateTime || $now >= $this->_endDateTime) {
            Redirect::route('golf-try-on-2023.outsidePeriod')->send();
        }
    }

    /**
     */
    public function outsidePeriod()
    {
        $now = date('Y-m-d H:i:s');
        if ($now <= $this->_startDateTime) {
            $checkMessage = 'まだ開始されていません<br>' . date('n月d日', strtotime($this->_startDateTime)) . 'から申込が開始されます';
            return view('golf_try_on_2023.notApplicationPeriod', compact('checkMessage'));
        }
        if ($now >= $this->_endDateTime) {
            $checkMessage = '募集期間は終了しました';
            return view('golf_try_on_2023.notApplicationPeriod', compact('checkMessage'));
        }

        Redirect::route('golf-try-on-2023.index')->send();
    }

}
