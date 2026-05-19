@extends('layout.admin')

@section('content')
    <div class="container justify-content-center">
        <div class="row">
            <div class="col-8">
                <div class="card rounded">
                    <div class="card-header">
                        <div class="card-title">
                            <span class="pt-2 text-lg card-title font-weight-bold card-title">Edit currency</span>
                        </div>
                    </div>
                    <div class="card-body pl-5 pr-5 pt-4 pb-3">
                        <form method="POST" action="{{ route('currency.update',['currency' => $data['currency']['id']])}}">
                            @csrf
                            @method('PATCH')
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="name" class="text-dark">Name</label>
                                        <input name="name" placeholder="Currency Name" id="name" type="text" value="{{ $data['currency']['name'] }}" class="form-control" />
                                        @error('name')
                                        <small id="nameError" class="form-text text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group mb-4">
                                        <label for="code"  class="text-dark">Code</label>
                                        <input name="code" id="code" placeholder="Currency Code" type="text" value="{{ $data['currency']['code'] }}" class="form-control" />
                                        @error('code')
                                        <small id="codeError" class="form-text text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group row mb-5 ml-1">
                                        <label for="exchange_rate" class="col-3 col-form-label">Exchange Rate</label>
                                        <input id="exchange_rate" class="ml-0 pl-0 col-2 form-control text-center"
                                               type="number" step="0.01" min="0.01" max="10000000" name="exchange_rate" value="{{ $data['currency']['exchange_rate'] }}" />
                                    </div>
                                </div>
                            </div>

                            <div class="container">
                                <div class="row d-flex justify-content-center">
                                    <div class="col-4">
                                        <button type="submit" class="btn btn-teal btn-block"><i class="fas fa-save mr-2"></i>Save</button>
                                    </div>
                                    <div class="col-4">
                                        <button type="reset" class="btn  btn-outline-danger btn-block"><i class="far fa-times-circle mr-2"></i>cancel</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')

@endsection

@section('custom_css')

@endsection
