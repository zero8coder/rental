<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Room\CheckIn;
use App\Admin\Actions\Room\CheckOut;
use App\Admin\Actions\Room\DownloadRoomExcel;
use App\Admin\Actions\Room\ImportRoom;
use App\Admin\Actions\Room\BatchRestore;
use App\Admin\Actions\Room\Restore;
use App\Admin\Actions\Room\RoomTenant;
use App\Admin\Actions\Room\RoomTenantDelete;
use App\Admin\Actions\Room\RoomTenantRestore;
use App\Models\Room;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Storage;

class RoomController extends AdminController
{

    protected $title = '房间';

    protected function grid()
    {
        $grid = new Grid(new Room());

        $grid->filter(function ($filter){
            $filter->disableIdFilter();
            $filter->column(1/3, function ($filter) {
                $filter->like('no', '房间编号');
                $filter->between('created_at', '创建时间')->datetime();
            });

            $filter->column(1/3, function ($filter) {
                $filter->like('storey', '楼层')->integer();
            });

            $filter->column(1/3, function ($filter) {
                $filter->equal('status', '房间状态')->select([Room::STATUS_UNUSED => Room::$statusMap[Room::STATUS_UNUSED], Room::STATUS_USING => Room::$statusMap[Room::STATUS_USING]]);
            });

            $filter->scope('trashed', '回收站')->onlyTrashed();

        });



        $grid->column('id', __('Id'));
        $grid->column('no', '房间编号');
        $grid->column('storey', '楼层');
        $grid->column('status', '房间状态')->display(function ($status) {
            return Room::$statusMap[$status];
        });
        $grid->column('created_at', '创建时间');
        $grid->column('updated_at', '更新时间');

        $grid->export(function ($export) {
            $export->filename(date("YmdHis") . '房间档案');
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new ImportRoom());
            $tools->append(new DownloadRoomExcel());
        });

        $grid->actions(function ($actions) {
            $actions->add(new Restore());
        });

        $grid->batchActions(function ($batch) {
            $batch->add(new BatchRestore());
        });

        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(Room::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('no', '房间编号');
        $show->field('storey','楼层');
        $show->field('status', '房间状态')->as(function ($status) {
            return Room::$statusMap[$status];
        });
        $show->field('created_at', '创建时间');
        $show->field('updated_at', '更新时间');


        $scope = \Request::get('_scope_');
        // 判断回收站筛选
        $is_del = false;
        if ($scope === 'is_del') {
            $is_del = true;
        }
        $show->tenants('租客', function ($tenants) use($id, $is_del) {

            if (!$is_del) {
                $tenants->model()->where('is_del', false);
            }

            $tenants->filter(function ($filter){
                $filter->disableIdFilter();
                $filter->column(1/3, function ($filter) {
                    $filter->like('name', '租客姓名');
                    $filter->between('created_at', '创建时间')->datetime();
                });

                $filter->column(1/3, function ($filter) {
                    $filter->like('phone', '手机号');
                });

                $filter->scope('is_del', '回收站')->where('is_del', true);

            });


            $tenants->resource('/' . config('admin.route.prefix') . '/tenants');
            $tenants->name('租客姓名');
            $tenants->phone('手机号');
            $tenants->id_card('身份证');
            $tenants->status('状态')->display(function ($status) {
                return Room::$roomTenantStatusMap[$status];
            });


            $tenants->created_at('创建时间');


            $tenants->disableCreateButton();
            $tenants->disableExport();
            $tenants->disableRowSelector();

            $tenants->tools(function (Grid\Tools $tools) use($id) {
                $roomTenantTool = new RoomTenant();
                $roomTenantTool->setRoomId($id);
                $tools->append($roomTenantTool);
            });

            $tenants->actions(function ($actions) use($id) {

                session()->put('room_id', $id);
                $tenant = $actions->row;

                // 去掉删除
                $actions->disableDelete();

                // 正常状态
                if (!$tenant->is_del) {
                    // 添加删除操作
                    $actions->add(new RoomTenantDelete());

                    // 添加退房操作
                    if ($tenant->status === Room::ROOM_TENANT_STATUS_IN) {
                        $actions->add(new CheckOut());
                    }

                    // 添加入住操作
                    if ($tenant->status === Room::ROOM_TENANT_STATUS_OUT) {
                        $actions->add(new CheckIn());
                    }

                } else {
                // 删除状态

                    // 去掉编辑
                    $actions->disableEdit();
                    // 去掉查看
                    $actions->disableView();
                    // 增加恢复操作
                    $actions->add(new RoomTenantRestore());


                }



            });


        });
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Room());

        $form->text('no','房间编号');
        $form->number('storey', '楼层');

        $form->radio('status', '房间状态')->options([Room::STATUS_UNUSED => Room::$statusMap[Room::STATUS_UNUSED], Room::STATUS_USING => Room::$statusMap[Room::STATUS_USING]])->default(Room::STATUS_UNUSED);
        return $form;
    }

}
