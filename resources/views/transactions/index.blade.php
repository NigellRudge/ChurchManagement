@extends('layout.admin')


@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-white d-flex flex-row justify-content-between">
                    <h5 class="font-weight-bold text-dark">{{trans('common.transactions_label')}}</h5>
                    <div class="d-flex flex-row">
                        <div class="mr-1">
                            <form action="{{ route('transactions.exportOverview') }}" method="post" id="exportForm">
                                @csrf
                                <input type="hidden" name="currency_id" id="export_currency_id">
                                <input type="hidden" name="account_id" id="export_account_id">
                                <input type="hidden" name="from_date" id="export_from_date">
                                <input type="hidden" name="to_date" id="export_to_date">
                                <button id="exportBtn" disabled class="btn btn-info">
                                    <i class="fa fa-file-excel mr-1"></i>
                                    {{trans('common.export_to_excel_label')}}
                                </button>
                            </form>
                        </div>
                        <button class="btn btn-teal" onclick="addTransaction(event)">
                            {{trans('common.add_transaction')}}
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="filter_account" class="col-form-label font-weight-bold" >{{trans('common.account_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white">
                                                <i class="fa fa-piggy-bank text-teal"></i>
                                            </div>
                                        </div>
                                        <select name="account" class="form-control" id="filter_account">
                                            <option value="0">{{trans('common.all_label')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-1 col-lg-1 col-md-2 col-sm-3">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="filter_currency" class="col-form-label font-weight-bold">{{trans('common.currency_label')}}</label>
                                    <select type="text" id="filter_currency" name="filter_currency_id" class="form-control">
                                        <option value="0">{{trans('common.all_label')}}</option>
                                        @foreach($data['currencies'] as $currency)
                                            <option value="{{$currency['id']}}">{{$currency['code']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="filter_from_date" class="col-form-label font-weight-bold">{{trans('common.date_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white">
                                                <i class="fa fa-calendar text-teal"></i>
                                            </div>
                                        </div>
                                        <input type="text" autocomplete="off" class="form-control" id="filter_from_date" name="from_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-1 col-lg-1 col-md-2 col-sm-3">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="tran_type" class="col-form-label font-weight-bold">{{trans('common.type_label')}}</label>
                                    <select type="text" autocomplete="off" class="form-control" id="tran_type" name="tran_type">
                                        <option value="0">{{trans('common.all')}}</option>
                                        <option value="{{config('constants.TRAN_TYPE_INCOME')}}">{{trans('common.income')}}</option>
                                        <option value="{{config('constants.TRAN_TYPE_EXPENSE')}}">{{trans('common.expense')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="min_amount"  class="col-form-label font-weight-bold">{{trans('common.min')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white">
                                                <i class="fa fa-arrow-up text-teal"></i>
                                            </div>
                                        </div>
                                        <input type="number" min="0.01" step="0.01" id="min_amount" name="min_amount" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="max_amount"  class="col-form-label font-weight-bold">{{trans('common.max')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white">
                                                <i class="fa fa-arrow-down text-teal"></i>
                                            </div>
                                        </div>
                                        <input type="number" min="0.01" step="0.01" id="max_amount" name="max_amount" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-5">
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
                    <div class="row">
                        <div class="col">
                            <table id="datatable" class="table table-bordered table-striped display compact nowrap">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>{{trans('common.description_label')}}</th>
                                    <th>
                                        <i class="fa fa-piggy-bank text-teal mr-1"></i>
                                        {{trans('common.account_label')}}
                                    </th>
                                    <th>
                                        <i class="fa fa-cog text-secondary mr-1"></i>
                                        {{trans('common.type_label')}}
                                    </th>
                                    <th>
                                        <i class="fa fa-coins text-warning mr-1"></i>
                                        {{trans('common.amount_label')}}
                                    </th>
                                    <th>
                                        <i class="fa fa-calendar-alt text-teal mr-1"></i>
                                        {{trans('common.date_label')}}
                                    </th>
                                    <th>
                                        <i class='fa fa-user-tie text-teal mr-2'></i>
                                        {{trans('common.created_by')}}
                                    </th>
                                    <th style="width: 140px"></th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.add_transaction')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class="mt-2 pl-3 pr-3">
                    <form method="post" action="#" id="addForm"  enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="add_tran_type" class="text-dark font-weight-bold">{{trans('common.type_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fa fa-cog"></i>
                                            </div>
                                        </div>
                                        <select class="form-control" name="tran_type" id="add_tran_type">
                                            <option value="0">{{trans('common.all')}}</option>
                                            <option value="{{config('constants.MAIN_ACCOUNT_TYPE_INCOME')}}">{{trans('common.income')}}</option>
                                            <option value="{{config('constants.MAIN_ACCOUNT_TYPE_EXPENSE')}}">{{trans('common.expense')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="add_account_id" class="text-dark font-weight-bold">{{trans('common.account_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fa fa-piggy-bank"></i>
                                            </div>
                                        </div>
                                        <select name="account_id" data-placeholder="{{trans('common.account')}}"  id="add_account_id" type="text" class="form-control">
                                        </select>
                                    </div>
                                    <div id="accountError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="add_description" class="text-dark font-weight-bold">{{trans('common.description_label')}}</label>
                                    <input type="text" id="add_description" placeholder="{{trans('common.placeholder_transaction_description')}}" name="description" class="form-control">
                                    <div id="descriptionError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="add_date" class="text-dark font-weight-bold">{{trans('common.date_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                        <input id="add_date" name="transaction_date" class="form-control" type="text" />
                                    </div>
                                    <div id="dateError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="add_attachment" class="text-dark font-weight-bold">{{trans('common.attachment')}}</label>
                                    <input id="add_attachment" name="attachment" class="form-control" type="file" />
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="add_amount"  class="text-dark font-weight-bold">{{trans('common.amount_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-warning">
                                                <i class="fas fa-coins"></i>
                                            </div>
                                        </div>
                                        <input  name="amount" step="0.01" min="0.01" max="100000000" id="add_amount" placeholder="$0.00" type="number" class="form-control" />
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
                    <h5 class="modal-title" id="editModalLabel">{{trans('common.edit_transaction')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class=" mt-2 pl-3 pr-3">
                    <form method="post" id="editForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="edit_transaction_id" name="transaction_id">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_account_id" class="text-dark font-weight-bold">{{trans('common.account_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fa fa-piggy-bank"></i>
                                            </div>
                                        </div>
                                        <select name="account_id" data-placeholder="{{trans('common.select_member_label')}}"  id="edit_account_id" type="text" class="form-control">
                                        </select>
                                    </div>
                                    <div id="editAccountError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_description" class="text-dark font-weight-bold">{{trans('common.description_label')}}</label>
                                    <input type="text" id="edit_description"  name="description" class="form-control">
                                    <div id="editDescriptionError" class="customError"></div>
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
                                        <input id="edit_date" name="transaction_date" class="form-control" type="text" />
                                    </div>
                                    <div id="editDatetError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_attachment" class="text-dark font-weight-bold">{{trans('common.attachment')}}<span class="text-danger">*</span></label>
                                    <input id="edit_attachment" name="attachment" class="form-control" type="file" />
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_amount"  class="text-dark font-weight-bold">{{trans('common.amount_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">l
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
                    <button onclick="submitEditForm()" class="btn btn-teal" id="editSubmitBtn">
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
                    <input type="hidden" name="transaction_id" id="remove_transaction_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-2">
                                {{trans('common.confirm_delete_transaction')}}:<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_transaction"></div> ?
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

    <div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="exampleModalLabel">{{trans('common.transaction_details')}}</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-2">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <div class="font-weight-bold text-dark mb-1">
                                        {{trans('common.transactions_label')}} {{trans('common.description_label') }}:
                                    </div>
                                    <div id="show_description"></div>
                                </div>
                                <div class="mb-3">
                                    <div class="font-weight-bold text-dark mb-1">
                                        <i class="fa fa-calendar mr-1 text-teal"></i>
                                        {{trans('common.transactions_label')}} {{trans('common.date_label')}}:
                                    </div>
                                    <div id="show_date"></div>
                                </div>
                                <div class="mb-3">
                                    <div class="font-weight-bold text-dark mb-1">
                                        <i class="fa fa-cog mr-1 text-teal"></i>
                                        {{trans('common.transaction_type')}}:
                                    </div>
                                    <div class="d-flex flex-row">
                                        <i class="fa fa-arrow-up text-success mr-1" id="show_transaction_type_icon_income"></i>
                                        <i class="fa fa-arrow-down text-danger mr-1" id="show_transaction_type_icon_expense"></i>
                                        <span id="show_transaction_type"></span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="font-weight-bold text-dark mb-1">
                                        <i class="fa fa-coins text-warning"></i>
                                        {{trans('common.amount_label')}}:
                                    </div>
                                    <div class="d-flex flex-row" >
                                        <span id="show_currency" class="mr-1 font-weight-bold text-dark"></span>
                                        <span id="show_sign" class="text-success font-weight-bold">$</span>
                                        <span id="show_amount"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <div class="font-weight-bold text-dark mb-1">
                                        <i class="fa fa-piggy-bank text-teal"></i>
                                        {{trans('common.main_account')}}:
                                    </div>
                                    <div id="show_main_account"></div>
                                </div>
                                <div class="mb-3">
                                    <div class="font-weight-bold text-dark mb-1">
                                        <i class="fa fa-wallet mr-1 text-teal"></i>
                                        {{trans('common.account')}}:
                                    </div>
                                    <div id="show_account"></div>
                                </div>
                                <div class="mb-3">
                                    <div class="font-weight-bold text-dark mb-1">
                                        <i class="fa fa-user text-teal"></i>
                                        {{trans('common.created_by')}}:
                                    </div>
                                    <div id="show_created_by"></div>
                                </div>
                                <div class="mb-3">
                                    <div class="font-weight-bold text-dark mb-1">
                                        <i class="fa fa-calendar-check text-teal"></i>
                                        {{trans('common.created_date')}}:
                                    </div>
                                    <div id="show_created_at"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{trans('common.close_label')}}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
<script>
    const addModal = $('#addModal')
    const addForm = $('#addForm')

    const removeModal = $('#removeModal')
    const removeForm = $('#removeForm')

    const editModal = $('#editModal')
    const editForm = $('#editForm')

    const showModal = $('#showModal')

    const exportCurrency = $('#export_currency_id')
    const exportToDate = $('#export_to_date')
    const exportFromDate = $('#export_from_date')
    const exportAccount = $('#export_account_id')

    const filterBtn = $('#filterBtn')
    const clearBtn = $('#clearBtn')

    const dateFilterEl = $('#filter_from_date')
    const tranTypeFilterEl = $('#tran_type')
    const filterCurrencyEl = $('#filter_currency')
    const accountFilterEl = $('#filter_account')
    const minAmountFilterEl = $('#min_amount')
    const maxAmountFilterEl = $('#max_amount')


    let currencyId = 0
    let fromDate = null
    let toDate = null
    let accountId = 0
    let minAmount = null
    let maxAmount = null
    let tranType = null

    const dataTable = $('#datatable').DataTable({
        processing: true,
        language: datatableTrans,
        autoWidth:false,
        serverSide: true,
        lengthMenu: [10, 25, 50, 75, 100 ],
        pageLength:10,
        ajax: {
            url:'{!! route('transactions.index') !!}',
            data: function(d){
                d.currency_id = currencyId
                d.from_date = fromDate
                d.account_id = accountId
                d.min_amount = minAmount
                d.max_amount = maxAmount
                d.tran_type = tranType
            },
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'description', name: 'description' },
            { data: 'account', name: 'account' },
            { data: 'tran_type_info', name: 'tran_type' },
            { data: 'amount_info', name: 'amount' },
            {data: 'transaction_date',name: 'transaction_date'},
            {data: 'created_by',name: 'created_by'},
            { data:'actions', name:'actions', orderable: false, searchable: false}
        ],
        initComplete:function (settings,json) {
            onReloadComplete(json)
        }
    })

    $(document).ready(function(){
        minAmountFilterEl.on('change', function(){
            let value = $(this).val()
            minAmount = value
            console.log(value)
        })
        maxAmountFilterEl.on('change', function(){
            let value = $(this).val()
            maxAmount = value
            console.log(value)

        })
        accountFilterEl.select2({
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
        accountFilterEl.on('change', function (event) {
            let value = $(this).val()
            accountId = value
            exportAccount.val(value)
        })
        filterBtn.on('click',function($event){
            console.log('clicked')
            dataTable.ajax.reload(onReloadComplete)
        })
        clearBtn.on('click',function($event){
            dateFilterEl.val('')
            tranTypeFilterEl.val(0)
            fromDate = null
            toDate = null
            currencyId = 0
            accountId = null
            minAmount = null
            maxAmount = null
            tranType = null
            accountFilterEl.val(null).trigger('change');
            filterCurrencyEl.val(0)
            minAmountFilterEl.val(null)
            maxAmountFilterEl.val(null)
            dataTable.ajax.reload(onReloadComplete)

            exportFromDate.val(null)
            exportToDate.val(null)
            exportAccount.val(null)
            exportCurrency.val(null)
        })
        filterCurrencyEl.on('change',function(){
            currencyId = this.value;
            exportCurrency.val(this.value)
        });
        tranTypeFilterEl.on('change',function(){
            tranType = this.value;
            console.log(tranType)
        });
        dateFilterEl.daterangepicker({
            singleDatePicker:false,
            autoUpdateInput: false,
            startDate: new Date(),
            showDropdowns: true,
            minYear: 1901,
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

        removeForm.submit(function (event) {
            event.preventDefault()
            let data = $(this).serialize()
            console.log(data)
            $.ajax({
                url:'{!! route('transactions.delete') !!}',
                method:'delete',
                data:data,
                complete: function({status , responseJSON}){
                    if(status === 200){
                        const {message} = responseJSON
                        toastr.warning(message,'{!! trans('common.success_label') !!}')
                        dataTable.ajax.reload(onReloadComplete)
                        removeModal.modal('hide')
                    }
                }
            })
        })

        $(".modal").on("hidden.bs.modal", function() {
            clearForm('addForm',false)
            clearForm('editForm',false)
            clearForm('removeForm',false)
        });

    })

    function submitAddForm(){
        addForm.validate({
            rules: {
                account_id:{
                  required:true
                },
                amount: {
                    required: true,
                    min: 0.01,
                    max: 1000000000
                },
                description: {
                    required: true,
                    maxlength: 100,
                    minlength: 3
                },
                transaction_date:{
                    required:true,
                    date:true
                }
            },
            messages: {
                account_id:{
                  required:'{!! trans('custom_validation.select_option') !!}'
                },
                amount: {
                    required: '{!! trans('custom_validation.required_field') !!}',
                    min: '{!! trans('custom_validation.min_length',['min' => 0.01]) !!}',
                    max: '{!! trans('custom_validation.max_length',['max' => 1000000000]) !!}',
                },
                description: {
                    required: '{!! trans('custom_validation.required_field') !!}',
                    minlength: '{!! trans('custom_validation.min_length',['min' => 4]) !!}',
                    maxlength: '{!! trans('custom_validation.max_length',['min' => 100]) !!}',
                },
                transaction_date: {
                    required: '{!! trans('custom_validation.select_option') !!}',
                    date: '{!! trans('custom_validation.valid_date') !!}'
                },
            },
            errorPlacement: function (error, element) {
                switch (element.attr('name')) {
                    case 'amount':
                        $('#amountError').html(error)
                        break;
                    case 'description':
                        $('#descriptionError').html(error)
                        break;
                    case 'date':
                        $('#dateError').html(error)
                        break;
                    case 'account_id':
                        $('#accountError').html(error)
                        break;
                }
            },
            errorClass: 'is-invalid',
            validClass: 'is-valid',
        })
        if(addForm.valid()){
            const data = new FormData(document.getElementById('addForm'));
            $.ajax({
                url: ' {!! route('transactions.store') !!}',
                method: 'post',
                processData: false,
                contentType: false,
                data: data,
                complete: function ({status,responseJSON}) {
                    if(status === 200){
                        let message = responseJSON
                        dataTable.ajax.reload(onReloadComplete)
                        toastr.success(message,'Success')
                        addModal.modal('hide')
                    }
                }
            })
        }
    }

    function submitEditForm(){
        editForm.validate({
            rules: {
                account_id:{
                    required:true
                },
                amount: {
                    required: true,
                    min: 0.01,
                    max: 1000000000
                },
                description: {
                    required: true,
                    maxlength: 100,
                    minlength: 3
                },
                transaction_date:{
                    required:true,
                    date:true
                }
            },
            messages: {
                account_id:{
                    required:'{!! trans('custom_validation.select_option') !!}'
                },
                amount: {
                    required: '{!! trans('custom_validation.required_field') !!}',
                    min: '{!! trans('custom_validation.min_length',['min' => 0.01]) !!}',
                    max: '{!! trans('custom_validation.max_length',['max' => 1000000000]) !!}',
                },
                description: {
                    required: '{!! trans('custom_validation.required_field') !!}',
                    minlength: '{!! trans('custom_validation.min_length',['min' => 4]) !!}',
                    maxlength: '{!! trans('custom_validation.max_length',['min' => 100]) !!}',
                },
                transaction_date: {
                    required: '{!! trans('custom_validation.select_option') !!}',
                    date: '{!! trans('custom_validation.valid_date') !!}'
                },
            },
            errorPlacement: function (error, element) {
                switch (element.attr('name')) {
                    case 'amount':
                        $('#editAmountError').html(error)
                        break;
                    case 'description':
                        $('#editDescriptionError').html(error)
                        break;
                    case 'date':
                        $('#editDateError').html(error)
                        break;
                    case 'account_id':
                        $('#editAccountError').html(error)
                        break;
                }
            },
            errorClass: 'is-invalid',
            validClass: 'is-valid',
        })
        if(editForm.valid()){
            const data = new FormData(document.getElementById('editForm'))
            console.log(data)
            $.ajax({
                url: ' {!! route('transactions.update') !!}',
                method: 'post',
                processData: false,
                contentType: false,
                data: data,
                complete: function ({status,responseJSON}) {
                    if(status === 200){
                        let message = responseJSON
                        dataTable.ajax.reload(onReloadComplete)
                        toastr.success(message,'Success')
                        editModal.modal('hide')
                    }
                }
            })
        }
    }

    function addTransaction(){

        $('#add_tran_type').on('change',function(){
            let accountTypeId = 0;
            let value = $(this).val()
            if(value === "1"){
                accountTypeId = value
                console.log('yup')
            }
            if(value === "2"){
                accountTypeId = value
                console.log('no')
            }
            if(value === 0){
                accountTypeId = null;
            }
            let accountId = $('#add_account_id');
            accountId.prop('disabled',false)
            accountId.select2({
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
                            page: params.page || 1,
                            account_type:accountTypeId
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
        })
        $('#add_date').daterangepicker({
            singleDatePicker:true,
            autoUpdateInput: true,
            startDate: new Date(),
            showDropdowns: true,
            minYear: 1901,
            locale:datePickerTran,
            applyButtonClasses:'btn btn-teal btn-sm',
            cancelButtonClasses:'btn btn-danger btn-sm'
        })
        $('#add_account_id').select2({
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
                        page: params.page || 1,
                        account_id:accountTypeId
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
        addModal.modal('show')
    }

    function editTransaction(event){
        const id = event.target.getAttribute('data-id')
        $.ajax({
            url:'{!! route('transactions.getById') !!}',
            method:'post',
            data: {
                _token: '{!! csrf_token() !!}',
                transaction_id:id
            },
            complete: function({status, responseJSON}){
                if(status === 200){
                    const {transaction} = responseJSON
                    $('#edit_description').val(transaction.description)
                    $('#edit_transaction_id').val(transaction.id)
                    $('#edit_date').daterangepicker({
                        singleDatePicker:true,
                        autoUpdateInput: true,
                        startDate: transaction.transaction_date,
                        showDropdowns: true,
                        minYear: 1901,
                        locale:datePickerTran,
                        applyButtonClasses:'btn btn-teal btn-sm',
                        cancelButtonClasses:'btn btn-danger btn-sm'
                    })
                    let tempVal = parseFloat((currency(transaction.amount).intValue /100).toString())
                    $('#edit_amount').val(tempVal)
                    setupEditAccount(transaction.account_id)
                    editModal.modal('show')
                    console.log(tempVal)
                }
            }
        })
        editModal.modal('show')
    }

    function deleteTransaction(event){
        let id = event.target.getAttribute('data-id')
        let description = event.target.getAttribute('data-description')
        $('#remove_transaction_id').val(id)
        $('#confirm_transaction').html(description)
        removeModal.modal('show')
    }

    function setupEditAccount(accountId){
        let accountSelect = $('#edit_account_id').select2({
            theme: 'bootstrap4',
            ajax: {
                url: '{!! route('sub-accounts.list') !!}',
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
                placeholder: '{!! trans('common.account') !!}',
                processResults: function(data){
                    console.log(data)
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
            type: 'POST',
            url: '{!! route('sub-accounts.getById') !!}',
            data: {
                _token: '{!! csrf_token() !!}',
                account_id:accountId
            }
        }).then(function(data){
            const {account} = data
            let option = new Option(account.name,account.id,true, true)
            accountSelect.append(option).trigger('change')
            accountSelect.trigger({
                type: 'select2:select',
                params: {
                    data: data
                }
            });
        });
    }

    function transactionInfo(event){
        const id = event.target.getAttribute('data-id')
        $.ajax({
            url:'{!! route('transactions.getById') !!}',
            method:'post',
            data: {
                _token:'{!! csrf_token() !!}',
                transaction_id:id
            },
            complete: function({status, responseJSON}){
                if(status === 200){
                    const {transaction} = responseJSON
                    console.log(transaction)
                    $('#show_description').html(transaction.description)
                    $('#show_date').html(transaction.transaction_date)
                    $('#show_currency').html(transaction.currency)
                    $('#show_amount').html(`${transaction.amount}`)
                    $('#show_main_account').html(transaction.main_account)
                    $('#show_account').html(transaction.account)
                    $('#show_created_by').html(transaction.created_by)
                    $('#show_created_at').html(transaction.created_at)
                    const signElement = $('#show_sign')
                    const transactionTypeElement = $('#show_transaction_type')
                    const incomeIconElement = $('#show_transaction_type_icon_income')
                    const expenseIconElement = $('#show_transaction_type_icon_expense')
                    signElement.removeClass('text-success')
                    signElement.removeClass('text-danger')
                    expenseIconElement.addClass('d-none')
                    incomeIconElement.addClass('d-none')
                    switch (parseInt(transaction.tran_type)) {
                        case 1:
                            signElement.addClass('text-success')
                            incomeIconElement.removeClass('d-none')
                            transactionTypeElement.html('{!! trans('common.income') !!}')
                            break;
                        case 2:
                            signElement.addClass('text-danger')
                            expenseIconElement.removeClass('d-none')
                            transactionTypeElement.html('{!! trans('common.expense') !!}')
                            break;
                        default:
                            break;
                    }
                    showModal.modal('show')
                }
            }
        })
    }


</script>
@endsection
