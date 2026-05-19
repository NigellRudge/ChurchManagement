@extends('layout.admin')

@section('content')

    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title">
                        <span class="font-weight-bold text-lg">Event Types</span>
                    </div>
                    <a class="btn btn-teal font-weight-bold" href="#" onclick="openAddModal(event)">
                        Create Event Type
                        <i class="fas fa-plus ml-1"></i>
                    </a>
                </div>
                <div class="card">
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered table-hover display compact nowrap">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Repeated</th>
                                <th>Interval</th>
                                <th>Status</th>
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
                    <h5 class="modal-title" id="addModalLabel">New Event Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="add_form">
                    @csrf
                    <div class=" mt-2 pl-3 pr-3">
                        <div class="form-group">
                            <label for="name" class="text-dark">Name</label>
                            <input name="name" placeholder="name" id="name" type="text" class="form-control" />
                            <small id="nameError" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group mb-4">
                            <label for="code"  class="text-dark">Code</label>
                            <input name="code" id="code" placeholder="code" type="text" class="form-control" />
                            <small id="codeError" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group mb-4">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" rows="3" placeholder="optional description"></textarea>
                        </div>

                        <div class="form-row mb-2 pl-1">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="repeated" id="repeated" />
                                <label for="repeated" class="form-check-label font-weight-bold">Repeated</label>
                            </div>
                        </div>
                        <div class="form-group row mb-5 ml-1">
                            <label for="interval" class=" col-form-label">Interval</label>
                            <input id="interval" class="ml-3 pl-0 col-2 form-control text-center" type="number" step="1" min="0" max="7" name="Interval" value="0" disabled />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal">
                            <i class="fas fa-save"></i>
                            Save
                        </button>
                        <button type="button" class="btn btn-danger">
                            <i class="fas fa-ban"></i>
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
                    <h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="remove_form">
                    @csrf
                    <input type="hidden" name="remove_event_type_id" id="remove_event_type_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-4">
                                Are you sure you want to remove this Event Type: <div class="d-inline text-teal font-weight-bold" id="confirm_event_type"></div> ?
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal">Yes</button>
                        <button type="button" class="btn btn-danger">No</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="editModalLabel">Edit Member Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="edit_form">
                    <input type="hidden" id="edit_event_type_id" name="edit_event_type_id" />
                    @csrf
                    <div class=" mt-2 pl-3 pr-3">
                        <div class="form-group">
                            <label for="edit_name" class="text-dark">Name</label>
                            <input name="edit_name" placeholder="Event Name" id="edit_name" type="text" class="form-control" />
                            <small id="nameError" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group mb-4">
                            <label for="edit_code"  class="text-dark">Code</label>
                            <input name="edit_code" id="edit_code" placeholder="Event Code" type="text" class="form-control" />
                            <small id="codeError" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group mb-4">
                            <label for="edit_description">Description</label>
                            <textarea class="form-control"  name="edit_description" id="edit_description" rows="3" placeholder="optional description"></textarea>
                        </div>

                        <div class="form-row mb-2 pl-1">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="edit_repeated" id="edit_repeated" />
                                <label for="edit_repeated" class="form-check-label font-weight-bold">Repeated</label>
                            </div>
                        </div>
                        <div class="form-group row mb-5 ml-1">
                            <label for="edit_interval" class=" col-form-label">Interval</label>
                            <input id="edit_interval" class="ml-3 pl-0 col-2 form-control text-center" type="number" step="1" min="0" max="7" name="edit_interval" value="0" disabled />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal">
                            <i class="fas fa-save"></i>
                            Save
                        </button>
                        <button type="reset" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                            <i class="fas fa-ban"></i>
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_css')
    @include('shared.datatable_css')
    <style>
        .swal-modal {
            background-color: #eaeaea;
            border: 1px solid white;
        }
        .swal-title {
            color: #0f6848;
        }
        .swal-button {
            padding: 7px 19px;
            border-radius: 2px;
            background-color: #0f6848;
            font-size: 12px;
            border: 1px solid #045a01;
        }
        .swal-button:not([disabled]):hover {
            background-color: #02553b;
        }

        .page-item.active .page-link {
            color: #fff !important;
            background-color: #0f6848 !important;
            border-color: #0f6848 !important;
        }

        .page-link {
            color: #0f6848!important;
            background-color: #fff !important;
            border: 1px solid #dee2e6 !important;
        }

        .page-link:hover {
            color: #fff !important;
            background-color: #0f6848 !important;
            border-color: #0f6848 !important;
        }
    </style>
@endsection

@section('custom_js')
    @include('shared.datatable_js')
    <script src ="{{ asset('vendor/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('vendor/jqueryValidator/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('vendor/jqueryValidator/additional-methods.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            let dataTable = $("#datatable").DataTable({
                processing: true,
                language: datatableTrans,
                autoWidth:false,
                serverSide: true,
                ajax: '{!! route('event-type.index') !!}',
                columns: [
                    { data: 'name', name: 'Name' },
                    { data: 'code', name: 'Code' },
                    { data: 'description', name: 'description' },
                    { data: 'repeated', name: 'Repeated' },
                    { data: 'interval', name: 'interval' },
                    { data: 'status', name: 'Status'},
                    { data:'actions', name:'actions', orderable: false, searchable: false}
                ]
            });
            $('#repeated').on('change',function($event){
                $('#interval').prop('disabled',false);
            });

            let addForm = $('#add_form')
            addForm.validate({
                rules:{
                    name:{
                        required:true,
                        minlength:3,
                        maxlength:50
                    },
                    code: {
                        required:true,
                        maxlength:8
                    },
                },
                messages:{
                    name: "name must be longer than 3 characters",
                    code: "code must be shorter than 8 characters",
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            addForm.submit(function($event){
                $event.preventDefault();
                let data = addForm.serialize()
                $.ajax({
                    url: ' {!! route('event-type.addAjax') !!}',
                    method: 'post',
                    data: data,
                    complete: function (xhr,status) {
                        if(xhr.status === 201){
                            $('#addModal').modal('toggle')
                            dataTable.ajax.reload()
                            swal({
                                title: "Success",
                                text: "Type added successfully",
                            });
                        }
                        if(xhr.status === 422){
                            let errors = xhr.responseJSON.errors
                            console.log(errors)
                            if(errors.code !== null && errors.code !== undefined){
                                $('#codeError').html(errors.code)
                                $("input[name='code']").addClass('is-invalid')
                            }
                            if(errors.name !== null && errors.name!== undefined){
                                $('#nameError').html(errors.name)
                                $("input[name='name']").addClass('is-invalid')
                            }
                        }
                    }
                })

            });

            let deleteForm = $('#remove_form')
            deleteForm.submit(function($event){
                $event.preventDefault()
                let data = deleteForm.serialize()
                console.log(data);
                $.ajax({
                    url: '{!! route('event-type.destroyAjax') !!}',
                    method: 'post',
                    data: data,
                    success: function(data){
                        console.log(data)
                    },
                    error: function(error){
                        console.log(error)
                    },
                    complete: function(xhr,data){
                        if(xhr.status === 201){
                            $('#removeModal').modal('hide')
                            dataTable.ajax.reload()
                            swal({
                                title: "Success",
                                text: "District Removed"
                            });
                        }
                    }
                })

            })

            let editForm = $('#edit_form')
            editForm.validate({
                rules:{
                    name:{
                        required:true,
                        minlength:3,
                        maxlength:50
                    },
                    code: {
                        required:true,
                        maxlength:8
                    },
                },
                messages:{
                    name: "name must be longer than 3 characters",
                    code: "code must be shorter than 8 characters",
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })

            editForm.submit(function($event){
                $event.preventDefault();
                let data = editForm.serialize()
                console.log(data)
                $.ajax({
                    url: ' {!! route('event-type.updateAjax') !!}',
                    method: 'post',
                    data: data,
                    complete: function (xhr,status) {
                        //console.log(xhr)
                        if(xhr.status === 201){
                            console.log(xhr)
                            $('#editModal').modal('hide')
                            dataTable.ajax.reload()
                            swal({
                                title: "Success",
                                text: "District updated successfully",
                            });
                        }
                        if(xhr.status === 422){
                            let errors = xhr.responseJSON.errors
                            console.log(errors)
                            if(errors.code !== null && errors.code !== undefined){
                                $('#codeError').html(errors.code)
                                $("input[name='code']").addClass('is-invalid')
                            }
                            if(errors.name !== null && errors.name!== undefined){
                                $('#nameError').html(errors.name)
                                $("input[name='name']").addClass('is-invalid')
                            }
                        }
                    }
                })

            });
        });

        function openAddModal($event){
            $event.preventDefault()
            let addModal = $('#addModal')
            addModal.modal('show')
        }
        function openRemoveModal($event){
            $event.preventDefault()
            let removeModal = $('#removeModal')
            removeModal.modal('show')
            let eventTypeName = $event.target.getAttribute('data-name')
            let eventTypeId = $event.target.getAttribute('data-id')
            $('#confirm_event_type').html( `${eventTypeName}`);
            $('input[name="remove_event_type_id"]').val(eventTypeId.toString());
        }
        function openEditModal($event){
            let editName = $("input[id='edit_name']")
            let editCode = $("input[id='edit_code']")
            let editActive = $("input[id='edit_active']")
            let editDescription = $("textarea[id='edit_description']")
            let editRepeated = $("input[id='edit_repeated']")
            let editInterval = $("input[name='edit_interval']");
            $event.preventDefault()
            let addModal = $('#editModal')
            addModal.modal('show')
            let typeId = parseInt($event.target.getAttribute('data-id'))
            //console.log(typeId)
            $("input[name='edit_event_type_id']").val(typeId)
            let data = {
                "_token": '{!! csrf_token() !!}',
                "edit_event_type_id": typeId
            }
            console.log(data)
            $.ajax({
                url: '{!! route('event-type.getByIdAjax') !!}',
                method:'post',
                data:data,
                complete: function(xhr){
                    let data = xhr.responseJSON.event_type
                    //console.log(data)
                    console.log(xhr.status);
                    if(xhr.status === 201){
                        console.log(`name: ${data.name}`)
                        console.log(`code: ${data.code}`)
                        console.log(`description: ${data.description}`)
                        editName.val(data.name)
                        editCode.val(data.code)
                        editDescription.val(data.description)
                        if(data.repeated.toLowerCase() === 'yes'){
                            editInterval.prop('disabled',false);
                            editRepeated.prop('checked',true);
                        }
                        data.status.toLowerCase() === 'active' ? editActive.prop('checked',true): editActive.prop('checked',false)
                    }
                }
            })
        }
    </script>
@endsection
