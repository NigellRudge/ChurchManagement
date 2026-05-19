@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="card-title">
                        <span class="font-weight-bold text-dark  text-lg">Promote Convert To member</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('members.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="first_name">First Name<span class="text-danger">*</span></label>
                                    <input id="first_name"  name="first_name" class="form-control" value="{{ $data['convert']['first_name'] }}" type="text" disabled />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="last_name">Last Name<span class="text-danger">*</span></label>
                                    <input id="last_name" name="last_name" class="form-control" value="{{ $data['convert']['last_name'] }}" type="text" disabled />
                                    @error('last_name')
                                    <small id="nameError" class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="birth_date">Birth Date<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <i class="far fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input id="birth_date" name="birth_date" class="form-control"  type="text"  />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="address">Address<span class="text-danger">*</span></label>
                                    <input value="{{ $data['convert']['address'] }}" type="text" disabled name="address" id="address" class="form-control">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="district_id">District<span class="text-danger">*</span></label>
                                    <select id="district_id" name="district_id" class="form-control" disabled>
                                        @foreach($data['districts'] as $district)
                                            @if($district->id == $data['convert']['district_id'] )
                                                <option selected value="{{$data['convert']['district_id']}}">{{$district->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="phone_number">Phone number<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <i class="fas fa-phone-alt"></i>
                                            </div>
                                        </div>
                                        <input type="number" id="phone_number" name="phone_number" class="form-control" value="{{$data['convert']['phone_number']}}" disabled>
                                    </div>
                                    @error('phone_number')
                                    <small id="nameError" class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <i class="fas fa-at"></i>
                                            </div>
                                        </div>
                                        <input type="email" id="email" name="email" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="convert_date">Convert date</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <i class="far fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="text" id="convert_date" disabled name="convert_date" placeholder="select a date" value="{{ $data['convert']['convert_date'] }}" class="form-control datepicker">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="baptized">Baptized</label>
                                    <select id="baptized"  name="baptized" class="form-control">
                                        <option value="0">Please select</option>
                                        <option value="1">Yes</option>
                                        <option value="2">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="baptize_date">Baptize Date</label>
                                    <input id="baptize_date" disabled type="text"  name="baptize_date" placeholder="select a date" class="form-control" />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="member_type_id">Member Type<span class="text-danger">*</span></label>
                                    <select name="member_type_id" id="member_type_id" class="form-control">
                                        <option value="0">Select Member type</option>
                                        @foreach($data['member_types'] as $type)
                                            <option value="{{$type->id}}">{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('member_type_id')
                                    <small id="nameError" class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="py-2">
                            <div class="row justify-content-end">
                                <div class="col-xl-1 col-lg-3 col-4">
                                    <button type="submit" class="btn btn-teal btn-block">Save</button>
                                </div>
                                <div class="col-xl-1 col-lg-3 col-4">
                                    <button type="reset" class="btn btn-danger btn-block">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('vendor/select2/select2.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/date-ranger-picker/date-range-picker.css') }}" />
@endsection

@section('custom_js')
    <script type="text/javascript" src="{{asset('vendor/select2/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/momentjs/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/date-ranger-picker/date-ranger-picker.min.js')}}"></script>

    <script>
        $(document).ready(function(){
            console.log("hello world")
            $('#birth_date').daterangepicker({
                singleDatePicker:true,
                autoUpdateInput: true,
                showDropdowns: true,
                minYear: 1901,
            })
            $('#baptize_date').daterangepicker({
                singleDatePicker:true,
                autoUpdateInput: true,
                showDropdowns: true,
                opens:'left',
                minYear: 1901,
            })
            $('#convert_date').daterangepicker({
                singleDatePicker:true,
                autoUpdateInput: true,
                showDropdowns: true,
                minYear: 1901,
            })

            $('#baptized').on('change',function(event){
                let value = event.target.value;
                if(value === '1'){
                    $('#baptize_date').attr('disabled',false);
                }
                else{
                    $('#baptize_date').attr('disabled',true);
                }
            })

        });
    </script>
@endsection
