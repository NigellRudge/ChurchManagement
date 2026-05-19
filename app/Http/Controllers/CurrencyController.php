<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Services\CurrencyService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CurrencyController extends CommonController
{
    private $currencyService;
    public function __construct(CurrencyService $service)
    {
        parent::__construct();
        $this->currencyService = $service;
        $this->data['controller_name'] = 'Currency';
        $this->data['category_name'] = 'Config';
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
            return DataTables::of($this->currencyService->getAllCurrencies())
                ->addColumn('actions', function ($row){
                    if($row->id == 1){
                        return "<a class='btn-teal btn btn-sm rounded text-white font-weight-bold mr-1 ' onclick='openEditModal(event)' data-id='$row->id' data-name='$row->name'>
                                <i class='fa fa-edit' data-id='$row->id' data-name='$row->name'></i>
                            </a>";
                    }
                    return "<a class='btn-primary btn btn-sm rounded text-white font-weight-bold mr-1 ' onclick='openHistoryModal(event)' data-id='$row->id' data-name='$row->name'>
                                <i class='fa fa-eye' data-id='$row->id' data-name='$row->name'></i>
                              </a>"
                            ."<a class='btn-teal btn btn-sm rounded text-white font-weight-bold mr-1 ' onclick='openEditModal(event)' data-id='$row->id' data-name='$row->name'>
                                <i class='fa fa-edit' data-id='$row->id' data-name='$row->name'></i>
                            </a>";


                })
                ->addcolumn('status_info', function ($row){
                    $value = $row->active  == 1 ? trans('common.active_label') : trans('common.inactive_label');
                    $status = $row->active == 1 ? $row->active : 0;
                    return $this->getItemStatusColumn($status,$value);
                })
                ->rawColumns(['actions','status_info'])
                ->make(true);
        }
        $this->data['action_name'] = 'Index';
        return view('config.currency.index')->with('data',$this->data);
    }

    public function destroyAjax(Request $request){
        $currency = $request['remove_currency_id'];
        DB::table('currencies')
            ->where('id','=',$currency)
            ->delete();
        return response(['message'=>trans('common.record_deleted_label')],201);

    }

    public function storeAjax(Request $request){
        $request->validate([
            'name' => 'required|min:4|max:50',
            'code' => 'required|min:3|max:8',
            'exchange_rate' => 'required|min:0.01'
        ]);

        $currency = new Currency([
            'name' => $request['name'],
            'code' => $request['code'],
            'exchange_rate'=> $request['exchange_rate']
        ]);
        $currency->save();
        return response(['message'=>trans('common.record_stored_label')],201);
    }

    public function getByIdJson(Request $request){
        $currencyId = $request['currencyId'];
        $currency = DB::table('currencies')
                        ->where('id','=',$currencyId)
                        ->select('id','name','code','exchange_rate')
                        ->first();
        return response(['currency'=>$currency],201);
    }

    public function updateAjax(Request $request){
        $data = $request->validate([
            'name' => 'required|min:4|max:50',
            'code' => 'required|min:3|max:8',
            'exchange_rate' => 'required|min:0.01'
        ]);
        $result = $this->currencyService->updateCurrency($request['currency_id'],$data);
        if($result){
            return response()->json(['message' => trans('common.record_stored_label')],201);
        }
        return response()->json(['message'=>trans('common.general_error')],501);
    }

    public function getJson(Request $request){
        $term = $request['name'];
        $results = Currency::select(['id',DB::raw('code as text')])
            ->where('name', 'like', "%$term%")
            ->orWhere('code', 'like',"%$term%")->get();
        return response()->json([
            'results'=>$results
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function history(Request $request){
        $currencyId = $request['currency_id'];
        return DataTables::of($this->currencyService->getCurrencyHistory($currencyId))->make(true);
    }
}
