<nav class="navbar navbar-expand navbar-dark bg-white topbar mb-4 static-top shadow">

    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow">
        <div class="nav-link btn-group d-flex flex-column justify-content-center">
            <a class="text-decoration-none pointer dropdown-toggle d-flex flex-row align-items-center" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer">
                <div class="d-flex flex-row" >
                    <span class="mr-2">
                        <img src="{{Session::get('lang_icon')}}" width="28" height="20" alt="flag">
                    </span>
                    <span class="mr-2 d-none d-lg-inline font-weight-bold text-dark text-xl" >{{Session::get('lang_name')}}</span>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                @foreach(\Illuminate\Support\Facades\Session::get('other_langs') as $lang)
                    <a class="dropdown-item" href="#"  onclick="ChangeLang({!! $lang->id !!})">
                        <img src="{{asset('storage/' . $lang->icon_name)}}" width="25" height="20" alt="flag" class="mr-1" />
                        {{trans('common.' . $lang->trans_code)}}
                    </a>
                @endforeach
            </div>
        </div>

        </li>
        <div class="topbar-divider d-none d-sm-block"></div>
        @auth
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-3 d-flex justify-content-center align-items-center">
                    <i class="fa fa-user-tie text-teal" style="font-size: 1.5rem"></i>
                </span>
                    <span class="mr-2 d-none d-lg-inline text-lg font-weight-bold text-dark text-capitalize">{{ Auth::user()->name }}</span>
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400" onclick="OpenLogOutModal(event)"></i>
                        {{trans('common.auth_logout_label')}}
                    </a>
                </div>
            </li>
        @endauth
    </ul>
</nav>
<form method="post" action="{{ route('change_language') }}" id="new_changeLangForm">
    @csrf
    <input type="hidden" name="language_id" id="new_language_id">
</form>
<script>
    function ChangeLang(langId) {
        $('#new_language_id').val(langId)
        const form = $('#new_changeLangForm')
        form.submit()
    }
</script>
