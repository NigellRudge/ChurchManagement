<?php


namespace App\Services;


use App\Models\VisitorSheetInfo;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class VisitorService
{
    public function getSheets(array  $data = null){
        return DB::table('visitors_sheet_info')
            ->select('id','name','date','num_visitors');
    }

    public function storeSheet(array $data){
        try {
            return DB::table('visitors_sheet')
                ->insert(['name'=>$data['name'],'date'=>$data['date']]);
        }
        catch (Exception $e){
            return null;
        }
    }

    public function updateSheet($sheetId,array $data){
        try {
            DB::table('visitors_sheet')->where('id','=',$sheetId)
                ->update($data);
            return true;
        }
        catch (Exception $exception){
            return false;
        }
    }

    public function destroySheet(int $sheetId){
        try{
           return DB::transaction(function()use($sheetId){
               DB::table('visitors_sheet_item')
                   ->where('sheet_id','=',$sheetId)
                   ->delete();
               DB::table('visitors_sheet')
                   ->where('id','=',$sheetId)
                   ->delete();
           });
        }
        catch (Exception $e){
            return null;
        }
    }

    public function getSheetInfo(int $sheetId){
        try {
            return VisitorSheetInfo::findOrFail($sheetId);
        }
        catch (Exception $exception){
            return null;
        }
    }

    public function getSheetContent($sheetId, array $filterOptions = null){
        $visitors = DB::table('visitors_sheet_item_info')
            ->select('id','name','sheet_id','sheet','gender','invited_by', 'phone_number')
            ->where('sheet_id',$sheetId);
        if(isset($filterOptions['gender_id']) &&  intval($filterOptions['gender_id']) != 0){
            $visitors->where('gender_id','=',intval($filterOptions['gender_id']));
        }
        return $visitors;
    }

    public function getSheetExportData(int $sheetId){
        $sheet = DB::table('visitors_sheet_info')
            ->select('id','name','date','num_visitors')
            ->where('id',$sheetId)->get()->first();
        //dd($sheet);
        $name = "$sheet->name export.xlsx";
        return [
            'generated_date' => Carbon::now()->toDateTimeString(),
            'generated_by' => auth()->user()->name,
            'sheet_name' => $sheet->name,
            'sheet_date' => $sheet->date,
            'num_visitors' => $sheet->num_visitors,
        ];
    }

    public function addToSheet(array $data){
        try {
            return DB::table('visitors_sheet_item')->insert($data);
        }
        catch (Exception $e){
            return null;
        }
    }

    public function getVisitorInfo(array $data){
        try {
            return DB::table('visitors_sheet_item')
                ->where('id',$data['edit_visitor_id'])
                ->where('sheet_id',$data['sheet_id'])
                ->get()->first();
        }
        catch (Exception $exception){
            return null;
        }
    }

    public function updateVisitor($sheetId, array $data){
        try {
            return DB::table('visitors_sheet_item')
                ->where('id',$data['edit_visitor_id'])
                ->where('sheet_id',$sheetId)
                ->update([
                    'first_name' => $data['edit_first_name'],
                    'last_name' => $data['edit_last_name'],
                    'gender_id' => $data['edit_gender_id'],
                    'invited_by_id' => $data['edit_invited_by_id'],
                ]);
        }
        catch (Exception $exception){
            return null;
        }
    }

    public function removeFromSheet(array $data){
        try{
            return DB::table('visitors_sheet_item')
                ->where('id',$data['remove_visitor_id'])
                ->where('sheet_id',$data['sheet_id'])
                ->delete();
        }
        catch (Exception $exception){
            return null;
        }
    }

    public function getAvailableDates(){
        // Get all saturdays of the year
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
        $not_available_dates = DB::table('visitors_sheet_info')
            ->select('date')
            ->whereBetween('date',[Carbon::now()->addYears(-1)->toDateString(),Carbon::now()->toDateString()])
            ->orderBy('date','desc')
            ->get()->toArray();

        // Remove occupied dates from list
        $temp = array();
        foreach ($not_available_dates as $not_date){
            $key = array_search($not_date->date,$all_saturdays);
            array_push($temp,$key);
        }
        foreach ($temp as $t){
            unset($all_saturdays[$t]);
        }
        $results = array();
        foreach ($all_saturdays as $date){
            $item = ['id'=>$date,'text'=>$date];
            array_push($results,$item);
        }
        return $results;
    }
}
