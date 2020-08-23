<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Tenant\BatchRestore;
use App\Models\Room;
use App\Models\Tenant;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Actions\Tenant\Restore;

class TenantController extends AdminController
{

    protected $title = '租客';


    protected function grid()
    {

        $grid = new Grid(new Tenant());

        $grid->column('id', __('Id'));
        $grid->column('name', __('姓名'));
        $grid->column('phone', __('手机号'));
        $grid->column('id_card', __('身份证'));
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('修改时间'));

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

        $grid->actions(function ($actions) {
            $actions->add(new Restore());
        });

        $grid->batchActions(function ($batch) {
            $batch->add(new BatchRestore());
        });

        $grid->export(function ($export) {
            $export->filename(date("YmdHis") . '租客档案');
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
