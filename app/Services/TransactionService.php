<?php


namespace App\Services;


use App\Models\Transaction;
use App\Models\TransactionInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionService
{

    public function getAllTransaction(array $filterOptions){
        $items = TransactionInfo::select('*');
        if(isset($filterOptions['from_date'])){
            $items->where('transaction_date','>=',Carbon::parse($filterOptions['from_date']));
        }
        if(isset($filterOptions['to_date'])){
            $items->where('transaction_date','<=',Carbon::parse($filterOptions['to_date']));
        }
        if(isset($filterOptions['account_id']) && $filterOptions['account_id'] != 0){
            $items->where('account_id','=',$filterOptions['account_id']);
        }
        if(isset($filterOptions['currency_id']) && $filterOptions['currency_id'] != 0){
            $items->where('currency_id','=',$filterOptions['currency_id']);
        }
        if(isset($filterOptions['min_amount']) && $filterOptions['min_amount'] > 0.0){
            $items->where('amount','>=',($filterOptions['min_amount'] * 100));
        }
        if(isset($filterOptions['max_amount']) && $filterOptions['max_amount'] > 0.0){
            $items->where('amount','<=',($filterOptions['max_amount'] * 100));
        }
        if(isset($filterOptions['tran_type']) && $filterOptions['tran_type'] != 0){
            $items->where('tran_type','=', $filterOptions['tran_type']);
        }

        return $items;
    }

    public function addTransaction(array $data){
//        if($data['type_id'] == config('constants.TRANSACTION_TYPE_DEBIT')){
//            $data['debit'] = $data['amount'];
//            $data['credit'] = 0.00;
//        }
//        else {
//            $data['credit'] = $data['amount'];
//            $data['debit']  = 0.00;
//        }
//        if(isset($data['amount'])){
//            unset($data['amount']);
//        }
//        if(isset($data['type_id'])){
//            unset($data['type_id']);
//        }
        $data['created_by'] = auth()->user()->id;
        $transaction = Transaction::create($data);
        if(isset($data['attachment'])){
            $file = $data['attachment'];
            if(!file_exists('upload/transaction_files/')){
                mkdir('upload/transaction_files/', 0777, true);
            }

            $file_name = $file->getClientOriginalName();
            $file_name = str_replace(' ', '_', $file_name);
            $file_name = preg_replace('/[^A-Za-z0-9.\-]/', '', $file_name);
            $file_name = $transaction->id . '_' . $transaction->description . '_' . $file_name;
            $destination = 'upload/transaction_files/';
            $file->move($destination,$file_name);
            $transaction->attachment = $file_name;
            $transaction->save();
        }
        return true;
    }

    public function editTransaction($transactionId, array $data){
//        if($data['type_id'] == config('constants.TRANSACTION_TYPE_DEBIT')){
//            $data['debit'] = $data['amount'];
//            $data['credit'] = 0.00;
//        }
//        else {
//            $data['credit'] = $data['amount'];
//            $data['debit']  = 0.00;
//        }
//        if(isset($data['amount'])){
//            unset($data['amount']);
//        }
//        if(isset($data['type_id'])){
//            unset($data['type_id']);
//        }
        unset($data['transaction_id']);
        $transaction = Transaction::find($transactionId);
        $transaction->update($data);
        if(isset($data['attachment'])){
            if(isset($transaction->attachment)){
                unlink('upload/transaction_files/' . $transaction->attachment);
                $transaction->attachment = null;
                $transaction->save();
            }
            else{
                $file = $data['attachment'];
                if(!file_exists('upload/transaction_files/')){
                    mkdir('upload/transaction_files/', 0777, true);
                }
                $file_name = $file->getClientOriginalName();
                $file_name = str_replace(' ', '_', $file_name);
                $file_name = preg_replace('/[^A-Za-z0-9.\-]/', '', $file_name);
                $file_name = $transaction->id . '_' . $transaction->description . '_' . $file_name;
                $destination = 'upload/transaction_files/';
                $file->move($destination,$file_name);
                $transaction->attachment = $file_name;
                $transaction->save();
            }
        }
        return true;
    }

    public function deleteTransaction($id){
        $transaction = Transaction::find($id);
        if(isset($transaction->attachment)){
            unlink('upload/transaction_files/' . $transaction->attachment);
        }
        $transaction->delete();
        return true;
    }

    public function getTotalTransactionAmountForYear(array $data = null){

    }

    public function getTotalTransactionAmountForYearPerMonth(array $data = null){

    }

    public function getTotalTransactionAmountForMonth(array $data = null){

    }

    public function importTransactions($data){

    }

    public function exportTransactions($data){

    }
}
