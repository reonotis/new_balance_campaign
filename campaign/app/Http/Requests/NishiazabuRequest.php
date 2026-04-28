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
class NishiazabuRequest extends FormRequest
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

    protected function prepareForValidation(): void
    {
        $this->merge([
            'l_read' => '',
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'f_name' => 'required',
            'l_name' => 'required',
            'f_read' => 'required|regex:/^[ァ-ヶー]+$/u',
            'nbid' => 'nullable',
            'sex' => 'required|in:1,2,3',
            'age' => 'required',

            'zip21' => 'required|size:3',
            'zip22' => 'required|size:4',
            'pref21' => 'required',
            'address21' => 'required',
            'street21' => 'required',

            'tel' => 'required|regex:/^[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}$/',
            'email' => 'required|regex:/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/|confirmed',
            'email_confirmation' => 'required|regex:/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/',

            'comment' => 'nullable',
            'comment2' => 'nullable',
            'comment3' => 'nullable',
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
            'comment' => 'ご自身が最も気に入っているグレーシューズ',
            'comment2' => '初めてご購入いただいたニューバランスのシューズ',
            'comment3' => 'これから履いてみたい、購入を考えているシューズ/アパレル',
        ];
    }
}

