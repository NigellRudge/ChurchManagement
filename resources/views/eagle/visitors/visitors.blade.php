@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-white d-flex flex-row justify-content-between">
                    <div class="card-title text-lg font-weight-bold text-dark pt-1">
                        {{trans('common.first_time_visitors_label')}}
                    </div>
                    <div class="d-flex flex-row">
                        <form action="#" method="post">
                            @csrf
                            <button class="mr-2 pt-2 pb-2 btn btn-primary text-light font-weight-bold" type="submit">
                                {{trans('common.export_to_excel_label')}}
                                <i class="ml-1 fas fa-file-excel"></i>
                            </button>
                        </form>

                        <button onclick="openAddModal(event)" class="btn btn-teal font-weight-bold">
                            Add Visitor
                            <i class="ml-1 fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row pl-2 mt-2 mb-3">
                        <div class="col-4">
                            <div class="form-group row">
                                <label for="filter_gender" class="col-form-label font-weight-bold">Filter By Gender</label>
                                <div class="col">
                                    <select type="text" id="filter_gender" name="filter_gender" class="form-control">
                                        <option value="0">All</option>
                                        @foreach($data['genders'] as $gender)
                                            <option value="{{$gender['id']}}">{{$gender['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" fix-topbar">
                        <table id="datatable" class="table table-striped table-bordered table-hover display compact nowrap">
                            <thead>
                            <tr class="text-dark">
                                <th>Name</th>
                                <th>date</th>
                                <th>Gender</th>
                                <th>Invited By</th>
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

    <div class="modal" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="remove_form">
                    @csrf
                    <input type="hidden" name="remove_visitor_id" id="remove_visitor_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-4 text-dark">
                                Are you sure you want to remove this Visitor:<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_visitor"></div> <span class="font-weight-bold">?</span>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal">Yes</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title font-weight-bold" id="addModalLabel">New Visitor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="add_form">
                    @csrf
                    <div class=" mt-2 pt-2 pl-3 pr-3">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="first_name" class="text-dark font-weight-bold">First Name<span class="text-danger font-weight-normal">*</span></label>
                                    <input type="text" name="first_name" id="first_name" class="form-control">
                                    <small id="nameError" class="form-text text-danger"></small>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="last_name" class="text-dark font-weight-bold">Last Name<span class="text-danger font-weight-normal">*</span></label>
                                    <input type="text" name="last_name" id="last_name" class="form-control">
                                    <small id="nameError" class="form-text text-danger"></small>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="gender_id" class="font-weight-bold text-dark">Gender<span class="text-danger font-weight-normal">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <i class="fa fa-male" id="male-icon"></i>
                                                <i class="fa fa-female" id="female-icon" style="display: none"></i>
                                            </div>
                                        </div>
                                        <select name="gender_id" id="gender_id" data-placeholder="Select gender" class="form-control">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col">
                                    <div class="form-group">
                                        <label for="invited_by_id" class="text-dark font-weight-bold">Invited By<span class="text-danger font-weight-normal">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-teal text-light">
                                                    <i class="far fa-user"></i>
                                                </div>
                                            </div>
                                            <select name="invited_by_id" data-placeholder="Select member"  id="invited_by_id" type="text" class="form-control">
                                            </select>
                                        </div>
                                        <small id="nameError" class="form-text text-danger"></small>
                                    </div>
                                </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="date" class="font-weight-bold text-dark">Date<span class="text-danger font-weight-normal">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <i class="far fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input id="date" name="date" class="form-control" type="text" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal">
                            <i class="fas fa-save"></i>
                            Save
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-ban"></i>
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="editModalLabel">Edit Visitor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="edit_form">
                    @csrf
                    <input type="hidden" name="edit_visitor_id" id="edit_visitor_id">
                    <div class=" mt-2 pl-3 pr-3">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_first_name" class="text-dark">First Name<span class="text-danger">*</span></label>
                                    <input type="text" name="edit_first_name" id="edit_first_name" class="form-control">
                                    <small id="nameError" class="form-text text-danger"></small>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_last_name" class="text-dark">Last Name<span class="text-danger">*</span></label>
                                    <input type="text" name="edit_last_name" id="edit_last_name" class="form-control">
                                    <small id="nameError" class="form-text text-danger"></small>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="edit_gender_id">Gender<span class="text-danger">*</span></label>
                                    <select name="edit_gender_id" id="edit_gender_id" data-placeholder="Select gender" class="form-control">
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_invited_by_id" class="text-dark">Invited By<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <i class="far fa-user"></i>
                                            </div>
                                        </div>
                                        <select name="edit_invited_by_id" data-placeholder="Select member"  id="edit_invited_by_id" type="text" class="form-control">
                                        </select>
                                    </div>
                                    <small id="nameError" class="form-text text-danger"></small>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_date">Date<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <i class="far fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input id="edit_date" name="edit_date" class="form-control" type="text" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal">
                            <i class="fas fa-save"></i>
                            Save
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
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
    @include('shared.selectCss')
    @include('shared.daterange-pickerCSS')
    @include('shared.sweet-alert-css')
@endsection

@section('custom_js')
    @include('shared.datatable_js')
    @include('shared.sweet-alert-js')
    @include('shared.validator')
    @include('shared.select2-js')
    @include('shared.date-ranger-picker-js')
    <script>
        $(document).ready(function(){
            let genderId = 0;
            let dataTable = $("#datatable").DataTable({
                processing: true,
                language: datatableTrans,
                autoWidth:false,
                serverSide: true,
                "lengthMenu": [5, 10, 25, 50, 75, 100 ],
                pageLength:5,
                ajax: {
                    url:'{!! route('visitors.index') !!}',
                    data: function(data){
                        data.gender_id = genderId;
                    }
                },
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'date', name: 'date',searchable: false },
                    {data: 'gender',name: 'gender'},
                    {data: 'invited_by',name: 'invited_by'},
                    { data:'actions', name:'actions', orderable: false, searchable: false}
                ]
            });
            const filterGender = $('#filter_gender')
            filterGender.on('change',function(){
                genderId = this.value;
                dataTable.ajax.reload()
            });
            let addForm = $('#add_form')
            addForm.validate({
                rules: {
                    first_name:{
                        required:true,
                        minlength:3,
                        maxlength:50
                    },
                    last_name:{
                        required:true,
                        minlength:3,
                        maxlength:50
                    },
                    gender_id: {
                        required:true,
                        min:1
                    },
                    invited_by_id: {
                        required:true,
                        min:1
                    },
                    date: {
                        required:true,
                        date:true
                    }
                },
                messages: {
                    first_name: 'Please enter a valid first_name',
                    last_name: 'please enter a valid last name',
                    gender_id: 'Please select a gender',
                    invited_by_id: 'Please enter a valid invitor',
                    date: 'Please enter a valid date',
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            addForm.submit(function($event){
               $event.preventDefault()
               let formData = addForm.serialize()
               console.log(formData)

                $.ajax({
                    url: '{!! route('visitors.storeVisitorAjax') !!}',
                    method: 'post',
                    data: formData,
                    complete: function(xhr){
                        console.log(xhr.responseJSON)
                        if(xhr.status === 201){
                            let message = xhr.responseJSON.message
                            $('#addModal').modal('hide')
                            dataTable.ajax.reload()
                            swal({
                                title: "Visitor Saved",
                                text: message
                            })
                        }
                    }
                })

            });

            let editForm = $('#edit_form')
            editForm.validate({
                rules: {
                    edit_first_name:{
                        required:true,
                        minlength:3,
                        maxlength:50
                    },
                    edit_last_name:{
                        required:true,
                        minlength:3,
                        maxlength:50
                    },
                    edit_gender_id: {
                        required:true,
                        min:1
                    },
                    edit_invited_by_id: {
                        required:true,
                        min:1
                    },
                    edit_date: {
                        required:true,
                        date:true
                    }
                },
                messages: {
                    edit_first_name: 'Please enter a valid first_name',
                    edit_last_name: 'please enter a valid last name',
                    edit_gender_id: 'Please select a gender',
                    edit_invited_by_id: 'Please enter a valid invitor',
                    edit_date: 'Please enter a valid date',
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            editForm.submit(function($event){
                $event.preventDefault()
                let formData = editForm.serialize()
                console.log(formData)

                $.ajax({
                    url: '{!! route('visitors.updateAjax') !!}',
                    method: 'post',
                    data: formData,
                    complete: function(xhr){
                        console.log(xhr.responseJSON)
                        if(xhr.status === 201){
                            let message = xhr.responseJSON.message
                            $('#editModal').modal('hide')
                            dataTable.ajax.reload()
                            swal({
                                title: "Visitor Saved",
                                text: message
                            })
                        }
                    }
                })

            });

            let deleteForm = $('#remove_form')
            deleteForm.submit(function($event){
                $event.preventDefault()
                let formData = deleteForm.serialize()
                console.log(formData)
                $.ajax({
                    url: '{!! route('visitors.destroyAjax') !!}',
                    method: 'post',
                    data: formData,
                    complete: function(xhr){
                        console.log(xhr.responseJSON.message)
                        if(xhr.status === 201){
                            $('#removeModal').modal('hide')
                            dataTable.ajax.reload()
                            swal({
                                title: "Success",
                                text: xhr.responseJSON.message
                            })
                        }
                    }
                })

            });


            $(".modal").on("hidden.bs.modal", function() {
               let firstName = $('#first_name')
                firstName.val('')
                firstName.removeClass('is-valid')
                firstName.removeClass('is-invalid')

                let lastName = $('#last_name')
                lastName.val('')
                lastName.removeClass('is-valid')
                lastName.removeClass('is-invalid')

                let genderId = $('#gender_id')
                genderId.val('')
                genderId.removeClass('is-valid')
                genderId.removeClass('is-invalid')

                let invitedBy = $('#invited_by_id')
                invitedBy.val('')
                invitedBy.removeClass('is-valid')
                invitedBy.removeClass('is-invalid')

                let date = $('#date')
                date.val('')
                date.removeClass('is-valid')
                date.removeClass('is-invalid')

                let editFirstName = $('#edit_first_name')
                editFirstName.val('')
                editFirstName.removeClass('is-valid')
                editFirstName.removeClass('is-invalid')

                let editLastName = $('#edit_last_name')
                editLastName.val('')
                editLastName.removeClass('is-valid')
                editLastName.removeClass('is-invalid')

                let editGenderId = $('#edit_gender_id')
                editGenderId.val('')
                editGenderId.removeClass('is-valid')
                editGenderId.removeClass('is-invalid')

                let editInvitedBy = $('#edit_invited_by_id')
                editInvitedBy.val('')
                editInvitedBy.removeClass('is-valid')
                editInvitedBy.removeClass('is-invalid')

                let editDate = $('#edit_date')
                editDate.val('')
                editDate.removeClass('is-valid')
                editDate.removeClass('is-invalid')
            });

            $('#gender_id').on('change',function($event){
                let value = this.value
                console.log(value)
            })
        });

        function openRemoveModal($event){
            $event.preventDefault();
            let removeModal = $('#removeModal')
            let visitorName = $event.target.getAttribute('data-name')
            let visitorId = $event.target.getAttribute('data-id')
            console.log(`visitorId: ${visitorId}`)
            $('input[name="remove_visitor_id"]').val(visitorId.toString());
            $('#confirm_visitor').html(visitorName)
            removeModal.modal('show')
        }

        function openAddModal($event){
            $event.preventDefault();
            let openModal = $('#addModal');
            openModal.modal('show');
            $('#date').daterangepicker({
                singleDatePicker:true,
                autoUpdateInput: true,
                showDropdowns: true,
                minYear: 1901,
            })
            $('#invited_by_id').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('members.json') !!}',
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
                    placeholder: 'Search Member',
                    processResults: function(data){
                        console.log(data)
                        return {
                            results: data.results,
                            pagination: {
                                more: false
                            }
                        }
                    }
                }
            });
            $('#gender_id').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('genders.Json') !!}',
                    type: 'post',
                    data: function(params){
                        return {
                            _token: '{!! csrf_token() !!}',
                            term:params.term
                        }
                    },
                    dataType: 'json',
                    cache:true,
                    delay:200,
                    placeholder: 'Search Gender',
                    processResults: function(data){
                        console.log(data.results)
                        return {
                            results: data.results,
                            pagination: {
                                more: false
                            }
                        }
                    }
                }
            })
        }

        function openEditModal($event){
            $event.preventDefault();
            let editModal = $('#editModal')
            let editFirstName = $("input[name='edit_first_name']")
            let editLastName = $("input[name='edit_last_name']")
            let editDate = $('#edit_date')
            let visitorId = $event.target.getAttribute('data-id');
            console.log(`visitor Id: ${visitorId}`)
            $("input[name='edit_visitor_id']").val(visitorId)
            let data = {
                "_token": '{!! csrf_token() !!}',
                "edit_visitor_id": visitorId
            }
            //console.log(data)
            $.ajax({
                url: '{!! route('visitors.getByIdAjax') !!}',
                method:'post',
                data:data,
                complete: function(xhr){
                    let visitor = xhr.responseJSON
                    console.log(visitor)
                    if(xhr.status === 201){
                        editFirstName.val(visitor.first_name)
                        editLastName.val(visitor.last_name)
                        setupEditGender(visitor.gender_id)
                        setupEditInvitedBy(visitor.invited_by_id)
                        editDate.daterangepicker({
                            singleDatePicker:true,
                            autoUpdateInput: true,
                            startDate: data.date,
                            showDropdowns: true,
                            minYear: 1901,
                        })
                    }
                    editModal.modal('show')
                }
            })
        }

        function setupEditGender(genderId){
            let genderSelect = $('#edit_gender_id').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('genders.Json') !!}',
                    type: 'post',
                    data: function(params){
                        return {
                            _token: '{!! csrf_token() !!}',
                            term:params.term
                        }
                    },
                    dataType: 'json',
                    cache:true,
                    delay:200,
                    placeholder: 'Search Gender',
                    processResults: function(data){
                        console.log(data)
                        return {
                            results: data.results,
                            pagination: {
                                more: false
                            }
                        }
                    }
                }
            });
            $.ajax({
                type: 'POST',
                url: '{!! route('genders.getByIdJson') !!}',
                data: {
                    _token: '{!! csrf_token() !!}',
                    genderId:genderId
                }
            }).then(function(data){
                console.log('gender data')
                console.log(data)
                let gender = data.gender[0]
                let option = new Option(gender.name,gender.id,true, true)
                genderSelect.append(option).trigger('change')
                genderSelect.trigger({
                    type: 'select2:select',
                    params: {
                        data: data
                    }
                });
            });
        }

        function setupEditInvitedBy(invitedById){
            let memberSelect = $('#edit_invited_by_id').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('members.json') !!}',
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
                    placeholder: 'Search Member',
                    processResults: function(data){
                        //console.log(data)
                        return {
                            results: data.results,
                            pagination: {
                                more: false
                            }
                        }
                    }
                }
            });
            $.ajax({
                type: 'POST',
                url: '{!! route('members.getByIdJson') !!}',
                data: {
                    _token: '{!! csrf_token() !!}',
                    id:invitedById
                }
            }).then(function(data){
                //console.log(data)
                let option = new Option(data.name,data.id,true, true)
                memberSelect.append(option).trigger('change')
                memberSelect.trigger({
                    type: 'select2:select',
                    params: {
                        data: data
                    }
                });
            });
        }
    </script>
@endsection
