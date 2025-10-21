<?php

namespace App\Consts;

class Common
{
    public const ZENKAKUKANA = '/^[ァ-ヶー]+$/u';
    public const MAILADDRESS = '/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/';
    public const DENWABANGOU = '/^[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}$/';
    public const BIRTHDAY = '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/';

    public const SEX_MEN = 1;
    public const SEX_WOMAN = 2;
    public const SEX_OTHER = 3;
    public const SEX_LIST = [
        self::SEX_MEN => '男性',
        self::SEX_WOMAN => '女性',
        self::SEX_OTHER => '回答しない',
    ];

    public const APPLY_TYPE_S223 = 1;
    public const APPLY_TYPE_GO_FUN = 2;
}
