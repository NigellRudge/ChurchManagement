@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="card-header bg-white mb-2 d-flex justify-content-between">
                    <div class="">
                        <span class="font-weight-bold text-lg text-dark">{{ trans('common.eagle_groups_label') }}</span>
                    </div>
                    <div class="d-flex flex-row">
                        <form action="{{ route('eagle-group.exportOverview') }}" method="post">
                            @csrf
                            <button id="exportBtn" class="mr-2 btn btn-primary font-weight-bold text-light rounded font-weight-bold" type="submit">
                                {{trans('common.export_to_excel_label')}}
                                <i class="ml-1 fas fa-file-excel"></i>
                            </button>
                        </form>
                        <a class="mr-1 btn btn-info font-weight-bold text-light rounded font-weight-bold" href="{{route('attendance.index')}}">
                            {{trans('common.attendance_label')}}
                            <i class="ml-1 fas fa-clipboard-list"></i>
                        </a>
                        <a  onclick="openAddModal(event)" class="btn btn-teal font-weight-bold text-light rounded font-weight-bold">
                            {{trans('common.add_eagle_group_label')}}
                            <i class="ml-1 fas fa-plus"></i>
                        </a>
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
                                    <span class="mr-1"><i class="fa fa-user-tie text-teal"></i></span>
                                    {{trans('common.team_captain_label')}}
                                </th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-users text-teal"></i></span>
                                    {{trans('common.num_members_label')}}
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
    </div>

    <div class="modal fade" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="exampleModalLabel">{{trans('common.confirm_label')}}</h5>
                    <button type="button" class="close bg-teal border-none" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="removeForm">
                    @csrf
                    <input type="hidden" name="remove_group_id" id="remove_group_id" value=""/>
                    <div class="modal-body">
                        <div class="d-flex flex-row">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="">
                                {{trans('common.confirm_remove_eagle_group_label')}}:
                                <div class="d-inline text-teal font-weight-bold" id="confirm_group_name"></div> ?
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger font-weight-bold">
                            <span class="mr-1"><i class="fa fa-trash"></i></span>
                            {{trans('common.yes_label')}}
                        </button>
                        <button type="button" class="btn btn-secondary font-weight-bold">
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
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.add_eagle_group_label')}}</h5>
                    <button type="button" class="close bg-teal border-none" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="font-weight-bold text-light">&times;</span>
                    </button>
                </div>
                <div class="pr-3 pl-3 pb-2 pt-2">
                    <form method="post" action="#" id="addForm">
                        @csrf
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="name" class="text-dark font-weight-bold">{{trans('common.name_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control">
                                    <div id="nameError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="team_captain" class="text-dark font-weight-bold">{{trans('common.team_captain_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                        </div>
                                        <select name="team_captain" id="team_captain" class="form-control"></select>
                                    </div>
                                    <div id="captainError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button onclick="submitAddForm()" class="btn btn-teal font-weight-bold">
                        <i class="fas fa-save mr-1"></i>
                        {{trans('common.save_label')}}
                    </button>
                    <button type="button" class="btn btn-danger font-weight-bold" data-dismiss="modal">
                        <i class="fas fa-ban mr-1"></i>
                        {{trans('common.no_label')}}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light font-weight-bold ">
                    <h5 class="modal-title" id="editModalLabel">{{trans('common.edit_eagle_group_label')}}</h5>
                    <button type="button" class="close bg-teal border-none" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class="pr-3 pl-3 pb-2 pt-2">
                    <form method="post" action="#" id="editForm">
                        @csrf
                        <input type="hidden" name="edit_group_id" id="edit_group_id">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_name" class="text-dark">{{trans('common.name_label')}}</label>
                                    <input type="text" name="edit_name" id="edit_name" class="form-control">
                                    <div id="editNameError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_team_captain" class="text-dark">{{trans('common.team_captain_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                        </div>
                                        <select name="edit_team_captain" id="edit_team_captain" class="form-control"></select>
                                    </div>
                                    <div id="editCaptainError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button onclick="submitEditForm()" class="btn btn-teal font-weight-bold">
                        <i class="fas fa-save mr-1"></i>
                        {{trans('common.save_label')}}
                    </button>
                    <button type="button" class="btn btn-danger font-weight-bold" data-dismiss="modal">
                        <i class="fas fa-ban mr-1"></i>
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
        const addForm = $('#addForm')
        const removeModal = $('#removeModal')
        const editForm = $('#editForm')
        const deleteForm = $('#removeForm')
        const dataTable = $("#datatable").DataTable({
            processing: true,
            language: datatableTrans,
            autoWidth:false,
            serverSide: true,
            lengthMenu: [10,15, 25, 50, 75, 100 ],
            pageLength:15,
            ajax: '{!! route('eagle-group.index') !!}',
            columns: [
                {data: 'id', name: 'id',searchable: false},
                {data: 'name', name: 'name'},
                {data: 'team_captain', name: 'team_captain'},
                {data: 'num_members', name: 'num_members',searchable: false},
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ],
            initComplete: function(settings,json){
                onReloadComplete(json)
            }
        });

        $(document).ready(function(){
            deleteForm.submit(function($event){
                $event.preventDefault()
                let data = deleteForm.serialize()
                $.ajax({
                    url: '{!! route('eagle-group.destroyAjax') !!}',
                    method: 'post',
                    data:data,
                    complete: function({status,responseJSON }){
                        const {message} = responseJSON
                        dataTable.ajax.reload(onReloadComplete)
                        $('#removeModal').modal('toggle')
                        if(status === 200){
                            toastr.warning(message,'{{ trans('common.success_label') }}')
                        }
                    }
                })
            });

            $(".modal").on("hidden.bs.modal", function() {
                clearForm('addForm',false)
                clearForm('editForm',false)
            });
        });

        function submitAddForm(){
            addForm.validate({
                rules:{
                    name:{
                        required:true,
                        minlength:5,
                        maxlength:40
                    },
                    team_captain:{
                        required:true,
                        min:1,
                    }
                },
                messages:{
                    name: {
                        required: '{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 5]) !!}',
                        maxlength:'{!! trans('custom_validation.max_length',['max' => 40]) !!}',
                    },
                    team_captain: {
                        required:'{!! trans('custom_validation.select_member') !!}',
                        min:'{!! trans('custom_validation.select_member') !!}',
                    }
                },
                errorPlacement: function(error, element){
                    switch(element.attr('name')){
                        case 'name':
                            $('#nameError').html(error)
                            break;
                        case 'team_captain':
                            $('#captainError').html(error)
                            break;
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(addForm.valid()){
                let data = addForm.serialize()
                $.ajax({
                    url: ' {!! route('eagle-group.storeAjax') !!}',
                    method: 'post',
                    data: data,
                    complete: function ({status,responseJSON }) {
                        if(status === 201){
                            $('#addModal').modal('toggle')
                            const {message} = responseJSON
                            dataTable.ajax.reload(onReloadComplete)
                            toastr.success(message,'Success')
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
                        minlength:5,
                        maxlength:40
                    },
                    edit_team_captain:{
                        required:true,
                        min:1,
                    }
                },
                messages:{
                    edit_name: {
                        required: '{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 5]) !!}',
                        maxlength:'{!! trans('custom_validation.max_length',['max' => 40]) !!}',
                    },
                    edit_team_captain: {
                        required:'{!! trans('custom_validation.select_member') !!}',
                        min:'{!! trans('custom_validation.select_member') !!}',
                    }
                },
                errorPlacement: function(error, element){
                    switch(element.attr('name')){
                        case 'edit_name':
                            $('#editNameError').html(error)
                            break;
                        case 'edit_team_captain':
                            $('#editCaptainError').html(error)
                            break;
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(editForm.valid()){
                const data = editForm.serialize()
                $.ajax({
                    url: ' {!! route('eagle-group.updateAjax') !!}',
                    method: 'post',
                    data: data,
                    complete: function ({status,responseJSON}) {
                        if(status === 200){
                            $('#editModal').modal('toggle')
                            const {message} = responseJSON
                            dataTable.ajax.reload(onReloadComplete)
                            toastr.success(message,'Success')
                        }
                    }
                })
            }
        }

        function DownloadFile(){
            let excelForm = $('#excel_form')
            excelForm.submit(function($event){
                $event.preventDefault();
                let data = excelForm.serialize()
                console.log()
            })
        }

        function openRemoveModal($event){
            let groupName = $event.target.getAttribute('data-name');
            let groupId = $event.target.getAttribute('data-id');
            console.log(groupId)
            console.log('hello')
            $('#confirm_group_name').html( `${groupName}`);
            $('#remove_group_id').val(groupId.toString());
            removeModal.modal('show')
        }

        function openAddModal($event){
            $event.preventDefault()
            let addModal = $('#addModal')
            addModal.modal('show')

            $('#team_captain').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('members.json') !!}',
                    type: 'post',
                    data: function(params){
                        return {
                            _token: '{!! csrf_token() !!}',
                            member_type_id: 6,
                            name:params.term,
                        }
                    },
                    dataType: 'json',
                    cache:true,
                    delay:200,
                    placeholder: 'Search member',
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
        }

        function openEditModal($event){
            $event.preventDefault();
            let modal = $('#editModal')
            let nameInput = $("input[name='edit_name']")
            let editInput = $("input[name='edit_group_id']")
            let groupId = parseInt($event.target.getAttribute('data-id'));
            editInput.val(groupId)
            console.log(`groupid: ${groupId}`)
            let data = {
                "_token": '{!! csrf_token() !!}',
                "group_id": groupId
            }
            $.ajax({
                url: '{!! route('eagle-group.getByIdAjax') !!}',
                method:'post',
                data:data,
                complete: function({status,responseJSON }){
                    const {group} = responseJSON
                    console.log(group)
                    if(status === 200){
                        nameInput.val(group.name)
                        setupEditMember(group.team_captain_id)
                    }
                    modal.modal('show')
                }
            })
        }

        function setupEditMember(memberId){
            let memberSelect = $('#edit_team_captain').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('members.json') !!}',
                    type: 'post',
                    data: function(params){
                        return {
                            _token: '{!! csrf_token() !!}',
                            name:params.term,
                            member_type_id: 6,
                        }
                    },
                    dataType: 'json',
                    cache:true,
                    delay:200,
                    placeholder: 'Search Member',
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
            $.ajax({
                type: 'POST',
                url: '{!! route('members.getByIdJson') !!}',
                data: {
                    _token: '{!! csrf_token() !!}',
                    id:memberId
                }
            }).then(function(data){
                const {member} = data
                console.log(member)
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

        function downloadExport($event){
            $event.preventDefault();
            $.ajax({
                url: '{!! route('eagle-group.exportOverview') !!}',
                method:'post',
                data: {
                    _token: '{!! csrf_token() !!}'
                },
                complete: function (xhr) {

                }
            })
        }

    </script>
@endsection
