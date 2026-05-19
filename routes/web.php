<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\BankFileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubAccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookCategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ConvertController;
use App\Http\Controllers\DedicationController;
use App\Http\Controllers\MemberFileController;
use App\Http\Controllers\ServiceClubController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\WorkerAttendanceController;
use App\Imports\MemberImport;
use App\Models\SubAccountInfo;
use App\Models\MainAccountInfo;
use App\Models\Member;
use App\Models\MemberFile;
use App\Models\TransactionInfo;
use App\Models\User;
use App\Models\UserModuleAccessInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\TideController;
use App\Http\Controllers\VisitorsController;
use App\Models\MemberInfo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use \App\Http\Controllers\MembersTypeController;
use \App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ExchangeRateController;
use \App\Http\Controllers\DashBoardController;
use \App\Http\Controllers\LocationsController;
use \App\Http\Controllers\CountryController;
use \App\Http\Controllers\ChurchEventController;
use \App\Http\Controllers\OfferingController;
use \App\Http\Controllers\EagleGroupController;
use \App\Http\Controllers\WorkGroupController;
use Illuminate\Http\Request;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\SeedController;
use \App\Http\Controllers\RegistrationController;
use Maatwebsite\Excel\Facades\Excel;

//$modules = UserModuleAccessInfo::where('user_id','=',2)->select('*');



Route::get('/login',[AuthController::class,'login'])->name('login');
Route::post('/login',[AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/logout',[AuthController::class,'logout'])->name('logout');

Route::group(['middleware'=>['custom_auth','language']],function(){
    Route::post('/changeLang', [AuthController::class,'changeLanguage'])->name('change_language');
    Route::get('/',[DashBoardController::class,'index'])->name('home');
    Route::get('/dashboard',[DashBoardController::class,'index'])->name('dashboard');
    Route::get('/dashboard/JW',[DashBoardController::class,'JWDashboard'])->name('dashboard.JW');

    Route::post('/gendersJson',function(Request $request){
       $term = $request['term'];
       $genders = DB::table('genders')
                 ->where('name','like',"%$term%")
                 ->orWhere('name','like',"%$term%")
                 ->select(['id',DB::raw('name as text')])->get();
        return response()->json([
            'results'=>$genders
        ]);
    })->name('genders.Json');

    Route::post('/genderById',function (Request $request){
        $genderId = $request['genderId'];
        $gender = DB::table('genders')
            ->where('id','=',$genderId)
            ->select('id','name','code')
            ->get();
        return response(['gender'=>$gender],201);
    })->name('genders.getByIdJson');

    // Currency Routes
    Route::group(['prefix'=>'/currency','as'=>'currency.'],function (){
        Route::get('/delete/{currency}',[CurrencyController::class,'delete'])->name('delete');
        Route::post('/history',[CurrencyController::class,'history'])->name('history');
        Route::post('/remove',[CurrencyController::class,'destroyAjax'])->name('destroyAjax');
        Route::post('/add',[CurrencyController::class,'storeAjax'])->name('storeAjax');
        Route::post('/update',[CurrencyController::class,'updateAjax'])->name('updateAjax');
        Route::post('/getById',[CurrencyController::class,'getByIdJson'])->name(   'getByIdJson');
        Route::post('/getJson',[CurrencyController::class,'getJson'])->name('getJson');
        Route::get('/',[CurrencyController::class,'index'])->name('index');
    });

    // MemberTypes Routes
    Route::group(['prefix'=>'/type','as'=>'type.'],function (){
        Route::get('/delete/{type}',[MembersTypeController::class,'delete'])->name('delete');
        Route::post('/add/json',[MembersTypeController::class,'addAjax'])->name('addAjax');
        Route::post('/remove/json',[MembersTypeController::class, 'destroyAjax'])->name('destroyAjax');
        Route::post('/getById',[MembersTypeController::class,'getByIdAjax'])->name('getByIdAjax');
        Route::post('/update/json',[MembersTypeController::class,'updateAjax'])->name('updateAjax');
        Route::get('/',[MembersTypeController::class,'index'])->name('index');
    });

    Route::resource('/country',CountryController::class);
    Route::post('/country/api/list',[CountryController::class,'getCountriesJson'])->name('country.list.json');
    Route::get('/country/delete/{country}',[CountryController::class,'delete'])->name('country.delete');

    Route::group(['prefix'=>'/events','as'=>'events.'],function (){
        Route::delete('/',[ChurchEventController::class,'destroyAjax'])->name('delete');
        Route::get('/calendar',[ChurchEventController::class,'calendar'])->name('calendar');
        Route::post('/StoreAjax',[ChurchEventController::class,'StoreAjax'])->name('StoreAjax');

        Route::post('/getEventByIdAjax',[ChurchEventController::class,'getEventByIdAjax'])->name('getEventByIdAjax');
        Route::post('/storeSheetItem',[ChurchEventController::class,'storeSheetItem'])->name('storeSheetItem');
        Route::post('/removeItemFromSheet',[ChurchEventController::class,'removeItemFromSheet'])->name('removeItemFromSheet');
        Route::post('/eventsListJson',[ChurchEventController::class,'eventsListJson'])->name('eventsListJson');
        Route::post('/getEventByIdJson',[ChurchEventController::class,'getEventByIdJson'])->name('getEventByIdJson');
        Route::post('/destroySheetAjax',[ChurchEventController::class,'destroySheetAjax'])->name('destroySheetAjax');
        Route::post('/getSheetByIdJson',[ChurchEventController::class,'getSheetByIdJson'])->name('getSheetByIdJson');
        Route::get('/registration',[ChurchEventController::class,'registrationSheets'])->name('registration');
        Route::post('/storeRegistrationSheet',[ChurchEventController::class,'storeRegistrationSheet'])->name('storeRegistrationSheet');
        Route::post('/updateRegistrationSheet',[ChurchEventController::class,'updateRegistrationSheet'])->name('updateRegistrationSheet');
        Route::post('/getTotalAmountOnSheet',[ChurchEventController::class,'getTotalAmountOnSheet'])->name('getTotalAmountOnSheet');
        Route::get('/registration/members/{registration_sheet}',[ChurchEventController::class, 'registrationSheetInfo'])->name('registrationSheetMembers');
        Route::post('/exportRegistrationSheet',[ChurchEventController::class,'exportRegistrationSheet'])->name('exportRegistrationSheet');
        Route::post('/calendarEvents',[ChurchEventController::class,'calendar'])->name('calendarEvents');
        Route::post('/getMembersNotOnSheet',[ChurchEventController::class,'membersNotOnSheet'])->name('membersNotOnSheet');

    });
    Route::resource('/events',ChurchEventController::class);

    Route::group(['prefix'=>'/event-type','as'=>'event-type.'],function (){
        Route::get('/create',[ChurchEventController::class,'eventTypesCreate'])->name('create');
        Route::get('/edit/{eventType}',[ChurchEventController::class,'eventTypesEdit'])->name('edit');
        Route::patch('/{eventType}',[ChurchEventController::class,'eventTypesEdit'])->name('update');
        Route::get('/delete/{eventType}',[ChurchEventController::class,'eventTypeDelete'])->name('delete');
        Route::delete('/{eventType}',[ChurchEventController::class,'eventTypeDestroy'])->name('destroy');
        Route::post('/storeAjax',[ChurchEventController::class,'eventTypesStoreAjax'])->name('addAjax');
        Route::post('/destroyAjax',[ChurchEventController::class,'eventTypesDestroyAjax'])->name('destroyAjax');
        Route::post('/getByIdAjax',[ChurchEventController::class,'getByIdAjax'])->name('getByIdAjax');
        Route::post('/updateAjax',[ChurchEventController::class,'updateAjax'])->name('updateAjax');
        Route::post('/',[ChurchEventController::class,'eventTypesStore'])->name('store');
        Route::get('/',[ChurchEventController::class,'eventTypesIndex'])->name('index');
    });

    Route::group(['prefix'=>'/district','as'=>'district.'],function (){
        Route::get('/delete/{district}',[DistrictController::class,'delete'])->name('delete');
        Route::post('/json',[DistrictController::class,'getDistrictsJson'])->name('json');
        Route::post('/storeAjax',[DistrictController::class, 'storeAjax'])->name('addJson');
        Route::post('/destroyAjax',[DistrictController::class, 'destroyAjax'])->name('destroyAjax');
        Route::post('/getAjax',[DistrictController::class,'getByIdAjax'])->name('getByIdAjax');
        Route::post('/updateAjax',[DistrictController::class,'updateAjax'])->name('updateAjax');
        Route::get('/',[DistrictController::class,'index'])->name('index');
    });

    Route::group(['prefix' => '/memberFiles','as' => 'memberFiles.'],function(){
        Route::delete('/delete',[MemberFileController::class,'delete'])->name('delete');
        Route::post('/upload',[MemberFileController::class,'uploadMemberFile'])->name('upload');
        Route::get('/download/{file_id}',[MemberFileController::class,'download'])->name('downloadFile');
        Route::post('/',[MemberFileController::class,'memberFiles'])->name('index');

    });

    Route::group(['prefix'=>'/members','as'=>'members.'],function (){
        Route::post('/birthDaysExport',[MemberController::class,'birthDaysExport'])->name('birthDayExport');
        Route::post('/checkMemberStatus',[MemberController::class,'checkStatus'])->name('checkStatus');
        Route::post('/historyList',[MemberController::class,'getMembershipHistory'])->name('membershipHistory');
        Route::post('/deactivateMembership',[MemberController::class,'endMembership'])->name('endMembership');
        Route::post('/reactivateMembership',[MemberController::class,'reactivateMember'])->name('reactivateMembership');
        Route::get('/promoteVisitor', [MemberController::class,'promoteVisitorToMember'])->name('promoteVisitor');
        Route::post('/promoteVisitor', [MemberController::class,'storePromotedVisitor'])->name('storePromotedVisitor');
        Route::post('/getParents', [MemberController::class,'getParents'])->name('getParents');
        Route::get('/delete/{member}',[MemberController::class,'delete'])->name('delete');
        Route::post('/list/json',[MemberController::class,'getMembersJson'])->name('json');
        Route::post('/pastors/json',[MemberController::class,'getPastorsJson'])->name('pastors');
        Route::post('/getById',[MemberController::class,'getMemberByIdJson'])->name('getByIdJson');
        Route::post('/import',[MemberController::class,'importMembers'])->name('import');
        Route::post('/addRelation',[MemberController::class,'storeMemberRelation'])->name('addRelation');
        Route::delete('/removeRelation',[MemberController::class,'removeRelation'])->name('removeRelation');
        Route::get('/family/{member}',[MemberController::class,'family'])->name('family');
        Route::post('/exportMembers',[MemberController::class,'exportMembers'])->name('exportMembers');
    });


    Route::group(['prefix'=>'/convert','as'=>'convert.'],function (){
        Route::post('/export',[ConvertController::class,'export'])->name('export');
        Route::post('/storeAjax',[ConvertController::class, 'store'])->name('store');
        Route::patch('/updateAjax',[ConvertController::class, 'update'])->name('update');
        Route::delete('/destroyAjax',[ConvertController::class, 'destroy'])->name('destroy');
        Route::post('/getByIdAjax',[ConvertController::class, 'getById'])->name('getById');
        Route::get('/',[ConvertController::class,'index'])->name('index');
    });

    Route::group(['prefix'=>'/offerings','as'=>'offerings.'],function (){
        Route::post('/export',[OfferingController::class,'export'])->name('export');
        Route::post('/',[OfferingController::class,'storeAjax'])->name('storeAjax');
        Route::patch('/',[OfferingController::class,'updateAjax'])->name('updateAjax');
        Route::delete('/',[OfferingController::class,'destroyAjax'])->name('destroyAjax');
        Route::post('/get',[OfferingController::class,'getByIdAjax'])->name('getByIdAjax');
        Route::get('/',[OfferingController::class,'index'])->name('index');
    });

    Route::group(['prefix'=>'/eagle-group','as'=>'eagle-group.'],function(){
        Route::get('/members/{eagle_group}',[EagleGroupController::class, 'members'])->name('members');
        Route::get('/delete/{eagle_group}',[EagleGroupController::class,'delete'])->name('delete');
        Route::post('/group/remove_member',[EagleGroupController::class,'removeGroupMember'])->name('removeMember');
        Route::post('/group/add_member',[EagleGroupController::class,'addGroupMemberAjax'])->name('addMemberAjax');
        Route::post('/storeAjax',[EagleGroupController::class,'storeAjax'])->name('storeAjax');
        Route::post('/destroyAjax',[EagleGroupController::class,'destroyAjax'])->name('destroyAjax');
        Route::post('/notYetMembers',[EagleGroupController::class,'getNotYetMembersJson'])->name('notMembersJson');
        Route::post('/MembersAjax',[EagleGroupController::class,'getGroupMembersJson'])->name('getGroupMembersJson');
        Route::post('/getByIdAjax',[EagleGroupController::class,'getByIdAjax'])->name('getByIdAjax');
        Route::post('/updateAjax',[EagleGroupController::class,'updateAjax'])->name('updateAjax');
        Route::post('/list/json',[EagleGroupController::class,'getEagleGroupsJson'])->name('getEagleGroupsJson');
        Route::post('/exportOverview',[EagleGroupController::class,'exportEagleGroupOverview'])->name('exportOverview');
        Route::post('/exportMembersOverview',[EagleGroupController::class,'exportGroupMembers'])->name('exportGroupMembers');
        Route::get('/edit/{eagle_group}',[EagleGroupController::class,'edit'])->name('edit');
        Route::get('/',[EagleGroupController::class,'index'])->name('index');
    });

    Route::group(['prefix'=>'/attendance','as'=>'attendance.'],function(){
        Route::post('/getAvailableDates',[AttendanceController::class,'getAvailableDates'])->name('getAvailableDates');
        Route::post('/exportSheet',[AttendanceController::class, 'exportSheet'])->name('exportSheet');
        Route::post('/removeAttendanceAjax',[AttendanceController::class,'removeFromSheet'])->name('removeFromSheet');
        Route::get('/viewSheet/{sheet}',[AttendanceController::class,'viewSheet'])->name('viewSheet');
        Route::post('/storeSheetItem',[AttendanceController::class,'addToSheet'])->name('addToSheet');
        Route::post('/destroySheetAjax',[AttendanceController::class,'destroySheet'])->name('destroySheet');
        Route::post('/storeSheetAjax',[AttendanceController::class,'storeSheet'])->name('storeSheet');
        Route::get('/',[AttendanceController::class,'index'])->name('index');
    });

    Route::group(['prefix'=>'visitors','as'=>'visitors.'],function(){
        Route::post('/exportSheet',[VisitorsController::class, 'exportSheet'])->name('exportSheet');
        Route::post('/getDates',[VisitorsController::class, 'getDates'])->name('getDates');
        Route::post('/update',[VisitorsController::class,'updateVisitor'])->name('updateVisitor');
        Route::post('/getSheetById',[VisitorsController::class, 'getSheetById'])->name('getSheetByIdAjax');
        Route::delete('/visitorSheetInfo/destroy',[VisitorsController::class,'destroyVisitor'])->name('destroyVisitor');
        Route::post('/store',[VisitorsController::class,'storeVisitor'])->name('storeVisitorAjax');
        Route::get('/visitorSheetInfo/{sheet}',[VisitorsController::class,'sheetInfo'])->name('sheetInfo');
        Route::post('/getById',[VisitorsController::class,'getVisitorById'])->name('getVisitorInfo');
        Route::get('/',[VisitorsController::class,'index'])->name('index');
        Route::post('/',[VisitorsController::class, 'storeSheet'])->name('storeSheet');
        Route::patch('/',[VisitorsController::class, 'updateSheet'])->name('updateSheet');
        Route::delete('/',[VisitorsController::class,'destroySheet'])->name('destroySheet');
    });

    Route::group(['prefix'=>'/work-groups','as'=>'work-groups.'],function(){
        Route::get('/info/{work_group}',[WorkGroupController::class,'info'])->name('info');
        Route::post('/destroyAjax',[WorkGroupController::class,'destroy'])->name('destroy');
        Route::post('/list',[WorkGroupController::class,'workGroupList'])->name('list');
        Route::post('/memberList',[WorkGroupController::class,'workGroupList'])->name('memberList');
        Route::post('/storeAjax',[WorkGroupController::class,'store'])->name('store');
        Route::post('/addMemberAjax',[WorkGroupController::class,'addGroupMemberAjax'])->name('addMembersAjax');
        Route::post('/getNotYetMembersJson',[WorkGroupController::class,'getNotYetMembersJson'])->name('getNotYetMembersJson');
        Route::post('/removeMemberAjax',[WorkGroupController::class,'removeMemberAjax'])->name('removeMemberAjax');
        Route::post('/getByIdAjax',[WorkGroupController::class,'getById'])->name('getById');
        Route::post('/updateAjax',[WorkGroupController::class,'update'])->name('update');
        Route::get('/',[WorkGroupController::class,'index'])->name('index');
    });

    Route::group(['prefix'=>'/seeds','as'=>'seeds.'],function (){
        Route::post('/export',[SeedController::class,'export'])->name('export');
        Route::get('/',[SeedController::class,'index'])->name('index');
        Route::post('/',[SeedController::class,'store'])->name('store');
        Route::post('/getById',[SeedController::class,'getById'])->name('getById');
        Route::delete('/',[SeedController::class,'destroy'])->name('destroy');
        Route::patch('/',[SeedController::class,'update'])->name('update');
    });

    Route::group(['prefix'=>'/covid-registration','as'=>'covid-registration.'],function (){

        Route::post('/export',[RegistrationController::class,'exportSheet'])->name('exportSheet');
        Route::post('/membersNotOnSheet',[RegistrationController::class,'getMembersNotOnSheet'])->name('membersNotOnSheet');
        Route::get('/{sheet}',[RegistrationController::class,'sheetInfo'])->name('sheetInfo');
        Route::post('/{sheet}',[RegistrationController::class, 'addToSheet'])->name('addToSheet');
        Route::delete('/{sheet}',[RegistrationController::class,'removeFromSheet'])->name('removeFromSheet');
        Route::get('/',[RegistrationController::class,'index'])->name('index');
        Route::post('/',[RegistrationController::class, 'storeSheet'])->name('storeSheet');
        Route::patch('/',[RegistrationController::class, 'updateSheet'])->name('updateSheet');
        Route::delete('/',[RegistrationController::class, 'destroySheet'])->name('destroySheet');
    });

    Route::group(['prefix'=>'/books','as'=>'books.'],function(){
        Route::post('/categories',[BookController::class,'bookCategories'])->name('categories');
        Route::get('/',[BookController::class,'index'])->name('index');
        Route::post('/',[BookController::class,'store'])->name('store');
        Route::delete('/',[BookController::class,'destroy'])->name('destroy');
        Route::post('/item',[BookController::class,'storeBookItem'])->name('storeBookItem');
        Route::post('/bookItems',[BookController::class,'bookItems'])->name('bookItems');
    });

    Route::post('/randomPost',[BookController::class,'store'])->name('postTest');

    Route::group(['prefix'=>'/categories','as'=>'categories.'], function(){
        Route::post('/json',[BookCategoryController::class,'getList'])->name('getList');
       Route::get('/',[BookCategoryController::class,'index'])->name('index');
       Route::post('/',[BookCategoryController::class,'store'])->name('store');
       Route::delete('/',[BookCategoryController::class,'destroy'])->name('destroy');
       Route::patch('/',[BookCategoryController::class,'update'])->name('update');
    });

    Route::group(['prefix'=>'/workerAttendance', 'as' => 'workerAttendance.'],function (){
        Route::post('/getById',[WorkerAttendanceController::class,'getSheetById'])->name('getSheetById');
        Route::post('/membersNotOnSheet',[WorkerAttendanceController::class,'getMembersNotOnSheet'])->name('membersNotOnSheet');
       Route::delete('/{sheet}',[WorkerAttendanceController::class,'removeItemFromSheet'])->name('removeFromSheet');
       Route::post('/{sheet}',[WorkerAttendanceController::class,'addItemToSheet'])->name('addToSheet');
       Route::get('/{sheet}', [WorkerAttendanceController::class,'viewSheet'])->name('show');


       Route::post('/', [WorkerAttendanceController::class,'storeSheet'])->name('store');
       Route::patch('/', [WorkerAttendanceController::class,'editSheet'])->name('edit');
       Route::delete('/', [WorkerAttendanceController::class,'destroySheet'])->name('destroy');
       Route::get('/', [WorkerAttendanceController::class,'index'])->name('index');
    });

    Route::group(['prefix'=>'/dedication', 'as' => 'dedication.'],function (){
       Route::post('/notDedicated', [DedicationController::class,'getNotDedicationInfants'])->name('notDedicated');
       Route::post('/export', [DedicationController::class,'exportData'])->name('export');
       Route::post('/getById', [DedicationController::class,'getById'])->name('getById');
       Route::post('/', [DedicationController::class,'store'])->name('store');
       Route::put('/', [DedicationController::class,'update'])->name('update');
       Route::delete('/', [DedicationController::class,'destroy'])->name('destroy');
       Route::get('/', [DedicationController::class,'index'])->name('index');
    });

    Route::group(['prefix' => '/serviceClub', 'as' => 'service_club.'], function(){
        Route::post('/export',[ServiceClubController::class,'export'])->name('export');
        Route::post('/getMemberSectors',[ServiceClubController::class,'getMemberSectors'])->name('getMemberSectors');
        Route::post('/getSectors',[ServiceClubController::class,'getSectors'])->name('getSectors');
        Route::post('/getById',[ServiceClubController::class,'getById'])->name('getById');
        Route::get('/',[ServiceClubController::class,'index'])->name('index');
        Route::post('/',[ServiceClubController::class,'store'])->name('store');
        Route::patch('/',[ServiceClubController::class,'update'])->name('update');
        Route::delete('/',[ServiceClubController::class,'destroy'])->name('destroy');
    });

    Route::group(['prefix' => '/accounts','as' => 'accounts.'],function(){
        Route::post('/getById',[AccountsController::class,'getById'])->name('getById');
        Route::post('/list',[AccountsController::class,'accountList'])->name('list');
        Route::get('/{account}',[AccountsController::class,'show'])->name('show');
        Route::get('/',[AccountsController::class,'index'])->name('index');
        Route::post('/',[AccountsController::class,'store'])->name('store');
        Route::patch('/',[AccountsController::class,'update'])->name('update');
        Route::delete('/deactivate',[AccountsController::class,'deactivate'])->name('deactivate');
        Route::post('/reactivate',[AccountsController::class,'reactivate'])->name('reactivate');
        Route::delete('/',[AccountsController::class,'destroy'])->name('destroy');
    });

    Route::group(['prefix' => '/sub-accounts','as' => 'sub-accounts.'],function(){
        Route::post('/deactivate',[SubAccountController::class,'deactivate'])->name('deactivate');
        Route::post('/checkBalance',[SubAccountController::class,'checkBalance'])->name('checkBalance');
        Route::post('/financeOverviewExport',[SubAccountController::class,'exportFinanceOverview'])->name('exportFinanceOverview');
        Route::post('/export',[SubAccountController::class,'exportData'])->name('export');
        Route::post('/reactivate',[SubAccountController::class,'reactivate'])->name('reactivate');
        Route::post('/getById',[SubAccountController::class,'getById'])->name('getById');
        Route::get('/{account}',[SubAccountController::class,'show'])->name('show');
        Route::get('/',[SubAccountController::class,'index'])->name('index');
        Route::post('/list',[SubAccountController::class,'accountList'])->name('list');
        Route::post('/',[SubAccountController::class,'store'])->name('store');
        Route::patch('/',[SubAccountController::class,'update'])->name('update');
        Route::delete('/',[SubAccountController::class,'destroy'])->name('destroy');
    });

    Route::group(['prefix' => '/transactions','as' => 'transactions.'],function(){
        Route::post('/exportOverview',[TransactionsController::class,'exportTransactionOverview'])->name('exportOverview');
        Route::post('/getById',[TransactionsController::class,'getById'])->name('getById');
        Route::post('/update',[TransactionsController::class,'update'])->name('update');
        Route::get('/download/{transaction}',[TransactionsController::class,'downloadTransactionAttachment'])->name('downloadAttachment');
        Route::post('/',[TransactionsController::class,'store'])->name('store');
        Route::delete('/',[TransactionsController::class,'delete'])->name('delete');
        Route::get('/',[TransactionsController::class,'index'])->name('index');

    });

    Route::group(['prefix' => '/user-management','as' => 'users.'], function (){
        Route::get('/',[UserController::class,'index'])->name('index');
        Route::post('/getById',[UserController::class,'getById'])->name('getById');
        Route::post('/',[UserController::class,'store'])->name('store');
        Route::delete('/',[UserController::class,'destroy'])->name('destroy');
        Route::patch('/changePassword',[UserController::class,'changePassword'])->name('changePassword');
        Route::patch('/',[UserController::class,'update'])->name('update');
    });

    Route::group(['prefix' =>'/budgets', 'as'=> 'budgets.'], function (){
        Route::post('/export/{budget}',[BudgetController::class,'exportBudget'])->name('export');
        Route::post('/getItemById',[BudgetController::class,'getItemById'])->name('getItemById');
        Route::post('/getById',[BudgetController::class,'getById'])->name('getById');
        Route::get('/{budget}',[BudgetController::class,'show'])->name('show');
        Route::post('/{budget}',[BudgetController::class,'addItem'])->name('addItem');
        Route::delete('/{budget}',[BudgetController::class,'removeItem'])->name('removeItem');
        Route::patch('/{budget}',[BudgetController::class,'updateItem'])->name('updateItem');
        Route::get('/',[BudgetController::class,'index'])->name('index');
        Route::post('/',[BudgetController::class,'store'])->name('store');
        Route::patch('/',[BudgetController::class,'update'])->name('update');
        Route::delete('/',[BudgetController::class,'destroy'])->name('delete');
    });

    Route::group(['prefix' => '/bankfiles','as' => 'bankfiles.'],function(){
        Route::get('/{bankFileId}',[BankFileController::class,'view'])->name('view_transactions');
        Route::post('/{bankFileId}',[BankFileController::class,'storeBankFileTransaction'])->name('store_transaction');
        Route::patch('/{bankFileId}',[BankFileController::class,'editBankFileTransaction'])->name('edit_transaction');
        Route::get('/',[BankFileController::class,'index'])->name('index');
        Route::post('/',[BankFileController::class,'store'])->name('store');
        Route::delete('/',[BankFileController::class,'destroy'])->name('destroy');

    });

    Route::resource('/members',MemberController::class);

});
