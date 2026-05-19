@extends('layout.admin')

@section('content')
    <div class="row d-flex">
        <div class="col-8">
            <div class="card" style="border-radius: 10px;overflow: hidden">
                <div class="card-header bg-white">
                    <div class="d-flex flex-row justify-content-between">
                        <h5 class="font-weight-bold text-dark">{{trans('common.sub_account')}} Info</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col">
                            <div class="mb-2">
                                <div class="font-weight-bold text-dark mb-1">{{trans('common.account')}} {{trans('common.name_label')}}</div>
                                <span>{{$data['account']['name']}}</span>
                            </div>
                            <div class="mb-2">
                                <div class="font-weight-bold text-dark mb-1">{{trans('common.account')}} {{trans('common.currency_label')}}</div>
                                <span>{{$data['account']['currency']}}</span>
                            </div>
                            <div class="mb-2">
                                <div class="font-weight-bold text-dark mb-1">Account type</div>
                                <span>
                                    @switch($data['account']['account_type'])
                                        @case(1)
                                        {{trans('common.income_type_account')}}
                                        @break

                                        @case(2)
                                        {{trans('common.expense_type_account')}}
                                        @break
                                    @endswitch
                                </span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <div class="font-weight-bold text-dark mb-1">{{trans('common.account')}} {{trans('common.main_account')}}</div>
                                <span>{{$data['account']['parent_account']}}</span>
                            </div>
                            <div class="mb-2">
                                <div class="font-weight-bold text-dark mb-1">{{trans('common.account')}} {{trans('common.description_label')}}</div>
                                <span>{{$data['account']['description']}}</span>
                            </div>
                            <div class="mb-2">
                                <div class="font-weight-bold text-dark mb-1">{{trans('common.account')}} {{trans('common.status_label')}}</div>
                                @switch($data['account']['active'])
                                    @case(1)
                                    <span class="bg-success text-light rounded-pill  px-2">{{trans('common.active_label')}}</span>
                                    @break
                                    @case(0)
                                    <span class="bg-secondary text-dark rounded-pill  px-2">{{trans('common.inactive_label')}}</span>
                                    @break
                                @endswitch
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-4 flex-grow-1">
            <div class="card" style="height: 100%; border-radius: 10px;overflow: hidden">
                <div class="card-header bg-white d-flex flex-row">
                    <h5 class="font-weight-bold text-dark">{{trans('common.balance')}}</h5>
                    <i class="fa fa-coins text-warning ml-2" style="font-size: 18px"></i>
                </div>
                <div class="card-body p-4 d-flex">
                    <div class="d-flex flex-grow-1 justify-content-center align-items-center">
                        <div class="d-flex flex-row justify-content-center">
                            <div class="text-lg">
                                <h3 class="@if($data['account']['account_type'] == 1) text-success @else text-danger @endif font-weight-bold" id="balance"></h3>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col">
            <div class="card" style="border-radius: 10px;overflow: hidden">
                <div class="card-header d-flex flex-row justify-content-between bg-white">
                    <h5 class="text-dark"> <i class="fa fa-exchange-alt text-warning "></i> {{trans('common.transactions')}}</h5>
                    <div class="d-flex flex-row">
                        <form action="{{route('sub-accounts.export')}}" method="post">
                            @csrf
                            <input type="hidden" id="export_from_date" name="from_date">
                            <input type="hidden" id="export_to_date" name="to_date">
                            <input type="hidden" id="export_account_id" name="account_id" value="{{ $data['account']['id'] }}">
                            <button class="mr-2 btn btn-primary font-weight-bold text-light rounded font-weight-bol" disabled id="exportBtn" type="submit">
                                {{trans('common.export_to_excel_label')}}
                                <i class="ml-1 fas fa-file-excel"></i>
                            </button>
                        </form>
                        <button onclick="addTransaction(event)" class="btn btn-teal">{{trans('common.add_transaction')}} <i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="filter_date" class="col-form-label font-weight-bold">{{trans('common.date_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white">
                                                <i class="fa fa-calendar text-teal"></i>
                                            </div>
                                        </div>
                                        <input type="text" autocomplete="off" class="form-control" id="filter_date" name="date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col d-flex flex-column justify-content-center pt-3">
                            <div class="d-flex flex-row">
                                <button class="btn btn-teal text-light font-weight-normal mr-2" id="filterBtn">
                                    {{trans('common.filter_label')}}
                                    <i class="fas fa-filter ml-1"></i>
                                </button>
                                <button class="btn btn-danger text-light font-weight-normal" id="clearBtn">
                                    {{trans('common.cancel_label')}}
                                    <i class="fas fa-ban ml-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <table id="datatable" class="table table-bordered display compact nowrap">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>{{trans('common.description_label')}}</th>
{{--                                    <th style="width: 100px">--}}
{{--                                        {{trans('common.currency_label')}}--}}
{{--                                    </th>--}}
                                    <th>
                                        {{trans('common.amount_label')}}
                                    </th>
                                    <th>
                                        <i class="fa fa-calendar-alt text-teal mr-1"></i>
                                        {{trans('common.date_label')}}
                                    </th>
                                    <th style="width: 120px">
                                        <i class="fa fa-user text-teal mr-1"></i>
                                        {{trans('common.created_by')}}
                                    </th>
                                    <th style="width: 110px"></th>
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
                <div class=" mt-2 pl-3 pr-3">
                    <form method="post" action="#" id="addForm"  enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="account_id" value="{{ $data['account']['id'] }}" />
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
                                            <div class="input-group-text bg-white text-teal">
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
                        <input type="hidden" id="edit_transaction_id" name="transaction_id" />
                        <input type="hidden"  name="account_id" value="{{ $data['account']['id'] }}" />
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
                                    <div id="editDateError" class="customError"></div>
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
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white">
                                                <i class="fas fa-coins  text-warning"></i>
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
@endsection

@section('custom_js')
    <script>
        let fromDate = null
        let toDate = null

        const dateFilterEl = $('#filter_date')

        const filterBtn = $('#filterBtn')
        const clearBtn = $('#clearBtn')

        const exportToDate = $('#export_to_date')
        const exportFromDate = $('#export_from_date')

        const addModal = $('#addModal')
        const addForm = $('#addForm')

        const removeModal = $('#removeModal')
        const removeForm = $('#removeForm')

        const editModal = $('#editModal')
        const editForm = $('#editForm')
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
                    d.to_date = toDate
                    d.from_date = fromDate
                    d.showButton = false
                    d.account_id = {!! $data['account']['id'] !!}
                },
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'description', name: 'description' },
                // { data: 'currency', name: 'currency' },
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
            checkBalance()
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

            filterBtn.on('click',function(){
                console.log([toDate,fromDate])
                dataTable.ajax.reload(onReloadComplete)
            })
            clearBtn.on('click',function($event){
                dateFilterEl.val('')
                fromDate = null
                toDate = null
                dataTable.ajax.reload(onReloadComplete)
                exportFromDate.val(null)
                exportToDate.val(null)
            })


            {{--addForm.submit(function (event) {--}}
            {{--    event.preventDefault()--}}
            {{--    const data = new FormData(this);--}}
            {{--    $.ajax({--}}
            {{--        url: ' {!! route('transactions.store') !!}',--}}
            {{--        method: 'post',--}}
            {{--        processData: false,--}}
            {{--        contentType: false,--}}
            {{--        data: data,--}}
            {{--        complete: function ({status,responseJSON}) {--}}
            {{--            if(status === 200){--}}
            {{--                let message = responseJSON--}}
            {{--                dataTable.ajax.reload(onReloadComplete)--}}
            {{--                toastr.success(message,'Success')--}}
            {{--                addModal.modal('hide')--}}
            {{--                checkBalance()--}}
            {{--            }--}}
            {{--        }--}}
            {{--    })--}}
            {{--})--}}

            editForm.submit(function (event) {
                event.preventDefault()
                const data = new FormData(this)
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
                            checkBalance()
                        }
                    }
                })
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
                            checkBalance()
                        }
                    }
                })
            })
            $(".modal").on("hidden.bs.modal", function() {
                clearForm('addForm',false)
                clearForm('editForm',false)
            })
        })
        function submitAddForm(){
            addForm.validate({
                rules: {
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
                    date:{
                        required:true,
                        date:true
                    }
                },
                messages: {
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
                    date: {
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
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(addForm.valid()) {
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
                            checkBalance()
                        }
                    }
                })
            }
        }

        function submitEditForm(){
            editForm.validate({
                rules: {
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
                    date:{
                        required:true,
                        date:true
                    }
                },
                messages: {
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
                    date: {
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
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(editForm.valid()) {
                const data = new FormData(document.getElementById('editForm'));
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
                            checkBalance()
                        }
                    }
                })
            }
        }

        function addTransaction(){
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
                        if(transaction.attachment !== null && transaction.attachment !== undefined){
                            $('#edit_attachment').val(transaction.attachment)
                        }
                        $('#edit_amount').val((currency(transaction.amount).intValue)/100)
                        setupEditAccount(transaction.account_id)
                        editModal.modal('show')
                        console.log(transaction)
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
        function checkBalance(){
            $.ajax({
                url:'{!! route('sub-accounts.checkBalance') !!}',
                method:'post',
                data: {
                    _token: '{!! csrf_token() !!}',
                    account_id: parseInt({!! $data['account']['id'] !!})
                },
                complete:function({status, responseJSON}){
                    if(status === 200){
                        const {data} = responseJSON
                        const balance = $('#balance')
                        balance.html(currency(data.balance).format())
                    }
                }
            })
        }
    </script>
@endsection
