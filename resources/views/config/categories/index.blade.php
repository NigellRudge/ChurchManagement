@extends('layout.admin')

@section('content')
    <div class="row">
        <div class="container justify-content-center col">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between">
                    <div class="card-title font-weight-bold  pt-1">
                        <span class="font-weight-bold text-lg text-dark">Book Categories</span>
                    </div>
                    <a class="btn btn-teal font-weight-bold pt-2" href="#" onclick="openAddModal(event)">
                        Add Category
                        <i class="ml-1 fas fa-plus"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered table-hover display compact nowrap">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
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
                    <button type="button" class="close " data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="remove_form">
                    @csrf
                    <input type="hidden" name="category_id" id="remove_category_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-4">
                                Are you sure you want to remove this Book Category:<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_category"></div> ?
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
                    <h5 class="modal-title" id="addModalLabel">New Book Category</h5>
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
                                    <label for="name" class="text-dark">Category Name<span class="text-danger">*</span></label>
                                    <input type="text" name="name" placeholder="eg. Science fiction" id="name" class="form-control">
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
                    <input type="hidden" id="edit_category_id" name="category_id" />
                    <div class=" mt-2 pl-3 pr-3">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_name" class="text-dark">Category Name<span class="text-danger">*</span></label>
                                    <input type="text" name="name" placeholder="eg. Science fiction" id="edit_name" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal" id="editSubmitBtn">
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
        const editForm = $('#edit_form')


        $(document).ready(function(){

            const dataTable = $("#datatable").DataTable({
                processing: true,
                serverSide: true,
                "lengthMenu": [10, 25, 50, 75, 100 ],
                pageLength:10,
                ajax: {
                    url:'{!! route('categories.index') !!}',
                    data: function(d){
                    },
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data:'actions', name:'actions', orderable: false, searchable: false}
                ]
            });


            removeForm.submit(function($event) {
                $event.preventDefault()
                let data = removeForm.serialize()
                console.log(data);
                $.ajax({
                    url: '{!! route('categories.destroy') !!}',
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
                            dataTable.ajax.reload()
                            toastr.warning(message, 'Success')
                        }
                    }
                })
            })

            addForm.validate({
                rules:{
                    name:{
                        required:true,
                        maxlength:150,
                    }
                },
                messages:{
                    title: "Enter a valid name",
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            addForm.submit(function($event){
                $event.preventDefault();
                let data = addForm.serialize()
                console.log(data)
                $.ajax({
                    url: ' {!! route('categories.store') !!}',
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

            editForm.validate({
                rules:{
                    name:{
                        required:true,
                        minlength:4,
                    }
                },
                messages:{
                    name: "please a valid name",
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            editForm.submit(function($event){
                $event.preventDefault();
                let data = editForm.serializeArray()
                console.log(data)
                $.ajax({
                    url: ' {!! route('categories.update') !!}',
                    method: 'patch',
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

            });
        });

        function openAddModal($event){
            $event.preventDefault()
            addModal.modal('show')
            let name = $('#name')
            let submitBtn = $('#addSubmitBtn')

            name.on('change',function($event){
                if($(this).val() !== null && $(this).val().length > 0){
                    submitBtn.attr('disabled',false)
                }
            });
        }
        function openRemoveModal($event){
            $event.preventDefault();
            let name = $event.target.getAttribute('data-name')
            let id = $event.target.getAttribute('data-id')

            $('#remove_category_id').val(id)
            $('#confirm_category').html(`${name}`)
            removeModal.modal('show')
        }
        function openEditModal($event){
            $event.preventDefault()
            let name = $event.target.getAttribute('data-name')
            let id = $event.target.getAttribute('data-id')
            let editName = $('#edit_name')
            let subBtn = $('#editSubmitBtn')
            editName.val(name)
            editName.on('change',function($event){
                if($(this).val() == null || $(this).val().length === 0){
                    subBtn.attr('disabled',true)
                }
                if($(this).val() !== null && $(this).val().length > 0){
                    subBtn.attr('disabled',false)
                }
            });
            $('#edit_category_id').val(id)
            editModal.modal('show')
        }


    </script>
@endsection
