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
    public const APPLY_TYPE_JUNIOR_FOOTBALL_442 = 11;
    public const APPLY_TYPE_AREA_302_RUNNING_CLUB = 12;
    public const APPLY_TYPE_TENJIN_RUNNERS_GATE = 13;
    public const APPLY_TYPE_TOKYO_LEGACY_HALF = 14;
    public const APPLY_TYPE_OSHMANS = 15;
    public const APPLY_TYPE_MINATO_RUNNERS_BASE = 16;
    public const APPLY_TYPE_TOKYO_ROKUTAI_FES = 17;

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
        self::APPLY_TYPE_JUNIOR_FOOTBALL_442 => 'Junior Football 442',
        self::APPLY_TYPE_AREA_302_RUNNING_CLUB => 'Area 302 running club',
        self::APPLY_TYPE_TENJIN_RUNNERS_GATE => 'Tenjin runners gate',
        self::APPLY_TYPE_TOKYO_LEGACY_HALF => 'New Balance Run Club Tokyo',
        self::APPLY_TYPE_OSHMANS => 'oshmansスペシャルランニングイベント',
        self::APPLY_TYPE_MINATO_RUNNERS_BASE => 'ゼビオ名古屋みなと',
        self::APPLY_TYPE_TOKYO_ROKUTAI_FES => '東京六体フェス2024',
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
        self::APPLY_TYPE_AREA_302_RUNNING_CLUB => 'area-302-running-club.index',
        self::APPLY_TYPE_TENJIN_RUNNERS_GATE => 'tenjin-runners-gate.index',
        self::APPLY_TYPE_TOKYO_LEGACY_HALF => 'run-club-tokyo.index',
        self::APPLY_TYPE_OSHMANS => 'oshmans.index',
        self::APPLY_TYPE_MINATO_RUNNERS_BASE => 'minato.index',
        self::APPLY_TYPE_TOKYO_ROKUTAI_FES => 'tokyo-rokutai-fes-2024.index',
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
            'start_date_time' => '2024-05-02 00:00:00', // 2024-05-02
            'end_date_time' => '2024-05-08 23:59:59',
        ],
        self::APPLY_TYPE_KICHIJOJI_GREY_DAYS_5K_RUNNING => [
            'start_date_time' => '2024-05-02 00:00:00', // 2024-05-02
            'end_date_time' => '2024-05-08 23:59:59',
        ],
        self::APPLY_TYPE_JUNIOR_FOOTBALL_442 => [
            'start_date_time' => '2024-07-06 00:00:00', // 2024-07-19
            'end_date_time' => '2024-08-25 23:59:59',
        ],
        self::APPLY_TYPE_AREA_302_RUNNING_CLUB => [
            1 => [
                'start_date_time' => '2024-07-06 00:00:00',
                'end_date_time' => '2024-08-16 23:59:59',
            ],
        ],
        self::APPLY_TYPE_TENJIN_RUNNERS_GATE => [
            1 => [
                'start_date_time' => '2024-07-29 00:00:00',
                'end_date_time' => '2024-08-24 23:59:59',
            ],
        ],
        self::APPLY_TYPE_TOKYO_LEGACY_HALF => [
            1 => [
                'start_date_time' => '2024-08-14 00:00:00',
                'end_date_time' => '2024-08-29 23:59:59',
            ],
            2 => [
                'start_date_time' => '2024-09-01 00:00:00',
                'end_date_time' => '2024-09-12 23:59:59',
            ],
        ],
        self::APPLY_TYPE_OSHMANS => [
            1 => [
                'start_date_time' => '2024-08-21 00:00:00',
                'end_date_time' => '2024-10-04 23:59:59',
            ],
        ],
        self::APPLY_TYPE_MINATO_RUNNERS_BASE => [
            7 => [
                'start_date_time' => '2024-06-22 00:00:00',
                'end_date_time' => '2024-07-18 23:59:5',
            ],
            8 => [
                'start_date_time' => '2024-08-21 00:00:00',
                'end_date_time' => '2024-10-04 23:59:59',
            ],
        ],
        self::APPLY_TYPE_TOKYO_ROKUTAI_FES => [
            1 => [
                'start_date_time' => '2024-09-01 00:00:00', // 9/4（水）
                'end_date_time' => '2024-09-11 23:59:5', // 9/11（水）
            ],
        ],
    ];

    /**
     * 申込フォームのリクエストから登録させる項目
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
            'choice_1',
            'choice_2',
            'choice_3',
        ],
        self::APPLY_TYPE_JUNIOR_FOOTBALL_442 => [
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
            'comment',
            'img_pass',
        ],
        self::APPLY_TYPE_AREA_302_RUNNING_CLUB => [
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
            'choice_1',
        ],
        self::APPLY_TYPE_TENJIN_RUNNERS_GATE => [
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
            'choice_1',
        ],
        self::APPLY_TYPE_TOKYO_LEGACY_HALF => [
            'f_name',
            'l_name',
            'f_read',
            'l_read',
            'sex',
            'email',
            'choice_1',
            'choice_2',
        ],
        self::APPLY_TYPE_OSHMANS => [
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
            'choice_1',
        ],
        self::APPLY_TYPE_MINATO_RUNNERS_BASE => [
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
            'choice_1',
        ],
        self::APPLY_TYPE_TOKYO_ROKUTAI_FES => [
            'f_name',
            'l_name',
            'f_read',
            'l_read',
            'sex',
            'email',
            'choice_1',
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
            'sex' => '性別',
            'address' => '住所',
            'tel' => '電話番号',
            'email' => 'メールアドレス',
            'choice_1' => 'ランニング頻度',
            'choice_2' => '試し履き希望シューズ',
            'choice_3' => 'シューズサイズ',
        ],
        self::APPLY_TYPE_JUNIOR_FOOTBALL_442 => [
            'name' => '名前',
            'age' => '年齢',
            'address' => '住所',
            'tel' => '電話番号',
            'email' => 'メールアドレス',
            'comment' => '返却同期',
            'img_pass' => 'レシート画像',
        ],
        self::APPLY_TYPE_AREA_302_RUNNING_CLUB => [
            'name' => '名前',
            'age' => '年齢',
            'address' => '住所',
            'tel' => '電話番号',
            'email' => 'メールアドレス',
            'choice_1' => 'どこでイベント知ったか',
        ],
        self::APPLY_TYPE_TENJIN_RUNNERS_GATE => [
            'name' => '名前',
            'age' => '年齢',
            'address' => '住所',
            'tel' => '電話番号',
            'email' => 'メールアドレス',
            'choice_1' => 'どこでイベント知ったか',
        ],
        self::APPLY_TYPE_TOKYO_LEGACY_HALF => [
            'name' => '名前',
            'sex' => '性別',
            'email' => 'メールアドレス',
            'choice_1' => '目標タイム',
            'choice_2' => 'シューズサイズ',
        ],
        self::APPLY_TYPE_OSHMANS => [
            'name' => '名前',
            'age' => '年齢',
            'address' => '住所',
            'tel' => '電話番号',
            'email' => 'メールアドレス',
            'choice_1' => 'どこでイベント知ったか',
        ],
        self::APPLY_TYPE_MINATO_RUNNERS_BASE => [
            'name' => '名前',
            'age' => '年齢',
            'address' => '住所',
            'tel' => '電話番号',
            'email' => 'メールアドレス',
            'choice_1' => 'どこでイベント知ったか',
        ],
        self::APPLY_TYPE_TOKYO_ROKUTAI_FES => [
            'name' => '名前',
            'sex' => '性別',
            'email' => 'メールアドレス',
            'choice_1' => '希望種目',
        ],
    ];

    /**
     * 抽選結果当選メールを送る機能がある案件
     */
    public const APPLY_LOTTERY_RESULT_WINNING_EMAIL = [
        self::APPLY_TYPE_KICHIJOJI_GREY_DAYS_EXCLUSIVE,
        self::APPLY_TYPE_KICHIJOJI_GREY_DAYS_5K_RUNNING,
        self::APPLY_TYPE_TOKYO_LEGACY_HALF,
    ];

    /**
     * 当選メールのテンプレート
     */
    public const WINNING_EMAIL_TEMPLATE = [
        self::APPLY_TYPE_KICHIJOJI_GREY_DAYS_EXCLUSIVE => 'emails.kichijoji_grey_days_exclusive.winningMail',
        self::APPLY_TYPE_KICHIJOJI_GREY_DAYS_5K_RUNNING => 'emails.kichijoji_grey_days_5k_runn.winningMail',
        self::APPLY_TYPE_TOKYO_LEGACY_HALF => 'emails.run_club_tokyo.announceMail',
    ];

    /**
     * 当選メールのタイトル
     */
    public const WINNING_EMAIL_TITLE = [
        self::APPLY_TYPE_KICHIJOJI_GREY_DAYS_EXCLUSIVE => 'Grey Days 2024 Exclusive Eventのご参加ありがとうございました。',
        self::APPLY_TYPE_KICHIJOJI_GREY_DAYS_5K_RUNNING => 'Grey Days 2024 5K Running Eventのご参加ありがとうございました。',
        self::APPLY_TYPE_TOKYO_LEGACY_HALF => '9/14（土）練習会について【New Balance Run Club Tokyo】',
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
        self::APPLY_TYPE_JUNIOR_FOOTBALL_442 => 'junior-football-2024',
    ];

    /**
     * フォームによって扱いが変わるカラム
     */
    public const CHOICE_1 = [
        self::APPLY_TYPE_CELEBRATION_SEAT => [
            0 => 'しない',
            1 => 'する',
        ],
        self::APPLY_TYPE_KICHIJOJI_GREY_DAYS_5K_RUNNING =>
            \App\Consts\KichijojiGrayDays5KRun::RUNNING_FREQUENCY,
        self::APPLY_TYPE_AREA_302_RUNNING_CLUB =>
            \App\Consts\Area302RunningClubConst::HOW_FOUND,
        self::APPLY_TYPE_TENJIN_RUNNERS_GATE =>
            \App\Consts\SSXFukuokaTenjinConst::HOW_FOUND,
        self::APPLY_TYPE_TOKYO_LEGACY_HALF =>
            \App\Consts\RunClubTokyoConstConst::GOAL_TIME,
        self::APPLY_TYPE_OSHMANS =>
            \App\Consts\OshmansConst::HOW_FOUND,
        self::APPLY_TYPE_MINATO_RUNNERS_BASE =>
            \App\Consts\MinatoRunnersBaseConst::HOW_FOUND,
        self::APPLY_TYPE_TOKYO_ROKUTAI_FES =>
            \App\Consts\TokyoRokutaiFesConst::PREFERRED_EVENT,
    ];

    public const CHOICE_2 = [
        self::APPLY_TYPE_KICHIJOJI_GREY_DAYS_5K_RUNNING =>
            \App\Consts\KichijojiGrayDays5KRun::DESIRED_SIZE,
        self::APPLY_TYPE_TOKYO_LEGACY_HALF =>
            \App\Consts\RunClubTokyoConstConst::SHOES_SIZE,
    ];

    public const CHOICE_3 = [
        self::APPLY_TYPE_KICHIJOJI_GREY_DAYS_5K_RUNNING =>
            \App\Consts\KichijojiGrayDays5KRun::SHOES_SIZE,
    ];

}
