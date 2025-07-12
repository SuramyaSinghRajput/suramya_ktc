@extends('admin.layout.default')

@section('dashboardmaster','active menu-item-open')
@section('content')
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-3 pb-0">
        <div class="card-title">
            <h3 class="card-label">Dashboard</h3>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <!-- You can place charts or stats here -->
            <div id="chart_1" class="w-100" style="height: 350px;"></div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<!-- You can add daterangepicker CSS here if needed -->
@endsection

@section('scripts')
<script src="{{ url('/') }}/public/js/apexcharts.js?v=7.2.9"></script>
<script>
    function setSubmitAtt() {
        setTimeout(() => {
            $('.applyBtn').attr('onclick', "submitDateForm()");
            $('.daterangepicker .ranges ul li:not(:last-child)').attr('onclick', "submitDateForm()");
        }, 500);
    }

    function submitDateForm() {
        setTimeout(function () {
            $('#Filter_ME').click();
        }, 200);
    }

    // Chart demo function
    function _demo1(dataArray) {
        if (!dataArray || dataArray.length === 0) return;

        const options = {
            series: [{
                name: "Orders",
                data: dataArray
            }],
            chart: {
                height: 350,
                type: 'line',
                zoom: { enabled: false }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'straight' },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'],
                    opacity: 0.5
                }
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            colors: ['#6993FF']
        };

        var chart = new ApexCharts(document.querySelector("#chart_1"), options);
        chart.render();
    }

    $(document).ready(function () {
        // Apply active class to custom date apply button
        $(document).on('click', '.applyBtn', function () {
            $(".daterangepicker .ranges ul li").removeClass("active");
            $(this).addClass("active");
        });

        // Initialize chart if data is available
        @if (!empty($ordersMonthWiseData) && count($ordersMonthWiseData) > 0)
            _demo1(@json($ordersMonthWiseData));
        @endif
    });
</script>
@endsection
