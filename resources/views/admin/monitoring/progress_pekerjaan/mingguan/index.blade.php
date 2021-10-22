@extends('admin.layout.index')

@section('title') Progress Mingguan @endsection

@section('head')
    <!-- Highchart -->
    <style>
        .highcharts-figure,
        .highcharts-data-table table {
            width: 100%;
            margin: 1em auto;
        }

        #containerChart {
            height: 600px;
            min-width: 100%;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #EBEBEB;
            margin: 10px 0;
            text-align: center;
            min-width: 100%;
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

        .highcharts-credits {
            display: none
        }
    </style>
    <!-- JSON Viewer -->
    <link href="https://cdn.jsdelivr.net/npm/jquery.json-viewer@1.4.0/json-viewer/jquery.json-viewer.css" type="text/css"
        rel="stylesheet">
@endsection

@section('page-header')
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Progress Mingguan</h4>
                    {{-- <span></span> --}}
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#!">Progress Mingguan</a> </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('page-body')
    <div class="row d-flex justify-content-center">
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
                                        <form id="formTahun" action="{{ route('getProgressMingguan') }}" method="get"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-sm-12 col-xl-3 m-b-30">
                                                    <h4 class="sub-title">Tahun</h4>
                                                    <select id="filterTahun" name="tahun"
                                                        class="form-control form-control-primary"
                                                        onchange="onChangeYearFilter()">
                                                        @for ($i = 2019; $i <= date('Y'); $i++)
                                                            <option value="{{ $i }}" @if ($i == $tahun) {{ 'selected' }} @endif>{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-sm-12 col-xl-9 m-b-30">
                                                    <h4 class="sub-title">Kegiatan</h4>
                                                    <select id="filterKegiatan" name="select"
                                                        class="form-control form-control-primary"
                                                        onchange="onChangeFilter(this)">
                                                    </select>
                                                </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-12">
        <div class="card p-2">
            {{-- <div class="row">
                    <div class="col-12"> --}}
            <figure class="highcharts-figure">
                <div id="containerChart"></div>
                <div class="highcharts-description">
                    <div class="row">
                        <div class="col-4">
                            <b style="font-weight: bolder">Rencana :</b> <span id="rencana">-<span>
                        </div>
                        <div class="col-4">
                            <b style="font-weight: bolder">Realisasi :</b> <span id="realisasi">-<span>
                        </div>
                        <div class="col-4">
                            <b style="font-weight: bolder">Deviasi :</b> <span id="deviasi">-<span>
                        </div>
                    </div>
                </div>
            </figure>
            {{-- </div> --}}
            {{-- <div class="col-6">
                        <pre id="weeklyProgress"></pre>
                    </div>
                    <div class="col-6">
                        <pre id="schedulePlan"></pre>
                    </div> --}}
            {{-- </div> --}}
        </div>
    </div>
    </div>

    <div class="modal-only">
        <div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">Detail Harian</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body row">
                        <div class="dt-responsive table-responsive">
                            <table id="detailTable" style="width: 100%" class="table  table-borderless"
                                style="overflow: scroll">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Uraian</th>
                                        <th>Volume</th>
                                        <th>Satuan</th>
                                    </tr>
                                </thead>
                                <tbody id="tableDetailBody"></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Highchart -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <!-- Json Viewer -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/jquery.json-viewer@1.4.0/json-viewer/jquery.json-viewer.js"></script> --}}
    <!-- Datatabble -->
    <script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables.net/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables.net/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/dataTables.responsive.min.js') }}"></script>
    <script>
        const addDays = (date, days) => {
            const result = new Date(date);
            result.setDate(result.getDate() + days);
            return result.toISOString().slice(0, 10);
        }

        const sortingByDate = (array, key) => {
            return array.sort((a, b) => {
                return Date(a[key]) > Date(b[key]) ? 1 : -1;
            })
        }

        const groupBy = (array, key) => {
            return array.reduce((result, currentValue) => {
                (result[currentValue[key]] = result[currentValue[key]] || []).push(
                    currentValue
                );
                return result;
            }, {});
        };

        const groupByWeek = (array, key) => {
            const resultsUnsorting = array.reduce((result, currentValue) => {
                const {
                    ['waktu_pelaksanaan']: remove, ...dataFilter
                } = currentValue;
                let d = new Date(currentValue[key]);
                d = Math.floor(d.getTime() / (1000 * 60 * 60 * 24 * 7));
                (result[d] = result[d] || []).push(
                    dataFilter
                );
                return result;
            }, {});
            const weeklyNumbers = Object.keys(resultsUnsorting);
            let resultsSorting = [];
            let weeklyNumbersTemp = 0;
            weeklyNumbers.forEach((weeklyNumber, index) => {
                const data = sortingByDate(resultsUnsorting[weeklyNumber], key)
                let volume = 0;
                let nilai = 0;
                data.forEach((item) => {
                    volume += Number(item.volume);
                    nilai += Number(item.nilai);
                })
                resultsSorting.push({
                    minggu_number: weeklyNumber,
                    minggu_ke: (index === 0) ? 1 : (resultsSorting[index - 1].minggu_ke + (Number(
                        weeklyNumber) - weeklyNumbersTemp)),
                    volume_migguan: volume,
                    nilai_mingguan: nilai,
                    data
                })
                weeklyNumbersTemp = Number(weeklyNumber);
            })
            return resultsSorting;
        };

        const dailyProgress = @json($progress_harian);
        const dailyProgressGroup = groupBy(dailyProgress, 'kegiatan');
        const dailyProgressName = Object.keys(dailyProgressGroup);
        const schedule = @json($jadwal);
        const scheduleGroup = groupBy(schedule, 'kegiatan');

        let schedulePlan = [];
        let htmlFilter = "";
        dailyProgressName.forEach((progress, index) => {
            htmlFilter += `<option value="${progress}" ${index == 0 ? 'selected' : ''}>${progress}</option>`
            let volume = 0;
            scheduleGroup[progress].forEach((progressPlan) => {
                volume += Number(progressPlan.volume)
            })
            schedulePlan.push({
                nama_kegiatan: progress,
                total_volume: volume
            })
        })
        $('#filterKegiatan').html(htmlFilter);
        let weeklyProgress = [];
        dailyProgressName.forEach((progress) => {
            const firstProgress = sortingByDate(dailyProgressGroup[progress], 'tgl')[0];
            const data = groupByWeek(dailyProgressGroup[progress], 'tgl');
            let volume = 0;
            let nilai = 0;
            let freeDays = 0;
            data.forEach((item) => {
                volume += item.volume_migguan;
                nilai += item.nilai_mingguan;
                freeDays += 2;
            })

            weeklyProgress.push({
                tanggal_awal: firstProgress.tgl,
                // tanggal_akhir: addDays(firstProgress.tgl, (firstProgress.waktu_pelaksanaan + freeDays)),
                tanggal_akhir: addDays(firstProgress.tgl, firstProgress.waktu_pelaksanaan),
                nama_kegiatan: progress,
                waktu_pelaksanaan: firstProgress.waktu_pelaksanaan,
                total_volume: volume,
                total_nilai: nilai,
                data_mingguan: data
            });
        })

        const renderCurvaS = ({
            activity
        }) => {
            let dataWeeklyProgress = [];
            let dataWeeklyCategories = [];
            let dataWeeklyTotalProgressTemp = [];
            let dataWeeklyTotalProgress = [];
            const weeklyProgressFilter = weeklyProgress.filter((progress) => {
                return progress.nama_kegiatan == activity;
            })
            const schedulePlanFilter = schedulePlan.filter((schedule) => {
                return schedule.nama_kegiatan == activity;
            })
            console.log(weeklyProgressFilter, schedulePlanFilter)
            document.getElementById('rencana').innerText = schedulePlanFilter[0].total_volume;
            document.getElementById('realisasi').innerText = weeklyProgressFilter[0].total_volume;
            document.getElementById('deviasi').innerText = weeklyProgressFilter[0].total_volume - schedulePlanFilter[0]
                .total_volume;

            weeklyProgressFilter[0].data_mingguan.forEach((progress, index) => {
                dataWeeklyProgress.push(progress.volume_migguan);
                dataWeeklyCategories.push(`Minggu ke-${progress.minggu_ke}`);
                let weeklyProgressPercent = (progress.volume_migguan / schedulePlanFilter[0].total_volume) *
                    100;

                index == 0 ?
                    dataWeeklyTotalProgress.push(weeklyProgressPercent) :
                    dataWeeklyTotalProgress.push(dataWeeklyTotalProgress[index - 1] + weeklyProgressPercent);
            })

            Highcharts.chart('containerChart', {
                chart: {
                    zoomType: 'xy',
                    events: {
                        click: function(event) {
                            const index = event.xAxis[0].axis.chart.hoverPoint.index;
                            const dayDetail = weeklyProgressFilter[0].data_mingguan[index];
                            let html = "";
                            dayDetail.data.forEach((item) => {
                                const months = [
                                    'Januari',
                                    'Februari',
                                    'Maret',
                                    'April',
                                    'Mei',
                                    'Juni',
                                    'Juli',
                                    'Agustus',
                                    'September',
                                    'Oktober',
                                    'November',
                                    'Desember'
                                ]
                                const days = [
                                    'Minggu',
                                    'Senin',
                                    'Selasa',
                                    'Rabu',
                                    'Kamis',
                                    'Jumat',
                                    'Sabtu'
                                ]
                                const d = new Date(item.tgl);
                                const year = d.getFullYear();
                                const date = d.getDate();
                                const monthIndex = d.getMonth()
                                const monthName = months[monthIndex]
                                const dayIndex = d.getDay()
                                const dayName = days[dayIndex]
                                const formatted = `${dayName}, ${date} ${monthName} ${year}`
                                html += `<tr>
                                                                                                                    <td>${formatted}</td>
                                                                                                                    <td>${item.uraian}</td>
                                                                                                                    <td>${item.volume}</td>
                                                                                                                    <td>${item.satuan}</td>
                                                                                                                    </tr>`
                            });
                            $('#tableDetailBody').html(html);
                            $('#detailModal').modal('show');
                        }
                    }
                },
                title: {
                    text: weeklyProgressFilter[0].nama_kegiatan
                },
                // subtitle: {
                //     text: 'Testing Nama Paket'
                // },
                xAxis: [{
                    categories: dataWeeklyCategories,
                    crosshair: true
                }],
                yAxis: [{
                    labels: {
                        format: '{value} %',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    title: {
                        text: 'Persentase Progress',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    }
                }, {
                    title: {
                        text: 'Total Volume Mingguan',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    labels: {
                        format: '{value}',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    opposite: true
                }],
                tooltip: {
                    shared: true
                },
                legend: {
                    layout: 'vertical',
                    align: 'left',
                    x: 120,
                    verticalAlign: 'top',
                    y: 100,
                    floating: true,
                    backgroundColor: Highcharts.defaultOptions.legend.backgroundColor ||
                        'rgba(255,255,255,0.25)'
                },
                series: [{
                    name: 'Total Volume Mingguan',
                    type: 'column',
                    yAxis: 1,
                    data: dataWeeklyProgress,

                }, {
                    name: 'Persentase Progress',
                    type: 'spline',
                    data: dataWeeklyTotalProgress,
                    tooltip: {
                        valueSuffix: ' %'
                    }
                }]
            });
        }

        const onChangeFilter = (it) => {
            renderCurvaS({
                activity: it.value
            });
        }

        const onChangeYearFilter = () => {
            document.getElementById('formTahun').submit();
        }

        $(document).ready(() => {
            renderCurvaS({
                activity: $('#filterKegiatan').val()
            });
        })

    </script>
@endsection
