<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class BookCategoryController extends CommonController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['controller_name'] = 'Book categories';
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request){
        if($request->ajax()){
            $categories = DB::table('book_category')->select('id','name');
            return DataTables::of($categories)
                ->addColumn('actions', function ($row){
                    return
                        "<a class='btn btn-sm btn-danger text-white rounded font-weight-bold mr-1 text-xs' data-id='$row->id' data-name='$row->name' onclick='openRemoveModal(event)'>
                            <i class='fa fa-trash'></i>
                            remove
                         </a>"
                        ."<a class='btn btn-sm btn-primary text-white rounded font-weight-bold mr-1 text-xs' data-id='$row->id' data-name='$row->name'  onclick='openEditModal(event)'>
                            <i class='fa fa-edit'></i>
                            edit
                         </a>";

                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('config.categories.index')->with('data',$this->data);
    }

    public function store(Request $request){
        $data = $request->validate([
            'name' => 'required|min:4|max:50'
        ]);
        $result = DB::table('book_category')->insert([$data]);
        return response(['message'=>'Category saved'],201);

    }

    public function update(Request $request){
        $data = $request->validate([
           'category_id' => 'required',
           'name' => 'required'
        ]);

        $result = DB::table('book_category')->where('id',$data['category_id'])->update([
            'name' => $data['name']
        ]);

        return response(['message' => 'Category updated'],201);
    }

    public function destroy(Request $request){
        $data = $request->validate([
            'category_id' => 'required'
        ]);

        $result = DB::table('book_category')->where('id',$data['category_id'])->delete();
        return response(['message' => 'Category removed'],201);
    }

    public function getById(Request $request){

    }

    public function getList(Request $request){
        $term = $request['name'];
        $page = $request['page'] ?? null;
        $resultCount = 10;
        $offset = ($page-1) * $resultCount;

        $results = DB::table('book_category')->select(['id','name as text'])
            ->where('name', 'like', "%$term%");
        if($page != null){
            $results->skip($offset)->take($resultCount);
        }
        return response()->json([
            'results'=>$results->get(),
            'total_items' =>$results->count()
        ]);
    }
}
