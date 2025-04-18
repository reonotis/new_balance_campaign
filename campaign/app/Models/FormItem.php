<?php

namespace app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $form_setting_id
 * @property int $type_no
 * @property int $sort
 * @property int $require_flg
 * @property \Illuminate\Support\Carbon $created_at
 */
class FormItem extends Model
{
    use HasFactory;
    const ITEM_TYPE_NAME = 1;
    const ITEM_TYPE_YOMI = 2;
    const ITEM_TYPE_SEX = 3;
    const ITEM_TYPE_AGE = 4;
    const ITEM_TYPE_ADDRESS = 5;
    const ITEM_TYPE_TEL = 6;
    const ITEM_TYPE_EMAIL = 7;
    const ITEM_TYPE_CHOICE_1 = 11;
    const ITEM_TYPE_CHOICE_2 = 12;
    const ITEM_TYPE_CHOICE_3 = 13;
    const ITEM_TYPE_RECEIPT_IMAGE = 31;
    const ITEM_TYPE_COMMENT_1 = 41;
    const ITEM_TYPE_COMMENT_2 = 42;
    const ITEM_TYPE_COMMENT_3 = 43;
    const ITEM_TYPE_LIST = [
        self::ITEM_TYPE_NAME => 'お名前',
        self::ITEM_TYPE_YOMI => 'ヨミ',
        self::ITEM_TYPE_SEX => '性別',
        self::ITEM_TYPE_AGE => '年齢',
        self::ITEM_TYPE_ADDRESS => '住所',
        self::ITEM_TYPE_TEL => '電話番号',
        self::ITEM_TYPE_EMAIL => 'メールアドレス',
        self::ITEM_TYPE_CHOICE_1 => '選択肢1',
        self::ITEM_TYPE_CHOICE_2 => '選択肢2',
        self::ITEM_TYPE_CHOICE_3 => '選択肢3',
        self::ITEM_TYPE_RECEIPT_IMAGE => 'レシート画像',
        self::ITEM_TYPE_COMMENT_1 => 'コメント1',
        self::ITEM_TYPE_COMMENT_2 => 'コメント2',
        self::ITEM_TYPE_COMMENT_3 => 'コメント3',
    ];

    protected $table = 'form_item';

    protected $casts = [
        'choice_data' => 'array',
        'created_at' => 'datetime',
    ];

    protected $fillable = [
        'form_setting_id',
        'type_no',
        'sort',
        'require_flg',
        'choice_data',
        'comment_title',
    ];

}
