<?php

namespace App\Admin\Actions\Tenant;

use App\Models\Room;
use Encore\Admin\Actions\Action;
use Exception;
use Illuminate\Http\Request;
use App\Models\Tenant;

class RoomTenant extends Action
{
    protected $selector = '.room-tenant';


    public function handle(Request $request)
    {
        $no = $request->input('no');
        $tenant_id = $request->input('tenant_id');
        $tenant = Tenant::findOrFail($tenant_id);
        $tenantRoomsIds = $tenant->normalRooms()->pluck('id')->toArray();


        if (!Room::where('no', $no)->exists()) {
            throw new Exception('找不到该房间');
        }

        $room = Room::where('no', $no)->first();

        if (in_array($room->id, $tenantRoomsIds)) {
            throw new Exception('该房间已绑定');
        }

        $tenant->rooms()->attach($room->id);

        if ($room->status === Room::STATUS_UNUSED) {
            $room->status = Room::STATUS_USING;
            $room->save();
        }

        return $this->response()->success('成功')->refresh();
    }

    public function form() {

        $this->hidden('tenant_id')->value(session()->get('tenant_id'));
        $this->text('no', '房间编号')->required();

    }


    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-default room-tenant">入住</a>
HTML;
    }


}
