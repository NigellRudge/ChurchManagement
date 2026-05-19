@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="card-header bg-white d-flex mb-2 justify-content-between">
                    <div class="text-lg">
                        <strong class="mr-1">{{trans('common.sheet_label')}}:</strong><span class="text-dark font-weight-bold">{{ $data['sheet']['name'] }}</span>
                    </div>
                    <div class="d-flex flex-row">
                        <form action="{{ route('covid-registration.exportSheet') }}" id="export_form" method="post">
                            @csrf
                            <input type="hidden" id="sheet_id" name="sheet_id" value="{{$data['sheet']['id']}}">
                            <button class="mr-2 btn btn-primary text-light font-weight-bold" id="exportBtn" disabled type="submit">
                                {{trans('common.export_to_excel_label')}}
                                <i class="ml-1 fas fa-file-excel"></i>
                            </button>
                        </form>
                        <button onclick="openAddModal(event)"  id="addBtn" class="btn btn-teal text-light font-weight-bold">
                            {{trans('common.add_member_label')}}
                            <i class="ml-1 fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="fix-topbar">
                        <table id="datatable" class="table table-bordered display compact nowrap">
                            <thead>
                            <tr class="text-dark">
                                <th>
                                    <span class="mr-1"><i class="fa fa-user text-teal"></i></span>
                                    {{trans('common.member_label')}}
                                </th>
                                <th>{{trans('common.gender_label')}}</th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-phone text-teal"></i></span>
                                    {{trans('common.phone_number_label')}}
                                </th>
                                <th>{{trans('common.id_number_label')}}</th>
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
                        <img src="{{ asset('storage/placeholder-male.jpg') }}" style="object-fit: cover" id="member_image" alt="member_image" class="rounded" width="140" height="170">
                    </div>
                </div>
                <form method="post" action="#" id="add_form">
                    @csrf
                    <input type="hidden" id="sheet_id" name="sheet_id" value="{{$data['sheet']['id']}}">
                    <div class="pl-4 pr-4 mt-2 mb-4">
                        <div class="form-row mt-2">
                            <div class="col">
                                <label for="member_id" class="font-weight-bold">{{trans('common.member_label')}}<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-white text-teal">
                                            <i class="fa fa-user"></i>
                                        </div>
                                    </div>
                                    <select class="form-control" id="member_id" name="member_id"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit"  id="add_submitBtn" class="btn btn-teal font-weight-bold text-light" disabled>
                            <span class="mr-1"><i class="fa fa-save"></i></span>
                            {{trans('common.save_label')}}
                        </button>
                        <button type="button" class="btn btn-danger font-weight-bold text-light">{{trans('common.cancel_label')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="exampleModalLabel">{{trans('common.confirm_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="font-weight-bold text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="remove_form">
                    @csrf
                    <input type="hidden" id="sheet_id" name="sheet_id" value="{{ $data['sheet']['id'] }}">
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
    <style>
        table > tbody > tr {

        }
    </style>
@endsection

@section('custom_js')
    @include('shared.totalJS')
    <script>

        const modal = $('#addModal')
        $(document).ready(function(){
            const dataTable = $("#datatable").DataTable({
                processing: true,
                serverSide: true,
                lengthMenu: [10, 25, 50, 75, 100 ],
                pageLength:10,
                ajax: '{!! route('covid-registration.sheetInfo',['sheet'=>$data['sheet']]) !!}',
                columns: [
                    {data: 'member_info', name: 'member'},
                    {data: 'gender', name: 'gender'},
                    {data: 'phone_number', name: 'phone_number'},
                    {data: 'id_number', name: 'id_number'},
                    { data:'actions', name:'actions', orderable: false, searchable: false}
                ],
                initComplete:function (settings,json) {
                    onReloadComplete(json)
                }
            });
            let addForm = $('#add_form');
            addForm.submit(function($event){
                $event.preventDefault();
                let data = addForm.serialize();
                //console.log(data)
                $.ajax({
                    url: '{!! route('covid-registration.addToSheet',['sheet'=>$data['sheet']]) !!}',
                    method: 'post',
                    data:data,
                    complete: function({status,responseJSON}){
                        if(status === 200){
                            let {message} = responseJSON
                            dataTable.ajax.reload(onReloadComplete)
                            toastr.success(message,'Success')
                            $('#addModal').modal('hide')
                        }
                    }
                })
            })
            $(".modal").on("hidden.bs.modal", function() {
                console.log('hidden')

                let memberInput = $('#member_id')
                memberInput.val(null).trigger('change')

                $('#add_submitBtn').attr('disabled',true);
                $('#member_image').attr('src','{!! asset('storage/placeholder-male.jpg') !!}')
            });

            let removeForm = $('#remove_form');
            removeForm.submit(function($event){
                $event.preventDefault();
                let data = removeForm.serialize();
                //console.log(data)

                $.ajax({
                    url: '{!! route('covid-registration.removeFromSheet',['sheet'=>$data['sheet']]) !!}',
                    method: 'delete',
                    data:data,
                    complete: function({status,responseJSON }){
                        if(status === 200){
                            const {message} = responseJSON
                            dataTable.ajax.reload(onReloadComplete)
                            toastr.warning(message,'Success')
                            $('#removeModal').modal('hide')
                        }
                    }
                })
            })
        });

        function openAddModal($event){
            $event.preventDefault()
            let memberInput = $('#member_id')
            modal.modal('show')
            memberInput.select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('covid-registration.membersNotOnSheet') !!}',
                    type: 'post',
                    data: function(params){
                        return {
                            _token: '{!! csrf_token() !!}',
                            name:params.term,
                            page: params.page || 1,
                            sheet_id: {!! $data['sheet']['id'] !!}
                        }
                    },
                    dataType: 'json',
                    cache:true,
                    delay:200,
                    placeholder: 'Search Member',
                    processResults: function(data,params){
                        params.page = params.page || 1;
                        //console.log(data)
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
            memberInput.on('change',function(){
                let value = $(this).val()
                if(value !== null && value !== 0){
                    $.ajax({
                        url: '{!! route('members.getByIdJson') !!}',
                        method: 'post',
                        data: {
                            _token: '{!!  csrf_token() !!}',
                            id: memberInput.val()
                        },
                        complete: function({status,responseJSON }){
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
            let removeModal = $('#removeModal')
            removeModal.modal('show')
            let memberName = $event.target.getAttribute('data-name')
            let member_id = $event.target.getAttribute('data-id')
            $('#confirm_member').html( `${memberName}`);
            $('#remove_member_id').val(member_id.toString());
        }

    </script>
@endsection
