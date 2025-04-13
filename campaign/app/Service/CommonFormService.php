<?php

namespace app\Service;

use App\Consts\CommonApplyConst;
use app\Http\Requests\FormCommonRequest;
use App\Models\CommonApply;
use App\Models\FormSetting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class CommonFormService
{
    protected int $applyType;
    protected string $durationMessage = '';

    function __construct()
    {
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
     * @param array $request
     * @param FormSetting $form_setting
     * @return bool
     */
    public function insertCommonApply(array $request, FormSetting $form_setting): bool
    {
        $common_apply = new CommonApply;
        $common_apply->apply_type = $form_setting->apply_type;

        foreach ($form_setting->formItem as $form_item) {
            switch($form_item->type_no){
                case(1):
                    $common_apply->f_name = $request['f_name'];
                    $common_apply->l_name = $request['l_name'];
                    break;
                case(2):
                    $common_apply->f_read = $request['f_read'];
                    $common_apply->l_read = $request['l_read'];
                    break;
                case(3):
                    $common_apply->sex = $request['sex'];
                    break;
                case(4):
                    $common_apply->age = $request['age'];
                    break;
                case(5):
                    $common_apply->zip21 = $request['zip21'];
                    $common_apply->zip22 = $request['zip22'];
                    $common_apply->pref21 = $request['pref21'];
                    $common_apply->address21 = $request['address21'];
                    $common_apply->street21 = $request['street21'];
                    break;
                case(6):
                    $common_apply->tel = $request['tel'];
                    break;
                case(7):
                    $common_apply->email = $request['email'];
                    break;
                default:
            }
        }

        return $common_apply->save();
    }


}
