@extends('layout.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-5">
                <div class="card">
                    <div class="text-center pt-2 pb-2 border-bottom">
                        <h4 class="card-title text-dark font-weight-bold">Confirm</h4>
                    </div>
                    <div class="card-body">
                        Are you sure you want to delete <span class="ml-2 font-weight-bold text-teal">{{ $data['type']->name }}</span>  ?
                    </div>
                    <div class="mt-4 pb-3 pt-2">
                        <div class="row d-flex justify-content-center">
                            <div class="col-4">
                                <form method="POST" action="{{ route('type.destroy',['type' => $data['type']->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-success btn-block">Ok</button>
                                </form>
                            </div>
                            <div class="col-4">
                                <a href="{{ route('type.index') }}" class="btn btn-outline-danger btn-block"><i class="far fa-times-circle mr-2"></i>cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
