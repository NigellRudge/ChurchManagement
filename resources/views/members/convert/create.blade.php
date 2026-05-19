@extends('layout.admin')

@section('content')
        <div class="row d-flex justify-content-center">
            <div class="col-10">
                <div class="card ">
                    <div class="card-header pb-1 pt-1">
                        <div class="mt-2 mb-2 ">
                            <h4 class="pt-1 card-title font-weight-bold">New convert</h4>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('convert.store')}}">
                        @csrf
                    <div class="card-body">
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="first_name" class="text-dark">First Name<span class="text-danger">*</span></label>
                                        <input name="first_name" placeholder="John" id="first_name" type="text" class="form-control"  required/>
                                        @error('first_name')
                                        <small id="nameError" class="form-text text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group mb-4">
                                        <label for="last_name"  class="text-dark">Last Name<span class="text-danger">*</span></label>
                                        <input name="last_name" id="last_name" placeholder="Doe" type="text" class="form-control" required />
                                        @error('last_name')
                                        <small id="codeError" class="form-text text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-4">
                                    <div class="form-group mb-4">
                                        <label for="gender_id"  class="text-dark">Gender<span class="text-danger">*</span></label>
                                        <select name="gender_id" id="gender_id" class="form-control" required>
                                            <option>Select Gender</option>
                                            @foreach($data['genders'] as $gender)
                                                <option value="{{$gender->id}}">{{$gender->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('code')
                                        <small id="codeError" class="form-text text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group mb-4">
                                        <label for="phone_number"  class="text-dark">Phone number<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text  bg-teal text-white"><i class="fas fa-phone-alt"></i></div>
                                            </div>
                                            <input name="phone_number" id="phone_number" placeholder="Type Code" type="text" class="form-control" required />
                                        </div>

                                        @error('phone_number')
                                        <small id="codeError" class="form-text text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group mb-4">
                                        <label for="convert_date"  class="text-dark">Convert Date<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text  bg-teal text-white"><i class="fas fa-calendar-alt"></i></div>
                                            </div>
                                            <input name="convert_date" id="convert_date" placeholder="Type Code" type="text" class="form-control" required />
                                        </div>
                                        @error('convert_date')
                                        <small id="codeError" class="form-text text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-row mb-4">
                                <div class="col">
                                    <div class="form-group mb-4">
                                        <label for="address"  class="text-dark">Address</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text  bg-teal text-white"> <i class="fas fa-house-user"></i></div>
                                            </div>
                                            <input name="address" id="address" placeholder="Main street # 1" type="text" class="form-control" />
                                        </div>

                                        @error('address')
                                        <small id="codeError" class="form-text text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group mb-4">
                                        <label for="district_id"  class="text-dark">District</label>
                                        <select name="district_id" id="district_id" class="form-control">
                                        </select>
                                        @error('district')
                                        <small id="codeError" class="form-text text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="row justify-content-end">
                                    <div class="col-2">
                                        <button type="submit" class="btn btn-teal btn-block"><i class="fas fa-save mr-2"></i>Save</button>
                                    </div>
                                    <div class="col-2">
                                        <button type="reset" class="btn  btn-danger btn-block"><i class="far fa-times-circle mr-2"></i>cancel</button>
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
    <link rel="stylesheet" href="{{ asset('vendor/select2/select2.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/date-ranger-picker/date-range-picker.css') }}" />
@endsection
@section('custom_js')
    <script type="text/javascript" src="{{asset('vendor/select2/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/momentjs/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/date-ranger-picker/date-ranger-picker.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('#convert_date').daterangepicker({
                singleDatePicker:true,
                autoUpdateInput: true,
                showDropdowns: true,
                minYear: 1901,
            })

            $('#district_id').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('district.json') !!}',
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
                    placeholder: 'Search District',
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
        });
    </script>
@endsection
