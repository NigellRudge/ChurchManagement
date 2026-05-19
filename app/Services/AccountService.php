<?php


namespace App\Services;


use App\Models\SubAccount;
use App\Models\SubAccountInfo;
use App\Models\MainAccount;
use App\Models\MainAccountInfo;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccountService
{
    private $transactionService;
    public function __construct(TransactionService $s)
    {
        $this->transactionService = $s;
    }

    public function getMainAccounts(array $filterOptions){
        $items = MainAccountInfo::select('*');
        if(isset($filterOptions['currency_id']) &&$filterOptions['currency_id'] != 0){
            $items->where('currency_id','=',$filterOptions['currency_id']);
        }
        if(isset($filterOptions['account_type']) &&$filterOptions['account_type'] != 0){
            $items->where('account_type','=',$filterOptions['account_type']);
        }
        if(isset($filterOptions['status']) &&$filterOptions['status'] != 0){
            if($filterOptions['status'] == 1){
                $items->whereNull('deleted_at');
            }
            if($filterOptions['status'] == 2){
                $items->whereNotNull('deleted_at');
            }

        }
        return $items;
    }

    public function storeMainAccount(array $data){
        $account = MainAccount::create([
            'name' =>$data['name'],
            'account_type' => $data['account_type'],
            'currency_id' => $data['currency_id'],
            'description' => $data['description']
        ]);
        return true;
    }
    public function updateMainAccount(array $data){
        $account = MainAccount::find($data['account_id']);
        $account->name = $data['name'];
        $account->account_type = $data['account_type'];
        $account->currency_id = $data['currency_id'];
        $account->description = $data['description'];
        $account->save();
        return true;
    }


    public function deactivateMainAccount($accountId){
        $account = MainAccount::find($accountId);
        $account->deleted_at = Carbon::now()->toDateTimeString();
        $account->save();
        return true;
    }

    public function reactivateMainAccount($accountId){
        $account = MainAccount::find($accountId);
        $account->deleted_at = null;
        $account->save();
        return true;
    }

    public function deleteMainAccount($accountId){
        try {
            $account = MainAccount::find($accountId);
            $account->delete();
            return true;
        }
        catch (\Exception $exception){
            return false;
        }

    }

    public function getSubAccount(array $filterOptions){
//        $tranTotals = DB::table('transactions')
//                        ->select(DB::raw("if(sum(amount) is null,0,sum(amount)) as 'total'"),'account_id')
//                        ->groupBy('account_id');
        $start = now()->firstOfYear();
        $end = now()->endOfYear();
        if(isset($filterOptions['from_date'])){
            $start = Carbon::parse($filterOptions['from_date'])->toDateString();
        }
        if(isset($filterOptions['to_date'])){
            $end = Carbon::parse($filterOptions['to_date'])->toDateString();
        }
//        if(isset($filterOptions['from_date'])){
//            $tranTotals->where('transaction_date' ,'>=',Carbon::parse($filterOptions['from_date'])->toDateString());
//        }
//        if(isset($filterOptions['to_date'])){
//            $tranTotals->where('transaction_date' ,'<=',Carbon::parse($filterOptions['to_date'])->toDateString());
//        }
        $tranTotals = DB::table('transactions')
            ->select(DB::raw("if(sum(amount) is null,0,sum(amount)) as 'total'"),'account_id')
            ->groupBy('account_id')
            ->where('transaction_date' ,'>=',$start)
            ->where('transaction_date' ,'<=',$end);

        $items = DB::table('sub_accounts')
                ->leftJoinSub($tranTotals,'tran_totals',function ($join){
                    $join->on('sub_accounts.id','=','tran_totals.account_id');
                })
                ->leftJoin(DB::raw('main_accounts ma'),'sub_accounts.parent_account_id','=','ma.id')
                ->leftJoin(DB::raw('currencies c'),'ma.currency_id','=','c.id')
                ->select('sub_accounts.id',
                    'sub_accounts.name',
                    'ma.currency_id',
                    'sub_accounts.can_delete',
                   //'sub_accounts.status',
                    DB::raw("c.code as 'currency'"),
                    DB::raw("ma.name as 'parent_account'"),
                    DB::raw("if(isnull(sub_accounts.deleted_at), 1, 0) AS 'status'"),
                    DB::raw("tran_totals.total as 'balance'"),
                    DB::raw("(tran_totals.total * c.exchange_rate) as 'balance_srd'"),
                    DB::raw("ma.account_type as 'account_type'")
                )
                /*->groupBy('sub_accounts.id')*/;
        $items->get();
        //dd($items->get());
        //$items = SubAccountInfo::select('*');
        if(isset($filterOptions['currency_id']) && $filterOptions['currency_id'] != 0){
            $items->where('currency_id','=',$filterOptions['currency_id']);
        }
        if(isset($filterOptions['account_type']) &&$filterOptions['account_type'] != 0){
            $items->where('account_type','=',$filterOptions['account_type']);
        }
        if(isset($filterOptions['status']) &&$filterOptions['status'] != 0){
            if($filterOptions['status'] == 1){
                $items->whereNull('sub_accounts.deleted_at');
            }
            if($filterOptions['status'] == 2){
                $items->whereNotNull('sub_accounts.deleted_at');
            }

        }
        return $items;
    }

    public function StoreSubAccount(array $data){
       // try {
            $mainAccount = MainAccount::find($data['parent_account_id']);
            $account = SubAccount::create($data);
            return true;
       // }
       // catch (\Exception $exception){
           // return false;
       // }
    }

    public function getInfo($accountId){

    }

    public function deactivateSubAccount($accountId){
        $subAccount = SubAccount::find($accountId);
        $subAccount->deleted_at = now()->toDateTimeString();
        $subAccount->save();
        return true;
    }
    public function reactivateSubAccount($accountId){
        $subAccount = SubAccount::find($accountId);
        $subAccount->deleted_at = null;
        $subAccount->save();
        return true;
    }


    public function deleteSubAccount($accountId){
        $subAccount = SubAccount::find($accountId);
        $subAccount->delete();
        return true;
    }

    public function updateSubAccount(array $data){
        $account = SubAccount::find($data['account_id']);
        $account->description = $data['description'];
        $account->name = $data['name'];
        $account->parent_account_id = $data['parent_account_id'];
        $account->can_delete = $data['can_delete'];
        $account->save();
        return true;
    }


}
