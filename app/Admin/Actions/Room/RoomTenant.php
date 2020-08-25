<?php

namespace App\Admin\Actions\Room;

use App\Models\Room;
use Encore\Admin\Actions\Action;
use Exception;
use Illuminate\Http\Request;
use App\Models\Tenant;

class RoomTenant extends Action
{
    protected $selector = '.room-tenant';
    private $room_id;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(Request $request)
    {
        $tenant = null;
        $name = $request->input('name');
        $phone = $request->input('phone');
        $id_card = $request->input('id_card');
        $room_id = $request->input('room_id');
        \DB::Transaction( function() use ($name, $phone, $id_card, $room_id)
        {
            $room = Room::find($room_id);
            // 房间租客 ID 集合
            $roomTenantsIds = $room->tenants->pluck('id')->toArray();

            if ($name && $phone) {
                // 填写名称和手机号
                $tenant = Tenant::where('name', $name)->where('phone', $phone)->first();
                if (!$tenant) {
                    $tenant = new Tenant([
                        'name' => $name,
                        'phone' => $phone,
                        'id_card' => $id_card,
                    ]);
                    $tenant->save();
                }

                // 判断是否已经绑定过房间
                if (in_array($tenant->id, $roomTenantsIds)) {
                    throw new Exception('该租客已绑定了房间');
                }

                // 房间绑定客户
                $room->tenants()->attach($tenant->id);

            } else {
                // 填写租客名称
                $tenants = Tenant::where('name', $name)->get();
                $tenantIds = $tenants ->pluck('id')->toArray();
                // 判断是否绑定过房间
                if (!empty(array_intersect($tenantIds, $roomTenantsIds))) {
                    throw new Exception('该租客已绑定了房间');
                }

                $tenant = new Tenant([
                    'name' => $name,
                    'id_card' => $id_card,
                ]);
                $tenant->save();
                $room->tenants()->attach($tenant->id);
            }

            if ($room->status === Room::STATUS_UNUSED) {
                $room->status = Room::STATUS_USING;
                $room->save();
            }
        });

        return $this->response()->success('成功')->refresh();
    }

    public function form() {
        $this->hidden('room_id')->value($this->room_id);
        $this->text('name', '租客姓名')->required();
        $this->mobile('phone', '手机号');
        $this->text('id_card', '身份证');
    }


    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-default room-tenant">入住</a>
HTML;
    }

    public function setRoomId($room_id) {
        $this->room_id = $room_id;
    }
}
