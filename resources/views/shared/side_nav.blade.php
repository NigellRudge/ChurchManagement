<?php

use App\Services\PermissionService;

$permissionService = new PermissionService();

?>
<ul class="navbar-nav bg-teal sidebar sidebar-dark accordion" id="accordionSidebar" >
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <i class="fas fa-church"></i>
        </div>
        <div class="sidebar-brand-text mx-3">{{config('constants.APP_NAME')}}</div>
    </a>
    <hr class="sidebar-divider">

    @if($permissionService->checkCategoryPermission(config('constants.MODULE_CATEGORY_MEMBERS')))
        <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseMembers" aria-expanded="true" aria-controls="collapseMembers">
                    <i class="fas fa-users-cog {{strtolower($data['category_name']) === 'members' ? 'text-warning': '' }}"></i>
                    <span class="{{strtolower($data['category_name']) === 'members' ? 'font-weight-bold text-white': '' }}">{{trans('common.members_label')}}</span>
                </a>
                <div id="collapseMembers" class="collapse {{strtolower($data['category_name']) === 'members' ? 'show': '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-1 collapse-inner rounded">
                        @if($permissionService->checkModulePermission(1))
                            <a class="collapse-item {{ ((strtolower($data['action_name']) == 'index') && (strtolower($data['controller_name']) == 'members')) ? 'bg-teal text-white':'' }}" href="{{ route('members.index') }}">
                                <i class="fas fa-users {{ ((strtolower($data['action_name']) == 'index') && (strtolower($data['controller_name']) == 'members')) ? 'text-white':'text-teal' }}"></i>
                                {{trans('common.member_overview_label')}}
                            </a>
                        @endif
                        @if($permissionService->checkModulePermission(2))
                            <a class="collapse-item {{ ((strtolower($data['action_name']) == 'converts index') && (strtolower($data['controller_name']) == 'members')) ? 'bg-teal text-white':'' }}" href="{{ route('convert.index') }}">
                                <i class="fas fa-hospital-user mr-1 {{ ((strtolower($data['action_name']) == 'converts index') && (strtolower($data['controller_name']) == 'members')) ? 'text-white':'text-teal' }}"></i>
                                {{trans('common.converts_label')}}
                            </a>
                        @endif
                        @if($permissionService->checkModulePermission(3))
                            <a class="collapse-item {{ ((strtolower($data['controller_name']) == 'infant dedication') && (strtolower($data['category_name']) == 'members')) ? 'bg-teal text-white':'' }}" href="{{ route('dedication.index') }}">
                                <i class="fas fa-baby-carriage mr-1 {{ ((strtolower($data['controller_name']) == 'infant dedication') && (strtolower($data['category_name']) == 'members')) ? 'text-white':'text-teal' }}"></i>
                                {{trans('common.infant_dedication_label')}}
                            </a>
                        @endif
                        @if($permissionService->checkModulePermission(4))
                            <a class="collapse-item {{ ((strtolower($data['action_name']) == 'index') && (strtolower($data['controller_name']) == 'registrations')) ? 'bg-teal text-white':'' }}" href="{{ route('covid-registration.index') }}">
                                <i class="fas fa-clipboard-list mr-1 {{ ((strtolower($data['action_name']) == 'index') && (strtolower($data['controller_name']) == 'registrations')) ? 'text-white':'text-teal' }}"></i>
                                {{trans('common.covid_reg_sheets_label')}}
                            </a>
                        @endif
                        @if($permissionService->checkModulePermission(5))
                            <a class="collapse-item {{ ((strtolower($data['action_name']) == 'index') && (strtolower($data['controller_name']) == 'service club')) ? 'bg-teal text-white':'' }}" href="{{ route('service_club.index') }}">
                                <i class="fas fa-building mr-1 {{ ((strtolower($data['action_name']) == 'index') && (strtolower($data['controller_name']) == 'service club')) ? 'text-white':'text-teal' }}"></i>
                                {{trans('common.service_club_label')}}
                            </a>
                        @endif
                    </div>
                </div>
            </li>
    @endif

    @if($permissionService->checkCategoryPermission(config('constants.MODULE_CATEGORY_GROUPS')))
        <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseWorkers" aria-expanded="true" aria-controls="collapseWorkers">
                <i class="fas fa-users {{strtolower($data['category_name']) === 'workers' ? 'text-warning': '' }}"></i>
                <span class="{{strtolower($data['category_name']) === 'workers' ? 'font-weight-bold text-white': '' }}">{{trans('common.workers_label')}}</span>
            </a>
            <div id="collapseWorkers" class="collapse {{strtolower($data['category_name']) === 'workers' ? 'show': '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-1 collapse-inner rounded">
                    @if($permissionService->checkModulePermission(6))
                        <a class="collapse-item {{ strtolower($data['controller_name']) == 'groups' ? 'bg-teal text-white':'' }}" href="{{ route('work-groups.index') }}">
                            <i class="fas fa-users mr-1 {{ strtolower($data['controller_name']) == 'groups' ? 'text-white':'text-teal' }}"></i>
                            {{trans('common.work_groups_label')}}
                        </a>
                    @endif
                    @if($permissionService->checkModulePermission(7))
                        <a class="collapse-item {{ strtolower($data['controller_name']) == 'worker attendance' ? 'bg-teal text-white':'' }}" href="{{ route('workerAttendance.index') }}">
                            <i class="fas fa-users mr-1 {{ strtolower($data['controller_name']) == 'worker attendance' ? 'text-white':'text-teal' }}"></i>
                            {{trans('common.workers_attendance_label')}}
                        </a>
                    @endif
                </div>
            </div>
        </li>
    @endif

    @if($permissionService->checkCategoryPermission(config('constants.MODULE_CATEGORY_YOUTH')))
        <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseJW" aria-expanded="true" aria-controls="collapseJW">
                <i class="fas fa-user-ninja {{strtolower($data['category_name']) === 'joshua warriors' ? 'text-warning': '' }}"></i>
                <span class="{{strtolower($data['category_name']) === 'joshua warriors' ? 'font-weight-bold text-white': '' }}">Joshua Warriors</span>
            </a>
            <div id="collapseJW" class="collapse {{strtolower($data['category_name']) === 'joshua warriors' ? 'show': '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-1 collapse-inner rounded">
                    @if($permissionService->checkModulePermission(8))
                        <a class="collapse-item  {{ strtolower($data['controller_name']) == 'attendance' ? 'bg-teal text-white':'' }}" href="{{ route('attendance.index') }}">
                            <i class="fas fa-clipboard-check mr-1 {{ strtolower($data['controller_name']) == 'attendance' ? 'text-white':'text-teal' }}"></i>
                            {{trans('common.attendance_label')}}
                        </a>
                    @endif
                    @if($permissionService->checkModulePermission(9))
                    <a class="collapse-item {{ ((strtolower($data['category_name']) == 'joshua warriors') && (strtolower($data['controller_name']) == 'eagle groups') && (strtolower($data['action_name'])== 'index')) ? 'bg-teal text-white':'' }}" href="{{ route('eagle-group.index') }}">
                        <i class="fas fa-dove mr-1 {{ ((strtolower($data['category_name']) == 'joshua warriors') && (strtolower($data['controller_name']) == 'eagle groups') && (strtolower($data['action_name'])== 'index')) ? 'text-white':'text-teal' }}"></i>
                        {{trans('common.eagle_groups_label')}}
                    </a>
                    @endif
                    @if($permissionService->checkModulePermission(10))
                    <a class="collapse-item {{ strtolower($data['controller_name']) == 'visitors' ? 'bg-teal text-white':'' }}" href="{{ route('visitors.index') }}">
                        <i class="fas fa-user-plus mr-1 {{ strtolower($data['controller_name']) == 'visitors' ? 'text-white':'text-teal' }}"></i>
                        {{trans('common.first_time_visitors_label')}}
                    </a>
                    @endif
                    @if($permissionService->checkModulePermission(11))
                    <a class="collapse-item {{ ((strtolower($data['category_name']) == 'joshua warriors') && (strtolower($data['controller_name']) == 'dashboard')) ? 'bg-teal text-white':'' }}" href="{{ route('dashboard.JW') }}">
                        <i class="fas fa-chart-line mr-1 {{ ((strtolower($data['category_name']) == 'joshua warriors') && (strtolower($data['controller_name']) == 'dashboard')) ? 'text-white':'text-teal' }}"></i>
                        {{trans('common.reports_label')}}
                    </a>
                    @endif
                </div>
            </div>
        </li>
    @endif

    @if($permissionService->checkCategoryPermission(config('constants.MODULE_CATEGORY_FINANCE')))
    <li class="nav-item ">
        <a class="nav-link"
           href="#" data-toggle="collapse" data-target="#collapseFinance" aria-expanded="true" aria-controls="collapseFinance">
            <i class="fas fa-dollar-sign {{strtolower($data['category_name']) === 'finance' ? 'text-warning': '' }}"></i>
            <span class="{{strtolower($data['category_name']) === 'finance' ? 'font-weight-bold text-white': '' }}">{{trans('common.finance_label')}}</span>
        </a>
        <div id="collapseFinance" class="collapse  {{ strtolower($data['category_name']) == 'finance'? 'show':'' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @if($permissionService->checkModulePermission(12))
                <a class="collapse-item {{ strtolower($data['controller_name']) == 'seeds' ? 'bg-teal text-white':'' }}" href="{{ route('seeds.index') }}">
                    <i class="fas fa-hand-holding-usd {{ strtolower($data['controller_name']) == 'seeds' ? 'text-white':'text-teal' }}"></i>
                    {{trans('common.seeds_label')}}
                </a>
                @endif
                @if($permissionService->checkModulePermission(13))
                <a class="collapse-item {{ strtolower($data['controller_name']) == 'offerings' ? 'bg-teal text-white':'' }}"
                   href="{{ route('offerings.index') }}">
                    <i class="fas fa-coins {{ strtolower($data['controller_name']) == 'offerings' ? 'text-white':'text-teal' }}"></i>
                    {{trans('common.collections_label')}}
                </a>
                @endif
                @if($permissionService->checkModulePermission(14))
                <a class="collapse-item {{ strtolower($data['controller_name']) == 'transactions' ? 'bg-teal text-white':'' }}"
                   href="{{ route('transactions.index') }}">
                    <i class="fas fa-exchange-alt {{ strtolower($data['controller_name']) == 'transactions' ? 'text-white':'text-teal' }}"></i>
                    {{trans('common.transactions_label')}}
                </a>
                @endif
{{--                @if($permissionService->checkModulePermission(21))--}}
{{--                    <a class="collapse-item {{ strtolower($data['controller_name']) == 'bankfiles' ? 'bg-teal text-white':'' }}"--}}
{{--                       href="{{ route('bankfiles.index') }}">--}}
{{--                        <i class="fa fa-piggy-bank {{ strtolower($data['controller_name']) == 'bankfiles' ? 'text-white':'text-teal' }}"></i>--}}
{{--                        {{trans('common.bank_files')}}--}}
{{--                    </a>--}}
{{--                @endif--}}
                @if($permissionService->checkModulePermission(15))
                <a class="collapse-item {{ strtolower($data['controller_name']) == 'sub accounts' ? 'bg-teal text-white':'' }}"
                   href="{{route('sub-accounts.index')}}">
                    <i class="fas fa-wallet {{ strtolower($data['controller_name']) == 'sub accounts' ? 'text-white':'text-teal' }}"></i>
                    {{trans('common.sub_accounts')}}
                </a>
                @endif
                @if($permissionService->checkModulePermission(16))
                <a class="collapse-item  {{ strtolower($data['controller_name']) == 'main accounts' ? 'bg-teal text-white':'' }}"
                   href="{{route('accounts.index')}}">
                    <i class="fas fa-piggy-bank {{ strtolower($data['controller_name']) == 'main accounts' ? 'text-white':'text-teal' }}"></i>
                    {{trans('common.main_accounts')}}
                </a>
                @endif
                @if($permissionService->checkModulePermission(17))
                <a class="collapse-item {{ strtolower($data['controller_name']) == 'budgets' ? 'bg-teal text-white':'' }}"
                   href="{{route('budgets.index')}}">
                    <i class="fas fa-list-alt {{ strtolower($data['controller_name']) == 'budgets' ? 'text-white':'text-teal' }}"></i>
                    {{trans('common.budgets_label')}}
                </a>
                @endif
                @if($permissionService->checkModulePermission(18))
                <a class="collapse-item {{ ((strtolower($data['action_name']) == 'reports') && (strtolower($data['controller_name']) == 'accounts')) ? 'bg-teal text-white':'' }}"
                   href="#">
                    <i class="fas fa-chart-line mr-1 text-teal"></i>
                    {{trans('common.reports_label')}}
                </a>
                @endif
            </div>
        </div>
    </li>
@endif

    <!-- Nav Item - Pages Collapse Menu -->

    @if($permissionService->checkModulePermission(19))
    <li class="nav-item ">
        <a class="nav-link {{ strtolower($data['controller_name']) == 'currency'? 'open':'collapsed' }}"
           href="#" data-toggle="collapse" data-target="#collapseEvents" aria-expanded="true" aria-controls="collapseEvents">
            <i class="fas fa-calendar-alt {{strtolower($data['controller_name']) === 'events' ? 'text-warning': '' }}"></i>
            <span class="{{strtolower($data['controller_name']) === 'events' ? 'font-weight-bold text-white': '' }}">{{trans('common.events_label')}}</span>
        </a>
        <div id="collapseEvents" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ ((strtolower($data['action_name']) == 'index') && (strtolower($data['controller_name']) == 'events')) ? 'bg-teal text-white':'' }}"
                   href="{{ route('events.index') }}">
                    All
                </a>
                <a class="collapse-item {{ ((strtolower($data['action_name']) == 'calendar') && (strtolower($data['controller_name']) == 'events')) ? 'bg-teal text-white':'' }}"
                   href="{{ route('events.calendar') }}">
                    <i class="fas fa-calendar-alt {{((strtolower($data['controller_name']) === 'events') && (strtolower($data['action_name']) === 'calendar') ) ? 'text-white': 'text-teal' }}"></i>
                    Calendar
                </a>
                <a class="collapse-item {{ ((strtolower($data['action_name']) == 'registration') && (strtolower($data['controller_name']) == 'events')) ? 'bg-teal text-white':'' }}"
                   href="{{ route('events.registration') }}">
                    <i class="fas fa-clipboard-list mr-1 {{ ((strtolower($data['action_name']) == 'registration') && (strtolower($data['controller_name']) == 'events')) ? 'text-white': 'text-teal' }}"></i>
                    {{trans('common.event_registration_label')}}
                </a>
            </div>
        </div>
    </li>
@endif

    <!-- Nav Item - Utilities Collapse Menu -->

    @if($permissionService->checkModulePermission(20))
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                <i class="fas fa-fw fa-cogs {{strtolower($data['category_name']) === 'config' ? 'text-warning': '' }} "></i>
                <span class="{{strtolower($data['category_name']) === 'config' ? 'font-weight-bold text-white': '' }}">{{trans('common.config_label')}}</span>
            </a>
            <div id="collapseUtilities" class="collapse {{strtolower($data['category_name']) === 'config' ? 'show': '' }}" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item  pt-1 pl-1 pr-1  pt-1 pb-1 {{ strtolower($data['controller_name']) === 'member types' ? 'bg-teal text-white':'' }}" href="{{ route('type.index') }}">
                        <i class="fas fa-users-cog mr-2 text-lg {{ strtolower($data['controller_name']) === 'member types' ? 'text-white':'text-teal' }}"></i>
                        {{trans('common.member_types_label')}}
                    </a>
                    <a class="collapse-item  pt-1 pl-1 pr-1  pt-1 pb-1 {{ strtolower($data['controller_name']) === 'districts' ? 'bg-teal text-white':'' }}" href="{{ route('district.index') }}">
                        <i class="fas fa-city mr-2 text-lg  {{ strtolower($data['controller_name']) === 'districts' ? 'text-white':'text-teal' }}"></i>
                        {{trans('common.districts_label')}}
                    </a>
                    <a class="collapse-item  pt-1 pl-1 pr-1  pt-1 pb-1 {{ strtolower($data['controller_name']) === 'currency' ? 'bg-teal text-white':'' }}" href="{{ route('currency.index') }}">
                        <i class="fas fa-dollar-sign mr-3 text-lg  {{ strtolower($data['controller_name']) === 'currency' ? 'text-white':'text-teal' }}"></i>
                        {{trans('common.currency_label')}}
                    </a>
                    <a class="d-flex flex-row collapse-item pt-1 pl-1 pr-1  pt-1 pb-1 {{ strtolower($data['controller_name']) === 'users' ? 'bg-teal text-white':'' }}" href="{{ route('users.index') }}">
                        <i class="fas fa-users-cog mr-2 text-lg  {{ strtolower($data['controller_name']) === 'users' ? 'text-white':'text-teal' }}"></i>
                        <span class="d-flex flex-wrap flex-row">{{trans('common.user_management_label')}}</span>
                    </a>
                </div>
            </div>
        </li>
    @endif

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
