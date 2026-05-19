@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#booktemplates" role="tab" aria-controls="booktemplates" aria-selected="true">Books Templates</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#bookitems" role="tab" aria-controls="bookitems" aria-selected="false">Books</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="booktemplates" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row pb-2 mb-2">
                                <div class="col d-flex justify-content-end">
                                    <button onclick="openAddModal(event)" class="btn btn-teal font-weight-bold text-light ">
                                        Add book template
                                        <i class="fa fa-plus ml-1"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <table id="booksDatatable" class="table table-bordered table-hover display compact nowrap">
                                        <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th>ISBN</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="bookitems" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row pb-2 mb-2">
                                <div class="col">
                                    <div class="d-flex justify-content-end">
                                        <button onclick="AddBookItem(event)" class="btn btn-teal font-weight-bold text-light">
                                            Add Book
                                            <i class="fa fa-plus ml-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="table-responsive">
                                        <table id="bookItemsDatatable" class="table table-bordered table-hover display compact nowrap">
                                            <thead>
                                            <tr>
                                                <th>id</th>
                                                <th>Uid</th>
                                                <th>title</th>
                                                <th>Condition</th>
                                                <th>Status</th>
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

                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="remove_form">
                    @csrf
                    <input type="hidden" name="book_id" id="remove_book_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-4">
                                Are you sure you want to remove this Book:<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_book"></div> ?
                                (Removing this book template will remove all physical copies of this book!)
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
                    <h5 class="modal-title" id="addModalLabel">New Book</h5>
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
                                    <label for="title" class="text-dark">Book title<span class="text-danger">*</span></label>
                                    <input type="text" name="title" placeholder="eg. Hamlet" id="title" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="author" class="text-dark">Book author<span class="text-danger">*</span></label>
                                    <input type="text" name="author" readonly placeholder="eg. J.K Rowling" id="author" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="isbn" class="text-dark">ISBN<span class="text-danger">*</span></label>
                                    <input type="text" name="isbn" readonly placeholder="5251-55255-5552" id="isbn" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="pub_date" class="text-dark">Publication Date<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend ">
                                            <div class="input-group-text bg-teal">
                                                <i class="fa fa-calendar text-light"></i>
                                            </div>
                                        </div>
                                        <input type="text" name="publication_date" readonly id="pub_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="categories">Categories</label>
                                    <Select class="form-control tags" id="categories" name="categories[]" multiple="multiple"></Select>
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
        const addModal = $('#addModal')
        const addForm = $('#add_form')

        const removeModal = $('#removeModal')
        const removeForm = $('#remove_form')

        const editModal = $('#editModal')
        const editForm = $('#editForm')

        $(document).ready(function(){

            const booksDataTable = $("#booksDatatable").DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                lengthMenu: [10, 25, 50, 75, 100 ],
                pageLength:10,
                ajax: {
                    url:'{!! route('books.index') !!}',
                    data: function(d){
                    },
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'title', name: 'title' },
                    { data: 'author',name: 'author'},
                    { data: 'ISBN',name: 'ISBN'},
                    { data:'actions', name:'actions', orderable: false, searchable: false}
                ]
            });

            const bookItemsDataTable = $("#bookItemsDatatable").DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                lengthMenu: [10, 25, 50, 75, 100 ],
                pageLength:10,
                ajax: {
                    url:'{!! route('books.bookItems') !!}',
                    method: 'post',
                    data: function(d){
                        d._token = '{!! csrf_token() !!}'
                    },
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'uid', name: 'uid' },
                    { data: 'title',name: 'title'},
                    { data: 'condition',name: 'condition'},
                    { data: 'status',name: 'status'},
                    { data:'actions', name:'actions', orderable: false, searchable: false}
                ]
            });


            removeForm.submit(function($event) {
                $event.preventDefault()
                let data = removeForm.serialize()
                console.log(data);
                $.ajax({
                    url: '{!! route('books.destroy') !!}',
                    method: 'delete',
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
                            booksDataTable.ajax.reload()
                            toastr.warning(message, 'Success')
                        }
                    }
                })
            })

            addForm.validate({
                rules:{
                    title:{
                        required:true,
                        maxlength:150,
                    },
                    author: {
                        required:true,
                        maxlength:80,
                        minlength:4,
                    },
                    isbn: {
                        required:true
                    },
                    publication_date: {
                        required:true,
                        date:true,
                    }
                },
                messages:{
                    title: "Enter a valid title",
                    author: "Enter a valid Author",
                    ISBN: "Enter the ISBN",
                    publication_date: "Enter the publication date",
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            addForm.submit(function($event){
                $event.preventDefault();
                let data = addForm.serialize()
                console.log(data)
                $.ajax({
                    url: ' {!! route('books.store') !!}',
                    method: 'post',
                    data: data,
                    complete: function (xhr,status) {
                        if(xhr.status === 201){
                            let message = xhr.responseJSON.message
                            $('#addModal').modal('toggle')
                            booksDataTable.ajax.reload()
                            toastr.success(message, 'Success')
                        }
                    }
                })

            });

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
                    url: ' {!! route('books.index') !!}',
                    method: 'post',
                    data: data,
                    complete: function (xhr,status) {
                        if(xhr.status === 201){
                            let message = xhr.responseJSON.message
                            $('#editModal').modal('toggle')
                            booksDataTable.ajax.reload()
                            toastr.info(message, 'Success')
                        }
                    }
                })

            });

            $(".modal").on("hidden.bs.modal", function() {

            });
        });

        function openAddModal($event){
            $event.preventDefault()
            addModal.modal('show')
            let title = $('#title')
            let author = $('#author')
            let ISBN = $('#isbn')
            let submitBtn = $('#addSubmitBtn')
            let pubDate = $('#pub_date')
            let categories = $('#categories')

            title.on('change',function($event){
                if($(this).val() !== null && $(this).val().length > 0){
                    author.prop('readonly',false)
                }
            });

            author.on('change',function($event){
                if($(this).val() !== null && $(this).val().length > 0){
                    ISBN.prop('readonly',false)
                }
            });

            ISBN.on('change',function($event){
                if($(this).val() !== null && $(this).val().length > 0){

                    pubDate.daterangepicker({
                        singleDatePicker:true,
                        autoUpdateInput: true,
                        showDropdowns: true,
                        minYear: 1901,
                    })
                }
            });

            pubDate.on('apply.daterangepicker', function(ev, picker) {
                submitBtn.attr('disabled',false)
            });

            categories.select2({
                theme: 'classic',
                tags:true,
                multiple:true,
                ajax: {
                    url: '{!! route('categories.getList') !!}',
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
                    placeholder: 'Search Category',
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
        }
        function openRemoveModal($event){
            $event.preventDefault()
            const bookId = $event.target.getAttribute('data-id')
            const bookName = $event.target.getAttribute('data-name')
            console.log(bookId)
            console.log(bookName)
            $("#remove_book_id").val(bookId)
            $("#confirm_book").html(`${bookName}`)
            removeModal.modal('show')
        }
        function openEditModal($event){

        }

        function AddBookItem($event){

        }

    </script>
@endsection
