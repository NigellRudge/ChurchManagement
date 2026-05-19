<?php


namespace App\Services;


use App\Models\Currency;
use App\Models\CurrencyHistory;
use App\Models\CurrencyHistoryInfo;
use Illuminate\Support\Facades\DB;

class CurrencyService
{

    public function getAllCurrencies(array $data = null){
        $items = Currency::select('id',
            'name',
            'code',
            'exchange_rate',
            'active',
            DB::raw('CASE WHEN active = 1 THEN "Active" ELSE "In-active" END  as status'))->orderBy('id');;
        return $items;
    }

    public function updateCurrency($currencyId, array $data){
        try {
            $currency = Currency::find($currencyId);
            if($currency->exchange_rate !== $data['exchange_rate']){
                DB::transaction(function() use($currency,$data){
                    CurrencyHistory::where('currency_id','=',$currency->id)->update(['end_date' => now()->toDateTimeString()]);
                    CurrencyHistory::insert([
                        'currency_id' => $currency->id,'rate'=>$data['exchange_rate'],'start_date' => now()->toDateTimeString()
                    ]);
                });
            }
            $currency->update($data);
            return true;
        }
        catch (\Exception $exception){
            return false;
        }
    }

    public function getCurrencyHistory($currencyId){
        $items = CurrencyHistoryInfo::where('currency_id','=',$currencyId)->select('*');
        return $items;
    }
}
