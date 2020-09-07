<?php

namespace App\Admin\Controllers;


use App\Admin\Actions\Tenant\BatchRestore;
use App\Admin\Actions\Tenant\ImportTenant;
use App\Models\Room;
use App\Models\Tenant;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Actions\Tenant\Restore;
use App\Admin\Actions\Tenant\RoomTenant;

class TenantController extends AdminController
{

    protected $title = '租客';


    protected function grid()
    {

        $grid = new Grid(new Tenant());



        $grid->filter(function ($filter){
            $filter->disableIdFilter();
            $filter->column(1/3, function ($filter) {
                $filter->like('name', '姓名');
                $filter->between('created_at', '创建时间')->datetime();

            });

            $filter->column(1/3, function ($filter) {
                $filter->like('phone', '手机号')->mobile();
            });

            $filter->column(1/3, function ($filter) {
                $filter->like('id_card', '身份证');
            });


            $filter->scope('trashed', '回收站')->onlyTrashed();

        });


        $grid->column('id', __('Id'));
        $grid->column('name', __('姓名'));
        $grid->column('phone', __('手机号'));
        $grid->column('id_card', __('身份证'));
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('修改时间'));



        $grid->actions(function ($actions) {
            $row = $actions->row;
            if ($row->deleted_at) {
                // 删除状态

                // 去掉删除
                $actions->disableDelete();
                // 去掉编辑
                $actions->disableEdit();
                // 去掉查看
                $actions->disableView();
                // 添加恢复
                $actions->add(new Restore());
            }

        });

        $grid->batchActions(function ($batch) {
            $batch->add(new BatchRestore());
        });

        $grid->export(function ($export) {
            $export->filename(date("YmdHis") . '租客档案');
        });

        // 将导入操作加入到表格的工具条中
        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new ImportTenant());
        });


        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(Tenant::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('名称'));
        $show->field('phone', __('手机号'));
        $show->field('id_card', __('身份证'));
        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('修改时间'));

        $scope = \Request::get('_scope_');
        // 判断回收站筛选
        $is_del = false;
        if ($scope === 'is_del') {
            $is_del = true;
        }
        $show->rooms('房间', function ($rooms) use($id, $is_del) {

            if (!$is_del) {
                $rooms->model()->where('is_del', false);
            }

            $rooms->filter(function ($filter){
                $filter->disableIdFilter();
                $filter->column(1/3, function ($filter) {
                    $filter->like('no', '房间编号');
                    $filter->between('created_at', '创建时间')->datetime();
                });

                $filter->column(1/3, function ($filter) {
                    $filter->like('storey', '楼层');
                });

                $filter->scope('is_del', '回收站')->where('is_del', true);

            });


            $rooms->resource('/' . config('admin.route.prefix') . '/rooms');
            $rooms->no('房间编号');
            $rooms->storey('楼层');
            $rooms->status('状态')->display(function ($status) {
                return Room::$roomTenantStatusMap[$status];
            });


            $rooms->created_at('创建时间');


            $rooms->disableCreateButton();
            $rooms->disableExport();
            $rooms->disableRowSelector();
            $rooms->tools(function (Grid\Tools $tools) use($id) {
                session()->put('tenant_id', $id);
                $roomTenantTool = new RoomTenant();
                $tools->append($roomTenantTool);
            });



        });

        return $show;
    }

    protected function form()
    {
        $form = new Form(new Tenant());

        $form->text('name', '名称');
        $form->mobile('phone', __('手机号'));
        $form->text('id_card', __('身份证'));

        return $form;
    }
}
