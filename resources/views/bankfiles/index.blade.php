@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-white d-flex flex-row justify-content-between">
                    <div class="">
                        <h5 class="text-dark font-weight-bold">{{trans('common.bank_files')}}</h5>
                    </div>
                    <div class="d-flex flex-row">
                        <div class="mr-1">
                            <form action="" id="exportForm">
                                @csrf
                                <button class="btn btn-info" id="exportBtn">
                                    <i class="fa fa-excel mr-1"></i>
                                    {{trans('common.export_to_excel_label')}}
                                </button>
                            </form>
                        </div>
                        <button class="btn btn-teal" onclick="uploadFile(event)">
                            <i class="fa fa-plus mr-1"></i>
                            {{trans('common.upload_bank_file')}}
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-4 col">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="type_filter" class="col-form-label font-weight-bold">
                                        <i class="fa fa-piggy-bank text-teal mr-1"></i>
                                        {{trans('common.bank_file_type')}}
                                    </label>
                                    <select type="text" class="form-control" id="type_filter" name="type">
                                        <option value="0">{{ trans('common.select_option') }}</option>
                                        @foreach($data['bank_file_types'] as $bankFileType)
                                            <option value="{{ $bankFileType->id }}"> {{ $bankFileType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-3 col">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="status_filer" class="col-form-label font-weight-bold">
                                        {{trans('common.status_label')}}
                                    </label>
                                    <select type="text" class="form-control" id="status_filer" name="status">
                                        <option value="0">{{ trans('common.select_option') }}</option>
                                        <option value="{{config('constants.BANK_FILE_STATUS_PENDING')}}">{{ trans('common.pending') }}</option>
                                        <option value="{{config('constants.BANK_FILE_STATUS_MATCHING')}}">{{ trans('common.matching') }}</option>
                                        <option value="{{config('constants.BANK_FILE_STATUS_MATCHED')}}">{{ trans('common.matched') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-5 pl-2">
                        <div class="col">
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
                    <div class="row">
                        <div class="col">
                            <div class="">
                                <table id="datatable" class="table table-bordered table-striped display compact nowrap">
                                    <thead>
                                    <tr>
                                        <th>
                                            <span class="mr-1"><i class="fa fa-file-alt text-teal"></i></span>
                                            {{trans('common.file_name')}}
                                        </th>
                                        <th>
                                            <span class="mr-1"><i class="fa fa-cog text-teal"></i></span>
                                            {{trans('common.bank_file_type')}}
                                        </th>
                                        <th>
                                            <span class="mr-1"><i class="fa fa-calendar text-teal"></i></span>
                                            {{trans('common.upload_date')}}
                                        </th>
                                        <th>
                                            {{trans('common.status_label')}}
                                        </th>
                                        <th>
                                            <span class="mr-1"><i class="fa fa-user text-teal"></i></span>
                                            {{trans('common.uploaded_by')}}
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
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.upload_bank_file')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" enctype="multipart/form-data" id="addForm">
                    @csrf
                    <div class=" mt-2 pl-3 pr-3">
                        <div class="form-row mb-2">
                            <div class="col">
                                <div class="form-group">
                                    <label for="add_file_name" class="font-weight-bold text-dark">{{trans('common.file_name')}}<span class="text-danger">*</span></label>
                                    <input type="text" name="file_name" id="add_file_name" class="form-control" />
                                    <div id="fileNameError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mb-2">
                            <div class="col">
                                <div class="form-group">
                                    <label for="add_file_type" class="font-weight-bold text-dark">{{trans('common.bank_file_type')}}<span class="text-danger">*</span></label>
                                    <select name="bank_file_type_id" id="add_bank_file_type_id" class="form-control">
                                        <option value="0">{{ trans('common.select_option') }}</option>
                                        @foreach($data['bank_file_types'] as $bankFileType)
                                            <option value="{{ $bankFileType->id }}"> {{ $bankFileType->name }}</option>
                                        @endforeach
                                    </select>
                                    <div id="typeError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mb-2">
                            <div class="col">
                                <div class="form-group">
                                    <label for="add_file" class="font-weight-bold text-dark">{{trans('common.file')}}<span class="text-danger">*</span></label>
                                    <input type="file" name="file" id="add_file" class="form-control" />
                                    <div id="fileError" class="customError"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal">
                            <i class="fas fa-save"></i>
                            {{trans('common.save_label')}}
                        </button>
                        <button class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-ban"></i>
                            {{trans('common.cancel_label')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        let status = 0
        let type = 0

        const typeFilterElement = $('#typeFilter')
        const statusFilterElement = $('#statusFilter')

        const filterBtn = $('#filterBtn')
        const clearBtn = $('#clearBtn')

        const addModal = $('#addModal')
        const addForm = $('#addForm')

        const editModal = $('#editModal')
        const editForm = $('#editForm')

        const removeModal = $('#removeModal')
        const removeForm = $('#removeForm')

        const dataTable = $('#datatable').DataTable({
            processing: true,
            language: datatableTrans,
            autoWidth:false,
            serverSide: true,
            lengthMenu: [10, 25, 50, 75, 100 ],
            pageLength:10,
            ajax: {
                url:'{!! route('bankfiles.index') !!}',
                data: function(d){
                    d.type = type
                    d.status = status
                },
            },
            columns: [
                { data: 'file_name', name: 'file_name' },
                { data: 'bank_file_type', name: 'bank_file_type' },
                { data: 'upload_date', name: 'upload_date' },
                { data: 'status_info', name: 'status' },
                { data: 'uploaded_by_info', name: 'uploaded_by' },
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ],
            initComplete:function (settings,json) {
                onReloadComplete(json)
            }
        })

        $(document).ready(function(){
            filterBtn.on('click',function($event){
                dataTable.ajax.reload(onReloadComplete)
            })
            clearBtn.on('click',function($event){
                dateFilterEl.val('')
                fromDate = null
                toDate = null
                currencyId = 0
                typeId = null
                memberId = null
                memberFilterEl.val(null).trigger('change');
                filterCurrency.val(0)
                filterType.val(0)
                dataTable.ajax.reload(onReloadComplete)

                exportFromDate.val(null)
                exportToDate.val(null)
                exportMember.val(null)
                exportCurrency.val(null)
                exportType.val(null)
            })

            addForm.submit(function($event){
                $event.preventDefault();
                addForm.validate({
                    rules:{
                        file_name:{
                            required:true,
                            minlength:5,
                            maxlength:80,
                        },
                        bank_file_type_id: {
                            required:true,
                            min:1,
                        },
                        file: {
                            required:true,
                        },
                    },
                    messages:{
                        bank_file_type_id:{
                            required:'{!! trans('custom_validation.select_option') !!}',
                            min: '{!! trans('custom_validation.required_field') !!}'
                        },
                        file: {
                            required:'{!! trans('custom_validation.required_file') !!}',
                        },
                        file_name: {
                            required:'{!! trans('custom_validation.required_field') !!}',
                            minlength: '{!! trans('custom_validation.min_length',['min' => 5]) !!}',
                            maxlength: '{!! trans('custom_validation.max_length',['max' => 80]) !!}',
                        }
                    },
                    errorPlacement: function (error, element) {
                        switch (element.attr('name')) {
                            case 'bank_file_type_id':
                                $('#typeError').html(error)
                                break;
                            case 'file':
                                $('#fileError').html(error)
                                break;
                            case 'file_name':
                                $('#fileNameError').html(error)
                                break;

                        }
                    },
                    errorClass: 'is-invalid',
                    validClass: 'is-valid',
                })
                if(addForm.valid()){
                    const data = new FormData(addForm);
                    $.ajax({
                        url: ' {!! route('bankfiles.store') !!}',
                        method: 'post',
                        processData: false,
                        contentType: false,
                        data: data,
                        complete: function ({status,responseJSON}) {
                            if(status === 200){
                                let message = responseJSON
                                addModal.modal('hide')
                                fileDataTable.ajax.reload()
                                toastr.success(message,'Success')
                            }
                        }
                    })
                }
            })
            $(".modal").on("hidden.bs.modal", function() {
                clearForm('addForm',false)
                clearForm('editForm',false)
            });
        })

        function uploadFile($event) {
            addModal.modal('show')
        }

        function deleteFile($event){

        }

        function editFile($event){

        }
    </script>
@endsection
