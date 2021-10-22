@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Monitoring Target & Realisasi Anggaran DBMPR Provinsi Jawa Barat</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Target & Realisasi Anggaran</a> </li>
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
                        <div id="collapseOne" class="panel-collapse collapse in show" role="tabpanel" aria-labelledby="headingOne">
                            <div class="accordion-content accordion-desc">
                                <div class="card-block">
                                    <div class="row">
                                        <div class="col-sm-12 col-xl-3 m-b-30">
                                            <h4 class="sub-title">Tahun</h4>
                                            <select id="filterTahun" name="tahun" class="form-control form-control-primary">
                                                @for ($i = 2019; $i <= date("Y"); $i++)
                                                <option value="{{$i}}" {{($i == date("Y")) ? 'selected' : ''}}>{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-xl-3 m-b-30">
                                            <h4 class="sub-title">UPTD</h4>
                                            <select id="filterUPTD" name="select" class="form-control form-control-primary">
                                                @if (Auth::user()->internalRole->uptd)
                                                <option value="{{Auth::user()->internalRole->uptd}}" selected>UPTD {{str_replace('uptd','',Auth::user()->internalRole->uptd)}}</option>
                                                @else
                                                <option value="uptd1" selected>UPTD 1</option>
                                                <option value="uptd2">UPTD 2</option>
                                                <option value="uptd3">UPTD 3</option>
                                                <option value="uptd4">UPTD 4</option>
                                                <option value="uptd5">UPTD 5</option>
                                                <option value="uptd6">UPTD 6</option>
                                                @endif
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
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Infografis Target Dan Realisasi Keuangan</h5>

                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                <div id="chartKeuangan"></div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Infografis Target Dan Realisasi Fisik</h5>

                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                <div id="chartFisik"></div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Infografis Deviasi</h5>

                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                <div id="chartDeviasi"></div>
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

    function chart(data, uptd, tahun){
        console.log(data.KEUANGAN);
        if(data.KEUANGAN){
            let realisasi = [];
            data.KEUANGAN.REALISASI_P.forEach((val,i) => {
                realisasi.push({y: val, amount: data.KEUANGAN.REALISASI[i]});
            });

            let target = [];
            data.KEUANGAN.TARGET_P.forEach((val,i) => {
                target.push({y: val, amount: data.KEUANGAN.TARGET[i]});
            });


            Highcharts.chart('chartKeuangan', {
                chart: {
                    type: 'column'
                },
                colors: ["#7cb5ec", "#90ed7d"],
                title: {
                    text: "Target dan Realisasi Keuangan UPTD "+(uptd.replace('uptd',''))+" Tahun "+tahun
                },
                xAxis: {
                    categories: [
                        'Jan',
                        'Feb',
                        'Mar',
                        'Apr',
                        'May',
                        'Jun',
                        'Jul',
                        'Aug',
                        'Sep',
                        'Oct',
                        'Nov',
                        'Dec'
                    ],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Persen (%)'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:,.2f}%</b></td></tr>' +
                        '<td style="padding:0"><b>{point.amount:,.0f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Target',
                    data: target
                }, {
                    name: 'Realisasi',
                    data: realisasi
                }]
            });
        }else{
            $("#chartKeuangan").html(`<h5 class="text-center"> Data Tidak Ada </h5>`);
        }

        if(data.FISIK){
            Highcharts.chart('chartFisik', {
                chart: {
                    type: 'column'
                },
                colors: ["#f7a35c", "#8085e9"],
                title: {
                    text: "Target dan Realisasi Fisik UPTD "+(uptd.replace('uptd',''))+" Tahun "+tahun
                },
                xAxis: {
                    categories: [
                        'Jan',
                        'Feb',
                        'Mar',
                        'Apr',
                        'May',
                        'Jun',
                        'Jul',
                        'Aug',
                        'Sep',
                        'Oct',
                        'Nov',
                        'Dec'
                    ],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Persen (%)'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:,.2f}%</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Target',
                    data: data.FISIK.TARGET
                }, {
                    name: 'Realisasi',
                    data: data.FISIK.REALISASI
                }]
            });
        }else{
            $("#chartFisik").html(`<h5 class="text-center"> Data Tidak Ada </h5>`);
        }

        if(data.DEVIASI){
            Highcharts.chart('chartDeviasi', {
                chart: {
                    type: 'column'
                },
                colors: ["#e4d354", "#2b908f"],
                title: {
                    text: "Deviasi Keuangan dan Fisik UPTD "+(uptd.replace('uptd',''))+" Tahun "+tahun
                },
                xAxis: {
                    categories: [
                        'Jan',
                        'Feb',
                        'Mar',
                        'Apr',
                        'May',
                        'Jun',
                        'Jul',
                        'Aug',
                        'Sep',
                        'Oct',
                        'Nov',
                        'Dec'
                    ],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Persen (%)'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:,.2f}%</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Keuangan',
                    data: data.DEVIASI.KEUANGAN
                }, {
                    name: 'Fisik',
                    data: data.DEVIASI.FISIK
                }]
            });
        }else{
            $("#chartDeviasi").html(`<h5 class="text-center"> Data Tidak Ada </h5>`);
        }
    }

    $(document).ready(function () {
        const baseUrl = "{{url('')}}/map/target-realisasi";
        let tahun = $("#filterTahun").val();
        let uptd = $("#filterUPTD").val();

        Highcharts.setOptions({
            lang: {
                decimalPoint: ',',
                thousandsSep: '.'
            }
        });

        $.get(baseUrl, { tahun: tahun, uptd: uptd},
            function(response){
                const data = response.data;
                chart(data, uptd, tahun);
            });

        $("#filterTahun, #filterUPTD").change(function () {
            tahun = $("#filterTahun").val();
            uptd = $("#filterUPTD").val();

            $.get(baseUrl, { tahun: tahun, uptd: uptd},
            function(response){
                const data = response.data;
                chart(data, uptd, tahun);
            });
        });
    });
</script>


@endsection
