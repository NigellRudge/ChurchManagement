<?php

$permissionService = new \App\Services\PermissionService();
?>

@extends('layout.admin')

@section('content')
    <div class="row mb-2 pl-1 pr-1 pb-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between">
                        <h5 class="font-weight-bold text-dark">{{trans('common.personal_information_label')}}</h5>
                        <div class="d-flex flex-row">
                                <button onclick="terminateMembership(event)" class="btn btn-danger mr-1 d-none" id="terminateButton">
                                    {{trans('common.terminate_membership')}}
                                    <i class="fa fa-trash ml-1"></i>
                                </button>
                                <button class="btn btn-danger mr-1 d-none" id="deleteButton" onclick="deleteMember(event)">
                                    {{trans('common.delete_member_label')}}
                                    <i class="fa fa-trash ml-1"></i>
                                </button>
                                <button onclick="reactivateMembership(event)"  class="btn btn-info mr-1 d-none" id="reactivateButton">
                                    {{trans('common.reactivate_membership')}}
                                    <i class="fa fa-trash ml-1"></i>
                                </button>

                            <a href="{{route('members.edit',['member'=>$data['member']])}}" class="btn btn-teal text-light">
                                {{trans('common.edit_information_label')}}
                                <i class="fa fa-edit ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-2">
                            <img src="{{ $data['member']->image }}" style="object-fit: cover" alt="member_image" class="rounded"  width="160" height="220"/>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <div class="font-weight-bold text-dark mb-1">
                                    {{trans('common.name_label')}}:
                                </div>
                                <div class="font-weight-normal">{{$data['member']['name']}}</div>
                            </div>
                            <div class="mb-2">
                                <div class="font-weight-bold text-dark mb-1">
                                    <i class="fa fa-calendar mr-1 text-teal"></i>
                                    {{trans('common.birth_date_label')}}:
                                </div>
                                <span class="font-weight-normal">{{$data['member']['birth_date']}} ({{$data['member']['age']}})</span>
                            </div>

                            <div class="mb-2">
                                <div class="font-weight-bold text-dark mb-1">
                                    <i class="fa fa-users-cog text-teal mr-1"></i>
                                    {{trans('common.member_type_label')}}:
                                </div>
                                <span class="font-weight-normal">{{$data['member']['member_type']}}</span>
                            </div>
                            <div class="mb-2">
                                <div class="font-weight-bold  text-dark mb-1">
                                    {{trans('common.gender_label')}}:
                                </div>
                                <span class="font-weight-normal">
                                    @if($data['member']['gender_id'] == 1)
                                        <i class="fa fa-male ml-1 text-primary"></i>
                                    @else
                                        <i class="fa fa-female ml-1 text-primary"></i>
                                    @endif
                                        {{$data['member']['gender']}}
                                </span>
                            </div>
                            <div class="mb-2">
                                <div class="font-weight-bold text-dark mb-1">
                                    <i class="fa fa-calendar mr-1 text-teal"></i>
                                {{trans('common.convert_date_label')}}:
                                </div>
                                <span class="font-weight-normal">
                                    @if(!isset($data['member']['convert_date']))
                                        {{trans('common.no_info')}}
                                    @else
                                        {{$data['member']['convert_date']}}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <div class="font-weight-bold text-dark mb-1">
                                    <i class="fa fa-phone mr-1 text-teal"></i>
                                {{trans('common.phone_number_label')}}:
                                </div>
                                <span class="font-weight-normal">{{$data['member']['phone_number']}}</span>
                            </div>
                            <div class="mb-2">
                                <div class="font-weight-bold text-dark mb-1">
                                    <i class="fa fa-map-marker mr-1 text-teal"></i>
                                {{trans('common.address_label')}}:
                                </div>
                                <span class="font-weight-normal">{{$data['member']['address']}}</span>
                            </div>
                            <div class="mb-2">
                                <div class="font-weight-bold text-dark mb-1">
                                {{trans('common.district_label')}}:
                                </div>
                                <span class="font-weight-normal">{{$data['member']['member_type']}}</span>
                            </div>
                            <div class="mb-2">
                                <div class="font-weight-bold text-dark mb-1">
                                    <i class="fa fa-envelope mr-1 text-teal"></i>
                                {{trans('common.email_label')}}:
                                </div>
                                <span class="font-weight-normal">{{$data['member']['email']}}</span>
                            </div>
                            <div class="mb-2">
                                <div class="font-weight-bold text-dark mb-1">
                                    <i class="fa fa-calendar mr-1 text-teal"></i>
                                    {{trans('common.baptized_date_label')}}:
                                </div>
                                <span class="font-weight-normal">{{$data['member']['baptized_date']}}</span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <div class="font-weight-bold text-dark mb-1">
                                    <i class="fa fa-id-card mr-1 text-teal"></i>
                                    {{trans('common.id_number_label')}}:
                                </div>
                                <span class="font-weight-normal">{{$data['member']['id_number']}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-0">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        @if($permissionService->checkModulePermission(12))
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                                <i class="fa fa-dollar-sign"></i>
                                {{trans('common.seeds_label')}}
                            </a>
                        </li>
                        @endif
                        <li class="nav-item" role="presentation">
                            <a class="nav-link  @if(!$permissionService->checkModulePermission(12))active @endif" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                                <i class="fa fa-users"></i>
                                {{trans('common.family_members_label')}}
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">
                                <i class="fa fa-list-alt"></i>
                                {{trans('common.membership_history')}}
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="files-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false">
                                <i class="fa fa-list-alt"></i>
                                {{trans('common.member_files')}}
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        @if($permissionService->checkModulePermission(12))
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row pb-4">
                                <div class="col d-flex justify-content-end">
                                    <button onclick="openAddSeedModal(event)" class="btn btn-teal font-weight-bold text-light ">
                                        {{trans('common.add_seed_label')}}
                                        <i class="fa fa-plus ml-1"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <table id="datatable" class="table table-bordered border-right border-left border-bottom display compact nowrap">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>{{trans('common.title_label')}}</th>
                                                <th>
                                                    <span class="mr-1"><i class="fa fa-calendar text-teal"></i></span>
                                                    {{trans('common.date_label')}}
                                                </th>
                                                <th>
                                                    <span class="mr-1"><i class="fa fa-cog text-teal"></i></span>
                                                    {{trans('common.type_label')}}
                                                </th>
                                                <th>
                                                    <span class="mr-1"><i class="fa fa-coins text-warning"></i></span>
                                                    {{trans('common.amount_label')}}
                                                </th>
                                                <th style="width: 120px"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="tab-pane fade @if(!$permissionService->checkModulePermission(12)) show active @endif" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row pb-4">
                                <div class="col">
                                    <div class="d-flex justify-content-end">
                                        <button onclick="openAddRelationModal(event)" class="btn btn-teal font-weight-bold text-light">
                                            {{trans('common.add_relation_label')}}
                                            <i class="fa fa-plus ml-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <table id="familyDatatable" class="table table-bordered border-right border-left border-bottom display compact nowrap">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>
                                                    <span class="mr-1"><i class="fa fa-user text-teal"></i></span>
                                                    {{trans('common.name_label')}}
                                                </th>
                                                <th>
                                                    {{trans('common.age_label')}}
                                                </th>
                                                <th>
                                                    {{trans('common.relation_label')}}</th>
                                                <th style="width: 80px"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                            <div class="row">
                                <div class="col">
                                    <table id="historyDataTable" class="table table-bordered border-right border-left border-bottom display compact nowrap">
                                        <thead>
                                            <tr>
                                                <th>{{trans('common.member_type_label')}}</th>
                                                <th>
                                                    <span class="mr-1"><i class="fa fa-calendar-check text-teal"></i></span>
                                                    {{trans('common.start_date')}}
                                                </th>
                                                <th>
                                                    <span class="mr-1"><i class="fa fa-calendar-minus text-teal"></i></span>
                                                    {{trans('common.end_date')}}
                                                </th>
                                                <th>
                                                    {{trans('common.remove_reason')}}
                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
                            <div class="row mb-3">
                                <div class="col d-flex flex-row justify-content-end ">
                                    <button onclick="uploadFile(event)" class="btn btn-teal font-weight-bold">
                                        <i class="fa fa-upload mr-1"></i>
                                        {{trans('common.upload_file')}}
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <table id="filesDataTable" class="table table-bordered border-bottom display compact nowrap">
                                        <thead>
                                            <tr>
                                                <th>{{trans('common.file_name')}}</th>
                                                <th>
                                                    <i class="fa fa-file mr-1"></i>
                                                    {{trans('common.file_name_in_directory')}}
                                                </th>
                                                <th>
                                                    <span class="mr-1"><i class="fa fa-calendar-check text-teal"></i></span>
                                                    {{trans('common.upload_date')}}
                                                </th>
                                                <th>
                                                    <span class="mr-1"><i class="fa fa-users text-teal"></i></span>
                                                    {{trans('common.uploaded_by')}}
                                                </th>
                                                <th style="width: 100px"></th>
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

    <div class="modal fade" id="removeSeedModal" tabindex="-1" role="dialog" aria-labelledby="removeSeedModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="removeSeedModalLabel">{{trans('common.confirm_label')}}</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="remove_seed_form">
                    @csrf
                    <input type="hidden" name="remove_seed_id" id="remove_seed_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-1">
                                {{trans('common.confirm_seed_delete_label')}}:<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_seed"></div> ?
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal">
                            {{trans('common.yes_label')}}
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            {{trans('common.no_label')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addSeedModal" tabindex="-1" role="dialog" aria-labelledby="addSeedModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="addSeedModalLabel">{{trans('common.add_seed_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" id="add_seed_form">
                    <input type="hidden" id="add_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="member_id" name="member_id" value="{{$data['member']['id']}}">
                    <div class=" mt-2 pl-3 pr-3">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="seed_type_id">{{trans('common.seed_type_label')}}</label>
                                    <select type="text" id="seed_type_id" name="seed_type_id" class="form-control">
                                        <option value="0">{{trans('common.select_type_label')}}</option>
                                        @foreach($data['types'] as $type)
                                            <option value="{{$type->id}}">{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                    <div id="typeError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="title">{{trans('common.title_label')}}</label>
                                    <input type="text" id="title" name="title" class="form-control">
                                    <div id="titleError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="date" class="text-dark">{{trans('common.date_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                        <input id="date" name="date" class="form-control" type="text" />
                                    </div>
                                    <div id="dateError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="currency_id" class="text-dark">{{trans('common.currency_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-dollar-sign"></i>
                                            </div>
                                        </div>
                                        <select name="currency_id" id="currency_id" type="text" class="form-control">
                                            <option value="0">{{trans('common.select_currency_label')}}</option>
                                            @foreach($data['currencies'] as $currency)
                                                <option value="{{$currency->id}}">{{$currency->code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="currencyError" class="customError"></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="amount"  class="text-dark">{{trans('common.amount_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text  bg-white text-warning">
                                                <i class="fas fa-coins"></i>
                                            </div>
                                        </div>
                                        <input name="amount" step="0.01" min="0.01" max="100000000" id="amount" placeholder="$0.00" type="number" class="form-control" />
                                    </div>
                                    <div id="amountError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal" id="submitBtn">
                            <i class="fas fa-save"></i>
                            {{trans('common.save_label')}}
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-ban"></i>
                            {{trans('common.cancel_label')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editSeedModal" tabindex="-1" role="dialog" aria-labelledby="editSeedModalLabel" aria-hidden="true"c>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="editSeedModalLabel">{{trans('common.edit_tide_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="edit_seed_form">
                    @csrf
                    <input type="hidden" id="edit_member_id" name="member_id" value="{{$data['member']['id']}}">
                    <input type="hidden" name="edit_seed_id" id="edit_seed_id">
                    <div class=" mt-2 pl-3 pr-3">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_seed_type_id">{{trans('common.type_label')}}</label>
                                    <select type="text" id="edit_seed_type_id" name="seed_type_id" class="form-control">
                                        <option value="0">{{trans('common.select_type_label')}}</option>
                                        @foreach($data['types'] as $type)
                                            <option value="{{$type->id}}">{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                    <div id="editTypeError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_title">{{trans('common.title_label')}}</label>
                                    <input type="text" id="edit_title" name="title" class="form-control">
                                    <div id="editTitleError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_date"  class="text-dark">{{trans('common.date_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="far fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input id="edit_date" name="date" class="form-control" type="text" />
                                    </div>
                                    <div id="editDateError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="edit_currency_id"  class="text-dark">{{trans('common.currency_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-teal">
                                                <i class="fas fa-dollar-sign"></i>
                                            </div>
                                        </div>
                                        <select name="currency_id" id="edit_currency_id" class="form-control">
                                            @foreach($data['currencies'] as $currency)
                                                <option value="{{$currency->id}}">{{$currency->code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="editCurrencyError" class="customError"></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_amount"  class="text-dark">{{trans('common.amount_label')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white text-warning">
                                                <i class="fas fa-coins"></i>
                                            </div>
                                        </div>
                                        <input name="amount" step="0.01" min="0.01" max="100000000" id="edit_amount" placeholder="$0.00" type="number" class="form-control" />
                                    </div>
                                    <div id="editAmountError" class="customError"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal">
                            <i class="fas fa-save"></i>
                            {{trans('common.save_label')}}
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-ban"></i>
                            {{trans('common.cancel_label')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addRelationModal" tabindex="-1" role="dialog" aria-labelledby="addRelationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="addRelationModalLabel">{{trans('common.add_relation_label')}}</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="d-flex justify-content-center pb-2 pt-2">
                            <img src="{{ asset('storage/placeholder-male.jpg') }}" id="member_image" alt="member_image" class="rounded" width="140" height="170" style="object-fit: cover">
                        </div>
                    </div>
                </div>
                <form method="post" action="#" id="add_relation_form">
                    @csrf
                    <input type="hidden" name="member_id" id="member_id" value="{{$data['member']['id']}}">
                    <div class="modal-body">
                        <div class=" pl-2 pt-1 pr-1 pb-2">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="relative_id" class="font-weight-bold text-dark">{{trans('common.familie_member_label')}}</label>
                                        <select class="form-control" id="relative_id" name="relative_id"></select>
                                        <div id="relativeError" class="customError"></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="relation_id" class="font-weight-bold text-dark">{{trans('common.relation_label')}}</label>
                                        <select class="form-control" id="relation_id" name="relation_id" >
                                                <option value="" disabled>{{trans('common.select_option')}}</option>
                                            @foreach($data['relationship_types'] as $relationshipType)
                                                <option value="{{$relationshipType->id}}">{{trans('common' .  '.' .$relationshipType->trans_code)}}</option>
                                            @endforeach
                                        </select>
                                        <div id="relationError" class="customError"></div>
                                    </div>
                                </div>
                                <div class="col">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-teal" >
                            <span class="mr-1"><i class="fa fa-save"></i></span>
                            {{trans('common.save_label')}}
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <span class="mr-1"><i class="fa fa-ban"></i></span>
                            {{trans('common.cancel_label')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeRelationModal" tabindex="-1" role="dialog" aria-labelledby="removeRelationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="removeRelationModalLabel">Confirm</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="remove_relation_form">
                    @csrf
                    <input type="hidden" name="remove_relation_id" id="remove_relation_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-1">
                                {{trans('common.confirm_remove_relative_label')}}<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_relation"></div> ?
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

    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="uploadModalLabel">{{trans('common.upload_file')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <form method="post" id="uploadForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="upload_member_id" name="member_id" value="{{$data['member']['id']}}">
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
                    <div class="modal-footer">
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
    </div>

    <div class="modal fade" id="deleteFileModal" tabindex="-1" role="dialog" aria-labelledby="deleteFileModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="deleteFileModalLabel">{{trans('common.confirm_label')}}</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="#" id="remove_file_form">
                    @csrf
                    <input type="hidden" name="file_id" id="remove_file_id">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="pt-2">
                                {{trans('common.confirm_delete_file')}}<br>
                                <div class="d-inline text-teal font-weight-bold" id="confirm_file"></div> ?
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

    <div class="modal fade" id="terminateMembershipModal" tabindex="-1" role="dialog" aria-labelledby="terminateMembershipModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="terminateMembershipModalLabel">{{trans('common.terminate_membership')}}</h5>
                    <button type="button" class="close bg-teal border-none" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class="row">
                    <div class="col p-4 d-flex flex-row justify-content-center">
                        {{trans('common.confirm_delete_member_label')}}
                    </div>
                </div>
                <div class="row">
                    <div class="col d-flex p-1 flex-column align-items-center">
                        <img id="member_image" alt="member_image" src="{{ $data['member']->image }}" width="120" height="180" style="object-fit: cover; border-radius: 8px">
                        <div class="mt-2 d-inline text-dark font-weight-bold" id="confirm_member_name"></div>
                    </div>
                </div>
                <form method="post" action="#" id="terminate_membership_form">
                    @csrf
                    <input type="hidden" name="member_id"  id="remove_member_id" value="{{ $data['member']['id'] }}"/>
                    <div class="modal-body px-4">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="remove_date" class="font-weight-normal text-dark">{{trans('common.date_label')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-white">
                                                <i class="far fa-calendar-alt text-teal"></i>
                                            </div>
                                        </div>
                                        <input type="text"  autocomplete="off" id="remove_date" name="remove_date" class="form-control">
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="remove_reason" class="font-weight-normal text-dark">{{trans('common.remove_reason')}}</label>
                                    <textarea type="text" id="remove_reason" name="remove_reason" rows="4" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" disabled>
                            <span class="mr-1"><i class="fa fa-trash"></i></span>
                            {{trans('common.yes_label')}}
                        </button>
                        <button type="button" class="btn btn-secondary">{{trans('common.no_label')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reactivateMembershipModal" tabindex="-1" role="dialog" aria-labelledby="reactivateMembershipModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="reactivateMembershipModalLabel">{{trans('common.reactivate_member_label')}}</h5>
                    <button type="button" class="close bg-teal border-none" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class="row">
                    <div class="col p-4 d-flex flex-row justify-content-center">
                        {{trans('common.confirm_reactivate_member_label')}}
                    </div>
                </div>
                <div class="row">
                    <div class="col d-flex p-1 flex-column align-items-center">
                        <img id="member_image" alt="member_image" src="{{ $data['member']->memberImage() }}" width="120" height="180" style="object-fit: cover; border-radius: 8px">
                        <div class="mt-2 d-inline text-dark font-weight-bold" id="reactivate_member_name"></div>
                    </div>
                </div>
                <form method="post" action="#" id="reactivate_membership_form">
                    @csrf
                    <input type="hidden" name="member_id"  id="reactivate_member_id" value="{{ $data['member']['id'] }}"/>
                    <div class="modal-body px-4">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">
                            <span class="mr-1"><i class="fa fa-trash"></i></span>
                            {{trans('common.yes_label')}}
                        </button>
                        <button type="button" class="btn btn-secondary">{{trans('common.no_label')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteMemberModal" tabindex="-1" role="dialog" aria-labelledby="deleteMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-teal text-light">
                    <h5 class="modal-title" id="deleteMemberModalLabel">{{trans('common.confirm_label')}}</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post"  id="deleteMemberForm">
                    @csrf
                    <input type="hidden" name="member" id="delete_member_id" value="{{$data['member']['id']}}">
                    <div class="modal-body">
                        <div class="d-flex flex-row align-baseline">
                            <div class="text-teal mr-2 ml-1" style="font-size: 3.0rem;">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="">
                                <span class="text-lg">{{trans('common.confirm_delete_member_label')}}</span><br>
                                <div class=" text-teal font-weight-bold pt-1">{{$data['member']['name']}}</div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-trash mr-1"></i>
                            {{trans('common.yes_label')}}
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{trans('common.no_label')}}
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
        const uploadModal = $('#uploadModal')
        const uploadForm = $('#uploadForm')
        const addSeedModal = $('#addSeedModal')
        const editSeedModal = $('#editSeedModal')
        const removeSeedModal = $('#removeSeedModal')
        const terminateMembershipModal = $('#terminateMembershipModal')
        const terminateMembershipForm = $('#terminate_membership_form')

        const reactivateMembershipModal = $('#reactivateMembershipModal')
        const reactivateMembershipForm = $('#reactivate_membership_form')

        const deleteMemberModal = $('#deleteMemberModal')
        const deleteMemberForm = $('#deleteMemberForm')

        const seedsDataTable = $("#datatable").DataTable({
            processing: true,
            serverSide: true,
            lengthMenu: [5, 10, 25, 50, 75, 100 ],
            autoWidth: false,
            pageLength:5,
            ajax: {
                url:'{!! route('seeds.index') !!}',
                data: function(d){
                    d.member_id = parseInt({!! $data['member']['id'] !!})
                },
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'title', name: 'title' },
                { data: 'date', name: 'date' },
                { data: 'type_info', name: 'type' },
                { data: 'amount_formatted', name: 'amount' },
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ]
        });
        const familyDataTable = $('#familyDatatable').DataTable({
            processing: true,
            autoWidth: false,
            serverSide: true,
            lengthMenu: [5, 10, 25, 50, 75, 100 ],
            pageLength:5,
            ajax: {
                url:'{!! route('members.family',['member'=>$data['member']]) !!}',
                data: function(d){
                },
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'relative_info', name: 'name_relative' },
                { data: 'relative_age', name: 'relative_age' },
                { data: 'trans_rel', name: 'trans_rel' },
                { data:'actions', name:'actions', orderable: false, searchable: false}
            ]
        });
        const historyDataTable = $('#historyDataTable').DataTable({
            processing: true,
            autoWidth: false,
            serverSide: true,
            lengthMenu: [5, 10, 25, 50, 75, 100 ],
            pageLength:5,
            ajax: {
                url:'{!! route('members.membershipHistory')!!}',
                method:'post',
                data: function(d){
                    d.member_id = parseInt({{$data['member']['id']}})
                    d._token = '{!! csrf_token() !!}'
                },
            },
            columns: [
                { data: 'membership_type', name: 'membership_type' },
                { data: 'start_info', name: 'start_info' },
                { data: 'end_info', name: 'end_info' },
                { data: 'end_reason', name: 'end_reason' },
            ]
        });
        const fileDataTable = $('#filesDataTable').DataTable({
            processing: true,
            autoWidth: false,
            serverSide: true,
            lengthMenu: [5, 10, 25, 50, 75, 100 ],
            pageLength:5,
            ajax: {
                url:'{!! route('memberFiles.index')!!}',
                method:'post',
                data: function(d){
                    d.member_id = parseInt({{$data['member']['id']}})
                    d._token = '{!! csrf_token() !!}'
                },
            },
            columns: [
                { data: 'name', name: 'name' },
                { data: 'file_name', name: 'file_name' },
                { data: 'upload_date', name: 'upload_date' },
                { data: 'uploaded_by', name: 'uploaded_by' },
                { data: 'actions', name: 'actions' },
            ]
        });

        const removeSeedForm = $('#remove_seed_form')
        const addSeedForm = $('#add_seed_form')
        const editSeedForm = $('#edit_seed_form')
        const removeFileForm = $('#remove_file_form')
        const relationForm = $('#add_relation_form')
        const removeRelationForm = $('#remove_relation_form')

        $(document).ready(function(){
            loadButtons();

            removeSeedForm.submit(function($event) {
                $event.preventDefault()
                let data = $(this).serialize()
                console.log(data);
                $.ajax({
                    url: '{!! route('seeds.destroy') !!}',
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
                            $('#removeSeedModal').modal('hide')
                            seedsDataTable.ajax.reload()
                            toastr.warning(message, 'Success')
                        }
                    }
                })
            })

            addSeedForm.submit(function($event){
                $event.preventDefault();
                addSeedForm.validate({
                    rules:{
                        seed_type_id:{
                            required:true,
                            min:1,
                        },
                        title:{
                            required:true,
                            minlength:4,
                            maxlength:50
                        },
                        date: {
                            required:true,
                            date: true
                        },
                        currency_id: {
                            required:true,
                            min:1,
                        },
                        amount: {
                            required:true,
                        }
                    },
                    messages:{
                        seed_type_id:{
                            required:'{!! trans('custom_validation.required_field') !!}',
                            min:'{!! trans('custom_validation.select_option') !!}'
                        },
                        title:{
                            required:'{!! trans('custom_validation.required_field') !!}',
                            minlength:'{!! trans('custom_validation.min_length',['min' => 4]) !!}',
                            maxlength:'{!! trans('custom_validation.max_length',['max' => 50]) !!}'
                        },
                        date: {
                            required:'{!! trans('custom_validation.required_field') !!}',
                            date:'{!! trans('custom_validation.valid_date') !!}'
                        },
                        currency_id: {
                            required:"{!! trans('custom_validation.select_currency') !!}",
                            min:"{!! trans('custom_validation.select_currency') !!}",
                        },
                        amount: {
                            required:'{!! trans('custom_validation.enter_amount') !!}',
                        }
                    },
                    errorPlacement: function(error, element){
                        switch(element.attr('name')){
                            case 'seed_type_id':
                                $('#typeError').html(error)
                                break;
                            case 'title':
                                $('#titleError').html(error)
                                break;
                            case 'date':
                                $('#dateError').html(error)
                                break;
                            case 'currency_id':
                                $('#currencyError').html(error)
                                break;
                            case 'amount':
                                $('#amountError').html(error)
                                break;
                        }
                    },
                    errorClass: 'is-invalid',
                    validClass: 'is-valid',
                })
                if(addSeedForm.valid()){
                    let data = $(this).serialize();
                    console.log(data)
                    $.ajax({
                        url: ' {!! route('seeds.store') !!}',
                        method: 'post',
                        data: data,
                        complete: function (xhr,status) {
                            if(xhr.status === 201){
                                let message = xhr.responseJSON.message
                                console.log(message)
                                $('#addSeedModal').modal('toggle')
                                seedsDataTable.ajax.reload()
                                toastr.success(message, 'Success')
                            }
                        }
                    })
                }

            });

            editSeedForm.submit(function($event){
                $event.preventDefault();
                editSeedForm.validate({
                    rules:{
                        seed_type_id:{
                            required:true,
                            min:1,
                        },
                        title:{
                            required:true,
                            minlength:4,
                            maxlength:50
                        },
                        date: {
                            required:true,
                            date: true
                        },
                        currency_id: {
                            required:true,
                            min:1,
                        },
                        amount: {
                            required:true,
                        }
                    },
                    messages:{
                        seed_type_id:{
                            required:'{!! trans('custom_validation.required_field') !!}',
                            min:'{!! trans('custom_validation.select_option') !!}'
                        },
                        title:{
                            required:'{!! trans('custom_validation.required_field') !!}',
                            minlength:'{!! trans('custom_validation.min_length',['min' => 4]) !!}',
                            maxlength:'{!! trans('custom_validation.max_length',['max' => 50]) !!}'
                        },
                        date: {
                            required:'{!! trans('custom_validation.required_field') !!}',
                            date:'{!! trans('custom_validation.valid_date') !!}'
                        },
                        currency_id: {
                            required:"{!! trans('custom_validation.select_currency') !!}",
                            min:"{!! trans('custom_validation.select_currency') !!}",
                        },
                        amount: {
                            required:'{!! trans('custom_validation.enter_amount') !!}',
                        }
                    },
                    errorPlacement: function(error, element){
                        switch(element.attr('name')){
                            case 'seed_type_id':
                                $('#editTypeError').html(error)
                                break;
                            case 'title':
                                $('#editTitleError').html(error)
                                break;
                            case 'date':
                                $('#editDteError').html(error)
                                break;
                            case 'currency_id':
                                $('#editCurrencyError').html(error)
                                break;
                            case 'amount':
                                $('#editAmountError').html(error)
                                break;
                        }
                    },
                    errorClass: 'is-invalid',
                    validClass: 'is-valid',
                })
                if(editSeedForm.valid()) {
                    const data = $(this).serialize()
                    $.ajax({
                        url: ' {!! route('seeds.update') !!}',
                        method: 'patch',
                        data: data,
                        complete: function ({status, responseJSON}) {
                            if (status === 201) {
                                let {message} = responseJSON
                                editSeedModal.modal('hide')
                                seedsDataTable.ajax.reload()
                                toastr.success(message, 'Success')
                            }
                        }
                    })
                }
            });

            uploadForm.validate({
                rules:{
                    file:{
                        required:true,
                        extension:"docx|doc|xlsx|csv|pdf|jpeg|jpg|png"
                    },
                    name:{
                        required:true,
                        minlength:5,
                        maxlength:40,
                    }
                },
                messages:{
                    file: {
                        required:'Please select a file',
                        extension:'Please select correct file type'
                    },
                    name: {
                        required:'Please enter name',
                        minlength:'name must be longer than 5 characters',
                        maxlength:'name must be shorter than 40 characters',
                    },
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
            })
            uploadForm.submit(function($event){
                $event.preventDefault();
                const data = new FormData(this);
                //console.log(data)
                $.ajax({
                    url: ' {!! route('memberFiles.upload') !!}',
                    method: 'post',
                    processData: false,
                    contentType: false,
                    data: data,
                    complete: function ({status,responseJSON}) {
                        if(status === 200){
                            let message = responseJSON
                            $('#uploadModal').modal('hide')
                            fileDataTable.ajax.reload()
                            toastr.success(message,'Success')
                        }
                    }
                })

            });

            removeFileForm.submit(function ($event) {
                $event.preventDefault();
                const data = $(this).serialize()
                console.log(data)
                $.ajax({
                    url:'{!! route('memberFiles.delete') !!}',
                    method:'delete',
                    data:data,
                    complete: function({status,responseJSON}){
                        if(status === 200){
                            let message = responseJSON
                            $('#deleteFileModal').modal('hide')
                            fileDataTable.ajax.reload()
                            toastr.success(message,'Success')
                        }
                    }
                })
            })

            relationForm.submit(function($event){
                $event.preventDefault()
                relationForm.validate({
                    rules:{
                        relative_id:{
                            required:true,
                            min:1,
                        },
                        relation_id:{
                            required:true,
                            min:1,
                        }
                    },
                    messages:{
                        relative_id:{
                            required:'{!! trans('custom_validation.select_member') !!}',
                            min:'{!! trans('custom_validation.select_member') !!}',
                        },
                        relation_id:{
                            required:'{!! trans('custom_validation.select_option') !!}',
                            min:'{!! trans('custom_validation.select_option') !!}',
                        }
                    },
                    errorPlacement: function(error, element){
                        switch(element.attr('name')){
                            case 'relative_id':
                                $('#relativeError').html(error)
                                break;
                            case 'relation_id':
                                $('#relationError').html(error)
                                break;
                        }
                    },
                    errorClass: 'is-invalid',
                    validClass: 'is-valid',
                })
                if(relationForm.valid()){
                    let data = $(this).serialize()
                    console.table(data)
                    $.ajax({
                        url: '{!! route('members.addRelation') !!}',
                        method:'post',
                        data: data,
                        complete: function ({status, responseJSON}) {
                            if(status === 201){
                                let {message} = responseJSON
                                $('#addRelationModal').modal('hide')
                                familyDataTable.ajax.reload()
                                toastr.success(message,'Success')
                            }
                        }
                    })
                }
            });

            removeRelationForm.submit(function ($event) {
                $event.preventDefault()
                let data = $(this).serialize()
                console.table(data)
                $.ajax({
                    url: '{!! route('members.removeRelation') !!}',
                    method:'delete',
                    data: data,
                    complete: function (xhr) {
                        if(xhr.status === 201){
                            let message = xhr.responseJSON.message
                            console.log(message)
                            $('#removeRelationModal').modal('hide')
                            familyDataTable.ajax.reload()
                            toastr.warning(message,'Success')
                        }
                    }
                })
            })

            terminateMembershipForm.submit(function(event){
                event.preventDefault()
                const data = $(this).serialize()
                console.log(data)
                $.ajax({
                    url:'{!! route('members.endMembership') !!}',
                    method:'post',
                    data:data,
                    complete: function({status, responseJSON}) {
                        if (status === 200) {
                            const {message} = responseJSON
                            toastr.info(message, '{!! trans('common.success_label') !!}')
                            historyDataTable.ajax.reload()
                            loadButtons()
                            terminateMembershipModal.modal('hide')
                        }
                    }
                });
            });

            reactivateMembershipForm.submit(function($event){
                $event.preventDefault()
                let data = $(this).serialize()
                $.ajax({
                    url:'{!! route('members.reactivateMembership') !!}',
                    method:'post',
                    data:data,
                    complete: function({status, responseJSON} ){
                        if(status === 200) {
                            const {message} = responseJSON
                            toastr.info(message, '{!! trans('common.success_label') !!}')
                            reactivateMembershipModal.modal('hide')
                            loadButtons()
                            historyDataTable.ajax.reload()
                        }
                    }
                })
            });

            deleteMemberForm.submit(function(event){
                event.preventDefault();
                const data = $(this).serialize()
                $.ajax({
                    url:'{{ route('members.destroy',['member' => $data['member']['id']]) }}',
                    method:'delete',
                    data:data,
                    complete:function({status,responseJSON}){
                        if(status === 200){
                            const {message} = responseJSON
                            toastr.warning(message,'{!! trans('common.success_label') !!}')
                            deleteMemberModal.modal('hide')
                            historyDataTable.ajax.reload()
                        }
                    }
                })
            })
            $(".modal").on("hidden.bs.modal", function() {
                clearForm('add_seed_form');
                clearForm('edit_seed_form');
                clearForm('add_relation_form');
                clearForm('uploadForm')
                clearForm('terminateMembershipForm')
                clearForm('reactivateMembershipForm')
                $('#member_image').attr('src','{!! asset('storage/placeholder-male.jpg') !!}')
            });

            $('#removeRelBtn').on('click',function ($event) {
                $event.preventDefault()
                let data = {
                    id: parseInt($(this).data('relation-id')),
                    name: $(this).data('name'),
                    relation: $(this).data('relation')
                }
                console.log(data)
                $('#remove_relation_id').val(data.id)
                $('#confirm_relation').html(`${data.name} (${data.relation})`)
                $('#removeRelationModal').modal('show')
            })
        });

        function openAddSeedModal($event){
            $event.preventDefault()
            const btn = $('#add_seed_form button:submit')
            const amount = $('#amount')
            addSeedModal.modal('show')
            $('#date').daterangepicker({
                singleDatePicker:true,
                autoUpdateInput: true,
                showDropdowns: true,
                minYear: 1901,
                locale:datePickerTran
            })
            amount.on('change',function(){
                let value = $(this).val();
                if(value > 0){
                    btn.prop('disabled',false)
                }
                if(value === 0){
                    btn.prop('disabled',true)
                }
            })
        }

        function deleteSeed($event){
            $event.preventDefault()
            const member = $event.target.getAttribute('data-member')
            const title = $event.target.getAttribute('data-title')
            const amount = $event.target.getAttribute('data-amount')
            const currency = $event.target.getAttribute('data-currency')
            const seedId = $event.target.getAttribute('data-id')
            $('#confirm_seed').html(`${title}: ${member} - ${currency}${amount} `);
            $('#remove_seed_id').val(seedId.toString());
            removeSeedModal.modal('show')
        }

        function editSeed($event){
            $event.preventDefault()
            let seedId = parseInt($event.target.getAttribute('data-id'))
            console.log(`Seed Id: ${seedId}`)
            $("input[name='edit_seed_id']").val(seedId)
            let data = {
                "_token": '{!! csrf_token() !!}',
                "seed_id": seedId
            }
            $.ajax({
                url: '{!! route('seeds.getById') !!}',
                method:'post',
                data:data,
                complete: function({status,responseJSON }){
                    let {seed} = responseJSON
                    if(status === 200){
                        $('#edit_amount').val(parseFloat((currency(seed.amount).intValue/100).toString()))
                        $('#edit_currency_id').val(`${seed.currency_id}`)
                        $('#edit_seed_type_id').val(`${seed.seed_type_id}`)
                        $('#edit_title').val(seed.title)
                        $('#edit_date').daterangepicker({
                            singleDatePicker:true,
                            autoUpdateInput: true,
                            startDate: seed.date,
                            showDropdowns: true,
                            minYear: 1901,
                            locale:datePickerTran
                        })
                        $('#editSeedModal').modal('show')
                    }
                }
            })
        }

        function openAddRelationModal($event){
            $event.preventDefault()
            const relative = $('#relative_id')
            let btn = $('#add_relation_form button:submit')
            let relationType = $('#relation_id')
            relative.select2({
                theme: 'bootstrap4',
                placeholder: '{!! trans('custom_validation.select_member') !!}',
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
                    processResults: function(data,params){
                        params.page = params.page || 1;
                        console.log(params)
                        return {
                            results: data.results,
                            pagination: {
                                more: (params.page * 10) < data.total_items
                            }
                        }
                    }
                }
            });
            relative.on('change',function(){
                console.log('get image  ')
                let value = $(this).val()
                if(value !== null && value !== 0){
                    $.ajax({
                        url: '{!! route('members.getByIdJson') !!}',
                        method: 'post',
                        data: {
                            _token: '{!!  csrf_token() !!}',
                            id: value
                        },
                        complete: function({status,responseJSON}){
                            if(status === 200){
                                let {member} = responseJSON
                                console.log(responseJSON)
                                $('#member_image').attr('src',member.member_image)
                                relationType.prop('disabled',false)
                            }
                        }
                    })
                }
                $('#add_submitBtn').attr('disabled',false);
            })
            relationType.on('change',function () {
                let value  = $(this).val()
                if(value !== 0){
                    btn.prop('disabled',false)
                }
            })
            $('#addRelationModal').modal('show')
        }

        function removeRelation($event){
            let modal = $('#removeRelationModal')
            let relationName = $event.target.getAttribute('data-relation')
            let relativeName = $event.target.getAttribute('data-name')
            let relationId = $event.target.getAttribute('data-id')
            $('#remove_relation_id').val(relationId)
            $('#confirm_relation').html(`${relativeName} -  (${relationName}) `);
            modal.modal('show')
        }

        function deleteFile($event){
            let fileId = $event.target.getAttribute('data-id')
            let fileName = $event.target.getAttribute('data-file_name')
            $('#remove_file_id').val(fileId)
            $('#confirm_file').html(fileName)
            $('#deleteFileModal').modal('show')
        }

        function uploadFile(event){
            event.preventDefault()
            uploadModal.modal('show')
        }

        function terminateMembership(event){
            const removeDate = $('#remove_date')
            const removeReason = $('#remove_reason')
            removeDate.daterangepicker({
                singleDatePicker:true,
                autoUpdateInput: true,
                showDropdowns: true,
                minYear: 1901,
                locale:datePickerTran
            })
            removeReason.on('change',function(event){
                let value = $(this).val()
                console.log(value)
                if(value.length >0 || true){
                    $('#terminate_membership_form button:submit').prop('disabled',false)
                }
                else{
                    $('#terminate_membership_form button:submit').prop('disabled',true)
                }
            })
            terminateMembershipModal.modal('show')
        }

        function reactivateMembership(event){
            reactivateMembershipModal.modal('show')
            $('#reactivate_member_name').html('{!! $data['member']['name'] !!}')
        }

        function deleteMember($event){
            deleteMemberModal.modal('show')
        }

        function loadButtons(){
            const deleteBtn = $('#deleteButton')
            const terminateBtn = $('#terminateButton')
            const reactivateBtn = $('#reactivateButton')
            $.ajax({
                method:'post',
                url:'{!! route('members.checkStatus') !!}',
                data:{
                    _token:'{!! csrf_token() !!}',
                    member_id:parseInt({!! $data['member']['id'] !!})
                },
                complete:function({status,responseJSON}){
                    if(status === 200){
                        const {member} = responseJSON
                        if(member.active){
                            terminateBtn.removeClass('d-none')
                            deleteBtn.addClass('d-none')
                            reactivateBtn.addClass('d-none')
                        }
                        else {
                            terminateBtn.addClass('d-none')
                            deleteBtn.removeClass('d-none')
                            reactivateBtn.removeClass('d-none')
                        }
                    }
                }
            })
        }
    </script>
@endsection
