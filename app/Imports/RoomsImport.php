<?php

namespace App\Imports;

use App\Models\Room;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class RoomsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $key=>$row)
        {
            if ($key != 0) {
                $statusMapKey = array_flip(Room::$statusMap);
                Room::create([
                    'no' => $row[0],
                    'storey' => $row[1],
                    'status' => $statusMapKey[$row[2]],
                ]);
            }

        }

    }
}
