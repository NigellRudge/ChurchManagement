<?php


namespace App\Services;


use App\Models\CovidRegistrationSheetInfo;
use Illuminate\Support\Facades\DB;

class CovidRegistrationService
{
    public function getSheets(array $data=null){
        return CovidRegistrationSheetInfo::all();
    }

    public function AddOrUpdateSheet(array $data,$sheetId=null): int{
        if(isset($sheetId)){
            return DB::table('covid_registration_sheet')
                ->where('id',$sheetId)
                ->update($data);
        }
        return DB::table('covid_registration_sheet')
                    ->insert($data);
    }

    public function destroySheet($sheetId){
        return DB::transaction(function() use($sheetId){
            DB::table('covid_registration_sheet_item')
                ->where('sheet_id','=',$sheetId)
                ->delete();

            DB::table('covid_registration_sheet')
                ->where('id','=',$sheetId)
                ->delete();
        });
    }

    public function membersOnSheet($sheetId, array $filterOptions = null){
        return DB::table('covid_registration_sheet_item_info')
                ->where('sheet_id',$sheetId)
                ->select('id','member','phone_number','id_number','member_id','gender','member_type');
    }

    public function addToSheet($sheetId,$memberId):int{
        if(isset($sheetId) && isset($memberId)){
            return DB::table('covid_registration_sheet_item')
                ->insert([
                    'sheet_id' => $sheetId,
                    'member_id' => $memberId
                ]);
        }
        return 0;
    }

    public function removeFromSheet($sheetId,$memberId):int{
        return DB::table('covid_registration_sheet_item')
            ->where('sheet_id',$sheetId)
            ->where('member_id',$memberId)
            ->delete();
    }

    public function membersNotOnSheet($sheetId, $name, $page){
        //Check if members present for this group and sheet id
        $membersPresent = DB::table('covid_registration_sheet_item')
            ->where('sheet_id',$sheetId)
            ->select('member_id as id')->get()->toArray();
        $take = 10;
        $offset = ($page - 1) * $take;
        //if no members present on sheet return full member list
        if(count($membersPresent) == 0){
            $results = DB::table('member_info')
                ->where('name','like',"%$name%")
                ->select('id',DB::raw('name as text'));
            $count = $results->count();
            if($page != null){
                $results = $results->skip($offset)->take($take);
            }
            return  [
                'data'=>$results->get(),
                'count' =>$count
            ];
        }
        $temp = array();
        foreach ($membersPresent as $member){
            array_push($temp,$member->id);
        }
        $membersPresent = $temp;
        //dd($membersPresent);
        // if members present on sheet return list of members not present on sheet
        $results = DB::table('member_info')
            ->where('name','like',"%$name%")
            ->select('id',DB::raw('name as text'))
            ->whereNotIn('id',$membersPresent);
        $count = $results->count();
        if($page != null){
             $result = $results->skip($offset)->take($take);
        }
        return [
            'data'=>$results->get(),
            'count' =>$count
        ];
    }
}
