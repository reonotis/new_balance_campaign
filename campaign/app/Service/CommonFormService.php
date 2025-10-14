<?php

namespace app\Service;

use App\Consts\CommonApplyConst;
use app\Http\Requests\FormCommonRequest;
use App\Models\Application;
use App\Models\FormItem;
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
        $application = new Application;
        $application->form_setting_id = $form_setting->id;

        foreach ($form_setting->formItem as $form_item) {
            switch($form_item->type_no){
                case(1):
                    $application->f_name = $request['f_name'];
                    $application->l_name = $request['l_name'];
                    break;
                case(2):
                    $application->f_read = $request['f_read'];
                    $application->l_read = $request['l_read'];
                    break;
                case(3):
                    $application->sex = $request['sex'];
                    break;
                case(4):
                    $application->age = $request['age'];
                    break;
                case(5):
                    $application->zip21 = $request['zip21'];
                    $application->zip22 = $request['zip22'];
                    $application->pref21 = $request['pref21'];
                    $application->address21 = $request['address21'];
                    $application->street21 = $request['street21'];
                    break;
                case(6):
                    $application->tel = $request['tel'];
                    break;
                case(7):
                    $application->email = $request['email'];
                    break;
                case(FormItem::ITEM_TYPE_CHOICE_1):
                    $application->choice_1 = $request['choice_11'];
                    break;
                case(FormItem::ITEM_TYPE_CHOICE_2):
                    $application->choice_2 = $request['choice_12'];
                    break;
                case(FormItem::ITEM_TYPE_CHOICE_3):
                    $application->choice_3 = $request['choice_13'];
                    break;
                case(FormItem::ITEM_TYPE_CHOICE_4):
                    $application->choice_4 = $request['choice_14'];
                    break;
                case(FormItem::ITEM_TYPE_NBID):
                    $application->my_NBID = $request['nbid'];
                    break;
                case(FormItem::ITEM_TYPE_RECEIPT_IMAGE):
                    $application->img_pass = $request['img_pass'];
                    break;
                case(FormItem::ITEM_TYPE_COMMENT_1):
                    $application->comment = $request['comment_41'];
                    break;
                case(FormItem::ITEM_TYPE_COMMENT_2):
                    $application->comment2 = $request['comment_42'];
                    break;
                case(FormItem::ITEM_TYPE_COMMENT_3):
                    $application->comment3 = $request['comment_43'];
                    break;
                default:
            }
        }

        return $application->save();
    }


}
