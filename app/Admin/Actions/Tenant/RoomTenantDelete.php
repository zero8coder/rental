<?php

namespace App\Admin\Actions\Tenant;

use App\Models\Tenant;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class RoomTenantDelete extends RowAction
{
    public $name = '删除';

    public function handle(Model $model)
    {
        $tenant = Tenant::findOrFail(session()->get('tenant_id'));
        $tenant->rooms()->updateExistingPivot($model->id, ['is_del' => true]);
        return $this->response()->success('删除成功')->refresh();
    }

    public function dialog(){
        $this->confirm('是否删除');
    }
}
