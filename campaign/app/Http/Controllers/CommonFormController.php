<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormCommonRequest;
use App\Models\Application;
use App\Models\FormSetting;
use App\Service\CommonFormService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{DB, Log, Redirect, Route};
use Mail;

class CommonFormController extends Controller
{
    private CommonFormService $common_service_service;

    private FormSetting $form_setting;

    private string $duration_message;
    private string $email;

    /**
     * コンストラクタ
     */
    function __construct()
    {
        $this->middleware(function ($request, $next) {

            $route_name = request()->route('route_name');

            // 基本設定を取得
            $form_setting = FormSetting::where('route_name', $route_name)
                ->where('delete_flag', 0)
                ->orderBy('id', 'desc')
                ->first();

            // レコードが取得できない場合は設定されていないので404
            if (empty($form_setting)) {
                abort(404);
            } else {
                $form_setting->load('formItem');
                $this->form_setting = $form_setting;
            }

            $this->duration_message = '';
            if ($this->checkErrorViewRedirect()) {
                Redirect::route('common_form.outsidePeriod', ['route_name' => $route_name])->send();
            }

            return $next($request);
        });
    }

    /**
     * 申込フォーム画面を表示する
     * @param $route_name
     * @return View
     */
    public function index(string $route_name): View
    {
        $form_items = $this->form_setting->formItem;

        return view('common_form.index', [
            'form_setting' => $this->form_setting,
            'form_items' => $form_items,
            'send_route' => route('common_form.store', ['route_name' => $route_name]),
        ]);
    }

    /**
     * 申込完了画面を表示する
     * @return View
     */
    public function complete(): View
    {
        return view('common_form.complete', [
            'form_setting' => $this->form_setting,
        ]);
    }

    /**
     * 申込内容登録処理
     * @param string $route_name
     * @param FormCommonRequest $request
     * @return RedirectResponse|void
     */
    public function store(string $route_name, FormCommonRequest $request)
    {
        $this->common_service_service = new CommonFormService();
        try {
            DB::beginTransaction();

            // 応募内容を登録
            $this->insertApplication($request);

            // reportメール
            $this->sendReportMail($request);

            // thank youメール
            $this->sendThankYouMail($request);

            DB::commit();

            Redirect::route('common_form.complete', ['route_name' => $route_name])->send();

        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    /**
     * 申し込み内容をDBに登録
     * @param FormCommonRequest $request
     * @param FormSetting $form_setting
     */
    private function insertApplication(FormCommonRequest $request): void
    {
        Log::info('insertApplication');

        $this->common_service_service->insertCommonApply($request->all(), $this->form_setting);
    }

    /**
     * レポートメールを送信
     */
    private function sendReportMail(FormCommonRequest $request)
    {
        Log::info('sendReportMail');

        $data = [
            'request' => $request,
            'form_items' => $this->form_setting->formItem,
            'url' => url('') . '/admin',
        ];
        Mail::send('emails.common_form.reportMail', $data, function ($message) {
            $message->to($this->form_setting->secretariat_mail_address)
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject($this->form_setting->title . 'への申込を受け付けました');
        });
    }

    /**
     * 申し込み者に自動返信メールを送信
     */
    private function sendThankYouMail(FormCommonRequest $request)
    {
        Log::info('sendThankYouMail');

        $this->email = $request->email;
        $data = [
            'mail_text' => $this->form_setting->mail_text,
        ];
        Mail::send('emails.common_form.thankYouMail', $data, function ($message) {
            $message->to($this->email)
                ->from('info@newbalance-campaign.jp')
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject($this->form_setting->mail_title);
        });
    }

    /**
     * エラー画面を表示する
     * @return View
     */
    public function outsidePeriod(): View
    {
        $this->setErrorMessage();

        return view('common_form.notApplicationPeriod', [
            'form_setting' => $this->form_setting,
            'check_message' => $this->duration_message,
        ]);
    }

    /**
     * 対象期間の応募数が最大数に達していない事をチェックする
     * @return bool
     */
    private function checkNumberApplications(): bool
    {
        // 最大値が設定されていなければ確認しない
        if (!$this->form_setting->max_application_count) {
            return true;
        }

        $count = Application::where('form_setting_id', $this->form_setting->id)
            ->where('created_at', '>=', $this->form_setting->start_at)
            ->count();

        // 最大値に達しているか確認
        if ($count >= $this->form_setting->max_application_count) {
            return false;
        }

        return true;
    }

    /**
     * エラー画面にリダイレクトするか判断する
     * @return bool
     */
    private function checkErrorViewRedirect(): bool
    {
        $this->setErrorMessage();
        // 既にエラー画面に行こうとしている場合、
        // もしくは申込完了画面に行こうとしている場合は再リダイレクトさせない
        if (
            Route::currentRouteName() === 'common_form.outsidePeriod' ||
            Route::currentRouteName() === 'common_form.complete'
        ) {
            return false;
        }

        if ($this->duration_message) {
            return true;
        }

        return false;
    }

    /**
     * エラーがある場合はエラーメッセージをセットする
     * @return void
     */
    private function setErrorMessage(): void
    {
        // 申込期間外の場合はリダイレクトする
        $now = Carbon::now();
        if ($now <= $this->form_setting->start_at) {
            $this->duration_message = $this->form_setting->start_at->isoFormat('M月D(ddd) H:mm') . 'より応募が可能となります。';
            return;
        }

        if ($now >= new Carbon($this->form_setting->end_at)) {
            $this->duration_message = '募集期間は終了しました';
            return;
        }

        // 最大申込数に達している場合はエラー画面に遷移
        if (!$this->checkNumberApplications()) {
            $this->duration_message = '申込最大数に達したため閉め切りました';
            return;
        }
    }

}
