<?php

namespace App\Http\Controllers;

use App\Exports\OfferingExport;
use App\Exports\SeedExport;
use App\Models\Currency;
use App\Models\OfferingInfo;
use App\Services\OfferingService;
use App\Services\SeedsService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class OfferingController extends CommonController
{
    private $offeringService;
    public function __construct(OfferingService $service)
    {
        parent::__construct();
        $this->offeringService = $service;
        $this->data['controller_name'] = 'Offerings';
        $this->data['category_name'] = 'Finance';
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            return DataTables::of($this->offeringService->getOfferings($request->all()))
                ->addColumn('amount',function($row){
                    return "<span class='text-success font-weight-bold'>$</span>$row->total_amount";
                })
                ->addColumn('srd_amount_info',function($row){
                    return "<span class='text-success font-weight-bold'>$</span>$row->srd_amount";
                })
                ->addColumn('usd_amount_info',function($row){
                    return "<span class='text-success font-weight-bold'>$</span>$row->usd_amount";
                })
                ->addColumn('eur_amount_info',function($row){
                    return "<span class='text-success font-weight-bold'>$</span>$row->euro_amount";
                })
                ->addColumn('actions',function($row){
                    return "<a class='btn btn-sm btn-teal rounded mr-1' href='#' onclick='openEditModal(event)'
                                    data-id='$row->id' data-date='$row->date' data-amount='$row->total_amount' data-name='$row->name'>
                                <i class='fa fa-edit' data-id='$row->id' data-date='$row->date' data-amount='$row->total_amount' data-name='$row->name'></i>
                            </a>"
                            ."<a class='btn btn-sm btn-danger rounded' onclick='openRemoveModal(event)' data-id='$row->id' data-date='$row->date'  data-amount='$row->total_amount' data-name='$row->name'>
                                <i class='fa fa-trash' data-id='$row->id' data-date='$row->date'  data-amount='$row->total_amount' data-name='$row->name'></i>
                            </a>";
                })
                ->rawColumns(['actions','amount','srd_amount_info','usd_amount_info','eur_amount_info'])
                ->make(true);
        }
        $this->data['action_name'] = 'Index';
        $this->data['currency_srd'] = Currency::find(1);
        $this->data['currency_usd'] = Currency::find(2);
        $this->data['currency_euro'] = Currency::find(3);
        return view('offering.index')->with('data',$this->data);
    }


    public function storeAjax(Request $request){
        $data = $request->validate([
            'date' => 'required',
            'name' => 'required',
            'srd_amount' => 'required',
            'usd_amount' => 'required',
            'euro_amount' => 'required',
        ]);
        $result = $this->offeringService->storeOffering($data);
        if($result){
            return response(['message' => trans('common.record_stored_label')],200);
        }
        return response(['message' => trans('common.general_error')],401);
    }

    public function updateAjax(Request $request){
        $data = $request->validate([
            'edit_offering_id'=>'required',
            'date' => 'required',
            'name' => 'required',
            'srd_amount' => 'required',
            'usd_amount' => 'required',
            'euro_amount' => 'required',
        ]);

         $result = $this->offeringService->updateOffering($data['edit_offering_id'],$data);
         if($result != null){
             return response()->json(['message' => trans('common.record_stored_label')],201);
         }
        return response()->json(['message' => trans('common.general_error')],401);
    }

    public function getByIdAjax(Request $request){
        $id = $request['offering_id'];
        $offering = $this->offeringService->getById($id);
        return response()->json(['offering'=>$offering],201);
    }

    public function destroyAjax(Request $request){
        $id = $request['remove_offering_id'];
        $result = $this->offeringService->destroyOffering($id);
        if($result != null){
            return response(['message'=>trans('common.record_deleted_label')],201);
        }
        return response(['message'=>trans('common.general_error')],401);
    }

    public function export(Request $request){
        $data = [
            'from_date' => $request['from_date'],
            'to_date' => $request['to_date'],
        ];
        $name = trans('common.offering_export') . '_' .now()->toDateString();
        return Excel::download(new OfferingExport($name,$data),$name . '.xlsx');
    }
}
