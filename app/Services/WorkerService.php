<?php


namespace App\Services;


use App\Models\WorkerAttendanceItemInfo;
use App\Models\WorkerAttendanceSheet;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class WorkerService
{

    public function getAttendanceSheets(array $filterOptions = null){
        $items = DB::table('worker_attendance_sheet_info')
            ->select('id','name','date','group_id','group','members_present');
        if(isset($filterOptions) && isset($filterOptions['start_date'])){
            $items = $items->whereDate('date','>',Carbon::parse($filterOptions['start_date']));
        }
        if(isset($filterOptions) && isset($filterOptions['start_date'])){
            $items = $items->whereDate('date','>',Carbon::parse($filterOptions['end_date']));
        }
        if(isset($filterOptions) && isset($filterOptions['group_id'])){
            $items = $items->where('group_id','=',$filterOptions['group_id']);
        }
        return $items;
    }

    public function storeSheet(array $data){
        //try {
            WorkerAttendanceSheet::create($data);
            return true;
       // }
       // catch (Exception $e){
         //   return false;
       // }
    }

    public function deleteSheet($sheetId){
        try {
            DB::table('worker_attendance_sheets')->where('id','=',$sheetId)->delete();
            return true;
        }
        catch (Exception $e){
            return false;
        }
    }

    public function getSheetInfo($sheetId){
        $items = WorkerAttendanceItemInfo::where('sheet_id','=',$sheetId)
                ->select(['id','member','member_id','member_image','phone_number','member_type','id_number']);
        return $items;
    }

    public function addItemToSheet($sheetId,$memberId){
        try {
            DB::table('worker_attendance_sheet_items')
                ->insert([
                    'sheet_id' => $sheetId,
                    'worker_id' => $memberId,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString()
                ]);
            return true;
        }
        catch (Exception $e){
            return false;
        }
    }

    public function removeItemFromSheet($sheetId,$memberId){
        try {
            DB::table('worker_attendance_sheet_items')
                ->where('sheet_id','=',$sheetId)
                ->where('worker_id','=',$memberId)
                ->delete();
            return true;
        }
        catch (Exception $e){
            return false;
        }
    }

    public  function getItemsNotOnSheet($sheetId, $page, $name, $groupId){
        $resultCount = 10;
        $offset = ($page-1) * $resultCount;
        $membersPresent = DB::table('worker_attendance_sheet_items')
            ->where('sheet_id',$sheetId)
            ->select('worker_id as id')->get()->toArray();

        //if no members present on sheet return full member list
        if(count($membersPresent) == 0){
            $items = DB::table('work_group_member_info')
                ->where('work_group_id','=',$groupId)
                ->where('member','like',"%$name%")
                ->select('id',DB::raw('member as text'));
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
        $items = DB::table('work_group_member_info')
            ->where('member','like',"%$name%")
            ->where('work_group_id','=',$groupId)
            ->select('id',DB::raw('member as text'))
            ->whereNotIn('id',$membersPresent);

        $count = $items->count();
        if($page != null){
            $items->skip($offset)->take($resultCount);
        }
        return [
            'results' => $items->get(),
            'total_items' => $count
        ];

    }

    public function updateSheet($sheetId, array $data){
        $sheet = WorkerAttendanceSheet::find($sheetId);
        $sheet->update($data);
        return true;
    }
}
