<?php

namespace App\Http\Controllers;

use App\Models\MinatoRunnersBase;
use App\Service\ImageUploaderService;
use App\Consts\MinatoRunnersBaseConst;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\MinatoRunnersBaseRequest;
use Illuminate\Support\Facades\{DB, Log, Redirect};
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Mail;

class MinatoRunnersBaseController extends Controller
{
    const APPLICATION_LIMIT = 30;
    // 1回目
    // protected string $_startDateTime = "2023-05-20 00:00:00";
    // protected string $_endDateTime = "2023-06-24 23:59:59";

    // 2回目
    // protected string $_startDateTime = "2023-07-01 00:00:00";
    // protected string $_endDateTime = "2023-07-22 23:59:59";

    // 3回目
    // protected string $_startDateTime = "2023-08-04 00:00:00";
    // protected string $_endDateTime = "2023-09-02 23:59:59";

    // 4回目
    // protected string $_startDateTime = "2023-09-30 00:00:00";
    // protected string $_endDateTime = "2023-10-20 23:59:59";

    // 5回目
    // protected string $_startDateTime = "2023-11-08 00:00:00";
    // protected string $_endDateTime = "2023-12-09 23:59:59";

    // 6回目
    // protected string $_startDateTime = "2024-01-09 00:00:00";
    // protected string $_endDateTime = "2024-12-17 23:59:59";

    // 7回目
    protected string $_startDateTime = "2024-06-22 00:00:00";
    protected string $_endDateTime = "2024-07-18 23:59:59";

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
    protected string $_how_found = "";
    private string $_baseFileName = "";

    protected $_secretariat = "";

    function __construct()
    {
        $this->_secretariat = config('mail.secretariat');
        if (\Route::currentRouteName() <> 'minato.outsidePeriod') {
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
        return view('minato_runners_base.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function complete(): View
    {
        return view('minato_runners_base.complete');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MinatoRunnersBaseRequest $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(MinatoRunnersBaseRequest $request)
    {
        // バリデーションcheck
        $request->validated();
        try {
            // クラス変数に格納する
            $this->storeVariable($request);

            // 応募内容を登録
            DB::beginTransaction();
            $this->insertApplication();

            // thank youメール
            $this->sendThankYouMail();

            // reportメール
            $this->sendReportMail($request);

            DB::commit();
            Redirect::route('minato.complete')->send();

        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    /**
     * クラス変数に格納する
     * @param MinatoRunnersBaseRequest $request
     */
    public function storeVariable(MinatoRunnersBaseRequest $request)
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
        if (!empty($request->street21)) $this->_street21 = $request->street21;
        $this->_tel = $request->tel;
        $this->_email = $request->email;
        if(!empty($request->how_found)){
            $this->_how_found =  implode(',', $request->how_found);
        }
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
        $minatoRunnersBase = new MinatoRunnersBase;
        $minatoRunnersBase->f_name = $this->_f_name;
        $minatoRunnersBase->l_name = $this->_l_name;
        $minatoRunnersBase->f_read = $this->_f_read;
        $minatoRunnersBase->l_read = $this->_l_read;
        $minatoRunnersBase->age = $this->_age;
        $minatoRunnersBase->zip21 = $this->_zip21;
        $minatoRunnersBase->zip22 = $this->_zip22;
        $minatoRunnersBase->pref21 = $this->_pref21;
        $minatoRunnersBase->address21 = $this->_address21;
        $minatoRunnersBase->street21 = $this->_street21;
        $minatoRunnersBase->tel = $this->_tel;
        $minatoRunnersBase->email = $this->_email;
        $minatoRunnersBase->how_found = $this->_how_found;
        $minatoRunnersBase->save();
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
        Mail::send('emails.minato_runners_base.thankYouMail', $data, function ($message) {
            $message->to($this->_email)
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('7/20（土）イベントへのお申込みが完了しました。');
        });
    }

    /**
     * レポートメールを送信
     */
    private function sendReportMail(MinatoRunnersBaseRequest $request)
    {
        Log::info('sendReportMail');

        $howFound = [];
        if(!empty($request->how_found)){
            foreach ($request->how_found as $val)
            $howFound[] = MinatoRunnersBaseConst::HOW_FOUND[$val];
        }

        $data = [
            "name" => $this->_f_name . " " . $this->_l_name,
            "read" => $this->_f_read . " " . $this->_l_read,
            "zip" => $this->_zip21 . "-" . $this->_zip22,
            "age" => $this->_age,
            "streetAddress" => $this->_pref21 . " " . $this->_address21 . " " . $this->_street21,
            "tel" => $this->_tel,
            "email" => $this->_email,
            "howFound" => $howFound,
            "url" => url('') . '/admin'
        ];
        Mail::send('emails.minato_runners_base.reportMail', $data, function ($message) {
            $message->to("nbrun@fluss.co.jp")
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('「MINATO RUNNERS BASE イベント」に申し込みがありました');
        });
    }

    /**
     */
    public function checkApplicationPeriod()
    {
        $now = date('Y-m-d H:i:s');

        if ($now <= $this->_startDateTime || $now >= $this->_endDateTime) {
            Redirect::route('minato.outsidePeriod')->send();
        }

        if (!$this->checkNumberApplications()) {
            Redirect::route('minato.outsidePeriod')->send();
        }
    }

    /**
     */
    public function outsidePeriod()
    {
        $now = date('Y-m-d H:i:s');
        if ($now <= $this->_startDateTime) {
            $checkMessage = 'まだ開始されていません<br>' . date('n月d日', strtotime($this->_startDateTime)) . 'から申込が開始されます';
            return view('minato_runners_base.notApplicationPeriod', compact('checkMessage'));
        }
        if ($now >= $this->_endDateTime) {
            $checkMessage = '募集期間は終了しました';
            return view('minato_runners_base.notApplicationPeriod', compact('checkMessage'));
        }

        if (!$this->checkNumberApplications()) {
            $checkMessage = '応募件数が最大に達したため、申し込みを終了しました。';
            return view('minato_runners_base.notApplicationPeriod', compact('checkMessage'));
        }

        Redirect::route('minato.index')->send();
    }

    private function checkNumberApplications()
    {
        $count = MinatoRunnersBase::where('delete_flag', 0)
            ->where('created_at', '>=', $this->_startDateTime)
            ->count();
        if ($count >= self::APPLICATION_LIMIT) {
            return false;
        }
        return true;
    }

}
