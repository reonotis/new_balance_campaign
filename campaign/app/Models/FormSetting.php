<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $apply_type
 * @property string $route_name
 * @property int $form_no
 * @property string $title
 * @property string $secretariat_mail_address
 * @property string $mail_title
 * @property string $mail_text
 * @property int $max_application_count
 * @property Carbon $start_at
 * @property Carbon $end_at
 * @property bool $send_bulk_mail_flg
 *
 * @property HasMany<int, FormItem> $formItem
 */
class FormSetting extends Model
{
    use HasFactory;

    protected $table = 'form_setting';
    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    protected $fillable = [
        'apply_type',
        'form_no',
        'title',
        'max_application_count',
        'route_name',
        'start_at',
        'end_at',
        'form_information',
        'secretariat_mail_address',
        'mail_title',
        'mail_text',
        'image_dir_name',
        'css_file_name',
        'banner_file_name',
    ];

    /**
     * @return HasMany
     */
    public function formItem():HasMany
    {
        return $this->hasMany(FormItem::class)
            ->where('delete_flag' , 0)
            ->orderBy('sort');
    }

}
