<?php

namespace App\Http\Controllers;

use App\Exports\TransactionOverviewExport;
use App\Models\MemberFile;
use App\Models\SubAccountInfo;
use App\Models\Transaction;
use App\Models\TransactionInfo;
use App\Services\TransactionService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use function Symfony\Component\Translation\t;

class TransactionsController extends CommonController
{
    private $transService;
    public function __construct(TransactionService $s)
    {
        parent::__construct();
        $this->data['category_name'] = 'Finance';
        $this->data['controller_name'] = 'Transactions';
        $this->transService = $s;
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request){
        if($request->ajax()){
            return DataTables::of($this->transService->getAllTransaction($request->all()))
                ->addColumn('actions', function ($row){
                    $actionsAvailable = ($row->seed_id == null) && ($row->offering_id == null);
                    $downloadButton = "";
                    if(isset($row->attachment)){
                        $downloadRoute = route('transactions.downloadAttachment',['transaction' =>$row->id]);
                        $downloadButton = "<a href='$downloadRoute'  class='btn btn-xs rounded-lg btn-info mr-1 ' data-id='$row->id' data-file_name='$row->file_name' title='$row->attachment'>
                             <i class='fas fa-download download_member_file' data-id='$row->id' data-file_name='$row->file_name'></i>
                            </a>";
                    }
                    if($actionsAvailable){
                        return "<a class='btn btn-primary rounded btn-xs text-white font-weight-bold mr-1' data-id ='$row->id' data-name='$row->name' onclick='transactionInfo(event)'>
                            <i class='fa fa-eye' data-id ='$row->id' data-name='$row->name' ></i>
                        </a>"
                            ."<a class='btn-teal btn btn-xs rounded text-white font-weight-bold mr-1' data-id ='$row->id' data-name='$row->name' onclick='editTransaction(event)' >
                            <i class='fa fa-edit' data-id ='$row->id' data-name='$row->name' ></i>
                         </a>"
                            . $downloadButton
                            ."<a class='btn-danger btn btn-xs rounded text-white  font-weight-bold mr-1' data-id ='$row->id' data-description='$row->description' onclick='deleteTransaction(event)'>
                            <i class='fa fa-trash' data-id ='$row->id' data-description='$row->description' ></i>
                        </a>"
                            ;
                    }
                    return "<a class='btn btn-primary rounded btn-xs text-white font-weight-bold mr-1' data-id ='$row->id' data-name='$row->name' onclick='transactionInfo(event)'>
                            <i class='fa fa-eye' data-id ='$row->id' data-name='$row->name' ></i>
                        </a>";
                })
                ->addColumn('tran_type_info', function ($row){
                    $value = $row->tran_type == config('constants.TRAN_TYPE_INCOME') ? trans('common.income') : trans('common.expense');
                    $class = $row->tran_type == config('constants.TRAN_TYPE_INCOME') ? 'fa-arrow-up text-teal' : 'fa-arrow-down text-danger';
                    return "<span class='d-flex flex-row'><i class='fa $class mr-1'></i>$value</span>";
                })
                ->addColumn('amount_info', function ($row){
                    $class = $row->tran_type == config('constants.TRAN_TYPE_INCOME') ? 'text-success' :'text-danger';
                    return /*$row->tran_type == config('constants.TRAN_TYPE_INCOME') ?*/
                        "<span class='$class font-weight-bold'><span class='text-secondary'>$row->currency</span> $</span>$row->amount";
//                        "<span class='text-danger font-weight-bold'><span class='text-secondary'>$row->currency</span>  $</span>$row->credit";
                })

                ->addColumn('created_by_info',function ($row){
                    return $this->getCreatedByColumn($row->created_by);
                })
                ->rawColumns(['actions','tran_type_info','created_by_info','amount_info'])
                ->make(true);
        }
        return view('transactions.index')->with('data',$this->data);
    }

    public function store(Request $request){
        $data = $request->validate([
           'account_id' => 'required',
           'description' => 'required',
           'transaction_date' => 'required',
           //'type_id' => 'required',
           'amount' => 'required',
            'attachment' => 'file|max:2048'
        ]);

        if($request->hasFile('attachment')){
            $data['attachment'] = $request->file('attachment');
        }
        $result = $this->transService->addTransaction($data);
        if($result){
            return response()->json(['message' => trans('common.record_stored_label')],200);
        }
        return response()->json(['message' => trans('common.general_error')],500);
    }

    public function update(Request $request){
        $data = $request->validate([
            'transaction_id' => 'required',
            'account_id' => 'required',
            'description' => 'required',
            'transaction_date' => 'required',
            //'type_id' => 'required',
            'amount' => 'required',
            'attachment' => 'file|max:2048'
        ]);
        if($request->hasFile('attachment')){
            $data['attachment'] = $request->file('attachment');
        }
        $result = $this->transService->editTransaction($data['transaction_id'],$data);
        if($result){
            return response()->json(['message' => trans('common.record_stored_label')],200);
        }
        return response()->json(['message' => trans('common.general_error')],500);
    }

    public function delete(Request $request){
        $transactionId = $request['transaction_id'];
        $result = $this->transService->deleteTransaction($transactionId);
        if($result){
            return response()->json(['message' => trans('common.record_deleted_label')],200);
        }
        return response()->json(['message' => trans('common.general_error')],500);
    }

    public function getById(Request $request){
        $id = $request['transaction_id'];
        $transaction = TransactionInfo::find($id);
        $amount =  floatval($transaction->amount);
        return response()->json(['transaction' => $transaction,'amount' => $amount],200);
    }

    public function downloadTransactionAttachment(Transaction $transaction){
        return response()->download("upload/transaction_files/$transaction->attachment");
    }

    public function exportTransactionOverview(Request $request){
        $data = [
          'from_date' => $request['from_date'],
          'to_date' => $request['to_date'],
          'currency_id' => $request['currency_id'],
          'account_id' => $request['account_id']
        ];

        $name = trans('common.transaction_overview_report') . '_' . now()->toDateString();
        return Excel::download(new TransactionOverviewExport($name,$data),$name . '.xlsx');
    }

}
