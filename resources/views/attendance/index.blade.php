    @extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between mb-2">
                    <div class="font-weight-bold text-lg text-dark">
                        {{trans('common.attendance_sheets_label')}}
                    </div>
                    <div>
                        <button id="addButton" disabled  class="btn btn-teal text-light font-weight-bold">
                            {{trans('common.add_sheet_label')}}
                            <i class="ml-1 fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="fix-topbar">
                        <table id="datatable" class="table table-bordered display compact nowrap">
                            <thead>
                                <tr class="text-dark">
                                    <th>Id</th>
                                    <th>{{trans('common.name_label')}}</th>
                                    <th>
                                        <span class="mr-1"><i class="fa fa-calendar text-teal"></i></span>
                                        {{trans('common.date_label')}}</th>
                                    <th>
                                        <span class="mr-1"><i class="fa fa-users text-teal"></i></span>
                                        {{trans('common.num_mems_present')}}
                                    </th>
                                    <th style="width: 120px;"></th>
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
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.add_sheet_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="font-weight-bold text-light">&times;</span>
                    </button>
                </div>
                    <div class="pl-4 pr-4 mt-2 mb-4">
                        <form method="post" action="#" id="add_form">
                            @csrf
                        <div class="form-row">
                            <div class="col">
                                <div class="from-group">
                                    <label for="date" class="font-weight-bolder">{{trans('common.date_label')}}<span class="text-danger font-weight-normal">*</span></label>
                                    <select id="date" name="date" class="form-control"></select>
                                    <span id="dateError"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col">
                                <div class="from-group">
                                    <label for="name" class="font-weight-bolder">{{trans('common.name_label')}}</label>
                                    <input type="text" id="name" name="name" class="form-control" disabled />
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button onclick="submitAddForm(event)" class="btn btn-teal font-weight-bold text-light">
                            <span class="mr-1"><i class="fa fa-save"></i></span>
                            {{trans('common.save_label')}}
                        </button>
                        <button type="button" data-dismiss="modal" class="btn btn-danger font-weight-bold text-light">
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
                <form method="post" action="#" id="remove_form">
                    @csrf
                    <input type="hidden" name="remove_sheet_id" id="remove_sheet_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-1">
                                {{trans('common.remove_sheet_label')}}:<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_sheet"></div> ?
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
        const addButton =$('#addButton')
        const addModal = $('#addModal')
        const removeModal = $('#removeModal')
        const dateInput = $('#date')
        const nameInput = $('#name')
        const addForm = $('#add_form');
        const dataTable = $("#datatable").DataTable({
            processing: true,
            language: datatableTrans,
            autoWidth:false,
            serverSide: true,
            "lengthMenu": [10, 25, 50, 75, 100 ],
            pageLength:10,
            ajax: '{!! route('attendance.index') !!}',
            columns: [
                {data: 'id', name: 'id',searchable: false},
                {data: 'name', name: 'name'},
                {data: 'date', name: 'date'},
                {data: 'members_present', name: 'members_present',searchable: false},
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ]
        });
        $(document).ready(function(){
            addButton.prop('disabled',false)
            addButton.on('click', function (event) {
                event.preventDefault()
                $('#name').val("")
                addModal.modal('show')
                let dateInput = $('#date').select2({
                    theme: 'bootstrap4',
                    ajax: {
                        url: '{!! route('attendance.getAvailableDates') !!}',
                        type: 'post',
                        data: function(params){
                            return {
                                _token: '{!! csrf_token() !!}',
                                name:params.term
                            }
                        },
                        dataType: 'json',
                        cache:true,
                        delay:200,
                        placeholder: 'Search dates',
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
            })
            dateInput.on('change',function(){
                let value = this.value;
                console.log(value)
                nameInput.val(`{!! trans('common.saturday') !!} ${value}`)
            })
            $(".modal").on("hidden.bs.modal", function() {
                clearForm('add_form',false)
                nameInput.attr('disabled',true)
                $('#dateError').html('')
            });

            let removeForm = $('#remove_form');
            removeForm.submit(function($event){
                $event.preventDefault();
                let data = removeForm.serialize();
                console.log(data)

                $.ajax({
                    url: '{!! route('attendance.destroySheet') !!}',
                    method: 'post',
                    data:data,
                    complete: function({status,responseJSON }){
                        if(status === 201){
                            let {message} = responseJSON
                            dataTable.ajax.reload()
                            toastr.warning(message,'{{trans('common.success_label')}}')
                            removeModal.modal('hide')
                        }
                    }
                })
            })
        });

        function openRemoveModal($event){
            $event.preventDefault()
            removeModal.modal('show')
            let sheetName = $event.target.getAttribute('data-name')
            let sheet_id = $event.target.getAttribute('data-id')
            $('#confirm_sheet').html( `${sheetName}`);
            $('#remove_sheet_id').val(sheet_id);
        }

        function submitAddForm(event){
            addForm.validate({
                rules:{
                    date:{
                        required:true,
                    },
                },
                messages:{
                    date: {
                        required:'{!! trans('custom_validation.select_option') !!}',
                    },
                },
                errorPlacement: function(error, element){
                    if(element.attr('name')){
                        $('#dateError').html(error)
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(addForm.valid()) {
                nameInput.attr('disabled', false)
                const data = addForm.serialize();
                console.log(data)

                $.ajax({
                    url: '{!! route('attendance.storeSheet') !!}',
                    method: 'post',
                    data: data,
                    complete: function ({status, responseJSON}) {
                        if (status === 201) {
                            let {message} = responseJSON
                            dataTable.ajax.reload()
                            toastr.success(message, '{{trans('common.success_label')}}')
                            addModal.modal('hide')
                        }
                    }
                })
            }
        }

    </script>
@endsection
