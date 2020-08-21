<?php

namespace App\Admin\Actions\Room;

use Encore\Admin\Actions\Action;
use Encore\Admin\Actions\Response;
use Illuminate\Http\Request;
use App\Imports\RoomsImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportRoom extends Action
{
    public $name = '导入数据';
    protected $selector = '.import-room';

    public function handle(Request $request)
    {
        $import = new RoomsImport();
        $import->import($request->file('file'));

        $errorRows = [];
        $str = "";
        foreach ($import->failures() as $failure) {
            $errorRow = [];
            $errorRow['row'] = $failure->row(); // row that went wrong
            $errorRow['attribute'] = $failure->attribute(); // either heading key (if using heading row concern) or column index
            $errorRow['errors'] = $failure->errors(); // Actual error messages from Laravel validator
            $errorRow['values'] = $failure->values(); // The values of the row that has failed.
            array_push($errorRows, $errorRow);

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
        <a class="btn btn-sm btn-default import-room">导入数据</a>
HTML;
    }
}
