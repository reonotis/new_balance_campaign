<?php

namespace App\Consts;

class CommonApplyConst
{
    public const APPLY_TYPE_TRY_ON_2023_AUTUMN = 4;

    /**
     * 申込可能期間
     */
    public const APPLY_TYPE_DURATION = [
        self::APPLY_TYPE_TRY_ON_2023_AUTUMN => [
            'start_date_time' => '2023-08-07 00:00:00',
            'end_date_time' => '2023-12-25 23:59:59',
        ],
    ];

    /**
     * 申込フォームで登録させる項目
     */
    public const APPLY_TYPE_INSERT_COLUMN = [
        self::APPLY_TYPE_TRY_ON_2023_AUTUMN => [
            'f_name',
            'l_name',
            'f_read',
            'l_read',
            'sex',
            'age',
            'zip21',
            'zip22',
            'pref21',
            'address21',
            'street21',
            'tel',
            'email',
            'comment',
            'img_pass',
        ],
    ];

}
