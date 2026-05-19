@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="card-header d-flex bg-white justify-content-between">
                    <div class="card-title pt-1">
                        <span class="font-weight-bold text-lg text-dark">{{trans('common.members_label')}}</span>
                    </div>
                    <div class="d-flex flex-row">
                        <div class="mr-1">
                            <form method="post" action="{{ route('members.exportMembers') }}">
                                @csrf
                                <input type="hidden" id="export_gender_id" name="gender_id">
                                <input type="hidden" id="export_from_age" name="from_age">
                                <input type="hidden" id="export_to_age" name="to_age">
                                <input type="hidden" id="export_member_type_id" name="member_type_id">
                                <input type="hidden" id="export_baptized" name="baptized">
                                <input type="hidden" id="export_status" name="status">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-file-excel mr-1"></i>
                                    {{trans('common.export_member_list')}}
                                </button>
                            </form>
                        </div>
                        <div class="mr-1">
                            <button onclick="openBirthDayModal(event)" class="btn btn-info">
                                <i class="fa fa-birthday-cake mr-1"></i>
                                {{trans('common.birth_days')}}
                            </button>
                        </div>
                        <a class="btn btn-teal font-weight-bold text-white" href="{{ route('members.create') }}">
                            {{trans('common.add_member_label')}}
                            <i class="ml-1 fas fa-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row pl-2 mt-2 mb-1">
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-5">
                            <div class="form-group">
                                <label for="type_filter" class="col-form-label font-weight-bold">{{trans('common.filter_by_type_label')}}</label>
                                <select type="text" id="type_filter" name="type_filter" class="form-control">
                                    <option value="0">{{trans('common.all_label')}}</option>
                                    @foreach($data['member_types'] as $type)
                                        <option value="{{$type['id']}}">{{$type['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-5">
                            <div class="form-group ">
                                <label for="filter_gender" class="col-form-label font-weight-bold">{{trans('common.filter_by_gender_label')}}</label>
                                <select type="text" id="filter_gender" name="filter_gender" class="form-control">
                                    <option value="0">{{trans('common.all_label')}}</option>
                                    @foreach($data['genders'] as $gender)
                                        <option value="{{$gender['id']}}">{{trans('common' . '.'. $gender['trans_string'])}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-5">
                            <div class="form-group ">
                                <label for="filter_status" class="col-form-label font-weight-bold">{{trans('common.status_label')}}</label>
                                <select type="text" id="filter_status" name="status_id" class="form-control">
                                    <option value="3">{{trans('common.all_label')}}</option>
                                    <option value="1">{{trans('common.active_label')}}</option>
                                    <option value="0">{{trans('common.inactive_label')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-5">
                            <div class="form-group ">
                                <label for="filter_baptized" class="col-form-label font-weight-bold">{{trans('common.baptized_label')}}</label>
                                <select type="text" id="filter_baptized" name="baptized" class="form-control">
                                    <option value="3">{{trans('common.all_label')}}</option>
                                    <option value="1">{{trans('common.yes_label')}}</option>
                                    <option value="0">{{trans('common.no_label')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-1 col-lg-2 col-md-3 col-sm-4">
                            <div class="form-group ">
                                <label for="filter_from_age" class="col-form-label font-weight-bold">{{trans('common.min_age')}}</label>
                                <input type="number" id="filter_from_age" name="from_age" class="form-control" min="0" max="120" />
                            </div>
                        </div>
                        <div class="col-xl-1 col-lg-2 col-md-3 col-sm-4">
                            <div class="form-group ">
                                <label for="filter_to_age" class="col-form-label font-weight-bold">{{trans('common.max_age')}}</label>
                                <input type="number" id="filter_to_age" name="to_age" class="form-control" min="0" max="120" />
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4 px-2">
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5">
                            <div class="d-flex flex-row">
                                <button class="btn btn-teal text-light font-weight-bold mr-1" id="filterBtn">
                                    {{trans('common.filter_label')}}
                                    <i class="fas fa-filter ml-1"></i>
                                </button>
                                <button class="btn btn-danger text-light font-weight-bold" id="clearBtn">
                                    {{trans('common.clear_label')}}
                                    <i class="fas fa-ban ml-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="fix-topbar">
                    <table id="datatable" class="table table-bordered display nowrap" style="width: 100%">
                        <thead class="">
                            <tr class="text-dark">
{{--                                <th>{{trans('common.picture_label')}}</th>--}}
                                <th>
                                    <span class="mr-1"><i class="fa fa-user text-teal"></i></span>
                                    {{trans('common.name_label')}}
                                </th>
                                <th>{{trans('common.age_label')}}</th>
                                <th>{{trans('common.gender_label')}}</th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-phone text-teal"></i></span>
                                    {{trans('common.phone_number_label')}}
                                </th>
{{--                                <th>--}}
{{--                                    <span class="mr-1"><i class="fa fa-users-cog text-teal"></i></span>--}}
{{--                                    {{trans('common.member_type_label')}}--}}
{{--                                </th>--}}
                                <th>
                                    <span class="mr-1 text-teal font-weight-bold">@ </span>
                                    {{trans('common.email_label')}}
                                </th>
                                <th>{{trans('common.status_label')}}</th>
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

    <div class="modal" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="uploadModalLabel">Upload File</h5>
                    <button type="button" class="close bg-teal border-none" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="font-weight-bold text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('members.import') }}" id="upload_form" enctype="multipart/form-data">
                    @csrf
                    <div class="pr-3 pl-3 pb-2 pt-2">
                        <div class="form-group">
                            <label for="import_file">Select File</label>
                            <input type="file" class="form-control-file" id="import_file" name="import_file">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal font-weight-bold">
                            <i class="fas fa-save mr-1"></i>
                            Upload
                        </button>
                        <button type="button" class="btn btn-danger font-weight-bold" data-dismiss="modal">
                            <i class="fas fa-ban mr-1"></i>
                            Cancel
                        </button>
                    </div>
                </form>
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
                        <img id="member_image" alt="member_image" src="{{asset('storage/placeholder-male.jpg')}}" width="120" height="180" style="object-fit: cover; border-radius: 8px">
                        <div class="mt-2 d-inline text-dark font-weight-bold" id="confirm_member_name"></div>
                    </div>
                </div>
                <form method="post" action="#" id="remove_form">
                    @csrf
                    <input type="hidden" name="member_id"  id="remove_member_id" value=""/>
                    <div class="modal-body px-4">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="remove_date" class="font-weight-normal text-dark">{{trans('common.date_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white">
                                                <i class="far fa-calendar-alt text-teal"></i>
                                            </div>
                                        </div>
                                        <input type="text" id="remove_date" name="remove_date" class="form-control">
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="remove_reason" class="font-weight-normal text-dark">{{trans('common.remove_reason')}}</label>
                                    <textarea type="text" id="remove_reason" name="remove_reason" rows="4" class="form-control"></textarea>
                                </div>
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

    <div class="modal fade" id="reactivateModal" tabindex="-1" role="dialog" aria-labelledby="reactivateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="reactivateModalLabel">{{trans('common.reactivate_member_label')}}</h5>
                    <button type="button" class="close bg-teal border-none" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class="row">
                    <div class="col p-4 d-flex flex-row justify-content-center">
                        {{trans('common.confirm_reactivate_member_label')}}
                    </div>
                </div>
                <div class="row">
                    <div class="col d-flex p-1 flex-column align-items-center">
                        <img id="reactivate_image" alt="member_image" src="{{asset('storage/placeholder-male.jpg')}}" width="120" height="180" style="object-fit: cover; border-radius: 8px">
                        <div class="mt-2 d-inline text-dark font-weight-bold" id="reactivate_member_name"></div>
                    </div>
                </div>
                <form method="post" action="#" id="reactivate_form">
                    @csrf
                    <input type="hidden" name="member_id"  id="reactivate_member_id" value=""/>
                    <div class="modal-body px-4">
                    </div>
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

    <div class="modal fade" id="birthDayModal" tabindex="-1" role="dialog" aria-labelledby="birthDayModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="birthDayModalLabel">{{trans('common.birth_days')}}</h5>
                    <button type="button" class="close bg-teal border-none" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class="row d-flex flex-row justify-content-center">
                        <div class="pt-2">
                            <h5 class="text-dark font-weight-bold">
                                {{trans('common.select_birth_period')}}
                            </h5>
                        </div>
                </div>
                <form action="{{ route('members.birthDayExport') }}" method="post" id="birthDayForm">
                    @csrf
                    <div class="modal-body px-4">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="birth_day_export_start_date" class="text-dark">{{trans('common.start_date')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <div class="i fa fa-calendar-alt"></div>
                                            </div>
                                        </div>
                                        <input type="text" id="birth_day_export_start_date" class="form-control" name="start_date" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="birth_day_export_end_date" class="text-dark">{{trans('common.end_date')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <div class="i fa fa-calendar-alt"></div>
                                            </div>
                                        </div>
                                        <input type="text" id="birth_day_export_end_date" class="form-control" name="end_date" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info">
                            <span class="mr-1"><i class="fa fa-download"></i></span>
                            {{trans('common.download_file')}}
                        </button>
                        <button data-dismiss="modal" aria-label="Close" type="button" class="btn btn-secondary">{{trans('common.cancel_label')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_css')
    @include('shared.totalCSS')
    <style>
        div.dataTables_wrapper {
            margin: 0 auto;
        }
    </style>
@endsection

@section('custom_js')
    @include('shared.totalJS')
    <script>
        const removeModal = $('#removeModal')
        const reactivateModal = $('#reactivateModal')
        const removeForm = $('#remove_form')
        const reactivateForm = $('#reactivate_form')
        const birthDayModal = $('#birthDayModal')
        const birthDayForm = $('#birthDayForm')

        $(document).ready(function(){
            let genderId = 0;
            let typeId = 0;
            let statusId = 3
            let baptizedState = 0
            let fromAge = 0
            let toAge = 0

            const clearBtn = $('#clearBtn')
            const filterBtn = $('#filterBtn')
            const filterStatus = $('#filter_status')
            const filterBaptized = $('#filter_baptized')
            const filterFromAge = $('#filter_from_age')
            const filterToAge = $('#filter_to_age')
            const filterGender = $('#filter_gender')
            const typeFilter = $('#type_filter')

            const exportGender = $('#export_gender_id')
            const exportFromAge = $('#export_from_age')
            const exportToAge = $('#export_to_age')
            const exportMemberType = $('#export_member_type_id')
            const exportBaptized = $('#export_baptized')
            const exportStatus = $('#export_status')

            const dataTable = $("#datatable").DataTable({
                processing: true,
                scrollX:true,
                paging:true,
                language: datatableTrans,
                autoWidth:true,
                serverSide: true,
                lengthMenu: [5, 10, 25, 50, 75, 100 ],
                pageLength:10,
                ajax: {
                   url: '{!! route('members.index') !!}',
                    data: function(d){
                       d.gender_id = genderId;
                       d.member_type_id = typeId;
                       d.statusId = statusId;
                       d.baptized = baptizedState
                        d.from_age = fromAge
                        d.to_age = toAge
                    }
                },
                columns: [
                    { data: 'name_info', name: 'name'},
                    { data: 'age', name: 'age'},
                    { data: 'gender_info', name: 'gender_info', searchable: false,orderable: false},
                    { data: 'phone_number',name: 'Phone_number'},
                    { data: 'email',name: 'email'},
                    { data: 'status_info', name: 'status_info',searchable: false},
                    { data:'actions', name:'actions', orderable: false, searchable: false}
                ]
            });
            removeForm.submit(function($event){
               $event.preventDefault()
                let data = $(this).serialize()
                console.log(data)
                $.ajax({
                    url:'{!! route('members.endMembership') !!}',
                    method:'post',
                    data:data,
                    complete: function({status, responseJSON}){
                        if(status === 200) {
                            const {message} = responseJSON
                            toastr.info(message, '{!! trans('common.success_label') !!}')
                            removeModal.modal('hide')
                            clearForms()
                            dataTable.ajax.reload()
                        }
                    }
                })
            });
            reactivateForm.submit(function($event){
               $event.preventDefault()
                let data = $(this).serialize()
                $.ajax({
                    url:'{!! route('members.reactivateMembership') !!}',
                    method:'post',
                    data:data,
                    complete: function(xhr){
                        const {status, responseJSON} = xhr
                        if(status === 200) {
                            const {message} = responseJSON
                            toastr.info(message, '{!! trans('common.success_label') !!}')
                            reactivateModal.modal('hide')
                            dataTable.ajax.reload()
                        }
                    }
                })
            });
            birthDayForm.submit(function (event) {
                //event.preventDefault()
                let data  = $(this).serialize()
                console.log(data)
                birthDayModal.modal('hide')
            })


            filterGender.on('change',function(){
                let value = $(this).val()
                genderId = value;
                exportGender.val(value)
            });
            filterStatus.on('change',function(){
                let value = $(this).val()
                statusId = value;
                exportStatus.val(value)
            });
            typeFilter.on('change',function(){
                let value = $(this).val()
                typeId = value;
                exportMemberType.val(value)
            });

            filterBtn.on('click',function(){
                dataTable.ajax.reload()
                console.log('clicked')
            })
            clearBtn.on('click',function($event){
                genderId = 0
                typeId = 0
                baptizedState = 0
                fromAge = 0
                toAge = 0
                exportFromAge.val(null)
                exportToAge.val(null)
                exportGender.val(null)
                exportMemberType.val(null)
                exportBaptized.val(null)
                exportStatus.val(null)
                statusId = null
                filterStatus.val(3)
                filterBaptized.val(0)
                filterGender.val(0)
                typeFilter.val(0)
                dataTable.ajax.reload()
            })

            filterBaptized.on('change', function () {
                let value = $(this).val()
                baptizedState = value
                exportBaptized.val(value)
            })
            filterFromAge.on('change',function(){
                let value = $(this).val()
                fromAge = value
                exportFromAge.val(value)
            })
            filterToAge.on('change',function(){
                let value = $(this).val()
                toAge = value
                exportToAge.val(value)
            })

            $(".modal").on("hidden.bs.modal", function() {
                clearForms()
            })
        });

        function openUploadModal($event){
            let modal = $('#uploadModal')
            modal.modal('show')
        }

        function openDeleteModal($event){
            removeModal.modal('show')
            let id = $event.target.getAttribute('data-id')
            let name = $event.target.getAttribute('data-name')
            let image = $event.target.getAttribute('data-image')
            $('#member_image').attr('src',image)
            $('#confirm_member_name').html(name)
            $('#remove_member_id').val(id)
            $('#remove_date').daterangepicker({
                singleDatePicker:true,
                autoUpdateInput: true,
                showDropdowns: true,
                minYear: 1901,
                locale:datePickerTran
            })
        }

        function openReactivateModal($event){
            reactivateModal.modal('show')
            let id = $event.target.getAttribute('data-id')
            let name = $event.target.getAttribute('data-name')
            let image = $event.target.getAttribute('data-image')
            $('#reactivate_member_id').val(id)
            $('#reactivate_member_name').html(name)
            $('#reactivate_image').attr('src',image)
        }

        function openBirthDayModal(event){
            $('#birth_day_export_start_date').daterangepicker({
                singleDatePicker:true,
                autoUpdateInput: true,
                startDate:moment().startOf('week').format('MM/DD/YYYY'),
                showDropdowns: true,
                minYear: 1901,
                locale:datePickerTran
            })
           $('#birth_day_export_end_date').daterangepicker({
                singleDatePicker:true,
                autoUpdateInput: true,
                showDropdowns: true,
               startDate:moment().endOf('week').format('MM/DD/YYYY'),
                minYear: 1901,
               locale:datePickerTran
            })
            birthDayModal.modal('show')
        }

        function clearForms(){
            $('#remove_reason').val(null)
        }
    </script>
@endsection
