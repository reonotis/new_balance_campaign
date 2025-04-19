<?php

namespace App\Http\Controllers\Admin;

use App\Models\FormItem;
use App\Models\FormSetting;
use App\Models\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class AdminController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 申込フォーム一覧画面を表示する
     * @return View
     */
    public function index(): View
    {
        $form_settings = FormSetting::paginate(10);

        $form_setting_ids = $form_settings->pluck('id')->toArray();
        $application_count = Application::select('form_setting_id', DB::raw('COUNT(*) as count'))
            ->whereIn('form_setting_id' , $form_setting_ids)
            ->groupBy('form_setting_id')
            ->pluck('count', 'form_setting_id')
            ->toArray();

        return view('admin.index', [
            'form_settings' => $form_settings,
            'application_count' => $application_count,
        ]);
    }

    /**
     * 応募一覧を表示する
     * @param FormSetting $form_setting
     * @return View
     */
    public function list(FormSetting $form_setting): View
    {
        $applications = Application::where('form_setting_id', $form_setting->id)
            ->paginate(50);

        return view('admin.list', [
            'form_setting' => $form_setting,
            'applications' => $applications,
        ]);
    }

    /**
     * フォーム作成画面を表示する
     * @param Request $request
     * @return View
     */
    public function formCreate(Request $request): View
    {
        $form_setting = null;
        if ($request->form_setting) {
            $form_setting = FormSetting::find($request->form_setting);
        }

        return view('admin.create', [
            'form_setting' => $form_setting,
        ]);
    }

    /**
     * フォーム情報更新画面を表示する
     * @param FormSetting $form_setting
     * @return View
     */
    public function formEdit(FormSetting $form_setting): View
    {
        return view('admin.edit', [
            'form_setting' => $form_setting,
        ]);
    }

    /**
     * フォーム情報を更新する
     * @param FormSetting $form_setting
     * @param Request $request
     * @return RedirectResponse|void
     */
    public function formUpdate(FormSetting $form_setting, Request $request)
    {
        try {
            DB::beginTransaction();

            $form_setting->update($request->all());
            DB::commit();
            Redirect::route('admin.form-edit', ['form_setting' => $form_setting])->send();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * フォーム情報を登録する
     * @return RedirectResponse|void
     */
    public function formRegister(Request $request)
    {
        try {
            DB::beginTransaction();

            // 登録
            $params = $request->except('_token');
            $params['start_at'] = $params['start_at']. ' 00:00:00';
            $params['end_at'] = $params['end_at']. ' 23:59:59';
            FormSetting::create($params);

            DB::commit();
            Redirect::route('admin.form-create')->send();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    /**
     * 申込フォームの項目設定画面を表示する
     * @param FormSetting $form_setting
     * @return View
     */
    public function formItemEdit(FormSetting $form_setting): View
    {
        // 既存の設定してある項目を取得
        $form_items = $form_setting->formItem;

        // 設定していない項目を取得
        $none_setting_items = FormItem::ITEM_TYPE_LIST;
        foreach ($form_items as $form_item) {
            unset($none_setting_items[$form_item->type_no]);
        }

        return view('admin.form-item-edit', [
            'form_setting' => $form_setting,
            'form_items' => $form_items,
            'none_setting_items' => $none_setting_items,
        ]);
    }

    /**
     * 申込フォームの項目設定を更新する
     * @param FormSetting $form_setting
     * @param Request $request
     * @return void
     */
    public function formItemUpdate(FormSetting $form_setting, Request $request)
    {
        try {
            DB::beginTransaction();

            // 一度削除
            FormItem::where('form_setting_id', $form_setting->id)->update([
                'delete_flag' => 1,
            ]);

            // 再登録
            $sort = 1;
            foreach ($request->type_no as $type_no) {
                $form_item_param = [
                    'form_setting_id' => $form_setting->id,
                    'type_no' => $type_no,
                    'sort' => $sort,
                    'require_flg' => 1,
                ];

                // コメントの場合は項目名を入れる
                if (in_array($type_no, [
                    \App\Models\FormItem::ITEM_TYPE_COMMENT_1,
                    \App\Models\FormItem::ITEM_TYPE_COMMENT_2,
                    \App\Models\FormItem::ITEM_TYPE_COMMENT_3,
                ])) {
                    $form_item_param['comment_title'] = $request->comment_title[$type_no];
                }

                // 注意事項の場合
                if ($type_no == FormItem::ITEM_TYPE_NOTES) {
                    $form_item_param['choice_data'] = [
                        'item_name' => $request->item_name[$type_no],
                        'support_msg' => $request->support_msg[$type_no],
                    ];
                }

                // 選択肢の場合
                if (in_array($type_no, [
                    FormItem::ITEM_TYPE_CHOICE_1,
                    FormItem::ITEM_TYPE_CHOICE_2,
                    FormItem::ITEM_TYPE_CHOICE_3,
                ])) {
                    $form_item_param['choice_data'] = [
                        'item_type' => $request->item_type[$type_no],
                        'item_name' => $request->item_name[$type_no],
                        'choices' => $request->choices[$type_no],
                        'support_msg' => $request->support_msg[$type_no],
                    ];
                }

                FormItem::create($form_item_param);

                $sort++;
            }

            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return redirect()->back()->withInput();
        }
    }

}
