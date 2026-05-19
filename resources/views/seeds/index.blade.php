@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between">
                    <div class="card-title font-weight-bold  pt-1">
                        <span class="font-weight-bold text-lg text-dark">{{trans('common.seeds_label')}}</span>
                    </div>
                    <div class="d-flex flex-row">
                        <form action="{{route('seeds.export')}}" method="post">
                            @csrf
                            <input type="hidden" id="export_from_date" name="from_date">
                            <input type="hidden" id="export_to_date" name="to_date">
                            <input type="hidden" id="export_member_id" name="member_id">
                            <input type="hidden" id="export_type_id" name="seed_type">
                            <input type="hidden" id="export_currency_id" name="currency_id">
                            <button class="mr-2 btn btn-primary font-weight-bold text-light rounded font-weight-bol" disabled id="exportBtn" type="submit">
                                {{trans('common.export_to_excel_label')}}
                                <i class="ml-1 fas fa-file-excel"></i>
                            </button>
                        </form>
                        <a class="btn btn-teal font-weight-bold" href="#" onclick="openAddModal(event)">
                            {{trans('common.add_seed_label')}}
                            <i class="ml-1 fas fa-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row m-0">
                        <div class="col-xl-1 col-lg-1 col-md-2 col-sm-3">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="filter_currency_id" class="col-form-label font-weight-bold">{{trans('common.currency_label')}}</label>
                                    <select type="text" id="filter_currency_id" name="filter_currency_id" class="form-control">
                                        <option value="0">{{trans('common.all_label')}}</option>
                                        @foreach($data['currencies'] as $currency)
                                            <option value="{{$currency['id']}}">{{$currency['code']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-3">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="filter_type_id" class="col-form-label font-weight-bold">{{trans('common.seed_type_label')}}</label>
                                    <select type="text" id="filter_type_id" name="filter_type_id" class="form-control">
                                        <option value="0">{{trans('common.all_label')}}</option>
                                        <option value="{{ config('constants.SEED_TYPE_TIDE') }}">{{trans('common.seed_type_tide')}}</option>
                                        <option value="{{ config('constants.SEED_TYPE_SPECIAL_SEED') }}">{{trans('common.seed_type_special_seed')}}</option>
                                        <option value="{{ config('constants.SEED_TYPE_BUILDING') }}">{{trans('common.building_seed')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="date_filter" class="col-form-label font-weight-bold">{{trans('common.date_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white">
                                                <i class="fa fa-calendar text-teal"></i>
                                            </div>
                                        </div>
                                        <input type="text" autocomplete="off" class="form-control" id="date_filter" name="date_filter">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-3 col">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="filter_member_id" class="col-form-label font-weight-bold">  <i class="fa fa-user text-teal mr-1"></i>{{trans('common.member_label')}}</label>
                                    <select type="text" class="form-control" id="filter_member_id" name="member_id">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-5 pl-2">
                        <div class="col">
                            <div class="d-flex flex-row">
                                <button class="btn btn-teal text-light font-weight-bold mr-2" id="filterBtn">
                                    {{trans('common.filter_label')}}
                                    <i class="fas fa-filter ml-1"></i>
                                </button>
                                <button class="btn btn-danger text-light font-weight-bold" id="clearBtn">
                                    {{trans('common.cancel_label')}}
                                    <i class="fas fa-ban ml-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <table id="datatable" class="table table-bordered table-striped display compact nowrap">
                            <thead>
                            <tr>
                                <th>
                                    <span class="mr-1"><i class="fa fa-user text-teal"></i></span>
                                    {{trans('common.member_label')}}
                                </th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-cog text-teal"></i></span>
                                    {{trans('common.type_label')}}
                                </th>
                                <th>
                                    {{trans('common.name_label')}}
                                </th>

                                <th>
                                    <span class="mr-1"><i class="fa fa-coins text-warning"></i></span>
                                    {{trans('common.amount_label')}}
                                </th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-dollar-sign text-teal"></i></span>
                                    {{trans('common.amount_base_currency')}}
                                </th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-calendar text-teal"></i></span>
                                    {{trans('common.date_label')}}
                                </th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="exampleModalLabel">{{trans('common.confirm_label')}}</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="removeForm">
                    @csrf
                    <input type="hidden" name="remove_seed_id" id="remove_seed_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-2">
                                {{trans('common.confirm_remove_seed_label')}}:<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_seed"></div> ?
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal">
                            <span class="mr-1"><i class="fa fa-trash"></i></span>
                            {{trans('common.yes_label')}}
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            {{trans('common.no_label')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.add_seed_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class="row">
                    <div class="col d-flex p-1 flex-row justify-content-center">
                        <img id="member_image" alt="member_image" src="{{asset('storage/placeholder-male.jpg')}}" width="120" height="180" style="object-fit: cover; border-radius: 8px">
                    </div>
                </div>
                <div class=" mt-2 pl-3 pr-3">
                    <form method="post" action="#" id="addForm">
                        @csrf
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="member_id" class="text-dark font-weight-bold">{{trans('common.member_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fa fa-user-alt"></i>
                                            </div>
                                        </div>
                                        <select name="member_id" data-placeholder="{{trans('common.select_member_label')}}"  id="member_id" type="text" class="form-control">
                                        </select>
                                    </div>
                                    <div id="memberError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="seed_type_id" class="text-dark font-weight-bold">{{trans('common.type_label')}}</label>
                                    <select type="text" id="seed_type_id" name="seed_type_id" class="form-control">
                                        <option value="">{{trans('common.select_type_label')}}</option>
                                        <option value="{{ config('constants.SEED_TYPE_TIDE') }}">{{trans('common.seed_type_tide')}}</option>
                                        <option value="{{ config('constants.SEED_TYPE_SPECIAL_SEED') }}">{{trans('common.seed_type_special_seed')}}</option>
                                        <option value="{{ config('constants.SEED_TYPE_BUILDING') }}">{{trans('common.building_seed')}}</option>
                                    </select>
                                    <div id="typeError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="title" class="text-dark font-weight-bold">{{trans('common.name_label')}}</label>
                                    <input type="text" id="title" placeholder="{{trans('common.seed_place_holder_label')}}" name="title" class="form-control">
                                    <div id="titleError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="date" class="text-dark font-weight-bold">{{trans('common.date_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                        <input id="date" name="date" class="form-control" type="text" />
                                    </div>
                                    <div id="dateError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-5">
                                <div class="form-group">
                                    <label for="currency_id" class="text-dark font-weight-bold">{{trans('common.currency_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-dollar-sign"></i>
                                            </div>
                                        </div>
                                        <select name="currency_id" id="currency_id" data-placeholder="Select currency" class="form-control">
                                            <option value="" >{{trans('common.select_option')}}</option>
                                            @foreach($data['currencies'] as $currency)
                                                <option value="{{$currency->id}}">{{$currency->code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="currencyError" class="customError"></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="amount"  class="text-dark font-weight-bold">{{trans('common.amount_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-warning">
                                                <i class="fas fa-coins"></i>
                                            </div>
                                        </div>
                                        <input  name="amount" step="0.01" min="0.01" max="100000000" id="amount" placeholder="$0.00" type="number" class="form-control" />
                                    </div>
                                    <div id="amountError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                    <div class="modal-footer">
                        <button onclick="submitAddForm()" class="btn btn-teal" id="addSubmitBtn">
                            <i class="fas fa-save"></i>
                            {{trans('common.save_label')}}
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-ban"></i>
                            {{trans('common.cancel_label')}}
                        </button>
                    </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="editModalLabel">{{trans('common.edit_seed_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class="row">
                    <div class="col d-flex p-1 flex-row justify-content-center">
                        <img id="edit_member_image" alt="member_image" src="{{asset('storage/placeholder-male.jpg')}}" width="120" height="180" style="object-fit: cover; border-radius: 8px">
                    </div>
                </div>
                <div class=" mt-2 pl-3 pr-3">
                    <form method="post" action="#" id="editForm">
                        @csrf
                        <input type="hidden" name="edit_seed_id" id="edit_seed_id">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_member_id" class="text-dark font-weight-bold">{{trans('common.member_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fa fa-user-alt"></i>
                                            </div>
                                        </div>
                                        <select name="member_id" data-placeholder="Select member"  id="edit_member_id" type="text" class="form-control">
                                        </select>
                                    </div>
                                    <div id="editMemberError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_seed_type_id" class="text-dark font-weight-bold">{{trans('common.seed_type_label')}}</label>
                                    <select type="text" id="edit_seed_type_id" name="seed_type_id" class="form-control">
                                        <option value="0">{{trans('common.select_type_label')}}</option>
                                        <option value="{{ config('constants.SEED_TYPE_TIDE') }}">{{trans('common.seed_type_tide')}}</option>
                                        <option value="{{ config('constants.SEED_TYPE_SPECIAL_SEED') }}">{{trans('common.seed_type_special_seed')}}</option>
                                        <option value="{{ config('constants.SEED_TYPE_BUILDING') }}">{{trans('common.building_seed')}}</option>
                                    </select>
                                    <div id="editTypeError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_title" class="text-dark font-weight-bold">{{trans('common.name_label')}}</label>
                                    <input type="text" id="edit_title" name="title" class="form-control">
                                    <div id="editTitleError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_date" class="text-dark font-weight-bold">{{trans('common.date_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                        <input id="edit_date" name="date" class="form-control" type="text" />
                                    </div>
                                    <div id="editDateError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-5">
                                <div class="form-group">
                                    <label for="edit_currency_id" class="text-dark font-weight-bold">{{trans('common.currency_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-dollar-sign"></i>
                                            </div>
                                        </div>
                                        <select name="currency_id" id="edit_currency_id" data-placeholder="Select currency" class="form-control">
                                            <option value="0">{{trans('common.select_currency_label')}}</option>
                                            @foreach($data['currencies'] as $currency)
                                                <option value="{{$currency->id}}">{{$currency->code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="editCurrencyError" class="customError"></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_amount"  class="text-dark font-weight-bold">{{trans('common.amount_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-warning">
                                                <i class="fas fa-coins"></i>
                                            </div>
                                        </div>
                                        <input  name="amount" step="0.01" min="0.01" max="100000000" id="edit_amount" placeholder="$0.00" type="number" class="form-control" />
                                    </div>
                                    <div id="editAmountError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                    <div class="modal-footer">
                        <button onclick="submitEditForm()" class="btn btn-teal">
                            <i class="fas fa-save"></i>
                            {{trans('common.save_label')}}
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-ban"></i>
                            {{trans('common.cancel_label')}}
                        </button>
                    </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addTransactionModal" tabindex="-1" role="dialog" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="editModalLabel">{{trans('common.add_transaction')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="edit_form">
                    @csrf
                    <input type="hidden" name="edit_seed_id" id="edit_seed_id">
                    <div class=" mt-2 pl-3 pr-3">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="account_id" class="text-dark font-weight-bold">{{trans('common.account')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fa fa-user-alt"></i>
                                            </div>
                                        </div>
                                        <select name="account_id" data-placeholder="{{trans('common.account')}}"  id="account_id" type="text" class="form-control">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="add_tran_description" class="text-dark font-weight-bold">{{trans('common.description_label')}}</label>
                                    <input type="text" name="description" id="add_tran_description" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="transaction_date" class="text-dark font-weight-bold">{{trans('common.date_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                        <input id="transaction_date" name="transaction_date" class="form-control" type="text" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="transaction_amount"  class="text-dark font-weight-bold">{{trans('common.amount_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-coins"></i>
                                            </div>
                                        </div>
                                        <input  name="amount" step="0.01" min="0.01" max="100000000" id="transaction_amount" placeholder="$0.00" type="number" class="form-control" />
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal">
                            <i class="fas fa-save"></i>
                            {{trans('common.save_label')}}
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-ban"></i>
                            {{trans('common.cancel_label')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection



@section('custom_js')
    @include('shared.totalJS')
    <script>
        const editModal = $('#editModal')
        const addTransactionModal = $('#addTransactionModal')

        let currencyId = 0;
        let fromDate = null;
        let toDate = null;
        let typeId = null
        let memberId = null

        const filterCurrency = $('#filter_currency_id')
        const filterType = $('#filter_type_id')
        const filterBtn = $('#filterBtn')
        const clearBtn = $('#clearBtn')
        const dateFilterEl = $('#date_filter')
        // const toDateEl = $('#to_date')
        const memberFilterEl = $('#filter_member_id')

        const exportMember = $('#export_member_id')
        const exportType = $('#export_type_id')
        const exportCurrency = $('#export_currency_id')
        const exportToDate = $('#export_to_date')
        const exportFromDate = $('#export_from_date')

        const addForm = $('#addForm')
        const deleteForm = $('#removeForm')
        const editForm = $('#editForm')

        const dataTable = $("#datatable").DataTable({
            processing: true,
            language: datatableTrans,
            autoWidth:false,
            serverSide: true,
            lengthMenu: [10, 25, 50, 75, 100 ],
            pageLength:10,
            ajax: {
                url:'{!! route('seeds.index') !!}',
                data: function(d){
                    d.currency_id = currencyId
                    d.to_date = toDate
                    d.from_date = fromDate
                    d.typeId = typeId
                    d.member_id = memberId
                },
            },
            columns: [
                { data: 'member_info', name: 'member' },
                { data: 'type_info', name: 'type_id' },
                { data: 'title', name: 'title' },
                { data: 'amount_formatted', name: 'amount' },
                { data: 'base_currency_amount_formatted', name: 'amount_in_base_currency' },
                {data: 'date',name: 'date'},
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ],
            initComplete:function (settings,json) {
                onReloadComplete(json)
            }
        });
        $(document).ready(function(){

            filterCurrency.on('change',function(){
                currencyId = this.value;
                exportCurrency.val(this.value)
            });

            filterType.on('change',function(){
                typeId = this.value;
                exportType.val(this.value)
            });

            dateFilterEl.daterangepicker({
                singleDatePicker:false,
                autoUpdateInput: false,
                startDate: new Date(),
                showDropdowns: true,
                minYear: 1901,
                locale:datePickerTran,
                applyButtonClasses:'btn btn-teal btn-sm',
                cancelButtonClasses:'btn btn-danger btn-sm'
            })
            dateFilterEl.on('apply.daterangepicker',function(ev, picker){
                let start = picker.startDate.format('DD-MM-YYYY')
                let end = picker.endDate.format('DD-MM-YYYY')
                start === end ? $(this).val(`${start}`): $(this).val(`${start} - ${end}`);
                exportFromDate.val(start)
                fromDate = start;
                exportToDate.val(end)
                toDate = end;
            })

            memberFilterEl.select2({
                theme: 'bootstrap4',
                allowClear:true,
                placeholder:'{{trans('common.select_member_label')}}',
                ajax: {
                    url: '{!! route('members.json') !!}',
                    type: 'post',
                    data: function(params){
                        return {
                            _token: '{!! csrf_token() !!}',
                            name: params.term,
                            page: params.page || 1
                        };
                    },
                    dataType: 'json',
                    cache:true,
                    delay:200,
                    placeholder: 'Search Member',
                    processResults: function({total_items,results},params){
                        params.page = params.page || 1;
                        return {
                            results: results,
                            pagination: {
                                more: (params.page * 10) < total_items
                            }
                        }
                    }
                }
            });
            memberFilterEl.on('change', function (event) {
                memberId = $(this).val()
                exportMember.val($(this).val())
            })

            filterBtn.on('click',function($event){
                dataTable.ajax.reload(onReloadComplete)
            })
            clearBtn.on('click',function($event){
                dateFilterEl.val('')
                fromDate = null
                toDate = null
                currencyId = 0
                typeId = null
                memberId = null
                memberFilterEl.val(null).trigger('change');
                filterCurrency.val(0)
                filterType.val(0)
                dataTable.ajax.reload(onReloadComplete)

                exportFromDate.val(null)
                exportToDate.val(null)
                exportMember.val(null)
                exportCurrency.val(null)
                exportType.val(null)
            })

            deleteForm.submit(function($event) {
                $event.preventDefault()
                let data = deleteForm.serialize()
                $.ajax({
                    url: '{!! route('seeds.destroy') !!}',
                    method: 'delete',
                    data: data,
                    success: function (data) {
                    },
                    error: function (error) {
                    },
                    complete: function ({status,responseJSON }) {
                        if (status === 201) {
                            let {message} = responseJSON
                            $('#removeModal').modal('hide')
                            dataTable.ajax.reload(onReloadComplete)
                            toastr.warning(message, 'Success')
                        }
                    }
                })
            })

            const exportForm = $('#exportForm');

            $(".modal").on("hidden.bs.modal", function() {
                clearForm('addForm')
                clearForm('editForm',false)
                $('#member_image').attr('src','{{asset('storage/placeholder-male.jpg')}}')
                $('#edit_member_image').attr('src','{{asset('storage/placeholder-male.jpg')}}')
            });
        });

        function submitAddForm(){
            addForm.validate({
                rules:{
                    member_id:{
                        required:true,
                        min:1,
                    },
                    seed_type_id: {
                      required:true,
                        min:1,
                    },
                    date: {
                        required:true,
                        date: true
                    },
                    currency_id: {
                        required:true,
                        min: 1
                    },
                    amount: {
                        required:true,
                        min: 0.01
                    },
                    title: {
                      required:true,
                      minlength: 3,
                      maxlength: 50
                    },
                },
                messages:{
                    seed_type_id:{
                        required:'{!! trans('custom_validation.select_option') !!}',
                        min: '{!! trans('custom_validation.required_field') !!}'
                    },
                    member_id: {
                        required:'{!! trans('custom_validation.select_member') !!}'
                    },
                    date: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        date:'{!! trans('custom_validation.valid_date') !!}'
                    },
                    currency_id: {
                        required:'{!! trans('custom_validation.select_currency') !!}',
                        min: '{!! trans('custom_validation.select_currency') !!}'
                    },
                    amount: {
                        required: '{!! trans('custom_validation.enter_amount') !!}',
                        min: '{!! trans('custom_validation.min_amount',['min' => 0.01]) !!}'
                    },
                    title: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 3]) !!}',
                        maxlength: '{!! trans('custom_validation.max_length',['max' => 50]) !!}',
                    }
                },
                errorPlacement: function (error, element) {
                    switch (element.attr('name')) {
                        case 'amount':
                            $('#amountError').html(error)
                            break;
                        case 'title':
                            $('#titleError').html(error)
                            break;
                        case 'date':
                            $('#dateError').html(error)
                            break;
                        case 'seed_type_id':
                            $('#typeError').html(error)
                            break;
                        case 'currency_id':
                            $('#currencyError').html(error)
                            break;
                        case 'member_id':
                            $('#memberError').html(error)
                            break;
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(addForm.valid()){
                let data = addForm.serialize()
                $.ajax({
                    url: ' {!! route('seeds.store') !!}',
                    method: 'post',
                    data: data,
                    complete: function ({status,responseJSON }) {
                        if(status === 201){
                            let {message} = responseJSON
                            $('#addModal').modal('toggle')
                            dataTable.ajax.reload(onReloadComplete)
                            toastr.success(message, 'Success')
                        }
                    }
                })
            }
        }

        function submitEditForm(){
            editForm.validate({
                rules:{
                    member_id:{
                        required:true,
                        min:1,
                    },
                    seed_type_id: {
                        required:true,
                        min:1,
                    },
                    date: {
                        required:true,
                        date: true
                    },
                    currency_id: {
                        required:true,
                        min: 1
                    },
                    amount: {
                        required:true,
                        min: 0.01
                    },
                    title: {
                        required:true,
                        minlength: 3,
                        maxlength: 50
                    },
                },
                messages:{
                    seed_type_id:{
                        required:'{!! trans('custom_validation.select_option') !!}',
                        min: '{!! trans('custom_validation.select_option') !!}'
                    },
                    member_id: {
                        required:'{!! trans('custom_validation.select_member') !!}'
                    },
                    date: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        date:'{!! trans('custom_validation.valid_date') !!}'
                    },
                    currency_id: {
                        required:'{!! trans('custom_validation.select_currency') !!}',
                        min: '{!! trans('custom_validation.select_currency') !!}'
                    },
                    amount: {
                        required: '{!! trans('custom_validation.enter_amount') !!}',
                        min: '{!! trans('custom_validation.min_amount',['min' => 0.01]) !!}'
                    },
                    title: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 3]) !!}',
                        maxlength: '{!! trans('custom_validation.max_length',['max' => 50]) !!}',
                    }
                },
                errorPlacement: function (error, element) {
                    switch (element.attr('name')) {
                        case 'amount':
                            $('#editAmountError').html(error)
                            break;
                        case 'title':
                            $('#editTitleError').html(error)
                            break;
                        case 'date':
                            $('#editDateError').html(error)
                            break;
                        case 'seed_type_id':
                            $('#editTypeError').html(error)
                            break;
                        case 'currency_id':
                            $('#editCurrencyError').html(error)
                            break;
                        case 'member_id':
                            $('#editMemberError').html(error)
                            break;
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(editForm.valid()){
                let data = editForm.serializeArray()
                $.ajax({
                    url: ' {!! route('seeds.update') !!}',
                    method: 'patch',
                    data: data,
                    complete: function ({status,responseJSON }) {
                        if(status === 201){
                            let {message} = responseJSON
                            editModal.modal('toggle')
                            dataTable.ajax.reload(onReloadComplete)
                            toastr.info(message, 'Success')
                        }
                    }
                })
            }
        }

        function openAddModal($event){
            $event.preventDefault()
            let addModal = $('#addModal')
            addModal.modal('show')
            const memberId = $('#member_id')
            const date = $('#date')
            const currency = $('#currency_id')
            const amount = $('#amount')
            const submitBtn = $('#addSubmitBtn')
            memberId.select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('members.json') !!}',
                    type: 'post',
                    data: function(params){
                        return {
                            _token: '{!! csrf_token() !!}',
                            name: params.term,
                            page: params.page || 1
                        };
                    },
                    dataType: 'json',
                    cache:true,
                    delay:200,
                    placeholder: 'Search Member',
                    processResults: function(data,params){
                        params.page = params.page || 1;
                        const {total_items} = data;
                        return {
                            results: data.results,
                            pagination: {
                                more: (params.page * 10) < total_items
                            }
                        }
                    }
                }
            });
            memberId.on('change',function (event) {
                event.preventDefault()
                let value = $(this).val()
                if(value !== null && value !== 0){
                    $.ajax({
                        url: '{!! route('members.getByIdJson') !!}',
                        method: 'post',
                        data: {
                            _token: '{!!  csrf_token() !!}',
                            id: value
                        },
                        complete: function({status, responseJSON}){
                            if(status === 200){
                                const {member} = responseJSON
                                $('#member_image').attr('src',member.member_image)
                            }
                        }

                    })
                }
            })
            date.daterangepicker({
                singleDatePicker:true,
                autoUpdateInput: true,
                startDate: new Date(),
                showDropdowns: true,
                minYear: 1901,
                drops:'up',
                locale:datePickerTran,
                applyButtonClasses:'btn btn-teal btn-sm',
                cancelButtonClasses:'btn btn-danger btn-sm'
            })
        }

        function deleteSeed($event){
            $event.preventDefault()
            let removeModal = $('#removeModal')
            let member = $event.target.getAttribute('data-member')
            let title = $event.target.getAttribute('data-title')
            let amount = $event.target.getAttribute('data-amount')
            let currency = $event.target.getAttribute('data-currency')
            let seedId = $event.target.getAttribute('data-id')

            $('#confirm_seed').html(`${title}: ${member} - ${currency}${amount} `);
            $('input[name="remove_seed_id"]').val(seedId.toString());
            removeModal.modal('show')
        }

        function editSeed($event){
            $event.preventDefault()
            let seedId = parseInt($event.target.getAttribute('data-id'))
            $("#edit_seed_id").val(seedId)
            let data = {
                "_token": '{!! csrf_token() !!}',
                "seed_id": seedId
            }
            $.ajax({
                url: '{!! route('seeds.getById') !!}',
                method:'post',
                data:data,
                complete: function({status, responseJSON}){
                    const {seed} = responseJSON
                    if(status === 200){
                        console.log(seed)
                        $('#edit_amount').val(parseFloat((currency(seed.amount).intValue/100).toString()).toFixed(2))
                        setupEditMember(seed.member_id)
                        $('#edit_currency_id').val(`${seed.currency_id}`)
                        $('#edit_seed_type_id').val(`${seed.seed_type_id}`)
                        $('#edit_title').val(seed.title)
                        editModal.modal('show')
                        $('#edit_date').daterangepicker({
                            singleDatePicker:true,
                            autoUpdateInput: true,
                            startDate: seed.date,
                            showDropdowns: true,
                            minYear: 1901,
                            drops:'up',
                            locale:datePickerTran,
                            applyButtonClasses:'btn btn-teal btn-sm',
                            cancelButtonClasses:'btn btn-danger btn-sm'
                        })
                    }
                }
            })
        }

        function setupEditMember(memberId){
            let memberSelect = $('#edit_member_id').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('members.json') !!}',
                    type: 'post',
                    data: function(params){
                        return {
                            _token: '{!! csrf_token() !!}',
                            name:params.term
                        }
                    },
                    dataType: 'json',
                    cache:true,
                    delay:50,
                    placeholder: 'Member',
                    processResults: function(data){
                        return {
                            results: data.results,
                            pagination: {
                                more: false
                            }
                        }
                    }
                }
            });
            $.ajax({
                type: 'post',
                url: '{!! route('members.getByIdJson') !!}',
                data: {
                    _token: '{!! csrf_token() !!}',
                    id:memberId
                }
            }).then(function({member}){
                $('#edit_member_image').attr('src',member.member_image)
                let option = new Option(member.name,member.id,true, true)
                memberSelect.append(option).trigger('change')
                memberSelect.trigger({
                    type: 'select2:select',
                    params: {
                        data: option
                    }
                });

            });
        }


        function addTransaction(event){
            const seedId = event.target.getAttribute('data-id')
            $.ajax({
                url:'{!! route('seeds.getById') !!}',
                method:'post',
                data: {
                    _token: '{!! csrf_token() !!}',
                    seed_id:seedId
                },
                complete:function({status, responseJSON}){
                    if(status === 200){
                        const {seed} = responseJSON
                        $('#account_id').select2({
                            theme: 'bootstrap4',
                            allowClear:true,
                            placeholder:'{{trans('common.account')}}',
                            ajax: {
                                url: '{!! route('sub-accounts.list') !!}',
                                type: 'post',
                                data: function(params){
                                    return {
                                        _token: '{!! csrf_token() !!}',
                                        name: params.term,
                                        page: params.page || 1
                                    };
                                },
                                dataType: 'json',
                                cache:true,
                                delay:200,
                                processResults: function({total_items,results},params){
                                    params.page = params.page || 1;
                                    return {
                                        results: results,
                                        pagination: {
                                            more: (params.page * 10) < total_items
                                        }
                                    }
                                }
                            }
                        });
                        $('#transaction_date').daterangepicker({
                            singleDatePicker:true,
                            autoUpdateInput: true,
                            startDate: seed.date,
                            showDropdowns: true,
                            minYear: 1901,
                            locale:datePickerTran,
                            applyButtonClasses:'btn btn-teal btn-sm',
                            cancelButtonClasses:'btn btn-danger btn-sm'
                        })
                        addTransactionModal.modal('show')
                    }
                }
            })
        }

    </script>
@endsection
