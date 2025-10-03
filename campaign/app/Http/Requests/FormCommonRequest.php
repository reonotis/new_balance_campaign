<?php

namespace app\Http\Requests;

use App\Consts\RunClubTokyoConstConst;
use app\Models\FormItem;
use App\Models\FormSetting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string f_name
 * @property string l_name
 * @property string f_read
 * @property string l_read
 * @property string email
 * @property int sex
 * @property int goal_time
 * @property int shoes_size
 */
class FormCommonRequest extends FormRequest
{
    private \Illuminate\Database\Eloquent\Collection  $form_item;


    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $form_setting_id = $this->form_setting_id;

        $form_setting = FormSetting::find($form_setting_id);
        if (empty($form_setting)) {
            abort(404);
        }
        $this->form_item = $form_setting->formItem;

//        dd($this->form_item);
        $rules = [];
        foreach ($form_setting->formItem as $form_item) {
            switch ($form_item->type_no){
                case FormItem::ITEM_TYPE_NAME:
                    $rules['f_name']= ['required'];
                    $rules['l_name']= ['required'];
                    break;
                case FormItem::ITEM_TYPE_YOMI:
                    $rules['f_read']= ['required', 'regex:/^[ァ-ヶー]+$/u'];
                    $rules['l_read']= ['required', 'regex:/^[ァ-ヶー]+$/u'];
                    break;
                case FormItem::ITEM_TYPE_SEX:
                    $rules['sex']= ['required'];
                    break;
                case FormItem::ITEM_TYPE_AGE:
                    $rules['age']= ['required'];
                    break;
                case FormItem::ITEM_TYPE_ADDRESS:
                    $rules['zip21']= ['required', 'size:3'];
                    $rules['zip22']= ['required', 'size:4'];
                    $rules['pref21']= ['required'];
                    $rules['address21']= ['required'];
                    $rules['street21']= ['required'];
                    break;
                case FormItem::ITEM_TYPE_TEL:
                    $rules['tel']= ['required', 'regex:/^[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}$/'];
                    break;
                case FormItem::ITEM_TYPE_EMAIL:
                    $rules['email']= ['required', 'regex:/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', 'confirmed'];
                    $rules['email_confirmation']= ['required', 'regex:/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/'];
                    break;
                case FormItem::ITEM_TYPE_RECEIPT_IMAGE:
                    $rules['image'] = ['required'];
                    break;
                case FormItem::ITEM_TYPE_COMMENT_1:
                    $rules['comment_1'] = ['required'];
                    break;
                case FormItem::ITEM_TYPE_COMMENT_2:
                    $rules['comment_2'] = ['required'];
                    break;
                case FormItem::ITEM_TYPE_COMMENT_3:
                    $rules['comment_3'] = ['required'];
                    break;
                default:
            }
        }

        return $rules;
    }

    /**
     * エラーメッセージをカスタマイズする
     * @return array string[]
     */
    public function messages(): array
    {
        return [
            'f_read.regex' => 'ミョウジは全角カナで入力してください。',
            'l_read.regex' => 'ナマエは全角カナで入力してください。',
            'age.required' => 'ご年齢を入力してください。',
            'tel.regex' => '電話番号は市外局番から-(ハイフン)を含めて入力してください。',
        ];
    }

    /**
     * 項目名を定義する
     * @return array string[]
     */
    public function attributes(): array
    {
        return [
            'sex' => '性別',
            'comment_1' => $this->form_item->where('type_no', FormItem::ITEM_TYPE_COMMENT_1)->first()->comment_title,
            'comment_2' => $this->form_item->where('type_no', FormItem::ITEM_TYPE_COMMENT_2)->first()->comment_title ?? '',
            'comment_3' => $this->form_item->where('type_no', FormItem::ITEM_TYPE_COMMENT_3)->first()->comment_title ?? '',
        ];
    }
}

