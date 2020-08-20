<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{


    const STATUS_UNUSED = 'unused';
    const STATUS_USING = 'using';

    public static $statusMap = [
        self::STATUS_UNUSED => '闲置',
        self::STATUS_USING => '出租中',
    ];


    protected $fillable = [
        'no',
        'storey',
        'status',
    ];

}
