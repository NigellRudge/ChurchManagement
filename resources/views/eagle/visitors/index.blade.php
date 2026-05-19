@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="card-header bg-white d-flex mb-2 justify-content-between">
                    <div class="font-weight-bold text-lg text-dark">
                        {{trans('common.first_time_visitors_label')}}
                    </div>
                    <div>
                        <button onclick="openAddModal(event)" class="btn btn-teal text-light font-weight-bold">
                            {{trans('common.add_sheet_label')}}
                            <i class="ml-1 fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="fix-topbar">
                        <table id="datatable" class="table table-bordered display compact nowrap">
                            <thead>
                            <tr class="text-dark">
                                <th>Id</th>
                                <th>{{trans('common.name_label')}}</th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-calendar text-teal"></i></span>
                                    {{trans('common.date_label')}}
                                </th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-users text-teal"></i></span>
                                    {{trans('common.num_visitors_label')}}
                                </th>
                                <th style="width: 120px"></th>
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

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light font-weight-bold">
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.add_sheet_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="font-weight-bold text-light">&times;</span>
                    </button>
                </div>
                <div class="pl-4 pr-4 mt-2 mb-4">
                    <form method="post" action="#" id="addForm">
                        @csrf
                        <div class="form-row">
                            <div class="col">
                                <div class="from-group">
                                    <label for="date" class="font-weight-bolder">{{trans('common.date_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                    <select id="date" name="date" class="form-control">
                                    </select>
                                    <div id="dateError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col">
                                <div class="from-group">
                                    <label for="name" class="font-weight-bolder">{{trans('common.name_label')}}</label>
                                    <input type="text" id="name" name="name" class="form-control" />
                                    <div id="nameError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button onclick="submitAddForm()" class="btn btn-teal font-weight-bold text-light">
                        <span class="mr-1"><i class="fa fa-save"></i></span>
                        {{trans('common.save_label')}}
                    </button>
                    <button type="button" class="btn btn-danger font-weight-bold text-light">
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
                <div class="modal-header bg-teal text-light font-weight-bold">
                    <h5 class="modal-title" id="editModalLabel">{{trans('common.edit_sheet_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="font-weight-bold text-light">&times;</span>
                    </button>
                </div>
                <div class="pl-4 pr-4 mt-2 mb-4">
                    <form method="post" action="#" id="editForm">
                        @csrf
                        <input type="hidden" id="edit_sheet_id" name="sheet_id">
                        <div class="form-row">
                            <div class="col">
                                <div class="from-group">
                                    <label for="edit_date" class="font-weight-bolder">{{trans('common.date_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                    <select id="edit_date" name="date" class="form-control">
                                    </select>
                                    <div id="editDateError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col">
                                <div class="from-group">
                                    <label for="edit_name" class="font-weight-bolder">{{trans('common.name_label')}}</label>
                                    <input type="text" id="edit_name" name="name" class="form-control" />
                                    <div id="editNameError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button onclick="submitEditForm()" class="btn btn-teal font-weight-bold text-light">
                        <span class="mr-1"><i class="fa fa-save"></i></span>
                        {{trans('common.save_label')}}
                    </button>
                    <button type="button" class="btn btn-danger font-weight-bold text-light">
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="removeForm">
                    @csrf
                    <input type="hidden" name="remove_sheet_id" id="remove_sheet_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-1">
                                {{trans('common.remove_sheet_label')}}:<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_sheet"></div> ?
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal">
                            <span class="mr-1"><i class="fa fa-save"></i></span>
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
        let genderId = 0;

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
                url:'{!! route('visitors.index') !!}',
                data: function (data) {
                    data.gender_id = genderId
                }
            },
            columns: [
                {data: 'id', name: 'id',searchable: false},
                {data: 'name', name: 'name'},
                {data: 'date', name: 'date'},
                {data: 'num_visitors', name: 'num_visitors',searchable: false},
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ]
        });
        const editForm = $('#editForm')
        const addForm = $('#addForm');
        const removeForm = $('#removeForm');
        $(document).ready(function(){
            editForm.submit(function ($event) {
                $event.preventDefault();
            })

            $(".modal").on("hidden.bs.modal", function() {
                clearForm('addForm',false)
                clearForm('editForm',false)
            });

            removeForm.submit(function($event){
                $event.preventDefault();
                let data = removeForm.serialize();
                console.log(data)

                $.ajax({
                    url: '{!! route('visitors.destroySheet') !!}',
                    method: 'delete',
                    data:data,
                    complete: function(xhr){
                        console.log(xhr.responseJSON)
                        if(xhr.status === 201){
                            let message = xhr.responseJSON.message
                            dataTable.ajax.reload()
                            toastr.warning(message,'Success')
                            $('#removeModal').modal('hide')
                        }
                    }
                })
            })
        });

        function submitAddForm() {
            addForm.validate({
                rules:{
                    date:{
                        required:true,
                    },
                    name:{
                        required:true,
                        minlength:4,
                        maxlength:50
                    },
                },
                messages:{
                    date: {
                        required: '{!! trans('custom_validation.select_option') !!}',
                    },
                    name:{
                        required: '{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 4]) !!}',
                        maxlength:'{!! trans('custom_validation.max_length',['max' => 50]) !!}',
                    }

                },
                errorPlacement: function(error, element){
                    switch(element.attr('name')){
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
            if (addForm.valid()) {
                let data = addForm.serialize();
                $.ajax({
                    url: '{!! route('visitors.storeSheet') !!}',
                    method: 'post',
                    data: data,
                    complete: function ({status, responseJSON}) {
                        if (status === 201) {
                            let {message} = responseJSON
                            dataTable.ajax.reload()
                            toastr.success(message, 'Success')
                            addModal.modal('hide')
                        }
                    }
                })
            }
        }

        function submitEditForm(){
            editForm.validate({
                rules:{
                    date:{
                        required:true,
                    },
                    name:{
                        required:true,
                        minlength:4,
                        maxlength:50
                    },
                },
                messages:{
                    date: {
                        required: '{!! trans('custom_validation.select_option') !!}',
                    },
                    name:{
                        required: '{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 4]) !!}',
                        maxlength:'{!! trans('custom_validation.max_length',['max' => 50]) !!}',
                    }

                },
                errorPlacement: function(error, element){
                    switch(element.attr('name')){
                        case 'date':
                            $('#edtDateError').html(error)
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
                let data = editForm.serialize();
                $.ajax({
                    url: '{!! route('visitors.updateSheet') !!}',
                    method: 'patch',
                    data: data,
                    complete: function ({status, responseJSON}) {
                        if (status === 201) {
                            let {message} = responseJSON
                            dataTable.ajax.reload()
                            toastr.success(message, 'Success')
                            editModal.modal('hide')
                        }
                    }
                })
            }
        }

        function openAddModal($event){
            $event.preventDefault()
            $('#name').val("")
            addModal.modal('show')
            const dateInput = $('#date').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('visitors.getDates') !!}',
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
                    placeholder: 'Select Date',
                    processResults: function(data){
                        //console.log(data)
                        return {
                            results: data.results,
                            pagination: {
                                more: false
                            }
                        }
                    }
                }
            });
        }

        function openRemoveModal($event){
            $event.preventDefault()
            let removeModal = $('#removeModal')
            removeModal.modal('show')
            let sheetName = $event.target.getAttribute('data-name')
            let sheet_id = $event.target.getAttribute('data-id')
            $('#confirm_sheet').html( `${sheetName}`);
            $('input[name="remove_sheet_id"]').val(sheet_id.toString());
        }

        function openEditModal($event){
            $event.preventDefault();
            let name = $('#edit_name')
            let sheetId = $event.target.getAttribute('data-id')
            $.ajax({
                url: '{!! route('visitors.getSheetByIdAjax') !!}',
                method: 'post',
                data: {
                    _token:'{!! csrf_token() !!}',
                    sheet_id: sheetId
                },
                complete: function({status, responseJSON}){
                    let {sheet} = responseJSON
                    if(status === 201){
                        name.val(sheet.name)
                        setupEditDate(sheet.date)
                        $('#edit_sheet_id').val(sheetId)
                        editModal.modal('show')
                    }
                }

            })

        }

        function setupEditDate(date){
            let dateSelect = $('#edit_date').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('visitors.getDates') !!}',
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
            let option = new Option(date,date,true, true)
            dateSelect.append(option).trigger('change')
        }
    </script>
@endsection
