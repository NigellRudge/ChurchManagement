<?php


namespace App\Services;


use App\Models\MemberInfo;
use App\Models\Seed;
use App\Models\SeedInfo;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\DocBlock\Tags\See;

class SeedsService
{
    private $transactionService;
    public function __construct(TransactionService $service)
    {
        $this->transactionService = $service;
    }

    public function getSeeds(array $data){
        $results = SeedInfo::select('id','member','amount','date',
                                'title','currency','member_id', 'amount_in_base_currency',
                        'type_id','image','member_type','gender_id');
        if(isset($data['currency_id']) && $data['currency_id'] != 0){
            $results = $results->where('currency_id',$data['currency_id']);
        }
        if(isset($data['from_date'])){
            $results = $results->whereDate('date','>=',Carbon::parse($data['from_date'])->toDateString());
        }
        if(isset($data['to_date'])){
            $results = $results->whereDate('date','<=',Carbon::parse($data['to_date'])->toDateString());
        }
        if(isset($data['member_id'])){
            $results = $results->where('member_id',$data['member_id']);
        }
        if(isset($data['typeId'])&& $data['typeId'] != 0){
            $results = $results->where('type_id',$data['typeId']);
        }
        return $results;
    }

    public function getSeedInfo($seedId){
        try {
            $seed = Seed::find($seedId);
            return $seed;
        }
        catch (Exception $exception){
            return false;
        }
    }

    public function destroySeed($seedId):int{
        try {
            $seed = Seed::find($seedId);
            DB::transaction(function() use($seed){
                $transaction = Transaction::where('seed_id','=',$seed->id)->select('*')->first();
                $transaction->delete();
                $seed->delete();
            });
            return true;
        }
        catch (Exception $exception){
            return false;
        }

    }

    public function updateSeed($seedId,$data){
        try {
            DB::transaction(function()use($seedId,$data){
                $seed = Seed::find($seedId);
                $seed->update($data);
                $member = MemberInfo::find($data['member_id']);
                $transaction = Transaction::where('seed_id','=',$seed->id)->select('*')->first();
                $transactionsData = array();
                $transactionsData['transaction_date'] = $data['date'];
                $transactionsData['amount'] = $data['amount'];
                $transactionsData['description'] = $member->name .': ' . $data['title'];
                $transactionsData['seed_id'] = $seed->id;
                if($data['seed_type_id'] == config('constants.SEED_TYPE_TIDE')){
                    switch ($data['currency_id']){
                        case 1:
                            $transactionsData['account_id'] = 8;
                            break;
                        case 2:
                            $transactionsData['account_id'] = 10;
                            break;
                        case 3:
                            $transactionsData['account_id'] = 12;
                            break;
                    }
                }
                if($data['seed_type_id'] == config('constants.SEED_TYPE_SPECIAL_SEED')){
                    switch ($data['currency_id']){
                        case 1:
                            $transactionsData['account_id'] = 9;
                            break;
                        case 2:
                            $transactionsData['account_id'] = 11;
                            break;
                        case 3:
                            $transactionsData['account_id'] = 13;
                            break;
                    }
                }
                $this->transactionService->editTransaction($transaction->id,$transactionsData);
            });

            return true;
        }
        catch (Exception $exception){
            return false;
        }

    }

    public function createSeed($data){
//        try {
            DB::transaction(function () use($data){
                $seed = Seed::create($data);
                $member = MemberInfo::find($data['member_id']);
                $transactionsData = array();
                $transactionsData['transaction_date'] = $data['date'];
                $transactionsData['amount'] = $data['amount'];
                //$transactionsData['type_id'] = config('constants.TRANSACTION_TYPE_DEBIT');
                $transactionsData['description'] = $member->name .': ' . $data['title'];
                $transactionsData['seed_id'] = $seed->id;
                if($data['seed_type_id'] == config('constants.SEED_TYPE_TIDE')){
                    switch ($data['currency_id']){
                        case 1:
                            $transactionsData['account_id'] = 8;
                            break;
                        case 2:
                            $transactionsData['account_id'] = 10;
                            break;
                        case 3:
                            $transactionsData['account_id'] = 12;
                            break;
                    }
                }
                if($data['seed_type_id'] == config('constants.SEED_TYPE_SPECIAL_SEED')){
                    switch ($data['currency_id']){
                        case 1:
                            $transactionsData['account_id'] = 9;
                            break;
                        case 2:
                            $transactionsData['account_id'] = 11;
                            break;
                        case 3:
                            $transactionsData['account_id'] = 13;
                            break;
                    }
                }
                if($data['seed_type_id'] == config('constants.SEED_TYPE_BUILDING')){
                    switch ($data['currency_id']){
                        case 1:
                            $transactionsData['account_id'] = 17;
                            break;
                        case 2:
                            $transactionsData['account_id'] = 18;
                            break;
                        case 3:
                            $transactionsData['account_id'] = 19;
                            break;
                    }
                }
                $this->transactionService->addTransaction($transactionsData);
            });

            return true;
//        }
//        catch (Exception $exception){
//            return false;
//        }

    }
}
