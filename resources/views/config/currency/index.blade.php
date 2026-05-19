@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-white d-flex flex-row justify-content-between">
                    <div class="card-title text-dark text-lg font-weight-bold">
                        {{trans('common.currency_label')}}
                    </div>
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-bordered display compact nowrap">
                        <thead>
                        <tr>
                            <th class="text-dark">{{trans('common.name_label')}}</th>
                            <th class="text-dark">{{trans('common.code_label')}}</th>
                            <th class="text-dark">{{trans('common.currency_rate_label')}}</th>
                            <th class="text-dark">{{trans('common.status_label')}}</th>
                            <th style="width: 80px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="remove_form">
                    @csrf
                    <input type="hidden" name="remove_currency_id" id="remove_currency_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-4">
                                {{trans('common.confirm_remove_currency_label')}}: <div class="d-inline text-teal font-weight-bold" id="confirm_currency"></div> ?
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal">
                            <span class="mr-1"><i class="fa fa-trash"></i></span>
                            {{trans('common.save_label')}}
                        </button>
                        <button type="button" class="btn btn-danger">
                            {{trans('common.no_label')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.add_currency_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="add_form">
                    @csrf
                    <div class=" mt-2 pl-3 pr-3">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="name" class="text-dark font-weight-bold">{{trans('common.name_label')}}<span class="text-danger">*</span></label>
                                    <input name="name" autocomplete="false"  id="name" type="text" class="form-control" />
                                    <small id="nameError" class="form-text text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group mb-4">
                                    <label for="code"  class="text-dark font-weight-bold">{{trans('common.code_label')}}<span class="text-danger">*</span></label>
                                    <input name="code" id="code"  type="text" class="form-control" />
                                    <small id="codeError" class="form-text text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group row mb-5 ml-1">
                                    <label for="exchange_rate" class="col-3 col-form-label font-weight-bold">{{trans('common.currency_rate_label')}}</label>
                                    <input id="exchange_rate" class="mt-1 pl-0 col-4 form-control text-center" type="number" step="0.01" min="0.01" max="10000000" name="exchange_rate" value="1.00" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal">
                            <i class="fas fa-save"></i>
                            {{trans('common.save_label')}}
                        </button>
                        <button type="button" class="btn btn-danger">
                            <i class="fas fa-ban"></i>
                            {{trans('common.cancel_label')}}
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
                    <h5 class="modal-title" id="editModalLabel">{{trans('common.edit_currency_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="edit_form">
                    <input type="hidden" id="edit_currency_id" name="currency_id" />
                    @csrf
                    <div class=" mt-2 pl-3 pr-3">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_name" class="text-dark font-weight-bold">{{trans('common.name_label')}}<span class="text-danger">*</span></label>
                                    <input readonly name="name" autocomplete="false"  id="edit_name" type="text" class="form-control" />
                                    <small id="nameError" class="form-text text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group mb-4">
                                    <label for="edit_code"  class="text-dark font-weight-bold">{{trans('common.code_label')}}<span class="text-danger">*</span></label>
                                    <input name="code" readonly id="edit_code"  type="text" class="form-control" />
                                    <small id="codeError" class="form-text text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group row mb-5 ml-1">
                                    <label for="edit_exchange_rate" class="col-3 col-form-label font-weight-bold">{{trans('common.currency_rate_label')}}</label>
                                    <input id="edit_exchange_rate" class="mt-1 pl-0 col-4 form-control text-center" type="number" step="0.01" min="0.01" max="10000000" name="exchange_rate" value="1.00" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal">
                            <i class="fas fa-save"></i>
                            {{trans('common.save_label')}}
                        </button>
                        <button type="reset" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                            <i class="fas fa-ban"></i>
                            {{trans('common.cancel_label')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="historyModalLabel">{{trans('common.history')}}</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3 pb-2" >
                        <div class="col">
                            <div class="d-flex flex-column">
                                <span class="text-dark font-weight-bold">{{trans('common.currency_label')}}</span>
                                <span class="text-secondary" id="detail_currency_name"></span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex flex-column">
                                <span class="text-dark font-weight-bold">{{trans('common.code_label')}}</span>
                                <span class="text-secondary" id="detail_currency_code"></span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex flex-column">
                                <span class="text-dark font-weight-bold">{{trans('common.currency_rate_label')}}</span>
                                <span class="text-secondary" id="detail_currency_rate"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <table id="historyDataTable" class="table table-bordered display compact nowrap">
                                <thead>
                                <tr>
                                    <th>{{trans('common.currency_rate_label')}}</th>
                                    <th>{{trans('common.start_date')}}</th>
                                    <th>{{trans('common.end_date')}}</th>
                                    <th>{{trans('common.status_label')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{trans('common.close_label')}}
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
        let selectedCurrencyId = 1;
        const deleteForm = $('#remove_form')
        const addForm = $('#add_form')
        const editForm = $('#edit_form')

        const addModal = $('#addModal')
        const editModal = $('#editModal')
        const removeModal = $('#removeModal')
        const historyModal = $('#historyModal')

        const dataTable = $("#datatable").DataTable({
            processing: true,
            language: datatableTrans,
            autoWidth:false,
            serverSide: true,
            ajax: '{!! route('currency.index') !!}',
            columns: [
                { data: 'name', name: 'Name' },
                { data: 'code', name: 'Code' },
                { data: 'exchange_rate', name: 'exchange_rate' },
                { data: 'status_info', name: 'Status', searchable: false},
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ]
        });
        const historyDataTable = $("#historyDataTable").DataTable({
            processing: true,
            language: datatableTrans,
            autoWidth:false,
            serverSide: true,
            ajax: {
                url:'{!! route('currency.history') !!}',
                method:'post',
                data: function(d){
                    d._token = '{!! csrf_token() !!}'
                    d.currency_id = selectedCurrencyId
                }
            },
            columns: [
                // { data: 'currency_name', name: 'currency_name' },
                // { data: 'currency_code', name: 'currency_code' },
                { data: 'rate', name: 'rate' },
                { data: 'start_date', name: 'start_date' },
                { data: 'end_date', name: 'end_date' },
                { data: 'active', name: 'active', searchable: false},
                // { data:'actions', name:'actions', orderable: false, searchable: false}
            ]
        });

        $(document).ready(function(){


            deleteForm.submit(function($event){
                $event.preventDefault()
                let data = deleteForm.serialize()
                $.ajax({
                    url: '{!! route('currency.destroyAjax') !!}',
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
                                text: "Currency Remove"
                            });
                        }
                    }
                })

            })


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
                    exchange_rate: {
                        required:true,
                        min:0.001,
                        max:10000
                    }
                },
                messages:{
                    name: "name must be longer than 3 characters",
                    code: "code must be shorter than 8 characters",
                    exchange_rate: "enter valid rate"
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            addForm.submit(function($event){
                $event.preventDefault();
                let data = addForm.serialize()
                $.ajax({
                    url: ' {!! route('currency.storeAjax') !!}',
                    method: 'post',
                    data: data,
                    complete: function (xhr,status) {
                        //console.log(xhr)
                        if(xhr.status === 201){
                            $('#addModal').modal('toggle')
                            dataTable.ajax.reload()
                            swal({
                                title: "Success",
                                text: "Currency added successfully",
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
            $(".modal").on("hidden.bs.modal", function() {
                let nameInput = $("input[name='name']")
                nameInput.val('');
                nameInput.removeClass('is-valid');

                let codeInput = $("input[name='code']")
                codeInput.val('');
                codeInput.removeClass('is-valid');

                let rateInput = $("input[name='exchange_rate']")
                rateInput.val(1.00.toFixed(2));
                rateInput.removeClass('is-valid');

                let editNameInput = $("input[name='edit_name']")
                editNameInput.val('');
                editNameInput.removeClass('is-valid');

                let editCodeInput = $("input[name='edit_code']")
                editCodeInput.val('');
                editCodeInput.removeClass('is-valid');

                let editRateInput = $("input[name='edit_exchange_rate']")
                editRateInput.val(1.00.toFixed(2));
                editRateInput.removeClass('is-valid');
            });



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
                    edit_exchange_rate: {
                        required:true,
                        min:0.001,
                        max:10000
                    }
                },
                messages:{
                    edit_name: "name must be longer than 3 characters",
                    edit_code: "code must be shorter than 8 characters",
                    edit_exchange_rate: "enter valid rate"
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            editForm.submit(function($event){
                $event.preventDefault();
                let data = editForm.serialize()
                $.ajax({
                    url: ' {!! route('currency.updateAjax') !!}',
                    method: 'post',
                    data: data,
                    complete: function (xhr,status) {
                        //console.log(xhr)
                        if(xhr.status === 201){
                            $('#editModal').modal('hide')
                            dataTable.ajax.reload()
                            let message = xhr.responseJSON.message
                            toastr.success(message,'{{trans('common.success_label')}}');
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



        function openRemoveModal($event){
            $event.preventDefault()
            removeModal.modal('show')
            let currencyName = $event.target.getAttribute('data-name')
            let currencyId = $event.target.getAttribute('data-id')
            $('#confirm_currency').html( `${currencyName}`);
            $('input[name="remove_currency_id"]').val(currencyId.toString());
        }

        function openAddModal($event){
            $event.preventDefault()
            addModal.modal('show')
        }

        function openEditModal($event){
            let editName = $("#edit_name")
            let editCode = $("#edit_code")
            let editRate = $("#edit_exchange_rate")
            $event.preventDefault()
            let currencyName = $event.target.getAttribute('data-name')
            let currencyId = parseInt($event.target.getAttribute('data-id'))
            $.ajax({
                url: '{!! route('currency.getByIdJson') !!}',
                method:'post',
                data:{
                    "_token": '{!! csrf_token() !!}',
                    "currencyId": currencyId
                },
                complete: function({status, responseJSON}){
                    const {currency} = responseJSON
                    if(status === 201){
                        editName.val(currency.name)
                        editCode.val(currency.code)
                        editRate.val(currency.exchange_rate)
                        $('#edit_currency_id').val(currencyId)
                        editModal.modal('show')
                    }
                }
            })
        }

        function openHistoryModal($event){
            const currencyId = $event.target.getAttribute('data-id')
            console.log(`currency_id: ${currencyId}`)
            $.ajax({
                url: '{!! route('currency.getByIdJson') !!}',
                method: 'post',
                data: {
                    currencyId:currencyId,
                    _token:'{!! csrf_token() !!}'
                },
                complete: function ({status, responseJSON}) {
                    if(status === 201){
                        const {currency} = responseJSON
                        console.log(currency)
                        $('#detail_currency_name').html(currency.name)
                        $('#detail_currency_code').html(currency.code)
                        $('#detail_currency_rate').html(currency.exchange_rate)
                        selectedCurrencyId = currencyId;
                        historyDataTable.ajax.reload();
                    }
                }

            })


            historyModal.modal('show')
        }
    </script>
@endsection
