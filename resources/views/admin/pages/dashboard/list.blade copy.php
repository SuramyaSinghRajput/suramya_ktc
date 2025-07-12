@extends('admin.layout.default')

@section('dashboardmaster','active menu-item-open')
@section('content')
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-3 pb-0">
        <div class="card-title">
            <h3 class="card-label">Dashboard
            </h3>
        </div>

        <form action="" method="get" class="w-100" style="position: absolute;    right: 0;    top: 10px;">
            <div class="row pl-0 pr-0">


                <div class=" col-lg-12 text-right">
                    <div class="dataTables_length">
                        <input type="text" name="fromtodate" id="fromtodate" class="" placeholder="From Date" autocomplete="off" value="" style="opacity:0; width:0;position:absolute;right:20%">
                        <button type="button" class="btn" onclick="$('#fromtodate').click(),setSubmitAtt()"><i class="icon-2x text-dark-50 ki ki-calendar "></i></button>
                        <button type="submit" class="btn btn-success btn-sm d-none" id="Filter_ME" data-toggle="tooltip" title="Apply Filter">Filter</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="row">
        </div>
    </div>
</div>

@endsection

{{-- Styles Section --}}
@section('styles')
@endsection


{{-- Scripts Section --}}
@section('scripts')
<script src="{{url('/')}}/public/js/apexcharts.js?v=7.2.9"></script>
<script>
    function setSubmitAtt() {
        $('.applyBtn').attr('onclick', "submitDateForm()");
        $('.daterangepicker .ranges ul li').not(':last').attr('onclick', "submitDateForm()");
    }

    function submitDateForm() {
        setTimeout(function() {
            $('#Filter_ME').click();

        }, 200);
    }
    var _demo1 = function(dataArray) {
        dataArray = JSON.parse(dataArray);
        const apexChart = "#chart_1";
        var options = {
            series: [{
                name: "Orders",
                // data: [10, 41, 35, 51, 49, 62, 69, 91, 148, 148, 148, 148]
                data: dataArray
            }],
            chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'straight'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            },
            colors: [primary]
        };

        var chart = new ApexCharts(document.querySelector(apexChart), options);
        chart.render();
    }
    _demo1('<?php if (count($ordersMonthWiseData) > 0) {
                echo json_encode($ordersMonthWiseData);
            } ?>');




            $(document).ready(function() {
$(".applyBtn").click(function () {
    $(".daterangepicker .ranges ul li").removeClass("active");
    // $(".tab").addClass("active"); // instead of this do the below
    $(this).addClass("active");
});
});
</script>
@endsection
