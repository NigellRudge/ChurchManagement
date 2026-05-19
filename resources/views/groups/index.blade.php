@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="mb-2 card-header bg-white d-flex flex-row justify-content-between">
                    <div class="font-weight-bold text-dark text-lg">
                        {{trans('common.work_groups_label')}}
                    </div>
                    <div class="">
                        <button onclick="openAddModal(event)" class="btn btn-teal font-weight-bold text-white">
                            {{trans('common.add_work_group_label')}}
                            <i class="ml-1 fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-bordered display compact nowrap">
                        <thead>
                        <tr>
                            <th style="width: 50px;">Id</th>
                            <th>{{trans('common.name_label')}}</th>
                            <th>
                                <span class="mr-1"><i class="fa fa-user text-teal"></i></span>
                                {{trans('common.coordinator_label')}}
                            </th>
                            <th>
                                <span class="mr-1"><i class="fa fa-user-tie text-teal"></i></span>
                                {{trans('common.pastor_label')}}
                            </th>
                            <th>{{trans('common.num_workers_label')}}</th>
                            <th style="width: 110px"></th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light font-weight-bold">
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.add_work_group_label')}}</h5>
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
                                    <label for="name" class="font-weight-bold">{{trans('common.name_label')}}<span class="text-danger">*</span></label>
                                    <input id="name" name="name" class="form-control" type="text" />
                                    <div id="nameError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="coordinator_id" class="font-weight-bold">{{trans('common.coordinator_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        </div>
                                        <select name="coordinator_id" data-placeholder="{{trans('common.search_coordinator_label')}}"   id="coordinator_id" type="text" class="form-control">
                                        </select>
                                    </div>
                                    <div id="coordinatorError" ></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="pastor_id" class="font-weight-bold">{{trans('common.pastor_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                        </div>
                                        <select name="pastor_id" data-placeholder="{{trans('common.search_pastor_label')}}"    id="pastor_id" type="text" class="form-control">
                                        </select>
                                    </div>
                                    <div id="pastorError"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button onclick="submitAddForm()" class="btn btn-teal">
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
                <form method="post" action="#" id="removeForm">
                    @csrf
                    <input type="hidden" name="remove_work_group_id" id="remove_work_group_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-1">
                                {{trans('common.confirm_remove_group_label')}}<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_work_group"></div> ?
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
                <div class="modal-header bg-teal text-light font-weight-bold">
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.edit_work_group_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class=" mt-2 pl-3 pr-3">
                    <form method="post" action="#" id="editForm">
                        @csrf
                        <input type="hidden" id="edit_group" name="edit_group">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_name" class="font-weight-bold">{{trans('common.name_label')}}<span class="text-danger">*</span></label>
                                    <input id="edit_name" name="edit_name" class="form-control" type="text" />
                                    <div id="editNameError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_coordinator_id" class="font-weight-bold">{{trans('common.coordinator_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        </div>
                                        <select name="edit_coordinator_id"   id="edit_coordinator_id" type="text" class="form-control">
                                        </select>
                                    </div>
                                    <div id="editCoordinatorError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_pastor_id" class="font-weight-bold">{{trans('common.pastor_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                        </div>
                                        <select name="edit_pastor_id"   id="edit_pastor_id" type="text" class="form-control">
                                        </select>
                                    </div>
                                    <div id="editPastorError" class="customError"></div>
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
    <script>
        const editModal = $('#editModal')
        const addModal = $('#addModal')
        const removeModal = $('#removeModal')

        const addForm = $('#addForm')
        const editForm = $('#editForm')
        const deleteForm = $('#removeForm')

        const dataTable = $("#datatable").DataTable({
            processing: true,
            language: datatableTrans,
            autoWidth:false,
            serverSide: true,
            lengthMenu: [10, 25, 50, 75, 100 ],
            pageLength:10,
            ajax: '{!! route('work-groups.index') !!}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                {data: 'pastor',name: 'pastor'},
                {data: 'coordinator',name: 'coordinator'},
                {data: 'active_members',name: 'active_members'},
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ]
        });
        $(document).ready(function(){

            deleteForm.submit(function($event) {
                $event.preventDefault()
                let data = deleteForm.serialize()
                $.ajax({
                    url: '{!! route('work-groups.destroy') !!}',
                    method: 'post',
                    data: data,
                    success: function (data) {
                        console.log(data)
                    },
                    error: function (error) {
                        console.log(error)
                    },
                    complete: function ({status, responseJSON}) {
                        if (status === 201) {
                            let {message} = responseJSON
                            removeModal.modal('hide')
                            dataTable.ajax.reload()
                            toastr.warning(message,'Success')
                        }
                    }
                })
            })
            $(".modal").on("hidden.bs.modal", function() {
                clearForm('addForm',false)
                clearForm('editForm',false)
            });
        });

        function submitAddForm() {
            addForm.validate({
                rules:{
                    name:{
                        required:true,
                        minlength:6,
                    },
                    pastor_id: {
                        required:true,
                    },
                    coordinator_id: {
                        required:true,
                    }
                },
                messages:{
                    name: {
                        required: '{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 6]) !!}'
                    },
                    pastor_id: {
                        required: '{!! trans('custom_validation.required_field') !!}',
                    },
                    coordinator_id: {
                        required: '{!! trans('custom_validation.required_field') !!}',
                    },
                },
                errorPlacement: function(error, element){
                    switch (element.attr('name')) {
                        case 'name':
                            error.appendTo('#nameError')
                            break;
                        case 'coordinator_id':
                            error.appendTo('#coordinatorError')
                            break;
                        case 'pastor_id':
                            error.appendTo('#pastorError')
                            break;
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(addForm.valid()){
                let data = addForm.serialize()
                console.log(data)
                $.ajax({
                    url: ' {!! route('work-groups.store') !!}',
                    method: 'post',
                    data: data,
                    complete: function ({status,responseJSON}) {
                        if(status === 201){
                            let {message} = responseJSON
                            addModal.modal('toggle')
                            dataTable.ajax.reload()
                            toastr.success(message,'{{trans('common.success_label')}}')
                        }
                        if(status === 422){
                            let {errors} = responseJSON
                            console.log(errors)
                            if(errors.code !== null && errors.code !== undefined){
                                $('#codeError').html(errors.code)
                                $("input[name='code']").addClass('is-invalid')
                            }
                            if(errors.name !== null && errors.name!== undefined){
                                $('#nameError').html(errors.name)
                                $("input[name='name']").addClass('is-invalid')
                            }
                        }
                    }
                })

            }

        }

        function submitEditForm(){
            editForm.validate({
                rules:{
                    edit_name:{
                        required:true,
                        minlength:4,
                    },
                    edit_pastor_id: {
                        required:true,
                    },
                    edit_coordinator_id: {
                        required:true,
                    }
                },
                messages:{
                    edit_name: {
                        required: '{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 4]) !!}'
                    },
                    edit_pastor_id: {
                        required: '{!! trans('custom_validation.required_field') !!}',
                    },
                    edit_coordinator_id: {
                        required: '{!! trans('custom_validation.required_field') !!}',
                    },
                },
                errorPlacement: function(error, element){
                    switch (element.attr('name')) {
                        case 'edit_name':
                            error.appendTo('#editNameError')
                            break;
                        case 'edit_coordinator_id':
                            error.appendTo('#editCoordinatorError')
                            break;
                        case 'edit_pastor_id':
                            error.appendTo('#editPastorError')
                            break;
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(editForm.valid()){
                let data = editForm.serialize()
                $.ajax({
                    url: ' {!! route('work-groups.update') !!}',
                    method: 'post',
                    data: data,
                    complete: function ({status, responseJSON}) {
                        if(status === 201){
                            let {message} = responseJSON
                            editModal.modal('hide')
                            dataTable.ajax.reload()
                            toastr.success(message,'{{trans('common.success_label')}}')
                        }
                        if(status === 422){
                            let {errors} = responseJSON
                            console.log(errors)
                        }
                    }
                })

            }
        }

        function openAddModal($event){
            $event.preventDefault()
            $('#pastor_id').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('members.pastors') !!}',
                    type: 'post',
                    data: function(params){
                        return {
                            _token: '{!! csrf_token() !!}',
                            member_type_id:3,
                            name:params.term
                        }
                    },
                    dataType: 'json',
                    cache:true,
                    delay:200,
                    allowClear:true,
                    placeholder: '{{trans('common.search_pastor_label')}}',
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

            $('#coordinator_id').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('members.json') !!}',
                    type: 'post',
                    data: function(params){
                        return {
                            _token: '{!! csrf_token() !!}',
                            member_type_id:1,
                            name:params.term
                        }
                    },
                    dataType: 'json',
                    cache:true,
                    delay:200,
                    placeholder: '{{trans('common.search_coordinator_label')}}',
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
            addModal.modal('show')
        }

        function openRemoveModal($event){
            $event.preventDefault()
            let workGroupName = $event.target.getAttribute('data-name')
            let workGroupId = $event.target.getAttribute('data-id')
            $('#confirm_work_group').html(workGroupName)
            $("input[name='remove_work_group_id']").val(workGroupId)
            removeModal.modal('show')
        }

        function openEditModal($event){
            $event.preventDefault()
            let editName = $("#edit_name")
            let groupId = $event.target.getAttribute('data-id')
            $('#edit_group').val(groupId)
            $.ajax({
                url: '{!! route('work-groups.getById') !!}',
                method:'post',
                data:{
                    "_token": '{!! csrf_token() !!}',
                    "group_id": groupId
                },
                complete: function({status,responseJSON }){
                    let {group} = responseJSON
                    if(status === 201){
                        editName.val(group.name)
                        setupEditCoordinator(group.coordinator_id)
                        setupEditPastor(group.pastor_id)
                    }
                    editModal.modal('show')
                }
            })
        }

        function setupEditCoordinator(memberId){
            let memberSelect = $('#edit_coordinator_id').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('members.json') !!}',
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
                    placeholder: 'Search Member',
                    processResults: function(data){
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
                url: '{!! route('members.getByIdJson') !!}',
                data: {
                    _token: '{!! csrf_token() !!}',
                    id:memberId
                }
            }).then(function(data){
                let {member} = data
                let option = new Option(member.name,member.id,true, true)
                memberSelect.append(option).trigger('change')
                memberSelect.trigger({
                    type: 'select2:select',
                    params: {
                        data: data
                    }
                });
            });
        }

        function setupEditPastor(memberId){
            let pastorSelect = $('#edit_pastor_id').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('members.json') !!}',
                    type: 'post',
                    data: function(params){
                        return {
                            _token: '{!! csrf_token() !!}',
                            member_type_id:3,
                            name:params.term
                        }
                    },
                    dataType: 'json',
                    cache:true,
                    delay:200,
                    placeholder: 'Search Member',
                    processResults: function(data){
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
                url: '{!! route('members.getByIdJson') !!}',
                data: {
                    _token: '{!! csrf_token() !!}',
                    id:memberId
                }
            }).then(function(data){
                let {member} = data
                let option = new Option(member.name,member.id,true, true)
                pastorSelect.append(option).trigger('change')
                pastorSelect.trigger({
                    type: 'select2:select',
                    params: {
                        data: data
                    }
                });
            });
        }
    </script>
@endsection
