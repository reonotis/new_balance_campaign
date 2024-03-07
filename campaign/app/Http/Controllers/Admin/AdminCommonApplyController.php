<?php

namespace App\Http\Controllers\Admin;

use App\Consts\Common;
use App\Consts\CommonApplyConst;
use App\Models\CommonApply;
use App\Service\CommonApplyService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class AdminCommonApplyController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private int $applyType;

    /**
     * @param int $applyType
     * @return View
     */
    public function index(int $applyType): View
    {
        $this->applyType = $applyType;
        if (!isset(CommonApplyConst::APPLY_TYPE_DISPLAY_COLUMN[$applyType])) {
            dd('不正な画面遷移です');
        }
        $displayItemList = CommonApplyConst::APPLY_TYPE_DISPLAY_COLUMN[$applyType];
        $applyTitle = CommonApplyConst::APPLY_TITLE_LIST[$applyType];

        /** @var CommonApplyService $service */
        $service = app(CommonApplyService::class, ['applyType' => $applyType]);
        $paginationList = $service->getByApplyTypeWithPaginate($applyType, 20);
        $applyList = $this->convertOfMapping($displayItemList, $paginationList);

        return view('admin.common_apply.index')
            ->with('applyType', $this->applyType)
            ->with('applyTitle', $applyTitle)
            ->with('displayItemList', $displayItemList)
            ->with('paginationList', $paginationList)
            ->with('applyList', $applyList);
    }

    /**
     * @param int $applyType
     */
    public function redirectApplyForm(int $applyType)
    {
        if (!isset(CommonApplyConst::APPLY_URL_NAME[$applyType])) {
            dd('不正な画面遷移です');
        }

        Redirect::route(CommonApplyConst::APPLY_URL_NAME[$applyType])->send();
    }

    /**
     * @param int $applyType
     * @return StreamedResponse
     */
    public function csv_dl(int $applyType): StreamedResponse
    {
        $this->applyType = $applyType;
        if (!isset(CommonApplyConst::APPLY_TYPE_DISPLAY_COLUMN[$applyType])) {
            dd('不正な画面遷移です');
        }
        $displayItemList = CommonApplyConst::APPLY_TYPE_DISPLAY_COLUMN[$applyType];
        $applyTitle = CommonApplyConst::APPLY_TITLE_LIST[$applyType];

        /** @var CommonApplyService $service */
        $service = app(CommonApplyService::class, ['applyType' => $applyType]);
        $applyList = $service->getByApplyType($applyType);

        // CSVヘッダー
        $header = $this->makeCSVHeader($displayItemList);

        // csvデータ
        $bodyData = [];
        foreach ($applyList as $apply) {
            $bodyData[] = $this->makeCSVBody($displayItemList, $apply);
        }

        $response = new StreamedResponse (function () use ($header, $bodyData) {
            $stream = fopen('php://output', 'w');

            //　文字化け回避
            stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932');

            // ヘッダー
            fputcsv($stream, $header);

            // CSVデータ
            foreach ($bodyData as $data) {
                fputcsv($stream, $data);
            }
            fclose($stream);
        });
        $response->headers->set('Content-Type', 'application/octet-stream');
        $fileName = $this->makeCSVName($applyTitle);
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $fileName);

        return $response;
    }

    /**
     * @param array $displayItemList
     * @param $applyList
     * @return array
     */
    private function convertOfMapping(array $displayItemList, $applyList): array
    {
        if (empty($applyList)) {
            return [];
        }

        $array = [];
        // CommonApplyのレコードをループ
        foreach ($applyList as $apply) {
            $items = [];

            // 列に表示する値をセットする
            foreach ($displayItemList as $itemKey => $itemValue) {
                $items[$itemKey] = $this->itemMapping($itemKey, $apply);
            }
            $items['id'] = $apply->id;
            $items['created_at'] = $apply->created_at->format('Y/m/d H:i');

            $array[] = $items;
        }
        return $array;
    }

    /**
     * @param string $itemKey
     * @param CommonApply $apply
     * @return array|string|string[]|void
     */
    private function itemMapping(string $itemKey, CommonApply $apply)
    {
        switch ($itemKey) {
            case 'name':
                return [
                    $apply->f_name . $apply->l_name,
                    $apply->f_read . $apply->l_read,
                ];
            case 'sex':
                if (empty($apply->sex)) {
                    return '不明';
                } else {
                    return Common::SEX_LIST[$apply->sex];
                }
            case 'birthday':
                if (empty($apply->birthday)) {
                    return '不明';
                } else {
                    $birthday = new Carbon($apply->birthday);
                    return $birthday->format('Y年m月d日');
                }
            case 'age':
                if (empty($apply->age)) {
                    return '不明';
                } else {
                    return $apply->age . '歳';
                }
            case 'tel':
                return $apply->tel;
            case 'email':
                return $apply->email;
            case 'comment':
                return $apply->comment;
            case 'img_pass':
                $directory = CommonApplyConst::IMG_DIR[$this->applyType];
                return asset("storage/$directory/resize/" . $apply->img_pass);
            case 'address':
                return [
                    $apply->zip21 . '-' . $apply->zip22,
                    $apply->pref21 . ' ' . $apply->address21,
                    $apply->street21,
                ];
            case 'choice_1':
                return $apply->choice_1;
            default:
                dd('不正です。' . $itemKey);
        }
    }

    /**
     * @param array $displayItemList
     * @return array|string[]
     */
    private function makeCSVHeader(array $displayItemList): array
    {
        if (empty($displayItemList)) {
            return [];
        }

        $header = [];
        do {
            // 配列の最初のキー
            $firstKey = array_key_first($displayItemList);

            switch ($firstKey) {
                case 'name':
                    $header[] = '名前';
                    $header[] = 'ナマエ';
                    break;
                case 'address':
                    $header[] = '郵便番号';
                    $header[] = '住所';
                    break;
                default:
                    $header[] = $displayItemList[$firstKey];
            }

            // 配列から削除する
            unset($displayItemList[$firstKey]);

            // 配列が空になるまでループ
        } while (!empty($displayItemList));

        return array_merge(['ID', '申込日'], $header);
    }

    /**
     * @param array $displayItemList
     * @param CommonApply $apply
     * @return array
     */
    private function makeCSVBody(array $displayItemList, CommonApply $apply): array
    {
        if (empty($displayItemList)) {
            return [];
        }

        $body = [];
        $body[] = $apply->id;
        $body[] = $apply->created_at->format('Y/m/d H:i');

        do {
            // 配列の最初のキー
            $firstKey = array_key_first($displayItemList);

            switch ($firstKey) {
                case 'name':
                    $body[] = $apply->f_name . $apply->l_name;
                    $body[] = $apply->f_read . $apply->l_read;
                    break;
                case 'address':
                    $body[] = $apply->zip21 . '-' . $apply->zip22;
                    $body[] = $apply->pref21 . ' ' . $apply->address21 . $apply->street21;
                    break;
                case 'sex':
                    if (empty($apply->sex)) {
                        $body[] = '不明';
                        break;
                    }
                    $body[] = Common::SEX_LIST[$apply->sex];
                    break;
                case 'birthday':
                    if (empty($apply->birthday)) {
                        $body[] = '不明';
                    } else {
                        $birthday = new Carbon($apply->birthday);
                        $body[] = $birthday->format('Y年m月d日');
                    }
                    break;
                case 'age':
                    if (empty($apply->age)) {
                        $body[] = '不明';
                        break;
                    }
                    $body[] = $apply->age . '歳';
                    break;
                case 'img_pass':
                    $directory = CommonApplyConst::IMG_DIR[$this->applyType];
                    $body[] = asset("storage/$directory/" . $apply->img_pass);
                    break;
                default:
                    $body[] = $apply->$firstKey;
            }

            // 配列から削除する
            unset($displayItemList[$firstKey]);

            // 配列が空になるまでループ
        } while (!empty($displayItemList));

        return $body;
    }

    /**
     * @param string $applyTitle
     * @return string
     */
    private function makeCSVName(string $applyTitle): string
    {
        // スベースをアンダースコアに変換
        $applyTitle = str_replace(' ', '_', $applyTitle);
        return $applyTitle . '_' . date('YmdHis') . '.csv';
    }
}
