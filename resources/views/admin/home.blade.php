@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<style>
    .highcharts-credits {
        display: none !important
    }

    .highcharts-figure,
    .highcharts-data-table table {
        min-width: 310px;
        /* max-width: 800px; */
        margin: 1em auto;
        width: 100%
    }

    #container {
        height: 400px;
        width: 100%
    }

    .highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #EBEBEB;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        /* max-width: 500px; */
    }

    .highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }

    .highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }

    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
        padding: 0.5em;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }

    .highcharts-data-table tr:hover {
        background: #f1f7ff;
    }

    .jstree-anchor {
        font-size: 8pt;
    }
</style>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.3.1/echarts.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts@5.3.1/dist/echarts.min.js"></script>
@endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Selamat Datang di Dashboard SYSTARUMIJA</h4>
                <span>SYSTEM INVENTARISASI RUANG MILIK JALAN</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Home</a> </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('page-body')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Pengawasan dan Pemanfaatan</h4>
                {{-- <span>lorem ipsum dolor sit amet, consectetur adipisicing elit</span> --}}
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li>
                        <li><i class="feather icon-minus minimize-card"></i></li> --}}
                    </ul>
                </div>
            </div>
            <div class="card-block">
                <div class="chart has-fixed-height" id="pie_basic" style="width: 800px; height: 600px;"></div>
    
                <div class="card-deck col-md-12">
                    {{-- <div class="card w-100">
                        
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="text-warning f-w-600">
                                        {{ @$total_report['not_complete'] }}
                                    </h4>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="feather icon-arrow-down f-28"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-warning">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <p class="text-white m-b-0">Not Completed</p>
                                </div>
                                <div class="col-3 text-right">
                                    <i class="feather icon-trending-up text-white f-16"></i>
                                </div>
                            </div>
                        </div>
                    </div> --}}
    
                    <div class="card w-100">
                        <a href="{{ url('admin/input-data/rumija/rumija') }}" target="_blank">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="text-success f-w-600">
                                        {{ @$total_report['pemanfaatan'] }}
                                    </h4>
                                    {{-- <h6 class="text-muted m-b-0">Finish</h6> --}}
                                </div>
                                <div class="col-4 text-right">
                                    <i class="feather icon-arrow-down f-28"></i>
                                </div>
                            </div>
                        </div>
                        </a>
                        <div class="card-footer bg-success">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <p class="text-white m-b-0">Pemanfaatan</p>
                                </div>
                                <div class="col-3 text-right">
                                    <i class="feather icon-trending-up text-white f-16"></i>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="card w-100">
                        <a href="{{ url('admin/input-data/rumija/permohonan_rumija') }}" target="_blank">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="text-primary f-w-600">
                                        {{ @$total_report['permohonan'] }}
                                    </h4>
                                    {{-- <h6 class="text-muted m-b-0">Finish</h6> --}}
                                </div>
                                <div class="col-4 text-right">
                                    <i class="feather icon-arrow-up f-28"></i>
                                </div>
                            </div>
                        </div>
                        </a>
                        <div class="card-footer bg-primary">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <p class="text-white m-b-0">Permohonan</p>
                                </div>
                                <div class="col-3 text-right">
                                    <i class="feather icon-trending-up text-white f-16"></i>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="card w-100">
                        <a href="{{ route('admin.rumija.report.index') }}" target="_blank">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="text-danger f-w-600">
                                        {{ @$total_report['report'] }}
    
                                    </h4>
                                    {{-- <h6 class="text-muted m-b-0">Finish</h6> --}}
                                </div>
                                <div class="col-4 text-right">
                                    <i class="feather icon-clock f-28"></i>
                                </div>
                            </div>
                        </div>
                        </a>
                        <div class="card-footer bg-danger">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <p class="text-white m-b-0">Laporan</p>
                                </div>
                                <div class="col-3 text-right">
                                    <i class="feather icon-trending-up text-white f-16"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             
    
    
            </div>
        </div>
    </div>
    {{-- <div class="col-md-12 col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5>Data Inventaris Rumija</h5>
            </div>
            <div class="card-block">
                <div id="inventarisasi-tree">
                    <ul>
                        @foreach ($uptd as $key => $data)
                        <li>{{$data->nama}} : <span
                                class="font-weight-bold">{{$data->inventarisRumija->whereIn('rumija_inventarisasi_kategori_id',$inCategories)->count()}}</span>
                            <ul>
                                @foreach ($data->library_sup as $sup)
                                <li>{{$sup->name}} :
                                    <span
                                        class="font-weight-bold">{{$sup->inventarisRumija->whereIn('rumija_inventarisasi_kategori_id',$inCategories)->count()}}</span>
                                    <ul>
                                        @foreach ($sup->ruasJalan as $ruasJalan)
                                        <li>{{$ruasJalan->nama_ruas_jalan}} :
                                            <span
                                                class="font-weight-bold">{{$ruasJalan->inventarisRumija->whereIn('rumija_inventarisasi_kategori_id',$inCategories)->count()}}</span>
                                            <ul>
                                                @foreach ($categories as $kategori)
                                                <li data-jstree='{"icon":"{{$kategori->icon}}"}'>
                                                    {{$kategori->nama}} :
                                                    <span
                                                        class="font-weight-bold">{{$ruasJalan->inventarisRumija->where('rumija_inventarisasi_kategori_id',
                                                        $kategori->id)->count()}}</span>
                                                    <ul>
                                                        @foreach($ruasJalan->inventarisRumija->where('rumija_inventarisasi_kategori_id',
                                                        $kategori->id) as $inventaris)
                                                        <li data-jstree='{"icon":"{{$kategori->icon}}"}'
                                                            data-href="{{ route('rumija.inventarisasi.edit', $inventaris->id) }}">

                                                            {{$inventaris->lokasi}}
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                        @endforeach
                                    </ul>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="col-md-12 col-xl-12">
        <div class="card">
            <div class="card-header">
                <h5>Pengumuman</h5>
            </div>
            <div class="card-block">
                @foreach ($pengumuman_internal as $item)
                <div class="card w-100 mb-2">
                    <a href="{{ route('announcement.show', $item->slug) }}" target="_blank">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h6 class="card-title">{{ $item->title }}</h6>
                                    <span style="color :grey; font-size: 10px;"><i class='icofont icofont-user'></i>
                                        {{ $item->nama_user }}|| <i class='icofont icofont-time'></i>
                                        {{ Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</span>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="feather icon-arrow-down f-20"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
                {{ $pengumuman_internal->links() }}
                <span style="color :#ff0000; font-size: 10px;"><i class='icofont icofont-user'></i>

            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
<script type="text/javascript">
    const tree = $('#inventarisasi-tree').jstree({
        core : {
            themes : {
            variant : "small"
            }
        },
        plugins : [
        "search",
        "sort",
        ]
    });

    tree.on("select_node.jstree", function (e, data) {
        const href = data?.node?.data?.href
        if(href) window.open(href,'_blank');
     });

</script>
<script>
    var library_uptd = {!! json_encode($library_uptd) !!};
    var data_pemanfaatan = {!! json_encode($data_pemanfaatan) !!};
    var data_permohonan = {!! json_encode($data_permohonan) !!};
    var data_laporan = {!! json_encode($data_laporan) !!};

    var chartDom = document.getElementById('pie_basic');
    var myChart = echarts.init(chartDom);
    var option;
    
    option = {
        xAxis: {
            type: 'category',
            data: library_uptd
        },
        yAxis: [
            {
                type: 'value'
            }
        ],
        dataGroupId: '',
        animationDurationUpdate: 500,
        tooltip: {
            trigger: 'axis',
            // formatter: '{b}<br />{a0}: {c0} Km<br />{a1}: {c1} Km<br />{a2}: {c2} Km<br />{a3}: {c3} Km<br />{a4}: {c4} Km'
        },
        legend: {
            data: ['PEMANFAATAN', 'PERMOHONAN', 'LAPORAN'],
            selected: {
                
                PEMANFAATAN: true,
                PERMOHONAN: true,
                LAPORAN: true

            }
        },
        toolbox: {
            show: true,
            feature: {
            dataView: { show: false, readOnly: false },
            magicType: { show: true, type: ['line', 'bar'] },
            restore: { show: true },
            saveAsImage: { show: true }
            }
        },
        
        calculable: true,

        series: [
            {
                name: 'PEMANFAATAN',
                type: 'bar',
                id: 'sales',
                itemStyle: {color: '#28a745'},
                data: data_pemanfaatan,
                universalTransition: {
                    enabled: true,
                    divideShape: 'clone'
                }
            },
            {
                name: 'PERMOHONAN',
                type: 'bar',
                itemStyle: {color: '#002fff'},

                data: data_permohonan,
                universalTransition: {
                    enabled: true,
                    divideShape: 'clone'
                }
            },
            {
                name: 'LAPORAN',
                type: 'bar',
                itemStyle: {color: '#cc0101'},

                data: data_laporan,
                universalTransition: {
                    enabled: true,
                    divideShape: 'clone'
                }
            }
        ]
    };

    option && myChart.setOption(option);

</script>
@endsection
