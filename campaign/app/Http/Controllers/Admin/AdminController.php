<?php

namespace App\Http\Controllers\Admin;

use App\Consts\Common;
use App\Models\FormItem;
use App\Models\FormSetting;
use App\Models\Application;
use App\Service\CommonApplyService;
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
use Mail;

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
        // 一斉メール送信件数
        $send_mail_count = ($form_setting->send_bulk_mail_flg) ?
            Application::where('form_setting_id', $form_setting->id)->where('send_lottery_result_email_flg', 1)->count()
            : 0;

        return view('admin.list', [
            'form_setting' => $form_setting,
            'send_mail_count' => $send_mail_count,
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

        // CSVデータ
        $body_data = $this->getCsvRecords($form_setting);

        $response = new StreamedResponse (function () use ($header, $body_data) {
            $stream = fopen('php://output', 'w');

            //　文字化け回避
            stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932//TRANSLIT');

            // ヘッダー
            fputcsv($stream, $header);

            // CSVデータ
            foreach ($body_data as $data) {
                foreach ($data as $value) {
                    if (!mb_check_encoding($value, 'UTF-8')) {
                        \Log::warning('Encoding error in value: ' . json_encode($value));
                    }
                }
                fputcsv($stream, $data);
            }
            fclose($stream);
        });
        $response->headers->set('Content-Type', 'application/octet-stream');
        $file_name = $form_setting->title . '.csv';
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $file_name);

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
                case FormItem::ITEM_TYPE_TEL:
                case FormItem::ITEM_TYPE_EMAIL:
                case FormItem::ITEM_TYPE_NBID:
                    $columns[] = FormItem::ITEM_TYPE_LIST[$form_item->type_no];
                    break;
                case FormItem::ITEM_TYPE_ADDRESS:
                    $columns[] = '郵便番号';
                    $columns[] = '住所';
                    break;
                case FormItem::ITEM_TYPE_CHOICE_1:
                case FormItem::ITEM_TYPE_CHOICE_2:
                case FormItem::ITEM_TYPE_CHOICE_3:
                case FormItem::ITEM_TYPE_CHOICE_4:
                    $columns[] = $form_item->choice_data['item_name'];
                    break;
                case FormItem::ITEM_TYPE_COMMENT_1:
                case FormItem::ITEM_TYPE_COMMENT_2:
                case FormItem::ITEM_TYPE_COMMENT_3:
                    $columns[] = $form_item->comment_title;
                    break;
                default:
                    break;
            }
        }

        return $columns;
    }

    /**
     * @param FormSetting $form_setting
     * @return string[]
     */
    public function getCsvRecords(FormSetting $form_setting): array
    {
        $type_no_list = $form_setting->formItem->pluck('type_no')->all();

        $query = Application::where('form_setting_id', $form_setting->id);

        return $query->get()->map(function ($row) use ($type_no_list) {
            $record_array = [];
            $record_array[] = $row->id;
            $record_array[] = $row->created_at->format('Y/m/d H:i:s');

            foreach ($type_no_list as $type_no) {
                switch ($type_no) {
                    case FormItem::ITEM_TYPE_NAME:
                        $record_array[] = $row->f_name . ' ' . $row->l_name;
                        break;
                    case FormItem::ITEM_TYPE_YOMI:
                        $record_array[] = $row->f_read . ' ' . $row->l_read;
                        break;
                    case FormItem::ITEM_TYPE_SEX:
                        $record_array[] = \App\Consts\Common::SEX_LIST[$row->sex] ?? '';
                        break;
                    case FormItem::ITEM_TYPE_AGE:
                        $record_array[] = $row->age;
                        break;
                    case FormItem::ITEM_TYPE_ADDRESS:
                        $record_array[] = $row->zip21 . '-' . $row->zip22;
                        $record_array[] = $row->pref21 . ' ' . $row->address21 . ' ' . $row->street21;
                        break;
                    case FormItem::ITEM_TYPE_TEL:
                        $record_array[] = $row->tel;
                        break;
                    case FormItem::ITEM_TYPE_EMAIL:
                        $record_array[] = $row->email;
                        break;
                    case FormItem::ITEM_TYPE_CHOICE_1:
                        $record_array[] = $row->choice_1;
                        break;
                    case FormItem::ITEM_TYPE_CHOICE_2:
                        $record_array[] = $row->choice_2;
                        break;
                    case FormItem::ITEM_TYPE_CHOICE_3:
                        $record_array[] = $row->choice_3;
                        break;
                    case FormItem::ITEM_TYPE_CHOICE_4:
                        $record_array[] = $row->choice_4;
                        break;
                    case FormItem::ITEM_TYPE_NBID:
                        $record_array[] = $row->my_NBID;
                        break;
                    case FormItem::ITEM_TYPE_COMMENT_1:
                        $record_array[] = $row->comment;
                        break;
                    case FormItem::ITEM_TYPE_COMMENT_2:
                        $record_array[] = $row->comment2;
                        break;
                    case FormItem::ITEM_TYPE_COMMENT_3:
                        $record_array[] = $row->comment3;
                        break;
                    default:
                        break;
                }
            }
            return $record_array;
        })->all();
    }

    /**
     * @param FormSetting $form_setting
     * @return array
     */
    public function getApplicationsColumn(FormSetting $form_setting): array
    {
        // カラムを取得
        $columns = [];
        $columns[] = ['data' => 'id', 'title' => 'ID', 'orderable' => false];
        $columns[] = ['data' => 'created_at', 'title' => '申込日時', 'orderable' => false];

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
                case FormItem::ITEM_TYPE_CHOICE_4:
                    $columns[] = ['data' => 'choice_4', 'title' => $form_item->choice_data['item_name'], 'orderable' => false];
                    break;
                case FormItem::ITEM_TYPE_NBID:
                    $columns[] = ['data' => 'my_NBID', 'title' => FormItem::ITEM_TYPE_LIST[FormItem::ITEM_TYPE_NBID], 'orderable' => false];
                    break;
                case FormItem::ITEM_TYPE_RECEIPT_IMAGE:
                    $columns[] = ['data' => 'image', 'title' => FormItem::ITEM_TYPE_LIST[FormItem::ITEM_TYPE_RECEIPT_IMAGE], 'orderable' => false];
                    break;
                case FormItem::ITEM_TYPE_COMMENT_1:
                    $columns[] = ['data' => 'comment', 'title' => $form_item->comment_title, 'orderable' => false];
                    break;
                case FormItem::ITEM_TYPE_COMMENT_2:
                    $columns[] = ['data' => 'comment2', 'title' => $form_item->comment_title, 'orderable' => false];
                    break;
                case FormItem::ITEM_TYPE_COMMENT_3:
                    $columns[] = ['data' => 'comment3', 'title' => $form_item->comment_title, 'orderable' => false];
                    break;
                default:
                    break;
            }
        }

        if ($form_setting->send_bulk_mail_flg) {
            $columns[] = ['data' => 'application_sand_email', 'title' => 'メール送信', 'orderable' => false];
            $columns[] = ['data' => 'sent_lottery_result_email_flg', 'title' => 'メール送信', 'orderable' => false];
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
            ->get()->map(function ($row) use ($form_setting) {
                return [
                    'id' => $row->id,
                    'created_at' => $row->created_at->format('Y/m/d H:i'),
                    'name' => $row->f_name . ' ' . $row->l_name,
                    'read' => $row->f_read . ' ' . $row->l_read,
                    'email' => $row->email,
                    'sex' => Common::SEX_LIST[$row->sex] ?? '',
                    'age' => $row->age,
                    'tel' => $row->tel,
                    'address' =>  $row->zip21 . '-' . $row->zip22 . '<br>' . $row->pref21 . ' ' . $row->address21 . '<br>' . $row->street21,
                    'choice_1' => $row->choice_1,
                    'choice_2' => $row->choice_2,
                    'choice_3' => $row->choice_3,
                    'choice_4' => $row->choice_4,
                    'my_NBID' => $row->my_NBID,
                    'image' => $row->img_pass
                        ? '<img src="' . asset('storage/' . $form_setting->image_dir_name . '/resize/' . $row->img_pass) . '" alt="レシート画像" class="resize_img" width="100">'
                        : '',
                    'comment' => $row->comment,
                    'comment2' => $row->comment2,
                    'comment3' => $row->comment3,
                    'application_sand_email' => '<input type="checkbox" class="application-sand-email" data-id="' . $row->id . '" ' . ($row->send_lottery_result_email_flg ? 'checked' : '') . '>',
                    'sent_lottery_result_email_flg' => $row->sent_lottery_result_email_flg
                        ? '送信済み'
                        : '未送信',
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
            $params['send_bulk_mail_flg'] = 0;
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
                    FormItem::ITEM_TYPE_CHOICE_4,
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

    /**
     * 対象者に一斉メールを送信するか否かの保存を行い、トータル何件送信するかの件数を返却する
     * @param FormSetting $form_setting
     * @param Request $request
     * @return array[]|false|string
     */
    public function applicationSandEmailFlg(FormSetting $form_setting, Request $request)
    {
        $application_id = $request->input('id');
        $value = $request->input('value');

        $updated_count = Application::where('form_setting_id', $form_setting->id)->where('id', $application_id)->update([
            'send_lottery_result_email_flg' => $value,
        ]);

        if ($updated_count === 0) {
            return json_encode([
                'error' => [
                    'code' => 404,
                    'msg' => '該当のデータが存在しません',
                ],
            ]);
        }

        // 送信予定件数取得
        return [
            'success' => [
                'count' => Application::where('form_setting_id', $form_setting->id)->where('send_lottery_result_email_flg', 1)->count(),
            ],
        ];
    }

    /**
     * 一斉メールを送信する
     */
    public function applicationSandMail(FormSetting $form_setting)
    {
        if($form_setting->send_bulk_mail_flg == 0) {
            return false;
        }

        // 一斉メールを送信する対象を取得
        $send_mail_list = Application::where('form_setting_id', $form_setting->id)
            ->where('send_lottery_result_email_flg', 1) // 送信する人
            ->where('sent_lottery_result_email_flg', 0) // まだ送信していない人
            ->get();

        $count = 0;
        foreach ($send_mail_list as $application) {
            if (
                is_null($application->email) ||
                is_null($application->f_name) ||
                is_null($application->l_name)
            ) {
                continue;
            }

            Mail::send('emails.' . $form_setting->route_name . '.bulkMail', $application->toArray(), function ($message) use ($application) {
                $message->to($application->email)
                    ->from('info@newbalance-campaign.jp')
                    ->bcc('fujisawareon@yahoo.co.jp')
                    ->subject('【東京レガシーハーフマラソン2025 出走権プレゼントキャンペーン】');
            });

            $application->update(['sent_lottery_result_email_flg' => 1]);
            $count++;
        }

        return response()->json(['count' => $count]);
    }
}
