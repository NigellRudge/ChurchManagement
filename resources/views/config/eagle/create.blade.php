@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="col-10">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <span class="font-weight-bold">Add Eagle Group</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('eagles-group.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="name">Group Name<span class="text-danger">*</span></label>
                                    <input id="name"  name="name" class="form-control" placeholder="Harpy eagle" type="text" />
                                    @error('name')
                                    <small id="nameError" class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="team_captain">Team Captain<span class="text-danger">*</span></label>
                                    <select name="team_captain" type="text" data-placeholder="Select Team Captain" id="team_captain" class="form-control">
                                    </select>
                                    @error('team_captain')
                                    <small id="nameError" class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="py-2">
                            <div class="row justify-content-end">
                                <div class="col-3">
                                    <button type="submit" class="btn btn-teal btn-block">Save</button>
                                </div>
                                <div class="col-3">
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
    <link rel="stylesheet" href="{{ asset('vendor/select2/select2.bootstrap4.min.css') }}" />
@endsection

@section('custom_js')
    <script type="text/javascript" src="{{asset('vendor/select2/select2.min.js')}}"></script>

    <script>
        $(document).ready(function(){
            $('#team_captain').select2({
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
