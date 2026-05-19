@extends('layout.admin')

@section('content')
<div class="row">
    <div class="container justify-content-center col">
        <div class="card">
            <div class="card-header d-flex bg-white justify-content-between">
                <div class="">
                    <span class="font-weight-bold text-lg text-dark">{{trans('common.districts_label')}}</span>
                </div>
                <a class="btn btn-teal font-weight-bold" href="#" onclick="openAddModal(event)">
                    {{trans('common.add_district_label')}}
                    <i class="fas fa-plus ml-1"></i>
                </a>
            </div>
            <div class="card-body">
                <table id="datatable" class="table table-bordered display compact nowrap">
                    <thead>
                    <tr>
                        <th>{{trans('common.name_label')}}</th>
                        <th>{{trans('common.code_label')}}</th>
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

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-teal text-light">
                <h5 class="modal-title font-weight-bold" id="addModalLabel">{{trans('common.add_district_label')}}</h5>
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
                                <label for="name" class="text-dark font-weight-bold">{{trans('common.name_label')}}<span class="text-danger">*</span></label>
                                <input name="name" autocomplete="false" placeholder="Paramaribo" id="name" type="text" class="form-control" />
                                <div id="nameError" class="customError"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-4">
                                <label for="code"  class="text-dark font-weight-bold">{{trans('common.code_label')}}<span class="text-danger">*</span></label>
                                <input name="code" id="code" placeholder="PARBO" type="text" class="form-control" />
                                <div id="codeError" class="customError"></div>
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
                <button type="button" class="btn btn-danger">
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
                <h5 class="modal-title" id="exampleModalLabel">{{trans('common.confirm_label')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-light">&times;</span>
                </button>
            </div>
            <form method="post" action="#" id="removeForm">
                @csrf
                <input type="hidden" name="remove_district_id" id="remove_district_id">
                <div class="modal-body">
                    <div class="d-flex flex-row align-baseline">
                        <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                            <i class="far fa-question-circle"></i>
                        </div>
                        <div class="pt-2">
                           {{trans('common.confirm_remove_district_label')}}: <div class="d-inline text-teal font-weight-bold" id="confirm_district"></div> ?
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">
                        <span class="mr-1"><i class="fa fa-trash"></i></span>
                        {{trans('common.yes_label')}}
                    </button>
                    <button type="button" class="btn btn-secondary">
                        {{trans('common.no_label')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-teal text-light">
                <h5 class="modal-title font-weight-bold" id="editModalLabel">{{trans('common.edit_district_label')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-light">&times;</span>
                </button>
            </div>
            <div class=" mt-2 pl-3 pr-3">
                <form method="post" action="#" id="editForm">
                    <input type="hidden" id="edit_district_id" name="edit_district_id" />
                    @csrf
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label for="edit_name" class="text-dark font-weight-bold">{{trans('common.name_label')}}<span class="text-danger">*</span></label>
                                <input name="edit_name" autocomplete="false" id="edit_name" type="text" class="form-control" />
                                <div id="editNameError" class="customError"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-4">
                                <label for="edit_code"  class="text-dark font-weight-bold">{{trans('common.code_label')}}<span class="text-danger">*</span></label>
                                <input name="edit_code" id="edit_code"  type="text" class="form-control" />
                                <div id="editCodeError" class="customError"></div>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            <div class="modal-footer">
                <button onclick="submitEditForm()" class="btn btn-teal">
                    <i class="fas fa-save"></i>
                    {{trans('common.save_label')}}
                </button>
                <button type="reset" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-ban"></i>
                    {{trans('common.cancel_label')}}
                </button>
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

        const addForm = $('#addForm')
        const deleteForm = $('#removeForm')
        const editForm = $('#editForm')
        const dataTable = $("#datatable").DataTable({
            processing: true,
            language: datatableTrans,
            autoWidth:false,
            serverSide: true,
            lengthMenu: [10, 25, 50, 75, 100 ],
            pageLength:10,
            ajax: '{!! route('district.index') !!}',
            columns: [
                { data: 'name', name: 'Name' },
                { data: 'code', name: 'Code' },
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ]
        });
        $(document).ready(function(){




            $(".modal").on("hidden.bs.modal", function() {
                clearForm('addForm')
                clearForm('editForm')
            });


            deleteForm.submit(function($event){
                $event.preventDefault()
                let data = deleteForm.serialize()
                $.ajax({
                    url: '{!! route('district.destroyAjax') !!}',
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
                            let message = xhr.responseJSON.message
                            dataTable.ajax.reload()
                            toastr.warning(message,'{{trans('common.success_label')}}')
                        }
                    }
                })

            })


            {{--editForm.validate({--}}
            {{--    rules:{--}}
            {{--        edit_name:{--}}
            {{--            required:true,--}}
            {{--            minlength:3,--}}
            {{--            maxlength:50--}}
            {{--        },--}}
            {{--        edit_code: {--}}
            {{--            required:true,--}}
            {{--            maxlength:8--}}
            {{--        }--}}
            {{--    },--}}
            {{--    messages:{--}}
            {{--        edit_name: "name must be longer than 3 characters",--}}
            {{--        edit_code: "code must be shorter than 8 characters",--}}
            {{--    },--}}
            {{--    errorClass: 'is-invalid',--}}
            {{--    validClass: 'is-valid',--}}
            {{--})--}}
            {{--editForm.submit(function($event){--}}
            {{--    $event.preventDefault();--}}
            {{--    let data = editForm.serialize()--}}
            {{--    console.log(data)--}}
            {{--    $.ajax({--}}
            {{--        url: ' {!! route('district.updateAjax') !!}',--}}
            {{--        method: 'post',--}}
            {{--        data: data,--}}
            {{--        complete: function (xhr,status) {--}}
            {{--            //console.log(xhr)--}}
            {{--            if(xhr.status === 201){--}}
            {{--                $('#editModal').modal('hide')--}}
            {{--                dataTable.ajax.reload()--}}
            {{--                let message = xhr.responseJSON.message--}}
            {{--                toastr.warning(message,'{{trans('common.success_label')}}')--}}
            {{--            }--}}
            {{--            if(xhr.status === 422){--}}
            {{--                let errors = xhr.responseJSON.errors--}}
            {{--                console.log(errors)--}}
            {{--                if(errors.code !== null && errors.code !== undefined){--}}
            {{--                    $('#codeError').html(errors.code)--}}
            {{--                    $("input[name='code']").addClass('is-invalid')--}}
            {{--                }--}}
            {{--                if(errors.name !== null && errors.name!== undefined){--}}
            {{--                    $('#nameError').html(errors.name)--}}
            {{--                    $("input[name='name']").addClass('is-invalid')--}}
            {{--                }--}}
            {{--            }--}}
            {{--        }--}}
            {{--    })--}}

            {{--});--}}
        });

        function submitAddForm(){
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
                    name: {
                        required: '{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 3]) !!}',
                        maxlength:'{!! trans('custom_validation.min_length',['max' => 50]) !!}',
                    },
                    code: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        maxlength:'{!! trans('custom_validation.max_length',['max' => 8]) !!}',
                    },
                },
                errorPlacement: function(error, element){
                  switch(element.attr('name')){
                      case 'name':
                          $('#nameError').html(error)
                          break
                      case 'code':
                          $('#codeError').html(error)
                          break
                  }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(addForm.valid()){
                const data = addForm.serialize()
                $.ajax({
                    url: ' {!! route('district.addJson') !!}',
                    method: 'post',
                    data: data,
                    complete: function ({status,responseJSON }) {
                        if(status === 201){
                            $('#addModal').modal('toggle')
                            let {message} = responseJSON
                            dataTable.ajax.reload()
                            toastr.success(message, '{{trans('common.success_label')}}');
                        }
                    }
                })

            }
        }

        function submitEditForm(){
            editForm.validate({
                rules:{
                    edit_name:{
                        required:true,
                        minlength:3,
                        maxlength:50
                    },
                    edit_code: {
                        required:true,
                        maxlength:8
                    },
                },
                messages:{
                    edit_name: {
                        required: '{!! trans('custom_validation.required_field') !!}',
                        minlength: '{!! trans('custom_validation.min_length',['min' => 3]) !!}',
                        maxlength:'{!! trans('custom_validation.min_length',['max' => 50]) !!}',
                    },
                    edit_code: {
                        required:'{!! trans('custom_validation.required_field') !!}',
                        maxlength:'{!! trans('custom_validation.max_length',['max' => 8]) !!}',
                    },
                },
                errorPlacement: function(error, element){
                    switch(element.attr('name')){
                        case 'edit_name':
                            $('#editNameError').html(error)
                            break
                        case 'edit_code':
                            $('#editCodeError').html(error)
                            break
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(editForm.valid()){
                const data = editForm.serialize()
                $.ajax({
                    url: ' {!! route('district.updateAjax') !!}',
                    method: 'post',
                    data: data,
                    complete: function ({status,responseJSON }) {
                        if(status === 201){
                            $('#editModal').modal('hide')
                            dataTable.ajax.reload()
                            let {message} = responseJSON
                            toastr.warning(message,'{{trans('common.success_label')}}')
                        }
                    }
                })
            }
        }

        function openRemoveModal($event){
            $event.preventDefault()
            let removeModal = $('#removeModal')
            removeModal.modal('show')
            let currencyName = $event.target.getAttribute('data-name')
            let districtId = $event.target.getAttribute('data-id')
            $('#confirm_district').html( `${currencyName}`);
            $('input[name="remove_district_id"]').val(districtId.toString());
        }

        function openAddModal($event){
            $event.preventDefault()
            let addModal = $('#addModal')
            addModal.modal('show')
        }

        function openEditModal($event){
            let editName = $("input[id='edit_name']")
            let editCode = $("input[id='edit_code']")
            let editActive = $("input[id='edit_active']")
            $event.preventDefault()
            let addModal = $('#editModal')
            addModal.modal('show')
            let districtName = $event.target.getAttribute('data-name')
            let districtId = parseInt($event.target.getAttribute('data-id'))
            console.log(districtId)
            $("input[name='edit_district_id']").val(districtId)
            let data = {
                "_token": '{!! csrf_token() !!}',
                "districtId": districtId
            }
            console.log(data)
            $.ajax({
                url: '{!! route('district.getByIdAjax') !!}',
                method:'post',
                data:data,
                complete: function({status, responseJSON}){
                    const {district} = responseJSON
                    if(status === 201){
                        editName.val(district.name)
                        editCode.val(district.code)
                    }
                }
            })
        }
    </script>
@endsection
