@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection
@section('page-header')
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Kendali Kontrak</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#!">Kendali Kontrak</a> </li>
                </ul>
            </div>
        </div>
    </div>
    <style>
        table.table-bordered tbody td {
            word-break: break-word;
            vertical-align: top;
        }

    </style>
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
                                    <a class="accordion-msg" data-toggle="collapse" data-parent="#accordion"
                                        href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Filter
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in show" role="tabpanel"
                                aria-labelledby="headingOne">
                                <div class="accordion-content accordion-desc">
                                    <div class="card-block">
                                        <div class="row">
                                            <div class="col-sm-12 col-xl-3 m-b-30">
                                                <h4 class="sub-title">Tahun</h4>
                                                <select id="filterTahun" name="tahun"
                                                    class="form-control form-control-primary">
                                                    <option value="" selected>-</option>
                                                    @for ($i = 2019; $i <= date('Y'); $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="col-sm-12 col-xl-3 m-b-30">
                                                <h4 class="sub-title">UPTD</h4>
                                                <select id="filterUPTD" name="select"
                                                    class="form-control form-control-primary">
                                                    @if (Auth::user()->internalRole->uptd)
                                                        <option value="{{ Auth::user()->internalRole->uptd }}" selected>UPTD
                                                            {{ str_replace('uptd', '', Auth::user()->internalRole->uptd) }}
                                                        </option>
                                                    @else
                                                        <option value="" selected>Semua</option>
                                                        <option value="">Dinas</option>
                                                        <option value="1">UPTD 1</option>
                                                        <option value="2">UPTD 2</option>
                                                        <option value="3">UPTD 3</option>
                                                        <option value="4">UPTD 4</option>
                                                        <option value="5">UPTD 5</option>
                                                        <option value="6">UPTD 6</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-sm-12 col-xl-3 m-b-30">
                                                <h4 class="sub-title">Kegiatan</h4>
                                                <select id="filterKegiatan" name="select"
                                                    class="form-control form-control-primary">
                                                    <option value="" selected>Semua</option>
                                                    <option value="pembangunan">Pembangunan</option>
                                                    <option value="peningkatan">Peningkatan</option>
                                                    <option value="rehabilitasi">Rehabilitasi</option>
                                                </select>
                                            </div>
                                            {{-- <div class="col-sm-12 col-xl-3 m-b-30">
                                        <h4 class="sub-title">Dari Tanggal</h4>
                                        <input type="date" id="filterDateFrom" name="dateFrom" class="form-control form-control-primary">
                                        </input>
                                    </div>
                                    <div class="col-sm-12 col-xl-3 m-b-30">
                                        <h4 class="sub-title">Ke Tanggal</h4>
                                        <input type="date" id="filterDateTo" name="dateTo" class="form-control form-control-primary">
                                        </input>
                                    </div> --}}
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
        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-block">
                    <div class="row align-items-center">
                        <div class="col-8"><a href="kendali-kontrak/status/CRITICAL CONTRACT">
                                <h4 class="text-c-yellow f-w-600">{{ $countCritical }}</h4>
                            </a>
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
        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-block">
                    <div class="row align-items-center">
                        <div class="col-8"><a href="kendali-kontrak/status/ON PROGRESS">
                                <h4 class="text-c-green f-w-600">{{ $countOnProgress }}</h4>
                            </a>
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
        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-block">
                    <div class="row align-items-center">
                        <div class="col-8"><a href="kendali-kontrak/status/FINISH">
                                <h4 class="text-c-blue f-w-600">{{ $countFinish }}</h4>
                            </a>
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
                    <h5>Rencana Dan Realisasi Kendali Kontrak</h5>

                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                            <li><i class="feather icon-minus minimize-card"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="card-block">
                    <div id="container"></div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script>
        const monthName = ['A', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
            'September', 'Oktober', 'November', 'Desember'
        ];

        function chart(data, uptd, tahun, kegiatan) {
            if (data.REALISASI.length > 0) {
                let rencana = [];
                data.RENCANA.forEach((val, i) => {
                    rencana.push({
                        y: val,
                        bulan: data.BULAN[i],
                        tahun: data.TAHUN[i]
                    });
                });

                let realisasi = [];
                data.REALISASI.forEach((val, i) => {
                    realisasi.push({
                        y: val,
                        bulan: data.BULAN[i],
                        tahun: data.TAHUN[i]
                    });
                });

                let month = [];
                data.BULAN.forEach((val, i) => {
                    month.push(monthName[val]);
                });


                let text = "Target dan Realisasi Fisik Kendali Kontrak ";
                text += (uptd != '') ? 'UPTD ' + uptd : '';
                text += (tahun != '') ? ' Tahun ' + tahun : ' ';
                text += (kegiatan != '') ? ' Kategori ' + kegiatan : ' ';
                Highcharts.chart('container', {
                    chart: {
                        type: 'column'
                    },
                    colors: ["#f7a35c", "#8085e9"],
                    title: {
                        text: text
                    },
                    xAxis: {
                        categories: month,
                        crosshair: true
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Persen (%)'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:20px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name} {point.tahun}: </td>' +
                            '<td style="padding:0"><b>{point.y:,.2f}%</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        },
                        series: {
                            cursor: 'pointer',
                            point: {
                                events: {
                                    click: function() {
                                        let url = "{{ route('monitoring-kontrak-progress') }}?bulan=" + this
                                            .options.bulan + "&tahun=" + this.options.tahun;
                                        url += (uptd != '') ? '&uptd=' + uptd : '';
                                        url += (kegiatan != '') ? '&kegiatan=' + kegiatan : '';
                                        location.href = url;
                                    }
                                }
                            }
                        }
                    },
                    series: [{
                        name: 'Rencana',
                        data: rencana
                    }, {
                        name: 'Realisasi',
                        data: realisasi
                    }]
                });
            } else {
                $("#container").html(`<h5 class="text-center"> Data Tidak Ada </h5>`);
            }

        }

        $(document).ready(function() {
            const baseUrl = "{{ url('') }}/map/kendali-kontrak";
            let tahun = $("#filterTahun").val();
            let uptd = $("#filterUPTD").val();
            let kegiatan = $("#filterKegiatan").val();

            Highcharts.setOptions({
                lang: {
                    decimalPoint: ',',
                    thousandsSep: '.'
                }
            });

            $.get(baseUrl, {
                    tahun: tahun,
                    uptd: uptd,
                    kegiatan: kegiatan
                },
                function(response) {
                    const data = response.data;
                    chart(data, uptd, tahun, kegiatan);
                });

            $("#filterTahun, #filterUPTD, #filterKegiatan").change(function() {
                tahun = $("#filterTahun").val();
                uptd = $("#filterUPTD").val();
                kegiatan = $("#filterKegiatan").val();

                $.get(baseUrl, {
                        tahun: tahun,
                        uptd: uptd,
                        kegiatan: kegiatan
                    },
                    function(response) {
                        const data = response.data;
                        chart(data, uptd, tahun, kegiatan);
                    });
            });
        });

    </script>
@endsection
