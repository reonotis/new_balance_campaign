<?php

namespace App\Http\Controllers;


use App\Consts\{Common, S223};
use App\Models\CommonApply;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\S223Request;
use Illuminate\Support\Facades\{DB, Log, Redirect};
use Illuminate\Routing\Redirector;
use Mail;

class S223Controller extends Controller
{
    protected string $_startDateTime = "2023-07-25 00:01:00";
    protected string $_endDateTime = "2023-08-10 23:59:59";

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

    protected string $_secretariat = "";

    function __construct()
    {
        $this->_secretariat = config('mail.secretariat');
        if (\Route::currentRouteName() <> 's223.outsidePeriod') {
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
        $choiceTimeList = $this->getChoiceTime();
        return view('s223.index', compact('choiceTimeList'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function complete(): View
    {
        return view('s223.complete');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param S223Request $request
     * @return Application|Redirector|RedirectResponse
     */
    public function store(S223Request $request)
    {
        // バリデーションcheck
        $request->validated();
        try {
            DB::beginTransaction();

            // 応募内容を登録
            $this->insertApplication($request);

            // thank youメール
            $this->sendThankYouMail();

            // reportメール
            $this->sendReportMail($request);

            DB::commit();
            return redirect('/s223/complete');
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    /**
     * 申し込み内容をDBに登録
     *
     */
    private function insertApplication(S223Request $request): void
    {
        Log::info('insertApplication');
        $a223 = new CommonApply;
        $a223->apply_type = Common::APPLY_TYPE_S223;
        $a223->f_name = $request->f_name;
        $a223->l_name = $request->l_name;
        $a223->f_read = $request->f_read;
        $a223->l_read = $request->l_read;
        $a223->zip21 = $request->zip21;
        $a223->zip22 = $request->zip22;
        $a223->pref21 = $request->pref21;
        $a223->address21 = $request->address21;
        $a223->street21 = $request->street21;
        $a223->tel = $request->tel;
        $a223->email = $request->email;
        $a223->choice_1 = $request->choice_time;
        $a223->save();
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
        Mail::send('emails.s223.thankYouMail', $data, function ($message) {
            $message->to($this->_email)
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('お申込みありがとうございます。');
        });
    }

    /**
     * レポートメールを送信
     */
    private function sendReportMail(S223Request $request)
    {
        Log::info('sendReportMail');
        $data = [
            "choiceTime" => S223::CHOICE_TIME[$request->choice_time]['time'],
            "name" => $request->f_name . " " . $request->l_name,
            "read" => $request->f_read . " " . $request->l_read,
            "zip" => $request->zip21 . "-" . $request->zip22,
            "streetAddress" => $request->pref21 . " " . $request->address21 . " " . $request->street21,
            "tel" => $request->tel,
            "email" => $request->email,
            "url" => url('') . '/admin'
        ];
        Mail::send('emails.s223.reportMail', $data, function ($message) {
            $message->to("nbrun@fluss.co.jp")
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject('「S223 キャンペーン」に申し込みがありました');
        });
    }

    /**
     * @return void
     */
    public function checkApplicationPeriod()
    {
        $now = date('Y-m-d H:i:s');

        if ($now <= $this->_startDateTime || $now >= $this->_endDateTime) {
            Redirect::route('s223.outsidePeriod')->send();
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
        return view('s223.notApplicationPeriod', compact('checkMessage'));
    }

    /**
     * 各時間帯に申込されている件数を確認する
     * @return array $choiceTimeList
     */
    private function getChoiceTime(): array
    {
        $choiceTimeList = S223::CHOICE_TIME;
        $records = CommonApply::getAll(Common::APPLY_TYPE_S223);

        // 申込件数をカウントする前に初期化する
        foreach ($choiceTimeList as &$choiceTime) {
            $choiceTime['count'] = 0;
            $choiceTime['apply_limit'] = false;
        }
        unset($choiceTime); // 参照渡ししている為、バグ回避で解除しておく

        // 件数をカウントする
        foreach ($records as $s223Record) {
            if (!array_key_exists($s223Record->choice_1, $choiceTimeList)) {
                continue;
            }
            $choiceTimeList[$s223Record->choice_1]['count']++;
        }

        // 上限に達しているか確認
        foreach ($choiceTimeList as $choiceTime) {
            if ($choiceTime['count'] >= $choiceTime['limit']) {
                $choiceTime['apply_limit'] = true;
            }
        }

        return $choiceTimeList;
    }

}
