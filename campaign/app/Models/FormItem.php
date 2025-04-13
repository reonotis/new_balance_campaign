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

    protected $table = 'form_item';
    protected $casts = [
        'created_at' => 'datetime',
    ];

    const ITEM_TYPE_NAME = 1;
    const ITEM_TYPE_YOMI = 2;
    const ITEM_TYPE_SEX = 3;
    const ITEM_TYPE_AGE = 4;
    const ITEM_TYPE_ADDRESS = 5;
    const ITEM_TYPE_TEL = 6;
    const ITEM_TYPE_EMAIL = 7;
    const ITEM_TYPE_LIST = [
        self::ITEM_TYPE_NAME => 'お名前',
        self::ITEM_TYPE_YOMI => 'ヨミ',
        self::ITEM_TYPE_SEX => '性別',
        self::ITEM_TYPE_AGE => '年齢',
        self::ITEM_TYPE_ADDRESS => '住所',
    ];


}
