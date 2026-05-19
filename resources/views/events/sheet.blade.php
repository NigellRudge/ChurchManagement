@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between">
                    <div class="card-title text-lg ">
                        <strong class="mr-1">{{trans('common.sheet_label')}}:</strong><span class="text-dark font-weight-bold">{{ $data['sheet']['name'] }}</span>
                    </div>
                    <div class="d-flex flex-row">
                        <form action="{{ route('events.exportRegistrationSheet') }}" id="export_form" method="post">
                            @csrf
                            <input type="hidden" id="sheet_id" name="sheet_id" value="{{$data['sheet']['id']}}">
                            <button class="mr-2 pt-2 pb-2 btn btn-primary text-light font-weight-bold d-none" id="exportBtn" type="submit">
                                {{trans('common.export_to_excel_label')}}
                                <i class="ml-1 fas fa-file-excel"></i>
                            </button>
                        </form>
                        <button onclick="openAddModal(event)"  id="addBtn" class="btn btn-teal text-light font-weight-bold">
                            {{trans('common.add_member_label')}}
                            <i class="ml-1 fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="fix-topbar">
                        <table id="datatable" class="table table-bordered display compact nowrap">
                            <thead>
                            <tr class="text-dark">
                                <th style="width: 40px;">Id</th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-user text-teal"></i></span>
                                    {{trans('common.member_label')}}
                                </th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-dollar-sign text-teal"></i></span>
                                    {{trans('common.paid_amount_label')}}
                                </th>
                                <th>
                                    <span class="mr-1"><i class="fa fa-calendar-check text-teal"></i></span>
                                    {{trans('common.registered_date_label')}}
                                </th>
                                <th style="width: 80px;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white pb-4">
                    <div class="row">
                        @if($data['sheet']['registration_price'] != null)
                        <div class="col-3">
                            <span>{{trans('common.total_received_amount_label')}}:</span><input name="total_sheet_amount" type="text"  readonly placeholder="$0.00" id="total_sheet_amount" class="form-control d-inline" />
                        </div>
                        @endif
                        @if($data['sheet']['limit_registrations'] != 0)
                            <div class="col-3">
                                <span>{{trans('common.slots_left_label')}}</span><input name="slots_left" type="text"  readonly placeholder="0" id="slots_left" class="form-control d-inline" />
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light font-weight-bold">
                    <h5 class="modal-title" id="addModalLabel">{{trans('common.add_member_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="font-weight-bold text-light">&times;</span>
                    </button>
                </div>
                <div class="row">
                    <div class="col pb-2 pt-2 d-flex justify-content-center">
                        <img src="{{ asset('storage/placeholder-male.jpg') }}" id="member_image" alt="member_image" class="rounded" width="140" height="170" style="object-fit: cover">
                    </div>
                </div>
                <form method="post" action="#" id="add_form">
                    @csrf
                    <input type="hidden" id="date" name="date" value="{{$data['sheet']['date']}}">
                    <input type="hidden" id="currency_id" name="currency_id" value="{{$data['sheet']['currency_id']}}">
                    <input type="hidden" id="sheet_id" name="sheet_id" value="{{$data['sheet']['id']}}">
                    <div class="pl-4 pr-4 mt-2 mb-4">
                        <div class="form-row mt-2">
                            <div class="col">
                                <label for="member_id" class="font-weight-bold">{{trans('common.member_label')}}<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-white">
                                            <i class="fa fa-user text-teal"></i>
                                        </div>
                                    </div>
                                    <select class="form-control" id="member_id" name="member_id"></select>
                                </div>
                            </div>
                        </div>
                        @if($data['sheet']['registration_price'] != null)
                            <div class="form-row mt-2">
                                <div class="col">
                                    <label for="paid_amount" class="font-weight-bold">{{trans('common.paid_amount_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <span class="">{{ $data['sheet_currency']->code }}</span>
                                            </div>
                                        </div>
                                        <input type="number" min="{{$data['sheet']['min_amount']}}" max="100000" value="{{$data['sheet']['min_amount']}}" step="5.00" id="paid_amount" name="paid_amount" class="form-control">
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="form-row mt-2">
                            <div class="col">
                                <label for="registration_date" class="font-weight-bold">{{trans('common.registered_date_label')}}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-white">
                                            <i class="fa fa-calendar text-teal"></i>
                                        </div>
                                    </div>
                                <input type="text" id="registration_date" name="registration_date" class="form-control" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit"  id="add_submitBtn" class="btn btn-teal font-weight-bold text-light" disabled>
                            <span class="mr-1"><i class="fa fa-save"></i></span>
                            {{trans('common.save_label')}}
                        </button>
                        <button type="button" class="btn btn-danger font-weight-bold text-light">
                            {{trans('common.cancel_label')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="exampleModalLabel">{{trans('common.confirm_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="font-weight-bold text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="remove_form">
                    @csrf
                    <input type="hidden" id="sheet_id" name="sheet_id" value="{{ $data['sheet']['id'] }}">
                    <input type="hidden" name="remove_member_id" id="remove_member_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-4">
                                {{trans('common.remove_member_from_sheet_label')}}:<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_member"></div> ?
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">
                            <span class="mr-1"><i class="fa fa-trash"></i></span>
                            {{trans('common.yes_label')}}
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('common.no_label')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('custom_css')
    @include('shared.totalCSS')

@endsection

@section('custom_js')
    @include('shared.totalJS')
    <script>
        const maxRegistrations = {!! $data['sheet']['max_registrations'] == null ? 3000 : $data['sheet']['max_registrations']  !!};
        let currencyCode = null;
        @foreach($data['currencies'] as $currency)
            @if($currency->id == $data['sheet']['currency_id'])
            currencyCode = '{!! $currency->code !!}'
        @endif
        @endforeach
        $(document).ready(function(){
            getTotalSheetAmount();
            let dataTable = $("#datatable").DataTable({
                processing: true,
                language: datatableTrans,
                autoWidth:false,
                serverSide: true,
                "lengthMenu": [5, 10, 25, 50, 75, 100 ],
                pageLength:5,
                ajax: '{!! route('events.registrationSheetMembers',['registration_sheet'=>$data['sheet']]) !!}',
                columns: [
                    {data: 'id', name: 'id',searchable: false},
                    {data: 'member', name: 'member'},
                    {data: 'amount', name: 'paid_amount'},
                    {data: 'registration_date', name: 'registration_date'},
                    { data:'actions', name:'actions', orderable: false, searchable: false}
                ]
            });
            let addForm = $('#add_form');
            addForm.submit(function($event){
                $event.preventDefault();
                let data = addForm.serialize();
                console.log(data)
                $.ajax({
                    url: '{!! route('events.storeSheetItem') !!}',
                    method: 'post',
                    data:data,
                    complete: function(xhr){
                        console.log(xhr.responseJSON)
                        if(xhr.status === 201){
                            let message = xhr.responseJSON.message
                            dataTable.ajax.reload()
                            getTotalSheetAmount();
                            toastr.success(message,'{{trans('common.success_label')}}')
                            $('#addModal').modal('hide')
                        }
                    }
                })
            })
            $(".modal").on("hidden.bs.modal", function() {
                console.log('hidden')

                let memberInput = $('#member_id')
                memberInput.val(null).trigger('change')

                $('#add_submitBtn').attr('disabled',true);
                $('#member_image').attr('src','{!! asset('storage/placeholder-male.jpg') !!}')
            });

            let removeForm = $('#remove_form');
            removeForm.submit(function($event){
                $event.preventDefault();
                let data = removeForm.serialize();
                console.log(data)

                $.ajax({
                    url: '{!! route('events.removeItemFromSheet') !!}',
                    method: 'post',
                    data:data,
                    complete: function(xhr){
                        console.log(xhr.responseJSON)
                        if(xhr.status === 201){
                            let message = xhr.responseJSON.message
                            dataTable.ajax.reload()
                            getTotalSheetAmount();
                            toastr.warning(message,'Success')
                            $('#removeModal').modal('hide')
                        }
                    }
                })
            })
        });

        function openAddModal($event){
            $event.preventDefault()
            let modal = $('#addModal')
            let registrationDate = $('#registration_date')
            let memberInput = $('#member_id')
            modal.modal('show')
            memberInput.select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('events.membersNotOnSheet') !!}',
                    type: 'post',
                    data: function(params){
                        return {
                            _token: '{!! csrf_token() !!}',
                            term:params.term,
                            sheet_id: {!! $data['sheet']['id'] !!}
                        }
                    },
                    dataType: 'json',
                    cache:true,
                    delay:200,
                    placeholder: 'Search Member',
                    processResults: function(data){
                        //console.log(data)
                        return {
                            results: data.results,
                            pagination: {
                                more: false
                            }
                        }
                    }
                }
            });
            memberInput.on('change',function(){
                let value = $(this).val()
                if(value !== null && value !== 0){
                    $.ajax({
                        url: '{!! route('members.getByIdJson') !!}',
                        method: 'post',
                        data: {
                            _token: '{!!  csrf_token() !!}',
                            id: memberInput.val()
                        },
                        complete: function({status, responseJSON}){
                            if(status === 200){
                                let {member} = responseJSON
                                console.log(responseJSON)
                                $('#member_image').attr('src',member.member_image)
                            }
                        }

                    })
                }
                registrationDate.attr('disabled',false)
            })
            registrationDate.daterangepicker({
                showWeekNumbers:true,
                singleDatePicker:true,
                timePickerIncrement:15,
                opens:'up',
                drops:'auto',
                timePicker:false,
                locale: {
                    format:'MM/DD/YYYY'
                },
                autoUpdateInput: false,
                showDropdowns: true,
            })
            registrationDate.on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY'));
                $('#add_submitBtn').attr('disabled',false);
            });
        }

        function openRemoveModal($event){
            $event.preventDefault()
            let removeModal = $('#removeModal')
            removeModal.modal('show')
            let memberName = $event.target.getAttribute('data-name')
            let member_id = $event.target.getAttribute('data-id')
            $('#confirm_member').html( `${memberName}`);
            $('input[name="remove_member_id"]').val(member_id.toString());
        }

        function getTotalSheetAmount(){
            const sheetId = {!! $data['sheet']['id'] !!};
            const totalSheetAmount = $('#total_sheet_amount')
            $.ajax({
                url: '{!! route('events.getTotalAmountOnSheet') !!}',
                method: 'post',
                data: {
                    _token: '{!! csrf_token() !!}',
                    sheet_id: sheetId
                },
                complete: function(xhr){
                    let amount = xhr.responseJSON.amount
                    let numMembers = xhr.responseJSON.num_members
                    let btn = $('#exportBtn')
                    let addBtn = $('#addBtn')
                    let slotsLeft = $('#slots_left')
                    if(xhr.status === 201){
                        amount  === 0 ? totalSheetAmount.val(`${currencyCode} $0.00`): totalSheetAmount.val(`${currencyCode}  $${xhr.responseJSON.amount}`);
                        numMembers > 0 ? btn.removeClass('d-none') : btn.addClass('d-none')
                        numMembers < maxRegistrations ? addBtn.removeClass('d-none') : addBtn.addClass('d-none')
                        numMembers < maxRegistrations ? slotsLeft.val(`${maxRegistrations - numMembers}`): slotsLeft.val(`0`)

                    }
                }
            })
        }

    </script>
@endsection
