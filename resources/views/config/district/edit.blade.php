@extends('layout.admin')

@section('content')
    <div class="container justify-content-center">
        <div class="row">
            <div class="col-10">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">
                            <span class="pt-1 font-weight-bold text-lg">Edit District</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('district.update',['district'=>$data['district']])}}">
                        @csrf
                        @method('PATCH')
                        <div class="card-body pl-5 pr-5 pt-4 pb-3">
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="name" class="text-dark">Name</label>
                                        <input name="name" value="{{ $data['district']['name']}}" placeholder="Type name" id="name" type="text" class="form-control" />
                                        @error('name')
                                        <small id="nameError" class="form-text text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group mb-4">
                                        <label for="code"  class="text-dark">Code</label>
                                        <input name="code" value="{{ $data['district']['code']}}" id="code" placeholder="Type Code" type="text" class="form-control" />
                                        @error('code')
                                        <small id="codeError" class="form-text text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group mb-4">
                                        <div class="form-check">
                                            <input type="checkbox" @if($data['district']['active'] == 1) checked @endif class="form-check-input" name="active" id="active">
                                            <label for="active"  class="text-dark">Active</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="container">
                                <div class="row d-flex justify-content-end">
                                    <div class="col-3">
                                        <button type="submit" class="btn btn-teal btn-block"><i class="fas fa-save mr-2"></i>Save</button>
                                    </div>
                                    <div class="col-3">
                                        <button type="reset" class="btn  btn-danger btn-block"><i class="far fa-times-circle mr-2"></i>cancel</button>
                                    </div>
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
    <link rel="stylesheet" href="{{ asset('vendor/select2/select2.min.css') }}" />
@endsection

@section('custom_js')
    <script type="text/javascript" src="{{asset('vendor/select2/select2.min.js')}}"></script>
    <script>
        $(document).ready(function(){

            $('#country').select2({
                ajax: {
                    url: '{!! route('country.list.json') !!}',
                    type: 'post',
                    data: function(params){
                        return {
                            _token: '{!! csrf_token() !!}',
                            searchTerm:params.term
                        }
                    },
                    dataType: 'json',
                    cache:true,
                    delay:200,
                    placeholder: 'Search Country',
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
