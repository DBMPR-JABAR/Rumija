@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Realisasi Anggaran Pendapatan & Belanja</h4>

            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Supervisi Pekerjaan</a> </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('page-body')
<div class="row">
    <!-- task, page, download counter  start -->

    <!-- task, page, download counter  end -->

    <!-- visitor start -->
    <div class="col-xl-12 col-md-12">
        <div class="card">
            <div class="card-header">

                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                <div id="chartdiv"></div>


                <table id="kontraktor" class="table table-striped table-bordered">
                    <thead>
                            <tr>
                                <th>No.</th>
                                <th>Uraian</th>
                                <th>Anggaran</th>
                                <th>Realiasi</th>

                                <th>%Serapan</th>
                                <th>Tahun</th>
                            </tr>
                        </thead>
                     <tbody>


                            <tr>
                                <td>1</td>
                                 <td>Pendapatan Asli Daerah</td>
                                 <td>1.500.000</td>
                                 <td>1.470.000</td>
                                 <td>93%</td>
                                 <td>2018</td>

                            </tr>
                            <tr>
                                <td>2</td>
                                 <td>Belanja Pegawai</td>
                                 <td>2.500.000</td>
                                 <td>2.370.000</td>
                                <td>91%</td>
                                <td>2018</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                 <td>Belanja Bunga</td>
                                 <td>2.600.000</td>
                                 <td>2.570.000</td>
                                <td>98%</td>
                                <td>2018</td>
                            </tr>


               </tbody>
               </table>



            </div>
        </div>
    </div>

     <!-- sale order start -->
</div>
@endsection

@section('script')
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<script>
    am4core.ready(function() {

    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end



    var chart = am4core.create('chartdiv', am4charts.XYChart)
    chart.colors.step = 2;

    chart.legend = new am4charts.Legend()
    chart.legend.position = 'top'
    chart.legend.paddingBottom = 20
    chart.legend.labels.template.maxWidth = 95

    var xAxis = chart.xAxes.push(new am4charts.CategoryAxis())
    xAxis.dataFields.category = 'category'
    xAxis.renderer.cellStartLocation = 0.1
    xAxis.renderer.cellEndLocation = 0.9
    xAxis.renderer.grid.template.location = 0;

    var yAxis = chart.yAxes.push(new am4charts.ValueAxis());
    yAxis.min = 0;

    function createSeries(value, name) {
        var series = chart.series.push(new am4charts.ColumnSeries())
        series.dataFields.valueY = value
        series.dataFields.categoryX = 'category'
        series.name = name

        series.events.on("hidden", arrangeColumns);
        series.events.on("shown", arrangeColumns);

        var bullet = series.bullets.push(new am4charts.LabelBullet())
        bullet.interactionsEnabled = false
        bullet.dy = 30;
        bullet.label.text = '{valueY}'
        bullet.label.fill = am4core.color('#ffffff')

        return series;
    }

    chart.data = [ {
            category: '2016',
            first: 1500,
            second: 1450,
            third: 60
        },
        {
            category: '2017',
            first: 1590,
            second: 1340,
            third: 60
        },
        {
            category: '2018',
            first: 2092,
            second: 1980,
            third: 69
        },
        {
            category: '2019',
            first: 2199,
            second: 2111,
            third: 45
        },
        {
            category: '2020',
            first: 2309,
            second: 2200,
            third: 22
        }
    ]


    createSeries('first', 'Anggaran');
    createSeries('second', 'Realisasi');

    function arrangeColumns() {

        var series = chart.series.getIndex(0);

        var w = 1 - xAxis.renderer.cellStartLocation - (1 - xAxis.renderer.cellEndLocation);
        if (series.dataItems.length > 1) {
            var x0 = xAxis.getX(series.dataItems.getIndex(0), "categoryX");
            var x1 = xAxis.getX(series.dataItems.getIndex(1), "categoryX");
            var delta = ((x1 - x0) / chart.series.length) * w;
            if (am4core.isNumber(delta)) {
                var middle = chart.series.length / 2;

                var newIndex = 0;
                chart.series.each(function(series) {
                    if (!series.isHidden && !series.isHiding) {
                        series.dummyData = newIndex;
                        newIndex++;
                    }
                    else {
                        series.dummyData = chart.series.indexOf(series);
                    }
                })
                var visibleCount = newIndex;
                var newMiddle = visibleCount / 2;

                chart.series.each(function(series) {
                    var trueIndex = chart.series.indexOf(series);
                    var newIndex = series.dummyData;

                    var dx = (newIndex - trueIndex + middle - newMiddle) * delta

                    series.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
                    series.bulletsContainer.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
                })
            }
        }
    }

    }); // end am4core.ready()
</script>
@endsection
