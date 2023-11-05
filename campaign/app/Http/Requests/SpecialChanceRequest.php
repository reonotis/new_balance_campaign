<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * @property string f_name
 * @property string l_name
 * @property string f_read
 * @property string l_read
 * @property integer sex
 * @property string birth_year
 * @property string birth_month
 * @property string birth_day
 * @property string birthday
 * @property string zip21
 * @property string zip22
 * @property string pref21
 * @property string address21
 * @property string street21
 * @property string email
 * @property UploadedFile image
 */
class SpecialChanceRequest extends FormRequest
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
            'birth_year' => 'required|numeric|max:2023',
            'birth_month' => 'required|numeric|max:12',
            'birth_day' => 'required|numeric|max:31',
            'birthday' => 'nullable|date',
            'zip21' => 'required|size:3',
            'zip22' => 'required|size:4',
            'pref21' => 'required',
            'address21' => 'required',
            'street21' => 'required',
            'email' => 'required|regex:/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/|confirmed',
            'email_confirmation' => 'required|regex:/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/',
            'image' => 'required',
        ];
    }

    /**
     * エラーメッセージをカスタマイズする
     * @return array
     */
    public function messages(): array
    {
        return [
            'f_read.regex' => 'ミョウジは全角カナで入力してください。',
            'l_read.regex' => 'ナマエは全角カナで入力してください。',
            'sex.required' => '性別を選択してください。',
            'tel.regex' => '電話番号は市外局番から-(ハイフン)を含めて入力してください。',
            'email.regex' => 'メールアドレスを正しく入力してください。',
            'image.required' => 'レシート画像が添付されていません。',
        ];
    }

    public function attributes()
    {
        return [
            'birth_year' => '誕生年',
            'birth_month' => '誕生月',
            'birth_day' => '誕生日',
            'birthday' => '生年月日',
        ];
    }

    public function validationData()
    {
        $data = parent::validationData();

        $data['birthday'] = null;

        // 年月日すべて揃っている場合にbirthdayに値を代入する
        if ($data['birth_year'] && $data['birth_month'] && $data['birth_day']) {
            $data['birthday'] = $data['birth_year'] . '-' . $data['birth_month'] . '-' . $data['birth_day'];
        }

        return $data;
    }
}

