@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title">
                        <span class="font-weight-bold text-lg">Countries</span>
                    </div>
                    <a class="btn btn-teal pt-2" href="{{ route('country.create') }}">
                        <i class="fas fa-plus-square mr-1"></i> Add Country
                    </a>
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-bordered table-hover display compact nowrap">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Status</th>
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
                "lengthMenu": [5, 10, 25, 50, 75, 100 ],
                pageLength:5,
                serverSide: true,
                ajax: '{!! route('country.index') !!}',
                columns: [
                    { data: 'name', name: 'Name' },
                    { data: 'code', name: 'Code' },
                    { data: 'status', name: 'Status'},
                    { data:'actions', name:'actions', orderable: false, searchable: false}
                ]
            });
        });
    </script>
@endsection
