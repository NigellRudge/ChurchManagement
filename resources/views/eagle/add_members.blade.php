@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="col-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title">Group Members</div>
                    <form action="{{route('eagle.add_members')}}" method="POST">
                        <label for="new_member">New Member</label>
                        <input type="text" id="new_member" name="new_member">
                        <button type="submit" class="btn btn-teal text-light">+</button>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive fix-topbar">
                        <table id="datatable" class="table table-striped table-bordered table-hover display compact nowrap">
                            <thead>
                            <tr class="text-dark">
                                <th>#</th>
                                <th>name</th>
                                <th>phone_number</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                    <div class="card-footer"></div>

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

            $('#member').select2({
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
            $("#datatable").DataTable({
                processing: true,
                serverSide: true,
                "lengthMenu": [5, 10, 25, 50, 75, 100 ],
                pageLength:5,
                ajax: '{!! route('eagle.members') !!}',
                columns: [

                    {data: 'id', name: '#'},
                    {data: 'name', name: 'phone_number'},
                    { data:'actions', name:'actions', orderable: false, searchable: false}
                ]
            });
        });

    </script>
@endsection
