<?php


namespace App\Services;


use App\Models\Member;
use App\Models\MemberInfo;
use App\Models\OfferingInfo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    private $startOfMonth;
    private $endOfMonth;
    public $months = ['Jan','Feb','Mar','Apr','May','Jun' ,'Jul' ,'Aug' ,'Sep' ,'Oct' ,'Nov' ,'Dec'];
    public function __construct()
    {
        $this->startOfMonth = Carbon::now()->firstOfMonth()->toDateString();
        $this->endOfMonth = Carbon::now()->lastOfMonth()->toDateString();
    }

    private function getMonth($monthNumber){
        return trans('javascript.month_'.$monthNumber);
    }
    public function getAllMonths(){
        $returnData = array();
        for($monthNumber=1;$monthNumber<=12;$monthNumber++){
            array_push($returnData, trans('javascript.month_'.$monthNumber));
        }
        return $returnData;
    }

    public function getTotalSeedsPerSeedType($seedType){
        return (DB::table('seeds_info')
                ->whereBetween('date',[$this->startOfMonth,$this->endOfMonth])
                ->where('type_id',$seedType)
                ->sum('amount_in_base_currency'))/100;
    }

    public function getTotalVisitors(array $filterOptions = null){

    }

    public function getTotalConverts(array $filterOptions = null){
        return DB::table('converts_info')
            ->whereBetween('convert_date',[$this->startOfMonth,$this->endOfMonth])
            ->count();
    }

    public function getTotalOfferings(array $filterOptions = null){
        return (DB::table('offering_info')
                ->whereBetween('date',[$this->startOfMonth,$this->endOfMonth])
                ->sum('total_amount'))/100;
    }

    public function getChartDataPerSeedType($seedType = 1,array $filterOptions = null){
        $chartData = array();
        $count = 0;
        while($count < 12){
            $now = Carbon::now()->firstOfYear();
            $now->addMonths($count);
            $seedData = DB::table('seeds_info')
                ->whereDate('date','>=' ,$now->firstOfMonth())
                ->whereDate('date','<=' ,$now->lastOfMonth())
                ->where('type_id',$seedType)
                ->sum('amount_in_base_currency')/100;

            $item = [
                'name' => $this->months[$count],
                'amount' => $seedData,
                'date' => $now->toDateString(),
                'count' => $count
            ];
            array_push($chartData,$item);
            $count = $count + 1;
        }
        return $chartData;
    }

    public function getCollectionsChartData(array $filterOptions = null){
        $chartData = array();
        $count = 0;
        while($count < 12){
            $now = Carbon::now()->firstOfYear();
            $now->addMonths($count);
            $collectionData = OfferingInfo::whereDate('date','>=' ,$now->firstOfMonth())
                ->whereDate('date','<=' ,$now->lastOfMonth())
                ->sum('total_amount')/100;

            $item = [
                'name' => $this->getMonth($count+1),
                'amount' => $collectionData,
                'date' => $now->toDateString(),
                'count' => $count
            ];
            array_push($chartData,$item);
            $count = $count + 1;
        }
        return $chartData;
    }

    public function  getDashboardData(): array{
        return [
            'total_members' => Member::count(),
            'total_tides' => $this->getTotalSeedsPerSeedType(1),
            'total_seeds' => $this->getTotalSeedsPerSeedType(2),
            'total_converts' => $this->getTotalConverts(),
            'total_offerings' =>  $this->getTotalOfferings(),
            'months' => $this->months,
            'tides_chart_data' => $this->getChartDataPerSeedType(1),
            'seed_chart_data' => $this->getChartDataPerSeedType(2),
            'collection_chart_data' => $this->getCollectionsChartData(),
        ];
    }

    public function getTotalYouthMembers(array $filterOptions = null){
        return MemberInfo::all()->filter(function($member){
            return (($member['age'] >= 14 && $member['age'] <=30));
        })->count();
    }

    public function getVisitorsThisWeek(array $filterOptions = null){
        return DB::table('visitors_info')
            ->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();
    }

    public function getVisitorsChartData(){
        $count = 0;
        $chartData = array();
        while($count < 12){
            $now = Carbon::now()->firstOfYear();
            $now->addMonths($count);
            $from = $now->firstOfMonth()->toDateString();
            $to = $now->lastOfMonth()->toDateString();
            $amount = DB::table('visitors_sheet_info')
                ->whereBetween('date',[$from,$to])
                ->sum('num_visitors');
            $item = [
                'month' => $this->months[$count],
                'amount' => $amount
            ];
            $count = $count + 1;
            array_push($chartData,$item);
        }
        return $chartData;
    }

    public function getEagleGroupAbsentChartData(array  $filterOptions = null){
        $eagleGroups = DB::table('eagle_group_info')->select('id','name','num_members')->get();
        $chartData = array();
        foreach ($eagleGroups as $group){
            $presentMembers = DB::table('attendance_sheet_rollcall as rollcall')
                ->leftJoin('attendance_sheet_info as sheet','rollcall.sheet_id','=','sheet.id')
                ->where('rollcall.group_id','=',$group->id)
                ->whereBetween('sheet.date',[Carbon::now()->startOfMonth(),Carbon::now()->lastOfMonth()])
                ->count();
            $absentMembers = $group->num_members - $presentMembers;
            $item = [
                'name' => $group->name,
                'total_members' => $group->num_members,
                'present' => $presentMembers,
                'absent' => $absentMembers
            ];
            array_push($chartData,$item);
        }
        return $chartData;
    }
}
