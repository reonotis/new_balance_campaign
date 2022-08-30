<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KokurutsuArukuTokyo extends Model
{
    use HasFactory;

    protected $date = [
        'created_at'
    ];

    protected $table = 'kokuritsu_aruku_tokyo';

}
