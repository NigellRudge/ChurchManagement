@extends('layout.admin')

@section('page_title')@endsection

@section('content')
    <div class="container justify-content-center">
        <div class="row">
            <div class="col-6">
                <div class="card ">
                    <div class="pb-1 pt-1 text-center border border-bottom">
                        <div class="">
                            <h4 class="pt-1 card-title font-weight-bold text-dark">New Event Type</h4>
                        </div>
                    </div>
                    <div class="card-body pl-5 pr-5 pt-4 pb-3">
                        <form method="POST" action="{{ route('event-type.store')}}">
                            @csrf
                            <div class="form-group">
                                <label for="name" class="text-dark">Name</label>
                                <input name="name" placeholder="name" id="name" type="text" class="form-control" />
                                @error('name')
                                <small id="nameError" class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label for="code"  class="text-dark">Code</label>
                                <input name="code" id="code" placeholder="code" type="text" class="form-control" />
                                @error('code')
                                <small id="codeError" class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" rows="3" placeholder="optional description"></textarea>
                            </div>

                            <div class="form-row mb-2 pl-1">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="repeated" id="repeated" />
                                    <label for="repeated" class="form-check-label font-weight-bold">Repeated</label>
                                </div>
                            </div>
                            <div class="form-group row mb-5 ml-1">
                                <label for="interval" class=" col-form-label">Interval</label>
                                <input id="interval" class="ml-3 pl-0 col-2 form-control text-center" type="number" step="1" min="0" max="7" name="Interval" value="0" disabled />
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
    </div>
@endsection

@section('custom_css')@endsection

@section('custom_js')
    <script>
        $(document).ready(function(){
            $('#repeated').change(function(){
                let checkbox = this;
                if(checkbox.checked){
                    $('#interval').prop('disabled',false);
                }
                else {
                    $('#interval').prop('disabled',true);
                }
            })
        });
    </script>
@endsection
