<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'id_card',
    ];

    public function rooms()
    {
        return $this->belongsToMany('App\Models\Room')->withPivot('status', 'created_at', 'is_del')->withTimestamps();
    }


    public function normalRooms()
    {
        return $this->belongsToMany('App\Models\Room')->withPivot('status', 'created_at', 'is_del')->wherePivot('is_del', false)->withTimestamps();
    }
}
