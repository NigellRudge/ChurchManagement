@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="d-flex card-header bg-white justify-content-between">
                    <div class="font-weight-bold text-lg text-dark">
                        {{trans('common.converts_label')}}
                    </div>
                    <div class="d-none d-flex justify-content-end mb-2">
                        <form action="{{ route('convert.export') }}" method="post">
                            @csrf
                            <input type="hidden" id="export_from_date" name="export_from_date">
                            <input type="hidden" id="export_to_date" name="export_to_date">
                            <input type="hidden" id="export_gender" name="export_gender">
                            <button class="mr-2 btn btn-primary font-weight-bold text-light rounded font-weight-bol" disabled id="exportBtn" type="submit">
                                {{trans('common.export_to_excel_label')}}
                                <i class="ml-1 fas fa-file-excel"></i>
                            </button>
                        </form>
                        <button id="addButton" disabled class="btn btn-teal font-weight-bold text-white" href="#">
                            {{trans('common.add_convert_label')}}
                            <i class="ml-1 fas fa-plus"></i>
                        </button>
                    </div>
                </div>
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
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4">
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
                    <table id="datatable" class="table table-bordered display compact nowrap">
                        <thead>
                        <tr>
                            <th style="width: 70px">#</th>
                            <th >
                                <span class="mr-1"><i class="fa fa-user text-teal"></i></span>
                                {{trans('common.name_label')}}
                            </th>
                            <th class="d-flex justify-content-center align-items-center" >
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
                            <th style="width: 80px"></th>
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
                    <button type="button" class="close bg-teal border-none" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="remove_form">
                    @csrf
                    <input type="hidden" name="remove_convert_id"  id="remove_convert_id" value=""/>
                    <div class="modal-body">
                        {{trans('common.confirm_convert_delete_label')}} <div class="d-inline text-teal font-weight-bold" id="confirm_convert_name"></div> ?
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
                <div class=" mt-2 pl-3 pt-2 pr-3">
                    <form method="post" action="#" id="add_form">
                        @csrf
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="first_name" class="text-dark font-weight-bold">{{trans('common.first_name_label')}}<span class="text-danger">*</span></label>
                                    <input name="first_name" placeholder="John" id="first_name" type="text" class="form-control"  />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group mb-4">
                                    <label for="last_name"  class="text-dark font-weight-bold">{{trans('common.last_name_label')}}<span class="text-danger">*</span></label>
                                    <input name="last_name" id="last_name" placeholder="Doe" type="text" class="form-control"  />
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-4">
                                <div class="form-group mb-4">
                                    <label for="gender_id"  class="text-dark font-weight-bold">{{trans('common.gender_label')}}<span class="text-danger">*</span></label>
                                    <select name="gender_id" id="gender_id" class="form-control" required>
                                        <option value="0">{{trans('common.select_option')}}</option>
                                        @foreach($data['genders'] as $gender)
                                            <option value="{{$gender->id}}">{{$gender->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group mb-4">
                                    <div class="d-flex flex-row">
                                        <label for="phone_number"  class="text-dark mr-2 font-weight-bold">{{trans('common.phone_number_label')}}</label>
                                        <i class="fas fa-phone-alt text-teal"></i>
                                        <span class="text-danger">*</span>
                                    </div>
                                    <input name="phone_number" id="phone_number" placeholder="+597" type="number" class="form-control"  />
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group mb-4">
                                    <div class="d-flex flex-row">
                                        <label for="convert_date"  class="text-dark mr-2 font-weight-bold">{{trans('common.convert_date_label')}}</label>
                                        <i class="fas fa-calendar-alt text-teal"></i>
                                        <span class="text-danger">*</span>
                                    </div>
                                    <input name="convert_date" id="convert_date" placeholder="Type Code" type="text" class="form-control"  />
                                </div>
                            </div>
                        </div>
                        <div class="form-row mb-4">
                            <div class="col">
                                <div class="form-group mb-4">
                                    <label for="address"  class="text-dark font-weight-bold">{{trans('common.address_label')}}</label>
                                    <input name="address" id="address" placeholder="Main street # 1" type="text" class="form-control" />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group mb-4">
                                    <label for="district_id"  class="text-dark font-weight-bold">{{trans('common.district_label')}}</label>
                                    <select name="district_id" id="district_id" class="form-control">
                                        <option value="0">{{trans('common.select_option')}}</option>
                                        @foreach($data['districts'] as $district)
                                            <option value="{{$district->id}}">{{$district->name}}</option>
                                        @endforeach
                                    </select>
                                    <small id="codeError" class="form-text text-danger"></small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button onclick="submitAddForm(event)" class="btn btn-teal">
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="editModalLabel">{{trans('common.edit_convert_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>

                    <div class=" mt-2 pl-3 pr-3">
                        <form method="post" action="#" id="edit_form">
                            <input type="hidden" id="edit_convert_id" name="edit_convert_id" />
                            @csrf
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_first_name" class="text-dark">{{trans('common.first_name_label')}}<span class="text-danger">*</span></label>
                                    <input name="edit_first_name" placeholder="John" id="edit_first_name" type="text" class="form-control"  required/>
                                    <div id="firstName"></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group mb-4">
                                    <label for="edit_last_name"  class="text-dark">{{trans('common.last_name_label')}}<span class="text-danger">*</span></label>
                                    <input name="edit_last_name" id="edit_last_name" placeholder="Doe" type="text" class="form-control" required />
                                    <div id="lastNameError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-4">
                                <div class="form-group mb-4">
                                    <label for="edit_gender_id"  class="text-dark">{{trans('common.gender_label')}}<span class="text-danger">*</span></label>
                                    <select name="edit_gender_id" id="edit_gender_id" class="form-control" required>
                                        <option value="0">{{trans('common.select_option')}}</option>
                                        @foreach($data['genders'] as $gender)
                                            <option value="{{$gender->id}}">{{$gender->name}}</option>
                                        @endforeach
                                    </select>
                                    <div id="genderError"></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group mb-4">
                                    <label for="edit_phone_number"  class="text-dark">{{trans('common.phone_number_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text  bg-teal text-white"><i class="fas fa-phone-alt"></i></div>
                                        </div>
                                        <input name="edit_phone_number" id="edit_phone_number" placeholder="Type Code" type="number" class="form-control" required />
                                    </div>
                                    <div id="phoneError"></div>
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
                                    <div id="dateError"></div>
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
                                    <div id="addressError"></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group mb-4">
                                    <label for="edit_district_id"  class="text-dark">{{trans('common.district_label')}}</label>
                                    <select name="edit_district_id" id="edit_district_id" class="form-control">
                                        <option value="0">{{trans('common.select_option')}}</option>
                                        @foreach($data['districts'] as $district)
                                            <option value="{{$district->id}}">{{$district->name}}</option>
                                        @endforeach
                                    </select>
                                    <div id="districtError"></div>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button onclick="submitEditForm(event)" class="btn btn-teal">
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
        const addForm = $('#add_form')
        const editForm = $('#edit_form')
        const addButton = $('#addButton')
        const filterGender = $('#filter_gender')
        const filterBtn = $('#filterBtn')
        const clearBtn = $('#clearBtn')
        const dateFilterEl = $('#date_filter')

        const exportToDate = $('#export_to_date')
        const exportFromDate = $('#export_from_date')
        const exportGender = $('#export_gender')

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
            ],
            initComplete:function (settings,json) {
                onReloadComplete(json)
            }
        });
        $(document).ready(function(){
            addButton.prop('disabled',false)
            addButton.on('click', function (event) {
                addModal.modal('show')
                $('#convert_date').daterangepicker({
                    singleDatePicker:true,
                    autoUpdateInput: true,
                    showDropdowns: true,
                    minYear: 1901,
                    locale:datePickerTran,
                    applyButtonClasses:'btn btn-teal btn-sm',
                    cancelButtonClasses:'btn btn-danger btn-sm'
                })
            })
            filterGender.on('change',function(){
                genderId = this.value;
                exportGender.val(genderId)
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
                genderId = 0
                filterGender.val(0)

                exportFromDate.val(null)
                exportToDate.val(null)
                exportGender.val(null)
                dataTable.ajax.reload(onReloadComplete)
            })

            /* Delete Form */
            const deleteForm = $('#remove_form')
            deleteForm.submit(function($event) {
                $event.preventDefault()
                let data = deleteForm.serialize()
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
                    complete: function ({status,responseJSON }) {
                        if (status === 200) {
                            const {message} = responseJSON
                            $('#removeModal').modal('hide')
                            dataTable.ajax.reload(onReloadComplete)
                            toastr.warning(message,'{{trans('common.success_label')}}')
                        }
                    }
                })
            })
            /* End */
            $(".modal").on("hidden.bs.modal", function() {
                clearForm('add_form',false)
                clearForm('edit_form',false)
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
                locale:datePickerTran,
                applyButtonClasses:'btn btn-teal btn-sm',
                cancelButtonClasses:'btn btn-danger btn-sm'
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
            // $('#promote_link').attr('href',url)
            $("#edit_convert_id").val(convertId)
            let data = {
                "_token": '{!! csrf_token() !!}',
                "edit_convert_id": convertId
            }
            $.ajax({
                url: '{!! route('convert.getById') !!}',
                method:'post',
                data:data,
                complete: function({status,responseJSON }){
                    const {convert} = responseJSON
                    if(status === 201){
                        editFirstName.val(convert.first_name)
                        editLastName.val(convert.last_name)
                        editAddress.val(convert.address)
                        editPhoneNumber.val(convert.phone_number)
                        $('#edit_gender_id').val(convert.gender_id)
                        $('#edit_district_id').val(convert.district_id)
                        $('#edit_convert_date').daterangepicker({
                            singleDatePicker:true,
                            autoUpdateInput: true,
                            startDate: convert.convert_date,
                            showDropdowns: true,
                            minYear: 1901,
                            locale:datePickerTran,
                            applyButtonClasses:'btn btn-teal btn-sm',
                            cancelButtonClasses:'btn btn-danger btn-sm'
                        })
                    }
                    addModal.modal('show')
                }
            })
        }

        function openPromoteModal($event){
            $event.preventDefault()
            let id = $event.target.getAttribute('data-id')
            let name = $event.target.getAttribute('data-name')
            console.log({id,name})
            $('#promote_convert_name').html(`${name}`)
            $('#promote_convert_id').val(id)
            $('#promoteModal').modal('show')
        }

        function submitAddForm(){
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
                        min:1
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
                    first_name: {
                        required:  "{!! trans('custom_validation.first_name_required') !!}",
                        minlength: "{!! trans('custom_validation.min_length',['min' => 5]) !!}",
                        maxlength: "{!! trans('custom_validation.max_length',['max' => 40]) !!}",
                    },
                    last_name: {
                        required:  "{!! trans('custom_validation.last_name_required') !!}",
                        minlength: "{!! trans('custom_validation.min_length',['min' => 5]) !!}",
                        maxlength: "{!! trans('custom_validation.max_length',['max' => 40]) !!}",
                    },
                    convert_date: {
                        required:'{!! trans('custom_validation.date_required') !!}',
                        date: '{!! trans('custom_validation.valid_date') !!}'
                    },
                    gender_id: {
                        required: '{!! trans('custom_validation.gender') !!}',
                        min:'{!! trans('custom_validation.gender') !!}',
                    },
                    district_id: {
                        required: '{!! trans('custom_validation.select_option') !!}',
                        min: '{!! trans('custom_validation.select_option') !!}'
                    },
                    address: {
                        required: '{!! trans('custom_validation.address') !!}',
                        minlength: "{!! trans('custom_validation.min_length',['min' => 5]) !!}",
                        maxlength: "{!! trans('custom_validation.max_length',['max' => 50]) !!}",
                    },
                    phone_number: {
                        required: '{!! trans('custom_validation.phone_number') !!}',
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(addForm.valid()){
                let data = addForm.serialize()
                $.ajax({
                    url: ' {!! route('convert.store') !!}',
                    method: 'post',
                    data: data,
                    complete: function ({status,responseJSON}) {
                        if(status === 200){
                            addModal.modal('hide')
                            const {message} = responseJSON
                            dataTable.ajax.reload(onReloadComplete)
                            toastr.success(message,'{{trans('common.success_label')}}')
                        }
                        if(status === 422){
                            let {errors} = responseJSON
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
            }

        }

        function submitEditForm() {
            editForm.validate({
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
                        min:1
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
                    first_name: {
                        required:  "{!! trans('custom_validation.first_name_required') !!}",
                        minlength: "{!! trans('custom_validation.min_length',['min' => 5]) !!}",
                        maxlength: "{!! trans('custom_validation.max_length',['max' => 40]) !!}",
                    },
                    last_name: {
                        required:  "{!! trans('custom_validation.last_name_required') !!}",
                        minlength: "{!! trans('custom_validation.min_length',['min' => 5]) !!}",
                        maxlength: "{!! trans('custom_validation.max_length',['max' => 40]) !!}",
                    },
                    convert_date: {
                        required:'{!! trans('custom_validation.date_required') !!}',
                        date: '{!! trans('custom_validation.valid_date') !!}'
                    },
                    gender_id: {
                        required: '{!! trans('custom_validation.gender') !!}',
                        min: '{!! trans('custom_validation.gender') !!}',
                    },
                    district_id: {
                        required: '{!! trans('custom_validation.select_option') !!}',
                        min: '{!! trans('custom_validation.select_option') !!}'
                    },
                    address: {
                        required: '{!! trans('custom_validation.address') !!}',
                        minlength: "{!! trans('custom_validation.min_length',['min' => 5]) !!}",
                        maxlength: "{!! trans('custom_validation.max_length',['max' => 50]) !!}",
                    },
                    phone_number: {
                        required: '{!! trans('custom_validation.phone_number') !!}',
                    }
                },
                errorPlacement: function(error, element){
                    switch (element.attr('name')) {
                      case 'first_name':
                          $('#firstNameError').html(error)
                          break;
                      case 'last_name':
                          $('#lastNameError').html(error)
                          break;
                      case 'gender_id':
                          $('#genderError').html(error)
                          break;
                      case 'phone_number':
                          $('#phoneError').html(error)
                          break;
                      case 'convert_date':
                          $('#dateError').html(error)
                          break;
                      case 'district_id':
                          $('#districtError').html(error)
                          break;
                      case 'address':
                          $('#addressError').html(error)
                          break;
                  }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            if(editForm.valid()){
                const data = editForm.serializeArray()
                $.ajax({
                    url: ' {!! route('convert.update') !!}',
                    method: 'patch',
                    data: data,
                    complete: function ({status,responseJSON}) {
                        if(status === 200){
                            $('#editModal').modal('toggle')
                            const {message} = responseJSON
                            dataTable.ajax.reload(onReloadComplete)
                            toastr.success(message,'{{trans('common.success_label')}}')
                        }
                        if(status === 422){
                            const {errors} = responseJSON
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
            }
        }
    </script>
@endsection
