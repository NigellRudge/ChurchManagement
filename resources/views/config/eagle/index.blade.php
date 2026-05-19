@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title pt-1">
                        <span class="font-weight-bold text-lg text-dark">Eagle Groups</span>
                    </div>
                    <a class="btn btn-teal pt-2" href="{{ route('eagles-group.create') }}">
                        <i class="fas fa-plus-square mr-1"></i> Add Eagle Group
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive fix-topbar">
                        <table id="datatable" class="table table-striped table-bordered table-hover display compact nowrap">
                            <thead>
                            <tr class="text-dark">
                                <th>Name</th>
                                <th>Team Captain</th>
                                <th>Number of Members</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_css')
    @include('shared.datatable_css')
@endsection

@section('custom_js')
    @include('shared.datatable_js')
    <script>
        $(document).ready(function(){
            $("#datatable").DataTable({
                processing: true,
                language: datatableTrans,
                autoWidth:false,
                serverSide: true,
                "lengthMenu": [5, 10, 25, 50, 75, 100 ],
                pageLength:5,
                ajax: '{!! route('eagles-group.index') !!}',
                columns: [
                    { data: 'name', name: 'Name' },
                    { data: 'team_captain', name: 'Team Captain' },
                    {data: 'number_of_members',name: 'Number of Members'},
                    { data:'actions', name:'actions', orderable: false, searchable: false}
                ]
            });
        });
    </script>
@endsection
