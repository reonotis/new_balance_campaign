<?php

namespace App\Http\Requests;

use App\Consts\CommonApplyConst;
use App\Service\CommonApplyService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string f_name
 * @property string l_name
 * @property string f_read
 * @property string l_read
 * @property string zip21
 * @property string zip22
 * @property string pref21
 * @property string address21
 * @property string street21
 * @property string tel
 * @property string email
 */
class VersityJacketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $apply_service = new CommonApplyService(CommonApplyConst::APPLY_TYPE_VERSITY_JACKET, 1);
        $record = $apply_service->getExistRecords();
        $exist_choice_1 = $record->pluck('choice_1')->toArray();

        return [
            'choice_1' => ['required', Rule::notIn($exist_choice_1)],
            'f_name' => 'required',
            'l_name' => 'required',
            'f_read' => 'required|regex:/^[ァ-ヶー]+$/u',
            'l_read' => 'required|regex:/^[ァ-ヶー]+$/u',
            'email' => 'required|regex:/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/|confirmed',
            'email_confirmation' => 'required|regex:/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/',
            'tel' => 'required|regex:/^[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}$/',
        ];
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
            'tel.regex' => '電話番号は市外局番から-(ハイフン)を含めて入力してください。',
            'email.regex' => 'メールアドレスを正しく入力してください。',
        ];
    }

    /**
     * 項目名を定義する
     * @return array string[]
     */
    public function attributes(): array
    {
        return [
            'comment' => '質問事項',
            'choice_1' => 'ご希望日時',
        ];
    }
}

