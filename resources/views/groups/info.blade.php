@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-white">
                    <div class=" mb-2 d-flex justify-content-between">
                        <div class="text-lg font-weight-bold">
                            {{trans('common.group_info_label')}}
                        </div>
                        <div class="">
                            <a class="btn btn-teal font-weight-bold rounded-lg text-light" onclick="openAddModal(event)">
                                {{trans('common.work_group_add_member_label')}}
                                <i class="fa fa-plus ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-xl-2 col-lg-2 col-md-4">
                            <label for="name">{{trans('common.name_label')}}</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{$data['group']['name']}}" disabled>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-4">
                            <div class="d-flex flex-column align-items-center">
                                <div class="d-flex flex-row justify-content-center mb-2">
                                    <img class="rounded-md" src="{{$data['coordinator']['image']}}" alt="coordinator" width="120px" height="120px" style="object-fit: cover;border-radius: 12px">
                                </div>
                                <div class="d-flex flex-column align-content-start">
                                    <span class="font-weight-bold text-dark">{{$data['group']['coordinator']}}</span>
                                    <span class="font-weight-normal">{{trans('common.coordinator_label')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-4">
                            <div class="d-flex flex-column align-items-center">
                                <div class="d-flex flex-row justify-content-center mb-2">
                                    <img class="rounded-md" src="{{$data['pastor']['image']}}" alt="coordinator" width="120px" height="120px" style="object-fit: cover;border-radius: 12px">
                                </div>
                                <div class="d-flex flex-column align-content-start">
                                    <span class="font-weight-bold text-dark">{{$data['group']['pastor']}}</span>
                                    <span class="font-weight-normal">{{trans('common.pastor_label')}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pl-1 pr-1">
                        <div class="col">
                            <div class="mt-1 ml-1 font-weight-bold mb-2">{{trans('common.work_group_members_label')}}</div>
                            <table id="datatable" class="table table-bordered display compact nowrap">
                                <thead>
                                <tr>
{{--                                    <th>#</th>--}}
{{--                                    <th>{{trans('common.picture_label')}}</th>--}}
                                    <th>
                                        <span class="mr-1"><i class="fa fa-user text-teal"></i></span>
                                        {{trans('common.member_label')}}
                                    </th>
                                    <th>
                                        <span class="mr-1"><i class="fa fa-phone text-teal"></i></span>
                                        {{trans('common.phone_number_label')}}
                                    </th>
                                    <th>
                                        <span class="mr-1"><i class="fa fa-id-card text-teal"></i></span>
                                        {{trans('common.id_number_label')}}
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
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light font-weight-bold">
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.add_member_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class="row">
                    <div class="col pb-2 pt-2 d-flex justify-content-center">
                        <img src="{{ asset('storage/placeholder-male.jpg') }}" id="member_image" alt="member_image" class="rounded" width="140" height="170" style="object-fit: cover">
                    </div>
                </div>
                <div class=" mt-2 pl-3 pr-3">
                    <form method="post" action="#" id="addForm">
                        <input type="hidden" id="group_id" value="{{$data['group']['id']}}" name="group_id" />
                        @csrf
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="member_id" class="font-weight-bold">{{trans('common.member_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        </div>
                                        <select name="member_id" data-placeholder="{{trans('common.select_member_label')}}"  id="member_id" type="text" class="form-control">
                                        </select>
                                    </div>
                                    <div id="memberError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="join_date" class="font-weight-bold">{{trans('common.join_date_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                        </div>
                                        <input name="join_date" data-placeholder="Select member"  id="join_date" type="text" class="form-control">
                                    </div>
                                    <div id="dateError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button onclick="submitAddForm()" class="btn btn-teal" id="submitBtn">
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
                    <h5 class="modal-title" id="exampleModalLabel">{{trans('common.work_group_member_remove_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="removeForm">
                    @csrf
                    <input type="hidden" name="remove_member_id" id="remove_member_id">
                    <input type="hidden" name="group_id" id="edit_group_id" value="{{ $data['group']['id'] }}">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-1">
                                <span class="text-dark">{{trans('common.confirm_delete_worker_label')}}</span><br>
                                <div class="d-inline text-teal font-weight-bold mt-1" id="confirm_member"></div> ?
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

@endsection
@section('custom_css')
    @include('shared.totalCSS')
@endsection

@section('custom_js')
    @include('shared.totalJS')
    <script>
        const deleteForm = $('#removeForm')
        const addForm = $('#addForm')
        const dataTable = $("#datatable").DataTable({
            processing: true,
            language: datatableTrans,
            autoWidth:false,
            serverSide: true,
            lengthMenu: [5, 10, 25, 50, 75, 100 ],
            pageLength:5,
            ajax: '{!! route('work-groups.info',['work_group'=>$data['group']]) !!}',
            columns: [
                // { data: 'id', name: 'id' },
                // { data: 'image_info', name: 'image_info', orderable: false, searchable: false},
                { data: 'member_info', name: 'member' },
                {data: 'phone_number',name: 'phone_number'},
                {data: 'id_number',name: 'id_number'},
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ]
        });
        $(document).ready(function(){
            deleteForm.submit(function($event) {
                $event.preventDefault()
                let data = deleteForm.serialize()
                console.log(data);
                $.ajax({
                    url: '{!! route('work-groups.removeMemberAjax') !!}',
                    method: 'post',
                    data: data,
                    success: function (data) {
                        console.log(data)
                    },
                    error: function (error) {
                        console.log(error)
                    },
                    complete: function (xhr, data) {
                        if (xhr.status === 201) {
                            let message = xhr.responseJSON.message
                            $('#removeModal').modal('hide')
                            dataTable.ajax.reload()
                            toastr.warning(message,'{{trans('common.success_label')}}');
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
                    member_id:{
                        required:true,
                        min:1,
                    },
                    join_date: {
                        required:true,
                        date:true
                    },
                },
                messages:{
                    member_id: {
                        required:'{!! trans('custom_validation.select_member') !!}',
                        min:'{!! trans('custom_validation.select_member') !!}',
                    },
                    join_date: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        date:'{!! trans('custom_validation.valid_date') !!}'
                    },
                },
                errorPlacement: function(error, element){
                    switch (element.attr('name')) {
                        case 'member_id':
                            $('#memberError').html(error)
                            break;
                        case 'join_date':
                            $('#joinError').html(error)
                            break;
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(addForm.valid()){
                let data = addForm.serialize()
                $.ajax({
                    url: ' {!! route('work-groups.addMembersAjax') !!}',
                    method: 'post',
                    data: data,
                    complete: function ({status,responseJSON }) {
                        if(status === 201){
                            $('#addModal').modal('toggle')
                            let {message} = responseJSON
                            dataTable.ajax.reload()
                            toastr.success(message,'{{trans('common.success_label')}}');
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
            let addModal = $('#addModal')
            $('#member_id').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('work-groups.getNotYetMembersJson') !!}',
                    type: 'post',
                    data: function(params){
                        return {
                            _token: '{!! csrf_token() !!}',
                            member_type_id:1,
                            pastor_id: {!! $data['group']['pastor_id'] !!},
                            coordinator_id: {!! $data['group']['coordinator_id'] !!},
                            group_id: {!! $data['group']['id'] !!},
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
            }).on('change',function(){
                let value = $(this).val()
                if(value !== null && value !== 0){
                    $.ajax({
                        url: '{!! route('members.getByIdJson') !!}',
                        method: 'post',
                        data: {
                            _token: '{!!  csrf_token() !!}',
                            id: value
                        },
                        complete: function({status,responseJSON }){
                            if(status === 200){
                                let {member} = responseJSON
                                $('#member_image').attr('src',member.member_image)
                            }
                        }
                    })
                }
            });
            $('#join_date').daterangepicker({
                singleDatePicker:true,
                autoUpdateInput: true,
                showDropdowns: true,
                minYear: 1901,
                locale:datePickerTran,
                applyButtonClasses:'btn btn-teal btn-sm',
                cancelButtonClasses:'btn btn-danger btn-sm'
            })
            addModal.modal('show')
        }

        function openRemoveModal($event){
            $event.preventDefault()
            let modal = $('#removeModal')
            let memberName = $event.target.getAttribute('data-name')
            let memberId = $event.target.getAttribute('data-id')
            $('#confirm_member').html(memberName)
            $("input[name='remove_member_id']").val(memberId)
            modal.modal('show')
        }

    </script>
@endsection
