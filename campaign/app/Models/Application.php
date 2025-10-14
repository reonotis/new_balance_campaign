<?php

namespace app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int id
 * @property int apply_type
 * @property string f_name
 * @property string l_name
 * @property string f_read
 * @property string l_read
 * @property int sex
 * @property string birthday
 * @property int age
 * @property string tel
 * @property string email
 * @property string zip21
 * @property string zip22
 * @property string pref21
 * @property string address21
 * @property string street21
 * @property string img_pass
 * @property string comment
 * @property string comment2
 * @property string comment3
 * @property string choice_1
 * @property string choice_2
 * @property string choice_3
 * @property string choice_4
 * @property string my_NBID
 * @property Carbon created_at
 */
class Application extends Model
{
    use HasFactory;

    protected $table = 'application';

    protected $fillable = [
        'sent_lottery_result_email_flg',
    ];

    protected $date = [
        'birthday',
        'created_at',
    ];

}
