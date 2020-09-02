<?php

namespace App\Admin\Actions\Room;

use App\Models\Room;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class RoomTenantDelete extends RowAction
{
    public $name = '删除';

    public function handle(Model $model)
    {
        $room = Room::findOrFail(session()->get('room_id'));
        $room->tenants()->updateExistingPivot($model->id, ['is_del' => true]);
        return $this->response()->success('Success message.')->refresh();
    }

    public function dialog(){
        $this->confirm('是否删除');
    }






}