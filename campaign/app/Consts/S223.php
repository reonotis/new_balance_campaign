<?php

namespace App\Consts;

class S223
{
    // 各時間の申し込み制限
    public const CHOICE_TIME = [
        '1' => [
            'time' => '16:00',
            'limit' => 10,
        ],
        '2' => [
            'time' => '17:00',
            'limit' => 10
        ],
        '3' => [
            'time' => '18:00',
            'limit' => 10
        ],
    ];
}
