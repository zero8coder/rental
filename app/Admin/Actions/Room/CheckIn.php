<?php

namespace App\Admin\Actions\Room;

use App\Models\Room;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class CheckIn extends RowAction
{
    public $name = '入住';

    public function handle(Model $model)
    {
        $room = Room::findOrFail(session()->get('room_id'));
        $room->tenants()->updateExistingPivot($model->id, ['status' => Room::ROOM_TENANT_STATUS_IN]);
        return $this->response()->success('入住成功')->refresh();
    }

    public function dialog(){
        $this->confirm('是否入住');
    }

}
