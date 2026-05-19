@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between bg-white">
                    <div class="card-title">
                        <span class="font-weight-bold text-dark text-lg">
                            {{trans('common.edit_eagle_group_label')}}
                        </span>
                    </div>
                    <div class="d-flex flex-row">
                        <form action="{{ route('eagle-group.exportGroupMembers') }}" id="export_form" method="post">
                            @csrf
                            <input type="hidden" id="group_id" name="group_id" value="{{$data['group']['id']}}">
                            <button class="mr-2 pt-2 pb-2 btn btn-primary text-light font-weight-bold" disabled id="exportBtn" type="submit">
                                {{trans(trans('common.export_to_excel_label'))}}
                                <i class="ml-1 fas fa-file-excel"></i>
                            </button>
                        </form>
                        <a class="btn btn-teal text-light font-weight-bold" onclick="addMember(event)">
                            {{trans('common.add_member_label')}}
                            <i class="ml-1 fas fa-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="name" class="label">{{trans('common.name_label')}}</label>
                                <input type="text" name="name" value="{{$data['group']['name']}}" id="name" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="team_captain">{{trans('common.team_captain_label')}}</label>
                                <input name="team_captain" id="team_captain" class="form-control" value="{{ $data['group']['team_captain'] }}"  disabled/>
                            </div>
                        </div>
                    </div>
                    <div class="row pl-1 pr-1">
                        <div class="col">
                            <div class="mt-1 ml-1 font-weight-bold mb-2">{{trans('common.members_label')}}</div>
                            <table id="datatable" class="table table-bordered display compact nowrap">
                                <thead>
                                <tr>
{{--                                    <th>Id</th>--}}
{{--                                    <th>{{trans('common.picture_label')}}</th>--}}
                                    <th>
                                        <i class="fa fa-user text-teal mr-1"></i>
                                        {{trans('common.name_label')}}
                                    </th>
                                    <th>{{trans('common.gender_label')}}</th>
                                    <th>
                                        <i class="fa fa-phone text-teal mr-1"></i>
                                        {{trans('common.phone_number_label')}}
                                    </th>
                                    <th>
                                        <span class="text-teal font-weight-bold mr-1">@</span>
                                        {{trans('common.email_label')}}
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

    <div class="modal" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <input type="hidden" name="group_id" value="{{ $data['group']['id'] }}" />
                    <input type="hidden" name="member_id" value="" id="remove_member_id"/>
                <div class="modal-body">
                    <div class="d-flex flex-row">
                        <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                            <i class="far fa-question-circle"></i>
                        </div>
                        <div class="">
                            {{trans('common.remove_member_from_sheet_label')}}: <div class="d-inline text-teal font-weight-bold" id="confirm_member_name"></div> ?
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">
                        <span class="mr-1"><i class="fa fa-trash"></i></span>
                        {{trans('common.yes_label')}}
                    </button>
                    <button type="button" class="btn btn-secondary">{{trans('common.no_label')}}</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light font-weight-bold">
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.add_member_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="font-weight-bold text-light">&times;</span>
                    </button>
                </div>
                <div class="row">
                    <div class="col pb-2 pt-2 d-flex justify-content-center">
                        <img src="{{ asset('storage/placeholder-male.jpg') }}" id="member_image" alt="member_image"
                             class="rounded" width="140" height="170" style="object-fit: cover">
                    </div>
                </div>
                <div class=" pl-3 pr-3 mt-2 mb-4">
                    <form method="post" action="#" id="addForm">
                        @csrf
                        <input type="hidden" name="group_id" value="{{$data['group']['id']}}"/>
                        <div class="form-row">
                            <div class="col">
                                <div class="from-group">
                                    <label for="new_member" class="font-weight-bolder">{{trans('common.member_label')}}</label>
                                    <select type="text" id="new_member" name="new_member" class="form-control"></select>
                                    <div id="memberError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button onclick="submitAddForm()" id="submitBtn" class="btn btn-teal text-light">
                        <span class="mr-1"><i class="fa fa-save"></i></span>
                        {{trans('common.save_label')}}
                    </button>
                    <button type="button" class="btn btn-danger text-light">{{trans('common.cancel_label')}}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('custom_js')
    <script>
        const addModal = $('#addModal')
        const removeModal = $('#removeModal')
        const removeForm = $('#removeForm')
        const addForm = $('#addForm')
        const dataTable = $("#datatable").DataTable({
            processing: true,
            language: datatableTrans,
            autoWidth:false,
            serverSide: true,
            lengthMenu: [10,15, 25, 50, 75, 100 ],
            pageLength:15,
            ajax: '{!! route('eagle-group.edit',['eagle_group'=>$data['group']]) !!}',
            columns: [
                { data:'name_info', name:'name', orderable: false, searchable: false},
                { data: 'gender_info', name: 'gender' },
                {data: 'phone_number',name: 'phone_number'},
                {data: 'email',name: 'email'},
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ],
            initComplete:function (settings,json) {
                onReloadComplete(json)
            }
        });
        $(document).ready(function() {

            removeForm.submit(function($event){
                $event.preventDefault();
                let data = removeForm.serialize()
                console.log(data)
               $.ajax({
                   method: 'post',
                   url:  "{!! route('eagle-group.removeMember') !!}",
                   data: data,
                   complete: function({status,responseJSON }){
                       if(status === 201){
                           let {message} = responseJSON
                           $('#removeModal').modal('hide')
                           dataTable.ajax.reload(onReloadComplete)
                           toastr.warning(message,'Success')
                           checkGroupInfo()
                       }

                   }
               })


            })

            {{--addForm.submit(function (event) {--}}
            {{--    event.preventDefault()--}}

            {{--    let data = addForm.serializeArray()--}}

            {{--    $.ajax({--}}
            {{--        url: '{!! route('eagle-group.addMemberAjax') !!}',--}}
            {{--        method: 'post',--}}
            {{--        data: data,--}}
            {{--        complete: function({status,responseJSON }){--}}
            {{--            if(status === 201){--}}
            {{--                let {message} = responseJSON--}}
            {{--                $('#addModal').modal('hide')--}}
            {{--                dataTable.ajax.reload(onReloadComplete)--}}
            {{--                toastr.success(message,'Success')--}}
            {{--                checkGroupInfo()--}}
            {{--            }--}}
            {{--        }--}}
            {{--    })--}}
            {{--})--}}

            $(".modal").on("hidden.bs.modal", function() {
               clearForm('addForm',false)
                $('#member_image').attr('src','{!! asset('storage/placeholder-male.jpg') !!}')
            });

        });

        function submitAddForm(){
            addForm.validate({
                rules:{
                    new_member:{
                        required:true,
                        min:1,
                    },
                },
                messages:{
                    new_member: {
                        required: '{!! trans('custom_validation.select_member') !!}',
                        min: '{!! trans('custom_validation.select_member') !!}',
                    },
                },
                errorPlacement: function(error, element){
                    switch(element.attr('name')){
                        case 'new_member':
                            $('#memberError').html(error)
                            break;
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(addForm.valid()) {
                let data = addForm.serializeArray()
                $.ajax({
                    url: '{!! route('eagle-group.addMemberAjax') !!}',
                    method: 'post',
                    data: data,
                    complete: function({status,responseJSON }){
                        if(status === 201){
                            let {message} = responseJSON
                            $('#addModal').modal('hide')
                            dataTable.ajax.reload(onReloadComplete)
                            toastr.success(message,'Success')
                            checkGroupInfo()
                        }
                    }
                })
            }
        }


        function showMember($event){
            $event.preventDefault();
            let memberName = $event.target.getAttribute('data-name');
            let memberId = $event.target.getAttribute('data-id');
            console.log(memberName);
            console.log(memberId);
        }

        function addMember($event){
            $event.preventDefault();
            let newMemberInput = $('#new_member')
            newMemberInput.select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('eagle-group.notMembersJson') !!}',
                    type: 'post',
                    data: function (params) {
                        return {
                            _token: '{!! csrf_token() !!}',
                            name: params.term,
                            page: params.page || 1,
                            team_captain: {!! $data['group']['team_captain_id'] !!},
                            group_id: {!! $data['group']['id'] !!}
                        }
                    },
                    dataType: 'json',
                    cache: true,
                    delay: 200,
                    placeholder: 'Search Member',
                    processResults: function (data,params) {
                        //console.log(data.results)
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: (params.page * 10) < data.total_items
                            }
                        }
                    }
                }
            });
            newMemberInput.on('change',function(){
                let value = $(this).val()
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
                                $('#member_image').attr('src',member.member_image)
                            }
                        }

                    })
                }
                $('#submitBtn').attr('disabled',false);
            })
            addModal.modal('show')
        }

        function removeMember($event){
            $event.preventDefault();
            let memberName = $event.target.getAttribute('data-name');
            let memberId = $event.target.getAttribute('data-id');
            $('#confirm_member_name').html( `${memberName}`);
            $('#remove_member_id').val(memberId.toString());
            removeModal.modal('show')
        }

        function checkGroupInfo() {
            let btn = $('#exportBtn')
            $.ajax({
                url: '{!! route('eagle-group.getByIdAjax') !!}',
                method: 'post',
                data: {
                    _token: '{!! csrf_token() !!}',
                    group_id: {!! $data['group']['id'] !!}
                },
                complete: function(xhr){
                    const {status, responseJSON} = xhr
                    let group = responseJSON.group
                    if(status === 201){
                        group.num_members > 0 ? btn.removeClass('d-none'): btn.addClass('d-none')
                    }
                }
            })
        }
    </script>
@endsection
