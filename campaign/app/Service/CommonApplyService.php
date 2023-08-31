<?php

namespace App\Service;

use App\Consts\CommonApplyConst;
use App\Models\CommonApply;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class CommonApplyService
{
    protected int $applyType;
    protected string $startDateTime = '';
    protected string $endDateTime = '';
    protected string $durationMessage = '';

    function __construct(int $applyType)
    {
        $this->applyType = $applyType;
        $this->startDateTime = CommonApplyConst::APPLY_TYPE_DURATION[$applyType]['start_date_time'];
        $this->endDateTime = CommonApplyConst::APPLY_TYPE_DURATION[$applyType]['end_date_time'];
        $this->checkApplicationDuration();
    }

    /**
     * 申込期間か判定し、期間外の場合はエラーメッセージを格納する
     * @return bool
     */
    public function checkApplicationDuration(): bool
    {
        $now = date('Y-m-d H:i:s');
        if ($now <= $this->startDateTime) {
            $this->durationMessage = 'まだ開始されていません';
            return false;
        }

        if ($now >= $this->endDateTime) {
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
}
