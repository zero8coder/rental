<?php

namespace App\Imports;

use App\Models\Tenant;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;


class TenantsImport implements ToModel, WithStartRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        return new Tenant([
            'name' => $row[0],
            'phone' => $row[1],
            'id_card' => $row[2],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

    // 验证
    public function rules(): array
    {
        return [
            '0' => 'required',
        ];
    }

    // 自定义验证信息
    public function customValidationMessages()
    {
        return [
            '0.required' => '姓名未填',
        ];
    }


}
