<?php


namespace App\Services;



use App\Models\Budget;
use App\Models\BudgetInfo;
use App\Models\BudgetItem;
use App\Models\BudgetItemInfo;

class BudgetService
{
    public function getAll(array $filterOptions){
        $items = BudgetInfo::select('*');
        if(isset($filterOptions['maxAmount']) && $filterOptions['maxAmount']> 0){
            $items->where('total_amount','<=',($filterOptions['maxAmount'] * 100));
        }
        if(isset($filterOptions['minAmount']) && $filterOptions['minAmount']> 0){
            $items->where('total_amount','>=',($filterOptions['minAmount'] * 100));
        }
        if(isset($filterOptions['from_date'])){
            $items->where('created_at','>=',$filterOptions['from_date']);
        }
        if(isset($filterOptions['to_date'])){
            $items->where('created_at','<=',$filterOptions['to_date']);
        }
        return $items;
    }

    public function createBudget(array $data)
    {
        $data['covered'] = false;
        $data['created_by'] = auth()->user()->id;
        try {
            $budget = Budget::create($data);
            $budget->save();
            return true;
        }
        catch (\Exception $exception){
            return false;
        }
    }

    public function deleteBudget($budgetId){
        try {
            $budget  = Budget::find($budgetId);
            $budget->delete();
            return true;
        }
        catch (\Exception $exception){
            return false;
        }
    }

    public function updateBudget(array $data){
        try {
            $budget = Budget::find($data['budget_id']);
            $budget->name = $data['name'];
            $budget->description = $data['description'];
            $budget->date = $data['date'];
            $budget->save();
            return true;
        }
        catch (\Exception $exception){
            return false;
        }
    }

    public function getBudgetItems($budgetId, array $filterOptions){
        $items = BudgetItemInfo::where('budget_id','=',$budgetId)->select('*');
        if(isset($filterOptions['to_amount']) && $filterOptions['to_amount'] > 0){
            $items->where('amount','<=',$filterOptions['to_amount']);
        }
        if(isset($filterOptions['from_amount']) && $filterOptions['from_amount'] > 0){
            $items->where('amount','>=',$filterOptions['to_amount']);
        }
        return $items;
    }

    public function addItemToBudget(array $data){
        try {
            $data['created_by'] = auth()->user()->id;
            $item = BudgetItem::create($data);
            $item->save();
            return true;
        }
        catch (\Exception $exception){
            return false;
        }
    }

    public function updateBudgetItem($itemId,array $data){
//        try {
            $item = BudgetItem::find($itemId);
            $item->update($data);
            $item->save();
            return true;
//        }
//        catch (\Exception $exception){
//            return false;
//        }
    }

    public function removeItemFromBudget($itemId){
        try {
            $item = BudgetItem::find($itemId);
            $item->delete();
            return true;
        }
        catch (\Exception $exception){
            return false;
        }
    }



    public function resolveBudgetItem($itemId, array $data){

    }

}
