@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="col">
            <div class="container">
                <div class="card">
                    <div class="card-header">
                        <h5>Promoted Visitor {{$data['visitor']['name']}} to member</h5>
                    </div>
                    <form action="{{ route('members.storePromotedVisitor') }}" method="post">


                    <div class="card-body">

                    </div>
                    <div class="card-footer">

                    </div>
                    </form>
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
        });

    </script>
@endsection
