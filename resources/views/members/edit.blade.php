@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="card-title">
                        <span class="font-weight-bold text-lg text-dark">{{trans('common.edit_member_label')}}</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('members.update',['member' => $data['member']]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="first_name" class="font-weight-bold text-dark">{{trans('common.first_name_label')}}<span class="text-danger">*</span></label>
                                    <input value="{{$data['member']['first_name']}}" id="first_name"  name="first_name" class="form-control" placeholder="Enter first name" type="text" />
                                    @error('first_name')
                                    <small id="nameError" class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="last_name" class="font-weight-bold text-dark">{{trans('common.last_name_label')}}<span class="text-danger">*</span></label>
                                    <input value="{{$data['member']['last_name']}}" id="last_name" name="last_name" class="form-control" placeholder="Enter last name" type="text" />
                                    @error('last_name')
                                    <small id="nameError" class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="maiden_name" class="font-weight-bold text-dark">{{trans('common.maiden_name_label')}}</label>
                                    <input value="{{$data['member']['maiden_name']}}" id="maiden_name" name="maiden_name" class="form-control" type="text" />
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="birth_date" class="font-weight-bold text-dark">{{trans('common.birth_date_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="far fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input id="birth_date" name="birth_date" class="form-control" type="text" />
                                    </div>
                                    @error('birth_date')
                                    <small id="birth_date_error" class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-xl-2 col-lg-2 col-md-3">
                                <div class="form-group">
                                    <label for="gender_id" class="font-weight-bold text-dark">{{trans('common.gender_label')}}<span class="text-danger">*</span></label>
                                    <select name="gender_id" id="gender_id" class="form-control">
                                        <option value="0">{{trans('common.select_gender_label')}}</option>
                                        @foreach($data['genders'] as $gender)
                                            <option value="{{$gender->id}}" @if($gender->id == $data['member']['gender_id']) selected @endIf>{{$gender->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('gender')
                                    <small id="genderError" class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="address" class="font-weight-bold text-dark">{{trans('common.address_label')}}<span class="text-danger">*</span></label>
                                    <input value="{{$data['member']['address']}}" type="text" name="address" id="address" class="form-control" placeholder="{{trans('common.address_placeholder_label')}}">
                                    @error('address')
                                    <small id="nameError" class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="neighborhood" class="font-weight-bold text-dark">{{trans('common.neighborhood_label')}}</label>
                                    <input value="{{$data['member']['neighborhood']}}" type="text" name="neighborhood" id="neighborhood" class="form-control" placeholder="{{trans('common.address_placeholder_label')}}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="district_id" class="font-weight-bold text-dark">{{trans('common.district_label')}}<span class="text-danger">*</span></label>
                                    <select id="district_id" name="district_id" class="form-control">
                                        <option value="0">{{trans('common.select_district_label')}}</option>
                                        @foreach($data['districts'] as $district)
                                            <option value="{{$district->id}}" @if($district->id == $data['member']['district_id'])selected @endif>{{$district->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('district_id')
                                    <small id="nameError" class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="phone_number" class="font-weight-bold text-dark">{{trans('common.phone_number_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-phone-alt"></i>
                                            </div>
                                        </div>
                                        <input value="{{$data['member']['phone_number']}}"  type="number" id="phone_number" name="phone_number" class="form-control" placeholder="+597">
                                    </div>
                                    @error('phone_number')
                                    <small id="nameError" class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="email" class="font-weight-bold text-dark">{{trans('common.email_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-at"></i>
                                            </div>
                                        </div>
                                        <input value="{{$data['member']['email']}}" type="email" id="email" name="email" placeholder="email@gmail.com" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="id_number" class="font-weight-bold text-dark">{{trans('common.id_number_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-id-card"></i>
                                            </div>
                                        </div>
                                        <input type="text" id="id_number" value="{{$data['member']['id_number']}}" name="id_number" placeholder="email@gmail.com" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="convert_date" class="font-weight-bold text-dark">{{trans('common.convert_date_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="far fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="text" id="convert_date" name="convert_date"  class="form-control datepicker">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="job_description" class="font-weight-bold text-dark">{{trans('common.job_description_label')}}</label>
                                    <input value="{{$data['member']['job']}}" type="text" id="job_description" name="job_description" class="form-control" placeholder="{{trans('common.job_description_label')}}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="education" class="font-weight-bold text-dark">{{trans('common.education_label')}}</label>
                                    <select id="education" name="education_id" class="form-control">
                                        <option value="0">{{trans('common.select_education_label')}}</option>
                                        @foreach($data['education_types'] as $education)
                                            <option value="{{$education->id}}" @if($education->id == $data['member']['education_id']) selected @endif>{{$education->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-1 col-lg-1 col-md-2 col-sm-3">
                                <div class="form-group">
                                    <label for="baptized" class="font-weight-bold text-dark">{{trans('common.baptized_label')}}</label>
                                    <select id="baptized"  name="baptized" class="form-control">
                                        <option value="0">{{trans('common.all_label')}}</option>
                                        <option value="1" @if($data['member']['baptized'] == 1) selected @endif>{{trans('common.yes_label')}}</option>
                                        <option value="2" @if($data['member']['baptized'] == 0) selected @endif>{{trans('common.no_label')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="baptize_date" class="font-weight-bold text-dark">{{trans('common.baptized_date_label')}}</label>
                                    <input id="baptize_date" disabled type="text"  name="baptize_date"  class="form-control" />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="member_type_id" class="font-weight-bold text-dark">{{trans('common.member_type_label')}}<span class="text-danger">*</span></label>
                                    <select name="member_type_id" id="member_type_id" class="form-control">
                                        <option value="0">{{trans('common.select_mem_type_label')}}</option>
                                        @foreach($data['member_types'] as $type)
                                            <option value="{{$type->id}}" @if($data['member']['member_type_id'] == $type->id) selected @endif>{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('member_type_id')
                                    <small id="nameError" class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-9">
                                <div class="form-row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="image" class="font-weight-bold text-dark">{{trans('common.member_image_label')}}</label>
                                            <input type="file" id="image" name="image" onchange="previewImage(event)" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row mt-2">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="skills" class="font-weight-bold text-dark">{{trans('common.skills_label')}}</label>
                                            <textarea placeholder="{{trans('common.skills_placeholder_label')}}"  id="skills" name="skills" type="text" class="form-control" rows="4">{{$data['member']['skills']}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="notes" class="font-weight-bold text-dark">{{trans('common.notes_label')}}</label>
                                            <textarea placeholder="{{trans('common.notes_placeholder_label')}}" id="notes" name="notes" type="text" class="form-control" rows="4">{{$data['member']['notes']}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 d-flex justify-content-center align-items-center">
                                <div class="">
                                    <img src="{{$data['member']->image()}}" alt="user_image" id="preview_image" class="rounded" width="140" height="170" style="object-fit: cover">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer p-4 bg-white">
                        <div class="row  d-flex justify-content-end">
                            <button type="submit" class="btn btn-teal mr-1 col-xl-1 col-lg-2 col-md-4 col-sm-5 col-3">
                                <i class="fa fa-save mr-2"></i>
                                {{trans('common.save_label')}}
                            </button>
                            <button type="reset" class="btn btn-danger col-xl-1 col-lg-2 col-md-4 col-sm-5 col-3">
                                <i class="fa fa-ban mr-2"></i>
                                {{trans('common.cancel_label')}}
                            </button>
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
                startDate: '{!! $data['member']['birth_date'] !!}',
                autoUpdateInput: true,
                showDropdowns: true,
                minYear: 1901,
            })
            $('#baptize_date').daterangepicker({
                singleDatePicker:true,
                @if($data['member']['baptized'] == 1)
                    startDate: '{!! $data['member']['baptize_date'] !!}',
                @endif
                autoUpdateInput: true,
                showDropdowns: true,
                minYear: 1901,
            })
            @if($data['member']['convert_date'] != null)
                $('#convert_date').daterangepicker({
                    singleDatePicker:true,
                    startDate: '{!! $data['member']['convert_date'] !!}',
                    autoUpdateInput: true,
                    showDropdowns: true,
                    minYear: 1901,
                })
            @else
            $('#convert_date').daterangepicker({
                singleDatePicker:true,
                startDate: new Date(),
                autoUpdateInput: true,
                showDropdowns: true,
                minYear: 1901,
            })
            @endif

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
        function previewImage(event){
            console.log('hello')
            console.log(event.target.files[0])
            let reader = new FileReader()
            reader.onload = function(){
                //console.log(reader.result)
                const image = $('#preview_image')
                image.attr('src',reader.result)
                image.removeClass('d-none')
            }
            reader.readAsDataURL(event.target.files[0])
        }
    </script>
@endsection
