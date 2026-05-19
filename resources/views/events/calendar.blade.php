@extends('layout.admin')

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-11">
            <div class="card card-primary">
                <div class="card-body" style="">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
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
                                        <input type="text"  readonly class="form-control" autocomplete="off" id="date" name="date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="location" class="text-dark font-weight-bold">{{trans('common.location_label')}}</label>
                                    <input id="location" name="location" type="text" class="form-control" >
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
                                            {{trans('common.paid_event_label')}}
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
                            {{trans('common.cancel_label')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="showModalLabel">{{trans('common.event_details_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class="p-4">
                    <div class="row mb-3">
                        <div class="col">
                            <div class="font-weight-bold">
                                {{trans('common.title_label')}}: <span id="show_title" class="font-weight-normal text-dark ml-1"></span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="font-weight-bold">
                                {{trans('common.date_label')}}: <span id="show_date" class="font-weight-normal text-dark ml-1"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <div class="font-weight-bold">
                                {{trans('common.location_label')}}: <span id="show_location" class="font-weight-normal text-dark ml-1"></span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="font-weight-bold">
                                {{trans('common.time_label')}}: <span id="show_time" class="font-weight-normal text-dark ml-1"></span>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-2">
                    <div class="font-weight-bold mb-4" style="color: rgba(125,123,123,0.8); font-size: 90%">{{trans('common.pricing_info_label')}}</div>

                    <div class="row mb-3">
                        <div class="col">
                            <div class="font-weight-bold">
                                {{trans('common.ticket_price_label')}}: <span id="show_price" class="font-weight-normal text-dark ml-1"></span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="font-weight-bold">
                                {{trans('common.last_payment_date_label')}}: <span id="show_last_payment_date" class="font-weight-normal text-dark ml-1"></span>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-2">
                    <div class="font-weight-bold mb-4" style="color: rgba(125,123,123,0.8); font-size: 90%">
                        {{trans('common.registration_info')}}</div>

                    <div class="row mb-3">
                        <div class="col">
                            <div class="font-weight-bold">
                                {{trans('common.registration_fee_label')}}: <span id="show_registration_price" class="font-weight-normal text-dark ml-1"></span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="font-weight-bold">
                                {{trans('common.last_registration_date_label')}}: <span id="show_last_register_date" class="font-weight-normal text-dark ml-1"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-teal font-weight-bold" data-dismiss="modal">
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
    <script src='fullcalendar/core/locales-all.js'></script>
    <script>
        const addEventModal = $('#addModal')
        $(document).ready(function () {
            const date = new Date();
            const addForm = $('#add_form')
            console.log(date)
            const d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear();
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'listWeek',
                locale: '{{Session::get('lang_code')}}',
                height: 700,
                selectable: true,
                plugins: ['bootstrap', 'interaction', 'dayGrid', 'timeGrid','listMonth'],
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay',
                },
                'themeSystem': 'bootstrap',
                //Random default events
                events:  function({endStr, startStr}, successCallback, failureCallback){
                    $.ajax({
                        url: '{!! route('events.calendarEvents') !!}',
                        method: 'post',
                        data: {
                            '_token': '{!! csrf_token() !!}',
                            'start_date': startStr,
                            'end_date': endStr,
                            'calendar': true
                        },
                        complete: function(xhr){
                            if(xhr.status === 201){
                                let events = xhr.responseJSON.events
                                console.log(events)
                                successCallback(events)
                            }
                        }
                    })
                },
                editable: true,
                droppable: true, // this allows things to be dropped onto the calendar !!!
                drop: function (info) {

                },
                eventClick:function (element) {
                    let eventId = element.event.id
                    showEvent(eventId)
                    $('#showModal').modal('show')
                },
                viewRender: function(view,element){
                    console.log('Yo dude')
                },
                select: function ({startStr, endStr}){
                    openAddModal(startStr)
                }
            });
            calendar.render();

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
                            addEventModal.modal('hide')
                            calendar.refetchEvents()
                            toastr.success(message,'Success')
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
        })

        function showEvent(eventId){
            let title = $('#show_title')
            let date = $('#show_date')
            let location = $('#show_location')
            let price = $('#show_price')
            let deposit = $('#show_registration_price')
            let lastPaymentDate = $('#show_last_payment_date')
            let lastRegisterDate = $('#show_last_register_date')
            let time = $('#show_time')
            $.ajax({
                url: '{!! route('events.getEventByIdJson') !!}',
                method: 'post',
                data: {
                    _token: '{!! csrf_token() !!}',
                    event_id: eventId
                },
                complete: function(xhr){
                    let event =  xhr.responseJSON.event
                    title.html(event.title)
                    date.html(event.date)
                    location.html(event.location)
                    deposit.html(event.reg_price !== null ? `${event.currency_code} ${event.reg_price}` : 'No Price')
                    lastPaymentDate.html(event.last_payment_date !== null ? event.last_payment_date : 'No Info')
                    lastRegisterDate.html(event.last_registration_date !== null ? event.last_registration_date : 'No Info')
                    time.html(event.time !== null ? event.time : 'No Info')
                    price.html(event.price !== null ? event.price : 'No Price')
                    console.log(event)
                }
            })
        }

        function openAddModal(startDate){
            const now = new Date()
            const minYear = now.getFullYear()
            console.log(`minyear: ${minYear}`)
            const minDate = `${now.getMonth()+1}/${now.getDate()<10? '0'+now.getDate() : now.getDate()}/${now.getFullYear()}`;
            const date = $('#date')
            const title = $('#title')
            const location = $('#location')
            const add_submitBtn = $('#add_submitBtn')
            const paidEvent = $('#is_paid_event')
            const shouldRegister = $('#should_register')
            const ticketPrice = $('#ticket_price')
            const registrationFee = $('#registration_fee')
            const lastRegisterDate = $('#last_register_date')
            const lastPaymentDate = $('#last_payment_date')
            const currencyId = $('#currency_id')
            date.val(`${startDate} 12:00`)
            addEventModal.modal('show')
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
        }
    </script>
@endsection
