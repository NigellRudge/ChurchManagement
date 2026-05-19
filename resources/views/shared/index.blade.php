@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title pt-1">
                        <span class="font-weight-bold text-lg text-dark">{{ $data['model_name'] }}</span>
                    </div>
                    <a class="btn btn-teal pt-2" href="{{ $data['create_route'] }}">
                        <i class="fas fa-plus-square mr-1"></i> Add {{ $data['model_name'] }}
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive fix-topbar">
                        <table id="datatable" class="table table-striped table-bordered table-hover display compact nowrap">
                            <thead>
                            <tr class="text-dark">
                                @foreach($data['columns'] as $column)
                                    <th> {{ ucfirst($column) }}</th>
                                @endforeach
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
                serverSide: true,
                "lengthMenu": [5, 10, 25, 50, 75, 100 ],
                pageLength:5,
                ajax: '{!! $data['index_route'] !!}',
                columns: [
                    @foreach($data['columns'] as $column)
                    {data: '{!! $column !!}', name: '{!! ucfirst($column) !!}'},
                    @endforeach
                    { data:'actions', name:'actions', orderable: false, searchable: false}
                ]
            });
        });
    </script>
@endsection
