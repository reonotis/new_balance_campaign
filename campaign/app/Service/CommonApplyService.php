<?php

namespace App\Service;

use App\Consts\CommonApplyConst;
use App\Models\CommonApply;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class CommonApplyService
{
    protected int $applyType;
    protected string $durationMessage = '';

    function __construct(int $apply_type, int $number = null)
    {
        $this->applyType = $apply_type;
        $this->number = $number;
    }

    /**
     * 申込期間か判定し、期間外の場合はエラーメッセージを格納する
     * @return bool
     */
    public function checkApplicationDuration(): bool
    {
        if (!is_null($this->number)) {
            $start_date_time_str = CommonApplyConst::APPLY_TYPE_DURATION[$this->applyType][$this->number]['start_date_time'];
            $end_date_time_str = CommonApplyConst::APPLY_TYPE_DURATION[$this->applyType][$this->number]['end_date_time'];
        } else {
            $start_date_time_str = CommonApplyConst::APPLY_TYPE_DURATION[$this->applyType]['start_date_time'];
            $end_date_time_str = CommonApplyConst::APPLY_TYPE_DURATION[$this->applyType]['end_date_time'];
        }

        $now = Carbon::now();
        $start_date_time = new Carbon($start_date_time_str);
        if ($now <= $start_date_time) {
            $this->durationMessage = $start_date_time->isoFormat('M月D(ddd) H:mm') . 'より応募が可能となります。';
            return false;
        }

        if ($now >=  new Carbon($end_date_time_str)) {
            $this->durationMessage = '募集期間は終了しました';
            return false;
        }

        return true;
    }

    /**
     * 申込期間外にセットされるメッセージを返却する
     * @return string
     */
    public function getDurationMessage(): string
    {
        return $this->durationMessage;
    }

    /**
     * 登録処理を行う
     * @param FormRequest $request
     * @param array $originalColumn
     * @return bool
     */
    public function insertCommonApply(FormRequest $request, array $originalColumn = []): bool
    {
        $commonApply = new CommonApply;
        $commonApply->apply_type = $this->applyType;

        foreach (CommonApplyConst::APPLY_TYPE_INSERT_COLUMN[$this->applyType] as $column) {
            // 入力項目が設定されていれば利用する
            if (isset($originalColumn[$column])) {
                $commonApply->$column = $originalColumn[$column];
                continue;
            }

            // 入力項目の設定がなければリクエストの値を利用する
            if (isset($request->$column)) {
                $commonApply->$column = $request->$column;
                continue;
            }

            // 必要項目が渡されていない場合
            Log::warning('入力項目が渡されませんでした。カラム名:' . $column);
        }
        return $commonApply->save();
    }

    /**
     * @param int $applyType
     */
    public function getByApplyType(int $applyType)
    {
        return CommonApply::where('delete_flag', 0)
            ->where('apply_type', $applyType)->get();
    }

    /**
     * @param int $applyType
     */
    public function getByApplyTypeWithPaginate(int $applyType, int $paginate)
    {
        return CommonApply::where('delete_flag', 0)
            ->where('apply_type', $applyType)->paginate($paginate);
    }

    /**
     */
    public function getLotteryResultEmailCount()
    {
        return CommonApply::where('sent_lottery_result_email_flg', 0)
            ->where('send_lottery_result_email_flg', 1)
            ->where('delete_flag', 0)
            ->where('apply_type', $this->applyType)
            ->count();
    }

    /**
     * @return Collection<int, CommonApply>
     */
    public function getLotteryResultEmailList(): Collection
    {
        return CommonApply::where('sent_lottery_result_email_flg', 0)
            ->where('send_lottery_result_email_flg', 1)
            ->where('delete_flag', 0)
            ->where('apply_type', $this->applyType)
            ->get();
    }

    /**
     * @param int $commonApplyId
     * @param int $val
     */
    public function updateLotteryResultEmailById(int $commonApplyId, int $val)
    {
        $commonApply = CommonApply::where('sent_lottery_result_email_flg', '0')
            ->where('delete_flag', 0)
            ->where('apply_type', $this->applyType)
            ->find($commonApplyId);

        if (is_null($commonApply)) {
            return [
                'error' => [
                    'code' => 404,
                    'msg' => '該当のデータが存在しません',
                ],
            ];
        }

        $commonApply->send_lottery_result_email_flg = $val;
        $commonApply->save();

        $count = CommonApply::where('sent_lottery_result_email_flg', '0')
            ->where('send_lottery_result_email_flg', '1')
            ->where('delete_flag', 0)
            ->where('apply_type', $this->applyType)
            ->count();
        return [
            'success' => [
                'count' => $count,
            ],
        ];
    }

}
