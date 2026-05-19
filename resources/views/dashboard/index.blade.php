@extends('layout.admin')

@section('content')
    <div class="row">
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="rounded shadow h-100 p-1" style="background-color: #faf9f9">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="font-weight-bold text-primary mb-1">{{trans('common.total_seeds_this_month_label')}}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$<span id="seeds_info">0.00</span></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding-usd fa-2x text-primary "></i>
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
                            <div class="font-weight-bold text-success mb-1">{{trans('common.total_active_members_label')}}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><span id="member_count">0</span></div>
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
                            <div class="font-weight-bold text-info mb-1">{{trans('common.total_converts_this_month_label')}}</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"> <span id="convert_count">0</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-baby-carriage fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="rounded shadow h-100 p-1" style="background-color: #faf9f9">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="font-weight-bold text-warning mb-1">{{trans('common.total_offerings_this_month_label')}}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$<span id="offering_info">0.00</span></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coins fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">{{trans('common.earning_overview_label')}}</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">{{trans('common.revenue_sources_label')}}</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                    <span class="mr-2 text-sm-left">
                      <i class="fas fa-circle text-primary"></i>
                        {{trans('common.tides_label')}}:
                        <span id="chart_total_tides"></span>
                    </span>
                    <span class="mr-2 text-sm-left">
                      <i class="fas fa-circle text-success"></i> {{trans('common.collections_label')}}:
                       <span id="chart_total_collections"></span>
                    </span>
                    <span class="mr-2 text-sm-left">
                      <i class="fas fa-circle text-info"></i> {{trans('common.seeds_label')}}:
                        <span id="chart_total_seed"></span>
                    </span>
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
{{--    <script src="{{ asset('js/demo/chart-area-demo.js')}}"></script>--}}
    <script src="{{ asset('vendor/currency/currency.js')}}"></script>
{{--    <script src="{{ asset('js/demo/chart-pie-demo.js')}}"></script>--}}
    <script src="{{ asset('vendor/countup/countup.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            let seeds = new countUp.CountUp('seeds_info',{!! $data['total_tides'] !!}, {decimalPlaces:2});
            let offerings = new countUp.CountUp('offering_info',{!! $data['total_offerings'] !!}, {decimalPlaces:2});
            let memberCount = new countUp.CountUp('member_count',{!! $data['total_members'] !!});
            let convertCount = new countUp.CountUp('convert_count',{!!$data['total_converts']  !!});
            if (!seeds.error && !offerings.error) {
                seeds.start();
                offerings.start();
                memberCount.start();
                convertCount.start();
            } else {
                console.error(seeds.error);
            }

            $('#chart_total_collections').html( currency({!! $data['total_offerings']  !!}).format())
            $('#chart_total_seed').html( currency({!! $data['total_seeds'] !!}).format())
            $('#chart_total_tides').html( currency({!! $data['total_tides'] !!}).format())
            console.log( currency({!! $data['total_tides'] !!}).format())




            Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
            Chart.defaults.global.defaultFontColor = '#858796';
            const ctx1 = document.getElementById("myPieChart");
            const myPieChart = new Chart(ctx1, {
                type: 'doughnut',
                data: {
                    labels: [
                        @foreach($data['income_sources'] as $source)
                            @if($loop->last)
                                '{!! $source !!}'
                            @else
                                '{!! $source !!}',
                            @endif
                        @endforeach
                    ],
                    datasets: [{
                        data: [
                            currency({!! $data['total_tides']  !!}),
                            currency('{!! $data['total_offerings']  !!}'),
                            currency('{!! $data['total_seeds']  !!}')
                        ],
                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                        hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    },
                    legend: {
                        display: true
                    },
                    cutoutPercentage: 70,
                },
            });

            const ctx2 = document.getElementById("myAreaChart");
            const myLineChart = new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: [
                        @foreach($data['months'] as $month)
                            @if($loop->last)
                            '{!! $month !!}'
                        @else
                            '{!! $month !!}',
                        @endif
                        @endforeach
                    ],
                    datasets: [{
                        label: "{{trans('common.tides_label')}}",
                        lineTension: 0.3,
                        backgroundColor: "rgba(78, 115, 223, 0.05)",
                        borderColor: "rgba(78, 115, 223, 1)",
                        pointRadius: 3,
                        pointBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointBorderColor: "rgba(78, 115, 223, 1)",
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: [
                            @foreach($data['tides_chart_data'] as $tide)
                                @if($loop->last)
                                '{!! $tide['amount'] !!}'
                            @else
                                '{!! $tide['amount'] !!}',
                            @endif
                            @endforeach
                        ],
                    },
                        {
                        label: "Other Seeds",
                        lineTension: 0.3,
                        backgroundColor: "rgba(78, 115, 223, 0.05)",
                        borderColor: "rgb(204,42,219)",
                        pointRadius: 3,
                        pointBackgroundColor: "rgb(204,42,219)",
                        pointBorderColor: "rgb(204,42,219)",
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: "rgb(204,42,219)",
                        pointHoverBorderColor: "rgb(204,42,219)",
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: [
                            @foreach($data['seed_chart_data'] as $seed)
                                @if($loop->last)
                                '{!! $seed['amount'] !!}'
                            @else
                                '{!! $seed['amount'] !!}',
                            @endif
                            @endforeach
                        ],
                    },
                        {
                        label: "{{trans('common.collections_label')}}",
                        lineTension: 0.3,
                        backgroundColor: "rgba(78, 115, 223, 0.05)",
                        borderColor: "rgba(10,193,4,0.8)",
                        pointRadius: 3,
                        pointBackgroundColor: "rgba(10,193,4,0.8)",
                        pointBorderColor: "rgba(10,193,4,0.8)",
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: "rgba(10,193,4,0.8)",
                        pointHoverBorderColor: "rgba(10,193,4,0.8)",
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: [
                            @foreach($data['collection_chart_data'] as $chart)
                                @if($loop->last)
                                '{!! $chart['amount'] !!}'
                            @else
                                '{!! $chart['amount'] !!}',
                            @endif
                            @endforeach
                        ],
                    }

                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 25,
                            top: 25,
                            bottom: 0
                        }
                    },
                    scales: {
                        xAxes: [{
                            time: {
                                unit: 'date'
                            },
                            gridLines: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 7
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                maxTicksLimit: 5,
                                padding: 10,
                                // Include a dollar sign in the ticks
                                callback: function(value, index, values) {
                                    return '$' + number_format(value);
                                }
                            },
                            gridLines: {
                                color: "rgb(234, 236, 244)",
                                zeroLineColor: "rgb(234, 236, 244)",
                                drawBorder: false,
                                borderDash: [2],
                                zeroLineBorderDash: [2]
                            }
                        }],
                    },
                    legend: {
                        display: false
                    },
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        titleMarginBottom: 10,
                        titleFontColor: '#6e707e',
                        titleFontSize: 14,
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        intersect: false,
                        mode: 'index',
                        caretPadding: 10,
                        callbacks: {
                            label: function(tooltipItem, chart) {
                                var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
                            }
                        }
                    }
                }
            });


        })

        function number_format(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(',', '').replace(' ', '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }
    </script>

@endsection
