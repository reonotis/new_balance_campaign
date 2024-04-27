<?php

namespace App\Consts;

class CommonApplyConst
{
    public const APPLY_TYPE_GO_FUN = 2;
    public const APPLY_TYPE_TRY_ON_2023_AUTUMN = 3;
    public const APPLY_TYPE_TRY_ON_2023_FRESH_FORM_1080_V13 = 4;
    public const APPLY_TYPE_SPECIAL_CHANCE_CAMPAIGN = 5;
    public const APPLY_TYPE_KICHIJOJI_SHOPPING_NIGHT = 6;
    public const APPLY_TYPE_CELEBRATION_SEAT = 7;
    public const APPLY_TYPE_TRY_ON_2024 = 8;
    public const APPLY_TYPE_KICHIJOJI_GREY_DAYS_EXCLUSIVE = 9;
    public const APPLY_TYPE_KICHIJOJI_GREY_DAYS_5K_RUNNING = 10;

    /**
     * 申込タイトル
     */
    public const APPLY_TITLE_LIST = [
        self::APPLY_TYPE_GO_FUN => 'GO FUN キャンペーン',
        self::APPLY_TYPE_TRY_ON_2023_AUTUMN => 'Running TRY ON 2023 Autumnキャンペーン',
        self::APPLY_TYPE_TRY_ON_2023_FRESH_FORM_1080_V13 => 'Fresh Foam X 1080v13 TRY ON Campaign',
        self::APPLY_TYPE_SPECIAL_CHANCE_CAMPAIGN => 'スペシャルチャンスキャンペーン',
        self::APPLY_TYPE_KICHIJOJI_SHOPPING_NIGHT => 'Kichijoji Special Shopping Night',
        self::APPLY_TYPE_CELEBRATION_SEAT => 'Run your way. Celebration Seat.',
        self::APPLY_TYPE_TRY_ON_2024 => 'Running TRY ON 2024',
        self::APPLY_TYPE_KICHIJOJI_GREY_DAYS_EXCLUSIVE => 'Grey Days Exclusive Event',
        self::APPLY_TYPE_KICHIJOJI_GREY_DAYS_5K_RUNNING => 'Grey Days 5K Running Event',
    ];

    /**
     * 申込サイトのルーティングネーム
     */
    public const APPLY_URL_NAME = [
        self::APPLY_TYPE_GO_FUN => 'go_fun.index',
        self::APPLY_TYPE_TRY_ON_2023_AUTUMN => 'try-on-2023-autumn.index',
        self::APPLY_TYPE_TRY_ON_2023_FRESH_FORM_1080_V13 => 'try-on-2023-fresh-form-1080-v13.index',
        self::APPLY_TYPE_SPECIAL_CHANCE_CAMPAIGN => 'special-chance-campaign.index',
        self::APPLY_TYPE_KICHIJOJI_SHOPPING_NIGHT => 'kichijoji-shopping-night.index',
        self::APPLY_TYPE_CELEBRATION_SEAT => 'celebration-seat.index',
        self::APPLY_TYPE_TRY_ON_2024 => 'try-on-2024.index',
        self::APPLY_TYPE_KICHIJOJI_GREY_DAYS_EXCLUSIVE => 'kichijoji-grey-days-exclusive.index',
        self::APPLY_TYPE_KICHIJOJI_GREY_DAYS_5K_RUNNING => 'kichijoji-grey-days-5k-running.index',
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
            'start_date_time' => '2023-11-10 00:00:00',
            'end_date_time' => '2023-12-11 23:59:59',
        ],
        self::APPLY_TYPE_KICHIJOJI_SHOPPING_NIGHT => [
            'start_date_time' => '2023-11-12 00:00:00',
            'end_date_time' => '2023-12-06 23:59:59',
        ],
        self::APPLY_TYPE_CELEBRATION_SEAT => [
            'start_date_time' => '2024-01-28 00:00:00',
            'end_date_time' => '2024-02-16 23:59:59',
        ],
        self::APPLY_TYPE_TRY_ON_2024 => [
            'start_date_time' => '2024-03-08 00:00:00', // 2024-03-11
            'end_date_time' => '2024-04-28 23:59:59',
        ],
        self::APPLY_TYPE_KICHIJOJI_GREY_DAYS_EXCLUSIVE => [
            'start_date_time' => '2024-04-27 00:00:00', // 2024-05-02
            'end_date_time' => '2024-05-08 23:59:59',
        ],
        self::APPLY_TYPE_KICHIJOJI_GREY_DAYS_5K_RUNNING => [
            'start_date_time' => '2024-05-02 00:00:00', // 2024-05-02
            'end_date_time' => '2024-05-08 23:59:59',
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
        self::APPLY_TYPE_KICHIJOJI_SHOPPING_NIGHT => [
            'f_name',
            'l_name',
            'f_read',
            'l_read',
            'zip21',
            'zip22',
            'pref21',
            'address21',
            'street21',
            'tel',
            'email',
        ],
        self::APPLY_TYPE_CELEBRATION_SEAT => [
            'f_name',
            'l_name',
            'f_read',
            'l_read',
            'sex',
            'zip21',
            'zip22',
            'pref21',
            'address21',
            'street21',
            'tel',
            'email',
            'img_pass',
            'choice_1',
        ],
        self::APPLY_TYPE_TRY_ON_2024 => [
            'f_name',
            'l_name',
            'f_read',
            'l_read',
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
        self::APPLY_TYPE_KICHIJOJI_GREY_DAYS_EXCLUSIVE => [
            'f_name',
            'l_name',
            'f_read',
            'l_read',
            'sex',
            'zip21',
            'zip22',
            'pref21',
            'address21',
            'street21',
            'tel',
            'email',
            'comment',
        ],
        self::APPLY_TYPE_KICHIJOJI_GREY_DAYS_5K_RUNNING => [
            'f_name',
            'l_name',
            'f_read',
            'l_read',
            'sex',
            'zip21',
            'zip22',
            'pref21',
            'address21',
            'street21',
            'tel',
            'email',
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
        self::APPLY_TYPE_KICHIJOJI_SHOPPING_NIGHT => [
            'name' => '名前',
            'address' => '住所',
            'tel' => '電話番号',
            'email' => 'メールアドレス',
        ],
        self::APPLY_TYPE_CELEBRATION_SEAT => [
            'name' => '名前',
            'address' => '住所',
            'tel' => '電話番号',
            'email' => 'メールアドレス',
            'img_pass' => 'レシート画像',
            'choice_1' => '出走',
        ],
        self::APPLY_TYPE_TRY_ON_2024 => [
            'name' => '名前',
            'age' => '年齢',
            'address' => '住所',
            'tel' => '電話番号',
            'email' => 'メールアドレス',
            'comment' => '返却同期',
            'img_pass' => 'レシート画像',
        ],
        self::APPLY_TYPE_KICHIJOJI_GREY_DAYS_EXCLUSIVE => [
            'name' => '名前',
            'sex' => '性別',
            'address' => '住所',
            'tel' => '電話番号',
            'email' => 'メールアドレス',
            'comment' => 'ニューバランス Greyに関する質問',
        ],
        self::APPLY_TYPE_KICHIJOJI_GREY_DAYS_5K_RUNNING => [
            'name' => '名前',
            'address' => '住所',
            'tel' => '電話番号',
            'email' => 'メールアドレス',
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
        self::APPLY_TYPE_CELEBRATION_SEAT => 'celebration-seat',
        self::APPLY_TYPE_TRY_ON_2024 => 'try-on-2024',
    ];

    /**
     * フォームによって扱いが変わるカラム
     */
    public const CHOICE_1 = [
        self::APPLY_TYPE_CELEBRATION_SEAT => [
            0 => 'しない',
            1 => 'する',
        ],
    ];
}
