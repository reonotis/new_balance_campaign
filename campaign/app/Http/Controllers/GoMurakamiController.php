<?php

namespace App\Http\Controllers;

use App\Consts\{Common};
use App\Models\GoMurakami;
use App\Service\ImageUploaderService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{DB, Log, Redirect};
use Illuminate\Support\Facades\Mail;
use InterventionImage;

class GoMurakamiController extends Controller
{
//    protected $_startDateTime = "2023-03-01 00:00:00";
    protected $_startDateTime = "2023-02-01 00:00:00";
    protected $_endDateTime = "2023-05-07 23:59:59";

    protected $_f_name = "";
    protected $_l_name = "";
    protected $_f_read = "";
    protected $_l_read = "";
    protected $_sex    = "";
    protected $_zip21  = "";
    protected $_zip22  = "";
    protected $_pref21 = "";
    protected $_address21 = "";
    protected $_street21 = "";
    protected $_tel    = "";
    protected $_email  = "";
    protected $_reason_applying  = "";
    private $_baseFileName  = "";

    protected $_errorMSG = [];

    protected $_secretariat = "";

    function __construct()
    {
        $this->_secretariat = config('mail.secretariat');
        if(\Route::currentRouteName() <> 'aruku-tokyo-2022.outsidePeriod'){
            $this->checkApplicationPeriod();
        }
    }

    /**
     * @return View
     */
    public function index()
    {
        return view('goMurakami.index');
    }

    /**
     * @return View
     */
    public function complete()
    {
        return view('goMurakami.complete');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // バリデーションcheck
            $this->checkValidation($request);

            // クラス変数に格納する
            $this->storeVariable($request);

            // 画像処理
            $this->_imgCheckAndUpload($request->image);

            // 応募内容を登録
            DB::beginTransaction();
            $this->insertApplication();

            // thank youメール
            $this->sendThankYouMail();

            // reportメール
            $this->sendReportMail();

            DB::commit();
            return redirect('/go-murakami-2023/complete');

        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return redirect()->back()->with('errors', $this->_errorMSG)->withInput();
            exit;
        }
    }

    /**
     * バリデーションcheck
     * @param request $request
     * @throw new Exception
     */
    public function checkValidation($request)
    {
        Log::info('checkValidation');
        $this->_errorMSG = [];

        if (!$request->f_name) $this->_errorMSG[] = "苗字を入力してください";
        if (!$request->l_name) $this->_errorMSG[] = "名前を入力してください";
        if (!$request->f_read){
            $this->_errorMSG[] = "ミョウジを入力してください";
        }else if(!preg_match(Common::ZENKAKUKANA, $request->f_read)) {
            $this->_errorMSG[] = "ミョウジは全角カナで入力してください";
        }

        if (!$request->l_read){
            $this->_errorMSG[] = "ナマエを入力してください";
        }else if(!preg_match(Common::ZENKAKUKANA, $request->l_read)){
            $this->_errorMSG[] = "ナマエは全角カナで入力してください";
        }

        if(strlen($request->zip21) <> 3 || strlen($request->zip22) <> 4  )$this->_errorMSG[] = "郵便番号は3桁-4桁で入力してください";
        if (!$request->pref21) $this->_errorMSG[] = "都道府県を入力してください";
        if (!$request->address21) $this->_errorMSG[] = "市区町村を入力してください";
        if (!$request->street21) $this->_errorMSG[] = "番地を入力してください";

        if (!preg_match(\App\Consts\Common::DENWABANGOU, $request->tel)) $this->_errorMSG[] = "電話番号は市外局番から-(ハイフン)を含めて入力してください";

        if (!preg_match(\App\Consts\Common::MAILADDRESS, $request->email1)){
            $this->_errorMSG[] = "メールアドレスを正しく入力してください";
        }else if($request->email1 <> $request->email2){
            $this->_errorMSG[] = "メールアドレスが確認用と一致していません";
        }
        if(empty($request->image)){
            $this->_errorMSG[] = "レシート画像が添付されていません";
        }

        if($this->_errorMSG){
            $errorMessage = implode("<br>\n" , $this->_errorMSG) ;
            throw new Exception($errorMessage);
        }
    }

    /**
     * クラス変数に格納する
     * @param request $request
     */
    public function storeVariable($request)
    {
        Log::info('storeVariable');
        $this->_f_name   = $request->f_name;
        $this->_l_name   = $request->l_name;
        $this->_f_read   = $request->f_read;
        $this->_l_read   = $request->l_read;
        // $this->_age      = $request->age;
        $this->_sex      = $request->sex;
        $this->_zip21    = $request->zip21;
        $this->_zip22    = $request->zip22;
        $this->_pref21   = $request->pref21;
        $this->_address21= $request->address21;
        $this->_street21 = $request->street21;
        $this->_tel      = $request->tel;
        $this->_email    = $request->email1;
        $this->_reason_applying    = $request->reason_applying;
    }

    /**
     * 画像のバリデーションを確認してアップロードする
     *
     * @param [type] $file
     * @return void
     */
    private function _imgCheckAndUpload($file)
    {
        Log::info('_imgCheckAndUpload');


        $IMGUploader = New ImageUploaderService();
        // 登録可能な拡張子か確認して取得する
        $extension = $IMGUploader->checkFileExtension($file);

        // ファイル名の作成 => TO_ {日時} . {拡張子}
        $this->_baseFileName = sprintf(
            '%s_%s.%s',
            'go_murakami',
            time(),
            $extension
        );

        // 指定されたディレクトリが存在するか確認
        $dirName = 'murakami';
        $IMGUploader->makeDirectory($dirName);
        $IMGUploader->makeDirectory($dirName . '/resize/');
        // 画像を保存する
        $IMGUploader->imgStore($file,'public/' . $dirName, $this->_baseFileName);

    }

    /**
     * 申し込み内容をDBに登録
     */
    public function insertApplication()
    {
        Log::info('insertApplication');
        $fc_tokyo = new GoMurakami;
        $fc_tokyo->f_name = $this->_f_name;
        $fc_tokyo->l_name = $this->_l_name;
        $fc_tokyo->f_read = $this->_f_read;
        $fc_tokyo->l_read = $this->_l_read;
        // $fc_tokyo->age    = $this->_age;
        // $fc_tokyo->sex    = $this->_sex;
        $fc_tokyo->zip21  = $this->_zip21;
        $fc_tokyo->zip22  = $this->_zip22;
        $fc_tokyo->pref21 = $this->_pref21;
        $fc_tokyo->address21 = $this->_address21;
        $fc_tokyo->street21 = $this->_street21;
        $fc_tokyo->tel    = $this->_tel;
        $fc_tokyo->email  = $this->_email;
        $fc_tokyo->img_pass	= $this->_baseFileName;
        $fc_tokyo->save();
    }

    /**
     * 申し込み者に自動返信メールを送信
     */
    public function sendThankYouMail()
    {
        Log::info('sendThankYouMail');
        $data = [
            "customerName" => $this->_f_name . $this->_l_name
        ];
        Mail::send('emails.goMurakami.thankYouMail', $data, function($message){
            $message->to($this->_email)
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('ご応募ありがとございました。');
        });
    }

    /**
     * レポートメールを送信
     */
    public function sendReportMail()
    {
        Log::info('sendReportMail');
        $data = [
            "name" => $this->_f_name . " " .$this->_l_name,
            "read" => $this->_f_read . " " .$this->_l_read,
            "zip"  => $this->_zip21 . "-" .$this->_zip22 ,
            "streetAddress"  => $this->_pref21 . " " .$this->_address21 . " " .$this->_street21 ,
            "tel"  => $this->_tel,
            "email"  => $this->_email,
            "url"  => url('').'/admin'
        ];
        Mail::send('emails.goMurakami.reportMail', $data, function($message){
            $message->to("nb_go-murakami-2023@fluss.co.jp")
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('「村上宗隆選手応援 キャンペーン」に申し込みがありました');
        });
    }

    /**
     */
    public function checkApplicationPeriod()
    {
        $now = date('Y-m-d H:i:s');

        if($now <= $this->_startDateTime || $now >= $this->_endDateTime){
            Redirect::route('goMurakami.outsidePeriod')->send();
        }
    }

    /**
     * @return View
     */
    public function outsidePeriod(): View
    {
        $now = date('Y-m-d H:i:s');
        $checkMessage = '';
        if($now <= $this->_startDateTime){
            $checkMessage = 'まだ開始されていません';
        }
        if($now >= $this->_endDateTime){
            $checkMessage = '募集期間は終了しました';
        }
        return view('goMurakami.notApplicationPeriod', compact('checkMessage'));
    }

}
