<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;

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
