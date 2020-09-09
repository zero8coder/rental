<?php

namespace App\Admin\Actions\Tenant;

use App\Models\Room;
use App\Models\Tenant;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class CheckIn extends RowAction
{
    public $name = '入住';

    public function handle(Model $model)
    {
        $tenant = Tenant::findOrFail(session()->get('tenant_id'));
        $tenant->rooms()->updateExistingPivot($model->id, ['status' => Room::ROOM_TENANT_STATUS_IN]);
        return $this->response()->success('退房成功')->refresh();
    }

    public function dialog(){
        $this->confirm('是否入住');
    }

}
