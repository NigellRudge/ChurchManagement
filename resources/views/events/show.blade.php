@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="col-8">
            <div class="card" style="height: 250px">
                <div class="card-header">
                    <div class="card-title d-flex justify-content-between">
                        <span class="font-weight-bold">
                            Event Details
                        </span>
                        <div class="">
                            <a href="#" class="btn btn-info text-light font-weight-bold">Edit event</a>
{{--                            <a href="#" class="btn btn-primary">Add Budget</a>--}}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row font-weight-bold ">
                        <div class="col pl-4">
                            <div class="mb-2">
                                Title: <span class="text-dark ml-2">{{ $data['event']['title'] }}</span>
                            </div>
                            <div class="mb-2">
                                <i class="fa fa-calendar mx-1"></i>Date: <span class="text-dark ml-2">{{ $data['event']['date'] }}</span>
                            </div>
                            <div class="mb-2">
                                <i class="fa fa-clock mx-1"></i> Time:<span class="text-dark ml-2">{{ $data['event']['time'] }}</span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <i class="fa fa-map-pin mx-1"></i>Location:  <span class="text-dark ml-2">{{ $data['event']['location'] }}</span>
                            </div>
                            <div class="mb-2">
                                <i class="fa fa-cog mx-1"></i>Status:<span class="text-dark ml-2">
                                @if($data['event']['date'] < \Carbon\Carbon::now())
                                    <span class="text-secondary">Passed</span>
                                @else
                                    <span class="text-success">Upcoming</span>
                                @endif
                                </span>
                            </div>
                            <div class="mb-2">
                                <i class="fa fa-dollar-sign mx-1"></i>Entree type:<span class="text-dark ml-2">{{$data['event']['entree_type']}}</span>
                            </div>
                            <div class="mb-2">
                                <i class="fa fa-money-bill mx-1"></i>Entree fee: <span class="text-dark ml-2">{{$data['event']['price']}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card" style="height: 250px">
                <div class="card-header">
                    <div class="card-title">
                        <span class="font-weight-bold">
                           Other Info
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="font-weight-bold">Description</div>
                    <div class="">
                        Speakers for this conference include :
                        Speaker 1,
                        Speaker 2
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span class="font-weight-bold">Budget</span>
                    <a class="btn btn-teal"><i class="fa fa-edit font-weight-normal mx-1"></i>Edit budget</a>
                </div>
                    <div class="card-body">
                        <div class="table-responsive fix-topbar">
                            <table id="datatable" class="table table-striped table-bordered table-hover display compact nowrap">
                                <thead>
                                <tr class="text-dark">
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th>Currency</th>
                                    <th>Open Amount</th>
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
        <div class="col-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title">
                        <span class="">Event Image</span>
                    </div>
                    <div>
                        <a href="#" class="btn btn-primary font-weight-bold"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    No images yet
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
        $(document).ready(function () {
            {{--$('#datatable').DataTable({--}}
            {{--    processing: true,--}}
            {{--    serverSide: true,--}}
            {{--    "lengthMenu": [5, 10, 25, 50, 75, 100 ],--}}
            {{--    pageLength:5,--}}
            {{--    ajax: '{!! route('events.index') !!}',--}}
            {{--    columns: [--}}
            {{--        { data: 'id', name: 'Id' },--}}
            {{--        { data: 'name', name: 'Name' },--}}
            {{--        {data: 'amount',name: 'Amount'},--}}
            {{--        {data: 'currency',name: 'Currency'},--}}
            {{--        {data:'open_amount',name:'Open Amount'},--}}
            {{--        { data: 'status', name: 'Status'},--}}
            {{--        { data:'actions', name:'actions', orderable: false, searchable: false}--}}
            {{--    ]--}}
            {{--});--}}
        })
    </script>
@endsection
