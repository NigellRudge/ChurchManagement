<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class BookController extends CommonController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['controller_name'] = 'Book Controller';
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request){
        if($request->ajax()){
            $books = DB::table('books')
                    ->select('id','title','author','ISBN');
            return DataTables::of($books)
                ->addColumn('actions', function ($row){
                    $showUrl = '#';
                    return
                        "<a class='btn-teal btn btn-sm text-white rounded font-weight-bold mr-1 text-xs' href='$showUrl'>
                            <i class='fa fa-eye'></i>
                         </a>"
                        ."<a class='btn btn-sm btn-primary text-white rounded font-weight-bold mr-1 text-xs' onclick='openEditModal(event)'>
                            <i class='fa fa-edit'></i>
                         </a>"
                        ."<a class='btn btn-sm btn-danger text-white rounded font-weight-bold mr-1 text-xs' data-id='$row->id' data-name='$row->title' onclick='openRemoveModal(event)'>
                            <i class='fa fa-trash'></i>
                         </a>";

                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        $this->data['categories'] = DB::table('book_categories')->select('id','name');
        return view('books.index')->with('data',$this->data);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function bookItems(Request $request){
        if($request->ajax()){
            $bookItems = DB::table('book_item_info')->select('id','uid' ,'title','condition','status');
            return DataTables::of($bookItems)
                ->addColumn('actions', function ($row){
                    $showUrl = '#';
                    return
                        "<a class='btn-teal btn btn-sm text-white rounded font-weight-bold mr-1 text-xs' href='$showUrl'>
                            <i class='fa fa-eye'></i>
                         </a>"
                        ."<a class='btn btn-sm btn-primary text-white rounded font-weight-bold mr-1 text-xs' onclick='editBookItem(event)'>
                            <i class='fa fa-edit'></i>
                         </a>"
                        ."<a class='btn btn-sm btn-danger text-white rounded font-weight-bold mr-1 text-xs' data-id='$row->id' data-name='$row->title' onclick='removeBookItem(event)'>
                            <i class='fa fa-trash'></i>
                         </a>";

                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return null;
    }


    public function store(Request $request){
        $data = $request->validate([
            'title' => 'required',
            'author' => 'required',
            'isbn' => 'required',
            'publication_date' => 'required'
        ]);
        //dd($request->all());
        $data['publication_date'] = Carbon::parse($data['publication_date'])->toDateString();
        $book = Book::create($data);

        if(isset($request['categories'])){
            foreach ($request['categories'] as $category) {
                DB::table('book_categories')->insert([
                    'book_id'=>$book->id,
                    'category_id'=>intval($category),
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString()
                ]);
            }
        }
        return response(['message' => 'Book Saved'],201);
    }

    public function update(Request $request){

    }

    public function destroy(Request $request){
        $bookId = $request->validate([
            'book_id' => 'required'
        ]);

        DB::table('books')->where('id','=',$bookId)->delete();
        return response(['message' => 'book Deleted'],201);
    }

    public function storeBookItem(Request $request){
        $data = $request->validate([
            'book_id' => 'required',
            'condition_id' => 'required',
            'status_id' => 'required'
        ]);
        $result = DB::table('book_item')->insert($data);
        return response(['message' => 'Book item saved'],201);
    }

}
