<?php

namespace App\Imports;

use App\Models\Room;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class RoomsImport implements ToModel,WithStartRow,WithValidation,SkipsOnFailure
{
    use Importable,SkipsFailures;

    private $statusMapKey;

    public function __construct()
    {
        $this->statusMapKey = array_flip(Room::$statusMap);
    }

    public function model(array $row)
    {
        return new Room([
            'no' => $row[0],
            'storey' => $row[1],
            'status' => $this->statusMapKey[$row[2]],
        ]);
    }

    // 设置去除表头 从第二行开始
    public function startRow(): int
    {
        return 2;
    }

    // 验证
    public function rules(): array
    {
        return [
            '0' => ['required', Rule::unique('rooms', 'no')],
            '2' => ['required', Rule::in(Room::$statusMap)],
        ];
    }

    // 自定义验证信息
    public function customValidationMessages()
    {
        return [
            '0.required' => '房间编号未填',
            '0.unique' => '房间编号已存在',
            '2.required' => '房间状态未填',
            '2.in' => '房间状态不符合规则',
        ];
    }
}
