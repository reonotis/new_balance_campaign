<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string f_name
 * @property string l_name
 * @property string f_read
 * @property string l_read
 * @property int sex
 * @property string zip21
 * @property string zip22
 * @property string pref21
 * @property string address21
 * @property string street21
 * @property string tel
 * @property string email
 * @property string comment
 * @property string comment2
 */
class HarajukuAnniversaryRequest extends FormRequest
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
        return [
            'f_name' => 'required',
            'l_name' => 'required',
            'f_read' => 'required|regex:/^[ァ-ヶー]+$/u',
            'l_read' => 'required|regex:/^[ァ-ヶー]+$/u',
            'sex' => 'required',
            'tel' => 'required|regex:/^[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}$/',
            'email' => 'required|regex:/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/|confirmed',
            'email_confirmation' => 'required|regex:/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/',
            'zip21' => 'required|size:3',
            'zip22' => 'required|size:4',
            'pref21' => 'required',
            'address21' => 'required',
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
            'age.required' => 'ご年齢を入力してください。',
            'tel.regex' => '電話番号は市外局番から-(ハイフン)を含めて入力してください。',
            'email.regex' => 'メールアドレスを正しく入力してください。',
            'image.required' => 'レシート画像が添付されていません。',
            'reason_applying.required' => '返品動機をご入力ください。',
            'choice_1.required' => 'どこでイベントについて知りましたかを選択してください。',
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
        ];
    }
}

