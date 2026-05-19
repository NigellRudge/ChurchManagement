<?php

namespace App\Imports;

use App\Models\EagleMembership;
use App\Models\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EagleMembershipsImport implements ToModel,WithHeadingRow
{
    private $startRow = 2;
    public function __construct()
    {

    }

    /**
     * @param array $row
     * @return Model|Model[]|null
     */
    public function model(array $row)
    {
        //dd(date('Y-m-d',trim($row['birth_date'])));
        return new EagleMembership([
            'group_id' => $row['group_id'],
            'member_id' => $row['member_id'],
        ]);
    }
}
