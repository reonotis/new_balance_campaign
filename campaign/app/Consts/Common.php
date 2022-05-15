<?php

namespace App\Consts;

class Common
{
    public const ZENKAKUKANA = '/^[ァ-ヶー]+$/u';
    public const MAILADDRESS = '/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/';
    public const DENWABANGOU = '/^[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}$/';
    public const BIRTHDAY = '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/';

}
