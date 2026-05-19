@extends('layout.admin')

@section('content')
        <div class="row">
            <div class="container justify-content-center col-5">
                <div class="card ">
                    <div class="pb-2 pt-2 text-center border border-bottom">
                        <div class="mt-2 mb-2 ">
                            <span class="pt-2 card-title font-weight-bold text-lg text-teal">Edit Type</span>
                        </div>
                    </div>
                    <div class="card-body pl-5 pr-5 pt-4 pb-3">
                        <form method="POST" action="{{ route('type.update',['type' => $data['type']])}}">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <label for="name" class="text-dark">Name</label>
                                <input name="name" value="{{$data['type']->name}}"  id="name" type="text" class="form-control" />
                                @error('name')
                                <small id="nameError" class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label for="code"  class="text-dark">Code</label>
                                <input name="code" value="{{$data['type']->code}}" id="code"  type="text" class="form-control" />
                                @error('code')
                                <small id="codeError" class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <div class="form-check">
                                    <input type="checkbox" @if($data['type']['active'] == 1) checked @endif  class="form-check-input" name="active" id="active">
                                    <label for="active"  class="text-dark">Active</label>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row d-flex justify-content-center">
                                    <div class="col">
                                        <button type="submit" class="btn btn-teal btn-block"><i class="fas fa-save mr-2"></i>Save</button>
                                    </div>
                                    <div class="col">
                                        <button type="reset" class="btn  btn-outline-danger btn-block"><i class="far fa-times-circle mr-2"></i>cancel</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection


