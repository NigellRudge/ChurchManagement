<?php


namespace App\Services;


use App\Models\Offering;
use App\Models\OfferingInfo;
use App\Models\Transaction;
use App\utils\CustomUtils;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class OfferingService
{
    private $transactionService;
    public function __construct(TransactionService $service)
    {
        $this->transactionService = $service;
    }

    public function getOfferings(array $filterOptions){
        $offerings = OfferingInfo::select(['id','name','date','total_amount','counted_by', 'srd_amount','usd_amount','euro_amount']);
        if(isset($filterOptions['from_date'])){
            $from = Carbon::parse($filterOptions['from_date'])->toDateString();
            $offerings->whereDate('date','>=' ,$from);
        }
        if(isset($filterOptions['to_date'])){
            $to = Carbon::parse($filterOptions['to_date'])->toDateString();
            $offerings->whereDate('date','<=' ,$to);
        }
        return $offerings;
    }

    public function storeOffering(array $data){
        try {
            $data['counted_by'] = auth()->user()->name;
            DB::transaction(function()use($data){
                $offering = Offering::create($data);
                $transactionData = array();
                $transactionData['transaction_date'] = $data['date'];
                $transactionData['created_by'] = auth()->user()->id;
                //$transactionData['type_id'] = config('constants.TRANSACTION_TYPE_DEBIT');
                $transactionData['offering_id'] = $offering->id;
                if(isset($data['srd_amount']) && $data['srd_amount'] > 0){
                    $transactionData['account_id'] = 14;
                    $transactionData['amount'] = $data['srd_amount'];
                    $transactionData['description'] = $offering->name . ': SRD';
                    $this->transactionService->addTransaction($transactionData);
                }
                if(isset($data['usd_amount']) && $data['usd_amount'] > 0){
                    $transactionData['account_id'] = 15;
                    $transactionData['amount'] = $data['usd_amount'];
                    $transactionData['description'] = $offering->name . ': USD';
                    $this->transactionService->addTransaction($transactionData);
                }
                if(isset($data['euro_amount']) && $data['euro_amount'] > 0){
                    $transactionData['account_id'] = 16;
                    $transactionData['amount'] = $data['euro_amount'];
                    $transactionData['description'] = $offering->name . ': EUR';
                    $this->transactionService->addTransaction($transactionData);
                }
            });

            return true;
        }
        catch (Exception $exception){
            return false;
        }
    }

    public function updateOffering($offeringId, array $data){
        try {
            unset($data['edit_offering_id']);
            $data['counted_by'] = auth()->user()->name;
            DB::transaction(function()use($data,$offeringId){
                $offering = Offering::find($offeringId);
                $offering->update($data);
                if(isset($data['srd_amount'])) {
                    $srdTransaction = Transaction::where('offering_id','=',$offeringId)->where('account_id','=',14)->select('*')->first();
                    if($srdTransaction !== null){
                        $srdTransactionData = array();
                        $srdTransactionData['transaction_date'] = $data['date'];
                        $srdTransactionData['created_by'] = auth()->user()->id;
                        $srdTransactionData['offering_id'] = $offering->id;
                        $srdTransactionData['account_id'] = 14;
                        $srdTransactionData['amount'] = $data['srd_amount'];
                        $srdTransactionData['description'] = $offering->name . ': SRD';
                        $this->transactionService->editTransaction($srdTransaction->id,$srdTransactionData);
                    }
                }
                if(isset($data['usd_amount'])){
                    $usdTransaction = Transaction::where('offering_id','=',$offeringId)->where('account_id','=',15)->select('*')->first();
                    if($usdTransaction !== null){
                        $usdTransactionData = array();
                        $usdTransactionData['transaction_date'] = $data['date'];
                        $usdTransactionData['created_by'] = auth()->user()->id;
                        $usdTransactionData['offering_id'] = $offering->id;
                        $usdTransactionData['account_id'] = 15;
                        $usdTransactionData['amount'] = $data['usd_amount'];
                        $usdTransactionData['description'] = $offering->name . ': USD';
                        $this->transactionService->editTransaction($usdTransaction->id,$usdTransactionData);
                    }
                }
                if(isset($data['euro_amount'])){
                    $euroTransaction = Transaction::where('offering_id','=',$offeringId)->where('account_id','=',16)->select('*')->first();
                    if($euroTransaction !== null){
                        $euroTransactionData = array();
                        $euroTransactionData['transaction_date'] = $data['date'];
                        $euroTransactionData['created_by'] = auth()->user()->id;
                        $euroTransactionData['offering_id'] = $offering->id;
                        $euroTransactionData['account_id'] = 16;
                        $euroTransactionData['amount'] = $data['euro_amount'];
                        $euroTransactionData['description'] = $offering->name . ': EUR';
                        $this->transactionService->editTransaction($euroTransaction->id,$euroTransactionData);
                    }
                }
            });
            return true;
        }
        catch (Exception $exception){
            return false;
        }
    }

    public function getById($offeringId){
        $offering = DB::table('offering_info')->where('id','=',$offeringId)->select('*')->first();
        $offering->srd_amount = $offering->srd_amount / 100;
        $offering->usd_amount = $offering->usd_amount / 100;
        $offering->euro_amount = $offering->euro_amount / 100;
        $offering->date = CustomUtils::parseDate($offering->date);
        return $offering;
    }

    public function destroyOffering($offeringId){
        try {
            DB::transaction(function()use($offeringId){
                $offering = Offering::find($offeringId);
                $offering->delete();
                Transaction::where('offering_id','=',$offeringId)->delete();
            });
            return true;
        }
        catch (Exception $exception){
            return null;
        }

    }
}
