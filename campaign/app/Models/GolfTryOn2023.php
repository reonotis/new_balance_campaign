<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string f_name
 * @property string l_name
 * @property string f_read
 * @property string l_read
 * @property int age
 * @property int sex
 * @property string zip21
 * @property string zip22
 * @property string pref21
 * @property string address21
 * @property string street21
 * @property string tel
 * @property string email
 * @property string img_pass
 * @property int reason_applying
 */
class GolfTryOn2023 extends Model
{
    use HasFactory;

    protected $table = 'golf_try_on_2023';
    protected $date = [
        'created_at'
    ];
}
