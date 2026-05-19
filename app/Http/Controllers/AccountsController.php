<?php

namespace App\Http\Controllers;

use App\Models\MainAccount;
use App\Models\MainAccountInfo;
use App\Services\AccountService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AccountsController extends CommonController
{

    private $accountService;
    public function __construct(AccountService $service)
    {
        parent::__construct();
        $this->accountService = $service;
        $this->data['category_name'] = 'Finance';
        $this->data['controller_name'] = 'Main Accounts';
    }


    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            return DataTables::of($this->accountService->getMainAccounts($request->all()))
                ->addColumn('actions', function ($row){
                    $showRoute = route('accounts.show',['account' => $row->id]);
                    if($row->active == 1){
                        return
                            /*"<a class='btn-teal btn btn-xs rounded text-white font-weight-bold mr-1' data-id ='$row->id' data-name='$row->name' onclick='editAccount(event)' >
                                <i class='fa fa-edit' data-id ='$row->id' data-name='$row->name' ></i>
                             </a>"
                            .*/
                            "<a class='btn-teal btn btn-xs rounded text-white font-weight-bold mr-1' data-id ='$row->id' data-name='$row->name' onclick='editAccount(event)' >
                                <i class='fa fa-edit' data-id ='$row->id' data-name='$row->name' ></i>
                             </a>"
                            ."<a class='btn btn-xs rounded text-light  font-weight-bold mr-1' data-id ='$row->id' data-name='$row->name' onclick='deactivateAccount(event)' style='background-color: #fd7400'>
                                <i class='fa fa-trash' data-id ='$row->id' data-name='$row->name' ></i>
                            </a>"
                            ;
                    }
                    return /*"<a class='btn btn-primary rounded btn-xs text-white font-weight-bold mr-1' href='$showRoute' data-id ='$row->id' data-name='$row->name' >
                                <i class='fa fa-eye' data-id ='$row->id' data-name='$row->name' ></i>
                            </a>"
                        .*/"<a class='btn-teal btn btn-xs rounded text-white font-weight-bold mr-1' data-id ='$row->id' data-name='$row->name' onclick='editAccount(event)' >
                                <i class='fa fa-edit' data-id ='$row->id' data-name='$row->name' ></i>
                             </a>"
                        ."<a class='btn-info btn btn-xs rounded text-white font-weight-bold mr-1' data-id ='$row->id' data-name='$row->name' onclick='reactivateAccount(event)'>
                                <i class='fa fa-lightbulb' data-id ='$row->id' data-name='$row->name' ></i>
                             </a>"
                        ."<a class='btn-danger btn btn-xs rounded text-white  font-weight-bold mr-1' data-id ='$row->id' data-name='$row->name' onclick='deleteAccount(event)'>
                                <i class='fa fa-trash' data-id ='$row->id' data-name='$row->name' ></i>
                            </a>"
                        ;

                })
                ->addColumn('balance_info', function($row){
                    $style = $row->account_type == config('constants.MAIN_ACCOUNT_TYPE_INCOME') ? 'text-success': 'text-danger';
                    $sign = $row->account_type == config('constants.MAIN_ACCOUNT_TYPE_INCOME') ? '+': '-';
                    //$value = number_format(abs($row->balance),2);
                    return "<span class='$style font-weight-bold'>$sign$</span>$row->balance";
                })
//                ->addColumn('credit_info', function($row){
//                    $value = number_format($row->sum_credit,2);
//                    return "<span class='text-danger font-weight-bold'>$</span>$value";
//                })
                ->addcolumn('status_info', function ($row){
                    $value = $row->active  == 1 ? 'Active' : 'In-active';
                    $status = $row->active == 1 ? $row->active : 0;
                    return $this->getItemStatusColumn($status,$value);
                })
//                ->addColumn('debit_info', function($row){
//                    $value = number_format($row->sum_debit,2);
//                    return "<span class='text-success font-weight-bold'>$</span>$value";
//                })
                ->rawColumns(['actions','balance_info'/*,'credit_info','debit_info'*/, 'status_info'])
                ->make(true);
        }
        return view('accounts.index')->with('data',$this->data);
    }




    public function store(Request $request)
    {
        $data= $request->validate([
           'name' => 'required',
           'account_type' => 'required',
           'currency_id' => 'required',
        ]);
        $data['description'] = $request['description'];
        $result = $this->accountService->storeMainAccount($data);
        if($result){
            return response()->json(['message' => trans('common.record_stored_label')],200);
        }
        return response()->json(['message' => trans('common.general_error')],500);
    }


    public function show(MainAccount $account){
        $this->data['account'] = $account;
        return view('accounts.show')->with('data',$this->data);
    }


    public function update(Request $request){
        $data= $request->validate([
            'account_id' => 'required',
            'name' => 'required',
            'account_type' => 'required',
            'currency_id' => 'required',
        ]);
        $data['description'] = $request['description'];
        $result = $this->accountService->updateMainAccount($data);
        if($result){
            return response()->json(['message' => trans('common.record_stored_label')],200);
        }
        return response()->json(['message' => trans('common.general_error')],500);
    }


    public function deactivate(Request $request){
        $id = $request['account_id'];
        $result = $this->accountService->deactivateMainAccount($id);
        if($result){
            return response()->json(['message' => trans('common.record_deleted_label')],200);
        }
        return response()->json(['message' => trans('common.general_error')],500);
    }

    public function destroy(Request $request){
        $id = $request['account_id'];
        $result = $this->accountService->deleteMainAccount($id);
        if($result){
            return response()->json(['message' => trans('common.record_deleted_label')],200);
        }
        return response()->json(['message' => trans('common.general_error')],500);
    }

    public function getById(Request $request){
        $id = $request['account_id'];
        $account = MainAccountInfo::find($id);
        return response()->json(['account' => $account],200);
    }

    public function reactivate(Request $request){
        $accountId = $request['account_id'];
        $result = $this->accountService->reactivateMainAccount($accountId);
        if($result){
            return response()->json(['message' => trans('common.record_stored_label')],200);
        }
        return response()->json(['message' => trans('common.general_error')],500);
    }

    public function accountList(Request $request){
        $term = $request['name'];
        $page = $request['page'] ?? null;
        $showInActive = $request['show_in_active'] ?? null;
        $resultCount = 10;
        $offset = ($page-1) * $resultCount;

        $items = MainAccountInfo::select("id",DB::raw("CONCAT(name,' ','(',currency, ')') AS 'text'"))->whereNull('deleted_at')->orderBY('name');
        if($term != null){
            $items->where('name','like',"%$term%");
        }
        if ($showInActive == null){
            $items->whereNull('deleted_at');
        }
        if($page != null){
            $items->skip($offset)->take($resultCount);
        }
        return response()->json([
            'results' => $items->get(),
            'total_items' => $items->count()
        ],200);
    }
}
