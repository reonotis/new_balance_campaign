<?php

namespace App\Http\Controllers\Admin;

use App\Models\FormItem;
use App\Models\FormSetting;
use App\Models\Application;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
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
            ->whereIn('form_setting_id', $form_setting_ids)
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
        return view('admin.list', [
            'form_setting' => $form_setting,
        ]);
    }

    /**
     * @param FormSetting $form_setting
     * @return StreamedResponse
     */
    public function csvDownload(FormSetting $form_setting): StreamedResponse
    {
        // CSVヘッダー
        $header = $this->getCsvHeader($form_setting);

        // csvデータ
        $body_data = []; // TODO

        $response = new StreamedResponse (function () use ($header, $body_data) {
            $stream = fopen('php://output', 'w');

            //　文字化け回避
            stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932');

            // ヘッダー
            fputcsv($stream, $header);

            // CSVデータ
            foreach ($body_data as $data) {
                fputcsv($stream, $data);
            }
            fclose($stream);
        });
        $response->headers->set('Content-Type', 'application/octet-stream');
        $fileName = 'applyTitle.csv';
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $fileName);

        return $response;
    }

    /**
     * @param FormSetting $form_setting
     * @return string[]
     */
    public function getCsvHeader(FormSetting $form_setting): array
    {
        $columns = ['ID', '申込日時'];
        foreach ($form_setting->formItem as $form_item) {
            switch ($form_item->type_no) {
                case FormItem::ITEM_TYPE_NAME:
                case FormItem::ITEM_TYPE_YOMI:
                case FormItem::ITEM_TYPE_SEX:
                case FormItem::ITEM_TYPE_AGE:
                case FormItem::ITEM_TYPE_ADDRESS:
                case FormItem::ITEM_TYPE_TEL:
                case FormItem::ITEM_TYPE_EMAIL:
                    $columns[] = FormItem::ITEM_TYPE_LIST[$form_item->type_no];
                    break;
                case FormItem::ITEM_TYPE_CHOICE_1:
                case FormItem::ITEM_TYPE_CHOICE_2:
                case FormItem::ITEM_TYPE_CHOICE_3:
                    $columns[] = $form_item->choice_data['item_name'];
                    break;
                case FormItem::ITEM_TYPE_COMMENT_1:
                    $columns[] =  $form_item->comment_title;
                    break;
                default:
                    break;
            }
        }

        return $columns;
    }


    /**
     * @param FormSetting $form_setting
     * @return array
     */
    public function getApplicationsColumn(FormSetting $form_setting): array
    {
        // カラムを取得
        $columns = [];
        $columns[] = ['data' => 'id', 'title' => 'ID', 'orderable' => true];
        $columns[] = ['data' => 'created_at', 'title' => '申込日時', 'orderable' => true];

        foreach ($form_setting->formItem as $form_item) {
            switch ($form_item->type_no) {
                case FormItem::ITEM_TYPE_NAME:
                    $columns[] = ['data' => 'name', 'title' => FormItem::ITEM_TYPE_LIST[FormItem::ITEM_TYPE_NAME], 'orderable' => false];
                    break;
                case FormItem::ITEM_TYPE_YOMI:
                    $columns[] = ['data' => 'read', 'title' => FormItem::ITEM_TYPE_LIST[FormItem::ITEM_TYPE_YOMI], 'orderable' => false];
                    break;
                case FormItem::ITEM_TYPE_SEX:
                    $columns[] = ['data' => 'sex', 'title' => FormItem::ITEM_TYPE_LIST[FormItem::ITEM_TYPE_SEX], 'orderable' => false];
                    break;
                case FormItem::ITEM_TYPE_AGE:
                    $columns[] = ['data' => 'age', 'title' => FormItem::ITEM_TYPE_LIST[FormItem::ITEM_TYPE_AGE], 'orderable' => false];
                    break;
                case FormItem::ITEM_TYPE_ADDRESS:
                    $columns[] = ['data' => 'address', 'title' => FormItem::ITEM_TYPE_LIST[FormItem::ITEM_TYPE_ADDRESS], 'orderable' => false];
                    break;
                case FormItem::ITEM_TYPE_TEL:
                    $columns[] = ['data' => 'tel', 'title' => FormItem::ITEM_TYPE_LIST[FormItem::ITEM_TYPE_TEL], 'orderable' => false];
                    break;
                case FormItem::ITEM_TYPE_EMAIL:
                    $columns[] = ['data' => 'email', 'title' => FormItem::ITEM_TYPE_LIST[FormItem::ITEM_TYPE_EMAIL], 'orderable' => false];
                    break;
                case FormItem::ITEM_TYPE_CHOICE_1:
                    $columns[] = ['data' => 'choice_1', 'title' => $form_item->choice_data['item_name'], 'orderable' => false];
                    break;
                case FormItem::ITEM_TYPE_CHOICE_2:
                    $columns[] = ['data' => 'choice_2', 'title' => $form_item->choice_data['item_name'], 'orderable' => false];
                    break;
                case FormItem::ITEM_TYPE_CHOICE_3:
                    $columns[] = ['data' => 'choice_3', 'title' => $form_item->choice_data['item_name'], 'orderable' => false];
                    break;
                case FormItem::ITEM_TYPE_COMMENT_1:
                    $columns[] = ['data' => 'comment', 'title' => $form_item->comment_title, 'orderable' => false];
                    break;
                default:
                    break;
            }
        }
        return $columns;
    }

    /**
     * @param FormSetting $form_setting
     * @param Request $request
     * @return JsonResponse
     */
    public function getApplicationsList(FormSetting $form_setting, Request $request): JsonResponse
    {
        Log::warning($request);

        $draw = intval($request->input('draw', 1));
        $start = intval($request->input('start', 0));
        $length = intval($request->input('length', 10));

        // 検索条件を加味したクエリ
        $query = Application::where('form_setting_id', $form_setting->id);

        // 対象フォームに紐づく全申込データ
        $count = $query->count();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // 絞り込んだ総件数
        $filtered_count = $query->count();

        // ページング + データ取得
        $data = $query
            ->offset($start)
            ->limit($length)
            ->get()->map(function ($row) {
                return [
                    'id' => $row->id,
                    'created_at' => $row->created_at->format('Y/m/d H:i'),
                    'name' => $row->f_name . ' ' . $row->l_name,
                    'read' => $row->f_read . ' ' . $row->l_read,
                    'email' => $row->email,
                    'sex' => $row->sex,
                    'age' => $row->age,
                    'tel' => $row->tel,
                    'address' => $row->zip21,
                    'choice_1' => $row->choice_1,
                    'choice_2' => $row->choice_2,
                    'choice_3' => $row->choice_3,
                    'comment' => $row->comment,
                ];
            });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $filtered_count,
            'data' => $data,
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

            $request_data = $request->all();
            $request_data['start_at'] = $request->start_at . ' 00:00:00';
            $request_data['end_at'] = $request->end_at . ' 23:59:59';

            $form_setting->update($request_data);
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
            $params['start_at'] = $params['start_at'] . ' 00:00:00';
            $params['end_at'] = $params['end_at'] . ' 23:59:59';
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
                        'support_msg' => $request->support_msg[$type_no] ?? null,
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
