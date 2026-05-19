<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Church Admin</title>
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <link href="{{asset('/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
    <link href="{{asset('/css/loader.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="{{asset('css/sb-admin-2.css')}}" rel="stylesheet">
    @include('shared.totalCSS')
    @yield('custom_css')
    <style>
        table > thead > tr > th {

            font-weight: normal;
        }
    </style>
    <script type="text/javascript">
        let javascript_trans = {!! json_encode(trans('javascript')) !!};
        let datatableTrans = {
            processing:     javascript_trans.loading_label,
            search:         javascript_trans.search_label,
            lengthMenu:     javascript_trans.select_showing_label,
            info:           javascript_trans.showing_label,
            infoEmpty:      javascript_trans.showing_zero_records_label,
            infoPostFix:    "",
            zeroRecords:    javascript_trans.zero_records_label,
            emptyTable:     javascript_trans.zero_records_label,
            paginate: {
                first:      javascript_trans.first_label,
                previous:   javascript_trans.previous_label,
                next:       javascript_trans.next_label,
                last:       javascript_trans.last_label
            },
            aria: {
                sortAscending:  `:${javascript_trans.sort_ascend_label}`,
                sortDescending: `:${javascript_trans.sort_descend_label}`
            }
        }
        let datePickerTran = {
            format: "MM/DD/YYYY",
            separator: " - ",
            applyLabel: javascript_trans.apply,
            cancelLabel: javascript_trans.cancel,
            fromLabel: javascript_trans.from,
            toLabel: javascript_trans.to,
            customRangeLabel: javascript_trans.custom,
            weekLabel: "W",
            daysOfWeek: [
                javascript_trans.Su,
                javascript_trans.Mo,
                javascript_trans.Tu,
                javascript_trans.We,
                javascript_trans.Th,
                javascript_trans.Fr,
                javascript_trans.Sa
            ],
            monthNames: [
                javascript_trans.january,
                javascript_trans.february,
                javascript_trans.march,
                javascript_trans.april,
                javascript_trans.may,
                javascript_trans.june,
                javascript_trans.july,
                javascript_trans.august,
                javascript_trans.september,
                javascript_trans.october,
                javascript_trans.november,
                javascript_trans.december
            ],
            firstDay: 1
        }
    </script>
</head>
<body id="page-top" style="background-color: #888889">

<!-- Page Wrapper -->
<div id="wrapper">
    <!-- Sidebar -->

    @include('shared.side_nav')
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            @include('shared.top_navbar')
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">
{{--                @include('shared.message')--}}

                @yield('content')

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        @include('shared.footer')
        <!-- End of Footer -->
    </div>
    <!-- End of Content Wrapper -->
</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
@include('shared.logout_confirm_modal')
@include('shared.change_lang_modal')
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
<script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>
<script src="{{asset('js/sb-admin-2.min.js')}}"></script>
<script src="{{asset('vendor/chart.js/Chart.min.js')}}"></script>
<!-- Page level custom scripts -->
@include('shared.totalJS')
@yield('custom_js')
</body>

</html>
