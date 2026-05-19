@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between">
                    <div class="card-title font-weight-bold  pt-1">
                        <span class="font-weight-bold text-lg text-dark">{{trans('common.service_club_members_label')}}</span>
                    </div>
                    <div class="d-flex flex-row">
                        <form action="{{route('service_club.export')}}" method="post">
                            @csrf
                            <input type="hidden" id="export_gender_id" name="gender_id">
                            <button class="mr-2 btn btn-primary font-weight-bold text-light rounded font-weight-bol" disabled id="exportBtn" type="submit">
                                {{trans('common.export_to_excel_label')}}
                                <i class="ml-1 fas fa-file-excel"></i>
                            </button>
                        </form>
                        <a class="btn btn-teal font-weight-bold" href="#" onclick="openAddModal(event)">
                            {{trans('common.add_member_label')}}
                            <i class="ml-1 fas fa-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row pl-3 mb-4">
                        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="filter_gender" class="col-form-label font-weight-bold">{{trans('common.filter_by_gender_label')}}</label>
                                    <select type="text" id="filter_gender" name="filter_gender" class="form-control">
                                        <option value="0">{{trans('common.all_label')}}</option>
                                        @foreach($data['genders'] as $gender)
                                            <option value="{{$gender->id}}">{{$gender->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col pt-4 d-flex flex-column justify-content-center">
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
                    <div class="">
                        <table id="datatable" class="table table-bordered display compact nowrap">
                            <thead>
                            <tr>
{{--                                <th>{{trans('common.member_image_label')}}</th>--}}
                                <th>{{trans('common.member_label')}}</th>
                                <th>{{trans('common.gender_label')}}</th>
                                <th>{{trans('common.id_number_label')}}</th>
                                <th>{{trans('common.profession_label')}}</th>
                                <th>{{trans('common.skills_label')}}</th>
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
    </div>

    <div class="modal fade" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="exampleModalLabel">{{trans('common.delete_member_label')}}</h5>
                    <button type="button" class="close bg-teal border-none" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class="row">
                    <div class="col p-4 d-flex flex-row justify-content-center">
                        {{trans('common.confirm_delete_member_label')}}
                    </div>
                </div>
                <div class="row">
                    <div class="col d-flex p-1 flex-column align-items-center">
                        <img id="remove_member_image" alt="member_image" src="{{asset('storage/placeholder-male.jpg')}}" width="120" height="180" style="object-fit: cover; border-radius: 8px">
                        <div class="mt-2 d-inline text-dark font-weight-bold" id="confirm_member"></div>
                    </div>
                </div>
                <form method="post" action="#" id="removeForm">
                    @csrf
                    <input type="hidden" name="service_member_id"  id="remove_member_id" value=""/>
                    <div class="modal-body px-4"></div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">
                            <span class="mr-1"><i class="fa fa-trash"></i></span>
                            {{trans('common.yes_label')}}
                        </button>
                        <button type="button" data-dismiss="modal" class="btn btn-secondary">{{trans('common.no_label')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.add_member_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class="row">
                    <div class="col d-flex p-1 flex-row justify-content-center">
                        <img id="add_member_image" alt="member_image" src="{{asset('storage/placeholder-male.jpg')}}" width="120" height="180" style="object-fit: cover; border-radius: 8px">
                    </div>
                </div>
                <div class=" mt-2 pl-3 pr-3">
                    <form method="post" action="#" id="addForm">
                        @csrf
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="add_member_id" class="text-dark">{{trans('common.member_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fa fa-user-alt"></i>
                                            </div>
                                        </div>
                                        <select name="member_id" data-placeholder="{{trans('common.select_member_label')}}"  id="add_member_id" type="text" class="form-control">
                                        </select>
                                    </div>
                                    <small id="memberError"></small>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-1">
                            <div class="col">
                                <div class="form-group">
                                    <label for="add_profession" class="text-dark">{{trans('common.profession_label')}}</label>
                                    <input type="text" id="add_profession" placeholder="{{trans('common.profession_placeholder')}}" name="profession" class="form-control">
                                    <small id="professionError"></small>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-1">
                            <div class="col">
                                <label for="add_skills" class="text-dark">{{trans('common.skills_label')}}</label>
                                <textarea name="skills" id="add_skills"  rows="3" class="form-control"></textarea>
                                <small id="skillsError"></small>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="add_business_owner" class="text-dark">{{trans('common.business_owner_label')}}</label>
                                    <select id="add_business_owner" name="business_owner" class="form-control">
                                        <option value="3">{{trans('common.select_option')}}</option>
                                        <option value="1">{{trans('common.yes_label')}}</option>
                                        <option value="0">{{trans('common.no_label')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="add_business_name" class="text-dark">{{trans('common.business_name_label')}}</label>
                                    <input disabled  id="add_business_name" name="business_name" class="form-control" type="text" />
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="add_sectors" class="text-dark font-weight-normal">{{trans('common.business_sectors_active')}}</label>
                                    <select disabled name="sectors[]" id="add_sectors" data-placeholder="Select currency" class="form-control">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button onclick="submitAddForm()" class="btn btn-teal" id="addSubmitBtn">
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
                    <h5 class="modal-title" id="editModalLabel">{{trans('common.edit_member_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class="row">
                    <div class="col d-flex p-1 flex-row justify-content-center">
                        <img id="edit_member_image" alt="member_image" src="{{asset('storage/placeholder-male.jpg')}}" width="120" height="180" style="object-fit: cover; border-radius: 8px">
                    </div>
                </div>
                <div class=" mt-2 pl-3 pr-3">
                    <form method="post" action="#" id="editForm">
                        @csrf
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_member_id" class="text-dark">{{trans('common.member_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fa fa-user-alt"></i>
                                            </div>
                                        </div>
                                        <select disabled name="member_id" data-placeholder="{{trans('common.select_member_label')}}"  id="edit_member_id" type="text" class="form-control">
                                        </select>
                                    </div>
{{--                                    <div id="memberError" class="customError"></div>--}}
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-1">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_profession" class="text-dark">{{trans('common.profession_label')}}</label>
                                    <input type="text" id="edit_profession" placeholder="{{trans('common.profession_placeholder')}}" name="profession" class="form-control">
                                    <div id="editProfessionError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-1">
                            <div class="col">
                                <label for="edit_skills" class="text-dark">{{trans('common.skills_label')}}</label>
                                <textarea name="skills" id="edit_skills"  rows="3" class="form-control"></textarea>
                                <div id="editSkillsError" class="customError"></div>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="edit_business_owner" class="text-dark">{{trans('common.business_owner_label')}}</label>
                                    <select id="edit_business_owner" name="business_owner" class="form-control">
                                        <option value="0">{{trans('common.no_label')}}</option>
                                        <option value="1">{{trans('common.yes_label')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_business_name" class="text-dark">{{trans('common.business_name_label')}}</label>
                                    <input disabled  id="edit_business_name" name="business_name" class="form-control" type="text" />
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_sectors" class="text-dark font-weight-normal">{{trans('common.business_sectors_active')}}</label>
                                    <select disabled name="sectors[]" id="edit_sectors"  class="form-control">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button onclick="submitEditForm()" class="btn btn-teal" id="editSubmitBtn">
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

    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="detailModalLabel">{{trans('common.service_club_member_info')}}</h5>
                    <button type="button" class="close bg-teal border-none" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class="modal-body px-4">
                    <div class="row mb-4">
                        <div class="col d-flex p-1 flex-column align-items-center">
                            <img id="detail_image" alt="member_image" src="{{asset('storage/placeholder-male.jpg')}}" width="120" height="180" style="object-fit: cover; border-radius: 8px">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <div class="text-dark font-weight-bold">{{trans('common.name_label')}}</div>
                                <div class="" id="show_name"></div>
                            </div>
                            <div class="mb-3">
                                <div class="text-dark font-weight-bold">{{trans('common.gender_label')}}</div>
                                <div class="" id="show_gender"></div>
                            </div>
                            <div class="mb-3">
                                <div class="text-dark font-weight-bold">{{trans('common.age_label')}}</div>
                                <div class="" id="show_age"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <div class="text-dark font-weight-bold">{{trans('common.id_number_label')}}</div>
                                <div class="" id="show_id"></div>
                            </div>
                            <div class="mb-3">
                                <div class="text-dark font-weight-bold">{{trans('common.job_description_label')}}</div>
                                <div class="" id="show_job"></div>
                            </div>
                            <div class="mb-3">
                                <div class="text-dark font-weight-bold">{{trans('common.skills_label')}}</div>
                                <div class="" id="show_skills"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <div class="text-dark font-weight-bold">{{trans('common.business_owner_label')}}</div>
                                <div class="" id="show_owner"></div>
                            </div>
                            <div class="mb-3">
                                <div class="text-dark font-weight-bold">{{trans('common.business_name_label')}}</div>
                                <div class="" id="show_business_name"></div>
                            </div>
                            <div class="mb-3">
                                <div class="text-dark font-weight-bold">{{trans('common.business_sectors_active')}}</div>
                                <div class="d-flex flex-row flex-wrap" id="show_business_sectors"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary">{{trans('common.close_label')}}</button>
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
        const editModal = $('#editModal')
        const addModal = $('#addModal')
        const removeModal = $('#removeModal')

        const removeForm = $('#removeForm')
        const editForm = $('#editForm')
        const addForm = $('#addForm')

        const filterBtn = $('#filterBtn')
        const clearBtn = $('#clearBtn')

        const genderFilterEl = $('#filter_gender')
        const dataTable = $("#datatable").DataTable({
            processing: true,
            language: datatableTrans,
            autoWidth:false,
            serverSide: true,
            lengthMenu: [10, 25, 50, 75, 100 ],
            pageLength:10,
            ajax: {
                url:'{!! route('service_club.index') !!}',
                data: function(d){
                    d.gender_id = genderId
                },
            },
            columns: [
                { data: 'name_info', name: 'name', orderable: false, searchable: false },
                // { data: 'name', name: 'name' },
                { data: 'gender_info', name: 'gender_info', orderable: false, searchable: false },
                { data: 'id_number', name: 'id_number' },
                { data: 'profession', name: 'profession' },
                { data: 'skills', name: 'skills' },
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ],
            initComplete: function(settings,json){
                onReloadComplete(json)
            }
        });

        $(document).ready(function(){

            genderFilterEl.on('change',function () {
                genderId = this.value
            })
            filterBtn.on('click',function($event) {
                dataTable.ajax.reload(onReloadComplete)
            })
            clearBtn.on('click',function($event){
                genderFilterEl.val(null)
                genderId = 0;
                dataTable.ajax.reload(onReloadComplete)
            })

            removeForm.submit(function($event) {
                $event.preventDefault()
                let data = $(this).serialize()
                console.log(data);
                $.ajax({
                    url: '{!! route('service_club.destroy') !!}',
                    method: 'delete',
                    data: data,
                    complete: function ({status, responseJSON}) {
                        if (status === 200) {
                            let message = responseJSON.message
                            removeModal.modal('hide')
                            dataTable.ajax.reload()
                            toastr.warning(message, 'Success')
                        }
                    }
                })
            })

            $(".modal").on("hidden.bs.modal", function() {
                $('#add_member_image').attr('src','{{asset('storage/placeholder-male.jpg')}}')
                $('#edit_member_image').attr('src','{{asset('storage/placeholder-male.jpg')}}')
                $('#remove_member_image').attr('src','{{asset('storage/placeholder-male.jpg')}}')

                clearForm('editForm',false)
                $('#add_sectors').val(0).change()
                $('#edit_sectors').val(0).change()
                clearForm('addForm',false)
            });
        });

        function submitAddForm(){
            addForm.validate({
                rules:{
                    member_id :{
                        required:true,
                    },
                    profession: {
                        required:true,
                        minlength:4,
                        maxlength: 40
                    },
                    skills: {
                        required:true,
                        minlength:4,
                        maxlength: 100
                    }
                },
                messages:{
                    member_id: {
                        required:  "{!! trans('custom_validation.select_option') !!}",
                    },
                    profession: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 4]) !!}',
                        maxlength: '{!! trans('custom_validation.max_length',['max' => 40]) !!}',
                    },
                    skills: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 4]) !!}',
                        maxlength: '{!! trans('custom_validation.max_length',['max' => 100]) !!}',
                    }
                },
                errorPlacement: function(error, element){
                    switch (element.attr('name')) {
                        case 'member_id':
                            error.appendTo('#memberError')
                            break;
                        case 'profession':
                            error.appendTo('#professionError')
                            break;
                        case 'skills':
                            error.appendTo('#skillsError')
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
                    url: ' {!! route('service_club.store') !!}',
                    method: 'post',
                    data: data,
                    complete: function ({status,responseJSON }) {
                        if(status === 200){
                            let message = responseJSON.message
                            console.log(message)
                            addModal.modal('hide')
                            dataTable.ajax.reload()
                            toastr.success(message, 'Success')
                        }
                    }
                })

            }
        }

        function submitEditForm(){
            editForm.validate({
                rules:{
                    member_id :{
                        required:true,
                    },
                    profession: {
                        required:true,
                        minlength:4,
                        maxlength: 40
                    },
                    skills: {
                        required:true,
                        minlength:4,
                        maxlength: 100
                    }
                },
                messages:{
                    member_id: {
                        required:  "{!! trans('custom_validation.select_option') !!}",
                    },
                    profession: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 4]) !!}',
                        maxlength: '{!! trans('custom_validation.max_length',['max' => 40]) !!}',
                    },
                    skills: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 4]) !!}',
                        maxlength: '{!! trans('custom_validation.max_length',['max' => 100]) !!}',
                    }
                },
                errorPlacement: function(error, element){
                    switch (element.attr('name')) {
                        case 'member_id':
                            error.appendTo('#editMemberError')
                            break;
                        case 'profession':
                            error.appendTo('#editProfessionError')
                            break;
                        case 'skills':
                            error.appendTo('#editSkillsError')
                            break;
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(editForm.valid()){
                $('#edit_member_id').prop('disabled',false)
                let data = editForm.serialize()
                $.ajax({
                    url: ' {!! route('service_club.update') !!}',
                    method: 'patch',
                    data: data,
                    complete: function ({status,responseJSON },) {
                        if(status === 200){
                            let {message} = responseJSON
                            editModal.modal('toggle')
                            dataTable.ajax.reload()
                            $('#edit_member_id').prop('disabled',true)
                            toastr.info(message, 'Success')
                        }
                    }
                })
            }
        }

        function openAddModal($event){
            const memberId = $('#add_member_id')
            let businessOwner = $('#add_business_owner')
            let businessName = $('#add_business_name')
            let profession = $('#add_profession')
            let addBtn = $('#addSubmitBtn')
            let sectors = $('#add_sectors')
            let image = $('#add_member_image')
            $event.preventDefault()
            addModal.modal('show')
            memberId.select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('members.json') !!}',
                    type: 'post',
                    data: (params)=>{
                        return {
                            _token: '{!! csrf_token() !!}',
                            name: params.term,
                            page: params.page || 1
                        };
                    },
                    dataType: 'json',
                    cache:true,
                    delay:200,
                    placeholder: 'Search Member',
                    processResults: function({total_items, results}, {page}){
                        page = page || 1;
                        return {
                            results: results,
                            pagination: {
                                more: (page * 10) < total_items
                            }
                        }
                    }
                }
            });
            memberId.on('change',function (event) {
                let value = $(this).val()
                console.log(value)
                if(value !== null && value !== 0){
                    $.ajax({
                        url: '{!! route('members.getByIdJson') !!}',
                        method: 'post',
                        data: {
                            _token: '{!!  csrf_token() !!}',
                            id: value
                        },
                        complete: function({status, responseJSON}){
                            if(status === 200){
                                const {member} = responseJSON
                                image.attr('src',member.member_image)
                            }
                        }

                    })
                }
            })
            businessOwner.on('change', function () {
                console.log('hello')
                let value = $(this).val()
                console.log(value)
                if( parseInt(value) === 1){
                    businessName.prop('disabled',false)
                    sectors.prop('disabled',false)
                    sectors.select2({
                        theme: 'classic',
                        multiple:true,
                        ajax: {
                            url: '{!! route('service_club.getSectors') !!}',
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
                            placeholder: 'Search Member',
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
                }
                if(parseInt(value) === 0){
                    businessName.prop('disabled',true)
                    businessName.val('')
                    sectors.prop('disabled',true)
                    sectors.val(null)
                }
                if(parseInt(value) === 3){
                    businessName.prop('disabled',true)
                    businessName.val('')
                    sectors.prop('disabled',true)
                    sectors.val(null)
                }
            })

            profession.on('change', function(event){
                let val = $(this).val()
                if(val.length > 1){
                    addBtn.prop('disabled',false)
                }
                if(val.length < 1){
                    addBtn.prop('disabled',true)
                }
            })
        }

        function openDeleteModal($event){
            $event.preventDefault()
            let name = $event.target.getAttribute('data-name')
            let id = $event.target.getAttribute('data-id')
            let image = $event.target.getAttribute('data-image')
            $('#remove_member_image').attr('src',image)
            $('#confirm_member').html(name);
            $('#remove_member_id').val(id);
            removeModal.modal('show')
        }

        function openEditModal($event){
            let image = $('#edit_member_image')
            let profession = $('#edit_profession')
            let skills = $('#edit_skills')
            let sectors = $('#edit_sectors')
            let businessOwner = $('#edit_business_owner')
            let businessName = $('#edit_business_name')

            $event.preventDefault()
            let id = parseInt($event.target.getAttribute('data-id'))
            $.ajax({
                url: '{!! route('service_club.getById') !!}',
                method:'post',
                data:{
                    "_token": '{!! csrf_token() !!}',
                    "service_member_id": id
                },
                complete: function({status, responseJSON}){
                    if(status === 200){
                        let {member} = responseJSON
                        console.log(member)
                        image.attr('src',member.image)
                        profession.val(member.profession)
                        skills.val(member.skills)
                        if(member.business_owner === 1){
                            setupEditSectors(member.member_id)
                            businessOwner.val(1)
                            businessName.prop('disabled',false)
                            businessName.val(member.business_name)
                            sectors.prop('disabled',false)
                        }
                        else {
                            businessOwner.val(0)
                            businessOwner.on('change', function () {
                                let value = $(this).val()
                                if( parseInt(value) === 1){
                                    businessName.prop('disabled',false)
                                    sectors.prop('disabled',false)
                                    sectors.select2({
                                        theme: 'classic',
                                        multiple:true,
                                        ajax: {
                                            url: '{!! route('service_club.getSectors') !!}',
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
                                }
                                if(parseInt(value) === 0){
                                    businessName.prop('disabled',true)
                                    businessName.val('')
                                    sectors.prop('disabled',true)
                                    sectors.val(null)
                                }
                            })
                        }
                        setupEditMember(member.member_id)
                        editModal.modal('show')
                    }
                }
            })
        }

        function setupEditMember(memberId){
            let memberSelect = $('#edit_member_id').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('members.json') !!}',
                    type: 'post',
                    data: function(params){
                        return {
                            _token: '{!! csrf_token() !!}',
                            name:params.term,
                            adult:true
                        }
                    },
                    dataType: 'json',
                    cache:true,
                    delay:50,
                    placeholder: 'Member',
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
                let option = new Option(data.member.name,data.member.id,true, true)
                memberSelect.append(option).trigger('change')
                memberSelect.trigger({
                    type: 'select2:select',
                    params: {
                        data: data
                    }
                });
                editModal.modal('show')
            });
        }

        function setupEditSectors(serviceMemberId){
            let sectorSelect = $('#edit_sectors').select2({
                theme: 'classic',
                multiple:true,
                ajax: {
                    url: '{!! route('service_club.getSectors') !!}',
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
                url:'{!! route('service_club.getMemberSectors') !!}',
                method:'post',
                data: {
                    _token: '{!! csrf_token() !!}',
                    member_id:serviceMemberId
                },
            })
            .then(function(data){
                let {sectors} = data
                for(let sector of sectors){
                    let option = new Option(sector.sector,sector.id,true, true)
                    sectorSelect.append(option).trigger('change')
                }
                sectorSelect.trigger({
                    type: 'select2:select',
                    params: {
                        data: data
                    }
                });
            })
        }

        function setupEditCurrency(currencyId){
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
                    delay:50,
                    placeholder: 'Currency',
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

        function openDetailModal($event){
            const id = $event.target.getAttribute('data-id')
            $.ajax({
                url:'{!! route('service_club.getById') !!}',
                method:'post',
                data: {
                    _token: '{!! csrf_token() !!}',
                    service_member_id: id
                },
                complete: function({status, responseJSON}){
                    if(status === 200 ){
                        let {member, sectors} = responseJSON
                        console.log(member, sectors)
                        $('#detail_image').attr('src',member.image)
                        $('#show_name').html(member.name)
                        if(member.gender_id === 1){
                            $('#show_gender').html('{!! trans('common.gender_male_label') !!}')
                        }
                        else {
                            $('#show_gender').html('{!! trans('common.gender_female_label') !!}')
                        }
                        $('#show_age').html(member.age)
                        $('#show_id').html(member.id_number)
                        $('#show_job').html(member.profession)
                        $('#show_skills').html(member.skills)
                        if(member.business_owner === 1){
                            $('#show_owner').html('{!! trans('common.yes_label') !!}')
                            $('#show_business_name').html(member.business_name)
                            let sectorsElement = $('#show_business_sectors')
                            sectorsElement.html('')
                            for(let sector of sectors){
                                const element = `<span class='bg-success mr-1 mb-1 rounded-pill text-light' style='padding:5px 10px 5px 10px'>${sector.sector}</span>`
                                sectorsElement.append(element)
                            }
                        }
                        else {
                            $('#show_owner').html('{!! trans('common.no_label') !!}')
                            $('#show_business_name').html('{!! trans('common.no_info') !!}')
                            $('#show_business_sectors').html('{!! trans('common.no_info') !!}')
                        }
                        $('#detailModal').modal('show')
                    }
                }
            })
        }
    </script>
@endsection
