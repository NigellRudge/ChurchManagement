<?php

namespace App\Imports;

use App\Models\Member;
use App\Models\MemberMembership;
use App\utils\CustomUtils;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class MemberImport implements ToModel,WithHeadingRow
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
        $member = new Member([
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'maiden_name' => $row['maiden_name'],
            'address' => $row['address'],
            'phone_number' => isset($row['phone_number']) ? $row['phone_number'] : 'Geen info',
            'email' => $row['email'],
            'gender_id' => intval($row['gender']),
            'birth_date' => CustomUtils::transFormDate($row['birth_date']),
            'member_type_id' => intval($row['member_type']),
            'marriage_date' => CustomUtils::transFormDate($row['marriage_date']),
            'neighborhood' => intval($row['neighborhood']),
            'notes' => intval($row['notes']),
            'skills' => intval($row['skills']),
            'district_id' => 1,
            'convert_date' => CustomUtils::transFormDate($row['convert_date']),
            'image'  => $row['image'],
            'job_description'  => $row['job_description'],
            'baptize_date'  => $row['baptize_date'],
            'baptized'  => isset($row['baptize_date']),
            'id_number'  => $row['id_number'],

        ]);
        $member->save();
        return new MemberMembership([
            'member_id' => $member['id'],
            'membership_type_id' =>$row['member_type'],
            'start_date' => isset($row['join_date']) ? CustomUtils::transFormDate($row['join_date']) : Carbon::now()->toDateString(),
            'end_date' => null,
            'created_at' => now()->toDateTimeString()
        ]);
    }

}
