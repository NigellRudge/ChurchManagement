@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="card-header bg-white d-flex flex-row justify-content-between">
                        <span class="card-title font-weight-bold text-lg text-dark">{{trans('common.event_registration_label')}}</span>
                        <a class="btn btn-teal pt-2 font-weight-bold" onclick="openAddModal(event)">
                            {{trans('common.add_sheet_label')}}
                            <i class="ml-1 fas fa-plus"></i>
                        </a>
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-bordered display compact nowrap">
                        <thead>
                        <tr class="text-dark">
                            <th>Id</th>
                            <th>{{trans('common.name_label')}}</th>
                            <th>
                                <span class="mr-1"><i class="fa fa-calendar-check text-teal"></i></span>
                                {{trans('common.last_registration_date_label')}}
                            </th>
                            <th>
                                <span class="mr-1"><i class="fa fa-dollar-sign text-teal"></i></span>
                                {{trans('common.registration_fee_label')}}
                            </th>
                            <th>
                                <span class="mr-1"><i class="fa fa-users text-teal"></i></span>
                                {{trans('common.registered_amount_label')}}
                            </th>
                            <th style="width: 120px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.add_sheet_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="add_form">
                    @csrf
                    <div class=" mt-2 pl-3 pr-3">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="event_id" class="font-weight-bold text-dark">Event</label>
                                    <div class="input-group">
                                        <select name="event_id" id="event_id" class="form-control"></select>
                                        <div class="input-group-append">
                                            <div class="input-group-text font-weight-bold bg-danger" id="event_clearBtn">
                                                <span class="font-weight-bold text-light" >X</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="name" class="text-dark font-weight-bold">{{trans('common.name_label')}}<span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="last_registration_date" class="text-dark font-weight-bold">{{trans('common.last_registration_date_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <span><i class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" autocomplete="off" id="last_registration_date" name="last_registration_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="registration_fee" name="registration_fee">
                                        <label class="form-check-label text-dark font-weight-bold" for="registration_fee">
                                            {{trans('common.registration_fee_label')}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-1 mb-2">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="currency_id" class="font-weight-bold text-dark">{{trans('common.currency_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <i class="fas fa-dollar-sign"></i>
                                            </div>
                                        </div>
                                        <select name="currency_id" id="currency_id" class="form-control" disabled>
                                            @foreach($data['currencies'] as $currency)
                                                <option id="currency_{{$currency->id}}" value="{{$currency->id}}">{{$currency->code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="registration_price" class="font-weight-bold text-dark">{{trans('common.amount_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <i class="fas fa-coins"></i>
                                            </div>
                                        </div>
                                        <input  disabled type="number" placeholder="$0.00" min="0.00" max="1000000" step="0.05" id="registration_price" name="registration_price" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-1 mb-3">
                            <div class="col-3">
                                <div class="form-group pt-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="limit_registrations" name="limit_registrations">
                                        <label class="form-check-label text-dark font-weight-bold" for="limit_registrations">
                                            {{trans('common.limit_label')}} ?
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="from-group">
                                    <label for="max_registrations" class="font-weight-bold text-dark">{{trans('common.max_num_registration')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal">
                                                <i class="fa fa-users text-light"></i>
                                            </div>
                                        </div>
                                        <input type="number" max="500" step="1.00" min="1.00" id="max_registrations" name="max_registrations" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="add_submitBtn" disabled class="btn btn-teal">
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

    <div class="modal" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="exampleModalLabel">{{trans('common.confirm_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="remove_form">
                    @csrf
                    <input type="hidden" name="remove_sheet_id" id="remove_sheet_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-4">
                                {{trans('confirm_remove_sheet_label')}}:<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_sheet"></div> ?
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

    <div class="modal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title font-weight-bold text-light" id="editModalLabel">{{trans('common.edit_sheet_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="edit_form">
                    @csrf
                    <input type="hidden" id="edit_sheet_id" name="edit_sheet_id">
                    <div class=" mt-2 pl-3 pr-3">
{{--                        <div class="form-row">--}}
{{--                            <div class="col">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="edit_event_id" class="font-weight-bold text-dark">Event</label>--}}
{{--                                    <div class="input-group">--}}
{{--                                        <select name="edit_event_id" id="edit_event_id" class="form-control"></select>--}}
{{--                                        <div class="input-group-append">--}}
{{--                                            <div class="input-group-text font-weight-bold bg-danger" id="event_clearBtn">--}}
{{--                                                <span class="font-weight-bold text-light" >X</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_name" class="text-dark font-weight-bold">{{trans('common.name_label')}}<span class="text-danger">*</span></label>
                                    <input type="text" id="edit_name" name="edit_name" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_last_registration_date" class="text-dark font-weight-bold">{{trans('common.last_registration_date_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <span><i class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" autocomplete="off" id="edit_last_registration_date" name="edit_last_registration_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="edit_registration_fee" name="edit_registration_fee">
                                        <label class="form-check-label text-dark font-weight-bold" for="edit_registration_fee">
                                            {{trans('common.registration_fee_label')}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-1 mb-2">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="edit_currency_id" class="font-weight-bold text-dark">{{trans('common.currency_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <i class="fas fa-dollar-sign"></i>
                                            </div>
                                        </div>
                                        <select name="edit_currency_id" id="edit_currency_id" class="form-control" disabled>
                                            @foreach($data['currencies'] as $currency)
                                                <option id="currency_{{$currency->id}}" value="{{$currency->id}}">{{$currency->code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_registration_price" class="font-weight-bold text-dark">{{trans('amount')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <i class="fas fa-coins"></i>
                                            </div>
                                        </div>
                                    <input  disabled type="number" placeholder="$0.00" min="0.00" max="1000000" step="0.05" id="edit_registration_price" name="edit_registration_price" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-1 mb-3">
                            <div class="col-3">
                                <div class="form-group pt-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="edit_limit_registrations" name="edit_limit_registrations">
                                        <label class="form-check-label text-dark font-weight-bold" for="edit_limit_registrations">
                                            {{trans('common.limit_label')}} ?
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="from-group">
                                    <label for="edit_max_registrations" class="font-weight-bold text-dark">{{trans('common.max_num_registration')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal">
                                                <i class="fa fa-users text-light"></i>
                                            </div>
                                        </div>
                                        <input type="number" max="500" step="1.00" min="1.00" id="edit_max_registrations" name="edit_max_registrations" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="edit_add_submitBtn" class="btn btn-teal">
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

@section('custom_css')
    @include('shared.totalCSS')
@endsection

@section('custom_js')
    @include('shared.totalJS')
    <script>
        $(document).ready(function(){
            const dataTable = $("#datatable").DataTable({
                processing: true,
                language: datatableTrans,
                autoWidth:false,
                serverSide: true,
                lengthMenu: [10, 25, 50, 75, 100 ],
                pageLength:10,
                ajax: '{!! route('events.registration') !!}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'last_registration_date', name: 'last_registration_date' },
                    {data: 'registration_price',name: 'registration_price'},
                    { data: 'registered_members', name: 'registered_members'},
                    { data:'actions', name:'actions', orderable: false, searchable: false}
                ]
            });
            const addForm = $('#add_form')
            addForm.validate({
                rules:{
                    name:{
                        required:true,
                        minlength:5,
                        maxlength:50,
                    },
                    last_registration_date: {
                        required:true,
                        date: true
                    }
                },
                messages:{
                    name: "please enter a valid name",
                    last_registration_date: "please enter a valid date",
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            addForm.submit(function($event){
                $event.preventDefault();
                $('#name').attr('disabled',false)
                $('#last_registration_date').attr('disabled',false)
                $('#registration_fee').attr('disabled',false)
                $('#currency_id').attr('disabled',false)
                $('#registration_price').attr('disabled',false)
                let data = addForm.serialize();
                console.log(data)
                $.ajax({
                    url: ' {!! route('events.storeRegistrationSheet') !!}',
                    method: 'post',
                    data: data,
                    complete: function (xhr,status) {
                        console.log(xhr.responseJSON)
                        let message = xhr.responseJSON.message
                        if(xhr.status === 201){
                            $('#addModal').modal('hide')
                            dataTable.ajax.reload()
                            toastr.success(message,'Success')
                        }
                    }
                })
            })

            const removeForm = $('#remove_form')
            removeForm.submit(function($event){
                $event.preventDefault()
                let data = removeForm.serialize()
                console.log(data)
                $.ajax({
                    url: '{!! route('events.destroySheetAjax') !!}',
                    method: 'post',
                    data: data,
                    complete: function(xhr){
                        let message = xhr.responseJSON.message
                        if(xhr.status === 201){
                            $('#removeModal').modal('hide')
                            dataTable.ajax.reload()
                            toastr.warning(message,'Success')
                        }
                    }
                })
            })

            const editForm = $('#edit_form')
            editForm.submit(function($event){
                $event.preventDefault()
                $('#edit_name').attr('disabled',false)
                $('#edit_last_registration_date').attr('disabled',false)
                $('#edit_registration_fee').attr('disabled',false)
                $('#edit_currency_id').attr('disabled',false)
                $('#edit_registration_price').attr('disabled',false)
                let data = editForm.serialize();
                console.table(editForm.serializeArray())
                $.ajax({
                    url: ' {!! route('events.updateRegistrationSheet') !!}',
                    method: 'post',
                    data: data,
                    complete: function (xhr,status) {
                        console.log(xhr.responseJSON)
                        let message = xhr.responseJSON.message
                        if(xhr.status === 201){
                            $('#editModal').modal('hide')
                            dataTable.ajax.reload()
                            toastr.success(message,'Success')
                        }
                    }
                })
            })
            cleanUp()
        });

        function openAddModal($event){
            const now = new Date();
            const minYear = now.getFullYear()
            const minDate = `${now.getMonth()+1}/${now.getDate()<10? '0'+now.getDate() : now.getDate()}/${now.getFullYear()}`;
            $event.preventDefault();
            console.log('Open !!!')

            // Setup
            let limitRegistrations = $('#limit_registrations')
            let maxRegistrations = $('#max_registrations')
            let submitBtn = $('#add_submitBtn')
            let addModal = $('#addModal')
            let eventClearBtn = $('#event_clearBtn')
            let event = $('#event_id')
            let name = $('#name')
            let lastRegistrationDate = $('#last_registration_date')
            let registrationPrice = $('#registration_price')
            let currency = $('#currency_id')
            let registrationFee = $('#registration_fee')

            // initialize select2 input
            event.select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('events.eventsListJson') !!}',
                    type: 'post',
                    data: function(params){
                        return {
                            _token: '{!! csrf_token() !!}',
                            name:params.term,

                        }
                    },
                    dataType: 'json',
                    cache:true,
                    delay:100,
                    allowClear: true,
                    placeholder: 'Search Currency',
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
            // When After selecting event
            event.on('change',function($event){
                let eventId = this.value;
                $.ajax({
                    method:'post',
                    url: '{!! route('events.getEventByIdJson') !!}',
                    data: {
                        _token: '{!! csrf_token() !!}',
                        event_id: eventId
                    },
                    complete: function(xhr){
                       console.log( xhr.responseJSON)
                        let eventData = xhr.responseJSON.event
                        if(xhr.status === 201){
                            setupInputs(eventData)
                        }
                    }
                })
            })
            // Clear input when event cleared
            eventClearBtn.on('click',function($event){
                clearInputs()
            })
            // toggle currency and amount when checked
            registrationFee.change(function(){
               if(this.checked){
                    currency.attr('disabled',false)
                    registrationPrice.attr('disabled',false)
               }
               else {
                   currency.attr('disabled',true)
                   registrationPrice.val('')
                   registrationPrice.attr('disabled',true)
               }
            });
            limitRegistrations.change(function(){
                if(this.checked){
                    maxRegistrations.attr('disabled',false)
                }
                else {
                    maxRegistrations.attr('disabled',true)
                    maxRegistrations.val('')
                }
            });
            // setup datepicker
            lastRegistrationDate.daterangepicker({
                showWeekNumbers:true,
                singleDatePicker:true,
                timePickerIncrement:15,
                drops:'below',
                locale: {
                    format:'MM/DD/YYYY'
                },
                autoUpdateInput: false,
                showDropdowns: true,
                minYear: minYear,
                minDate:minDate
            })
            lastRegistrationDate.on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY'));
                submitBtn.attr('disabled',false)
            });
            addModal.modal('show')
        }

        function openEditModal($event){
            $event.preventDefault();
            let editModal = $('#editModal')
            let sheetId = $event.target.getAttribute('data-id')
            let editRegistrationFee = $('#edit_registration_fee')
            let editCurrency = $('#edit_currency_id')
            let maxRegistrations = $('#edit_max_registrations')
            let limitRegistrations = $('#edit_limit_registrations')
            let editName = $('#edit_name')
            let editRegistrationPrice = $('#edit_registration_price')
            $('#edit_sheet_id').val(sheetId)
            console.log(sheetId)
            $.ajax({
                url: '{!! route('events.getSheetByIdJson') !!}',
                method: 'post',
                data: {
                    _token: '{!! csrf_token() !!}',
                    sheet_id: sheetId
                },
                complete: function(xhr){
                    let sheet = xhr.responseJSON.sheet
                    console.log(sheet)
                    if(xhr.status === 201){
                        editName.val(sheet.name)
                        editRegistrationPrice.val(sheet.min_amount)
                        //setupEditCurrency(sheet.currency_id)
                        console.log(`event id: ${sheet.event_id}`)
                        setupEditEvent(sheet.event_id)
                        if(sheet.min_amount != null && sheet.min_amount > 0){

                            editRegistrationFee.prop('checked',true)
                            editCurrency.val(sheet.currency_id)
                            editCurrency.attr('disabled',false)
                            editRegistrationPrice.attr('disabled',false)
                            editRegistrationPrice.val(sheet.min_amount)
                        }
                        $('#edit_last_registration_date').daterangepicker({
                            singleDatePicker:true,
                            autoUpdateInput: true,
                            startDate: sheet.last_registration_date,
                            showDropdowns: true,
                            minYear: 1901,
                        })
                        if(sheet.limit_registrations === 1){
                            limitRegistrations.prop('checked',true)
                            maxRegistrations.attr('disabled',false)
                            maxRegistrations.val(sheet.max_registrations)
                        }
                    }
                }
            })

            editModal.modal('show')
        }

        function setupEditCurrency(currencyId){
            if(currencyId != null){
                let currencySelect = $('#edit_currency_id').select2({
                    theme: 'bootstrap4',
                    ajax: {
                        url: '{!! route('currency.getJson') !!}',
                        type: 'post',
                        data: function(params){
                            return {
                                _token: '{!! csrf_token() !!}',
                                name:params.term
                            }
                        },
                        dataType: 'json',
                        cache:true,
                        delay:200,
                        placeholder: 'Search Currency',
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
                    url: '{!! route('currency.getByIdJson') !!}',
                    data: {
                        _token: '{!! csrf_token() !!}',
                        currencyId:currencyId
                    }
                }).then(function(data){
                    let currency = data.currency[0]
                    let option = new Option(currency.code,currency.id,true, true)
                    currencySelect.append(option).trigger('change')
                    currencySelect.trigger({
                        type: 'select2:select',
                        params: {
                            data: data
                        }
                    });
                });
            }
        }
        function setupEditEvent(eventId){
            if(eventId != null){
                let eventSelect = $('#edit_event_id').select2({
                    theme: 'bootstrap4',
                    ajax: {
                        url: '{!! route('events.eventsListJson') !!}',
                        type: 'post',
                        data: function(params){
                            return {
                                _token: '{!! csrf_token() !!}',
                                name:params.term
                            }
                        },
                        dataType: 'json',
                        cache:true,
                        delay:100,
                        allowClear: true,
                        placeholder: 'Search Event',
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
                    url: '{!! route('events.getEventByIdJson') !!}',
                    data: {
                        _token: '{!! csrf_token() !!}',
                        event_id:eventId
                    }
                }).then(function(data){
                    let event = data.event
                    console.log('event')
                    console.log(event)
                    let option = new Option(event.title,event.id,true, true)
                    eventSelect.append(option).trigger('change')
                    eventSelect.trigger({
                        type: 'select2:select',
                        params: {
                            data: data
                        }
                    });
                });
            }
        }


        function openRemoveModal($event){
            $event.preventDefault()
            let removeModal = $('#removeModal')
            removeModal.modal('show')
            let sheetName = $event.target.getAttribute('data-name')
            let sheetId = $event.target.getAttribute('data-id')
            $('#confirm_sheet').html( `${sheetName} `);
            $('input[name="remove_sheet_id"]').val(sheetId.toString());
        }

        function setupInputs(eventData){
            let name =  $('#name')
            name.val(`${eventData.title}  Registration Sheet`)
            name.attr('disabled',true)

            let lastRegistrationDate = $('#last_registration_date')
            lastRegistrationDate.val(eventData.last_registration_date)
            lastRegistrationDate.attr('disabled',true)
            if(eventData.should_register === 1){
                let registrationFee = $('#registration_fee')
                registrationFee.prop('checked',true)
                registrationFee.attr('disabled',true)

                $(`#currency_${eventData.currency_id}`).attr('selected',true)
                $('#currency_id').attr('disabled',true)

                let registrationPrice = $('#registration_price')
                registrationPrice.val(eventData.reg_price)
                registrationPrice.attr('disabled',true)
                let submitBtn = $('#add_submitBtn')
                submitBtn.attr('disabled',false)
            }
        }

        function clearInputs(){
            console.log('clean up')
            let eventId =  $('#event_id')
            eventId.select2()
            eventId.select2("destroy");
            eventId.val('')

            let name =  $('#name')
            name.val('')
            name.attr('disabled',false)
            name.removeClass('is-valid');
            name.removeClass('is-invalid');

            let lastRegistrationDate = $('#last_registration_date')
            lastRegistrationDate.val('')
            lastRegistrationDate.attr('disabled',false)
            lastRegistrationDate.removeClass('is-valid');
            lastRegistrationDate.removeClass('is-invalid');

            let registrationFee = $('#registration_fee')
            registrationFee.prop('checked',false)
            registrationFee.attr('disabled',false)

            let currencyId = $('#currency_id')
            currencyId.attr('disabled',true)
            currencyId.removeClass('is-valid');
            currencyId.removeClass('is-invalid');

            let registrationPrice = $('#registration_price')
            registrationPrice.val('')
            registrationPrice.attr('disabled',true)
            registrationPrice.removeClass('is-valid');
            registrationPrice.removeClass('is-invalid');

            let submitBtn = $('#add_submitBtn')
            submitBtn.attr('disabled',true)

            let editEventId =  $('#edit_event_id')
            editEventId.select2()
            editEventId.select2("destroy");
            editEventId.val('')

            let editName =  $('#edit_name')
            editName.val('')
            editName.attr('disabled',false)
            editName.removeClass('is-valid');
            editName.removeClass('is-invalid');

            let editLastRegistrationDate = $('#edit_last_registration_date')
            editLastRegistrationDate.val('')
            editLastRegistrationDate.attr('disabled',false)
            editLastRegistrationDate.removeClass('is-valid');
            editLastRegistrationDate.removeClass('is-invalid');

            let editRegistrationFee = $('#edit_registration_fee')
            editRegistrationFee.prop('checked',false)
            editRegistrationFee.attr('disabled',false)

            let editCurrencyId = $('#edit_currency_id')
            editCurrencyId.attr('disabled',true)
            editCurrencyId.removeClass('is-valid');
            editCurrencyId.removeClass('is-invalid');

            let editRegistrationPrice = $('#edit_registration_price')
            editRegistrationPrice.val('')
            editRegistrationPrice.attr('disabled',true)
            editRegistrationPrice.removeClass('is-valid');
            editRegistrationPrice.removeClass('is-invalid');

            $('#limit_registrations').prop('checked',false)
            let maxRegistrations = $('#max_registrations')
            maxRegistrations.attr('disabled',true)
            maxRegistrations.val('')

            $('#edit_limit_registrations').prop('checked',false)
            let editMaxRegistrations = $('#edit_max_registrations')
            editMaxRegistrations.attr('disabled',true)
            editMaxRegistrations.val('')

        }

        function cleanUp(){
            $(".modal").on("hidden.bs.modal", function() {
                clearInputs()
            });
        }
    </script>
@endsection
