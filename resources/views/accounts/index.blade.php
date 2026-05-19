@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-white d-flex flex-row justify-content-between">
                    <h5 class="font-weight-bold text-dark">{{trans('common.main_accounts')}}</h5>
                    <button class="btn btn-teal" onclick="addAccount(event)">
                        {{trans('common.add_main_account')}}
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4">
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
                        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="filter_account" class="col-form-label font-weight-bold">{{trans('common.account_type')}}</label>
                                    <select type="text" id="filter_account" name="filter_account" class="form-control">
                                        <option value="0">{{trans('common.all_label')}}</option>
                                        <option value="1">{{trans('common.income_type_account')}}</option>
                                        <option value="2">{{trans('common.expense_type_account')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="filter_status" class="col-form-label font-weight-bold">{{trans('common.status_label')}}</label>
                                    <select type="text" id="filter_status" name="filter_status" class="form-control">
                                        <option value="1" selected>{{trans('common.active_label')}}</option>
                                        <option value="2">{{trans('common.inactive_label')}}</option>
                                        <option value="0">{{trans('common.all_label')}}</option>
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
                    <div class="row">
                        <div class="col">
                            <table id="datatable" class="table table-bordered display nowrap">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>{{trans('common.name_label')}}</th>
                                    <th>
                                        <i class="fa fa-dollar-sign mr-1 text-teal"></i>
                                        {{trans('common.currency_label')}}
                                    </th>
{{--                                    <th>--}}
{{--                                        <i class="fa fa-arrow-up mr-1 text-success"></i>--}}
{{--                                        {{trans('common.total')}} {{trans('common.debit')}}--}}
{{--                                    </th>--}}
{{--                                    <th>--}}
{{--                                        <i class="fa fa-arrow-down mr-1 text-danger"></i>--}}
{{--                                        {{trans('common.total')}} {{trans('common.credit')}}--}}
{{--                                    </th>--}}
                                    <th>
                                        <i class="fa fa-coins mr-1 text-warning"></i>
                                        {{trans('common.balance')}}
                                    </th>
                                    <th>
                                        {{trans('common.status_label')}}
                                    </th>
                                    <th style="width: 120px"></th>
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
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.add_main_account')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                    <div class=" mt-2 pl-3 pr-3">
                    <form method="post" action="#" id="addForm">
                        @csrf
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="add_name" class="text-dark font-weight-bold">{{trans('common.name_label')}}<span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="add_name" class="form-control">
                                    <div id="nameError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="add_description" class="text-dark font-weight-bold">{{trans('common.description_label')}}</label>
                                    <input type="text" id="add_description" placeholder="{{trans('common.seed_place_holder_label')}}" name="description" class="form-control">
                                    <div id="descriptionError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="add_account_type" class="text-dark font-weight-bold">{{trans('common.account_type')}}<span class="text-danger">*</span></label>
                                    <select name="account_type" id="add_account_type" class="form-control">
                                        <option value="" disabled selected>{{trans('common.select_option')}}</option>
                                        <option value="{{config('constants.MAIN_ACCOUNT_TYPE_EXPENSE')}}">{{trans('common.expense_type_account')}}</option>
                                        <option value="{{config('constants.MAIN_ACCOUNT_TYPE_INCOME')}}">{{trans('common.income_type_account')}}</option>
                                    </select>
                                    <div id="typeError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="currency_id" class="text-dark font-weight-bold">{{trans('common.currency_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-dollar-sign"></i>
                                            </div>
                                        </div>
                                        <select name="currency_id" id="currency_id" data-placeholder="Select currency" class="form-control">
                                            <option value="" disabled selected>{{trans('common.select_option')}}</option>
                                            @foreach($data['currencies'] as $currency)
                                                <option value="{{$currency->id}}">{{$currency->code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="currencyError" class="customError"></div>
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

    <div class="modal fade" id="deactivateModal" tabindex="-1" role="dialog" aria-labelledby="deactivateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-light" style='background-color: #fd7400'>
                    <h5 class="modal-title" id="deactivateModalLabel">{{trans('common.confirm_label')}}</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="deactivateForm">
                    @csrf
                    <input type="hidden" name="account_id" id="deactivate_account_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="mr-2 ml-1" style="font-size: 3.0rem;color: #fd7400" >
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-2">
                                {{trans('common.confirm_deactivate_main_account')}}:<br>
                                <div class="d-inline text-dark font-weight-bold" id="confirm_deactivate_account"></div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn  text-light" style='background-color: #fd7400'>
                            <span class="mr-1"><i class="fa fa-trash"></i></span>
                            {{trans('common.yes_label')}}
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{trans('common.no_label')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-light">
                    <h5 class="modal-title" id="deactivateModalLabel">{{trans('common.confirm_label')}}</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="deleteForm">
                    @csrf
                    <input type="hidden" name="account_id" id="delete_account_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-danger mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="">
                                {{trans('common.confirm_delete_main_account')}}<br>
                                {{trans('common.all')}}
                                <span class="text-danger font-weight-bold">{{trans('common.sub_accounts')}}</span>
                                {{trans('common.and')}}
                                <span class="text-danger font-weight-bold">{{trans('common.transactions')}}</span> {{trans('common.will_be_removed')}}!!<br/>
                                <div class="d-inline text-dark mt-2">
                                    {{trans('Account')}}: <span class="font-weight-bold" id="confirm_delete_account"></span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">
                            <span class="mr-1"><i class="fa fa-trash"></i></span>
                            {{trans('common.yes_label')}}
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{trans('common.no_label')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reactivateModal" tabindex="-1" role="dialog" aria-labelledby="reactivateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="reactivateModalLabel">{{trans('common.confirm_label')}}</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="reactivateForm">
                    @csrf
                    <input type="hidden" name="account_id" id="reactivate_account_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="mr-2 ml-1 text-teal" style="font-size: 3.0rem" >
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-2">
                                {{trans('common.confirm_reactivate_main_account')}}:<br>
                                <div class="d-inline text-dark font-weight-bold" id="confirm_reactivate_account"></div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal  text-light">
                            <span class="mr-1"><i class="fa fa-trash"></i></span>
                            {{trans('common.yes_label')}}
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{trans('common.no_label')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="editModalLabel">{{trans('common.edit_main_account')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>

                    <div class=" mt-2 pl-3 pr-3">
                        <form method="post" action="#" id="editForm">
                            @csrf
                            <input type="hidden" name="account_id" id="edit_account_id">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_name" class="text-dark font-weight-bold">{{trans('common.name_label')}}<span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="edit_name" class="form-control">
                                    <div id="editNameError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_description" class="text-dark font-weight-bold">{{trans('common.description_label')}}</label>
                                    <input type="text" id="edit_description" placeholder="{{trans('common.seed_place_holder_label')}}" name="description" class="form-control">
                                    <div id="editDescriptionError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_account_type" class="text-dark font-weight-bold">{{trans('common.account_type')}}<span class="text-danger">*</span></label>
                                    <select name="account_type" id="edit_account_type" class="form-control">
                                        <option value="" disabled selected>{{trans('common.select_option')}}</option>
                                        <option value="{{config('constants.MAIN_ACCOUNT_TYPE_EXPENSE')}}">{{trans('common.expense_type_account')}}</option>
                                        <option value="{{config('constants.MAIN_ACCOUNT_TYPE_INCOME')}}">{{trans('common.income_type_account')}}</option>
                                    </select>
                                    <div id="editTypeError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group mb-1">
                                    <label for="edit_currency_id" class="text-dark font-weight-bold">{{trans('common.currency_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-dollar-sign"></i>
                                            </div>
                                        </div>
                                        <select disabled style="border-bottom-right-radius: 0.35rem;border-top-right-radius: 0.35rem" name="currency_id" id="edit_currency_id" data-placeholder="Select currency" class="form-control">
                                            <option value="0">{{trans('common.select_currency_label')}}</option>
                                            @foreach($data['currencies'] as $currency)
                                                <option value="{{$currency->id}}">{{$currency->code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="editCurrencyError" class="customError"></div>
                                </div>
                                <div class="mb-2">
                                    <span id="currency_edit_message" style="font-size: 0.8rem" class="text-danger px-2 pt-0 d-none">{{trans('common.cant_change_currency')}}</span>
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

@endsection


@section('custom_js')
    <script>
        const addForm  = $('#addForm')
        const addModal  = $('#addModal')

        const deactivateForm  = $('#deactivateForm')
        const deactivateModal  = $('#deactivateModal')

        const editForm  = $('#editForm')
        const editModal = $('#editModal')

        const deleteForm = $('#deleteForm')
        const deleteModal = $('#deleteModal')

        const reactivateForm = $('#reactivateForm')
        const reactivateModal = $('#reactivateModal')

        const currencyFilterEl = $('#filter_currency_id')
        const accountFilterEl = $('#filter_account')
        const statusFilterEl = $('#filter_status')

        const filterBtn = $('#filterBtn')
        const clearBtn = $('#clearBtn')

        let currencyId = 0
        let accountType = 0
        let status = 1

        const dataTable = $('#datatable').DataTable({
            processing: true,
            language: datatableTrans,
            autoWidth: false,
            serverSide: true,
            lengthMenu: [10, 25, 50, 75, 100],
            pageLength: 10,
            ajax: {
                url: '{!! route('accounts.index') !!}',
                data: function (d) {
                    d.currency_id = currencyId
                    d.account_type = accountType
                    d.status = status
                },
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'currency', name: 'currency'},
                // {data: 'debit_info', name: 'debit_info'},
                // {data: 'credit_info', name: 'credit_info'},
                {data: 'balance_info', name: 'balance_info'},
                {data: 'status_info', name: 'status_info'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false}
            ],
            initComplete: function (settings, json) {
                onReloadComplete(json)
            }
        })
        $(document).ready(function(){
            currencyFilterEl.on('change',function () {
                const value = this.value
                if(value !== 0){
                   currencyId = parseInt(value)
                }
            })
            accountFilterEl.on('change',function () {
                const value = this.value
                if(value !== 0){
                    accountType = parseInt(value)
                }
            })
            statusFilterEl.on('change', function(){
                status = this.value
            })
            filterBtn.on('click',function($event){
                console.log('clicked')
                dataTable.ajax.reload(onReloadComplete)
            })
            clearBtn.on('click',function($event){
                currencyId = 0
                accountType = 0
                status = 1
                currencyFilterEl.val(0)
                accountFilterEl.val(0)
                statusFilterEl.val(1)
                dataTable.ajax.reload(onReloadComplete)
            })



            {{--addForm.validate({--}}
            {{--    rules: {--}}
            {{--        name: {--}}
            {{--            required: true,--}}
            {{--            minlength: 4,--}}
            {{--            maxlength: 25--}}
            {{--        },--}}
            {{--        description: {--}}
            {{--            required: true,--}}
            {{--            maxlength: 50,--}}
            {{--            minlength: 4--}}
            {{--        },--}}
            {{--        currency_id: {--}}
            {{--            required: true,--}}
            {{--        },--}}
            {{--        account_type: {--}}
            {{--            required: true--}}
            {{--        }--}}
            {{--    },--}}
            {{--    messages: {--}}
            {{--        name: {--}}
            {{--            required: '{!! trans('custom_validation.required_field') !!}',--}}
            {{--            minlength: '{!! trans('custom_validation.min_length',['min' => 4]) !!}',--}}
            {{--            maxlength: '{!! trans('custom_validation.max_length',['min' => 25]) !!}',--}}
            {{--        },--}}
            {{--        description: {--}}
            {{--            required: '{!! trans('custom_validation.required_field') !!}',--}}
            {{--            minlength: '{!! trans('custom_validation.min_length',['min' => 4]) !!}',--}}
            {{--            maxlength: '{!! trans('custom_validation.max_length',['min' => 50]) !!}',--}}
            {{--        },--}}
            {{--        currency_id: {--}}
            {{--            required: '{!! trans('custom_validation.select_option') !!}'--}}
            {{--        },--}}
            {{--        account_type: {--}}
            {{--            required: '{!! trans('custom_validation.select_option') !!}'--}}
            {{--        }--}}
            {{--    },--}}
            {{--    errorPlacement: function (error, element) {--}}
            {{--        switch (element.attr('name')) {--}}
            {{--            case 'name':--}}
            {{--                $('#nameError').html(error)--}}
            {{--                break;--}}
            {{--            case 'description':--}}
            {{--                $('#descriptionError').html(error)--}}
            {{--                break;--}}
            {{--            case 'currency_id':--}}
            {{--                $('#currencyError').html(error)--}}
            {{--                break;--}}
            {{--            case 'account_type':--}}
            {{--                $('#typeError').html(error)--}}
            {{--                break;--}}
            {{--        }--}}
            {{--    },--}}
            {{--    errorClass: 'is-invalid',--}}
            {{--    validClass: 'is-valid',--}}
            {{--})--}}
            {{--addForm.submit(function(event){--}}
            {{--    event.preventDefault()--}}
            {{--    const data = $(this).serialize()--}}
            {{--    $.ajax({--}}
            {{--        url:'{!! route('accounts.store') !!}',--}}
            {{--        method:'post',--}}
            {{--        data:data,--}}
            {{--        complete:function({status,responseJSON}){--}}
            {{--            if(status === 200){--}}
            {{--                const {message} = responseJSON--}}
            {{--                toastr.success(message, '{!! trans('common.success_label') !!}')--}}
            {{--                dataTable.ajax.reload(onReloadComplete)--}}
            {{--                addModal.modal('hide')--}}
            {{--            }--}}
            {{--        }--}}
            {{--    })--}}
            {{--})--}}

            {{--editForm.validate({--}}
            {{--    rules: {--}}
            {{--        name: {--}}
            {{--            required: true,--}}
            {{--            minlength: 4,--}}
            {{--            maxlength: 25--}}
            {{--        },--}}
            {{--        description: {--}}
            {{--            required: true,--}}
            {{--            maxlength: 50,--}}
            {{--            minlength: 4--}}
            {{--        },--}}
            {{--        currency_id: {--}}
            {{--            required: true,--}}
            {{--        },--}}
            {{--        account_type: {--}}
            {{--            required: true--}}
            {{--        }--}}
            {{--    },--}}
            {{--    messages: {--}}
            {{--        name: {--}}
            {{--            required: '{!! trans('custom_validation.required_field') !!}',--}}
            {{--            minlength: '{!! trans('custom_validation.min_length',['min' => 4]) !!}',--}}
            {{--            maxlength: '{!! trans('custom_validation.max_length',['min' => 25]) !!}',--}}
            {{--        },--}}
            {{--        description: {--}}
            {{--            required: '{!! trans('custom_validation.required_field') !!}',--}}
            {{--            minlength: '{!! trans('custom_validation.min_length',['min' => 4]) !!}',--}}
            {{--            maxlength: '{!! trans('custom_validation.max_length',['min' => 50]) !!}',--}}
            {{--        },--}}
            {{--        currency_id: {--}}
            {{--            required: '{!! trans('custom_validation.select_option') !!}'--}}
            {{--        },--}}
            {{--        account_type: {--}}
            {{--            required: '{!! trans('custom_validation.select_option') !!}'--}}
            {{--        }--}}
            {{--    },--}}
            {{--    errorPlacement: function (error, element) {--}}
            {{--        switch (element.attr('name')) {--}}
            {{--            case 'name':--}}
            {{--                $('#editNameError').html(error)--}}
            {{--                break;--}}
            {{--            case 'description':--}}
            {{--                $('#editDescriptionError').html(error)--}}
            {{--                break;--}}
            {{--            case 'currency_id':--}}
            {{--                $('#editCurrencyError').html(error)--}}
            {{--                break;--}}
            {{--            case 'account_type':--}}
            {{--                $('#editTypeError').html(error)--}}
            {{--                break;--}}
            {{--        }--}}
            {{--    },--}}
            {{--    errorClass: 'is-invalid',--}}
            {{--    validClass: 'is-valid',--}}
            {{--})--}}
            {{--editForm.submit(function(event){--}}
            {{--    event.preventDefault()--}}
            {{--    $('#edit_currency_id').prop('disabled',false)--}}
            {{--    const data = $(this).serialize()--}}
            {{--    console.log(data);--}}
            {{--    $.ajax({--}}
            {{--        url:'{!! route('accounts.update') !!}',--}}
            {{--        method:'patch',--}}
            {{--        data:data,--}}
            {{--        complete:function({status,responseJSON}){--}}
            {{--            if(status === 200){--}}
            {{--                const {message} = responseJSON--}}
            {{--                toastr.info(message, '{!! trans('common.success_label') !!}')--}}
            {{--                dataTable.ajax.reload(onReloadComplete)--}}
            {{--                editModal.modal('hide')--}}
            {{--            }--}}
            {{--        }--}}
            {{--    })--}}
            {{--})--}}

            deactivateForm.submit(function(event){
                event.preventDefault()
                const data = $(this).serialize()
                $.ajax({
                    url:'{!! route('accounts.deactivate') !!}',
                    method:'delete',
                    data:data,
                    complete:function({status,responseJSON}){
                        if(status === 200){
                            const {message} = responseJSON
                            toastr.warning(message, '{!! trans('common.success_label') !!}')
                            dataTable.ajax.reload(onReloadComplete)
                            deactivateModal.modal('hide')
                        }
                    }
                })
            })

            reactivateForm.submit(function(event){
                event.preventDefault()
                const data = $(this).serialize()
                $.ajax({
                    url:'{!! route('accounts.reactivate') !!}',
                    method:'post',
                    data:data,
                    complete:function({status,responseJSON}){
                        if(status === 200){
                            const {message} = responseJSON
                            toastr.warning(message, '{!! trans('common.success_label') !!}')
                            dataTable.ajax.reload(onReloadComplete)
                            reactivateModal.modal('hide')
                        }
                    }
                })
            })

            deleteForm.submit(function (event) {
                event.preventDefault()
                const data = $(this).serialize()
                $.ajax({
                    url:'{!! route('accounts.destroy') !!}',
                    method:'delete',
                    data:data,
                    complete: function({status, responseJSON}){
                        if(status === 200){
                            const {message} = responseJSON
                            toastr.warning(message, '{!! trans('common.success_label') !!}')
                            dataTable.ajax.reload(onReloadComplete)
                            deleteModal.modal('hide')
                        }
                    }
                })
            })

            $(".modal").on("hidden.bs.modal", function() {
                clearForm('addForm',false)
                clearForm('editForm',false)
            });

        });

        function submitAddForm(){
            addForm.validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 4,
                        maxlength: 25
                    },
                    description: {
                        required: true,
                        maxlength: 50,
                        minlength: 4
                    },
                    currency_id: {
                        required: true,
                    },
                    account_type: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: '{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 4]) !!}',
                        maxlength: '{!! trans('custom_validation.max_length',['min' => 25]) !!}',
                    },
                    description: {
                        required: '{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 4]) !!}',
                        maxlength: '{!! trans('custom_validation.max_length',['min' => 50]) !!}',
                    },
                    currency_id: {
                        required: '{!! trans('custom_validation.select_option') !!}'
                    },
                    account_type: {
                        required: '{!! trans('custom_validation.select_option') !!}'
                    }
                },
                errorPlacement: function (error, element) {
                    switch (element.attr('name')) {
                        case 'name':
                            $('#nameError').html(error)
                            break;
                        case 'description':
                            $('#descriptionError').html(error)
                            break;
                        case 'currency_id':
                            $('#currencyError').html(error)
                            break;
                        case 'account_type':
                            $('#typeError').html(error)
                            break;
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(addForm.valid()){
                const data = addForm.serialize()
                $.ajax({
                    url:'{!! route('accounts.store') !!}',
                    method:'post',
                    data:data,
                    complete:function({status,responseJSON}){
                        if(status === 200){
                            const {message} = responseJSON
                            toastr.success(message, '{!! trans('common.success_label') !!}')
                            dataTable.ajax.reload(onReloadComplete)
                            addModal.modal('hide')
                        }
                    }
                })
            }
        }

        function submitEditForm(){
            editForm.validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 4,
                        maxlength: 25
                    },
                    description: {
                        required: true,
                        maxlength: 50,
                        minlength: 4
                    },
                    currency_id: {
                        required: true,
                    },
                    account_type: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: '{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 4]) !!}',
                        maxlength: '{!! trans('custom_validation.max_length',['min' => 25]) !!}',
                    },
                    description: {
                        required: '{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 4]) !!}',
                        maxlength: '{!! trans('custom_validation.max_length',['min' => 50]) !!}',
                    },
                    currency_id: {
                        required: '{!! trans('custom_validation.select_option') !!}'
                    },
                    account_type: {
                        required: '{!! trans('custom_validation.select_option') !!}'
                    }
                },
                errorPlacement: function (error, element) {
                    switch (element.attr('name')) {
                        case 'name':
                            $('#editNameError').html(error)
                            break;
                        case 'description':
                            $('#editDescriptionError').html(error)
                            break;
                        case 'currency_id':
                            $('#editCurrencyError').html(error)
                            break;
                        case 'account_type':
                            $('#editTypeError').html(error)
                            break;
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(editForm.valid()){
                $('#edit_currency_id').prop('disabled',false)
                const data = editForm.serialize()
                $.ajax({
                    url:'{!! route('accounts.update') !!}',
                    method:'patch',
                    data:data,
                    complete:function({status,responseJSON}){
                        if(status === 200){
                            const {message} = responseJSON
                            toastr.info(message, '{!! trans('common.success_label') !!}')
                            dataTable.ajax.reload(onReloadComplete)
                            editModal.modal('hide')
                        }
                    }
                })
            }
        }

        function addAccount(event){
            addModal.modal('show')
        }

        function deactivateAccount(event){
            const id = event.target.getAttribute('data-id')
            const name = event.target.getAttribute('data-name')
            $('#deactivate_account_id').val(id)
            $('#confirm_deactivate_account').html(name)
            deactivateModal.modal('show')
        }

        function reactivateAccount(event){
            const id = event.target.getAttribute('data-id')
            const name = event.target.getAttribute('data-name')
            $('#reactivate_account_id').val(id)
            $('#confirm_reactivate_account').html(name)
            console.log([id,name])
            reactivateModal.modal('show')
        }

        function deleteAccount(event){
            const id = event.target.getAttribute('data-id')
            const name = event.target.getAttribute('data-name')
            $('#delete_account_id').val(id)
            $('#confirm_delete_account').html(name)
            deleteModal.modal('show')
        }

        function editAccount(event){
            const id = event.target.getAttribute('data-id')
            $.ajax({
                url:'{{route('accounts.getById')}}',
                method:'post',
                data: {
                    _token: '{!! csrf_token() !!}',
                    account_id:id
                },
                complete: function({status, responseJSON}){
                    if(status === 200){
                        const {account} = responseJSON
                        $('#edit_account_id').val(account.id)
                        $('#edit_name').val(account.name)
                        $('#edit_description').val(account.description)
                        $('#edit_account_type').val(account.account_type)
                        $('#edit_currency_id').val(account.currency_id)
                        if(account.can_edit === 1){
                            $('#edit_currency_id').prop('disabled',false)
                            $('#currency_edit_message').addClass('d-none')
                        }
                        else{
                            $('#edit_currency_id').prop('disabled',true)
                            $('#currency_edit_message').removeClass('d-none')
                        }
                        editModal.modal('show')
                    }
                }
            })
        }

    </script>
@endsection
