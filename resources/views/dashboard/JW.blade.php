@extends('layout.admin')

@section('content')
    <div class="row">
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="rounded shadow h-100 p-1" style="background-color: #faf9f9">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="font-weight-bold text-primary mb-1">{{trans('common.num_youth_members_label')}}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['total_jw_members'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-child fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="rounded shadow h-100 p-1" style="background-color: #faf9f9">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="font-weight-bold text-success mb-1">{{trans('common.num_youth_members_in_groups_label')}}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['total_members_in_group'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="rounded shadow h-100 p-1" style="background-color: #faf9f9">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="font-weight-bold text-info mb-1">{{trans('common.visitors_this_week_label')}}</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $data['new_people_this_week'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-plus fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row ">

        <!-- Area Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header border-bottom-0 bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary" {{trans('common.visitors_overview_label')}}</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area h-100">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Area Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header border-bottom-0 bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">{{trans('common.absent_member_per_group_label')}}</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area h-100">
                        <canvas id="myAreaChart2"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_css')

@endsection

@section('custom_js')
    <script src="{{ asset('vendor/chart.js/Chart.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            const visitorChartCtx = document.getElementById("myAreaChart");
            const attendanceCharCtx = document.getElementById("myAreaChart2");
            let visitorChartData = {
                labels: [@foreach($data['bar_chart_data'] as $chart)
                    @if($loop->last)
                    '{!! $chart['month'] !!}'
                    @else
                        '{!! $chart['month'] !!}',
                    @endif
                    @endforeach],
                datasets: [{
                    label: '{{trans('common.first_time_visitors_label')}}',
                    maxBarThickness: 40,
                    backgroundColor: "rgb(78,115,223)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    borderWidth: 1,
                    data: [
                        @foreach($data['bar_chart_data'] as $chart)
                            @if($loop->last)
                            '{!! $chart['amount'] !!}'
                        @else
                            '{!! $chart['amount'] !!}',
                        @endif
                        @endforeach
                    ]
                }
                ]

            }
            let attendanceChartData = {
                labels: [@foreach($data['eagle_data'] as $group)
                    @if($loop->last)
                    '{!! $group['name'] !!}'
                    @else
                        '{!! $group['name'] !!}',
                    @endif
                    @endforeach],
                datasets: [{
                    label: '{{trans('common.absent_member_label')}}',
                    maxBarThickness: 40,
                    backgroundColor: "rgb(78,115,223)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    borderWidth: 1,
                    minBarLength: 2,
                    data: [
                        @foreach($data['eagle_data'] as $group)
                            @if($loop->last)
                            '{!! $group['absent'] !!}'
                        @else
                            '{!! $group['absent'] !!}',
                        @endif
                        @endforeach
                    ]
                }
                ]

            }
            const myBar = new Chart(visitorChartCtx, {
                type: 'bar',
                data: visitorChartData,
                options: {
                    responsive: true,
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: false,
                        text: 'Chart.js Bar Chart'
                    },
                    scales: {
                        yAxes: [{
                            display: true,
                            ticks: {
                                min: 0
                            }
                        }],
                    }
                }
            });
            const myBar2 = new Chart(attendanceCharCtx, {
                type: 'bar',
                data: attendanceChartData,
                options: {
                    responsive: true,
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: false,
                        text: 'Chart.js Bar Chart'
                    },
                    scales: {
                        yAxes: [{
                            display: true,
                            ticks: {
                                min: 0
                            }
                        }],
                    }
                }
            });
        })
    </script>
@endsection
