@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-white d-flex flex-row mb-2 justify-content-between">
                    <div class="text-lg font-weight-bold text-dark pt-1">
                        {{trans('common.name_label')}}: {{$data['sheet']['name']}}
                    </div>
                    <div class="d-flex flex-row">
                        <form action="{{ route('visitors.exportSheet') }}" method="post">
                            @csrf
                            <input type="hidden" id="sheet_id" name="sheet_id" value="{{$data['sheet']['id']}}">
                            <button class="mr-2 pt-2 pb-2 btn btn-primary text-light font-weight-bold" disabled id="exportBtn" type="submit">
                                {{trans('common.export_to_excel_label')}}
                                <i class="ml-1 fas fa-file-excel"></i>
                            </button>
                        </form>
                        <button onclick="openAddModal(event)" class="btn btn-teal font-weight-bold">
                            {{trans('common.add_visitor_label')}}
                            <i class="ml-1 fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row pl-2 mt-2 mb-3">
                        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="filter_gender" class="col-form-label font-weight-bold">{{trans('common.filter_by_gender_label')}}</label>
                                    <select type="text" id="filter_gender" name="filter_gender" class="form-control">
                                        <option value="0">{{trans('common.all_label')}}</option>
                                        @foreach($data['genders'] as $gender)
                                            <option value="{{$gender['id']}}">{{$gender['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class=" fix-topbar">
                        <table id="datatable" class="table table-bordered display compact nowrap">
                            <thead>
                            <tr class="text-dark">
                                <th>{{trans('common.name_label')}}</th>
                                <th>{{trans('common.filter_by_gender_label')}}</th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-user text-teal"></i></span>
                                    {{trans('common.invited_by_label')}}
                                </th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-phone text-teal"></i></span>
                                    {{trans('common.phone_number_label')}}
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
                    <input type="hidden" id="sheet_id" name="sheet_id" value="{{$data['sheet']['id']}}">
                    <input type="hidden" name="remove_visitor_id" id="remove_visitor_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-2 text-dark">
                                {{trans('common.remove_member_from_sheet_label')}}:<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_visitor"></div> <span class="font-weight-bold">?</span>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">
                            <span class="mr-1"><i class="fa fa-trash"></i></span>
                            {{trans('common.yes_label')}}
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{trans('no')}}
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
                    <h5 class="modal-title font-weight-bold" id="addModalLabel">{{trans('common.add_visitor_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class=" mt-2 pt-2 pl-3 pr-3">
                    <form method="post" action="#" id="addForm">
                        @csrf
                        <input type="hidden" id="sheet_id" name="sheet_id" value="{{$data['sheet']['id']}}">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="first_name" class="text-dark font-weight-bold">{{trans('common.first_name_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                    <input type="text" name="first_name" id="first_name" class="form-control">
                                    <div id="firstNameError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="last_name" class="text-dark font-weight-bold">{{trans('common.last_name_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                    <input type="text" name="last_name" id="last_name" class="form-control" >
                                    <div id="lastNameError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="phone_number" class="text-dark font-weight-bold">{{trans('common.phone_number_label')}}</label>
                                    <input type="text" name="phone_number" id="phone_number" class="form-control">
                                    <div id="phoneError" class="customError"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="gender_id" class="font-weight-bold text-dark">{{trans('common.gender_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white ">
                                                <i class="fa fa-male text-teal" id="male-icon"></i>
                                                <i class="fa fa-female text-teal" id="female-icon" style="display: none"></i>
                                            </div>
                                        </div>
                                        <select name="gender_id" id="gender_id" data-placeholder="Select gender" class="form-control" >
                                            <option value="0">{{trans('common.select_gender_label')}}</option>
                                            @foreach($data['genders'] as $gender)
                                                <option value="{{$gender->id}}">{{$gender->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="genderError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="invited_by_id" class="text-dark font-weight-bold">{{trans('common.invited_by_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white">
                                                <i class="fa fa-user text-teal"></i>
                                            </div>
                                        </div>
                                        <select name="invited_by_id" data-placeholder="{{trans('common.select_member_label')}}"  id="invited_by_id" type="text" class="form-control" >
                                        </select>
                                    </div>
                                    <div id="invitedError" class="customError"></div>
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

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="editModalLabel">{{trans('common.edit_visitor_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class=" mt-2 pl-3 pr-3">
                    <form method="post" action="#" id="editForm">
                        @csrf
                        <input type="hidden" id="sheet_id" name="sheet_id" value="{{$data['sheet']['id']}}">
                        <input type="hidden" name="edit_visitor_id" id="edit_visitor_id">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_first_name" class="text-dark font-weight-bold">{{trans('common.first_name_label')}}<span class="text-danger">*</span></label>
                                    <input type="text" name="edit_first_name" id="edit_first_name" class="form-control">
                                    <div id="editFirstNameError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_last_name" class="text-dark font-weight-bold">{{trans('common.last_name_label')}}<span class="text-danger">*</span></label>
                                    <input type="text" name="edit_last_name" id="edit_last_name" class="form-control">
                                    <div id="editLastNameError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_phone_number" class="text-dark font-weight-bold">{{trans('common.phone_number_label')}}</label>
                                    <input type="text" name="phone_number" id="edit_phone_number" class="form-control">
                                    <div id="editPhoneError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_gender_id" class="text-dark font-weight-bold">{{trans('common.gender_label')}}<span class="text-danger">*</span></label>
                                    <select name="edit_gender_id" id="edit_gender_id" data-placeholder="Select gender" class="form-control">
                                        <option value="0">{{trans('common.select_gender_label')}}</option>
                                        @foreach($data['genders'] as $gender)
                                            <option value="{{$gender->id}}">{{$gender->name}}</option>
                                        @endforeach
                                    </select>
                                    <div id="editGenderError" class="customError"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_invited_by_id" class="text-dark font-weight-bold">{{trans('common.invited_by_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white">
                                                <i class="fa fa-user text-teal"></i>
                                            </div>
                                        </div>
                                        <select name="edit_invited_by_id" data-placeholder="Select member"  id="edit_invited_by_id" type="text" class="form-control">
                                        </select>
                                    </div>
                                    <div id="editInvitedError" class="customError"></div>
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
        let genderId = 0;
        const addForm = $('#addForm')
        const editForm = $('#editForm')
        const deleteForm = $('#removeForm')

        const addModal = $('#addModal')
        const editModal = $('#editModal')

        const dataTable = $("#datatable").DataTable({
            processing: true,
            language: datatableTrans,
            autoWidth:false,
            serverSide: true,
            lengthMenu: [10,15, 25, 50, 75, 100 ],
            pageLength:15,
            ajax: {
                url:'{!! route('visitors.sheetInfo',['sheet' => $data['sheet']['id']]) !!}',
                data: function(data){
                    data.gender_id = genderId;
                }
            },
            columns: [
                { data: 'name', name: 'name' },
                {data: 'gender',name: 'gender'},
                {data: 'invited_by',name: 'invited_by'},
                {data: 'phone_number',name: 'phone_number'},
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ],
            initComplete:function(settings,json){
                onReloadComplete(json)
            }
        });
        const filterGender = $('#filter_gender')
        $(document).ready(function(){
            filterGender.on('change',function(){
                genderId = this.value;
                dataTable.ajax.reload()
            });
            deleteForm.submit(function($event){
                $event.preventDefault()
                let data = deleteForm.serialize()
                $.ajax({
                    url: '{!! route('visitors.destroyVisitor') !!}',
                    method: 'delete',
                    data: data,
                    complete: function({status,responseJSON }){
                        if(status === 200){
                            const {message} = responseJSON
                            $('#removeModal').modal('hide')
                            dataTable.ajax.reload(onReloadComplete)
                            toastr.warning(message,'Success')
                        }
                    }
                })

            });
            $(".modal").on("hidden.bs.modal", function() {
                clearForm('addForm', false)
                clearForm('editForm',false)
            });
            $('#gender_id').on('change',function($event){
                let value = this.value
                console.log(value)
            })
        });

        function submitAddForm(){
            addForm.validate({
                rules: {
                    first_name:{
                        required:true,
                        minlength:3,
                        maxlength:50
                    },
                    last_name:{
                        required:true,
                        minlength:3,
                        maxlength:50
                    },
                    phone_number:{
                        required:true,
                    },
                    gender_id:{
                        required:true,
                        min:1
                    },
                    invited_by_id :{
                        required:true,
                        min: 1
                    }
                },
                messages: {
                    first_name: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        minlength:'{!! trans('custom_validation.min_length',['min' => 3]) !!}',
                        maxlength:'{!! trans('custom_validation.max_length',['max' => 50]) !!}',
                    },
                    last_name: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        minlength:'{!! trans('custom_validation.min_length',['min' => 3]) !!}',
                        maxlength:'{!! trans('custom_validation.max_length',['max' => 50]) !!}',
                    },
                    gender_id: {
                        required:'{!! trans('custom_validation.select_option') !!}',
                        min:'{!! trans('custom_validation.select_option') !!}',
                    },
                    invited_by_id: {
                        required:'{!! trans('custom_validation.select_member') !!}',
                        min: '{!! trans('custom_validation.select_member') !!}',
                    },
                    phone_number: {
                        required: '{!! trans('custom_validation.required_field') !!}'
                    }
                },
                errorPlacement: function(error, element){
                  switch(element.attr('name')){
                      case 'first_name':
                          $('#firstNameError').html(error)
                          break;
                      case 'last_name':
                          $('#lastNameError').html(error)
                          break;
                      case 'gender_id':
                          $('#genderError').html(error)
                          break;
                      case 'invited_by_id':
                          $('#invitedError').html(error)
                          break;
                      case 'phone_number':
                          $('#phoneError').html(error)
                          break;
                  }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(addForm.valid()){
                const data = addForm.serialize()
                $.ajax({
                    url: '{!! route('visitors.storeVisitorAjax') !!}',
                    method: 'post',
                    data: data,
                    complete: function({status,responseJSON}){
                        if(status === 200){
                            const {message} = responseJSON
                            addModal.modal('hide')
                            dataTable.ajax.reload(onReloadComplete)
                            toastr.success(message,'Success')
                        }
                    }
                })
            }
        }

        function submitEditForm(){
            editForm.validate({
                rules: {
                    edit_first_name:{
                        required:true,
                        minlength:3,
                        maxlength:50
                    },
                    edit_last_name:{
                        required:true,
                        minlength:3,
                        maxlength:50
                    },
                    phone_number:{
                        required:true,
                    },
                    edit_gender_id:{
                        required:true,
                        min:1
                    },
                    edit_invited_by_id :{
                        required:true,
                        min: 1
                    }
                },
                messages: {
                    edit_first_name: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        minlength:'{!! trans('custom_validation.min_length',['min' => 3]) !!}',
                        maxlength:'{!! trans('custom_validation.max_length',['max' => 50]) !!}',
                    },
                    edit_last_name: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        minlength:'{!! trans('custom_validation.min_length',['min' => 3]) !!}',
                        maxlength:'{!! trans('custom_validation.max_length',['max' => 50]) !!}',
                    },
                    edit_gender_id: {
                        required:'{!! trans('custom_validation.select_option') !!}',
                        min:'{!! trans('custom_validation.select_option') !!}',
                    },
                    edit_invited_by_id: {
                        required:'{!! trans('custom_validation.select_member') !!}',
                        min: '{!! trans('custom_validation.select_member') !!}',
                    },
                    phone_number: {
                        required: '{!! trans('custom_validation.required_field') !!}'
                    }
                },
                errorPlacement: function(error, element){
                    switch(element.attr('name')){
                        case 'edit_first_name':
                            $('#editFirstNameError').html(error)
                            break;
                        case 'edit_last_name':
                            $('#editLastNameError').html(error)
                            break;
                        case 'edit_gender_id':
                            $('#editGenderError').html(error)
                            break;
                        case 'edit_invited_by_id':
                            $('#editInvitedError').html(error)
                            break;
                        case 'phone_number':
                            $('#editPhoneError').html(error)
                            break;
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(editForm.valid()){
                let data = editForm.serialize()
                $.ajax({
                    url: '{!! route('visitors.updateVisitor') !!}',
                    method: 'post',
                    data: data,
                    complete: function({status, responseJSON}){
                        if(status === 201){
                            const {message} = responseJSON
                            editModal.modal('hide')
                            dataTable.ajax.reload(onReloadComplete)
                            toastr.success(message,'Success')
                        }
                    }
                })

            }
        }

        function openRemoveModal($event){
            $event.preventDefault();
            let removeModal = $('#removeModal')
            let visitorName = $event.target.getAttribute('data-name')
            let visitorId = $event.target.getAttribute('data-id')
            console.log(`visitorId: ${visitorId}`)
            $('input[name="remove_visitor_id"]').val(visitorId.toString());
            $('#confirm_visitor').html(visitorName)
            removeModal.modal('show')
        }

        function openAddModal($event){
            $event.preventDefault();
            const firstName = $('#first_name')
            const lastName = $('#last_name')
            const gender = $('#gender_id')
            const invitedBy = $('#invited_by_id').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('members.json') !!}',
                    type: 'post',
                    data: function (params) {
                        return {
                            _token: '{!! csrf_token() !!}',
                            name: params.term,
                            page: params.page || 1
                        }
                    },
                    dataType: 'json',
                    cache: true,
                    delay: 200,
                    placeholder: 'Search Member',
                    processResults: function (data, params) {
                        params.page = params.page || 1;
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
            addModal.modal('show');
            {{--firstName.on('change',function(){--}}
            {{--    lastName.attr('disabled',false)--}}
            {{--})--}}
            {{--lastName.on('change',function(){--}}
            {{--    gender.attr('disabled',false)--}}
            {{--})--}}
            {{--gender.on('change',function (event) {--}}
            {{--    let value = parseInt($(this).val())--}}
            {{--    if(value > 0){--}}
            {{--        invitedBy.attr('disabled',false)--}}
            {{--        invitedBy.select2({--}}
            {{--            theme: 'bootstrap4',--}}
            {{--            ajax: {--}}
            {{--                url: '{!! route('members.json') !!}',--}}
            {{--                type: 'post',--}}
            {{--                data: function(params){--}}
            {{--                    return {--}}
            {{--                        _token: '{!! csrf_token() !!}',--}}
            {{--                        name:params.term,--}}
            {{--                        page: params.page || 1--}}
            {{--                    }--}}
            {{--                },--}}
            {{--                dataType: 'json',--}}
            {{--                cache:true,--}}
            {{--                delay:200,--}}
            {{--                placeholder: 'Search Member',--}}
            {{--                processResults: function(data,params){--}}
            {{--                    params.page = params.page || 1;--}}
            {{--                    console.log(data)--}}
            {{--                    return {--}}
            {{--                        results: data.results,--}}
            {{--                        pagination: {--}}
            {{--                            more: (params.page * 10) < data.total_items--}}
            {{--                        }--}}
            {{--                    }--}}
            {{--                }--}}
            {{--            }--}}
            {{--        });--}}
            {{--    }--}}
            {{--    else {--}}
            {{--        invitedBy.attr('disabled',true)--}}
            {{--        invitedBy.val('')--}}
            {{--    }--}}

        }

        function openEditModal($event){
            $event.preventDefault();
            let editFirstName = $("#edit_first_name")
            let gender = $('#edit_gender_id')
            let editLastName = $("#edit_last_name")
            let visitorId = $event.target.getAttribute('data-id');
            let phoneNumber = $('#edit_phone_number')
            $("#edit_visitor_id").val(visitorId)

            $.ajax({
                url: '{!! route('visitors.getVisitorInfo') !!}',
                method:'post',
                data:{
                    _token: '{!! csrf_token() !!}',
                    edit_visitor_id: visitorId,
                    sheet_id: {!! $data['sheet']['id'] !!}
                },
                complete: function({status, responseJSON}){
                    let {visitor} = responseJSON
                    console.log(visitor)
                    if(status === 201){
                        editFirstName.val(visitor.first_name)
                        editLastName.val(visitor.last_name)
                        gender.val(visitor.gender_id)
                        if(visitor.phone_number !== null){
                            phoneNumber.val(visitor.phone_number)
                        }
                        setupEditInvitedBy(visitor.invited_by_id)
                    }
                    editModal.modal('show')
                }
            })
        }

        function setupEditGender(genderId){
            let genderSelect = $('#edit_gender_id').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('genders.Json') !!}',
                    type: 'post',
                    data: function(params){
                        return {
                            _token: '{!! csrf_token() !!}',
                            term:params.term
                        }
                    },
                    dataType: 'json',
                    cache:true,
                    delay:200,
                    placeholder: 'Search Gender',
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
                url: '{!! route('genders.getByIdJson') !!}',
                data: {
                    _token: '{!! csrf_token() !!}',
                    genderId:genderId
                }
            }).then(function(data){
                console.log('gender data')
                console.log(data)
                let gender = data.gender[0]
                let option = new Option(gender.name,gender.id,true, true)
                genderSelect.append(option).trigger('change')
                genderSelect.trigger({
                    type: 'select2:select',
                    params: {
                        data: data
                    }
                });
            });
        }

        function setupEditInvitedBy(invitedById){
            let memberSelect = $('#edit_invited_by_id').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('members.json') !!}',
                    type: 'post',
                    data: function(params){
                        return {
                            _token: '{!! csrf_token() !!}',
                            name:params.term,
                            page: params.page || 1
                        }
                    },
                    dataType: 'json',
                    cache:true,
                    delay:200,
                    placeholder: 'Search Member',
                    processResults: function(data,params){
                        params.page = params.page || 1;
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
                url: '{!! route('members.getByIdJson') !!}',
                data: {
                    _token: '{!! csrf_token() !!}',
                    id:invitedById
                }
            }).then(function(data){
                const {member} = data
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
    </script>
@endsection
