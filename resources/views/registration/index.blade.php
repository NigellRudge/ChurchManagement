@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="d-flex card-header bg-white mb-2 justify-content-between">
                    <div class="">
                        <span class="font-weight-bold text-lg text-dark">{{trans('common.covid_reg_sheets_label')}}</span>
                    </div>
                    <a class="btn btn-teal pt-2 font-weight-bold" onclick="openAddModal(event)">
                        {{trans('common.add_sheet_label')}}
                        <i class="ml-1 fas fa-plus"></i>
                    </a>
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-bordered display compact nowrap">
                        <thead>
                        <tr class="text-dark">
                            <th style="width: 50px">Id</th>
                            <th>{{trans('common.name_label')}}</th>
                            <th>
                                <span class="mr-1"><i class="fa fa-calendar text-teal"></i></span>
                                {{trans('common.date_label')}}
                            </th>
                            <th>
                                <span class="mr-1"><i class="fa fa-users text-teal"></i></span>
                                {{trans('common.num_mems_present')}}
                            </th>
                            <th style="width: 110px"></th>
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
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.new_covid_sheet')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class=" mt-2 pl-3 pr-3">
                    <form method="post" action="#" id="add_form">
                        @csrf
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="date" class="text-dark font-weight-bold">{{trans('common.date_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white">
                                                <span><i class="fa fa-calendar text-teal"></i></span>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" autocomplete="off" id="date" name="date">
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
                    </form>
                </div>
                <div class="modal-footer">
                    <button onclick="submitAddForm()" id="add_submitBtn" class="btn btn-teal">
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
                            <div class="pt-2">
                                {{trans('common.remove_sheet_label')}}<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_sheet"></div> ?
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

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title font-weight-bold text-light" id="editModalLabel">{{trans('common.edit_covid_sheet_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class=" mt-2 pl-3 pr-3">
                    <form method="post" action="#" id="edit_form">
                        @csrf
                        <input type="hidden" id="edit_sheet_id" name="edit_sheet_id">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_date" class="text-dark font-weight-bold">{{trans('common.date_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <span><i class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" autocomplete="off" id="edit_date" name="date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_name" class="text-dark font-weight-bold">{{trans('common.name_label')}}<span class="text-danger">*</span></label>
                                    <input type="text" id="edit_name" name="name" class="form-control">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button onclick="submitEditForm()" id="edit_add_submitBtn" class="btn btn-teal">
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
    <script>
        const addModal = $('#addModal')
        const editModal = $('#editModal')
        const editForm = $('#edit_form')
        const addForm = $('#add_form')
        const removeForm = $('#remove_form')

        const dataTable = $("#datatable").DataTable({
            processing: true,
            serverSide: true,
            lengthMenu: [10, 25, 50, 75, 100 ],
            pageLength:10,
            ajax: '{!! route('covid-registration.index') !!}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'date', name: 'date' },
                { data: 'registered_members', name: 'registered_members'},
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ]
        });
        $(document).ready(function(){

            removeForm.submit(function($event){
                $event.preventDefault()
                let data = removeForm.serialize()
                console.log(data)
                $.ajax({
                    url: '{!! route('covid-registration.destroySheet') !!}',
                    method: 'delete',
                    data: data,
                    complete: function({status, responseJSON}){
                        let {message} = responseJSON
                        if(status === 201){
                            $('#removeModal').modal('hide')
                            dataTable.ajax.reload()
                            toastr.warning(message,'{{trans('common.success_label')}}')
                        }
                    }
                })
            })

            $(".modal").on("hidden.bs.modal", function() {
                clearForm('edit_form',false)
                clearForm('add_form',false)
            });
        });

        function submitAddForm() {
            addForm.validate({
                rules:{
                    name:{
                        required:true,
                        minlength:5,
                        maxlength:50,
                    },
                    date: {
                        required:true,
                        date: true
                    }
                },
                messages:{
                    name: {
                        required: '{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 5]) !!}',
                        maxlength: '{!! trans('custom_validation.max_amount',['max' => 50]) !!}'
                    },
                    date: {
                        required: '{!! trans('custom_validation.date_required') !!}',
                        date: '{!! trans('custom_validation.valid_date') !!}'
                    },
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(addForm.valid()){
                let data = addForm.serialize();
                console.log(data)
                $.ajax({
                    url: ' {!! route('covid-registration.storeSheet') !!}',
                    method: 'post',
                    data: data,
                    complete: function ({status,responseJSON}) {
                        let {message} = responseJSON
                        if(status === 201){
                            $('#addModal').modal('hide')
                            dataTable.ajax.reload()
                            toastr.success(message,'{{trans('common.success_label')}}')
                        }
                    }
                })
            }
        }

        function submitEditForm() {
            editForm.validate({
                rules:{
                    name:{
                        required:true,
                        minlength:5,
                        maxlength:50,
                    },
                    date: {
                        required:true,
                        date: true
                    }
                },
                messages:{
                    name: {
                        required: '{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 5]) !!}',
                        maxlength: '{!! trans('custom_validation.max_amount',['max' => 50]) !!}'
                    },
                    date: {
                        required: '{!! trans('custom_validation.date_required') !!}',
                        date: '{!! trans('custom_validation.valid_date') !!}'
                    },
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(editForm.valid()){
                let data = editForm.serialize();
                console.table(editForm.serializeArray())
                $.ajax({
                    url: ' {!! route('covid-registration.updateSheet') !!}',
                    method: 'patch',
                    data: data,
                    complete: function ({status,responseJSON },) {
                        let {message} = responseJSON
                        if(status === 201){
                            $('#editModal').modal('hide')
                            dataTable.ajax.reload()
                            toastr.success(message,'{{trans('common.success_label')}}')
                        }
                    }
                })
            }
        }

        function openAddModal($event){
            $event.preventDefault();
            // Setup
            const submitBtn = $('#add_submitBtn')
            const name = $('#name')
            const date = $('#date')

            // setup datepicker
            date.daterangepicker({
                showWeekNumbers:true,
                singleDatePicker:true,
                timePickerIncrement:15,
                drops:'down',
                locale:datePickerTran,
                autoUpdateInput: true,
                showDropdowns: true,
                applyButtonClasses:'btn btn-teal btn-sm',
                cancelButtonClasses:'btn btn-danger btn-sm'
            })
            date.on('apply.daterangepicker',function(ev,picker){
                console.log(picker.startDate.format('YYYY-MM-DD'))
            })
            name.on('change',function($event){
                let value = $(this).val()
                console.log(value)
                if(value.length > 0){
                    submitBtn.attr('disabled',false)
                }
                else {
                    submitBtn.attr('disabled',true)
                }
            })
            addModal.modal('show')
        }

        function openEditModal($event){
            $event.preventDefault();
            const id = $event.target.getAttribute('data-id')
            const sheetName = $event.target.getAttribute('data-name')
            const sheetDate = $event.target.getAttribute('data-date')

            const name = $('#edit_name')
            const date = $('#edit_date')

            $('#edit_sheet_id').val(id)
            name.val(sheetName)
            date.daterangepicker({
                showWeekNumbers:true,
                startDate: sheetDate,
                singleDatePicker:true,
                timePickerIncrement:15,
                drops:'down',
                locale:datePickerTran,
                autoUpdateInput: true,
                showDropdowns: true,
                applyButtonClasses:'btn btn-teal btn-sm',
                cancelButtonClasses:'btn btn-danger btn-sm'
            })
            console.log(`Sheet id: ${id}`)
            editModal.modal('show')
        }

        function openRemoveModal($event){
            $event.preventDefault()
            let removeModal = $('#removeModal')
            removeModal.modal('show')
            let date = $event.target.getAttribute('data-date')
            let name = $event.target.getAttribute('data-name')
            let sheetId = $event.target.getAttribute('data-id')
            $('#confirm_sheet').html( `${name}  ${date} `);
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

    </script>
@endsection
