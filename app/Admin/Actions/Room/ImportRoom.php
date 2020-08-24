<?php

namespace App\Admin\Actions\Room;

use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;
use App\Imports\RoomsImport;


class ImportRoom extends Action
{
    public $name = '导入数据';
    protected $selector = '.import-room';

    public function handle(Request $request)
    {
        $import = new RoomsImport();
        $import->import($request->file('file'));

        $str = "";
        foreach ($import->failures() as $failure) {

            $str .=  ' 第'. $failure->row() . '行 失败原因：' . implode(' ', $failure->errors()) . '<br> 行数据：' . implode(' ', $failure->values()). '<br>';

        }

        if ($str !== '') {
            return $this->response()->error($str)->topFullWidth()->timeout(7000000);
        }

        return $this->response()->success('导入成功');
    }

    public function form()
    {
        $this->file('file', '请选择文件');
    }


    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-default import-room"><i class="fa fa-upload"></i>导入数据</a>
HTML;
    }
}
