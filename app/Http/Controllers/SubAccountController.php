<?php

namespace App\Http\Controllers;

use App\Exports\AccountTransactionExport;
use App\Exports\FinanceOverReportExport;
use App\Exports\TestExport;
use App\Models\MainAccountInfo;
use App\Models\SubAccountInfo;
use App\Services\AccountService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class SubAccountController extends CommonController
{
    private $accountService;
    public function __construct(Accountservice $s)
    {
        parent::__construct();
        $this->accountService = $s;
        $this->data['category_name'] = 'Finance';
        $this->data['controller_name'] = 'Sub Accounts';
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request){
        if($request->ajax()){
            return DataTables::of($this->accountService->getSubAccount($request->all()))
                ->addColumn('actions', function ($row){
                    $showRoute = route('sub-accounts.show',['account' => $row->id]);
                    if($row->can_delete){
                        if($row->status == 1){
                            return "<a class='btn btn-primary rounded btn-xs text-white font-weight-bold mr-1' href='$showRoute' data-id ='$row->id' data-name='$row->name' >
                                <i class='fa fa-eye' data-id ='$row->id' data-name='$row->name' ></i>
                            </a>"
                                ."<a class='btn-teal btn btn-xs rounded text-white font-weight-bold mr-1' data-id ='$row->id' data-name='$row->name' onclick='editAccount(event)' >
                                <i class='fa fa-edit' data-id ='$row->id' data-name='$row->name' ></i>
                             </a>"
                                ."<a class='btn btn-xs rounded text-light  font-weight-bold mr-1' data-id ='$row->id' data-name='$row->name' onclick='deactivateAccount(event)' style='background-color: #fd7400'>
                                <i class='fa fa-trash' data-id ='$row->id' data-name='$row->name' ></i>
                            </a>"
                                ;
                        }
                        return "<a class='btn btn-primary rounded btn-xs text-white font-weight-bold mr-1' href='$showRoute' data-id ='$row->id' data-name='$row->name' >
                                <i class='fa fa-eye' data-id ='$row->id' data-name='$row->name' ></i>
                            </a>"
                            ."<a class='btn-teal btn btn-xs rounded text-white font-weight-bold mr-1' data-id ='$row->id' data-name='$row->name' onclick='editAccount(event)' >
                                <i class='fa fa-edit' data-id ='$row->id' data-name='$row->name' ></i>
                             </a>"
                            ."<a class='btn-info btn btn-xs rounded text-white font-weight-bold mr-1' data-id ='$row->id' data-name='$row->name' onclick='reactivateAccount(event)'>
                                <i class='fa fa-lightbulb' data-id ='$row->id' data-name='$row->name' ></i>
                             </a>"
                            ."<a class='btn-danger btn btn-xs rounded text-white  font-weight-bold mr-1' data-id ='$row->id' data-name='$row->name' onclick='deleteAccount(event)'>
                                <i class='fa fa-trash' data-id ='$row->id' data-name='$row->name' ></i>
                            </a>"
                            ;
                    }
                    return "<a class='btn btn-primary rounded btn-xs text-white font-weight-bold mr-1' href='$showRoute' data-id ='$row->id' data-name='$row->name' >
                                <i class='fa fa-eye' data-id ='$row->id' data-name='$row->name' ></i>
                            </a>";


                })
                ->addColumn('balance_info', function($row){
                    $value = number_format($row->balance/100,2,'.',',');
                    $style = $row->account_type == config('constants.MAIN_ACCOUNT_TYPE_INCOME') ? 'text-success': 'text-danger';
                    $sign = $row->account_type == config('constants.MAIN_ACCOUNT_TYPE_INCOME') ?  '+': '-';
                    return "<span class='$style font-weight-bold'>$sign$</span>$value";
                })
                ->addColumn('account_type_info', function($row){
                    $row->account_type == 1 ? trans('common.income_type_account'): trans('common.expense_type_account') ;
                    return $row->account_type == 1 ? trans('common.income_type_account'): trans('common.expense_type_account') ;;
                })

                ->addcolumn('status_info', function ($row){
                    $value = $row->status  == 1 ? 'Active' : 'In-active';
                    $status = $row->status == 1 ? $row->status : 0;
                    return $this->getItemStatusColumn($status,$value);
                })
                ->rawColumns(['actions','balance_info','status_info'])
                ->make(true);
        }
        $this->data['main_accounts'] = MainAccountInfo::select("id",DB::raw("CONCAT(name, '(',currency,')') AS 'name'"))->get();
        return view('subaccounts.index')->with('data',$this->data);
    }

    public function show(Request $request, SubAccountInfo $account){
        $this->data['account'] = $account;
        $this->data['balance'] = $account['sum_debit'] -$account['sum_credit'];
        $this->data['sign'] = $this->data['balance'] < 0 ? '-'  : '+';
        return view('subaccounts.show')->with('data',$this->data);
    }

    public function store(Request $request){
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'parent_account_id' => 'required',
            'can_delete' => 'required'
        ]);
        $result = $this->accountService->StoreSubAccount($data);
        if($result){
            return response()->json(['message' => trans('common.record_stored_label')],200);
        }
        return response()->json(['message' => trans('common.general_error')],500);

    }

    public function deactivate(Request $request){
        $accountId = $request['account_id'];
        if($accountId <=17){
            return response()->json(['message' => trans('common.general_error')],500);
        }
        $result = $this->accountService->deactivateSubAccount($accountId);
        if($result){
            return response()->json(['message' => trans('common.record_stored_label')],200);
        }
        return response()->json(['message' => trans('common.general_error')],500);
    }

    public function reactivate(Request $request){
        $accountId = $request['account_id'];
        $result = $this->accountService->reactivateSubAccount($accountId);
        if($result){
            return response()->json(['message' => trans('common.record_stored_label')],200);
        }
        return response()->json(['message' => trans('common.general_error')],500);
    }

    public function update(Request $request){
        $data = $request->validate([
            'account_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'parent_account_id' => 'required',
            'can_delete' => 'required'
        ]);
        $result = $this->accountService->updateSubAccount($data);
        if($result){
            return response()->json(['message' => trans('common.record_stored_label')],200);
        }
        return response()->json(['message' => trans('common.general_error')],500);
    }

    public function destroy(Request $request){
        $accountId = $request['account_id'];
        if($accountId <= 17){
            return response()->json(['message' => trans('common.general_error')],500);
        }
        $result = $this->accountService->deleteSubAccount($accountId);
        if($result){
            return response()->json(['message' => trans('common.record_deleted_label')],200);
        }
        return response()->json(['message' => trans('common.general_error')],500);

    }

    public function getById(Request $request){
        $id = $request['account_id'];
        return response()->json(['account' => SubAccountInfo::find($id)],200);
    }

    public function accountList(Request $request){
        $term = $request['name'];
        $page = $request['page'] ?? null;
        $accountType = $request['account_type'] ?? null;
        $showInActive = $request['show_in_active'] ?? null;
        $resultCount = 10;
        $offset = ($page-1) * $resultCount;

        $items = SubAccountInfo::select("id",DB::raw("CONCAT(name,' ','(',currency, ')') AS 'text'"))->whereNull('deleted_at')->orderBY('name');
        if($term != null){
            $items->where('name','like',"%$term%");
        }
        if($accountType != null && $accountType != 0){
            $items->where('account_type','=',$accountType);
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

    public function exportData(Request $request){
        $id = $request['account_id'];
        $account = SubAccountInfo::find($id);
        $name = 'Account_transaction_report:' . $account->name;
        $data = $request->all();
        $data['account'] = $account;
        return Excel::download(new AccountTransactionExport($name,$data),$name . '.xlsx');
    }
    public function checkBalance(Request $request){
        $account = SubAccountInfo::find($request['account_id']);
        $data = [
            'balance' => $account['balance'],
            'sum_credit' => $account['sum_credit'],
            'sum_debit' => $account['sum_debit']
        ];
        return response()->json(['data' =>$data],200);
    }

    public function exportFinanceOverview(Request $request){
        $data = $request->all();
        $title = trans('common.finance_overview_report');
        $name  = $title . '.xlsx';
        return Excel::download(new FinanceOverReportExport($title,$data), $name);
    }
}
