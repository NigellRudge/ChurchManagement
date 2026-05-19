@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between">
                    <div class="card-title font-weight-bold  pt-1">
                        <span class="font-weight-bold text-lg text-dark">Tides</span>
                    </div>
                    <a class="btn btn-teal font-weight-bold pt-2" href="#" onclick="openAddModal(event)">
                        Add Tide
                        <i class="ml-1 fas fa-plus"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="row pl-3 mb-4">
                        <div class="col-3">
                            <div class="form-group row">
                                <label for="filter_currency_id" class="col-form-label font-weight-bold">Filter By Currency</label>
                                <div class="col">
                                    <select type="text" id="filter_currency_id" name="filter_currency_id" class="form-control">
                                        <option value="0">All</option>
                                        @foreach($data['currencies'] as $currency)
                                            <option value="{{$currency['id']}}">{{$currency['code']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group row">
                                <label for="from_date" class="col-form-label">From</label>
                                <div class="col">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal">
                                                <i class="fa fa-calendar text-light"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" id="from_date" name="from_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group row">
                                <label for="to_date" class="col-form-label">To</label>
                                <div class="col">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal">
                                                <i class="fa fa-calendar text-light"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" id="to_date" name="to_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <button class="btn btn-teal text-light font-weight-bold" id="filterBtn">
                                Filter
                                <i class="fas fa-filter ml-1"></i>
                            </button>
                            <button class="btn btn-danger text-light font-weight-bold" id="clearBtn">
                                Clear
                                <i class="fas fa-ban ml-1"></i>
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered table-hover display compact nowrap">
                            <thead>
                            <tr>
                                <th>Member</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="remove_form">
                    @csrf
                    <input type="hidden" name="remove_tide_id" id="remove_tide_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-4">
                                Are you sure you want to remove this Tide:<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_tide"></div> ?
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal">Yes</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="addModalLabel">New Tide</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="add_form">
                    @csrf
                    <div class=" mt-2 pl-3 pr-3">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="member_id" class="text-dark">Member<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <i class="far fa-user"></i>
                                            </div>
                                        </div>
                                        <select name="member_id" data-placeholder="Select member"  id="member_id" type="text" class="form-control">
                                        </select>
                                    </div>
                                    <small id="nameError" class="form-text text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="date" class="text-dark">Date<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <i class="far fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input id="date" name="date" class="form-control" type="text"  disabled/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-5">
                                <div class="form-group">
                                    <label for="currency_id" class="text-dark">Currency<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <i class="fas fa-dollar-sign"></i>
                                            </div>
                                        </div>
                                        <select name="currency_id" id="currency_id" data-placeholder="Select currency" class="form-control">
                                            <option value="0">Select Currency</option>
                                            @foreach($data['currencies'] as $currency)
                                                <option value="{{$currency->id}}">{{$currency->code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="amount"  class="text-dark">Amount<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <i class="fas fa-coins"></i>
                                            </div>
                                        </div>
                                        <input disabled name="amount" step="0.01" min="0.01" max="100000000" id="amount" placeholder="$0.00" type="number" class="form-control" />
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal" disabled id="addSubmitBtn">
                            <i class="fas fa-save"></i>
                            Save
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-ban"></i>
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="editModalLabel">Edit Tide</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="edit_form">
                    @csrf
                    <input type="hidden" name="edit_tide_id" id="edit_tide_id">
                    <div class=" mt-2 pl-3 pr-3">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_member_id" class="text-dark">Member<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <i class="far fa-user"></i>
                                            </div>
                                        </div>
                                        <select name="edit_member_id" data-placeholder="Select member"  id="edit_member_id" type="text" class="form-control">
                                        </select>
                                    </div>
                                    <small id="nameError" class="form-text text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_date"  class="text-dark">Date<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <i class="far fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input id="edit_date" name="edit_date" class="form-control" type="text" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="edit_currency_id"  class="text-dark">Currency<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <i class="fas fa-dollar-sign"></i>
                                            </div>
                                        </div>
                                        <select name="edit_currency_id" id="edit_currency_id" class="form-control">
                                            @foreach($data['currencies'] as $currency)
                                                <option value="{{$currency->id}}">{{$currency->code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_amount"  class="text-dark">Amount<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-teal text-light">
                                                <i class="fas fa-coins"></i>
                                            </div>
                                        </div>
                                        <input name="edit_amount" step="0.01" min="0.01" max="100000000" id="edit_amount" placeholder="$0.00" type="number" class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal">
                            <i class="fas fa-save"></i>
                            Save
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-ban"></i>
                            Cancel
                        </button>
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
        let currencyId = 0;
        let fromDate = null;
        let toDate = null;
        const filterCurrency = $('#filter_currency_id')
        const filterBtn = $('#filterBtn')
        const clearBtn = $('#clearBtn')
        $(document).ready(function(){

            filterCurrency.on('change',function(){
                currencyId = this.value;
            });
            filterBtn.on('click',function($event){
                console.log('clicked')
                dataTable.ajax.reload()
            })
            const fromDateEl = $('#from_date').daterangepicker({
                singleDatePicker:true,
                autoUpdateInput: false,
                startDate: new Date(),
                showDropdowns: true,
                minYear: 1901,
            })
            fromDateEl.on('apply.daterangepicker',function(ev,picker){
                let val = picker.startDate.format('YYYY-MM-DD')
                $(this).val(val)
                console.log(val)
                fromDate = val;
            })
            const toDateEl = $('#to_date').daterangepicker({
                singleDatePicker:true,
                autoUpdateInput: false,
                startDate: new Date(),
                showDropdowns: true,
                minYear: 1901,
            })
            toDateEl.on('apply.daterangepicker',function(ev,picker){
                let val = picker.startDate.format('YYYY-MM-DD')
                $(this).val(val)
                console.log(val)
                toDate = val;
            })
            clearBtn.on('click',function($event){
                fromDateEl.val('')
                toDateEl.val('')
                fromDate = null
                toDate = null
                currencyId = 0
                filterCurrency.val(0)
                dataTable.ajax.reload()
            })

            const dataTable = $("#datatable").DataTable({
                processing: true,
                language: datatableTrans,
                autoWidth:false,
                serverSide: true,
                "lengthMenu": [10, 25, 50, 75, 100 ],
                pageLength:10,
                ajax: {
                    url:'{!! route('tides.index') !!}',
                    data: function(d){
                        d.currency_id = currencyId
                        d.to_date = toDate
                        d.from_date = fromDate
                    },
                },
                columns: [
                    { data: 'member', name: 'member' },
                    { data: 'newAmount', name: 'amount' },
                    {data: 'date',name: 'date'},
                    { data:'actions', name:'actions', orderable: false, searchable: false}
                ]
            });

            const deleteForm = $('#remove_form')
            deleteForm.submit(function($event) {
                $event.preventDefault()
                let data = deleteForm.serialize()
                console.log(data);
                $.ajax({
                    url: '{!! route('tides.destroyAjax') !!}',
                    method: 'post',
                    data: data,
                    success: function (data) {
                        console.log(data)
                    },
                    error: function (error) {
                        console.log(error)
                    },
                    complete: function (xhr, data) {
                        if (xhr.status === 201) {
                            let message = xhr.responseJSON.message
                            $('#removeModal').modal('hide')
                            dataTable.ajax.reload()
                            toastr.warning(message, 'Success')
                        }
                    }
                })
            })

            const addForm = $('#add_form')
            addForm.validate({
                rules:{
                    member_id:{
                        required:true,
                        min:1,
                    },
                    date: {
                        required:true,
                        date: true
                    },
                    currency_id: {
                        required:true
                    },
                    amount: {
                        required:true,
                        min: 0
                    }
                },
                messages:{
                    member_id: "please select a member",
                    date: "please enter a valid date",
                    currency_id: "please select a currency",
                    amount: "amount should be more than 0"
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            addForm.submit(function($event){
                $event.preventDefault();
                let data = addForm.serialize()
                $.ajax({
                    url: ' {!! route('tides.storeAjax') !!}',
                    method: 'post',
                    data: data,
                    complete: function (xhr,status) {
                        if(xhr.status === 201){
                            let message = xhr.responseJSON.message
                            $('#addModal').modal('toggle')
                            dataTable.ajax.reload()
                            toastr.success(message, 'Success')
                        }
                    }
                })

            });

            const editForm = $('#edit_form')
            editForm.validate({
                rules:{
                    edit_member_id:{
                        required:true,
                        min:1,
                    },
                    edit_date: {
                        required:true,
                        date: true
                    },
                    edit_currency_id: {
                        required:true
                    },
                    edit_amount: {
                        required:true,
                        min: 0
                    }
                },
                messages:{
                    member_id: "please select a member",
                    date: "please enter a valid date",
                    currency_id: "please select a currency",
                    amount: "amount should be more than 0"
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            editForm.submit(function($event){
                $event.preventDefault();
                let data = editForm.serializeArray()
                console.log(data)
                $.ajax({
                    url: ' {!! route('tides.updateAjax') !!}',
                    method: 'post',
                    data: data,
                    complete: function (xhr,status) {
                        if(xhr.status === 201){
                            let message = xhr.responseJSON.message
                            $('#editModal').modal('toggle')
                            dataTable.ajax.reload()
                            toastr.info(message, 'Success')
                        }
                    }
                })

            });

            $(".modal").on("hidden.bs.modal", function() {
                let memberInput = $("select[name='member_id']")
                memberInput.val('');
                memberInput.removeClass('is-valid');
                memberInput.removeClass('is-invalid');

                let dateInput = $("input[name='date']")
                dateInput.val('');
                dateInput.attr('disabled',true)
                dateInput.removeClass('is-valid');
                dateInput.removeClass('is-invalid');

                let currencyInput = $("select[name='currency_id']")
                currencyInput.val(0);
                currencyInput.attr('disabled',true)
                currencyInput.removeClass('is-valid');
                currencyInput.removeClass('is-invalid');

                let amountInput = $("input[name='amount']")
                amountInput.val('');
                amountInput.attr('disabled',true)
                amountInput.removeClass('is-valid');
                currencyInput.removeClass('is-invalid');

                let editMemberInput = $("select[name='edit_member_id']")
                editMemberInput.val('');
                editMemberInput.removeClass('is-valid');
                editMemberInput.removeClass('is-invalid');

                let editDateInput = $("input[name='edit_date']")
                editDateInput.val('');
                editDateInput.removeClass('is-valid');
                editDateInput.removeClass('is-invalid');

                let editCurrencyInput = $("select[name='edit_currency_id']")
                editCurrencyInput.val('');
                editCurrencyInput.removeClass('is-valid');
                editCurrencyInput.removeClass('is-invalid');

                let editAmountInput = $("input[name='edit_amount']")
                amountInput.val('');
                amountInput.removeClass('is-valid');
            });
        });

        function openAddModal($event){
            $event.preventDefault()
            let addModal = $('#addModal')
            addModal.modal('show')
            const memberId = $('#member_id')
            const date = $('#date')
            const currency = $('#currency_id')
            const amount = $('#amount')
            const submitBtn = $('#addSubmitBtn')
            memberId.select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('members.json') !!}',
                    type: 'post',
                    data: function(params){
                        return {
                            _token: '{!! csrf_token() !!}',
                            name: params.term,
                            page: params.page || 1
                        };
                    },
                    dataType: 'json',
                    cache:true,
                    delay:200,
                    placeholder: 'Search Member',
                    processResults: function(data,params){
                        params.page = params.page || 1;
                        console.log(params)
                        const {total_items} = data;
                        return {
                            results: data.results,
                            pagination: {
                                more: (params.page * 10) < total_items
                            }
                        }
                    }
                }
            });
            memberId.on('change',function (event) {
                event.preventDefault()
                date.attr('disabled',false)
                date.daterangepicker({
                    singleDatePicker:true,
                    autoUpdateInput: true,
                    showDropdowns: true,
                    minYear: 1901,
                })
            })
            date.on('apply.daterangepicker',function (ev,picker) {
                currency.attr('disabled',false)
            })
            currency.on('change',function(){
                let value = parseInt($(this).val())
                console.log(value)
                if(value !== 0){
                    amount.attr('disabled',false)
                }
                else {
                    amount.attr('disabled',true)
                }
            })
            amount.on('change',function(event){
                let value = parseInt($(this).val())
                if(value > 0){
                    submitBtn.prop('disabled',false)
                }
            })
        }
        function openRemoveModal($event){
            $event.preventDefault()
            let removeModal = $('#removeModal')
            removeModal.modal('show')
            let tideMember = $event.target.getAttribute('data-member')
            let tideAmount = $event.target.getAttribute('data-amount')
            let tideId = $event.target.getAttribute('data-id')
            $('#confirm_tide').html( `${tideMember} - ${tideAmount} `);
            $('input[name="remove_tide_id"]').val(tideId.toString());
        }
        function openEditModal($event){
            let editAmount = $("input[id='edit_amount']")
            $event.preventDefault()
            let editModal = $('#editModal')
            editModal.modal('show')
            let tideId = parseInt($event.target.getAttribute('data-id'))
            console.log(`tide Id: ${tideId}`)
            $("input[name='edit_tide_id']").val(tideId)
            let data = {
                "_token": '{!! csrf_token() !!}',
                "tideId": tideId
            }
            //console.log(data)
            $.ajax({
                url: '{!! route('tides.getByIdAjax') !!}',
                method:'post',
                data:data,
                complete: function(xhr){
                    let data = xhr.responseJSON.tide
                    //console.log(data)
                    if(xhr.status === 201){
                        editAmount.val(data.amount)
                        setupEditMember(data.member_id)
                        $('#edit_currency_id').val(`${data.currency_id}`)
                        //setupEditCurrency(data.currency_id)
                        $('#edit_date').daterangepicker({
                            singleDatePicker:true,
                            autoUpdateInput: true,
                            startDate: data.date,
                            showDropdowns: true,
                            minYear: 1901,
                        })
                    }
                    editModal.modal('show')
                }
            })
        }

        function setupEditMember(memberId){
            let memberSelect = $('#edit_member_id').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('members.json') !!}',
                    type: 'post',
                    data: function(params){
                        return {
                            _token: '{!! csrf_token() !!}',
                            name:params.term
                        }
                    },
                    dataType: 'json',
                    cache:true,
                    delay:50,
                    placeholder: 'Member',
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
            $.ajax({
                type: 'POST',
                url: '{!! route('members.getByIdJson') !!}',
                data: {
                    _token: '{!! csrf_token() !!}',
                    id:memberId
                }
            }).then(function(data){
                //console.log(data)
                let option = new Option(data.name,data.id,true, true)
                memberSelect.append(option).trigger('change')
                memberSelect.trigger({
                    type: 'select2:select',
                    params: {
                        data: data
                    }
                });
            });
        }

        function setupEditCurrency(currencyId){
            let currencySelect = $('#edit_currency_id').select2({
                theme: 'bootstrap4',
                ajax: {
                    url: '{!! route('currency.getJson') !!}',
                    type: 'post',
                    data: function(params){
                        return {
                            _token: '{!! csrf_token() !!}',
                            name:params.term
                        }
                    },
                    dataType: 'json',
                    cache:true,
                    delay:50,
                    placeholder: 'Currency',
                    processResults: function(data){
                        console.log(data)
                        return {
                            results: data.results,
                            pagination: {
                                more: false
                            }
                        }
                    }
                }
            });
            $.ajax({
                type: 'POST',
                url: '{!! route('currency.getByIdJson') !!}',
                data: {
                    _token: '{!! csrf_token() !!}',
                    currencyId:currencyId
                }
            }).then(function(data){
                let currency = data.currency[0]
                let option = new Option(currency.code,currency.id,true, true)
                currencySelect.append(option).trigger('change')
                currencySelect.trigger({
                    type: 'select2:select',
                    params: {
                        data: data
                    }
                });
            });
        }
    </script>
@endsection
