<?php

namespace App\Admin\Actions\Room;

use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;
use App\Imports\RoomsImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportRoom extends Action
{
    public $name = '导入数据';
    protected $selector = '.import-room';

    public function handle(Request $request)
    {
        Excel::import(new RoomsImport, $request->file('file'));
        return $this->response()->success('导入成功')->refresh();
    }

    public function form()
    {
        $this->file('file', '请选择文件');
    }

    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-default import-room">导入数据</a>
HTML;
    }
}
