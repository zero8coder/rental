<?php

namespace App\Admin\Actions\Room;

use App\Models\Room;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class RoomTenantRestore extends RowAction
{
    public $name = '恢复';

    public function handle(Model $model)
    {
        $room = Room::findOrFail(session()->get('room_id'));
        $room->tenants()->updateExistingPivot($model->id, ['is_del' => false]);
        return $this->response()->success('成功')->refresh();
    }

    public function dialog(){
        $this->confirm('是否恢复');
    }


}
