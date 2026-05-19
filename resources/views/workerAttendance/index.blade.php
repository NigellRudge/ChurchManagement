@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between mb-2">
                    <div class="font-weight-bold text-lg text-dark">
                        {{trans('common.workers_attendance_label')}}
                    </div>
                    <div>
                        <button id="addButton" class="btn btn-teal text-light font-weight-bold">
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
                                    {{trans('common.work_group_label')}}
                                </th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-user-check text-teal"></i></span>
                                    {{trans('common.num_mems_present')}}
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
                                    <label for="name" class="font-weight-bolder">{{trans('common.name_label')}}</label>
                                    <input type="text" id="name" name="name" class="form-control" />
                                    <div id="nameError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col">
                                <div class="from-group">
                                    <label for="add_date" class="font-weight-bolder">{{trans('common.date_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                        <input id="add_date" name="date" class="form-control" type="text" />
                                    </div>
                                    <div id="dateError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col">
                                <div class="from-group">
                                    <label for="add_group_id" class="font-weight-bolder">{{trans('common.work_group_label')}}</label>
                                    <select id="add_group_id" name="group_id" class="form-control">
                                    </select>
                                    <div id="groupError"></div>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button onclick="submitAddForm(event)" class="btn btn-teal font-weight-bold text-light">
                            <span class="mr-1"><i class="fa fa-save"></i></span>
                            {{trans('common.save_label')}}
                        </button>
                        <button data-dismiss="modal"  type="button" class="btn btn-danger font-weight-bold text-light">
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
                            <input type="hidden" name="sheet_id" id="edit_sheet_id">
                        <div class="form-row">
                            <div class="col">
                                <div class="from-group">
                                    <label for="edit_name" class="font-weight-bolder">{{trans('common.name_label')}}</label>
                                    <input type="text" id="edit_name" name="name" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col">
                                <div class="from-group">
                                    <label for="edit_date" class="font-weight-bolder">{{trans('common.date_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                        <input id="edit_date" name="date" class="form-control" type="text" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col">
                                <div class="from-group">
                                    <label for="edit_group_id" class="font-weight-bolder">{{trans('common.work_group_label')}}</label>
                                    <select id="edit_group_id" name="group_id" class="form-control">
                                    </select>
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
                        <button data-dismiss="modal"  type="button" class="btn btn-danger font-weight-bold text-light">
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
                    <input type="hidden" name="sheet_id" id="remove_sheet_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-2">
                                {{trans('common.confirm_remove_sheet_label')}}:<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_sheet"></div> ?
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal">
                            <span class="mr-1"><i class="fa fa-trash"></i></span>
                            {{trans('common.yes_label')}}
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{trans('common.no_label')}}</button>
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
        const addModal = $('#addModal')
        const removeModal = $('#removeModal')
        const editModal = $('#editModal')
        const addForm = $('#addForm')
        const editForm = $('#editForm')
        const removeForm = $('#removeForm')
        const dataTable =  $('#datatable').DataTable({
            processing: true,
            language: datatableTrans,
            autoWidth:false,
            serverSide: true,
            lengthMenu: [10, 25, 50, 75, 100 ],
            pageLength:10,
            ajax: '{!! route('workerAttendance.index') !!}',
            columns: [
                {data: 'id', name: 'id',searchable: false},
                {data: 'name', name: 'name'},
                {data: 'date', name: 'date'},
                {data: 'group', name: 'group'},
                {data: 'members_present', name: 'members_present',searchable: false},
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ]
        });
        $(document).ready(function(){
            $('#addButton').on('click', function(event){
                event.preventDefault()
                $('#add_date').daterangepicker({
                    singleDatePicker:true,
                    autoUpdateInput: true,
                    showDropdowns: true,
                    minYear: 1901,
                    locale:datePickerTran,
                    applyButtonClasses:'btn btn-teal btn-sm',
                    cancelButtonClasses:'btn btn-danger btn-sm'
                })
                $('#add_group_id').select2({
                    theme: 'bootstrap4',
                    ajax: {
                        url: '{!! route('work-groups.list') !!}',
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
                        placeholder: 'Search Workgroup',
                        processResults: function(data,params){
                            params.page = params.page || 1;
                            console.log(params)
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
            })
            removeForm.submit(function($event) {
                $event.preventDefault()
                let data = $(this).serialize()
                console.log(data);
                $.ajax({
                    url: '{!! route('workerAttendance.destroy') !!}',
                    method: 'delete',
                    data: data,
                    success: function (data) {
                        console.log(data)
                    },
                    error: function (error) {
                        console.log(error)
                    },
                    complete: function (xhr, data) {
                        const {status, responseJSON} = xhr
                        if (status === 200) {
                            let message = responseJSON.message
                            $('#removeModal').modal('hide')
                            dataTable.ajax.reload()
                            toastr.warning(message, '{{trans('common.success_label')}}')
                        }
                    }
                })
            })

            $(".modal").on("hidden.bs.modal", function() {
                console.log('cleanup called')
                clearForm('addForm',false)
                $('#member_image').attr('src','{!! asset('storage/placeholder-male.jpg') !!}')
            });
        });

        function submitAddForm(){
            addForm.validate({
                rules:{
                    group_id:{
                        required:true,
                        min:1,
                    },
                    date: {
                        required:true,
                        date: true
                    },
                    name: {
                        required:true,
                    }
                },
                messages:{
                    group_id: {
                        required: '{!! trans('custom_validation.required_field') !!}'
                    },
                    date: {
                        required: '{!! trans('custom_validation.required_field') !!}'
                    },
                    name: {
                        required: '{!! trans('custom_validation.required_field') !!}'
                    }
                },
                errorPlacement: function(error, element){
                    switch (element.attr('name')) {
                        case 'group_id':
                            $('#groupError').html(error)
                            break;
                        case 'name':
                            $('#nameError').html(error)
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
                let data = addForm.serialize()
                $.ajax({
                    url: ' {!! route('workerAttendance.store') !!}',
                    method: 'post',
                    data: data,
                    complete: function ({status,responseJSON}) {
                        if(status === 200){
                            let {message} = responseJSON
                            $('#addModal').modal('hide')
                            dataTable.ajax.reload()
                            toastr.success(message, '{{trans('common.success_label')}}')
                        }
                    }
                })
            }
        }

        function submitEditForm(){
            editForm.validate({
                rules:{
                    group_id:{
                        required:true,
                        min:1,
                    },
                    date: {
                        required:true,
                        date: true
                    },
                    name: {
                        required:true,
                    }
                },
                messages:{
                    group_id: {
                        required: '{!! trans('custom_validation.required_field') !!}'
                    },
                    date: {
                        required: '{!! trans('custom_validation.required_field') !!}'
                    },
                    name: {
                        required: '{!! trans('custom_validation.required_field') !!}'
                    }
                },
                errorPlacement: function(error, element){
                    switch (element.attr('name')) {
                        case 'group_id':
                            $('#groupError').html(error)
                            break;
                        case 'name':
                            $('#nameError').html(error)
                            break;
                        case 'date':
                            $('#dateError').html(error)
                            break;
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(editForm.valid()){
                let data = editForm.serialize()
                $.ajax({
                    url: ' {!! route('workerAttendance.edit') !!}',
                    method: 'patch',
                    data: data,
                    complete: function ({status,responseJSON}) {
                        if(status === 200){
                            let {message} = responseJSON
                            editModal.modal('hide')
                            dataTable.ajax.reload()
                            toastr.success(message, '{{trans('common.success_label')}}')
                        }
                    }
                })
            }
        }


        function removeSheet($event){
            $event.preventDefault()
            const sheetId = $event.target.getAttribute('data-id')
            const name = $event.target.getAttribute('data-name')
            console.log(name)
            $('#remove_sheet_id').val(sheetId)
            $('#confirm_sheet').html(name)
            removeModal.modal('show')
        }

        function editSheet($event){
            const id = $event.target.getAttribute('data-id')
            $('#edit_sheet_id').val(id)
            $.ajax({
                url:'{!! route('workerAttendance.getSheetById') !!}',
                method:'post',
                data: {
                    _token:'{!! csrf_token() !!}',
                    id: id
                },
                complete: function ({status, responseJSON}) {
                    if(status === 201){
                        const {sheet} = responseJSON
                        editModal.modal('show')
                        $('#edit_name').val(sheet.name)
                        setupEditGroupId(sheet.group_id)
                        $('#edit_date').val(sheet.date)
                    }
                }
            })
        }

        function setupEditGroupId(groupId){
            const editGroupId = $('#edit_group_id').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('work-groups.list') !!}',
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
                    placeholder: 'Search Workgroup',
                    processResults: function(data,params){
                        params.page = params.page || 1;
                        console.log(params)
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
            $.ajax({
                type: 'POST',
                url: '{!! route('work-groups.getById') !!}',
                data: {
                    _token: '{!! csrf_token() !!}',
                    group_id: groupId
                }
            }).then(function(data){
                const {group} = data
                let option = new Option(group.name,group.id,true, true)
                editGroupId.append(option).trigger('change')
                editGroupId.trigger({
                    type: 'select2:select',
                    params: {
                        data: data
                    }
                });
                editModal.modal('show')
            });
        }
    </script>
@endsection
