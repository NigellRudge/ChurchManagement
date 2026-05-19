@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-white d-flex flex-row justify-content-between">
                    <div class="card-title text-lg font-weight-bold text-dark">{{trans('common.title_label')}}: {{$data['budget']['name']}}</div>
                    <div class="d-flex flex-row">
                        <form action="{!! route('budgets.export',['budget' => $data['budget']['id']]) !!}" method="post">
                            @csrf
                            <button id="exportBtn" type="submit" class="btn btn-info font-weight-normal">
                                <i class="fa fa-file-excel mr-1"></i>
                                {{trans('common.export_to_excel_label')}}
                            </button>
                        </form>
                        <button class="btn btn-teal ml-1" onclick="addItem(event)">
                            {{trans('common.add_expense')}}
                            <i class="fa fa-plus ml-1"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-bordered display compact nowrap">
                        <thead>
                        <tr>
                            <th style="width: 80px">Id</th>
                            <th>
                                {{trans('common.name_label')}}
                            </th>
                            <th>
                                {{trans('common.description_label')}}
                            </th>
                            <th>
                                <span class="mr-1"><i class="fa fa-coins text-warning"></i></span>
                                {{trans('common.amount_label')}}
                            </th>
                            <th>
                                <span class="mr-1"><i class="fa fa-dollar-sign text-success"></i></span>
                                {{trans('common.amount_base_currency')}}
                            </th>
                            <th>
                                {{trans('common.created_by')}}
                            </th>
                            <th style="width: 100px"></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light font-weight-normal">
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.add_expense')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="font-weight-bold text-light">&times;</span>
                    </button>
                </div>
                    <div class="py-3 px-4">
                        <form method="post" action="#" id="addForm">
                            @csrf
                            <input type="hidden" name="budget_id" id="add_budget_id" value="{{$data['budget']['id']}}">
                        <div class="form-row">
                            <div class="col">
                                <div class="from-group">
                                    <label for="name" class="font-weight-bold text-dark">{{trans('common.name_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                    <input  type="text" id="name" name="name" class="form-control" />
                                    <div id="nameError" class="customButton"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col">
                                <div class="from-group">
                                    <label for="add_description" class="font-weight-bold text-dark">{{trans('common.description_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                    <textarea  class="form-control" id="add_description" name="description" rows="4" ></textarea>
                                    <div id="descriptionError" class="customButton"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col-4">
                                <div class="from-group">
                                    <label for="add_currency_id" class="font-weight-bold text-dark">{{trans('common.currency_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                    <select type="text" id="add_currency_id" name="currency_id" class="form-control">
                                        <option value="" disabled selected>{{trans('common.select_option')}}</option>
                                        @foreach( $data['currencies'] as $currency)
                                            <option value="{{ $currency->id }}">{{$currency->code}}</option>
                                        @endforeach
                                    </select>
                                    <div id="currencyError" class="customButton"></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="from-group">
                                    <label for="add_amount" class="font-weight-bold text-dark">{{trans('common.amount_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text text-warning bg-white">
                                                <i class="fa fa-coins"></i>
                                            </div>
                                        </div>
                                        <input type="number" id="add_amount" name="amount" class="form-control"/>
                                    </div>
                                    <div id="amountError" class="customButton"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button onclick="submitAddForm()" class="btn btn-teal font-weight-normal text-light">
                        <span class="mr-1"><i class="fa fa-save"></i></span>
                        {{trans('common.save_label')}}
                    </button>
                    <button type="button" class="btn btn-danger font-weight-normal text-light">
                        <span class="mr-1"><i class="fa fa-ban"></i></span>
                        {{trans('common.cancel_label')}}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light font-weight-normal">
                    <h5 class="modal-title" id="editModalLabel">{{trans('common.edit_expense')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="font-weight-bold text-light">&times;</span>
                    </button>
                </div>
                <div class="py-3 px-4">
                    <form method="post" id="editForm">
                        @csrf
                        <input type="hidden" name="budget_id" id="add_budget_id" value="{{$data['budget']['id']}}">
                        <input type="hidden" name="item_id" id="edit_item_id">
                    <div class="form-row">
                        <div class="col">
                            <div class="from-group">
                                <label for="edit_name" class="font-weight-bold text-dark">{{trans('common.name_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                <input type="text" id="edit_name" name="name" class="form-control" />
                                <div id="editNameError" class="customButton"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row mt-2">
                        <div class="col">
                            <div class="from-group">
                                <label for="edit_description" class="font-weight-bold text-dark">{{trans('common.description_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                <textarea  class="form-control" id="edit_description" name="description" rows="4" ></textarea>
                                <div id="editDescriptionError" class="customButton"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row mt-2">
                        <div class="col-4">
                            <div class="from-group">
                                <label for="edit_currency_id" class="font-weight-bold text-dark">{{trans('common.currency_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                <select type="text" id="edit_currency_id" name="currency_id" class="form-control">
                                    <option value="" disabled selected>{{trans('common.select_option')}}</option>
                                    @foreach( $data['currencies'] as $currency)
                                        <option value="{{ $currency->id }}">{{$currency->code}}</option>
                                    @endforeach
                                </select>
                                <div id="editCurrencyError" class="customButton"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="from-group">
                                <label for="edit_amount" class="font-weight-bold text-dark">{{trans('common.amount_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text text-warning bg-white">
                                            <i class="fa fa-coins"></i>
                                        </div>
                                    </div>
                                    <input type="number" id="edit_amount" name="amount" class="form-control"/>
                                </div>
                                <div id="editAmountError" class="customButton"></div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button onclick="submitEditForm()" class="btn btn-teal font-weight-normal text-light">
                        <span class="mr-1"><i class="fa fa-save"></i></span>
                        {{trans('common.save_label')}}
                    </button>
                    <button type="button" class="btn btn-danger font-weight-normal text-light">
                        <span class="mr-1"><i class="fa fa-ban"></i></span>
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
                    <input type="hidden" name="expense_id" id="remove_expense_id">
                    <input type="hidden" name="budget_id" value="{{ $data['budget']['id'] }}">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="">
                                {{trans('common.confirm_remove_expense')}}<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_remove_expense"></div>
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
        const budgetId = parseInt({!! $data['budget']['id'] !!})

        const addModal = $('#addModal')
        const removeModal = $('#removeModal')
        const editModal = $('#editModal')

        const addForm = $('#addForm')
        const editForm = $('#editForm')
        const removeForm = $('#removeForm')
        const dataTable = $("#datatable").DataTable({
            processing: true,
            language: datatableTrans,
            autoWidth:false,
            serverSide: true,
            lengthMenu: [10, 25, 50, 75, 100 ],
            pageLength:10,
            ajax: {
                url:'{!! route('budgets.show',['budget' => $data['budget']['id']]) !!}',
                data: function(d){

                },
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'description', name: 'description' },
                { data: 'amount_info', name: 'amount' },
                { data: 'amount_in_base_currency_info', name: 'amount_in_base_currency' },
                { data: 'creator', name: 'creator' },
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ],
            initComplete:function (settings,json) {
                onReloadComplete(json)
            }
        });
        $(document).ready(function () {

            {{--addForm.validate({--}}
            {{--    rules: {--}}
            {{--        name: {--}}
            {{--            required: true,--}}
            {{--            minlength: 4,--}}
            {{--            maxlength: 25--}}
            {{--        },--}}
            {{--        description: {--}}
            {{--            required: true,--}}
            {{--            maxlength: 100,--}}
            {{--            minlength: 4--}}
            {{--        },--}}
            {{--        currency_id:{--}}
            {{--            required:true--}}
            {{--        },--}}
            {{--        amount: {--}}
            {{--            required: true,--}}
            {{--        },--}}
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
            {{--            maxlength: '{!! trans('custom_validation.max_length',['min' => 100]) !!}',--}}
            {{--        },--}}
            {{--        currency_id: {--}}
            {{--            required: '{!! trans('custom_validation.select_option') !!}'--}}
            {{--        },--}}
            {{--        amount: {--}}
            {{--            required: '{!! trans('custom_validation.required_field') !!}'--}}
            {{--        },--}}
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
            {{--            case 'amount':--}}
            {{--                $('#amountError').html(error)--}}
            {{--                break;--}}

            {{--        }--}}
            {{--    },--}}
            {{--    errorClass: 'is-invalid',--}}
            {{--    validClass: 'is-valid',--}}
            {{--})--}}
            {{--addForm.submit(function(event){--}}
            {{--    event.preventDefault();--}}
            {{--    const data = $(this).serialize()--}}
            {{--    $.ajax({--}}
            {{--        url:'{!! route('budgets.addItem',['budget' => $data['budget']['id']]) !!}',--}}
            {{--        method:'post',--}}
            {{--        data:data,--}}
            {{--        complete: function ({status, responseJSON}) {--}}
            {{--            if(status === 201){--}}
            {{--                const {message} = responseJSON--}}
            {{--                dataTable.ajax.reload(onReloadComplete)--}}
            {{--                addModal.modal('hide')--}}
            {{--                toastr.success(message, '{!! trans('common.success_label') !!}')--}}
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
            {{--            maxlength: 100,--}}
            {{--            minlength: 4--}}
            {{--        },--}}
            {{--        currency_id:{--}}
            {{--            required:true--}}
            {{--        },--}}
            {{--        amount: {--}}
            {{--            required: true,--}}
            {{--        },--}}
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
            {{--            maxlength: '{!! trans('custom_validation.max_length',['min' => 100]) !!}',--}}
            {{--        },--}}
            {{--        currency_id: {--}}
            {{--            required: '{!! trans('custom_validation.select_option') !!}'--}}
            {{--        },--}}
            {{--        amount: {--}}
            {{--            required: '{!! trans('custom_validation.required_field') !!}'--}}
            {{--        },--}}
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
            {{--            case 'amount':--}}
            {{--                $('#editAmountError').html(error)--}}
            {{--                break;--}}

            {{--        }--}}
            {{--    },--}}
            {{--    errorClass: 'is-invalid',--}}
            {{--    validClass: 'is-valid',--}}
            {{--})--}}
            {{--editForm.submit(function(event){--}}
            {{--    event.preventDefault();--}}
            {{--    const data = $(this).serialize()--}}
            {{--    $.ajax({--}}
            {{--        url:'{!! route('budgets.updateItem',['budget' => $data['budget']['id']]) !!}',--}}
            {{--        method:'patch',--}}
            {{--        data:data,--}}
            {{--        complete: function ({status, responseJSON}) {--}}
            {{--            if(status === 201){--}}
            {{--                const {message} = responseJSON--}}
            {{--                dataTable.ajax.reload(onReloadComplete)--}}
            {{--                editModal.modal('hide')--}}
            {{--                toastr.info(message, '{!! trans('common.success_label') !!}')--}}
            {{--            }--}}
            {{--        }--}}
            {{--    })--}}
            {{--})--}}

            removeForm.submit(function(event){
                event.preventDefault();
                const data = $(this).serialize()
                $.ajax({
                    url:'{!! route('budgets.removeItem',['budget' => $data['budget']['id']]) !!}',
                    method:'delete',
                    data:data,
                    complete: function ({status, responseJSON}) {
                        if(status === 201){
                            const {message} = responseJSON
                            dataTable.ajax.reload(onReloadComplete)
                            removeModal.modal('hide')
                            toastr.warning(message, '{!! trans('common.success_label') !!}')
                        }
                    }
                })
            })
            $(".modal").on("hidden.bs.modal", function() {
                console.log('clear')
                clearForm('addForm',false)
                clearForm('editForm',false)
            });
        })

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
                        maxlength: 100,
                        minlength: 4
                    },
                    currency_id:{
                        required:true
                    },
                    amount: {
                        required: true,
                    },
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
                        maxlength: '{!! trans('custom_validation.max_length',['min' => 100]) !!}',
                    },
                    currency_id: {
                        required: '{!! trans('custom_validation.select_option') !!}'
                    },
                    amount: {
                        required: '{!! trans('custom_validation.required_field') !!}'
                    },
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
                        case 'amount':
                            $('#amountError').html(error)
                            break;

                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(addForm.valid()){
                const data = addForm.serialize()
                $.ajax({
                    url:'{!! route('budgets.addItem',['budget' => $data['budget']['id']]) !!}',
                    method:'post',
                    data:data,
                    complete: function ({status, responseJSON}) {
                        if(status === 201){
                            const {message} = responseJSON
                            dataTable.ajax.reload(onReloadComplete)
                            addModal.modal('hide')
                            toastr.success(message, '{!! trans('common.success_label') !!}')
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
                        maxlength: 100,
                        minlength: 4
                    },
                    currency_id:{
                        required:true
                    },
                    amount: {
                        required: true,
                    },
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
                        maxlength: '{!! trans('custom_validation.max_length',['min' => 100]) !!}',
                    },
                    currency_id: {
                        required: '{!! trans('custom_validation.select_option') !!}'
                    },
                    amount: {
                        required: '{!! trans('custom_validation.required_field') !!}'
                    },
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
                        case 'amount':
                            $('#editAmountError').html(error)
                            break;

                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(editForm.valid()){
                const data = editForm.serialize()
                console.log(data)
                $.ajax({
                    url:'{!! route('budgets.updateItem',['budget' => $data['budget']['id']]) !!}',
                    method:'patch',
                    data:data,
                    complete: function ({status, responseJSON}) {
                        if(status === 201){
                            const {message} = responseJSON
                            dataTable.ajax.reload(onReloadComplete)
                            editModal.modal('hide')
                            toastr.info(message, '{!! trans('common.success_label') !!}')
                        }
                    }
                })
            }
        }

        function addItem() {
            addModal.modal('show')
        }

        function editItem(event) {
            const id = event.target.getAttribute('data-id')
            $.ajax({
                url:'{!! route('budgets.getItemById') !!}',
                method:'post',
                data: {
                    _token:'{!! csrf_token() !!}',
                    item_id:id
                },
                complete: function({status, responseJSON}){
                    if(status === 201){
                        const {item} = responseJSON
                        $('#edit_item_id').val(id)
                        $('#edit_name').val(item.name)
                        $('#edit_description').val(item.description)
                        $('#edit_currency_id').val(item.currency_id)
                        $('#edit_amount').val(currency(item.amount))
                        editModal.modal('show')
                    }
                }
            })
        }

        function deleteItem(event) {
            const id = event.target.getAttribute('data-id')
            const name = event.target.getAttribute('data-name')
            $('#remove_expense_id').val(id)
            $('#confirm_remove_expense').html(name)
            removeModal.modal('show')
        }
    </script>
@endsection
