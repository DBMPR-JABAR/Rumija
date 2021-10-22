@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection
<link rel="stylesheet" type="text/css" href="{{ asset('assets\vendor\datatables.net-bs4\css\dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets\vendor\data-table\css\buttons.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets\vendor\datatables.net-responsive-bs4\css\responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets\vendor\data-table\extensions\responsive\css\responsive.dataTables.css') }}">
   <style>
     table.table-bordered tbody td {
    word-break: break-word;
    vertical-align: top;
}
     </style>
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
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Proyek Kontrak</a> </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('page-body')







<div class="row">
<div class="col-lg-12">
    <div class="card">
        <div class="card-block accordion-block">
            <div id="accordion" role="tablist" aria-multiselectable="true">
                <div class="accordion-panel">
                    <div class="accordion-heading" role="tab" id="headingOne">
                        <h3 class="card-title accordion-title">
                            <a class="accordion-msg" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Filter
                            </a>
                        </h3>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                        <div class="accordion-content accordion-desc">
                            <div class="card-block">
                                <div class="row">
                                    <div class="col-sm-12 col-xl-3 m-b-30">
                                        <h4 class="sub-title">Tahun</h4>
                                        <select id="filterTahun" name="tahun" class="form-control form-control-primary">
                                            <option value="">-</option>
                                            <option value="2019">2019</option>
                                            <option value="2020">2020</option>
                                            <option value="2021">2021</option>
                                            <option value="2022">2022</option>
                                            <option value="2023">2023</option>
                                            <option value="2024">2024</option>
                                            <option value="2025">2025</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-xl-3 m-b-30">
                                        <h4 class="sub-title">UPTD</h4>
                                        <select id="filterUPTD" name="select" class="form-control form-control-primary">
                                            <option value="">Semua</option>
                                            <option value="uptd1">UPTD 1</option>
                                            <option value="uptd2">UPTD 2 </option>
                                            <option value="uptd3">UPTD 3 </option>
                                            <option value="uptd4">UPTD 4 </option>
                                            <option value="uptd5">UPTD 5 </option>
                                            <option value="uptd6">UPTD 6 </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-xl-3 m-b-30">
                                        <h4 class="sub-title">Kegiatan</h4>
                                        <select id="filterKegiatan" name="select" class="form-control form-control-primary">
                                            <option value="">Semua</option>
                                            <option value="pembangunan">Pembangunan</option>
                                            <option value="peningkatan">Peningkatan</option>
                                            <option value="rehabilitasi">Rehabilitasi</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



    <!-- task, page, download counter  start -->
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col-8"><a href="{{url('admin/monitoring/proyek-kontrak/status/CRITICAL CONTACT')}}">
                        <h4 class="text-c-yellow f-w-600">{{$countCritical}}</h4></a>
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
                    <div class="col-8"><a href="{{url('admin/monitoring/proyek-kontrak/status/ON PROGRESS')}}">
                        <h4 class="text-c-green f-w-600">{{ $countOnProgress }}</h4> </a>
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
                    <div class="col-8"><a href="{{url('admin/monitoring/proyek-kontrak/status/OFF PROGRESS')}}">
                        <h4 class="text-c-pink f-w-600">{{$countOffProgress}}</h4></a>
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
                        <h4 class="text-c-blue f-w-600">{{$countFinish}}</h4>
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
    <div class="col-xl-12 col-md-12">
        <div class="card">

        <div class="card-header">
                <h5>Rencana Dan Realisasi Proyek Kontrak</h5>

                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
        </div>
          <div class="card-block">
          <div id="container" ></div>

          </div>
        </div>
      </div>
{{--
      <div class="col-xl-12 col-md-12">
        <div class="card">

            <div class="card-header">
                <h5>Daftar Penyelesaian Pekerjaan Kontraktor</h5>

                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                            <li><i class="feather icon-maximize full-card"></i></li>
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

                          <div class="card-block">
                            <div class="table-responsive dt-responsive">
                              <table id="proyekContract" style="width:100%;font-size:12px" class="table table-striped table-bordered ">
                                <thead>
                                    <tr>
                                        <th style="width:5%">No.</th>
                                        <th>Tanggal</th>
                                        <th style="width:10px">Nama Paket</th>
                                        <th>Penyedia Jasa</th>
                                        <th>Kategori</th>
                                        <th style="width:5%">Jenis Pekerjaan</th>
                                        <th>Ruas Jalan</th>
                                        <th>Lokasi</th>
                                        <th>Rencana</th>
                                        <th>Realisasi</th>
                                        <th>Deviasi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $number = 1?>
                                @foreach ($listProjectContract as $data)
                                        <tr>
                                        <td>{{$number++}}</td>
                                        <td>{{ $data->TANGGAL }}</td>
                                        <td style="width:10px">{{$data->NAMA_PAKET}} </th>

                                        <td>{{$data->PENYEDIA_JASA}} </th>
                                            <td><b>{{$data->KEGIATAN}}</b></td>
                                            <td>{{$data->JENIS_PEKERJAAN}}</td>
                                            <td>{{$data->RUAS_JALAN}}</td>
                                            <td>{{$data->LOKASI}}</td>
                                            <td>{{$data->RENCANA}}</td>
                                            <td>{{$data->REALISASI}}</td>
                                            <td>{{$data->DEVIASI}}</td>
                                            <td></td>
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
                                        <th>Tgl Kontrak</th>
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
      </div> --}}

    </div>
    <!-- visitor start -->

     <!-- sale order start -->
</div>
@endsection

@section('script')
<script src="https://code.highcharts.com/gantt/highcharts-gantt.js"></script>
<script src="https://code.highcharts.com/gantt/modules/exporting.js"></script>
<script>

    let series = <?php echo $proyekKontrak; ?>
		 //var today = new Date(),
  var today = new Date(<?php echo $today; ?>),
    day = 1000 * 60 * 60 * 24,
    // Utility functions
    dateFormat = Highcharts.dateFormat,
    defined = Highcharts.defined,
    isObject = Highcharts.isObject,
    reduce = Highcharts.reduce;

// Set to 00:00:00:000 today
today.setUTCHours(0);
today.setUTCMinutes(0);
today.setUTCSeconds(0);
today.setUTCMilliseconds(0);
today = today.getTime();
Highcharts.ganttChart('container', {
    navigator: {
        enabled: true,
        liveRedraw: true,
        series: {
            type: 'gantt',
            pointPlacement: 0.5,
            pointPadding: 0.25
        },
        yAxis: {
            min: 0,
            max: 3,
            reversed: true,
            categories: []
        }
    },
    scrollbar: {
        enabled: true
    },
    rangeSelector: {
        enabled: true,
        selected: 0
    },
    series: [ ],
    tooltip: {
        pointFormatter: function () {

            var point = this,
                format = '%Y-%m-%d',
                options = point.options,
                completed = options.completed,
                amount = isObject(completed) ? completed.amount : completed,
                //status = ((amount || 0) * 100) + '%',
                status = ((amount || 0) * 100) + '%',
                lines;

            lines = [{
                value: point.name,
                style: 'font-weight: bold;'
            }, {
                title: 'Start',
                value: dateFormat(format, point.start)
            }, {
                visible: !options.milestone,
                title: 'End',
                value: dateFormat(format, point.end)
            }, {
                title: 'Completed',
                value: status
            }, {
                title: 'Owner',
                value: options.owner || 'unassigned'
            }];

            return reduce(lines, function (str, line) {
                var s = '',
                    style = (
                        defined(line.style) ? line.style : 'font-size: 0.8em;'
                    );
                if (line.visible !== false) {
                    s = (
                        '<span style="' + style + '">' +
                        (defined(line.title) ? line.title + ': ' : '') +
                        (defined(line.value) ? line.value : '') +
                        '</span><br/>'
                    );
                }
                return str + s;
            }, '');
        }
    },
    title: {
        text: ' '
    },
    xAxis: {
        currentDateIndicator: true,
        // min: today - 3 * day,
        // max: today + 110 * day
    }
});
</script>


@endsection
