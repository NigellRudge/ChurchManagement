@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-white d-flex flex-row justify-content-between">
                    <h5 class="font-weight-bold text-dark">{{trans('common.sub_accounts')}}</h5>
                    <div class="d-flex flex-row">
                        <form action="{{route('sub-accounts.exportFinanceOverview')}}" method="post">
                            @csrf
                            <input type="hidden" id="export_from_date" name="from_date">
                            <input type="hidden" id="export_to_date" name="to_date">
                            <input type="hidden" id="export_account_type" name="account_type">
                            <input type="hidden" id="export_currency_id" name="currency_id">
                            <input type="hidden" id="export_status" name="status">
                            <button class="mr-2 btn btn-primary font-weight-bold text-light rounded font-weight-bol" disabled id="exportBtn" type="submit">
                                {{trans('common.export_to_excel_label')}}
                                <i class="ml-1 fas fa-file-excel"></i>
                            </button>
                        </form>
                        <button class="btn btn-teal" onclick="addAccount(event)">
                            {{trans('common.add_account_label')}}
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row pl-2">
                        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="filter_currency_id" class="col-form-label font-weight-bold">{{trans('common.filter_by_currency')}}</label>
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
                    </div>
                    <div class="row mb-5 pl-2">
                        <div class="col">
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
                            <table id="datatable" class="table table-bordered display nowrap">
                                <thead>
                                <tr>
                                    <th>Id</th>
{{--                                    <th>--}}
{{--                                        <i class="fa fa-piggy-bank text-teal"></i>--}}
{{--                                        {{trans('common.main_account')}}--}}
{{--                                    </th>--}}
                                    <th>{{trans('common.name_label')}}</th>
                                    <th>
                                        {{trans('common.account_type')}}
                                    </th>
                                    <th style="width: 100pxshow">
                                        <i class="fa fa-dollar-sign mr-1 text-teal"></i>
                                        {{trans('common.currency_label')}}
                                    </th>
                                    <th>
                                        <i class="fa fa-coins mr-1 text-warning"></i>
                                        {{trans('common.balance')}}
                                    </th>
                                    <th style="width: 100px">{{trans('common.status_label')}}</th>
                                    <th></th>
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

    <div class="modal fade" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-light">
                    <h5 class="modal-title" id="exampleModalLabel">{{trans('common.confirm_label')}}</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="removeForm">
                    @csrf
                    <input type="hidden" name="account_id" id="remove_account_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-danger mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-2">
                                {{trans('common.confirm_delete_sub_account')}}<br>
                                <div class="d-inline text-dark font-weight-bold" id="confirm_account"></div> ?
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

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.add_account_label')}}</h5>
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
                                    <label for="add_parent_account_id" class="text-dark font-weight-bold">{{trans('common.parent_account')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fa fa-piggy-bank"></i>
                                            </div>
                                        </div>
                                        <select id="add_parent_account_id" name="parent_account_id" class="form-control">
                                        </select>
                                    </div>
                                    <div id="parentError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="add_can_delete">{{trans('common.can_delete')}}</label>
                                    <select type="text" class="form-control" id="add_can_delete" name="can_delete">
                                        <option value="" disabled>{{trans('common.select_option')}}</option>
                                        <option value="0">{{trans('common.no_label')}}</option>
                                        <option value="1">{{trans('common.yes_label')}}</option>
                                    </select>
                                    <div id="deleteError" class="customError"></div>
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
                    <h5 class="modal-title" id="editModalLabel">{{trans('common.edit_sub_account')}}</h5>
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
                                    <label for="edit_parent_account_id" class="text-dark font-weight-bold">{{trans('common.parent_account')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fa fa-piggy-bank"></i>
                                            </div>
                                        </div>
                                        <select id="edit_parent_account_id" name="parent_account_id" class="form-control">
                                        </select>
                                    </div>
                                    <div id="editParentError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_can_delete">{{trans('common.can_delete')}}</label>
                                    <select type="text" class="form-control" id="edit_can_delete" name="can_delete">
                                        <option value="" disabled>{{trans('common.select_option')}}</option>
                                        <option value="0">{{trans('common.no_label')}}</option>
                                        <option value="1">{{trans('common.yes_label')}}</option>
                                    </select>
                                    <div id="editDeleteError" class="customError"></div>
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

@endsection

@section('custom_js')
    <script>

        let currencyId = 0
        let accountType = 0
        let status = 1
        let fromDate = null;
        let toDate = null;

        const addModal = $('#addModal')
        const addForm = $('#addForm')

        const editModal = $('#editModal')
        const editForm = $('#editForm')

        const removeModal = $('#removeModal')
        const removeForm = $('#removeForm')

        const deactivateForm  = $('#deactivateForm')
        const deactivateModal  = $('#deactivateModal')

        const reactivateForm = $('#reactivateForm')
        const reactivateModal = $('#reactivateModal')

        const currencyFilterEl = $('#filter_currency_id')
        const accountFilterEl = $('#filter_account')
        const statusFilterEl = $('#filter_status')
        const dateFilterEl = $('#date_filter')

        const exportFromDate = $('#export_from_date')
        const exportToDate = $('#export_to_date')


        const filterBtn = $('#filterBtn')
        const clearBtn = $('#clearBtn')

        const dataTable = $('#datatable').DataTable({
            processing: true,
            language: datatableTrans,
            autoWidth: false,
            serverSide: true,
            lengthMenu: [10, 25, 50, 75, 100],
            pageLength: 10,
            ajax: {
                url: '{!! route('sub-accounts.index') !!}',
                data: function (d) {
                    d.currency_id = currencyId
                    d.account_type = accountType
                    d.status = status
                    d.to_date = toDate
                    d.from_date = fromDate
                },
            },
            columns: [
                {data: 'id', name: 'id'},
                // {data: 'parent_account', name: 'parent_account'},
                {data: 'name', name: 'name'},
                {data: 'account_type_info', name: 'account_type_info'},
                // {data: 'debit_info', name: 'debit_info'},
                // {data: 'credit_info', name: 'credit_info'},
                {data: 'currency', name: 'currency'},
                {data: 'balance_info', name: 'balance_info'},
                {data: 'status_info', name: 'status_info'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false}
            ],
            initComplete: function (settings, json) {
                onReloadComplete(json)
            }
        })


        $(document).ready(function(){
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
            filterBtn.on('click',function(){
                dataTable.ajax.reload(onReloadComplete)
            })

            statusFilterEl.on('change', function(){
                status = this.value
            })

            clearBtn.on('click',function($event){
                currencyId = 0
                accountType = 0
                status = 1
                dateFilterEl.val('')
                fromDate = null
                exportToDate.val(null)
                exportFromDate.val(null)
                toDate = null
                currencyFilterEl.val(0)
                accountFilterEl.val(0)
                statusFilterEl.val(1)
                dataTable.ajax.reload(onReloadComplete)
            })

            {{--addForm.submit(function(event){--}}
            {{--   event.preventDefault()--}}
            {{--   const data = $(this).serialize()--}}
            {{--   console.log(data)--}}
            {{--   $.ajax({--}}
            {{--       url:'{!! route('sub-accounts.store') !!}',--}}
            {{--       method:'post',--}}
            {{--       data:data,--}}
            {{--       complete: function({status, responseJSON}){--}}
            {{--           if(status === 200){--}}
            {{--               const {message} = responseJSON--}}
            {{--               toastr.success(message, '{!! trans('common.success_label') !!}')--}}
            {{--               dataTable.ajax.reload(onReloadComplete)--}}
            {{--               addModal.modal('hide')--}}
            {{--           }--}}
            {{--       }--}}
            {{--   })--}}
            {{--});--}}

            editForm.submit(function(event){
                event.preventDefault()
                $('#edit_parent_account_id').prop('disabled',false)
                const data = $(this).serialize()
                console.log(data)
                $.ajax({
                    url:'{!! route('sub-accounts.update') !!}',
                    method:'patch',
                    data:data,
                    complete: function({status, responseJSON}){
                        if(status === 200){
                            const {message} = responseJSON
                            toastr.info(message,'{!! trans('common.success_label') !!}')
                            dataTable.ajax.reload(onReloadComplete)
                            editModal.modal('hide')
                        }
                    }
                })
            })

            removeForm.submit(function(event){
                event.preventDefault()
                const data = $(this).serialize()
                console.log(data)
                $.ajax({
                    url:'{{ route('sub-accounts.destroy') }}',
                    method:'delete',
                    data:data,
                    complete:function({status, responseJSON}){
                        if(status === 200){
                            const {message} = responseJSON
                            dataTable.ajax.reload(onReloadComplete)
                            removeModal.modal('hide')
                            toastr.warning(message,'{!! trans('common.success_label') !!}')
                        }
                    }

                })
            })

            deactivateForm.submit(function(event){
                event.preventDefault()
                const data = $(this).serialize()
                $.ajax({
                    url:'{!! route('sub-accounts.deactivate') !!}',
                    method:'post',
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
                console.log(data)
                $.ajax({
                    url:'{!! route('sub-accounts.reactivate') !!}',
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

            $(".modal").on("hidden.bs.modal", function() {
                clearForm('addForm',false)
                clearForm('editForm',false)
            });
        })

        function submitAddForm(){
            addForm.validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3,
                        maxlength: 25
                    },
                    description: {
                        required: true,
                        maxlength: 100,
                        minlength: 3
                    },
                    parent_account_id: {
                        required: true,
                    },
                    can_delete:{
                        required:true
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
                        maxlength: '{!! trans('custom_validation.max_length',['min' => 100]) !!}',
                    },
                    parent_account_id: {
                        required: '{!! trans('custom_validation.select_option') !!}'
                    },
                    can_delete: {
                        required: '{!! trans('custom_validation.select_option') !!}'
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
                        case 'parent_account_id':
                            $('#parentError').html(error)
                            break;
                        case 'can_delete':
                            $('#deleteError').html(error)
                            break;

                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(addForm.valid()){
                const data = addForm.serialize()
                console.log(data)
                $.ajax({
                    url:'{!! route('sub-accounts.store') !!}',
                    method:'post',
                    data:data,
                    complete: function({status, responseJSON}){
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
                        minlength: 3,
                        maxlength: 25
                    },
                    description: {
                        required: true,
                        maxlength: 100,
                        minlength: 3
                    },
                    parent_account_id: {
                        required: true,
                    },
                    can_delete:{
                        required:true
                    }
                },
                messages: {
                    name: {
                        required: '{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 4]) !!}',
                        maxlength: '{!! trans('custom_validation.max_length',['max' => 25]) !!}',
                    },
                    description: {
                        required: '{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 4]) !!}',
                        maxlength: '{!! trans('custom_validation.max_length',['max' => 100]) !!}',
                    },
                    parent_account_id: {
                        required: '{!! trans('custom_validation.select_option') !!}'
                    },
                    can_delete: {
                        required: '{!! trans('custom_validation.select_option') !!}'
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
                        case 'parent_account_id':
                            $('#editParentError').html(error)
                            break;
                        case 'can_delete':
                            $('#editDeleteError').html(error)
                            break;

                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(editForm.valid()){
                $('#edit_parent_account_id').prop('disabled',false)
                const data = editForm.serialize()
                console.log(data)
                $.ajax({
                    url:'{!! route('sub-accounts.update') !!}',
                    method:'patch',
                    data:data,
                    complete: function({status, responseJSON}){
                        if(status === 200){
                            const {message} = responseJSON
                            toastr.info(message,'{!! trans('common.success_label') !!}')
                            dataTable.ajax.reload(onReloadComplete)
                            editModal.modal('hide')
                        }
                    }
                })
            }
        }

        function addAccount(event){
            const parentAccount = $('#add_parent_account_id')
            parentAccount.select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{{ route('accounts.list') }}',
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
                        console.log(data)
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
            addModal.modal('show')
        }

        function editAccount(event){
            const id = event.target.getAttribute('data-id')
            $.ajax({
                url:'{!! route('sub-accounts.getById') !!}',
                method:'post',
                data: {
                    _token: '{!! csrf_token() !!}',
                    account_id: id
                },
                complete: function({status, responseJSON}){
                    if(status === 200){
                        const {account} = responseJSON
                        console.log(account)
                        $('#edit_name').val(account.name)
                        $('#edit_description').val(account.description)
                        $('#edit_account_id').val(account.id)
                        setupEditParentAccountId(account.parent_account_id)
                        if(account.can_edit === 1){
                            let element =$('#edit_parent_account_id')
                            element.val(account.parent_account_id)
                            element.prop('disabled',false)
                        }
                        else {
                            let element =$('#edit_parent_account_id')
                            element.prop('disabled',true)
                        }
                       if(account.can_delete){
                           $('#edit_can_delete').val(1)
                       }
                       else {
                           $('#edit_can_delete').val(0)
                       }
                        editModal.modal('show')
                    }
                }
            })
        }

        function deleteAccount(event){
            const id = event.target.getAttribute('data-id')
            const name =  event.target.getAttribute('data-name')
            $('#remove_account_id').val(id)
            $('#confirm_account').html(name)
            removeModal.modal('show')
        }

        function deactivateAccount(event){
            const id = event.target.getAttribute('data-id')
            const name =  event.target.getAttribute('data-name')
            $('#deactivate_account_id').val(id)
            $('#confirm_deactivate_account').html(name)
            deactivateModal.modal('show')
        }

        function reactivateAccount(event){
            const id = event.target.getAttribute('data-id')
            const name =  event.target.getAttribute('data-name')
            $('#reactivate_account_id').val(id)
            $('#confirm_reactivate_account').html(name)
            reactivateModal.modal('show')
        }

        function setupEditParentAccountId(parentId){
            let accountSelect = $('#edit_parent_account_id').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('accounts.list') !!}',
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
                    placeholder: 'Currency',
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
                url: '{!! route('accounts.getById') !!}',
                data: {
                    _token: '{!! csrf_token() !!}',
                    account_id:parentId
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
    </script>
@endsection
