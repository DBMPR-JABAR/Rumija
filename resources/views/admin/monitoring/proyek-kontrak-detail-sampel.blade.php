@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Proyek Kontrak</h4>

            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="index.html"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Proyek Kontrak</a> </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('page-body')
<div class="row">
    <!-- task, page, download counter  start -->
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="text-c-yellow f-w-600">20</h4>
                        <h6 class="text-muted m-b-0">Critical Contract</h6>
                    </div>
                    <div class="col-4 text-right">
                        <i class="feather icon-bar-chart f-28"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-c-yellow">
                <div class="row align-items-center">
                    <div class="col-9">
                        <p class="text-white m-b-0">% pekerjaan</p>
                    </div>
                    <div class="col-3 text-right">
                        <i class="feather icon-trending-up text-white f-16"></i>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="text-c-green f-w-600">40</h4>
                        <h6 class="text-muted m-b-0">On Progress</h6>
                    </div>
                    <div class="col-4 text-right">
                        <i class="feather icon-file-text f-28"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-c-green">
                <div class="row align-items-center">
                    <div class="col-9">
                        <p class="text-white m-b-0">% Pekerjaan</p>
                    </div>
                    <div class="col-3 text-right">
                        <i class="feather icon-trending-up text-white f-16"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="text-c-pink f-w-600">145</h4>
                        <h6 class="text-muted m-b-0">Off Progress</h6>
                    </div>
                    <div class="col-4 text-right">
                        <i class="feather icon-calendar f-28"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-c-pink">
                <div class="row align-items-center">
                    <div class="col-9">
                        <p class="text-white m-b-0">% pekerjaan</p>
                    </div>
                    <div class="col-3 text-right">
                        <i class="feather icon-trending-up text-white f-16"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="text-c-blue f-w-600">500</h4>
                        <h6 class="text-muted m-b-0">Finish</h6>
                    </div>
                    <div class="col-4 text-right">
                        <i class="feather icon-download f-28"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-c-blue">
                <div class="row align-items-center">
                    <div class="col-9">
                        <p class="text-white m-b-0">% Pekerjaan</p>
                    </div>
                    <div class="col-3 text-right">
                        <i class="feather icon-trending-up text-white f-16"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- task, page, download counter  end -->

    <!-- visitor start -->
    <div class="col-xl-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Daftar Penyelesaian Pekerjaan Kontraktor</h5>

                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">

                <ul class="nav nav-tabs md-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#home3" role="tab">Finish</a>
                        <div class="slide"></div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#profile3" role="tab">On Progress</a>
                        <div class="slide"></div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#messages3" role="tab">Crtitical Contract</a>
                        <div class="slide"></div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#settings3" role="tab">Off Progress</a>
                        <div class="slide"></div>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content card-block">
                    <div class="tab-pane active" id="home3" role="tabpanel">
                        <!-- chart -->
                        <div id="chartdiv" style="height:250px"></div>
                        <!--  -->
                        <div class="card" style="box-shadow: none;">
                          <div class="card-header">
                            <h5>Detail Daftar Pekerjaan</h5>
                            <ul class="filter-list mt-4">
                              <li>
                                <a href=""><button type="button" class="btn btn-primary">Semua <i class="feather icon-sliders"></i></button></a>
                              </li>
                              <li>
                                <a href=""><button type="button" class="btn btn-success">Pemeliharaan Berkala</button></a>
                              </li>
                              <li>
                                <a href=""><button type="button" class="btn btn-success">Pembangunan</button></a>
                              </li>
                              <li>
                                <a href=""><button type="button" class="btn btn-success">Peningkatan</button></a>
                              </li>
                            </ul>
                          </div>
                          <div class="card-block">
                            <div class="table-responsive dt-responsive">
                              <table id="detail" class="table table-striped table-bordered ">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Pekerja</th>
                                        <th>Kategori</th>
                                        <th>Tanggal</th>
                                        <th>Jenis Pekerjaan</th>
                                        <th>Ruas Jalan</th>
                                        <th>Lokasi</th>
                                        <th>Rencana</th>
                                        <th>Realisasi</th>
                                        <th>Deviasi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     @foreach ($proyekdetail as $data)
                                        <tr>
                                            <td>1</td>
                                            <td>{{$data->PENYEDIA_JASA}} </th>
                                            <td><b>{{$data->KEGIATAN}}</b></td>
                                            <td>{{$data->TANGGAL}}</td>
                                            <td>{{$data->JENIS_PEKERJAAN}}</td>
                                            <td>{{$data->RUAS_JALAN}}</td>
                                            <td>{{$data->LOKASI}}</td>
                                            <td>{{$data->RENCANA}}</td>
                                            <td>{{$data->REALISASI}}</td>
                                            <td>{{$data->DEVIASI}}</td>
                                            <td>{{{$data->STATUS}}}</b></td>
                                        </tr>
                                      @endforeach
                                  </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="profile3" role="tabpanel">
                        <!-- Chart -->
                        <div id="chartdivontrack" style="height:250px"></div>
                        <!--  -->
                        <div class="card" style="box-shadow: none;">
                          <div class="card-header">
                            <h5>Detail Daftar Pekerjaan</h5>
                            <ul class="filter-list mt-4">
                              <li>
                                <a href=""><button type="button" class="btn btn-primary">Semua <i class="feather icon-sliders"></i></button></a>
                              </li>
                              <li>
                                <a href=""><button type="button" class="btn btn-success">Pemeliharaan Berkala</button></a>
                              </li>
                              <li>
                                <a href=""><button type="button" class="btn btn-success">Pembangunan</button></a>
                              </li>
                              <li>
                                <a href=""><button type="button" class="btn btn-success">Peningkatan</button></a>
                              </li>
                            </ul>
                          </div>
                          <div class="card-block">
                            <div class="table-responsive dt-responsive">
                              <table id="detail" class="table table-striped table-bordered ">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Pekerja</th>
                                        <th>Kategori</th>
                                        <th>Tanggal</th>
                                        <th>Jenis Pekerjaan</th>
                                        <th>Ruas Jalan</th>
                                        <th>Lokasi</th>
                                        <th>Rencana</th>
                                        <th>Realisasi</th>
                                        <th>Deviasi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Kuli </th>
                                            <td><b>Pemeliharaan Berkala</b></td>
                                            <td>2019-10-20</td>
                                            <td>Hotmix</td>
                                            <td> Cibadak - Cikidang - Pelabuhan Ratu</td>
                                            <td>113+950 - 115+950</td>
                                            <td>37.3470%</td>
                                            <td>60.3160%</td>
                                            <td>22.9690%</td>
                                            <td><b class="text-success">On Progress</b></td>
                                        </tr>

                                        <tr>
                                            <td>2</td>
                                            <td>CDS Studio </th>
                                            <td><b>Pembangunan</b></td>
                                            <td>2020-01-01</td>
                                            <td>Hotmix</td>
                                            <td> Bts. Karawang/Purwakarta (Curug) - Purwakarta</td>
                                            <td>113+950 - 115+950</td>
                                            <td>37.3470%</td>
                                            <td>60.3160%</td>
                                            <td>22.9690%</td>
                                            <td><b class="text-success">On Progress</b></td>
                                        </tr>

                                        <tr>
                                            <td>3</td>
                                            <td>PT.Buana </th>
                                            <td><b>Peningkatan</b></td>
                                            <td>2019-09-25</td>
                                            <td>box culvert</td>
                                            <td> Waluran-Malereng-Palangpang</td>
                                            <td>113+950 - 115+950</td>
                                            <td>37.3470%</td>
                                            <td>60.3160%</td>
                                            <td>22.9690%</td>
                                            <td><b class="text-success">On Progress</b></td>
                                        </tr>

                                        <tr>
                                            <td>4</td>
                                            <td>Asakiwari </th>
                                            <td><b>Peningkatan</b></td>
                                            <td>2019-09-25</td>
                                            <td>box culvert</td>
                                            <td> Waluran-Malereng-Palangpang</td>
                                            <td>113+950 - 115+950</td>
                                            <td>37.3470%</td>
                                            <td>60.3160%</td>
                                            <td>22.9690%</td>
                                            <td><b class="text-success">On Progress</b></td>
                                        </tr>

                                        <tr>
                                            <td>5</td>
                                            <td>Lingatama </th>
                                            <td><b>Pembangunan</b></td>
                                            <td>2019-09-25</td>
                                            <td>box culvert</td>
                                            <td> Waluran-Malereng-Palangpang</td>
                                            <td>113+950 - 115+950</td>
                                            <td>37.3470%</td>
                                            <td>60.3160%</td>
                                            <td>22.9690%</td>
                                            <td><b class="text-success">On Progress</b></td>
                                        </tr>
                                  </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                     </div>
                    <div class="tab-pane" id="messages3" role="tabpanel">
                        <div id="chartdivalert" style="height:250px"></div>
                     </div>
                    <div class="tab-pane" id="settings3" role="tabpanel">
                        <div id="chartdivselesai" style="height:250px"></div>
                    </div>
                </div>




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

    // Create chart instance
    var chart = am4core.create("chartdiv", am4charts.XYChart);

    // Add data
    chart.data = [

      {
        "region": "OnTrack",
        "state": "Lingatama",
        "sales": 16
      },
      {
        "region": "OnTrack",
        "state": "Asakiwari",
        "sales": 10
      },
      {
        "region": "OnTrack",
        "state": "PT.Buana",
        "sales": 9
      },
      {
        "region": "OnTrack",
        "state": "CDS Studio",
        "sales": 22
      },

      {
        "region": "OnTrack",
        "state": "Kuli",
        "sales": 4
      }
    ];

    // Create axes
    var yAxis = chart.yAxes.push(new am4charts.CategoryAxis());
    yAxis.dataFields.category = "state";
    yAxis.renderer.grid.template.location = 0;
    yAxis.renderer.labels.template.fontSize = 10;
    yAxis.renderer.minGridDistance = 10;

    var xAxis = chart.xAxes.push(new am4charts.ValueAxis());

    // Create series
    var series = chart.series.push(new am4charts.ColumnSeries());
    series.dataFields.valueX = "sales";
    series.dataFields.categoryY = "state";
    series.columns.template.tooltipText = "{categoryY}: [bold]{valueX}[/]";
    series.columns.template.strokeWidth = 0;
    series.columns.template.adapter.add("fill", function(fill, target) {
      if (target.dataItem) {
        switch(target.dataItem.dataContext.region) {
          case "Selesai":
            return chart.colors.getIndex(0);
            break;
          case "Off Progress":
            return chart.colors.getIndex(1);
            break;
          case "Crtitical Contract":
            return chart.colors.getIndex(2);
            break;
          case "OnTrack":
            return "#2196f3";
            break;
        }
      }
      return fill;
    });

    var axisBreaks = {};
    var legendData = [];

    // Add ranges



    chart.cursor = new am4charts.XYCursor();


    var legend = new am4charts.Legend();
    legend.position = "right";
    legend.scrollable = true;
    legend.valign = "top";
    legend.reverseOrder = true;

    chart.legend = legend;
    legend.data = legendData;

    legend.itemContainers.template.events.on("toggled", function(event){
      var name = event.target.dataItem.dataContext.name;
      var axisBreak = axisBreaks[name];
      if(event.target.isActive){
        axisBreak.animate({property:"breakSize", to:0}, 1000, am4core.ease.cubicOut);
        yAxis.dataItems.each(function(dataItem){
          if(dataItem.dataContext.region == name){
            dataItem.hide(1000, 500);
          }
        })
        series.dataItems.each(function(dataItem){
          if(dataItem.dataContext.region == name){
            dataItem.hide(1000, 0, 0, ["valueX"]);
          }
        })
      }
      else{
        axisBreak.animate({property:"breakSize", to:1}, 1000, am4core.ease.cubicOut);
        yAxis.dataItems.each(function(dataItem){
          if(dataItem.dataContext.region == name){
            dataItem.show(1000);
          }
        })

        series.dataItems.each(function(dataItem){
          if(dataItem.dataContext.region == name){
            dataItem.show(1000, 0, ["valueX"]);
          }
        })
      }
    })

    }); // end am4core.ready()



















    var chart2 = am4core.create("chartdivontrack", am4charts.XYChart);

    // Add data
    chart2.data = [

      {
        "region": "OnTrack",
        "state": "Lingatama",
        "sales": 1
      },
      {
        "region": "OnTrack",
        "state": "Asakiwari",
        "sales": 3
      },
      {
        "region": "OnTrack",
        "state": "PT.Buana",
        "sales": 7
      },
      {
        "region": "OnTrack",
        "state": "CDS Studio",
        "sales": 2
      },

      {
        "region": "OnTrack",
        "state": "Kuli",
        "sales": 4
      }
    ];

    // Create axes
    var yAxis2 = chart2.yAxes.push(new am4charts.CategoryAxis());
    yAxis2.dataFields.category = "state";
    yAxis2.renderer.grid.template.location = 0;
    yAxis2.renderer.labels.template.fontSize = 10;
    yAxis2.renderer.minGridDistance = 10;

    var xAxis = chart2.xAxes.push(new am4charts.ValueAxis());

    // Create series
    var series = chart2.series.push(new am4charts.ColumnSeries());
    series.dataFields.valueX = "sales";
    series.dataFields.categoryY = "state";
    series.columns.template.tooltipText = "{categoryY}: [bold]{valueX}[/]";
    series.columns.template.strokeWidth = 0;
    series.columns.template.adapter.add("fill", function(fill, target) {
      if (target.dataItem) {
        switch(target.dataItem.dataContext.region) {
          case "Selesai":
            return chart2.colors.getIndex(0);
            break;
          case "Off Progress":
            return chart2.colors.getIndex(1);
            break;
          case "Crtitical Contract":
            return chart2.colors.getIndex(2);
            break;
          case "OnTrack":
            return "#0df3a3";
            break;
        }
      }
      return fill;
    });

    var axisBreaks = {};
    var legendData = [];

    // Add ranges



    chart.cursor = new am4charts.XYCursor();


    var legend = new am4charts.Legend();
    legend.position = "right";
    legend.scrollable = true;
    legend.valign = "top";
    legend.reverseOrder = true;

    chart.legend = legend;
    legend.data = legendData;

    legend.itemContainers.template.events.on("toggled", function(event){
      var name = event.target.dataItem.dataContext.name;
      var axisBreak = axisBreaks[name];
      if(event.target.isActive){
        axisBreak.animate({property:"breakSize", to:0}, 1000, am4core.ease.cubicOut);
        yAxis.dataItems.each(function(dataItem){
          if(dataItem.dataContext.region == name){
            dataItem.hide(1000, 500);
          }
        })
        series.dataItems.each(function(dataItem){
          if(dataItem.dataContext.region == name){
            dataItem.hide(1000, 0, 0, ["valueX"]);
          }
        })
      }
      else{
        axisBreak.animate({property:"breakSize", to:1}, 1000, am4core.ease.cubicOut);
        yAxis.dataItems.each(function(dataItem){
          if(dataItem.dataContext.region == name){
            dataItem.show(1000);
          }
        })

        series.dataItems.each(function(dataItem){
          if(dataItem.dataContext.region == name){
            dataItem.show(1000, 0, ["valueX"]);
          }
        })
      }
    });

</script>
@endsection
