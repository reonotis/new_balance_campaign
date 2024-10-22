<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 * @property int choice_1
 * @property int choice_2
 * @property int choice_3
 * @property \Illuminate\Support\Carbon created_at
 */
class CommonApply extends Model
{
    use HasFactory;

    protected $table = 'common_apply';
    protected $date = [
        'birthday',
        'created_at',
    ];

    /**
     * @param int $applyType
     * @return mixed
     */
    public function getAll(int $applyType)
    {
        return self::where('delete_flag', 0)
            ->where('apply_type', $applyType)->get();
    }

}
