@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="px-3 row d-flex flex-row justify-content-between mb-2">
                        <div class="card-title font-weight-bold  pt-1">
                            <span class="font-weight-bold text-lg text-dark">{{trans('common.budgets_label')}}</span>
                        </div>
                        <div class="d-flex flex-row">
{{--                            <form action="#" method="post">--}}
{{--                                <input type="hidden" id="export_from_date" name="from_date">--}}
{{--                                <input type="hidden" id="export_to_date" name="to_date">--}}
{{--                                <input type="hidden" id="export_to_amount" name="to_amount">--}}
{{--                                <input type="hidden" id="export_from_amount" name="from_amount">--}}
{{--                                <button id="exportBtn" type="submit" class="btn btn-info font-weight-normal">--}}
{{--                                    <i class="fa fa-file-excel mr-1"></i>--}}
{{--                                    {{trans('common.export_to_excel_label')}}--}}
{{--                                </button>--}}
{{--                            </form>--}}
                            <button onclick="addBudget(event)" class="btn btn-teal font-weight-normal ml-2">
                                {{trans('common.add_budget')}}
                                <i class="fa fa-plus ml-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-1 px-2">
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="date_filter" class="col-form-label font-weight-bold">{{trans('common.to_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white">
                                                <i class="fa fa-calendar text-teal"></i>
                                            </div>
                                        </div>
                                        <input type="text" autocomplete="off" class="form-control" id="date_filter" name="to_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-3 col">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="filter_min" class="col-form-label font-weight-bold">{{trans('common.amount_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white">
                                                <i class="fa fa-arrow-up text-teal"></i>
                                            </div>
                                        </div>
                                        <input type="number" min="0.00" step="0.01"  class="form-control" id="filter_min" name="above_amount" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-3 col">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="filter_max" class="col-form-label font-weight-bold">{{trans('common.amount_label')}}</label>
                                    <div class="input-group">
                                        <input type="number" min="0.00" step="0.01" class="form-control" id="filter_max" name="below_amount" />
                                        <div class="input-group-append">
                                            <div class="input-group-text bg-white">
                                                <i class="fa fa-arrow-down text-teal"></i>
                                            </div>
                                        </div>
                                    </div>
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
                    <table id="datatable" class="table table-bordered display compact nowrap">
                        <thead>
                        <tr>
                            <th style="width: 80px">Id</th>
                            <th>
                                <span class="mr-1"><i class="fa fa-cog text-teal"></i></span>
                                {{trans('common.name_label')}}
                            </th>
                            <th>
                                {{trans('common.description_label')}}
                            </th>
                            <th>
                                <span class="mr-1"><i class="fa fa-coins text-warning"></i></span>
                                {{trans('common.total_amount_label')}}
                            </th>
                            <th>
                                <span class="mr-1"><i class="fa fa-calendar text-teal"></i></span>
                                {{trans('common.created_date')}}
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
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.add_budget')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="font-weight-bold text-light">&times;</span>
                    </button>
                </div>

                    <div class="py-3 px-4">
                        <form method="post" action="#" id="addForm">
                            @csrf
                        <div class="form-row">
                            <div class="col">
                                <div class="from-group">
                                    <label for="name" class="font-weight-bold text-dark">{{trans('common.name_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                    <input placeholder="{{ trans('common.budget_placeholder_name') }}" type="text" id="name" name="name" class="form-control" />
                                    <div id="nameError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col">
                                <div class="from-group">
                                    <label for="add_date" class="font-weight-bold text-dark">{{trans('common.date_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white">
                                                <i class="fa fa-calendar text-teal"></i>
                                            </div>
                                        </div>
                                        <input type="text" id="add_date" name="date" class="form-control" />
                                    </div>
                                    <div id="dateError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col">
                                <div class="from-group">
                                    <label for="add_description" class="font-weight-bold text-dark">{{trans('common.description_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                    <textarea placeholder="{{trans('common.budget_placeholder_description')}}" class="form-control" id="add_description" name="description" rows="4" ></textarea>
                                    <div id="descriptionError" class="customError"></div>
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
                        <button type="button" data-dismiss="modal" class="btn btn-danger font-weight-normal text-light">
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
                    <h5 class="modal-title" id="editModalLabel">{{trans('common.edit_budget')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="font-weight-bold text-light">&times;</span>
                    </button>
                </div>
                    <div class="py-3 px-4">
                        <form method="post" action="#" id="editForm">
                            @csrf
                            <input type="hidden" name="budget_id" id="edit_budget_id">
                        <div class="form-row">
                            <div class="col">
                                <div class="from-group">
                                    <label for="edit_name" class="font-weight-bold text-dark">{{trans('common.name_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                    <input type="text" id="edit_name" name="name" class="form-control" />
                                    <div id="editNameError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col">
                                <div class="from-group">
                                    <label for="edit_date" class="font-weight-bold text-dark">{{trans('common.date_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white">
                                                <i class="fa fa-calendar text-teal"></i>
                                            </div>
                                        </div>
                                        <input type="text" id="edit_date" name="date" class="form-control" />
                                    </div>
                                    <div id="editDateError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col">
                                <div class="from-group">
                                    <label for="edit_description" class="font-weight-bold text-dark">{{trans('common.description_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                    <textarea class="form-control" id="edit_description" name="description" rows="4" placeholder=""></textarea>
                                    <div id="editDescriptionError" class="customError"></div>
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
                        <button type="button" data-dismiss="modal" class="btn btn-danger font-weight-normal text-light">
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
                    <input type="hidden" name="id" id="remove_budget_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="">
                                {{trans('common.confirm_remove_budget')}}<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_remove_budget"></div>
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

@section('custom_css')
    @include('shared.totalCSS')
@endsection

@section('custom_js')
    @include('shared.totalJS')

    <script>
        let fromDate = null
        let toDate = null
        let maxAmount = 0
        let minAmount = 0

        const addModal = $('#addModal')
        const removeModal = $('#removeModal')
        const editModal = $('#editModal')

        const addForm = $('#addForm')
        const removeForm = $('#removeForm')
        const editForm = $('#editForm')

        const filterBtn = $('#filterBtn')
        const clearBtn = $('#clearBtn')

        const dateFilterEl = $('#date_filter')
        const maxFilterEl = $('#filter_max')
        const minFilterEl = $('#filter_min')

        const exportToDate = $('#export_to_date')
        const exportFromDate = $('#export_from_date')
        const exportMinAmount = $('#export_from_amount')
        const exportMaxAmount = $('#export_to_amount')
        const dataTable = $("#datatable").DataTable({
            processing: true,
            language: datatableTrans,
            autoWidth:false,
            serverSide: true,
            lengthMenu: [10, 25, 50, 75, 100 ],
            pageLength:10,
            ajax: {
                url:'{!! route('budgets.index') !!}',
                data: function(d){
                    d.to_date = toDate
                    d.from_date = fromDate
                    d.minAmount = minAmount
                    d.maxAmount = maxAmount
                },
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'description', name: 'description' },
                { data: 'amount_info', name: 'total_amount' },
                { data: 'date', name: 'date' },
                { data: 'creator', name: 'creator' },
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ],
            initComplete:function (settings,json) {
                onReloadComplete(json)
            }
        });

        $(document).ready(function () {
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
            dateFilterEl.on('apply.daterangepicker',function(ev,picker){
                const start = picker.startDate.format('YYYY-MM-DD')
                const end = picker.endDate.format('YYYY-MM-DD')
                start === end ? $(this).val(start) : $(this).val(`${start} - ${end}`)
                exportFromDate.val(start)
                fromDate = start;
                exportToDate.val(end)
                toDate = end;
            })

            maxFilterEl.on('change', function (event) {
                let value = $(this).val()
                maxAmount = value
                exportMaxAmount.val(value)
            })
            minFilterEl.on('change', function (event) {
                let value = $(this).val()
                minAmount = value
                exportMinAmount.val(value)
            })

            filterBtn.on('click',function($event){
                dataTable.ajax.reload(onReloadComplete)
            })
            clearBtn.on('click',function($event){
                dateFilterEl.val('')
                fromDate = null
                toDate = null
                maxAmount = null
                minAmount = null
                maxFilterEl.val(null)
                minFilterEl.val(null)
                exportMinAmount.val(null)
                exportMaxAmount.val(null)
                dataTable.ajax.reload(onReloadComplete)
            })

            removeForm.submit(function(event){
                event.preventDefault();
                const data = $(this).serialize()
                $.ajax({
                    url:'{!! route('budgets.delete') !!}',
                    method:'delete',
                    data: data,
                    complete: function({status, responseJSON}){
                        if(status === 200){
                            const {message} = responseJSON
                            removeModal.modal('hide')
                            dataTable.ajax.reload(onReloadComplete)
                            toastr.warning(message,'{!! trans('common.success_label') !!}')
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
                        minlength: 4,
                        maxlength: 40
                    },
                    description: {
                        required: true,
                        maxlength: 100,
                        minlength: 4
                    },
                    date: {
                        required: true,
                        date: true,
                    },
                },
                messages: {
                    name: {
                        required: '{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 4]) !!}',
                        maxlength: '{!! trans('custom_validation.max_length',['max' => 40]) !!}',
                    },
                    description: {
                        required: '{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 4]) !!}',
                        maxlength: '{!! trans('custom_validation.max_length',['min' => 100]) !!}',
                    },
                    date: {
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
                        case 'date':
                            $('#dateError').html(error)
                            break;

                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(addForm.valid()){
                const data = addForm.serialize()
                $.ajax({
                    url:'{!! route('budgets.store') !!}',
                    method:'post',
                    data: data,
                    complete: function({status, responseJSON}){
                        if(status === 200){
                            const {message} = responseJSON
                            addModal.modal('hide')
                            dataTable.ajax.reload(onReloadComplete)
                            toastr.success(message,'{!! trans('common.success_label') !!}')
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
                    date: {
                        required: true,
                        date: true,
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
                        maxlength: '{!! trans('custom_validation.max_length',['max' => 100]) !!}',
                    },
                    date: {
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
                        case 'date':
                            $('#editDateError').html(error)
                            break;

                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(editForm.valid()){
                const data = editForm.serialize()
                $.ajax({
                    url:'{!! route('budgets.update') !!}',
                    method:'patch',
                    data: data,
                    complete: function({status, responseJSON}){
                        if(status === 200){
                            const {message} = responseJSON
                            editModal.modal('hide')
                            dataTable.ajax.reload(onReloadComplete)
                            toastr.success(message,'{!! trans('common.success_label') !!}')
                        }
                    }
                })
            }
        }

        function addBudget(event){
            addModal.modal('show')
            $('#add_date').daterangepicker({
                singleDatePicker:true,
                autoUpdateInput: true,
                showDropdowns: true,
                minYear: 1901,
                locale:datePickerTran,
                applyButtonClasses:'btn btn-teal btn-sm',
                cancelButtonClasses:'btn btn-danger btn-sm'
            })
        }

        function deleteBudget(event){
            const id = event.target.getAttribute('data-id')
            const name = event.target.getAttribute('data-name')
            $('#confirm_remove_budget').html(name)
            $('#remove_budget_id').val(id)
            removeModal.modal('show');
        }

        function editBudget(event){
            const id = event.target.getAttribute('data-id')
            $.ajax({
                url:'{!! route('budgets.getById') !!}',
                method:'post',
                data: {
                    _token: '{!! csrf_token() !!}',
                    id:id
                },
                complete: function ({status, responseJSON}) {
                    if(status === 200){
                        const {budget} = responseJSON
                        $('#edit_name').val(budget.name)
                        $('#edit_budget_id').val(id)
                        $('#edit_description').val(budget.description)
                        $('#edit_date').daterangepicker({
                            singleDatePicker:true,
                            autoUpdateInput: true,
                            startDate: budget.date,
                            showDropdowns: true,
                            minYear: 1901,
                            locale:datePickerTran,
                            applyButtonClasses:'btn btn-teal btn-sm',
                            cancelButtonClasses:'btn btn-danger btn-sm'
                        })
                        editModal.modal('show')
                    }
                }
            })
        }

    </script>
@endsection
