@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between">
                    <div class="font-weight-bold pt-1">
                        <span class="font-weight-bold text-lg text-dark">{{trans('common.users_label')}}</span>
                    </div>
                    <a class="btn btn-teal font-weight-bold pt-2" href="#" onclick="addUser(event)">
                        {{trans('common.add_user_label')}}
                        <i class="ml-1 fas fa-plus"></i>
                    </a>
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-bordered display compact nowrap">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>{{trans('common.user_name_label')}}</th>
                            <th>
                                <span class="mr-1 text-teal">@</span>
                                {{trans('common.email_label')}}
                            </th>
                            <th>
                                <i class="fa fa-cog mr-1 text-teal"></i>
                                {{trans('common.user_type')}}
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

    <div class="modal" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="exampleModalLabel">{{trans('common.confirm_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="remove_form">
                    @csrf
                    <input type="hidden" name="user_id" id="remove_user_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="">
                                {{trans('common.confirm_remove_user_label')}}:<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_user"></div> ?
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
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.add_user_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="addForm">
                        @csrf
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="user_name" class="font-weight-bold">{{trans('common.user_name_label')}}<span class="text-danger">*</span></label>
                                    <input id="user_name" name="user_name" class="form-control" type="text" />
                                    <div id="userNameError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="email" class="font-weight-bold">{{trans('common.email_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-at"></i>
                                            </div>
                                        </div>
                                        <input name="email"  id="email" type="email" class="form-control" />
                                    </div>
                                    <div id="emailError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col">
                                <div class="form-group">
                                    <label for="password" class="font-weight-bold">{{trans('common.password_label')}}<span class="text-danger">*</span></label>
                                    <input id="password" name="password" class="form-control" type="password"  />
                                    <div id="passwordError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col">
                                <div class="form-group">
                                    <label for="confirm_password" class="font-weight-bold">{{trans('common.confirm_password')}}<span class="text-danger">*</span></label>
                                    <input id="confirm_password" name="confirm_password" class="form-control" type="password"  />
                                    <div id="confirmError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col-3 d-flex flex-column justify-content-center">
                                <label for="add_is_admin" class="font-weight-normal text-dark">Administrator</label>
                            </div>
                            <div class="col">
                                <select id="add_is_admin" name="is_admin" type="text" class="form-control">
                                    <option value="">{{trans('common.select_option')}}</option>
                                    <option value="0">{{trans('common.no_label')}}</option>
                                    <option value="1">{{trans('common.yes_label')}}</option>
                                </select>
                                <div id="adminError" class="customError"></div>
                            </div>
                        </div>
                        <div class="form-row mt-3">
                            <div class="col">
                                <label for="add_modules">{{trans('common.roles')}}</label>
                                <select name="modules[]" id="add_modules" multiple="multiple" class="form-control">
                                    @foreach($data['modules']  as $module)
                                        <option value="{{$module->id}}"> {{$module->name}}</option>
                                    @endforeach
                                </select>
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

    <div class="modal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="editModalLabel">{{trans('common.edit_user_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="#" id="editForm">
                        @csrf
                        <input type="hidden" name="user_id" id="edit_user_id">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_user_name" class="font-weight-bold">{{trans('common.user_name_label')}}<span class="text-danger">*</span></label>
                                    <input id="edit_user_name" name="user_name" class="form-control" type="text" />
                                    <div id="editUserNameError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_email" class="font-weight-bold">{{trans('common.email_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-at"></i>
                                            </div>
                                        </div>
                                        <input name="email"  id="edit_email" type="email" class="form-control" />
                                    </div>
                                    <div id="editEmailError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col-3 d-flex flex-column justify-content-center">
                                <label for="edit_is_admin" class="font-weight-normal text-dark">Administrator</label>
                            </div>
                            <div class="col">
                                <select id="edit_is_admin" name="is_admin" type="text" class="form-control">
                                    <option value="0">{{trans('common.no_label')}}</option>
                                    <option value="1">{{trans('common.yes_label')}}</option>
                                </select>
                                <div id="editAdminError" class="customError"></div>
                            </div>
                        </div>
                        <div class="form-row mt-3">
                            <div class="col">
                                <label for="edit_modules">Roles</label>
                                <select name="modules[]" id="edit_modules" multiple="multiple" class="form-control">
                                    @foreach($data['modules']  as $module)
                                        <option value="{{$module->id}}"> {{$module->name}}</option>
                                    @endforeach
                                </select>
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

    <div class="modal" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="changePasswordModalLabel">{{trans('common.change_password_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post"  id="change_password_form">
                    @csrf
                    <input type="hidden" id="change_user_id" name="user_id">
                    <div class=" mt-2 pl-3 pr-3">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="old_password" class="font-weight-bold">{{trans('common.old_password_label')}}<span class="text-danger">*</span></label>
                                    <input id="old_password" name="old_password" class="form-control" type="password" />
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="new_password" class="font-weight-bold">{{trans('common.new_password_label')}}<span class="text-danger">*</span></label>
                                    <input id="new_password" name="new_password" class="form-control" type="password" disabled />
                                    <span class="font-weight-normal" id="toggle_password"><i class="fas fa-eye mr-1" id="toggle_password_icon"></i><small class="font-weight-bold">show</small></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="confirm_new_password" class="font-weight-bold">{{trans('common.confirm_new_password_label')}}<span class="text-danger">*</span></label>
                                    <input id="confirm_new_password" name="confirm_password" class="form-control" type="password" disabled />
                                    <span class="font-weight-normal" id="toggle_confirm"><i class="fas fa-eye mr-1" id="toggle_confirm_icon" ></i><small class="font-weight-bold">show</small></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal"  id="submitBtn" disabled>
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
        const addModal = $('#addModal')
        const editModal = $('#editModal')
        const removeModal = $('#removeModal')
        const changePasswordModal = $('#changePasswordModal')

        const addForm = $('#addForm')
        const editForm = $('#editForm')
        const deleteForm = $('#remove_form')
        const changePasswordForm = $('#change_password_form')

        const dataTable = $("#datatable").DataTable({
            processing: true,
            serverSide: true,
            lengthMenu: [5, 10, 25, 50, 75, 100 ],
            pageLength:5,
            ajax: {
                url:'{!! route('users.index') !!}',
                data: function(d){

                },
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                {data: 'email',name: 'email'},
                {data: 'user_info',name: 'user_info'},
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ]
        });

        $(document).ready(function(){

            deleteForm.submit(function($event) {
                $event.preventDefault()
                const data = $(this).serialize()
                $.ajax({
                    url: '{!! route('users.destroy') !!}',
                    method: 'delete',
                    data: data,
                    complete: function ({status,responseJSON }) {
                        if (status === 201) {
                            let {message} = responseJSON
                            $('#removeModal').modal('hide')
                            dataTable.ajax.reload()
                            toastr.warning(message, '{{trans('common.success_label')}}')
                        }
                    }
                })
            })

            changePasswordForm.validate({
                rules: {
                    password: {
                        required:true,
                        minlength:5
                    },
                    new_password:{
                        minlength:5
                    },
                    confirm_password: {
                        equalTo:'#new_password',
                        minlength:5
                    }
                },
                message: {
                    password: 'Please enter old password',
                    new_password: 'Enter a new password',
                    confirm_password: 'confirm password not equal to password'
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            changePasswordForm.submit(function ($event) {
                $event.preventDefault()
                let data = $(this).serialize()
                $.ajax({
                    url: '{!! route('users.changePassword') !!}',
                    data: data,
                    method:'patch',
                    complete: function({status ,responseJSON }){
                        if(status === 201){
                            let {message} = responseJSON
                            $('#changePasswordModal').modal('toggle')
                            dataTable.ajax.reload()
                            toastr.success(message, '{!! trans('common.success_label') !!}')
                        }
                        if(status === 401) {
                            console.error("this is an error")
                        }
                    }
                })
            })

            $(".modal").on("hidden.bs.modal", function() {
                clearForm('addForm',false)
                clearForm('editForm',false)
                $('#add_modules option:selected').prop("selected", false)
                $('#edit_modules option:selected').prop("selected", false)
            });

        });

        function submitAddForm(){
            addForm.validate({
                rules:{
                    user_name:{
                        required:true,
                        minlength:4,
                    },
                    email: {
                        required:true,
                        email: true
                    },
                    password: {
                        required:true,
                        minlength: 5
                    },
                    confirm_password: {
                        minlength: 5,
                        required:true,
                        equalTo:'#password'
                    },
                    is_admin : {
                        required:true
                    }
                },
                messages:{
                    user_name: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        minlength:'{{ trans('custom_validation.min_length',['min' => 4]) }}',
                    },
                    email: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        email: true
                    },
                    password: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        minlength: '{{ trans('custom_validation.min_length',['min' => 5]) }}',
                    },
                    confirm_password: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        minlength: '{{ trans('custom_validation.min_length',['min' => 5]) }}',
                        equalTo:'{!! trans('custom_validation.match_password') !!}'
                    },
                    is_admin : {
                        required: '{{trans('common.select_option')}}'
                    }
                },
                errorPlacement: function(error, element){
                    switch(element.attr('name')){
                        case 'user_name':
                            $('#userNameError').html(error)
                            break
                        case 'email':
                            $('#emailError').html(error)
                            break
                        case 'password':
                            $('#passwordError').html(error)
                            break
                        case 'confirm_password':
                            $('#confirmError').html(error)
                            break
                        case 'is_admin':
                            $('#adminError').html(error)
                            break

                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(addForm.valid()){
                const data = addForm.serialize()
                $.ajax({
                    url: ' {!! route('users.store') !!}',
                    method: 'post',
                    data: data,
                    complete: function ({status,responseJSON }) {
                        if(status === 201){
                            let {message} = responseJSON
                            $('#addModal').modal('toggle')
                            dataTable.ajax.reload()
                            toastr.success(message, '{{ trans('common.success_label') }}');
                        }
                    }
                })
            }
        }

        function submitEditForm(){
            editForm.validate({
                rules:{
                    user_name:{
                        required:true,
                        minlength:4,
                    },
                    email: {
                        required:true,
                        email: true
                    },
                    is_admin : {
                        required:true
                    }
                },
                messages:{
                    user_name: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        minlength:'{{ trans('custom_validation.min_length',['min' => 4]) }}',
                    },
                    email: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        email: true
                    },
                    is_admin : {
                        required: '{{trans('common.select_option')}}'
                    }
                },
                errorPlacement: function(error, element){
                    switch(element.attr('name')){
                        case 'user_name':
                            $('#editUserNameError').html(error)
                            break
                        case 'email':
                            $('#editEmailError').html(error)
                            break
                        case 'is_admin':
                            $('#editAdminError').html(error)
                            break

                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(editForm.valid()){
                const data = editForm.serialize()
                $.ajax({
                    url: ' {!! route('users.update') !!}',
                    method: 'patch',
                    data: data,
                    complete: function ({status,responseJSON}) {
                        if(status === 201){
                            let {message} = responseJSON
                            $('#editModal').modal('toggle')
                            dataTable.ajax.reload()
                            toastr.info(message, '{!! trans('common.success_label') !!}');
                        }
                    }
                })
            }
        }

        function submitChangeForm(){

        }

        function addUser($event){
            $event.preventDefault()
            const modules = $('#add_modules')
            modules.select2({})
            $('#add_is_admin').on('change', function(){
                let value = $(this).val()
                if(parseInt(value) === 0){
                    $('#add_modules option:selected').prop("selected", false)
                    modules.attr('disabled',false)

                }
                if(parseInt(value) === 1){
                    modules.attr('disabled',true)
                }
            })
            addModal.modal('show')

        }

        function deleteUser($event){
            $event.preventDefault()
            let userName = $event.target.getAttribute('data-name')
            let id = $event.target.getAttribute('data-id')
            console.log([userName,id])
            $('#confirm_user').html( `${userName} `);
            $('#remove_user_id').val(id);
            removeModal.modal('show')
        }

        function editUser($event){
            const id = $event.target.getAttribute('data-id')
            $.ajax({
                url:'{!! route('users.getById') !!}',
                method:'post',
                data: {
                    _token: '{!! csrf_token() !!}',
                    user_id:id
                },
                complete: function({status, responseJSON}){
                    if(status === 201){
                        console.log(responseJSON)
                        const {user, modules} = responseJSON;
                        $('#edit_user_id').val(id)
                        $('#edit_user_name').val(user.name)
                        $('#edit_email').val(user.email)
                        $('#edit_is_admin').val(user.is_admin)
                        for(let item of modules){
                            $(`#edit_modules option[value=${item.module_id}]`).prop('selected', true);
                        }
                        $('#edit_modules').select2()
                        editModal.modal('show')
                    }
                }
            })
        }

        function changePassword($event){
            $event.preventDefault();
            let submitBtn = $('#submitBtn')
            let oldPassword = $('#old_password')
            let newPassword = $('#new_password')
            let passwordToggle = $('#toggle_password')
            let confirmToggle = $('#toggle_confirm')
            let confirmPassword = $('#confirm_new_password')
            let userId = $('#change_user_id')
            let id = $event.target.getAttribute('data-id');
            userId.val(id)
            oldPassword.on('change',function($event){
                $event.preventDefault();
                newPassword.attr('disabled',false)
            })
            newPassword.on('change',function($event){
                $event.preventDefault();
                confirmPassword.attr('disabled',false)
            })
            confirmPassword.on('change',function($event){
                $event.preventDefault();
                submitBtn.attr('disabled',false)
            })
            passwordToggle.on('click',function ($event) {
                $event.preventDefault();
                let icon = $('#toggle_password_icon')
                console.log('click')
                icon.removeClass('fa fa-eye')
                icon.addClass('fa fa-eye-slash')
                if (newPassword.attr("type") === "password") {
                    newPassword.attr("type", "text");
                } else {
                    newPassword.attr("type", "password");
                    icon.addClass('fa fa-eye')
                    icon.removeClass('fa fa-eye-slash')
                }
            })
            confirmToggle.on('click',function ($event) {
                $event.preventDefault();
                let icon = $('#toggle_confirm_icon')
                console.log('click')
                icon.removeClass('fa fa-eye')
                icon.addClass('fa fa-eye-slash')
                if (confirmPassword.attr("type") === "password") {
                    confirmPassword.attr("type", "text");
                } else {
                    confirmPassword.attr("type", "password");
                    icon.addClass('fa fa-eye')
                    icon.removeClass('fa fa-eye-slash')
                }
            })
            console.log(`user id: ${id}`)
            changePasswordModal.modal('show')

        }
    </script>
@endsection
