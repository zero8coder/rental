<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Room\ImportRoom;
use App\Models\Room;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

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
