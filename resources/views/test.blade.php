@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="col">
            <form method="post" id="uploadForm" action="{{route('testUploadPost')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="member_id" name="member_id" value="118" >
                <div class=" mt-2 pl-3 pr-3">
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label for="upload_file" class="text-dark">{{trans('common.file')}} <span class="text-danger">*</span></label>
                                <input type="file" id="upload_file" name="file" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label for="upload_file_name" class="text-dark">{{trans('common.file_name')}} <span class="text-danger">*</span></label>
                                <input type="text" id="upload_file_name" name="name" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <button type="submit" class="btn btn-teal">
                        <i class="fas fa-save"></i>
                        {{trans('common.save_label')}}
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fas fa-ban"></i>
                        {{trans('common.no_label')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_css')
    @include('shared.totalCSS')
@endsection

@section('custom_css')
    @include('shared.totalJS')
@endsection
