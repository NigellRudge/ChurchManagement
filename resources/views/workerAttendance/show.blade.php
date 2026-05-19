@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between">
                    <div class="font-weight-bold text-lg text-dark">
                        {{trans('common.sheet_label')}}: {{$data['sheet']['name']}}
                    </div>
                    <div>
                        <button onclick="addItem(event)" class="btn btn-teal text-light font-weight-bold">
                            {{trans('common.add_member_label')}}
                            <i class="ml-1 fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-xl-3 col-lg-3 col-md-4">
                            <div class="form-group">
                                <label for="d_date" class="font-weight-bold text-dark">{{trans('common.date_label')}}</label>
                                <input type="text" id="d_date" class="form-control" value="{{$data['sheet']['date']}}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="fix-topbar">
                        <table id="datatable" class="table table-bordered display compact nowrap">
                            <thead>
                            <tr class="text-dark">
{{--                                <th>Id</th>--}}
{{--                                <th>{{trans('common.picture_label')}}</th>--}}
                                <th>{{trans('common.member_label')}}</th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-phone text-teal"></i></span>
                                    {{trans('common.phone_number_label')}}
                                </th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-id-card text-teal"></i></span>
                                    {{trans('common.id_number_label')}}
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
                    <div class="col pb-2 pt-2 d-flex justify-content-center">
                        <img src="{{ asset('storage/placeholder-male.jpg') }}" id="member_image" alt="member_image"
                             class="rounded" width="140" height="170" style="object-fit: cover">
                    </div>
                </div>
                <div class="pl-3 pr-3 mt-2 mb-4">
                    <form method="post" action="#" id="addForm">
                        @csrf
                        <input type="hidden" name="sheet_id" value="{{$data['sheet']['id']}}"/>
                        <div class="form-row">
                            <div class="col">
                                <div class="from-group">
                                    <label for="add_member_id" class="font-weight-bolder">{{trans('common.member_label')}}</label>
                                    <select type="text" id="add_member_id" name="member_id" class="form-control"></select>
                                </div>
                                <div id="memberError" class="customError"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button onclick="submitAddForm()" class="btn btn-teal text-light">
                        <span class="mr-1"><i class="fa fa-save"></i></span>
                        {{trans('common.save_label')}}
                    </button>
                    <button type="button" class="btn btn-danger text-light">{{trans('common.cancel_label')}}</button>
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
                    <input type="hidden" name="member_id" id="remove_member_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-2">
                                {{trans('common.remove_member_from_sheet_label')}}:<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_member"></div> ?
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
        const removeForm = $('#removeForm')

        const dataTable =  $('#datatable').DataTable({
            processing: true,
            language: datatableTrans,
            autoWidth:false,
            serverSide: true,
            lengthMenu: [10, 25, 50, 75, 100 ],
            pageLength:10,
            ajax: '{!! route('workerAttendance.show',['sheet' => $data['sheet']['id']]) !!}',
            columns: [
                // {data: 'id', name: 'id',searchable: false},
                // {data: 'image_info', name: 'image_info', orderable: false, searchable: false},
                {data: 'member_info', name: 'member'},
                {data: 'phone_number', name: 'phone_number'},
                {data: 'id_number', name: 'id_number'},
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ]
        });
        $(document).ready(function(){
            {{--const dataTable =  $('#datatable').DataTable({--}}
            {{--    processing: true,--}}
            {{--    language: datatableTrans,--}}
            {{--    autoWidth:false,--}}
            {{--    serverSide: true,--}}
            {{--    lengthMenu: [10, 25, 50, 75, 100 ],--}}
            {{--    pageLength:10,--}}
            {{--    ajax: '{!! route('workerAttendance.show',['sheet' => $data['sheet']['id']]) !!}',--}}
            {{--    columns: [--}}
            {{--        {data: 'id', name: 'id',searchable: false},--}}
            {{--        {data: 'image_info', name: 'image_info', orderable: false, searchable: false},--}}
            {{--        {data: 'member', name: 'member'},--}}
            {{--        {data: 'phone_number', name: 'phone_number'},--}}
            {{--        { data:'actions', name:'actions', orderable: false, searchable: false}--}}
            {{--    ]--}}
            {{--});--}}

            {{--addForm.validate({--}}
            {{--    rules:{--}}
            {{--        group_id:{--}}
            {{--            required:true,--}}
            {{--            min:1,--}}
            {{--        },--}}
            {{--        date: {--}}
            {{--            required:true,--}}
            {{--            date: true--}}
            {{--        },--}}
            {{--        name: {--}}
            {{--            required:true,--}}
            {{--        }--}}
            {{--    },--}}
            {{--    messages:{--}}
            {{--        group_id: "please select a group",--}}
            {{--        date: "please enter a valid date",--}}
            {{--        amount: "please enter a name"--}}
            {{--    },--}}
            {{--    errorClass: 'is-invalid',--}}
            {{--    validClass: 'is-valid',--}}
            {{--})--}}
            {{--addForm.submit(function($event){--}}
            {{--    $event.preventDefault();--}}
            {{--    let data = addForm.serialize()--}}
            {{--    console.log(data)--}}
            {{--    $.ajax({--}}
            {{--        url: ' {!! route('workerAttendance.addToSheet',['sheet' =>$data['sheet']]) !!}',--}}
            {{--        method: 'post',--}}
            {{--        data: data,--}}
            {{--        complete: function (xhr,status) {--}}
            {{--            if(xhr.status === 200){--}}
            {{--                let message = xhr.responseJSON.message--}}
            {{--                $('#addModal').modal('hide')--}}
            {{--                dataTable.ajax.reload()--}}
            {{--                toastr.success(message, '{{trans('common.success_label')}}')--}}
            {{--            }--}}
            {{--        }--}}
            {{--    })--}}

            {{--});--}}

            removeForm.submit(function($event) {
                $event.preventDefault()
                let data = $(this).serialize()
                console.log(data);
                $.ajax({
                    url: '{!! route('workerAttendance.removeFromSheet',['sheet'=>$data['sheet']]) !!}',
                    method: 'delete',
                    data: data,
                    complete: function ({status, responseJSON}) {
                        if (status === 200) {
                            const {message} = responseJSON
                            $('#removeModal').modal('hide')
                            dataTable.ajax.reload()
                            toastr.warning(message, '{{trans('common.success_label')}}')
                        }
                    }
                })
            })
            $(".modal").on("hidden.bs.modal", function() {
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
                },
                messages:{
                    member_id: {
                        required: '{!! trans('custom_validation.select_member') !!}',
                        min:'{!! trans('custom_validation.select_member') !!}',
                    },
                },
                errorPlacement: function(error, element){
                    switch (element.attr('name')) {
                        case 'member_id':
                            $('#memberError').html(error)
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
                    url: ' {!! route('workerAttendance.addToSheet',['sheet' =>$data['sheet']]) !!}',
                    method: 'post',
                    data: data,
                    complete: function ({status,responseJSON}) {
                        if(status === 200){
                            let {message} = responseJSON
                            dataTable.ajax.reload()
                            addModal.modal('hide')
                            toastr.success(message, '{{trans('common.success_label')}}')
                        }
                    }
                })
            }
        }

        function addItem($event){
            event.preventDefault()
            let newMemberInput = $('#add_member_id')
            newMemberInput.select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('workerAttendance.membersNotOnSheet') !!}',
                    type: 'post',
                    data: function (params) {
                        return {
                            _token: '{!! csrf_token() !!}',
                            name: params.term,
                            page: params.page || 1,
                            sheet_id: {!! $data['sheet']['id'] !!},
                            group_id: {!! $data['sheet']['group_id'] !!}
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
                        complete: function({status,responseJSON }){
                            if(status === 200){
                                const {member} = responseJSON
                                console.log(member)
                                $('#member_image').attr('src',member.member_image)
                            }
                        }

                    })
                }
                $('#submitBtn').attr('disabled',false);
            })
            addModal.modal('show')
        }

        function removeItem($event){
            $event.preventDefault()
            const memberId = $event.target.getAttribute('data-id')
            const name = $event.target.getAttribute('data-name')
            console.log(name)
            $('#remove_member_id').val(memberId)
            $('#confirm_member').html(name)
            removeModal.modal('show')
        }

        function editSheet($event){

        }
    </script>
@endsection
