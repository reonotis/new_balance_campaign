<?php

namespace App\Http\Requests;

use App\Consts\RunClubTokyoConstConst;
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
class RunClubTokyoRequest extends FormRequest
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
            'sex' => ['required'],
            'email' => 'required|regex:/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/|confirmed',
            'email_confirmation' => 'required|regex:/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/',
            // 'goal_time' => ['required', Rule::in(array_keys(RunClubTokyoConstConst::GOAL_TIME))],
            // 'shoes_size' => ['required', Rule::in(array_keys(RunClubTokyoConstConst::SHOES_SIZE))],
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
            'goal_time' => '目標タイム',
            'shoes_size' => 'シューズサイズ',
        ];
    }
}

