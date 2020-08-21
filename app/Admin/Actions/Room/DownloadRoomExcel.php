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
        // $request ...

        return $this->response()->success('Success message...')->refresh();
    }

    public function html()
    {
        $url = Storage::disk ('public')->url("import/room.xls");
        return <<<HTML
        <a class="btn btn-sm btn-default" href=$url target="_blank">下载模板</a>
HTML;
    }
}
