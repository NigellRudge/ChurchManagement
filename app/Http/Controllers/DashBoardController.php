<?php

namespace App\Http\Controllers;

use App\Models\EagleMemberInfo;
use App\Models\FirstTimeVisitor;
use App\Models\Member;
use App\Models\MemberInfo;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashBoardController extends CommonController
{
    private $reportService;
    public function __construct(ReportService $service)
    {
        parent::__construct();
        $this->reportService = $service;
    }

    public function index(Request $request)
    {
        if(auth()->check()){
            $this->data['user'] = auth()->user();
        }
        $returnData = array();
        $returnData['category_name'] = '';
        $returnData['controller_name'] = 'Dashboard';
        $returnData['action_name'] = 'Index';
        $returnData['income_sources'] = [trans('common.tides_label'),trans('common.collections_label'),trans('common.seeds_label')];
        $returnData['total_members'] = Member::count();
        $returnData['total_tides'] = $this->reportService->getTotalSeedsPerSeedType(config('constants.SEED_TYPE_TIDE'));
        $returnData['total_seeds'] = $this->reportService->getTotalSeedsPerSeedType(config('constants.SEED_TYPE_SPECIAL_SEED'));
        $returnData['total_converts'] = $this->reportService->getTotalConverts();
        $returnData['total_offerings'] =  $this->reportService->getTotalOfferings();
        $returnData['months'] = $this->reportService->getAllMonths();
        $returnData['tides_chart_data'] = $this->reportService->getChartDataPerSeedType(1);
        $returnData['seed_chart_data'] = $this->reportService->getChartDataPerSeedType(2);
        $returnData['collection_chart_data'] = $this->reportService->getCollectionsChartData();
        //  dd($returnData);
        return view('dashboard.index')->with('data',$returnData);
    }

    public function JWDashboard(Request $request){
        if(auth()->check()){
            $this->data['user'] = auth()->user();
        }
        $this->data['total_jw_members'] = $this->reportService->getTotalYouthMembers();
        $this->data['total_members_in_group'] = EagleMemberInfo::count();
        $this->data['new_people_this_week'] = $this->reportService->getVisitorsThisWeek();

        $this->data['controller_name'] = 'dashboard';
        $this->data['action_name_'] = 'reports';
        $this->data['category_name'] = 'joshua warriors';
        $this->data['eagle_data'] = $this->reportService->getEagleGroupAbsentChartData();
        $this->data['bar_chart_data'] = $this->reportService->getVisitorsChartData();
        return view('dashboard.JW')->with('data',$this->data);
    }

    public function financeDashboard(Request $request){
        $returnData = array();
    }
}
