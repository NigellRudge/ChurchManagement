@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="card-header bg-white d-flex mb-2 justify-content-between">
                    <div class="font-weight-bold  pt-1">
                        <span class="font-weight-bold text-lg text-dark">{{trans('common.collections_label')}}</span>
                    </div>
                    <div class="d-flex flex-row">
                        <div class="mr-1">
                            <form action="{{route('offerings.export')}}" method="post">
                                @csrf
                                <input type="hidden" name="from_date" id="export_from_date">
                                <input type="hidden" name="to_date" id="export_to_date">
                                <button id="exportBtn" disabled type="submit" class="btn btn-info">
                                    <i class="fa fa-file-excel"></i>
                                    {{trans('common.export_to_excel_label')}}
                                </button>
                            </form>
                        </div>
                        <a id="addBtn" class="btn btn-teal font-weight-bold" href="#" >
                            {{trans('common.add_collection_label')}}
                            <i class="ml-1 fas fa-plus"></i>
                        </a>
                    </div>

                </div>
                <div class="card-body">
                    <div class="row pl-3 mb-4">
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
                        <div class="col pt-4 flex-column d-flex justify-content-center">
                            <div class="row d-flex flex-row">
                                <button class="btn mr-1 btn-teal text-light font-weight-bold" id="filterBtn">
                                    {{trans('common.filter_label')}}
                                    <i class="fas fa-filter ml-1"></i>
                                </button>
                                <button class="btn btn-danger text-light font-weight-bold" id="clearBtn">
                                    {{trans('common.filter_label')}}
                                    <i class="fas fa-ban ml-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <table id="datatable" class="table table-striped table-bordered display compact nowrap">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>{{trans('common.name_label')}}</th>
                                <th>{{trans('common.srd_amount')}}</th>
                                <th>{{trans('common.usd_amount')}}</th>
                                <th>{{trans('common.eur_amount')}}</th>
                                <th>
                                    <span  class="mr-1"><i class="fa fa-coins text-warning"></i></span>
                                    {{trans('common.total_amount_label')}}
                                </th>
                                <th>
                                    <span  class="mr-1"><i class="fa fa-calendar text-teal"></i></span>
                                    {{trans('common.date_label')}}
                                </th>
                                <th>

                                    <span  class="mr-1"><i class="fa fa-user text-teal"></i></span>
                                    {{trans('common.counted_by_label')}}
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

    <div class="modal" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <input type="hidden" name="remove_offering_id" id="remove_offering_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-2">
                                {{trans('common.confirm_delete_collection_label')}}:<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_offering"></div> ?
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">
                            <span class="mr-1"><i class="fa fa-trash"></i></span>
                            {{trans('common.yes_label')}}
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('common.no_label')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title font-weight-bold" id="addModalLabel">{{trans('common.add_collection_label')}}</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class=" mt-2 pl-3 pr-3">
                    <form method="post" action="#" id="addForm">
                        @csrf
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="date" class="text-dark font-weight-bold">{{trans('common.date_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                        <input type="text" id="date" name="date"  class="form-control">
                                    </div>
                                    <div id="Error" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold text-dark">{{trans('common.name_label')}}</label>
                                    <input type="text" id="name" name="name" class="form-control">
                                    <div id="nameError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="srd_amount" class="font-weight-bold text-dark">SRD {{trans('common.amount_label')}}</label>
                                    <input type="number" id="srd_amount" name="srd_amount" placeholder="0.00" min="0.00" class="form-control">
                                    <div id="srdError" class="customError"></div>
                                </div>
                            </div>
                            <div class="col-3">
                                <label for="srd_rate">{{trans('common.currency_rate_label')}}</label>
                                <input type="text" id="srd_rate" class="form-control" value="{{$data['currency_srd']['exchange_rate']}}" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="usd_amount" class="font-weight-bold text-dark">USD {{trans('common.amount_label')}} </label>
                                    <input type="number" id="usd_amount" name="usd_amount" placeholder="0.00" min="0.00"  class="form-control">
                                    <div id="usdError" class="customError"></div>
                                </div>
                            </div><div class="col-3">
                                <label for="usd_rate">{{trans('common.currency_rate_label')}}</label>
                                <input type="text" id="usd_rate" class="form-control" value="{{$data['currency_usd']['exchange_rate']}}" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="euro_amount" class="font-weight-bold text-dark">EURO {{trans('common.amount_label')}}</label>
                                    <input type="number" id="euro_amount" name="euro_amount" placeholder="0.00" min="0.00"  class="form-control">
                                    <div id="euroError" class="customError"></div>
                                </div>
                            </div>
                            <div class="col-3">
                                <label for="eur_rate">{{trans('common.currency_rate_label')}}</label>
                                <input type="text" id="eur_rate" class="form-control" value="{{$data['currency_euro']['exchange_rate']}}" disabled>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="total_amount" class="font-weight-bold text-dark">{{trans('common.total_amount_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text  bg-white text-teal">
                                                <i class="fa fa-coins text-warning"></i>
                                            </div>
                                        </div>
                                        <input type="number" id="total_amount" name="total_amount" class="form-control" placeholder="0.00"  readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                    <div class="modal-footer">
                        <button onclick="submitAddForm()" class="btn btn-teal submitBtn" id="submitBtn">
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

    <div class="modal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="editModalLabel">{{trans('common.edit_collection_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class=" mt-2 pl-3 pr-3">
                    <form method="post" action="#" id="editForm">
                        @csrf
                        <input type="hidden" name="edit_offering_id" id="edit_offering_id">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_date" class="font-weight-bold text-dark">{{trans('common.date_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                        <input type="text" id="edit_date" name="date"  class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_name" class="font-weight-bold text-dark">{{trans('common.name_label')}}<span class="text-danger">*</span></label>
                                    <input type="text" id="edit_name" name="name" class="form-control">
                                    <div id="editNameError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_srd_amount" class="font-weight-bold text-dark">SRD {{trans('common.amount_label')}}<span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" id="edit_srd_amount" name="srd_amount" class="form-control">
                                    <div id="editSrdError" class="customError"></div>
                                </div>
                            </div>
                            <div class="col-3">
                                <label for="srd_rate">{{trans('common.currency_rate_label')}}</label>
                                <input type="text" id="srd_rate" class="form-control" value="{{$data['currency_srd']['exchange_rate']}}" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_usd_amount" class="font-weight-bold text-dark">USD {{trans('common.amount_label')}}</label>
                                    <input type="number"  step="0.01" id="edit_usd_amount" name="usd_amount" class="form-control">
                                    <div id="editUsdError" class="customError"></div>
                                </div>
                            </div>
                            <div class="col-3">
                                <label for="usd_rate">{{trans('common.currency_rate_label')}}</label>
                                <input type="text" id="usd_rate" class="form-control" value="{{$data['currency_usd']['exchange_rate']}}" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_euro_amount" class="font-weight-bold text-dark">EURO  {{trans('common.amount_label')}}</label>
                                    <input type="number" step="0.01" id="edit_euro_amount" name="euro_amount" class="form-control">
                                    <div id="editEuroError" class="customError"></div>
                                </div>
                            </div>
                            <div class="col-3">
                                <label for="eur_rate">{{trans('common.currency_rate_label')}}</label>
                                <input type="text" id="eur_rate" class="form-control" value="{{$data['currency_euro']['exchange_rate']}}" disabled>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_total_amount" class="font-weight-bold text-dark"> {{trans('common.total_amount_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend ">
                                            <div class="input-group-text bg-white text-teal">
                                                <span class="font-weight-bold text-sm-center">SRD</span>
                                            </div>
                                        </div>
                                        <input type="number" id="edit_total_amount" name="edit_total_amount" class="form-control" placeholder="0.00" readonly>
                                    </div>
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
@endsection

@section('custom_css')
    @include('shared.totalCSS')
@endsection

@section('custom_js')
    @include('shared.totalJS')
    <script type="text/javascript" src="{{ asset('vendor/currency/currency.js') }}"></script>
    <script>
        let fromDate = null;
        let toDate = null;

        let srdTotal = 0.00
        let usdTotal = 0.00
        let euroTotal = 0.00

        const srdRate = parseFloat('{!! $data['currency_srd']['exchange_rate'] !!}').toFixed(2)
        const euroRate = parseFloat('{!! $data['currency_euro']['exchange_rate'] !!}').toFixed(2)
        const usdRate = parseFloat('{!! $data['currency_usd']['exchange_rate'] !!}').toFixed(2)

        const srdAmount = $('#srd_amount')
        const usdAmount = $('#usd_amount')
        const euroAmount = $('#euro_amount')
        const totalAmount = $('#total_amount')

        const editSrdAmount = $('#edit_srd_amount')
        const editUsdAmount = $('#edit_usd_amount')
        const editEuroAmount = $('#edit_euro_amount')
        const editTotalAmount = $('#edit_total_amount')

        const filterBtn = $('#filterBtn')
        const clearBtn = $('#clearBtn')
        const dateFilterEl = $('#date_filter')

        const exportFromDate = $('#export_from_date')
        const exportToDate = $('#export_to_date')

        const addForm = $('#addForm')
        const deleteForm = $('#removeForm')
        const editForm = $('#editForm')

        const addModal = $('#addModal')
        const editModal = $('#editModal')

        const dataTable = $("#datatable").DataTable({
            processing: true,
            language: datatableTrans,
            autoWidth:false,
            serverSide: true,
            lengthMenu: [10, 25, 50, 75, 100 ],
            pageLength:10,
            ajax: {
                url:'{!! route('offerings.index') !!}',
                data: function(d){
                    d.to_date = toDate
                    d.from_date = fromDate
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'srd_amount_info', name: 'srd_amount' },
                { data: 'usd_amount_info', name: 'usd_amount' },
                { data: 'eur_amount_info', name: 'eur_amount_' },
                { data: 'amount', name: 'amount' },
                {data: 'date',name: 'date'},
                {data: 'counted_by',name: 'counted_by'},
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ],
            initComplete:function (settings,json) {
                onReloadComplete(json)
            }
        });
        $(document).ready(function(){

            filterBtn.on('click',function(){
                dataTable.ajax.reload()
            })
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
            clearBtn.on('click',function($event){
                dateFilterEl.val('')
                fromDate = null
                toDate = null
                exportFromDate.val(null)
                exportToDate.val(null)
                dataTable.ajax.reload()
            })

            /* Add modal total amount calculation */
            srdAmount.on('change',{amount:srdAmount.val(),name:'SRD',exchangeRate:srdRate,totalElementId:'total_amount'},updateTotals)
            usdAmount.on('change',{amount:usdAmount.val(),name:'USD',exchangeRate:usdRate,totalElementId:'total_amount'},updateTotals)
            euroAmount.on('change',{amount:euroAmount.val(),name:'EUR',exchangeRate:euroRate,totalElementId:'total_amount'},updateTotals)

            editSrdAmount.on('change',{amount:editSrdAmount.val(),name:'SRD',exchangeRate:srdRate,totalElementId:'edit_total_amount'},updateTotals)
            editUsdAmount.on('change',{amount:editUsdAmount.val(),name:'USD',exchangeRate:usdRate,totalElementId:'edit_total_amount'},updateTotals)
            editEuroAmount.on('change',{amount:editEuroAmount.val(),name:'EUR',exchangeRate:euroRate,totalElementId:'edit_total_amount'},updateTotals)
            /***************************************************/


            deleteForm.submit(function($event) {
                $event.preventDefault()
                let data = deleteForm.serialize()
                console.log(data);
                $.ajax({
                    url: '{!! route('offerings.destroyAjax') !!}',
                    method: 'delete',
                    data: data,
                    complete: function ({status, responseJSON}) {
                        if (status === 201) {
                            let {message} = responseJSON
                            $('#removeModal').modal('hide')
                            dataTable.ajax.reload(onReloadComplete)
                            toastr.warning(message,"{{trans('common.success_label')}}")
                        }
                    }
                })
            })


            $(".modal").on("hidden.bs.modal", function() {
                clearForm('addForm',false)
                clearForm('editForm',false)
                srdTotal = 0.00
                usdTotal = 0.00
                euroTotal = 0.00
            });

            $('.submitBtn').prop('disabled',false)
            $('#addBtn').on('click',function ($event) {
                $event.preventDefault()
                $('#date').daterangepicker({
                    singleDatePicker:true,
                    autoUpdateInput: true,
                    showDropdowns: true,
                    minYear: 1901,
                    locale:datePickerTran,
                    applyButtonClasses:'btn btn-teal btn-sm',
                    cancelButtonClasses:'btn btn-danger btn-sm'
                })
                $('#addModal').modal('show')
            })
        });

        function submitAddForm(){
            addForm.validate({
                rules:{
                    date: {
                        required:true,
                        date:true
                    },
                    srd_amount: {
                        required:true,
                        min: 1
                    },
                    usd_amount: {
                        required:true,
                        min: 0
                    },
                    euro_amount: {
                        required:true,
                        min: 0
                    },
                    name :{
                        required:true,
                        minlength: 3,
                        maxlength: 60
                    }
                },
                messages:{
                    date: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        date:'{!! trans('custom_validation.valid_date') !!}'
                    },
                    srd_amount: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        min: '{!! trans('custom_validation.min_amount',['min' => 1]) !!}'
                    },
                    usd_amount: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        min: '{!! trans('custom_validation.min_amount',['min' => 0]) !!}'
                    },
                    euro_amount: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        min: '{!! trans('custom_validation.min_amount',['min' => 0]) !!}'
                    },
                    name: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 3]) !!}',
                        maxlength: '{!! trans('custom_validation.max_length',['min' => 60]) !!}'
                    }
                },
                errorPlacement: function (error, element) {
                    switch (element.attr('name')) {
                        case 'srd_amount':
                            $('#amountError').html(error)
                            break;
                        case 'usd_amount':
                            $('#usdError').html(error)
                            break;
                        case 'euro_amount':
                            $('#euroError').html(error)
                            break;
                        case 'date':
                            $('#dateError').html(error)
                            break;
                        case 'name':
                            $('#nameError').html(error)
                            break;
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(addForm.valid()){
                $('.submitBtn').prop('disabled',true)
                let data = addForm.serialize()
                $.ajax({
                    url: ' {!! route('offerings.storeAjax') !!}',
                    method: 'post',
                    data: data,
                    complete: function ({status, responseJSON}) {
                        if(status === 200){
                            const message = 'test'
                            $('#addModal').modal('toggle')
                            dataTable.ajax.reload(onReloadComplete)
                            $('.submitBtn').prop('disabled',false)
                            toastr.success(message,'{{trans('common.success_label')}}')
                        }
                    }
                })
            }
        }

        function submitEditForm(){
            $('.submitBtn').prop('disabled',true)
            editForm.validate({
                rules:{
                    date: {
                        required:true,
                        date:true
                    },
                    srd_amount: {
                        required:true,
                        min: 0
                    },
                    usd_amount: {
                        required:true,
                        min: 0
                    },
                    euro_amount: {
                        required:true,
                        min: 0
                    },
                    name :{
                        required:true,
                        minlength: 3,
                        maxlength: 60
                    }
                },
                messages:{
                    date: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        date:'{!! trans('custom_validation.valid_date') !!}'
                    },
                    srd_amount: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        min: '{!! trans('custom_validation.min_amount',['min' => 0]) !!}'
                    },
                    usd_amount: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        min: '{!! trans('custom_validation.min_amount',['min' => 0]) !!}'
                    },
                    euro_amount: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        min: '{!! trans('custom_validation.min_amount',['min' => 0]) !!}'
                    },
                    name: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 3]) !!}',
                        maxlength: '{!! trans('custom_validation.max_length',['min' => 60]) !!}'
                    }
                },
                errorPlacement: function (error, element) {
                    switch (element.attr('name')) {
                        case 'srd_amount':
                            $('#editAmountError').html(error)
                            break;
                        case 'usd_amount':
                            $('#editUsdError').html(error)
                            break;
                        case 'euro_amount':
                            $('#editEuroError').html(error)
                            break;
                        case 'date':
                            $('#editDateError').html(error)
                            break;
                        case 'name':
                            $('#editNameError').html(error)
                            break;
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(editForm.valid()){
                let data = editForm.serialize()
                console.log(data)
                $.ajax({
                    url: ' {!! route('offerings.updateAjax') !!}',
                    method: 'patch',
                    data: data,
                    complete: function ({status, responseJSON}) {
                        if(status === 201){
                            const {message} = responseJSON
                            $('#editModal').modal('toggle')
                            dataTable.ajax.reload(onReloadComplete)
                            toastr.success(message,'{{trans('common.success_label')}}')
                        }
                    }
                })
            }
        }


        function openRemoveModal($event){
            $event.preventDefault()
            let OfferingDate = $event.target.getAttribute('data-date')
            let OfferingAmount = currency(parseFloat($event.target.getAttribute('data-amount'))).format()
            let offeringId = $event.target.getAttribute('data-id')
            $('#confirm_offering').html( `${OfferingDate}: SRD ${OfferingAmount} `);
            $('input[name="remove_offering_id"]').val(offeringId);
            $('#removeModal').modal('show')
        }

        function openEditModal($event){
            $event.preventDefault()
            let offeringId = parseInt($event.target.getAttribute('data-id'))
            let date = $('#edit_date')
            let name = $('#edit_name')


            $("#edit_offering_id").val(offeringId)
            $.ajax({
                url: '{!! route('offerings.getByIdAjax') !!}',
                method:'post',
                data:{
                    "_token": '{!! csrf_token() !!}',
                    "offering_id": offeringId
                },
                complete: function({status , responseJSON}){
                    const {offering} = responseJSON
                    console.log(offering.total_amount)
                    if(status === 201){
                        editSrdAmount.val(parseFloat(offering.srd_amount).toFixed(2)).change()
                        editUsdAmount.val(parseFloat(offering.usd_amount).toFixed(2)).change()
                        editEuroAmount.val(parseFloat(offering.euro_amount).toFixed(2)).change()
                        name.val(offering.name)
                        editTotalAmount.val((offering.total_amount/100).toFixed(2))
                        date.daterangepicker({
                            singleDatePicker:true,
                            autoUpdateInput: true,
                            startDate: offering.date,
                            showDropdowns: true,
                            minYear: 1901,
                            locale:datePickerTran,
                            applyButtonClasses:'btn btn-teal btn-sm',
                            cancelButtonClasses:'btn btn-danger btn-sm'
                        })
                    }
                    editModal.modal('show')
                }
            })
        }

        function updateTotalAmount($event){
            const {name, exchangeRate} = $event.data
            const amount = $event.currentTarget.value
            let temp = parseFloat(totalAmount.val())
            if(amount) {
                let newAmount = parseFloat(amount) * exchangeRate
                let newTotalAmount = currency(temp).add(newAmount).format()
                totalAmount.val(`${newTotalAmount}`)
            }
        }

        function updateDate($event,picker){
            console.log('new function')
            let val = picker.startDate.format('YYYY-MM-DD')
            $event.currentTarget.value = val
            if($event.data.target === 1){
                fromDate = val
            }
            else{
                toDate = val;
            }
        }

        function updateTotals($event){
            const {name, exchangeRate,totalElementId} = $event.data
            const amount = parseFloat(checkIfZero($event.currentTarget.value)).toFixed(2);
            let subTotal = (amount * parseFloat(exchangeRate)).toFixed(2)
            switch(name){
                case 'SRD':
                    srdTotal = subTotal
                    break
                case 'USD':
                    usdTotal = subTotal
                    break
                case 'EUR':
                    euroTotal = subTotal
                    break
            }
            let total = (parseFloat(srdTotal) + parseFloat(usdTotal) + parseFloat(euroTotal)).toFixed(2);
            $(`#${totalElementId}`).val(total)
        }
    </script>
@endsection
