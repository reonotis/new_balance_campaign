<?php

namespace App\Http\Controllers;

use App\Service\ImageUploaderService;
use App\Consts\Common;
use App\Models\CommonApply;
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
    protected string $startDateTime = "2023-09-07 00:00:00";
    protected string $endDateTime = "2023-10-13 23:59:59";

    protected string $email = "";
    protected string $baseFileName = "";

    protected string $_secretariat = "";

    function __construct()
    {
        $this->_secretariat = config('mail.secretariat');
        if (\Route::currentRouteName() <> 'go_fun.outsidePeriod') {
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
            $this->imgCheckAndUpload($request->image);

            // 応募内容を登録
            $this->insertApplication($request);

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
     *
     */
    private function insertApplication(GoFunRequest $request): void
    {
        Log::info('insertApplication');
        $goFun = new CommonApply;
        $goFun->apply_type = Common::APPLY_TYPE_GO_FUN;
        $goFun->f_name = $request->f_name;
        $goFun->l_name = $request->l_name;
        $goFun->f_read = $request->f_read;
        $goFun->l_read = $request->l_read;
        $goFun->zip21 = $request->zip21;
        $goFun->zip22 = $request->zip22;
        $goFun->pref21 = $request->pref21;
        $goFun->address21 = $request->address21;
        $goFun->street21 = $request->street21;
        $goFun->tel = $request->tel;
        $goFun->email = $request->email;
        $goFun->save();
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
     * @return void
     */
    public function checkApplicationPeriod()
    {
        $now = date('Y-m-d H:i:s');

        if ($now <= $this->startDateTime || $now >= $this->endDateTime) {
            Redirect::route('go_fun.outsidePeriod')->send();
        }
    }

    /**
     * 画像のバリデーションを確認してアップロードする
     *
     * @param UploadedFile $file
     * @return void
     * @throws Exception
     */
    private function imgCheckAndUpload($file)
    {
        Log::info('imgCheckAndUpload');

        $IMGUploader = New ImageUploaderService();
        // 登録可能な拡張子か確認して取得する
        $extension = $IMGUploader->checkFileExtension($file);

        // ファイル名の作成 => TO_ {日時} . {拡張子}
        $this->baseFileName = sprintf(
            '%s_%s.%s',
            'go_fun',
            time(),
            $extension
        );

        // 指定されたディレクトリが存在するか確認
        $dirName = 'go_fun';
        $IMGUploader->makeDirectory($dirName);
        $IMGUploader->makeDirectory($dirName . '/resize/');
        // 画像を保存する
        $IMGUploader->imgStore($file,'public/' . $dirName, $this->baseFileName);
    }

    /**
     * @return View
     */
    public function outsidePeriod(): View
    {
        $now = date('Y-m-d H:i:s');
        $checkMessage = '';
        if ($now <= $this->startDateTime) {
            $checkMessage = 'まだ開始されていません';
        }
        if ($now >= $this->endDateTime) {
            $checkMessage = '募集期間は終了しました';
        }
        return view('go_fun.notApplicationPeriod', compact('checkMessage'));
    }

}
