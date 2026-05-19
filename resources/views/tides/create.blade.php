@extends('layout.admin')

@section('content')
    <div class="container justify-content-center">
        <div class="row">
            <div class="col-10">
                <form method="POST" action="{{ route('tides.store')}}">
                    @csrf
                    <div class="card ">
                        <div class="card-header">
                            <div class="">
                                <span class="card-title font-weight-bold">Add Tide</span>
                            </div>
                        </div>
                        <div class="card-body pl-5 pr-5 pt-4 pb-3">
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="member_id" class="text-dark">Member<span class="text-danger">*</span></label>
                                        <select name="member_id" data-placeholder="Select member"  id="member_id" type="text" class="form-control">

                                        </select>
                                        @error('member')
                                        <small id="nameError" class="form-text text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="date">Date<span class="text-danger">*</span></label>
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
                            <div class="form-row">
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="currency_id">Currency<span class="text-danger">*</span></label>
                                        <select name="currency_id" id="currency_id" data-placeholder="Select currency" class="form-control">
                                            @foreach($data['currencies'] as $currency)
                                                <option value="{{$currency->id}}">{{$currency->code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="amount"  class="text-dark">Amount<span class="text-danger">*</span></label>
                                        <input name="amount" step="0.01" min="0.01" max="100000000" id="amount" placeholder="$0.00" type="number" class="form-control" />
                                        @error('amount')
                                        <small id="codeError" class="form-text text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="container">
                                <div class="row d-flex justify-content-end">
                                    <div class="col-2">
                                        <button type="submit" class="btn btn-teal btn-block"><i class="fas fa-save mr-2"></i>Save</button>
                                    </div>
                                    <div class="col-2">
                                        <button type="reset" class="btn  btn-outline-danger btn-block"><i class="far fa-times-circle mr-2"></i>cancel</button>
                                    </div>
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

            $('#member_id').select2({
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

            $('#date').daterangepicker({
                singleDatePicker:true,
                autoUpdateInput: true,
                showDropdowns: true,
                minYear: 1901,
            })

        });

    </script>
@endsection
