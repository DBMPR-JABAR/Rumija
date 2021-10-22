@extends('admin.layout.index')

@section('title') Rekomendasi Sumber Daya @endsection

@section('head')
    <!-- Highchart -->
    <link rel="stylesheet" href="https://github.com/downloads/lafeber/world-flags-sprite/flags16.css" />
    <style>
        #containerChart {
            height: 400px;
        }

        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 100%;
            max-width: 100%;
            margin: 1em 0 auto auto;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #EBEBEB;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 100%;
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

        .ld-label {
            width: 100%;
            display: inline-block;
        }

        .ld-url-input {
            width: 100%;
        }

        .ld-time-input {
            width: 100%;
        }

        .dataTables_filter {
            text-align: right
        }

        .highcharts-credits {
            display: none
        }

    </style>
@endsection

@section('page-header')
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Rekomendasi Sumber Daya</h4>
                    {{-- <span></span> --}}
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-kontraktor"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#!">Rekomendasi Sumber Daya</a> </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('page-body')
    <div class="row d-flex justify-content-center">
        <div class="col-12">
            <div class="card p-3">
                <ul class="nav nav-tabs md-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active show" data-toggle="tab" href="#kontraktor" role="tab"
                            aria-selected="true">Kontraktor</a>
                        <div class="slide"></div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#konsultan" role="tab"
                            aria-selected="false">Konsultan</a>
                        <div class="slide"></div>
                    </li>
                </ul>

                <div class="tab-content card-block">
                    <div class="tab-pane active show" id="kontraktor" role="tabpanel">
                        <figure class="highcharts-figure" style="max-width: 100%; width:100%">
                            <div id="containerKontraktor" class="containerChart"></div>
                            {{-- <p class="highcharts-description">
                                Daftar Kontraktor terbaik berdasarkan kinerja
                            </p> --}}
                        </figure>
                        <div class="dt-responsive table-responsive">
                            <table id="tabelKontraktor" class="table table-striped table-bordered table-sm"
                                style="max-width: 100%; width: 100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Nilai</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tabelBodyKontraktor">
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="tab-pane" id="konsultan" role="tabpanel">
                        <figure class="highcharts-figure">
                            <div id="containerKonsultan" class="containerChart"></div>
                            {{-- <p class="highcharts-description">
                                Daftar Konsultan terbaik berdasarkan kinerja
                            </p> --}}
                        </figure>
                        <div class="dt-responsive table-responsive">
                            <table id="tabelKonsultan" class="table table-striped table-bordered table-sm"
                                style="max-width: 100%; width: 100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Nilai</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tabelBodyKonsultan"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Highchart -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables.net/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables.net/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script>
        const data = {
            kontraktor: [{
                    no: "1",
                    nama: "LIKATAMA - MANGGALA KSO.",
                    nilai: "95",
                    nilai_kata: "Sangat Baik"
                },
                {
                    no: "2",
                    nama: "CV. ALAM MEKAR",
                    nilai: "90",
                    nilai_kata: "Sangat Baik"
                },
                {
                    no: "3",
                    nama: "PT. SARANA SEJA IBADAH KSO",
                    nilai: "85",
                    nilai_kata: "Sangat Baik"
                },
                {
                    no: "4",
                    nama: "PT. AMBER HASYA",
                    nilai: "80",
                    nilai_kata: "Baik"
                },
                {
                    no: "5",
                    nama: "PT. BERKAH BUMI CIHERANG",
                    nilai: "75",
                    nilai_kata: "Baik"
                },
                {
                    no: "6",
                    nama: "CV. AREMCO",
                    nilai: "70",
                    nilai_kata: "Sedang"
                },
                {
                    no: "7",
                    nama: "PT .BINA INFRA",
                    nilai: "65",
                    nilai_kata: "Sedang"
                },
                {
                    no: "8",
                    nama: "PT. SEECONS",
                    nilai: "60",
                    nilai_kata: "Cukup"
                },
                {
                    no: "9",
                    nama: "PT. PURI DIMENSI",
                    nilai: "55",
                    nilai_kata: "Cukup"
                },
                {
                    no: "10",
                    nama: "PT. SEECONS",
                    nilai: "50",
                    nilai_kata: "Kurang"
                },
                {
                    no: "11",
                    nama: "PT. PURI DIMENSI",
                    nilai: "45",
                    nilai_kata: "Kurang"
                },
                {
                    no: "12",
                    nama: "PT .BINA INFRA",
                    nilai: "40",
                    nilai_kata: "Kurang"
                }
            ],
            konsultan: [{
                    no: "1",
                    nama: "PT. SECON DWITUNGGAL PUTRA",
                    nilai: "87",
                    nilai_kata: "Sangat Baik"
                }, {
                    no: "2",
                    nama: "PT.NUANSA GALAXY",
                    nilai: "86",
                    nilai_kata: "Sangat Baik"
                },
                {
                    no: "3",
                    nama: "PT. WIN SOLUTION KONSUTAN",
                    nilai: "77",
                    nilai_kata: "Baik"
                },
                {
                    no: "4",
                    nama: "PT. GARIS PUTIH SEJAJAR",
                    nilai: "75",
                    nilai_kata: "Baik"
                },
                {
                    no: "5",
                    nama: "PT. EZZY ANUGRAH KSO",
                    nilai: "75",
                    nilai_kata: "Baik"
                },
                {
                    no: "6",
                    nama: "PT WINSOLUSI KONSULTAN",
                    nilai: "60",
                    nilai_kata: "Cukup"
                },
                {
                    no: "7",
                    nama: "PT GARIS PUTIH SEJAJAR",
                    nilai: "56",
                    nilai_kata: "Cukup"
                },
                {
                    no: "8",
                    nama: "PT ANUGRAH EZZY PERKASA (KSO)",
                    nilai: "55",
                    nilai_kata: "Cukup"
                },
                {
                    no: "9",
                    nama: "PT. SEECONS , PT. PURI DIMENSI",
                    nilai: "40",
                    nilai_kata: "Kurang"
                }
            ]
        }

        let tabelKontraktor, tabelKonsultan;
        const setTable = ({
            tableId,
            bodyId,
            data
        }) => {
            let html = "";
            data.forEach(item => {
                html +=
                    `<tr>
                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td>${item.no}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td>${item.nama}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td>${item.nilai}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td class="justify-content-center"><button style="margin: auto auto" data-toggle="tooltip"
                                                                                                                                                                                                                                                                                                                                                                                                                                                                        title="Detail" class="btn btn-success btn-sm waves-effect waves-light"><i
                                                                                                                                                                                                                                                                                                                                                                                                                                                                            class="icofont icofont-eye"></i>Detail</button></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                        </tr>`;
            });
            $(`#${bodyId}`).html(html);
            return $(`#${tableId}`).DataTable();
        }
        const setChart = ({
            containerId,
            title,
            data
        }) => {
            const categories = [];
            const sangatBaik = [],
                baik = [],
                sedang = [],
                cukup = [],
                kurang = [];
            const length = data.length < 10 ? data.length : 10;
            for (i = 0; i < length; i++) {
                categories.push(`${data[i].nama} ${data[i].no}`)
                const nilai = Number(data[i].nilai);
                switch (data[i].nilai_kata) {
                    case 'Sangat Baik': {
                        sangatBaik.push(nilai);
                        baik.push(0);
                        sedang.push(0);
                        cukup.push(0);
                        kurang.push(0)
                        break;
                    }
                    case 'Baik': {
                        sangatBaik.push(0);
                        baik.push(nilai);
                        sedang.push(0);
                        cukup.push(0);
                        kurang.push(0)
                        break;
                    }
                    case 'Sedang': {
                        sangatBaik.push(0);
                        baik.push(0);
                        sedang.push(nilai);
                        cukup.push(0);
                        kurang.push(0)
                        break;
                    }
                    case 'Cukup': {
                        sangatBaik.push(0);
                        baik.push(0);
                        sedang.push(0);
                        cukup.push(nilai);
                        kurang.push(0)
                        break;
                    }
                    case 'Kurang': {
                        sangatBaik.push(0);
                        baik.push(0);
                        sedang.push(0);
                        cukup.push(0);
                        kurang.push(nilai)
                        break;
                    }
                }
            }

            // console.log(baik, sedang, sangatBaik, kurang, cukup)
            Highcharts.chart(containerId, {
                colors: ['#269900', '#99c800', '#fff000', '#ff7900', '#ff0800'].reverse(),
                chart: {
                    type: 'column',
                    inverted: true,
                    polar: true
                },
                title: {
                    text: title
                },
                tooltip: {
                    outside: true
                },
                pane: {
                    size: '85%',
                    innerSize: '20%',
                    endAngle: 270
                },
                xAxis: {
                    tickInterval: 1,
                    labels: {
                        align: 'right',
                        useHTML: true,
                        allowOverlap: true,
                        step: 1,
                        y: 3,
                        style: {
                            fontSize: '13px'
                        }
                    },
                    lineWidth: 0,
                    categories
                },
                yAxis: {
                    crosshair: {
                        enabled: true,
                        color: '#333'
                    },
                    lineWidth: 0,
                    tickInterval: 25,
                    reversedStacks: false,
                    endOnTick: true,
                    showLastLabel: true
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                        borderWidth: 0,
                        pointPadding: 0,
                        groupPadding: 0.15
                    }
                },
                series: [{
                    name: 'Sangat Baik',
                    data: sangatBaik
                }, {
                    name: 'Baik',
                    data: baik
                }, {
                    name: 'Sedang',
                    data: sedang
                }, {
                    name: 'Cukup',
                    data: cukup
                }, {
                    name: 'Kurang',
                    data: kurang
                }].reverse()
            });
        }

        const dataFilterByPage = ({
            data,
            startIdx,
            endIdx
        }) => {
            let filter = [];
            for (i = startIdx; i < endIdx; i++) {
                filter.push(data[i])
            }
            return filter;
        }

        $(document).ready(() => {
            const tabelKontraktor = setTable({
                tableId: 'tabelKontraktor',
                bodyId: 'tabelBodyKontraktor',
                data: data.kontraktor
            });
            const tabelKonsultan = setTable({
                tableId: 'tabelKonsultan',
                bodyId: 'tabelBodyKonsultan',
                data: data.konsultan
            });

            tabelKontraktor.on('page.dt', () => {
                const info = tabelKontraktor.page.info();
                const dataFilter = dataFilterByPage({
                    data: data.kontraktor,
                    startIdx: info.start,
                    endIdx: info.end
                });

                setChart({
                    containerId: 'containerKontraktor',
                    title: 'Daftar Kontraktor Terbaik',
                    data: dataFilter
                });
            });

            tabelKonsultan.on('page.dt', () => {
                const info = tabelKonsultan.page.info();
                const dataFilter = dataFilterByPage({
                    data: data.konsultan,
                    startIdx: info.start,
                    endIdx: info.end
                });
                setChart({
                    containerId: 'containerKonsultan',
                    title: 'Daftar Konsultan Terbaik',
                    data: dataFilter
                });
            });
            setChart({
                containerId: 'containerKontraktor',
                title: 'Daftar Kontraktor Terbaik',
                data: data.kontraktor
            });
            setChart({
                containerId: 'containerKonsultan',
                title: 'Daftar Konsultan Terbaik',
                data: data.konsultan
            });
        })

    </script>
@endsection
