@extends('layout.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title font-weight-bold">Confirm</h4>
                    </div>
                    <div class="card-body">
                        Are you sure you want to delete the tide:
                        <span class="ml-2 font-weight-bold text-teal">
                            {{ $data['tide']->member->fullName() . ' ' . $data['tide']->currency->code . $data['tide']['amount'] }}</span>  ?
                    </div>
                    <div class="card-footer">
                        <div class="row d-flex justify-content-end">
                            <div class="col-3">
                                <form method="POST" action="{{ route('tides.destroy',['tide' => $data['tide']->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-success btn-block">Ok</button>
                                </form>
                            </div>
                            <div class="col-3">
                                <a href="{{ route('members.index') }}" class="btn btn-danger btn-block"><i class="far fa-times-circle mr-2"></i>Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
