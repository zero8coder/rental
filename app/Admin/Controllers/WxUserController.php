<?php

namespace App\Admin\Controllers;

use App\Models\WxUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WxUserController extends AdminController
{

    protected $title = '微信用户';


    protected function grid()
    {
        $grid = new Grid(new WxUser());
        $grid->filter(function($filter){

            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            $filter->like('name', '姓名');

            $filter->like('phone', '手机号');

        });

        $grid->column('id', __('Id'));
        $grid->column('name', '姓名');
        $grid->column('phone', '手机号');
        $grid->column('openid','Openid');
        $grid->column('created_at', '创建时间');
        $grid->column('updated_at','更新时间');
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            // 去掉删除
            $actions->disableDelete();

        });
        return $grid;
    }


    protected function detail($id)
    {
        $show = new Show(WxUser::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', '姓名');
        $show->field('phone',  '手机号');
        $show->field('openid', 'Openid');
        $show->field('created_at',  '创建时间');
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
        $form = new Form(new WxUser());

        $form->text('name', __('Name'));
        $form->mobile('phone', __('Phone'));

        return $form;
    }
}
