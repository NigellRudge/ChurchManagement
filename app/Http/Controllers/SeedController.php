<?php

namespace App\Http\Controllers;

use App\Exports\CovidRegistrationExport;
use App\Exports\SeedExport;
use App\Services\SeedsService;
use App\utils\CustomUtils;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class SeedController extends CommonController
{
    private $seedService;
    public function __construct(SeedsService $service)
    {
        parent::__construct();
        $this->seedService = $service;
        $this->data['controller_name'] = "Seeds";
        $this->data['action_name'] = "Index";
        $this->data['category_name'] = "Finance";

    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request){
        if($request->ajax()){
            return DataTables::of($this->seedService->getSeeds($request->all()))
                ->addColumn('actions',function($row){
                    return "<a class='btn btn-sm btn-teal rounded mr-1' href='#' onclick='editSeed(event)' data-id='$row->id'>
                                <i class='fa fa-edit' data-id='$row->id' onclick='editSeed(event)'></i>
                            </a>"
                        ."<a class='btn btn-sm rounded btn-danger' href='#' onclick='deleteSeed(event)'
                            data-id='$row->id'
                            data-date='$row->date'
                            data-member='$row->member'
                            data-amount='$row->amount'
                            data-title='$row->title'
                            data-currency='$row->currency'>
                                 <i class='fa fa-trash'
                                 data-id='$row->id'
                                data-date='$row->date'
                                data-member='$row->member'
                                data-amount='$row->amount'
                                data-title='$row->title'
                                data-currency='$row->currency'></i>
                            </a>";
                })
                ->addColumn('amount_formatted',function($row){
                    return  "$row->currency <span class='text-success font-weight-bold'>$</span>$row->amount";
                })
                ->addColumn('base_currency_amount_formatted',function($row){
                    return  "<span class='font-weight-bold text-dark'>$</span>$row->amount_in_base_currency";
                })
                ->addColumn('type_info', function ($row){
                    switch ($row->type_id){
                        case config('constants.SEED_TYPE_TIDE'):
                            return trans('common.seed_type_tide');
                            break;
                        case config('constants.SEED_TYPE_SPECIAL_SEED'):
                            return trans('common.seed_type_special_seed');
                            break;
                        case config('constants.SEED_TYPE_BUILDING'):
                            return trans('common.building_seed');
                            break;
                    }
                })
                ->addColumn('member_info',function($row){
                    $image = "<img alt='member_image' src='$row->image' style='object-fit: cover;border-radius: 30px' width='50' height='50' />";
                    $nameContainer = "<div class='d-flex flex-column px-2 py-1'>
                                        <span class='font-weight-bold text-dark'>$row->member</span>
                                        <span class='font-weight-normal' style='font-size: 0.90rem;margin-top: 2px'>$row->member_type</span>
                                    </div>";
                    return "<div class='d-flex flex-row'>
                                $image
                                $nameContainer
                            </div>";
                })
                ->rawColumns(['actions','amount_formatted','base_currency_amount_formatted','member_info'])
                ->make(true);
        }
        $this->data['types'] = DB::table('seed_types')->select('id','name')->get();
        return view('seeds.index')->with('data',$this->data);
    }

    public function store(Request $request){
        $data = $request->validate([
            'seed_type_id' =>'required',
            'title'=> 'required',
            'member_id' => 'required',
            'date' => 'required',
            'currency_id' => 'required',
            'amount' => 'required|min:0.01'
        ]);
        $result = $this->seedService->createSeed($data);
        if($result){
            return response()->json(['message' => trans('common.record_stored_label')],201);
        }
        return response()->json(['message' => trans('common.general_error')],401);
    }

    public function update(Request $request){
        $data = $request->validate([
            'seed_type_id' =>'required',
            'title'=> 'required',
            'member_id' => 'required',
            'date' => 'required',
            'currency_id' => 'required',
            'amount' => 'required|min:0.01'
        ]);
        $result = $this->seedService->updateSeed($request['edit_seed_id'],$data);
        return response(['message'=>'Seed Updated'],201);
    }

    public function destroy(Request $request){
        $id = $request['remove_seed_id'];
        $result = $this->seedService->destroySeed($id);
        return response(['message' => "Seed Removed"],201);
    }

    public function getById(Request $request){
        $id = $request['seed_id'];
        $seed = $this->seedService->getSeedInfo($id);
        return response(['seed'=>$seed],200);
    }

    public function export(Request $request){
        $data = $request->all();
        $title = trans('common.seed_list_export'). '_' . Carbon::now()->toDateTimeString();
        $fileName = $title  . '.xlsx';
        return Excel::download(new SeedExport($title,$data),$fileName);
    }
}
