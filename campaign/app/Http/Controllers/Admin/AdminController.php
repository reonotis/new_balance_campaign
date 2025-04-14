<?php

namespace App\Http\Controllers\Admin;

use App\Models\FormItem;
use App\Models\FormSetting;
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
     *
     * @return View
     */
    public function index(): View
    {
        $form_settings = FormSetting::orderBy('form_no', 'desc')
            ->paginate(10);

        return view('admin.index', [
            'form_settings' => $form_settings,
        ]);
    }

    /**
     *
     */
    public function formCreate(): View
    {
        return view('admin.create', []);
    }

    /**
     * @return RedirectResponse|void
     */
    public function formRegister(Request $request)
    {
        try {
            DB::beginTransaction();

            $form_setting_query = FormSetting::where('apply_type', $request->apply_type)
                ->where('form_no', $request->form_no)->first();

            if ($form_setting_query) {
                // 更新
                $form_setting_query->update($request->except('_token'));
            } else {
                // 登録
                FormSetting::create($request->except('_token'));
            }

            DB::commit();
            Redirect::route('admin.form-create')->send();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    /**
     *
     */
    public function getFormDetail(Request $request)
    {
        $apply_type = $request->query('apply_type');
        $form_no = $request->query('form_no') ?? 1;

        $form_setting_query = FormSetting::where('apply_type', $apply_type)
            ->where('form_no', $form_no);

        return response()->json([
            'form_setting' => $form_setting_query->first(),
        ]);
    }

    /**
     *
     */
    public function itemSetting(Request $request): View
    {
        $apply_type = $request->query('apply_type');
        $form_no = $request->query('form_no') ?? 1;

        $form_setting = FormSetting::where('apply_type', $apply_type)
            ->where('form_no', $form_no)->first();
        if (!$form_setting) {
            abort(404);
        }

        // 既存の設定してある項目を取得
        $form_items = $form_setting->formItem;

        // 設定していない項目を取得
        $none_setting_items = FormItem::ITEM_TYPE_LIST;
        foreach ($form_items as $form_item) {
            unset($none_setting_items[$form_item->type_no]);
        }

        return view('admin.item-setting', [
            'apply_type' => $apply_type,
            'form_no' => $form_no,
            'form_setting' => $form_setting,
            'form_items' => $form_items,
            'none_setting_items' => $none_setting_items,
        ]);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function itemSettingUpdate(Request $request)
    {
        $apply_type = $request->apply_type;
        $form_no = $request->form_no;
        try {
            DB::beginTransaction();

            $form_setting = FormSetting::where('apply_type', $apply_type)->where('form_no', $form_no)->first();

            // 一度削除
            FormItem::where('form_setting_id', $form_setting->id)->update([
                'delete_flag' => 1,
            ]);

            // 再登録
            $sort = 1;
            foreach ($request->type_no as $type_no) {
                $form_item = FormItem::create([
                    'form_setting_id' => $form_setting->id,
                    'type_no' => $type_no,
                    'sort' => $sort,
                    'require_flg' => 1,
                ]);

                // 選択肢の場合
                if (in_array($type_no, [
                    FormItem::ITEM_TYPE_CHOICE_1,
                    FormItem::ITEM_TYPE_CHOICE_2,
                    FormItem::ITEM_TYPE_CHOICE_3,
                ])) {

                    $form_item->update([
                        'choice_data' => [
                            'item_type' => $request->item_type[$type_no],
                            'item_name' => $request->item_name[$type_no],
                            'choices' => $request->choices[$type_no],
                        ],
                    ]);
                }


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
