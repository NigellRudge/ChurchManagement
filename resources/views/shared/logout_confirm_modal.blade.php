<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-teal text-light">
                <h5 class="modal-title" id="logoutModalLabel">{{trans('common.confirm_label')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-light">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('logout') }}" id="logout_from">
                @csrf
                <div class="modal-body">
                    <div class="d-flex flex-row align-baseline">
                        <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                            <i class="far fa-question-circle"></i>
                        </div>
                        <div class="pt-2 text-dark pl-2">
                            {{ trans('common.auth_logout_confirm_label') }}
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary">
                        <i class="fas fa-sign-out-alt"></i>
                        {{ trans('common.yes_label') }}
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ trans('common.no_label') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    const OpenLogOutModal  = ($event)=>{
        $event.preventDefault()
        $('#logoutModal').modal('show')
    }
</script>
