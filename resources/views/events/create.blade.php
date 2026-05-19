@extends('layout.admin')


@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-10">
            <div class="container">
                <div class="card rounded">
                    <div class="card-header">
                        <div class="card-title">
                            <span class="text-dark font-weight-bold text-lg">Create Event</span>
                        </div>
                    </div>
                    <form method="POST"  autocomplete="off" action="{{ route('events.store') }}">
                        @csrf
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="title" class="text-dark">Title<span class="text-danger">*</span></label>
                                        <input type="text" id="title" name="title" class="form-control">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="location" class="text-dark">Location<span class="text-danger">*</span></label>
                                        <input id="location" name="location" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="start_date" class="text-dark">Start Date<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-teal text-light">
                                                    <span><i class="fa fa-calendar"></i></span>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" id="start_date" name="start_date">
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="end_date" class="text-dark">End Date</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-teal text-light">
                                                    <span><i class="fa fa-calendar"></i></span>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" id="end_date" name="end_date">
                                            <div class="input-group-append" id="cancel_btn">
                                                <div class="input-group-text text-danger">
                                                    <span class="font-weight-bold">X</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row justify-content-end">
                                <div class="col-2">
                                    <button type="submit" class="btn btn-teal btn-block">Save</button>
                                </div>
                                <div class="col-2">
                                    <a class="btn btn-danger btn-block">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('custom_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/date-ranger-picker/date-range-picker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/timepicker/timepicker.min.css') }}" />

@endsection

@section('custom_js')
    <script type="text/javascript" src="{{asset('vendor/momentjs/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/date-ranger-picker/date-ranger-picker.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/timepicker/timepicker.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            const now = new Date();
            const minYear = now.getFullYear()
            const minDate = `${now.getMonth()+1}/${now.getDate()<10? '0'+now.getDate() : now.getDate()}/${now.getFullYear()}`;
            const startDate = $('#start_date')
            const endDate = $('#end_date');
            let selectedDate = minDate

            startDate.daterangepicker({
                showWeekNumbers:true,
               singleDatePicker:true,
                timePickerIncrement:15,
               timePicker:true,
               locale: {
                   format:'MM/DD/YYYY hh:mm'
               },
               autoUpdateInput: true,
               showDropdowns: true,
               minYear: minYear,
                minDate:minDate
           },function(chosen_date){
                selectedDate = chosen_date.format('MM/DD/YYYY hh:mm')
               startDate.val(selectedDate)
           })

            endDate.daterangepicker({
                singleDatePicker:true,
                timePickerIncrement:15,
                timePicker:true,
                locale: {
                    format:'MM/DD/YYYY hh:mm',
                },
                autoUpdateInput: false,
                showDropdowns: true,
                minYear: minYear,
                minDate:selectedDate
            },function(chosen_date){
                endDate.val(chosen_date.format('MM/DD/YYYY hh:mm'))
            })

            $('#cancel_btn').on('click',function(event){
                endDate.val(null)
            })
        });
    </script>
@endsection
