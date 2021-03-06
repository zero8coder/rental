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

    const ROOM_TENANT_STATUS_IN = 'in';
    const ROOM_TENANT_STATUS_OUT = 'out';

    public static $roomTenantStatusMap = [
        self::ROOM_TENANT_STATUS_IN => '入住',
        self::ROOM_TENANT_STATUS_OUT => '退房',
    ];



    protected $fillable = [
        'no',
        'storey',
        'status',
    ];

    public function tenants()
    {
        return $this->belongsToMany('App\Models\Tenant')->withPivot('status', 'created_at', 'is_del')->withTimestamps();
    }

    public function normalTenants()
    {
        return $this->belongsToMany('App\Models\Tenant')->withPivot('status', 'created_at', 'is_del')->wherePivot('is_del', false)->withTimestamps();
    }




}
