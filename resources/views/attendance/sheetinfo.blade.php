@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="card-header d-flex justify-content-between bg-white">
                    <div class="card-title text-lg ">
                         <span class="text-dark font-weight-bold">{{ $data['sheet']['name'] }}</span>
                    </div>
                    <div class="d-flex flex-row">
                        <form action="{{ route('attendance.exportSheet') }}" method="post">
                            @csrf
                            <input type="hidden" id="sheet_id" name="sheet_id" value="{{ $data['sheet']['id'] }}">
                            <button class="mr-2 btn btn-primary text-light font-weight-bold" disabled id="exportBtn" type="submit">
                                {{trans('common.export_to_excel_label')}}
                                <i class="ml-1 fas fa-file-excel"></i>
                            </button>
                        </form>
                        <button onclick="openAddModal(event)" class="btn btn-teal text-light font-weight-bold">
                            {{trans('common.add_member_label')}}
                            <i class="ml-1 fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row pl-3 mb-4">
                        <div class="col-xl-2 col-lg-3 col-md-3 col-sm-4">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="filter_group_id" class="col-form-label font-weight-bold">{{trans('common.filter_by_eagle_label')}}:</label>
                                    <select type="text" id="filter_group_id" name="filter_group_id" class="form-control">
                                        <option value="0">{{trans('common.all_label')}}</option>
                                        @foreach($data['groups'] as $group)
                                            <option value="{{$group['id']}}">{{$group['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="fix-topbar">
                        <table id="datatable" class="table table-bordered display compact nowrap">
                            <thead>
                            <tr class="text-dark">
{{--                                <th>Id</th>--}}
{{--                                <th>{{trans('common.picture_label')}}</th>--}}
                                <th>
                                    <span class="mr-1"><i class="fa fa-user text-teal"></i></span>
                                    {{trans('common.member_label')}}
                                </th>
                                <th>
                                    <i class="fa fa-phone text-teal mr-1"></i>
                                    {{trans('common.phone_number_label')}}
                                </th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-dove text-teal"></i></span>
                                    {{trans('common.eagle_group_label')}}
                                </th>
                                <th style="width: 80px"></th>
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
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.add_member_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="font-weight-bold text-light">&times;</span>
                    </button>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="d-flex justify-content-center pb-2 pt-2">
                            <img src="{{ asset('storage/placeholder-male.jpg') }}" style="object-fit: cover" id="member_image" alt="member_image" class="rounded" width="140" height="170">
                        </div>
                    </div>
                </div>
                <div class="pl-4 pr-4 mt-2 mb-4">
                    <form method="post" action="#" id="addForm">
                        @csrf
                        <input type="hidden" id="add_date" name="date" value="{{$data['sheet']['date']}}">
                        <input type="hidden" id="add_sheet_id" name="sheet_id" value="{{$data['sheet']['id']}}">
                        <div class="form-row mt-2">
                            <div class="col">
                                <label for="member_id" class="font-weight-bold">{{trans('common.member_label')}}<span class="text-danger">*</span></label>
                                <select class="form-control" id="member_id" name="member_id"></select>
                                <div id="memberError" class="customError"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button onclick="submitAddForm()"  id="add_submitBtn" class="btn btn-teal font-weight-normal text-light">
                        <span class="mr-1"><i class="fa fa-save"></i></span>
                        {{trans('common.save_label')}}
                    </button>
                    <button data-dismiss="modal" type="button" class="btn btn-danger font-weight-normal text-light">
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
                    <input type="hidden" id="remove_sheet_id" name="sheet_id" value="{{ $data['sheet']['id'] }}">
                    <input type="hidden" name="remove_member_id" id="remove_member_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-1">
                                {{trans('common.remove_member_from_sheet_label')}}:<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_member"></div> ?
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

@endsection

@section('custom_css')
    @include('shared.totalCSS')
@endsection

@section('custom_js')
    @include('shared.totalJS')
    <script>
        let groupId = 0;

        const filerGroup = $('#filter_group_id')

        const removeForm = $('#removeForm');
        const addForm = $('#addForm');

        const addModal = $('#addModal')
        const removeModal = $('#removeModal')

        const dataTable = $("#datatable").DataTable({
            processing: true,
            language: datatableTrans,
            autoWidth:false,
            serverSide: true,
            lengthMenu: [10, 25, 50, 75, 100 ],
            pageLength:10,
            ajax: {
                url: '{!! route('attendance.viewSheet',['sheet'=>$data['sheet']]) !!}',
                data: function(d) {
                    d.group_id =groupId
                }
            },
            columns: [
                // {data: 'id', name: 'id',searchable: false},
                // { data:'image_info', name:'image_info', orderable: false, searchable: false},
                {data: 'member_info', name: 'member'},
                {data: 'phone_number', name: 'phone_number'},
                {data: 'group', name: 'group'},
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ],
            initComplete: function(settings,json){
                onReloadComplete(json)
            }
        });
        $(document).ready(function(){
            filerGroup.on('change',function(){
                groupId = this.value;
                dataTable.ajax.reload()
            });

            $(".modal").on("hidden.bs.modal", function() {
                clearForm('addForm')
                $('#member_image').attr('src','{!! asset('storage/placeholder-male.jpg') !!}')
            });

            removeForm.submit(function($event){
                $event.preventDefault();
                let data = removeForm.serialize();
                console.log(data)

                $.ajax({
                    url: '{!! route('attendance.removeFromSheet') !!}',
                    method: 'post',
                    data:data,
                    complete: function(xhr){
                        console.log(xhr.responseJSON)
                        if(xhr.status === 201){
                            let message = xhr.responseJSON.message
                            dataTable.ajax.reload(onReloadComplete)
                            toastr.warning(message,'Success')
                            $('#removeModal').modal('hide')
                        }
                    }
                })
            })
        });

        function submitAddForm(){
            addForm.validate({
                rules:{
                    member_id:{
                        required:true,
                        min:1
                    }
                },
                messages:{
                    member_id:{
                        required:'{!! trans('custom_validation.select_member') !!}',
                        min:'{!! trans('custom_validation.select_member') !!}'
                    }
                },
                errorPlacement: function(error, element){
                    switch(element.attr('name')){
                        case 'member_id':
                            $('#memberError').html(error)
                            break;
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(addForm.valid()){
                let data = addForm.serialize();
                $.ajax({
                    url: '{!! route('attendance.addToSheet') !!}',
                    method: 'post',
                    data:data,
                    complete: function({status,responseJSON }){
                        if(status === 201){
                            let {message} = responseJSON
                            dataTable.ajax.reload(onReloadComplete)
                            toastr.success(message,'Success')
                            addModal.modal('hide')
                        }
                    }
                })
            }
        }

        function openAddModal($event){
            $event.preventDefault()
            let groupInput = $('#group_id')
            let memberInput = $('#member_id')
            addModal.modal('show')
            memberInput.select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('eagle-group.getGroupMembersJson') !!}',
                    type: 'post',
                    data: function(params){
                        console.log(`page: ${params.page}`)
                        return {
                            _token: '{!! csrf_token() !!}',
                            term:params.term,
                            page: params.page || 1,
                            sheet_id: {!! $data['sheet']['id'] !!}
                        }
                    },
                    dataType: 'json',
                    cache:true,
                    delay:200,
                    placeholder: 'Search Member',
                    processResults: function(data, params){
                        params.page = params.page || 1;
                        const {total_items} = data;
                        console.log(`total items: ${total_items}`)
                        return {
                            results: data.results,
                            pagination: {
                                more: (params.page * 10) < total_items
                            }
                        }
                    }
                }
            });

            memberInput.on('change',function(){
                console.log('get image  ')
                let value = $(this).val()
                if(value !== null && value !== 0){
                    $.ajax({
                        url: '{!! route('members.getByIdJson') !!}',
                        method: 'post',
                        data: {
                            _token: '{!!  csrf_token() !!}',
                            id: memberInput.val()
                        },
                        complete: function({status,responseJSON}){
                            if(status === 200){
                                const {member} = responseJSON
                                $('#member_image').attr('src',member.member_image)
                            }
                        }
                    })
                }
                $('#add_submitBtn').attr('disabled',false);
            })
        }

        function openRemoveModal($event){
            $event.preventDefault()
            removeModal.modal('show')
            let memberName = $event.target.getAttribute('data-name')
            let member_id = $event.target.getAttribute('data-id')
            $('#confirm_member').html( `${memberName}`);
            $('input[name="remove_member_id"]').val(member_id.toString());
        }
    </script>
@endsection
