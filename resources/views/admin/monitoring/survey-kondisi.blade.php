@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Monitoring Survey Kondisi Jalan</h4>
             </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Survey Kondisi Jalan</a> </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('page-body')
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDBSpJ4v4aOY7DEg4QAIwcSFCXljmPJFUg&callback=initMap">
</script>
<script>
        // fungsi initialize untuk mempersiapkan peta
        function initialize() {
        var options = {
            center:new google.maps.LatLng(-6.9032739,107.5731165),
            zoom:8,
            mapTypeId:google.maps.MapTypeId.ROADMAP
        };
        var info = 'UPTD 1';

        var map = new google.maps.Map(document.getElementById("googleMap"), options);
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(-6.9147444,107.6098111), // longitude latitude
            map: map,
            title: 'Bandung'
        });
        var marker2 = new google.maps.Marker({
            position: new google.maps.LatLng(-6.9,107.6), // longitude latitude
            map: map,
            title: 'Bandung'
        });
        var marker3 = new google.maps.Marker({
            position: new google.maps.LatLng(-6.913244,107.666), // longitude latitude
            map: map,
            title: 'Bandung'
        });
        var marker4 = new google.maps.Marker({
            position: new google.maps.LatLng(-6.9147444,107.5098111), // longitude latitude
            map: map,
            title: 'Bandung'
        });
        var marker5 = new google.maps.Marker({
            position: new google.maps.LatLng(-6.8147444,107.6098111), // longitude latitude
            map: map,
            title: 'Bandung'
        });
        var maerker6 = new google.maps.Marker({
            position: new google.maps.LatLng(-7.0147444,107.6098111), // longitude latitude
            map: map,
            title: 'Bandung'
        });
        var infowindow = new google.maps.InfoWindow({
          content: info,
          position: marker
        });
        marker.addListener('click', function() {
          // tampilkan info window di atas marker
          infowindow.open(map, marker);
        });
        }
        // event jendela di-load
        google.maps.event.addDomListener(window, 'load', initialize);
</script>
<!--chart-->
<style>
#chartdiv {
  width: 100%;
  height: 500px;
  max-width: 100%;
}

#controls {
  overflow: hidden;
  padding-bottom: 3px;
}
</style>

<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/plugins/rangeSelector.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

<!-- Chart code -->
<script>
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart
var chart = am4core.create("chartdiv", am4charts.XYChart);
chart.padding(0, 15, 0, 15);
//chart data
/*chart.data = [ {
            date: '2014-08-08',
            open: 43.23,
            high: 43.32,
            low: 42.91,
            close: 43.20,
            volume: 28942700,
            adjclose: 43.20
        },
        {
            date: '2014-08-08',
            open: 43.23,
            high: 43.32,
            low: 42.91,
            close: 43.20,
            volume: 28942700,
            adjclose: 43.20
        },
        {
            date: '2014-08-08',
            open: 43.23,
            high: 43.32,
            low: 42.91,
            close: 43.20,
            volume: 28942700,
            adjclose: 43.20
        },
        {
            date: '2014-08-08',
            open: 43.23,
            high: 43.32,
            low: 42.91,
            close: 43.20,
            volume: 28942700,
            adjclose: 43.20
        },
        {
            date: '2014-08-08',
            open: 43.23,
            high: 43.32,
            low: 42.91,
            close: 43.20,
            volume: 28942700,
            adjclose: 43.20
        }
    ]*/
// Load external data
chart.dataSource.url = "https://www.amcharts.com/wp-content/uploads/assets/stock/MSFT.csv";
chart.dataSource.parser = new am4core.CSVParser();
chart.dataSource.parser.options.useColumnNames = true;
chart.dataSource.parser.options.reverse = true;

// the following line makes value axes to be arranged vertically.
chart.leftAxesContainer.layout = "vertical";

// uncomment this line if you want to change order of axes
//chart.bottomAxesContainer.reverseOrder = true;

var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
dateAxis.renderer.grid.template.location = 0;
dateAxis.renderer.ticks.template.length = 8;
dateAxis.renderer.ticks.template.strokeOpacity = 0.1;
dateAxis.renderer.grid.template.disabled = true;
dateAxis.renderer.ticks.template.disabled = false;
dateAxis.renderer.ticks.template.strokeOpacity = 0.2;
dateAxis.renderer.minLabelPosition = 0.01;
dateAxis.renderer.maxLabelPosition = 0.99;
dateAxis.keepSelection = true;
dateAxis.minHeight = 30;

dateAxis.groupData = true;
dateAxis.minZoomCount = 5;

// these two lines makes the axis to be initially zoomed-in
// dateAxis.start = 0.7;
// dateAxis.keepSelection = true;

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.tooltip.disabled = true;
valueAxis.zIndex = 1;
valueAxis.renderer.baseGrid.disabled = true;
// height of axis
valueAxis.height = am4core.percent(65);

valueAxis.renderer.gridContainer.background.fill = am4core.color("#000000");
valueAxis.renderer.gridContainer.background.fillOpacity = 0.05;
valueAxis.renderer.inside = true;
valueAxis.renderer.labels.template.verticalCenter = "bottom";
valueAxis.renderer.labels.template.padding(2, 2, 2, 2);

//valueAxis.renderer.maxLabelPosition = 0.95;
valueAxis.renderer.fontSize = "0.8em"

var series = chart.series.push(new am4charts.LineSeries());
series.dataFields.dateX = "Date";
series.dataFields.valueY = "Adj Close";
series.tooltipText = "{valueY.value}";
series.name = "MSFT: Value";
series.defaultState.transitionDuration = 0;

var valueAxis2 = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis2.tooltip.disabled = true;
// height of axis
valueAxis2.height = am4core.percent(35);
valueAxis2.zIndex = 3
// this makes gap between panels
valueAxis2.marginTop = 30;
valueAxis2.renderer.baseGrid.disabled = true;
valueAxis2.renderer.inside = true;
valueAxis2.renderer.labels.template.verticalCenter = "bottom";
valueAxis2.renderer.labels.template.padding(2, 2, 2, 2);
//valueAxis.renderer.maxLabelPosition = 0.95;
valueAxis2.renderer.fontSize = "0.8em"

valueAxis2.renderer.gridContainer.background.fill = am4core.color("#000000");
valueAxis2.renderer.gridContainer.background.fillOpacity = 0.05;

var series2 = chart.series.push(new am4charts.ColumnSeries());
series2.dataFields.dateX = "Date";
series2.dataFields.valueY = "Volume";
series2.yAxis = valueAxis2;
series2.tooltipText = "{valueY.value}";
series2.name = "MSFT: Volume";
// volume should be summed
series2.groupFields.valueY = "sum";
series2.defaultState.transitionDuration = 0;

chart.cursor = new am4charts.XYCursor();

var scrollbarX = new am4charts.XYChartScrollbar();
scrollbarX.series.push(series);
scrollbarX.marginBottom = 20;
scrollbarX.scrollbarChart.xAxes.getIndex(0).minHeight = undefined;
chart.scrollbarX = scrollbarX;


// Add range selector
var selector = new am4plugins_rangeSelector.DateAxisRangeSelector();
selector.container = document.getElementById("controls");
selector.axis = dateAxis;

}); // end am4core.ready()
</script>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <section id="our-services" class="pt-5 bglight">
                <div class="container">
                    <div class="row whitebox top15">

                        <div class="col-lg-12 col-md-12">
                            <div class="widget heading_space text-center text-md-left">

                                <div class="col-12 px-0">
                                    <div class="w-100">
                                    <div id="googleMap" style="width:100%;height:400px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            </div><div class="card">
            <div class="card-block">
                <div id="controls"></div>
                <div id="chartdiv"></div>
            </div>
    </div>
</div>
@endsection

@section('script')

@endsection
