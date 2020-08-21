<?php

namespace App\Admin\Actions\Room;

use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadRoomExcel extends Action
{
    protected $selector = '.download-room-excel';

    public function handle(Request $request)
    {
        $url = Storage::disk ('public')->url("import/room.xls");
        return $this->response()->download($url)->success('下载成功');
    }

    public function html()
    {

        return <<<HTML
        <a class="btn btn-sm btn-default download-room-excel">下载模板</a>
HTML;
    }
    public function dialog()
    {
        $this->confirm('确定下载模板？');
    }
}
