@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between">
                    <div class="pt-1">
                        <span class="font-weight-bold text-lg text-dark">{{trans('common.events_label')}}</span>
                    </div>
                    <div class="d-flex flex-row">
                        <a class="btn btn-info pt-2 font-weight-bold mr-1" href="{{ route('events.calendar') }}">
                            {{trans('common.calendar_label')}}
                            <i class="ml-1 fas fa-calendar"></i>
                        </a>
                        <a class="btn btn-teal pt-2 font-weight-bold" onclick="openAddModal(event)">
                            {{trans('common.add_event_label')}}
                            <i class="ml-1 fas fa-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="datatable" class="table border-right border-left border-bottom display compact nowrap">
                        <thead>
                            <tr class="text-dark">
                                <th>{{trans('common.title_label')}}</th>
                                <th>{{trans('common.date_label')}}</th>
                                <th>{{trans('common.time_label')}}</th>
                                <th>{{trans('common.location_label')}}</th>
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

    <div class="modal" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.add_event_label')}}</h5>
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
                                    <label for="title" class="text-dark font-weight-bold">{{trans('common.title_label')}}<span class="text-danger">*</span></label>
                                    <input type="text" id="title" name="title" class="form-control">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="date" class="text-dark font-weight-bold">{{trans('common.date_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <span><i class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" autocomplete="off" id="date" name="date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="location" class="text-dark font-weight-bold">{{trans('common.location_label')}}</label>
                                    <input id="location" name="location" type="text" class="form-control" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="description" class="text-dark font-weight-bold">{{trans('common.description_label')}}<span class="text-danger">*</span></label>
                                    <textarea id="description" name="description" rows="4" type="text" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-1 mb-2">
                            <div class="col-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_paid_event" name="is_paid_event">
                                        <label class="form-check-label text-dark font-weight-bold" for="is_paid_event">
                                            Paid Event
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="currency_id" class="font-weight-bold">{{trans('common.currency_label')}}</label>
                                            <select class="form-control" id="currency_id" name="currency_id" disabled>
                                                @foreach($data['currencies'] as $currency)
                                                    <option value="{{$currency['id']}}">{{$currency['code']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="ticket_price" class="font-weight-bold">{{trans('common.ticket_price_label')}}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text bg-teal text-light">
                                                        <span><i class="fa fa-dollar-sign"></i></span>
                                                    </div>
                                                </div>
                                                <input type="number" min="0.0" max="1000000" placeholder="$0.00" disabled step="0.05" id="ticket_price" name="ticket_price" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="last_payment_date" class="font-weight-bold">{{trans('common.last_payment_date_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <span><i class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>
                                        <input type="text" id="last_payment_date" disabled name="last_payment_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="should_register" name="should_register">
                                        <label class="form-check-label text-dark font-weight-bold" for="should_register">
                                            {{trans('common.registration_label')}}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="registration_fee" class="font-weight-bold">{{trans('common.registration_fee_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <span><i class="fa fa-dollar-sign"></i></span>
                                            </div>
                                        </div>
                                        <input type="text" id="registration_fee" min="0.0" max="1000000" disabled step="0.05" placeholder="$0.00" name="registration_fee" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="last_register_date" class="font-weight-bold">{{trans('common.last_registration_date_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <span><i class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>
                                        <input type="text" id="last_register_date" disabled  name="last_register_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="add_submitBtn" disabled class="btn btn-teal">
                            <i class="fas fa-save"></i>
                            {{trans('common.save_label')}}
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-ban"></i>
                            {{trans('common.no_label')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="remove_form">
                    @csrf
                    <input type="hidden" name="remove_event_id" id="remove_event_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-4">
                                Are you sure you want to remove this Event:<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_event"></div> ?
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

    <div class="modal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title font-weight-bold text-light" id="editModalLabel">Edit Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="edit_form">
                    @csrf
                    <input type="hidden" id="edit_event_id" name="edit_event_id">
                    <div class=" mt-2 pl-3 pr-3">
                        <div class=" mt-2 pl-3 pr-3">
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="edit_title" class="text-dark font-weight-bold">Title<span class="text-danger">*</span></label>
                                        <input type="text" id="edit_title" name="edit_title" class="form-control">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="edit_date" class="text-dark font-weight-bold">Date<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-teal text-light">
                                                    <span><i class="fa fa-calendar"></i></span>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" autocomplete="off" id="edit_date" name="edit_date">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="edit_location" class="text-dark font-weight-bold">Location<span class="text-danger">*</span></label>
                                        <input id="edit_location" name="edit_location" type="text" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row mt-1 mb-2">
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="edit_is_paid_event" name="edit_is_paid_event">
                                            <label class="form-check-label text-dark font-weight-bold" for="edit_is_paid_event">
                                                Paid Event
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="edit_currency_id" class="font-weight-bold">Currency</label>
                                                <select class="form-control" id="edit_currency_id" name="currency_id" disabled>
                                                    @foreach($data['currencies'] as $currency)
                                                        <option value="{{$currency['id']}}">{{$currency['code']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="edit_ticket_price" class="font-weight-bold">Ticket price</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-teal text-light">
                                                            <span><i class="fa fa-dollar-sign"></i></span>
                                                        </div>
                                                    </div>
                                                    <input type="number" min="0.0" max="1000000" placeholder="$0.00" disabled step="0.05" id="edit_ticket_price" name="ticket_price" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_last_payment_date" class="font-weight-bold">Last Payment Date</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-teal text-light">
                                                    <span><i class="fa fa-calendar"></i></span>
                                                </div>
                                            </div>
                                            <input type="text" id="edit_last_payment_date" disabled name="last_payment_date" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="edit_should_register" name="should_register">
                                            <label class="form-check-label text-dark font-weight-bold" for="edit_should_register">
                                                Add Registration?
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_registration_fee" class="font-weight-bold">Registration fee</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-teal text-light">
                                                    <span><i class="fa fa-dollar-sign"></i></span>
                                                </div>
                                            </div>
                                            <input type="text" id="edit_registration_fee" min="0.0" max="1000000" disabled step="0.05" placeholder="$0.00" name="registration_fee" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_last_register_date" class="font-weight-bold">Last Registration Date</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-teal text-light">
                                                    <span><i class="fa fa-calendar"></i></span>
                                                </div>
                                            </div>
                                            <input type="text" id="edit_last_register_date" disabled  name="last_register_date" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="edit_submitBtn" disabled class="btn btn-teal">
                            <i class="fas fa-save"></i>
                            Save
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-ban"></i>
                            Cancel
                        </button>
                    </div>
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
        const addModal = $('#addModal')
        const addForm = $('#add_form')

        const editModal = $('#editModal')
        const editForm = $('#edit_form')

        const removeModal = $('#removeModal')
        const removeForm = $('#remove_form')

        $(document).ready(function(){
            const dataTable = $("#datatable").DataTable({
                processing: true,
                language: datatableTrans,
                autoWidth:false,
                serverSide: true,
                "lengthMenu": [5, 10, 25, 50, 75, 100 ],
                pageLength:5,
                ajax: '{!! route('events.index') !!}',
                columns: [
                    { data: 'title', name: 'title' },
                    { data: 'date', name: 'date' },
                    {data: 'time',name: 'time'},
                    { data: 'location', name: 'location'},
                    { data:'actions', name:'actions', orderable: false, searchable: false}
                ]
            });

            addForm.validate({
                rules:{
                    title:{
                        required:true,
                        minlength:5,
                    },
                    location: {
                        required:true,
                        minlength: 5
                    },
                    ate: {
                        required:true,
                        date:true
                    }
                },
                messages:{
                    title: "please enter a title",
                    location: "please enter a location",
                    date: "please enter a start_date",
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            addForm.submit(function($event){
                $event.preventDefault();
                let data = addForm.serialize();
                console.log(data)
                $.ajax({
                    url: ' {!! route('events.StoreAjax') !!}',
                    method: 'post',
                    data: data,
                    complete: function (xhr,status) {
                        console.log(xhr.responseJSON)
                        let message = xhr.responseJSON.message
                        if(xhr.status === 201){
                            addModal.modal('hide')
                            dataTable.ajax.reload()
                            toastr.success(message,'Success')
                        }
                    }
                })
            })


            removeForm.submit(function($event){
                $event.preventDefault()
                let data = $(this).serialize()
                console.log(data)
                $.ajax({
                    url: '{!! route('events.delete') !!}',
                    method: 'delete',
                    data: data,
                    complete: function(xhr){
                        if(xhr.status === 201){
                            let message = xhr.responseJSON.message
                            toastr.warning(message,'Success')
                            removeModal.modal('hide')
                            dataTable.ajax.reload()
                        }
                    }
                })
            })
            $(".modal").on("hidden.bs.modal", function() {
                let title = $("#title")
                title.val('');
                title.removeClass('is-valid');
                title.removeClass('is-invalid');

                let location = $("#location")
                location.val('');
                location.removeClass('is-valid');
                location.removeClass('is-invalid');
                location.attr('disabled',true)

                let submitBtn = $('#add_submitBtn')
                submitBtn.attr('disabled',true)

                let paidEvent = $('#is_paid_event')
                paidEvent.prop('checked',false)

                let shouldRegister = $('#should_register')
                shouldRegister.prop('checked',false)

                let ticketPrice = $('#ticket_price')
                ticketPrice.val('')
                ticketPrice.attr('disabled',true)

                let lastPaymentDate = $('#last_payment_date')
                lastPaymentDate.val('')
                lastPaymentDate.attr('disabled',true)

                let registrationFee = $('#registration_fee')
                registrationFee.val('')
                registrationFee.attr('disabled',true)

                let lastRegisterDate = $('#last_register_date')
                lastRegisterDate.val('')
                lastRegisterDate.attr('disabled',true)

                let description = $('#description')
                description.val('');
                description.removeClass('is-valid');
                description.removeClass('is-invalid');
            });
        });

        function openAddModal($event){
            $event.preventDefault();
            console.log('Open !!!')
            const now = new Date();
            const minYear = now.getFullYear()
            console.log(`minyear: ${minYear}`)
            const minDate = `${now.getMonth()+1}/${now.getDate()<10? '0'+now.getDate() : now.getDate()}/${now.getFullYear()}`;
            console.log(`minDate: ${minDate}`)
            const date = $('#date')
            const title = $('#title')
            const location = $('#location')
            const add_submitBtn = $('#add_submitBtn')
            let selectedDate = minDate
            const paidEvent = $('#is_paid_event')
            const shouldRegister = $('#should_register')
            const ticketPrice = $('#ticket_price')
            const registrationFee = $('#registration_fee')
            const lastRegisterDate = $('#last_register_date')
            const lastPaymentDate = $('#last_payment_date')
            const currencyId = $('#currency_id')
            date.daterangepicker({
                showWeekNumbers:true,
                singleDatePicker:true,
                timePickerIncrement:15,
                timePicker:true,
                timePicker24Hour: true,
                locale: {
                    format:'MM/DD/YYYY H:mm'
                },
                autoUpdateInput: false,
                showDropdowns: true,
                minYear: minYear,
                minDate:minDate
            })
            date.on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY H:mm'));
                console.log($(this).val())
            });

            $('#cancel_btn').on('click',function(event){

            })
            title.on('change',function(){
                location.attr('disabled',false)
            })
            location.on('change',function(){
                add_submitBtn.attr('disabled',false)
            })

            paidEvent.change(function(){
                if(this.checked){
                    ticketPrice.attr('disabled',false)
                    currencyId.attr('disabled',false)
                    lastPaymentDate.attr('disabled',false)
                    lastPaymentDate.daterangepicker({
                        showWeekNumbers:true,
                        singleDatePicker:true,
                        timePickerIncrement:15,
                        drops:'up',
                        locale: {
                            format:'MM/DD/YYYY'
                        },
                        autoUpdateInput: false,
                        showDropdowns: true,
                        minYear: minYear,
                        minDate:minDate
                    })
                    lastPaymentDate.on('apply.daterangepicker', function(ev, picker) {
                            $(this).val(picker.startDate.format('MM/DD/YYYY'));
                    });
                }
                else{
                    ticketPrice.attr('disabled',true)
                    lastPaymentDate.val('')
                    lastPaymentDate.attr('disabled',true)
                    currencyId.attr('disabled',true)
                }
            })

            shouldRegister.change(function(){
                if(this.checked){
                    registrationFee.attr('disabled',false)
                    lastRegisterDate.attr('disabled',false)
                    lastRegisterDate.daterangepicker({
                        showWeekNumbers:true,
                        singleDatePicker:true,
                        timePickerIncrement:15,
                        drops:'up',
                        locale: {
                            format:'MM/DD/YYYY'
                        },
                        autoUpdateInput: false,
                        showDropdowns: true,
                        minYear: minYear,
                        minDate:minDate
                    })
                    lastRegisterDate.on('apply.daterangepicker', function(ev, picker) {
                            $(this).val(picker.startDate.format('MM/DD/YYYY'));
                        });
                }
                else{
                    registrationFee.attr('disabled',true)
                    lastRegisterDate.val('')
                    lastRegisterDate.attr('disabled',true)
                }
            })

            addModal.modal('show')
        }

        function openEditModal($event){
            $event.preventDefault();
            let eventId = $event.target.getAttribute('data-id')
            console.log(eventId)
        }

        function openRemoveModal($event){
            $event.preventDefault()
            const eventId = $event.target.getAttribute('data-id')
            const title = $event.target.getAttribute('data-title')
            const date = $event.target.getAttribute('data-date')
            $('#confirm_event').html(` ${title} ${date}`)
            $('#remove_event_id').val(eventId)
            removeModal.modal('show')
        }
    </script>
@endsection
