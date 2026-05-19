@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class=" d-flex justify-content-between">
                <div class="font-weight-bold text-lg text-dark">
                    {{trans('common.converts_label')}}
                </div>
                <div class="d-flex justify-content-end mb-2">
                    <form action="{{ route('convert.export') }}" method="post">
                        @csrf
                        <input type="hidden" id="export_from_date" name="export_from_date">
                        <input type="hidden" id="export_to_date" name="export_to_date">
                        <input type="hidden" id="export_gender" name="export_gender">
                        <button class="mr-2 btn btn-primary font-weight-bold text-light rounded font-weight-bold" type="submit">
                            {{trans('common.export_to_excel_label')}}
                            <i class="ml-1 fas fa-file-excel"></i>
                        </button>
                    </form>
                    <a class="btn btn-teal font-weight-bold text-white" href="#" onclick="openAddModal(event)">
                        {{trans('common.add_convert_label')}}
                        <i class="ml-1 fas fa-plus"></i>
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row pl-2 mt-2 mb-3">
                        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="filter_gender" class="col-form-label font-weight-bold">{{trans('common.filter_by_gender_label')}}</label>
                                    <select type="text" id="filter_gender" name="filter_gender" class="form-control">
                                        <option value="0">{{trans('common.all_label')}}</option>
                                        @foreach($data['genders'] as $gender)
                                            <option value="{{$gender['id']}}">{{trans('common' . '.'. $gender['trans_string'])}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="from_date" class="col-form-label font-weight-bold">{{trans('common.from_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white">
                                                <i class="fa fa-calendar text-teal"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" id="from_date" name="from_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="to_date" class="col-form-label font-weight-bold">{{trans('common.to_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white">
                                                <i class="fa fa-calendar text-teal"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" id="to_date" name="to_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col pt-4 d-flex flex-column justify-content-center" style="">
                            <div class="row d-flex flex-row">
                                <button class=" mr-1 btn btn-teal text-light font-weight-bold" id="filterBtn">
                                    Filter
                                    <i class="fas fa-filter ml-1"></i>
                                </button>
                                <button class="btn btn-danger text-light font-weight-bold" id="clearBtn">
                                    Clear
                                    <i class="fas fa-ban ml-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <table id="datatable" class="table border-right border-left border-bottom display compact nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-user text-teal"></i></span>
                                    {{trans('common.name_label')}}
                                </th>
                                <th>
                                    {{trans('common.gender_label')}}
                                </th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-phone text-teal"></i></span>
                                    {{trans('common.phone_number_label')}}
                                </th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-map-marker text-teal"></i></span>
                                    {{trans('common.address_label')}}
                                </th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-calendar text-teal"></i></span>
                                    {{trans('common.convert_date_label')}}
                                </th>
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


    <div class="modal fade" id="promoteModal" tabindex="-1" role="dialog" aria-labelledby="promoteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="promoteModalLabel">Promote to visitor Member</h5>
                    <button type="button" class="close bg-teal border-none" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="promote_form">
                    @csrf
                    <input type="hidden" name="convert_id"  id="promote_convert_id" value=""/>
                    <div class="modal-body">
                        Are you sure you want to promote this convert to a member?
                        <div class="text-teal font-weight-bold" id="promote_convert_name"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal">Yes</button>
                        <button type="button" class="btn btn-danger">No</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.add_convert_label')}}</h5>
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
                                    <label for="first_name" class="text-dark font-weight-bold">{{trans('common.first_name_label')}}<span class="text-danger">*</span></label>
                                    <input name="first_name" placeholder="John" id="first_name" type="text" class="form-control"  required/>
                                    <small id="nameError" class="form-text text-danger"></small>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group mb-4">
                                    <label for="last_name"  class="text-dark font-weight-bold">{{trans('common.last_name_label')}}<span class="text-danger">*</span></label>
                                    <input name="last_name" id="last_name" placeholder="Doe" type="text" class="form-control" required />
                                    <small id="codeError" class="form-text text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-4">
                                <div class="form-group mb-4">
                                    <label for="gender_id"  class="text-dark font-weight-bold">{{trans('common.gender_label')}}<span class="text-danger">*</span></label>
                                    <select name="gender_id" id="gender_id" class="form-control" required>
                                        @foreach($data['genders'] as $gender)
                                            <option value="{{$gender->id}}">{{$gender->name}}</option>
                                        @endforeach
                                    </select>
                                    <small id="codeError" class="form-text text-danger"></small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group mb-4">
                                    <label for="phone_number"  class="text-dark font-weight-bold">{{trans('common.phone_number_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text  bg-teal text-white"><i class="fas fa-phone-alt"></i></div>
                                        </div>
                                        <input name="phone_number" id="phone_number" placeholder="Type Code" type="text" class="form-control" required />
                                    </div>
                                    <small id="codeError" class="form-text text-danger"></small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group mb-4">
                                    <label for="convert_date"  class="text-dark font-weight-bold">{{trans('common.convert_date_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text  bg-teal text-white"><i class="fas fa-calendar-alt"></i></div>
                                        </div>
                                        <input name="convert_date" id="convert_date" placeholder="Type Code" type="text" class="form-control" required />
                                    </div>
                                    <small id="codeError" class="form-text text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mb-4">
                            <div class="col">
                                <div class="form-group mb-4">
                                    <label for="address"  class="text-dark font-weight-bold">{{trans('common.address_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text  bg-teal text-white"> <i class="fas fa-house-user"></i></div>
                                        </div>
                                        <input name="address" id="address" placeholder="Main street # 1" type="text" class="form-control" />
                                    </div>
                                    <small id="codeError" class="form-text text-danger"></small>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group mb-4">
                                    <label for="district_id"  class="text-dark font-weight-bold">{{trans('common.district_label')}}</label>
                                    <select name="district_id" id="district_id" class="form-control">
                                        @foreach($data['districts'] as $district)
                                            <option value="{{$district->id}}">{{$district->name}}</option>
                                        @endforeach
                                    </select>
                                    <small id="codeError" class="form-text text-danger"></small>
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

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="editModalLabel">{{trans('common.edit_convert_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="edit_form">
                    <input type="hidden" id="edit_convert_id" name="edit_convert_id" />
                    @csrf
                    <div class=" mt-2 pl-3 pr-3">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_first_name" class="text-dark">{{trans('common.first_name_label')}}<span class="text-danger">*</span></label>
                                    <input name="edit_first_name" placeholder="John" id="edit_first_name" type="text" class="form-control"  required/>
                                    <small id="nameError" class="form-text text-danger"></small>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group mb-4">
                                    <label for="edit_last_name"  class="text-dark">{{trans('common.last_name_label')}}<span class="text-danger">*</span></label>
                                    <input name="edit_last_name" id="edit_last_name" placeholder="Doe" type="text" class="form-control" required />
                                    <small id="codeError" class="form-text text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-4">
                                <div class="form-group mb-4">
                                    <label for="edit_gender_id"  class="text-dark">{{trans('common.gender_label')}}<span class="text-danger">*</span></label>
                                    <select name="edit_gender_id" id="edit_gender_id" class="form-control" required>
                                        <option>{{trans('common.select_gender_label')}}</option>
                                        @foreach($data['genders'] as $gender)
                                            <option value="{{$gender->id}}">{{$gender->name}}</option>
                                        @endforeach
                                    </select>
                                    <small id="codeError" class="form-text text-danger"></small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group mb-4">
                                    <label for="edit_phone_number"  class="text-dark">{{trans('common.phone_number_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text  bg-teal text-white"><i class="fas fa-phone-alt"></i></div>
                                        </div>
                                        <input name="edit_phone_number" id="edit_phone_number" placeholder="Type Code" type="text" class="form-control" required />
                                    </div>
                                    <small id="codeError" class="form-text text-danger"></small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group mb-4">
                                    <label for="edit_convert_date"  class="text-dark">{{trans('common.convert_date_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text  bg-teal text-white"><i class="fas fa-calendar-alt"></i></div>
                                        </div>
                                        <input name="edit_convert_date" id="edit_convert_date" placeholder="Type Code" type="text" class="form-control" required />
                                    </div>
                                    <small id="codeError" class="form-text text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mb-4">
                            <div class="col">
                                <div class="form-group mb-4">
                                    <label for="edit_address"  class="text-dark">{{trans('common.address_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text  bg-teal text-white"> <i class="fas fa-house-user"></i></div>
                                        </div>
                                        <input name="edit_address" id="edit_address" placeholder="Main street # 1" type="text" class="form-control" />
                                    </div>
                                    <small id="codeError" class="form-text text-danger"></small>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group mb-4">
                                    <label for="edit_district_id"  class="text-dark">{{trans('common.district_label')}}</label>
                                    <select name="edit_district_id" id="edit_district_id" class="form-control">
                                        @foreach($data['districts'] as $district)
                                            <option value="{{$district->id}}">{{$district->name}}</option>
                                        @endforeach
                                    </select>
                                    <small id="codeError" class="form-text text-danger"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
{{--                        <a href="#" id="promote_link" class="btn btn-info font-weight-bold text-light">Promote to member</a>--}}
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
@endsection

@section('custom_css')
    @include('shared.totalCSS')
@endsection

@section('custom_js')
    @include('shared.totalJS')
    <script>
        $(document).ready(function(){

            /* Filter Logic */
            let genderId = 0;
            let fromDate = null;
            let toDate = null;
            const filterGender = $('#filter_gender')
            filterGender.on('change',function(){
                genderId = this.value;
                $('#export_gender').val(genderId)
               // dataTable.ajax.reload()
            });
            const filterBtn = $('#filterBtn')
            const clearBtn = $('#clearBtn')
            filterBtn.on('click',function($event){
                console.log('clicked')
                dataTable.ajax.reload()
            })
            const fromDateEl = $('#from_date').daterangepicker({
                singleDatePicker:true,
                autoUpdateInput: true,
                showDropdowns: true,
                minYear: 1901,
            }).on('apply.daterangepicker',function(ev,picker){
                let val = picker.startDate.format('YYYY-MM-DD')
                $(this).val(val)
                fromDate = val;
                $('#export_from_date').val(fromDate)
            })
            const toDateEl = $('#to_date').daterangepicker({
                singleDatePicker:true,
                autoUpdateInput: true,
                showDropdowns: true,
                minYear: 1901,
            }).on('apply.daterangepicker',function(ev,picker){
                let val = picker.startDate.format('YYYY-MM-DD')
                $(this).val(val)
                toDate = val;
                $('#export_to_date').val(toDate)
            })
            clearBtn.on('click',function($event){
                fromDateEl.val('')
                toDateEl.val('')
                fromDate = null
                toDate = null
                genderId = 0
                filterGender.val(0)
                dataTable.ajax.reload()
            })

            const dataTable = $("#datatable").DataTable({
                processing: true,
                lengthMenu: [5, 10, 25, 50, 75, 100 ],
                pageLength:5,
                language: datatableTrans,
                autoWidth:false,
                serverSide: true,
                ajax: {
                    url:'{!! route('convert.index') !!}',
                    data: function(d){
                        d.gender_id = genderId;
                        d.from_date = fromDate;
                        d.to_date = toDate;
                    }
                },
                columns: [
                    {data: 'id',name: 'id'},
                    { data: 'name', name: 'name' },
                    { data: 'gender_info', name: 'gender_info', searchable: false,orderable: false},
                    { data: 'phone_number', name: 'phone_number'},
                    { data: 'address', name: 'address'},
                    { data: 'convert_date', name: 'convert_date'},
                    { data:'actions', name:'actions', orderable: false, searchable: false}
                ]
            });
            /* End */

            /* add Form */
            const addForm = $('#add_form')
            addForm.validate({
                rules:{
                    first_name:{
                        required:true,
                        minlength:5,
                        maxlength:40
                    },
                    last_name:{
                        required:true,
                        minlength:5,
                        maxlength:40
                    },
                    gender_id: {
                        required:true,
                    },
                    phone_number: {
                        required:true
                    },

                    convert_date: {
                        required:true,
                        date: true
                    },
                    district_id: {
                        required:true,
                        min:1
                    },
                    address: {
                        required:true,
                        minlength: 5,
                        maxlength:50
                    }
                },
                messages:{
                    first_name: "First name must be between 5 and 40 characters long",
                    last_name: "Last name must be between 5 and 40 characters long",
                    convert_date: "please enter a valid date",
                    gender_id: "please select a gender",
                    district_id: "please select a district",
                    address: "address should be between 5 and 50 characters long",
                    phone_number: "please enter a valid phone number"
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            addForm.submit(function($event){
                $event.preventDefault();
                let data = addForm.serialize()
                $.ajax({
                    url: ' {!! route('convert.store') !!}',
                    method: 'post',
                    data: data,
                    complete: function (xhr,status) {
                        if(xhr.status === 201){
                            $('#addModal').modal('toggle')
                            let message = xhr.responseJSON.message
                            dataTable.ajax.reload()
                            toastr.success(message,'{{trans('common.success_label')}}')
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
            /* End */

            /* Edit form */
            const editForm = $('#edit_form')
            editForm.validate({
                rules:{
                    edit_first_name:{
                        required:true,
                        minlength:3,
                        maxlength:40
                    },
                    edit_last_name:{
                        required:true,
                        minlength:3,
                        maxlength:40
                    },
                    edit_gender_id: {
                        required:true,
                    },
                    edit_phone_number: {
                        required:true
                    },

                    edit_convert_date: {
                        required:true,
                        date: true
                    },
                    edit_district_id: {
                        required:true,
                        min:1
                    },
                    edit_address: {
                        required:true,
                        minlength: 5,
                        maxlength:50
                    }
                },
                messages:{
                    edit_first_name: "First name must be between 3 and 40 characters long",
                    edit_last_name: "Last name must be between 3 and 40 characters long",
                    edit_convert_date: "please enter a valid date",
                    edit_gender_id: "please select a gender",
                    edit_district_id: "please select a district",
                    edit_address: "address should be between 5 and 50 characters long",
                    edit_phone_number: "please enter a valid phone number"
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            editForm.submit(function($event){
                $event.preventDefault();
                let data = editForm.serializeArray()
                console.log(data)
                $.ajax({
                    url: ' {!! route('convert.update') !!}',
                    method: 'patch',
                    data: data,
                    complete: function (xhr,status) {
                        if(xhr.status === 201){
                            $('#editModal').modal('toggle')
                            let message = xhr.responseJSON.message
                            dataTable.ajax.reload()
                            toastr.success(message,'{{trans('common.success_label')}}')
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
            /* End */

            /* Delete Form */
            const deleteForm = $('#remove_form')
            deleteForm.submit(function($event) {
                $event.preventDefault()
                let data = deleteForm.serialize()
                console.log(data);
                $.ajax({
                    url: '{!! route('convert.destroy') !!}',
                    method: 'delete',
                    data: data,
                    success: function (data) {
                        console.log(data)
                    },
                    error: function (error) {
                        console.log(error)
                    },
                    complete: function (xhr, data) {
                        if (xhr.status === 201) {
                            let message = xhr.responseJSON.message
                            $('#removeModal').modal('hide')
                            dataTable.ajax.reload()
                            toastr.warning(message,'{{trans('common.success_label')}}')
                        }
                    }
                })
            })
            /* End */
            $(".modal").on("hidden.bs.modal", function() {
                let firstNameInput = $("select[name='first_name']")
                firstNameInput.val('');
                firstNameInput.removeClass('is-valid');
                firstNameInput.removeClass('is-invalid');

                let lastNameInput = $("input[name='last_name']")
                lastNameInput.val('');
                lastNameInput.removeClass('is-valid');
                lastNameInput.removeClass('is-invalid');

                let addressInput = $("select[name='address']")
                addressInput.val('');
                addressInput.removeClass('is-valid');
                addressInput.removeClass('is-invalid');

                let phoneNumberInput = $("select[name='phone_number']")
                phoneNumberInput.val('');
                phoneNumberInput.removeClass('is-valid');
                phoneNumberInput.removeClass('is-invalid');

                let genderInput = $("input[name='gender_id']")
                genderInput.val('');
                genderInput.removeClass('is-valid');

                let districtInput = $("input[name='district_id']")
                districtInput.val('');
                districtInput.removeClass('is-valid');

                let convertDateInput = $("input[name='convert_date']")
                convertDateInput.val('');
                convertDateInput.removeClass('is-valid');
                convertDateInput.removeClass('is-invalid');

                let editFirstNameInput = $("select[name='edit_first_name']")
                editFirstNameInput.val('');
                editFirstNameInput.removeClass('is-valid');
                editFirstNameInput.removeClass('is-invalid');

                let editLastNameInput = $("input[name='edit_last_name']")
                editLastNameInput.val('');
                editLastNameInput.removeClass('is-valid');
                editLastNameInput.removeClass('is-invalid');

                let editAddressInput = $("select[name='edit_address']")
                editAddressInput.val('');
                editAddressInput.removeClass('is-valid');
                editAddressInput.removeClass('is-invalid');

                let editPhoneNumberInput = $("select[name='edit_phone_number']")
                editPhoneNumberInput.val('');
                editPhoneNumberInput.removeClass('is-valid');
                editPhoneNumberInput.removeClass('is-invalid');

                let editGenderInput = $("input[name='edit_gender_id']")
                editGenderInput.val('');
                editGenderInput.removeClass('is-valid');

                let editDistrictInput = $("input[name='edit_district_id']")
                editDistrictInput.val('');
                editDistrictInput.removeClass('is-valid');

                let editConvertDateInput = $("input[name='edit_convert_date']")
                editConvertDateInput.val('');
                editConvertDateInput.removeClass('is-valid');
                editConvertDateInput.removeClass('is-invalid');

            });
        });

        function openRemoveModal($event){
            $event.preventDefault()
            let removeModal = $('#removeModal')
            removeModal.modal('show')
            let convertName = $event.target.getAttribute('data-name')
            let convertId = $event.target.getAttribute('data-id')
            $('#confirm_convert_name').html( `${convertName}`);
            $('input[name="remove_convert_id"]').val(convertId.toString());
        }

        function openAddModal($event){
            $event.preventDefault()
            let addModal = $('#addModal')
            addModal.modal('show')

            $('#convert_date').daterangepicker({
                singleDatePicker:true,
                autoUpdateInput: true,
                showDropdowns: true,
                minYear: 1901,
            })
        }

        function openEditModal($event){
            let editFirstName = $("input[name='edit_first_name']")
            let editLastName = $("input[name='edit_last_name']")
            let editAddress = $("input[name='edit_address']")
            let editPhoneNumber = $("input[name='edit_phone_number']")
            $event.preventDefault()
            let addModal = $('#editModal')
            addModal.modal('show')
            let convertId = parseInt($event.target.getAttribute('data-id'))
            let url = `/convert/promote/${convertId}`
            console.log(`url: ${url}`)
            $('#promote_link').attr('href',url)
            console.log(`convert Id: ${convertId}`)
            $("input[name='edit_convert_id']").val(convertId)
            let data = {
                "_token": '{!! csrf_token() !!}',
                "edit_convert_id": convertId
            }
            //console.log(data)
            $.ajax({
                url: '{!! route('convert.getById') !!}',
                method:'post',
                data:data,
                complete: function(xhr){
                    let data = xhr.responseJSON.convert
                    console.log(data)
                    if(xhr.status === 201){
                        editFirstName.val(data.first_name)
                        editLastName.val(data.last_name)
                        editAddress.val(data.address)
                        editPhoneNumber.val(data.phone_number)
                        $('#edit_gender_id').val(data.gender_id)
                        $('#edit_district_id').val(data.district_id)
                        $('#edit_convert_date').daterangepicker({
                            singleDatePicker:true,
                            autoUpdateInput: true,
                            startDate: data.convert_date,
                            showDropdowns: true,
                            minYear: 1901,
                        })
                    }
                    addModal.modal('show')
                }
            })
        }
        const openPromoteModal = ($event)=>{
            $event.preventDefault()
            let id = $event.target.getAttribute('data-id')
            let name = $event.target.getAttribute('data-name')
            console.log({id,name})
            $('#promote_convert_name').html(`${name}`)
            $('#promote_convert_id').val(id)
            $('#promoteModal').modal('show')
        }
    </script>
@endsection
