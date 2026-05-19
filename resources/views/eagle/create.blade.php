@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Create Eagle Group
                    </div>
                </div>
                <form action="{{ route('eagle-group.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="name">Group Name</label>
                                    <input type="text" name="name" id="name" class="form-control">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="team_captain">Team Captain</label>
                                    <select name="team_captain" id="team_captain" class="form-control"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row d-flex justify-content-end">
                            <div class="col-2">
                                <button type="submit" class="btn btn-teal btn-block">Save</button>
                            </div>
                            <div class="col-2">
                                <button type="reset" class="btn btn-danger btn-block">Cancel</button>
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
                            searchTerm:params.term
                        }
                    },
                    dataType: 'json',
                    cache:true,
                    delay:200,
                    placeholder: 'Search member',
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
