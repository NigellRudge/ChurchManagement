<div class="modal fade" id="langModal" tabindex="-1" role="dialog" aria-labelledby="langModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-teal text-light">
                <h5 class="modal-title" id="langModalLabel">{{trans('common.change_language_label')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-light">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('change_language') }}" id="change_lang_form">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="language_id"  class="font-weight-bold text-dark">{{trans('common.language_label')}}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-white">
                                    <i class="fa fa-flag text-teal"></i>
                                </div>
                            </div>
                            <select name="language_id" id="language_id" class="form-control">
                                @foreach(\Illuminate\Support\Facades\Session::get('other_langs') as $lang)
                                    <option value="{{$lang->id}}">
                                        {{$lang->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-teal">{{trans('common.save_label')}}</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{trans('common.cancel_label')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // const ChangeLang  = ($event)=>{
    //     $event.preventDefault()
    //     $('#langModal').modal('show')
    // }
</script>
