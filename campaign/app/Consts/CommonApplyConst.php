<?php

namespace App\Consts;

class CommonApplyConst
{
    public const APPLY_TYPE_GO_FUN = 2;
    public const APPLY_TYPE_TRY_ON_2023_AUTUMN = 3;
    public const APPLY_TYPE_TRY_ON_2023_FRESH_FORM_1080_V13 = 4;
    public const APPLY_TYPE_SPECIAL_CHANCE_CAMPAIGN = 5;

    /**
     * 申込タイトル
     */
    public const APPLY_TITLE_LIST = [
        self::APPLY_TYPE_GO_FUN => 'GO FUN キャンペーン',
        self::APPLY_TYPE_TRY_ON_2023_AUTUMN => 'Running TRY ON 2023 Autumnキャンペーン',
        self::APPLY_TYPE_TRY_ON_2023_FRESH_FORM_1080_V13 => 'Fresh Foam X 1080v13 TRY ON Campaign',
        self::APPLY_TYPE_SPECIAL_CHANCE_CAMPAIGN => 'スペシャルチャンスキャンペーン',
    ];

    /**
     * 申込サイトのルーティングネーム
     */
    public const APPLY_URL_NAME = [
        self::APPLY_TYPE_GO_FUN => 'go_fun.index',
        self::APPLY_TYPE_TRY_ON_2023_AUTUMN => 'try-on-2023-autumn.index',
        self::APPLY_TYPE_TRY_ON_2023_FRESH_FORM_1080_V13 => 'try-on-2023-fresh-form-1080-v13.index',
        self::APPLY_TYPE_SPECIAL_CHANCE_CAMPAIGN => 'special-chance-campaign.index',
    ];

    /**
     * 申込可能期間
     */
    public const APPLY_TYPE_DURATION = [
        self::APPLY_TYPE_GO_FUN => [
            'start_date_time' => '2023-08-07 00:00:00',
            'end_date_time' => '2023-10-13 23:59:59',
        ],
        self::APPLY_TYPE_TRY_ON_2023_AUTUMN => [
            'start_date_time' => '2023-08-07 00:00:00',
            'end_date_time' => '2023-10-15 23:59:59',
        ],
        self::APPLY_TYPE_TRY_ON_2023_FRESH_FORM_1080_V13 => [
            'start_date_time' => '2023-10-03 00:00:00',
            'end_date_time' => '2023-12-10 23:59:59',
        ],
        self::APPLY_TYPE_SPECIAL_CHANCE_CAMPAIGN => [
            'start_date_time' => '2023-11-01 00:00:00',
//            'start_date_time' => '2023-11-10 00:00:00',
            'end_date_time' => '2023-12-11 23:59:59',
        ],
    ];

    /**
     * 申込フォームで登録させる項目
     */
    public const APPLY_TYPE_INSERT_COLUMN = [
        self::APPLY_TYPE_GO_FUN => [
            'f_name',
            'l_name',
            'f_read',
            'l_read',
            'sex',
            'birthday',
            'zip21',
            'zip22',
            'pref21',
            'address21',
            'street21',
            'tel',
            'email',
            'img_pass',
        ],
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
        self::APPLY_TYPE_TRY_ON_2023_FRESH_FORM_1080_V13 => [
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
        self::APPLY_TYPE_SPECIAL_CHANCE_CAMPAIGN => [
            'f_name',
            'l_name',
            'f_read',
            'l_read',
            'sex',
            'birthday',
            'zip21',
            'zip22',
            'pref21',
            'address21',
            'street21',
            'email',
            'img_pass',
        ],
    ];

    /**
     * 管理画面に表示させる項目と並び順
     * ※ [ID]と[申込日時]は共通で先頭に表示させる為、記載不要
     */
    public const APPLY_TYPE_DISPLAY_COLUMN = [
        self::APPLY_TYPE_GO_FUN => [
            'name' => '名前',
            'sex' => '性別',
            'birthday' => '生年月日',
            'address' => '住所',
            'tel' => '電話番号',
            'email' => 'メールアドレス',
            'img_pass' => 'レシート画像',
        ],
        self::APPLY_TYPE_TRY_ON_2023_AUTUMN => [
            'name' => '名前',
            'sex' => '性別',
            'age' => '年齢',
            'address' => '住所',
            'tel' => '電話番号',
            'email' => 'メールアドレス',
            'comment' => '返却同期',
            'img_pass' => 'レシート画像',
        ],
        self::APPLY_TYPE_TRY_ON_2023_FRESH_FORM_1080_V13 => [
            'name' => '名前',
            'sex' => '性別',
            'age' => '年齢',
            'address' => '住所',
            'tel' => '電話番号',
            'email' => 'メールアドレス',
            'comment' => '返却同期',
            'img_pass' => 'レシート画像',
        ],
        self::APPLY_TYPE_SPECIAL_CHANCE_CAMPAIGN => [
            'name' => '名前',
            'birthday' => '生年月日',
            'sex' => '性別',
            'address' => '住所',
            'email' => 'メールアドレス',
            'img_pass' => 'レシート画像',
        ],
    ];

    /**
     * 画像を格納するディレクトリ名
     */
    public const IMG_DIR = [
        self::APPLY_TYPE_GO_FUN => 'go_fun',
        self::APPLY_TYPE_TRY_ON_2023_AUTUMN => 'try-on-2023-autumn',
        self::APPLY_TYPE_TRY_ON_2023_FRESH_FORM_1080_V13 => 'try-on-2023-fresh-form-1080-v13',
        self::APPLY_TYPE_SPECIAL_CHANCE_CAMPAIGN => 'special-chance-campaign',
    ];
}
