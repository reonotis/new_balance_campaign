<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * @property string f_name
 * @property string l_name
 * @property string f_read
 * @property string l_read
 * @property int sex
 * @property string email
 * @property int preferred_event
 */
class TokyoRokutaiFesRequest extends FormRequest
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
            'email' => 'required|regex:/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/|confirmed',
            'email_confirmation' => 'required|regex:/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/',
            'preferred_event' => 'required',
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
            'email.regex' => 'メールアドレスを正しく入力してください。',
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
            'preferred_event' => '希望種目',
            'sex' => '性別',
        ];
    }
}

