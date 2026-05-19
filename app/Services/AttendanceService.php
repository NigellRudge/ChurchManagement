<?php


namespace App\Services;

use App\Models\AttendanceSheetInfo;
use App\Models\RollCall;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    public function getAttendanceSheets(array $data = null){
        return DB::table('attendance_sheet_info')
            ->select('id','name','date','members_present');
    }

    public function storeSheet(array $data){
        try {
            $result = DB::table('attendance_sheet')
                ->insert(['name'=>$data['name'],'date'=>$data['date']]);
        }
        catch (\Exception $e){
            $result = null;
        }
        return $result;

    }

    public function deleteSheet($sheetId){
        try {
            $result = DB::transaction(function()use($sheetId){
                DB::table('attendance_sheet_item')
                    ->where('sheet_id','=',$sheetId)
                    ->delete();
                DB::table('attendance_sheet')
                    ->where('id','=',$sheetId)
                    ->delete();
            });
        }
        catch (Exception $e){
            $result = null;
        }
        return $result;
    }

    public function getSheetInfo($sheetId,array $filterOptions){
        $all_members = RollCall::where('sheet_id',$sheetId)
            ->select('id','member','group_id','group','sheet_id','member_type','image','phone_number');

        if(isset($filterOptions['group_id']) &&  intval($filterOptions['group_id']) != 0){
            $all_members->where('group_id','=',intval($filterOptions['group_id']));
        }
        return $all_members;
    }

    public function addToSheet($data){
        try {
            $result = DB::table('attendance_sheet_item')
                ->insert($data);
        }
        catch (Exception $e){
            $result = null;
        }
        return $result;
    }

    public function removeFromSheet($data){
        try {
            $result = DB::table('attendance_sheet_item')
                ->where('sheet_id',$data['sheet_id'])
                ->where('member_id',$data['remove_member_id'])
                ->delete();
        }
        catch (\Exception $exception){
            $result = null;
        }
        return $result;
    }

    public function getMembers($sheetId, $term,$page){
        $resultCount = 10;
        $offset = ($page-1) * $resultCount;
        $membersPresent = DB::table('attendance_sheet_item')
            ->where('sheet_id',$sheetId)
            ->select('member_id as id')->get()->toArray();

        //if no members present on sheet return full member list
        if(count($membersPresent) == 0){
            $items = DB::table('eagle_member_info')
                ->where('name','like',"%$term%")
                ->select('id',DB::raw('name as text'));
            $count = $items->count();
            if($page != null){
                $items->skip($offset)->take($resultCount);
            }
            return [
                'results' => $items->get(),
                'total_items' => $count
            ];
        }
        $temp = array();
        foreach ($membersPresent as $member){
            array_push($temp,$member->id);
        }
        $membersPresent = $temp;
        // if members present on sheet return list of members not present on sheet
        $items = DB::table('eagle_member_info')
            ->where('name','like',"%$term%")
            ->select('id',DB::raw('name as text'))
            ->whereNotIn('id',$membersPresent);
        // get teamcaptains
        $teamCaptains = DB::table('member_info')
                        ->where('member_type_id','=',6)
                        ->select('id',DB::raw('name as text'));
        //$items = $items->union($teamCaptains);
        $count = $items->count();
        if($page != null){
            $items->skip($offset)->take($resultCount);
        }
        return [
            'results' => $items->get(),
            'total_items' => $count
        ];

    }


    public function getSheetExportData($sheetId){
        $sheet = AttendanceSheetInfo::findOrFail($sheetId);
        return [
            'sheet_id' => $sheet->id,
            'sheet_name' => $sheet->name,
            'sheet_date' => $sheet->date,
            'generated_by' => auth()->user()->name,
            'generated_date' => Carbon::now()->toDateTimeString(),
            'num_present' => $sheet->members_present,
            'num_absent' => 0
        ];

    }

    public function getDates(){
        $last_saturday = Carbon::parse('last saturday');
        $all_saturdays = array();
        $today = Carbon::now();
        if($today->isSaturday()){
            array_push($all_saturdays,$today->toDateString());
        }
        while($last_saturday->year >= 2021){
            array_push($all_saturdays,$last_saturday->toDateString());
            $last_saturday->addDays(-7);
        }

        //get all dates with a attendance sheet
        $not_available_dates = DB::table('attendance_sheet')
            ->select('date')
            ->whereBetween('date',[Carbon::now()->addYears(-1)->toDateString(),Carbon::now()->toDateString()])
            ->orderBy('date','desc')
            ->get()->toArray();

        // Remove occupied dates from list
        $temp = array();
        foreach ($not_available_dates as $not_date){
            $key = array_search($not_date->date,$all_saturdays);
            unset($all_saturdays[$key]);
        }
        $results = array();
        foreach ($all_saturdays as $date){
            $item = ['id'=>$date,'text'=>$date];
            array_push($results,$item);
        }
        return $results;
    }
}
