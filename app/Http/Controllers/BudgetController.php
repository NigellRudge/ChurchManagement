<?php

namespace App\Http\Controllers;

use App\Exports\BudgetExport;
use App\Models\Budget;
use App\Models\BudgetInfo;
use App\Models\BudgetItem;
use App\Services\BudgetService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class BudgetController extends CommonController
{
    private $budgetService;
    public function __construct(BudgetService $service)
    {
        parent::__construct();
        $this->data['category_name'] = 'Finance';
        $this->data['controller_name'] = 'Budgets';
        $this->budgetService = $service;
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            return DataTables::of($this->budgetService->getAll($request->all()))
                ->addColumn('actions', function ($row){
                    $showUrl = route('budgets.show',['budget' => $row->id]);
                    return
                        "<a href='$showUrl' class='btn btn-primary btn-xs rounded-md mr-1'>
                            <i class='fa fa-eye'></i>
                        </a>".
                        "<a class='btn btn-xs btn-teal rounded-md mr-1' onclick='editBudget(event)' data-id='$row->id'>
                            <i class='fa fa-edit' data-id='$row->id'></i>
                        </a>".
                        "<a class='btn btn-xs btn-danger rounded-md mr-1' onclick='deleteBudget(event)' data-id='$row->id' data-name='$row->name'>
                            <i class='fa fa-trash' data-id='$row->id' data-name='$row->name'></i>
                        </a>";
                })
                ->addColumn('amount_info', function ($row){
                    return "<span class='text-success font-weight-bold' style='margin-right: 2px'>$</span>$row->total_amount";
                })
                ->addColumn('creator', function ($row){
                    return "<span class='d-flex flex-row'>
                                <i class='fa fa-user mr-1 text-teal'></i>
                                $row->creator
                            </span>";
                })
                ->rawColumns(['actions','amount_info','creator'])
                ->make(true);
        }
        return view('budget.index')->with('data',$this->data);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'date' => 'required'
        ]);
        $result = $this->budgetService->createBudget($data);
        if($result){
            return response()->json(['message'=> trans('common.record_stored_label')],200);
        }
        return response()->json(['message'=> trans('common.general_error')],401);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Budget $budget
     * @return Application|Factory|View
     * @throws Exception
     */
    public function show(Request $request, Budget $budget)
    {
        if ($request->ajax()){
            return DataTables::of($this->budgetService->getBudgetItems($budget->id, $request->all()))
                ->addColumn('actions', function ($row){
                    $showUrl = route('budgets.show',['budget' => $row->id]);
                    return
//                        "<a href='$showUrl' class='btn btn-primary btn-xs rounded-md mr-1'>
//                            <i class='fa fa-eye'></i>
//                        </a>".
                        "<a class='btn btn-xs btn-teal rounded-md mr-1' onclick='editItem(event)' data-id='$row->id'>
                            <i class='fa fa-edit' data-id='$row->id'></i>
                        </a>".
                        "<a class='btn btn-xs btn-danger rounded-md mr-1' onclick='deleteItem(event)' data-id='$row->id' data-name='$row->name'>
                            <i class='fa fa-trash' data-id='$row->id' data-name='$row->name'></i>
                        </a>";
                })
                ->addColumn('amount_info', function ($row){
                    return "<span class='text-success font-weight-bold' style='margin-right: 2px'> <span class='text-secondary'>$row->currency</span> $</span>$row->amount";
                })
                ->addColumn('amount_in_base_currency_info', function ($row){
                    return "<span class='text-success font-weight-bold' style='margin-right: 2px'> $</span>$row->amount_in_base_currency";
                })
                ->addColumn('creator', function ($row){
                    return "<span class='d-flex flex-row'>
                                <i class='fa fa-user mr-1 text-teal'></i>
                                $row->creator
                            </span>";
                })
                ->rawColumns(['actions','amount_info','amount_in_base_currency_info','creator'])
                ->make(true);
        }
        $this->data['budget'] = $budget;
        return view('budget.show')->with('data',$this->data);
    }



    public function update(Request $request)
    {
        $data = $request->validate([
           'budget_id' => 'required',
           'name' => 'required',
           'date' => 'required',
           'description' => 'required'
        ]);
        $result = $this->budgetService->updateBudget($data);
        if($result){
            return response()->json(['message'=> trans('common.record_stored_label') ],200);
        }
        return response()->json(['message'=> trans('common.general_error')],401);
    }


    public function destroy(Request $request)
    {
        $id = $request['id'];
        $result = $this->budgetService->deleteBudget($id);
        if($result){
            return response()->json(['message'=> trans('common.record_deleted_label') ],200);
        }
        return response()->json(['message'=> trans('common.general_error')],401);
    }

    public function getById(Request $request){
        $id = $request['id'];
        $budget = BudgetInfo::find($id);
        if($budget){
            return response()->json(['budget'=> $budget ],200);
        }
        return response()->json(['message'=> trans('common.general_error')],401);
    }

    public function addItem(Request $request){
        $data = $request->validate([
            'budget_id' => 'required',
            'currency_id' => 'required',
            'amount' => 'required',
            'description' => 'required',
            'name' => 'required'
        ]);
        $result = $this->budgetService->addItemToBudget($data);
        if($result){
            return response()->json(['message'=> trans('common.record_stored_label') ],201);
        }
        return response()->json(['message'=> trans('common.general_error')],401);
    }

    public function removeItem(Request $request){
        $data = $request->validate([
            'budget_id' => 'required',
            'expense_id' => 'required'
        ]);
        $result = $this->budgetService->removeItemFromBudget($data['expense_id']);
        if($result){
            return response()->json(['message'=> trans('common.record_stored_label') ],201);
        }
        return response()->json(['message'=> trans('common.general_error')],401);
    }

    public function getItemById(Request $request){
        $id = $request['item_id'];
        $budget = BudgetItem::find($id);
        if($budget){
            return response()->json(['item'=> $budget ],201);
        }
        return response()->json(['message'=> trans('common.general_error')],401);
    }

    public function updateItem(Request $request){
        $data = $request->validate([
            'budget_id' => 'required',
            'currency_id' => 'required',
            'amount' => 'required',
            'description' => 'required',
            'name' => 'required'
        ]);
        $result = $this->budgetService->updateBudgetItem($request['item_id'],$data);
        if($result){
            return response()->json(['message'=> trans('common.record_stored_label') ],201);
        }
        return response()->json(['message'=> trans('common.general_error')],401);
    }

    public function exportBudget(Request $request, BudgetInfo $budget){
        $data = array();
        $data['title'] = $budget->name;
        $data['description'] = $budget->description;
        $data['created_date'] = $budget->created_at;
        $data['created_by'] = $budget->created_at;
        $data['created_by'] = $budget->creator;
        $data['budget_id'] = $budget->id;
        return Excel::download(new BudgetExport($data['title'],$data),$data['title'].'.xlsx');
    }
}
