@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="d-flex card-header bg-white mb-2 justify-content-between">
                    <div class="card-title pt-1">
                        <span class="font-weight-bold text-lg text-dark">{{trans('common.infant_dedication_label')}}</span>
                    </div>
                    <div class="d-flex flex-row">
                        <form action="{{ route('dedication.export') }}" method="post">
                            @csrf
                            <input type="hidden" id="export_from_date" name="start_date" />
                            <input type="hidden" id="export_to_date" name="end_date" />
                            <button class="mr-2 btn btn-primary font-weight-bold text-light rounded font-weight-bold" disabled id="exportBtn" type="submit">
                                {{trans('common.export_to_excel_label')}}
                                <i class="ml-1 fas fa-file-excel"></i>
                            </button>
                        </form>
                        <a class="btn btn-teal font-weight-bold text-white pt-2" onClick="openAddModal(event)" href="#">
                            {{trans('common.dedicate_infant_label')}}
                            <i class="ml-1 fas fa-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row pl-2 mt-2 mb-3">
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
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="date_filter" class="col-form-label font-weight-bold">{{trans('common.date_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white">
                                                <i class="fa fa-calendar text-teal"></i>
                                            </div>
                                        </div>
                                        <input type="text" autocomplete="off" class="form-control" id="date_filter" name="date_filter">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-5 pl-2">
                        <div class="col">
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
                        <table id="datatable" class="table border-right border-left border-bottom display compact nowrap">
                            <thead>
                            <tr class="text-dark">
                                <th>{{trans('common.picture_label')}}</th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-user text-teal"></i></span>
                                    {{trans('common.name_label')}}
                                </th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-calendar text-teal"></i></span>
                                    {{trans('common.birth_date_label')}}
                                </th>
                                <th>
                                    {{trans('common.gender_label')}}
                                </th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-calendar-check text-teal"></i></span>
                                    {{trans('common.dedication_date_label')}}
                                </th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-female text-teal"></i></span>
                                    {{trans('common.mother_label')}}
                                </th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-male text-teal"></i></span>
                                    {{trans('common.father_label')}}
                                </th>
                                <th>{{trans('common.status_label')}}</th>
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
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.dedicate_infant_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class=" mt-2 pl-3 pr-3">
                    <form method="post" action="#" id="addForm">
                        @csrf
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="add_infant" class="text-dark mr-1 font-weight-bold">{{trans('common.infant_label')}}</label><span class="text-danger">*</span>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-baby-carriage text-teal"></i>
                                            </div>
                                        </div>
                                        <select name="infant_id" id="add_infant" type="text" class="form-control"></select>
                                    </div>
                                    <div id="infant_error" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group mb-4">
                                    <label for="mother_id"  class="text-dark font-weight-bold">{{trans('common.mother_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-female"></i>
                                            </div>
                                        </div>
                                        <input name="mother_id" id="mother_id" type="text" class="form-control" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group mb-4">
                                    <label for="father_id"  class="text-dark font-weight-bold">{{trans('common.father_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-male"></i>
                                            </div>
                                        </div>
                                        <input name="father_id" id="father_id" type="text" class="form-control" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group mb-4">
                                    <label for="add_dedication_date"  class="text-dark font-weight-bold">{{trans('common.dedication_date_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                        <input name="dedication_date" id="add_dedication_date" type="text" class="form-control"/>
                                    </div>
                                    <div id="dedicate_date_error" class="customError"></div>
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
                    <h5 class="modal-title" id="editModalLabel">{{trans('common.edit_information_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="editForm">
                    @csrf
                    <input type="hidden" id="edit_id" name="id">
                    <div class=" mt-2 pl-3 pr-3">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_infant" class="text-dark font-weight-bold">{{trans('common.infant_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-baby-carriage"></i>
                                            </div>
                                        </div>
                                        <select name="infant_id" id="edit_infant" readonly type="text" class="form-control"></select>
                                    </div>
                                    <div id="infant_error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group mb-4">
                                    <label for="edit_mother_id"  class="text-dark font-weight-bold">{{trans('common.mother_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-female"></i>
                                            </div>
                                        </div>
                                        <input name="mother_id" id="edit_mother_id" type="text" class="form-control" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group mb-4">
                                    <label for="edit_father_id"  class="text-dark font-weight-bold">{{trans('common.father_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-male"></i>
                                            </div>
                                        </div>
                                        <input name="father_id" id="edit_father_id" type="text" class="form-control" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group mb-4">
                                    <label for="edit_dedication_date" class="text-dark font-weight-bold">{{trans('common.dedication_date_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                        <input name="dedication_date" id="edit_dedication_date" type="text" class="form-control"/>
                                    </div>
                                    <div id="edit_dedication_date_error"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal">
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
                    <input type="hidden" name="infant_id"  id="remove_infant_id" value=""/>

                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-2">
                                {{trans('common.confirm_delete_dedicated_infant_label')}} <div class="d-inline text-teal font-weight-bold" id="confirm_infant"></div> ?
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

    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="detailModalLabel">{{trans('common.infant_details_label')}}</h5>
                    <button type="button" class="close bg-teal border-none" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-2">
                    <div class="row">
                        <div class="col d-flex flex-column align-items-center">
                            <img alt="infant_image" id="display_image" src="{{asset('storage/placeholder-male.jpg')}}" style="object-fit: cover; border-radius: 12px" height="140" width="100" />
                        </div>
                    </div>
                    <div class="row mt-2 p-4">
                        <div class="col">
                            <div class="text-dark font-weight-bold">{{trans('common.name_label')}}</div>
                            <div class="" id="display_name">Deyon Rudge</div>
                        </div>
                        <div class="col">
                            <div class="d-flex flex-row">
                                <span class="mr-1"><i class="fa fa-calendar text-teal"></i></span>
                                <div class="text-dark font-weight-bold">{{trans('common.birth_date_label')}}:</div>
                            </div>
                            <div class="" id="display_birth_date">30 august 2021</div>
                        </div>
                        <div class="col">
                            <div class="d-flex flex-row">
                                <span class="mr-1"><i class="fa fa-male text-teal" id="gender_icon"></i></span>
                                <div class="text-dark font-weight-bold">{{trans('common.gender_label')}}:</div>
                            </div>
                            <div class="" id="display_gender">Male</div>
                        </div>
                    </div>
                    <div class="row mt-2 p-4">
                        <div class="col">
                            <div class="d-flex flex-row">
                                <span class="mr-1"><i class="fa fa-calendar text-teal"></i></span>
                                <div class="text-dark font-weight-bold">{{trans('common.dedication_date_label')}}:</div>
                            </div>
                            <div class="" id="display_dedication_date">30 august 2021</div>
                        </div>
                        <div class="col">
                            <div class="text-dark font-weight-bold">{{trans('common.mother_label')}}:</div>
                            <div class="" id="display_mother">Ruth kramp</div>
                        </div>
                        <div class="col">
                            <div class="d-flex flex-row">
                                <div class="text-dark font-weight-bold">{{trans('common.father_label')}}:</div>
                            </div>
                            <div class="" id="display_father">Eric Rudge</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal"  class="btn btn-secondary">{{trans('common.close_label')}}</button>
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
        let fromDate = null;
        let toDate = null;

        const addModal = $('#addModal')
        const addForm = $('#addForm')
        const removeModal = $('#removeModal')
        const removeForm = $('#removeForm')
        const editModal = $('#editModal')
        const editForm = $('#editForm')
        const detailModal = $('#detailModal')

        const filterGender = $('#filter_gender')
        const clearBtn = $('#clearBtn')
        const filterBtn = $('#filterBtn')
        const dateFilterEl = $('#date_filter')

        const exportFromDate = $('#export_from_date')
        const exportToDate = $('#export_to_date')

        const dataTable = $("#datatable").DataTable({
            processing: true,
            language: datatableTrans,
            autoWidth:false,
            serverSide: true,
            lengthMenu: [10, 25, 50, 75, 100 ],
            pageLength:10,
            ajax: {
                url: '{!! route('dedication.index') !!}',
                data: function(d){
                    d.gender_id = genderId
                    d.start_date = fromDate
                    d.end_date = toDate
                }
            },
            columns: [
                { data: 'image_info', name: 'image_info', orderable: false, searchable: false },
                { data: 'name', name: 'Name' },
                { data: 'birth_date', name: 'birth_date',searchable: false },
                { data: 'gender_info', name: 'gender_info', searchable: false,orderable: false},
                { data: 'dedication_date',name: 'dedication_date'},
                { data: 'mother', name: 'mother', orderable: false, searchable: false },
                { data: 'father', name: 'father', orderable: false, searchable: false },
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ],
            initComplete:function (settings,json) {
                onReloadComplete(json)
            }
        });
        $(document).ready(function(){
            filterGender.on('change',function(){
                genderId = this.value;

            });

            filterBtn.on('click',function(){
                dataTable.ajax.reload(onReloadComplete)
            })
            dateFilterEl.daterangepicker({
                singleDatePicker:false,
                autoUpdateInput: false,
                startDate: new Date(),
                showDropdowns: true,
                minYear: 1901,
                locale:datePickerTran,
                applyButtonClasses:'btn btn-teal btn-sm',
                cancelButtonClasses:'btn btn-danger btn-sm'
            }).on('apply.daterangepicker',function(ev, picker){
                let start = picker.startDate.format('DD-MM-YYYY')
                let end = picker.endDate.format('DD-MM-YYYY')
                start === end ? $(this).val(`${start}`): $(this).val(`${start} - ${end}`);
                exportFromDate.val(start)
                fromDate = start;
                exportToDate.val(end)
                toDate = end;
            })

            clearBtn.on('click',function($event){
                dateFilterEl.val('')
                fromDate = null
                toDate = null
                exportFromDate.val(null)
                exportToDate.val(null)
                genderId = 0
                filterGender.val(0)
                dataTable.ajax.reload(onReloadComplete)
            })

            removeForm.submit(function($event){
                $event.preventDefault();
                const data = $(this).serialize()
                $.ajax({
                    url:'{!! route('dedication.destroy') !!}',
                    method:'delete',
                    data:data,
                    complete: function({status, responseJSON}){
                        const {message} = responseJSON
                        if(status === 200){
                            toastr.warning(message, '{{trans('common.success_label')}}')
                            removeModal.modal('hide')
                            dataTable.ajax.reload(onReloadComplete)
                        }
                    }
                })
            })


            $(".modal").on("hidden.bs.modal", function() {
                clearForm('addForm', false)
                clearForm('editForm',false)
            });

        });

        function submitAddForm(){
            addForm.validate({
                rules:{
                    infant_id:{
                        required:true,
                    },
                    dedication_date: {
                        required:true,
                        date: true
                    }
                },
                messages:{
                    infant_id: {
                        required:  "{!! trans('custom_validation.select_option') !!}",
                    },
                    dedication_date: {
                        required:'{!! trans('custom_validation.date_required') !!}',
                        date: '{!! trans('custom_validation.valid_date') !!}'
                    }
                },
                errorPlacement: function(error, element){
                    switch (element.attr('name')) {
                        case 'infant_id':
                            error.appendTo('#infant_error')
                            break;
                        case 'dedication_date':
                            error.appendTo('#dedication_date_error')
                            break;
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(addForm.valid()){
                const data = addForm.serialize()
                console.log(data)
                $.ajax({
                    url:'{!! route('dedication.store') !!}',
                    method:'post',
                    data:data,
                    complete: function({status,responseJSON}){
                        if(status === 200){
                            let message = responseJSON.message
                            toastr.success(message,'{!! trans('common.success_label') !!}' )
                            addModal.modal('hide')
                            dataTable.ajax.reload(onReloadComplete)
                        }
                    }
                })
            }
        }

        function submitEditForm(){
            editForm.validate({
                rules:{
                    infant_id:{
                        required:true,
                    },
                    dedication_date: {
                        required:true,
                        date: true
                    }
                },
                messages:{
                    infant_id: {
                        required:  "{!! trans('custom_validation.select_option') !!}",
                    },
                    dedication_date: {
                        required:'{!! trans('custom_validation.date_required') !!}',
                        date: '{!! trans('custom_validation.valid_date') !!}'
                    }
                },
                errorPlacement: function(error, element){
                    switch (element.attr('name')) {
                        case 'infant_id':
                            error.appendTo('#edit_infant_error')
                            break;
                        case 'dedication_date':
                            error.appendTo('#edit_dedication_date_error')
                            break;
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(editForm.valid()){
                const data = editForm.serialize()
                console.log(data)
                $.ajax({
                    url:'{!! route('dedication.update') !!}',
                    method:'put',
                    data:data,
                    complete: function({status,responseJSON}){
                        if(status === 200){
                            let message = responseJSON.message
                            toastr.success(message,'{!! trans('common.success_label') !!}' )
                            editModal.modal('hide')
                            dataTable.ajax.reload(onReloadComplete)
                        }
                    }
                })
            }
        }

        function openUploadModal($event){
            let modal = $('#uploadModal')
            modal.modal('show')
        }

        function openAddModal($event){
            addModal.modal('show')
            const infant = $('#add_infant')
            infant.select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('dedication.notDedicated') !!}',
                    type: 'post',
                    data: function(params){
                        return {
                            _token: '{!! csrf_token() !!}',
                            name: params.term,
                            infant:true,
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
            infant.on('change',function (event) {
                event.preventDefault()
                let value = $(this).val()
                if(value !== null && value !== 0){
                    $.ajax({
                        url: '{!! route('members.getParents') !!}',
                        method: 'post',
                        data: {
                            _token: '{!!  csrf_token() !!}',
                            infant_id: value
                        },
                        complete: function(xhr){
                            const {status, responseJSON} = xhr
                            const {mother, father} = responseJSON
                            if(status === 200){
                                console.log(responseJSON)
                                $('#mother_id').val(mother.name)
                                $('#father_id').val(father.name)
                            }
                        }

                    })
                }
            })
            $('#add_dedication_date').daterangepicker({
                singleDatePicker:true,
                autoUpdateInput: true,
                startDate: new Date(),
                showDropdowns: true,
                minYear: 1901,
                locale:datePickerTran,
                applyButtonClasses:'btn btn-teal btn-sm',
                cancelButtonClasses:'btn btn-danger btn-sm'
            })
        }

        function openRemoveModal($event){
            removeModal.modal('show')
            const id = $event.target.getAttribute('data-id')
            const name = $event.target.getAttribute('data-name')
            $('#remove_infant_id').val(id)
            $('#confirm_infant').html(name)

        }

        function openDetailModal($event){
            let id = $event.target.getAttribute('data-id')
            console.log(id)
            $.ajax({
                url:'{!! route('dedication.getById') !!}',
                method: 'post',
                data: {
                    _token:'{!! csrf_token() !!}',
                    id: id
                },
                complete: function({status,responseJSON}){
                    if(status === 200){
                        let item = responseJSON.item
                        $('#display_mother').html(item.mother)
                        $('#display_father').html(item.father)
                        $('#display_gender').html(item.gender)
                        $('#display_birth_date').html(item.birth_date)
                        $('#display_dedication_date').html(item.dedication_date)
                        $('#display_image').attr('src',item.image)
                        detailModal.modal('show')
                    }
                }
            })
        }

        function openEditModal($event){
            let id = $event.target.getAttribute('data-id')
            $.ajax({
                url:'{!! route('dedication.getById') !!}',
                method: 'post',
                data: {
                    _token:'{!! csrf_token() !!}',
                    id: id
                },
                complete: function({status,responseJSON}){
                    if(status === 200){
                        let item = responseJSON.item
                        console.log(item)
                        $('#edit_id').val(item.id)
                        $('#edit_infant').val(item.name)
                        $('#edit_mother_id').val(item.mother)
                        $('#edit_father_id').val(item.father)
                        $('#edit_dedication_date').daterangepicker({
                            singleDatePicker:true,
                            autoUpdateInput: true,
                            startDate: item.dedication_date,
                            showDropdowns: true,
                            minYear: 1901,
                            locale:datePickerTran,
                            applyButtonClasses:'btn btn-teal btn-sm',
                            cancelButtonClasses:'btn btn-danger btn-sm'
                        })
                        editModal.modal('show')
                    }
                }
            })
        }
    </script>
@endsection
