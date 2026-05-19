<div class="row">
    <div class="container col-6">
        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger" role="alert">
                    {{$error}}
                </div>
            @endforeach
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Success!</h5>
                {{session('success')}}
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Info!</h5>
                {{session('info')}}
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Success!</h5>
                {{session('warning')}}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{session('error')}}
            </div>
        @endif
    </div>
</div>
