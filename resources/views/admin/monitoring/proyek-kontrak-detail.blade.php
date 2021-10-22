@extends('admin.layout.index')

@section('title') Rincian Kendali Kontrak @endsection
@section('head')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables.net/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables.net/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/data-table/extensions/responsive/css/responsive.dataTables.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/chosen_v1.8.7/chosen.css') }}">
<link rel="stylesheet" href="https://js.arcgis.com/4.17/esri/themes/light/main.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/list-scroll/list.css') }}">



<style>
.chosen-container.chosen-container-single {
    width: 300px !important; /* or any value that fits your needs */
}

    table.table-bordered tbody td {
        word-break: break-word;
        vertical-align: top;
    }
</style>
@endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Detail Kendali Kontrak </h4>

            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Kendali Kontrak</a> </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('page-body')
<div class="row">
    <div class="col-xl-12 col-md-12">
            <div class="card">
                <div class="card-block-big">
                    <!-- Tab panes -->
                     <div class="tab-content tabs card-block">
                            <div class="tab-pane active" id="Detail" role="tabpanel">
                                <p>No Paket : {{$getProyekDetail[0]->NMP}}</p>
                                <p>UPTD : {{$getProyekDetail[0]->UPTD}} </p>
                                <p>Nama Kegiatan : {{$getProyekDetail[0]->NAMA_KEGIATAN}} </p>
                                <p>Jenis Pekerjaan : {{$getProyekDetail[0]->JENIS_PEKERJAAN}} </p>
                                <p>Penyedia Jasa : {{$getProyekDetail[0]->PENYEDIA_JASA}} </p>
                                <p>Status Proyek : {{$getProyekDetail[0]->STATUS_PROYEK}}</p>
                                <div class="dt-responsive table-responsive">
                                    <table id="dttable" class="table table-striped table-bordered able-responsive">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Rencana Volume Harian</th>
                                                <th>Rencana Volume Kumulatif</th>
                                                <th>Realisasi Volume Harian</th>
                                                <th>Realisasi Volume Kumulatif</th>
                                                <th>Progress Fisik Rencana Bobot</th>
                                                <th>Progress Fisik Rencana Kumulatif</th>
                                                <th>Progress Fisik Realisasi Bobot</th>
                                                <th>Progress Fisik Realisasi Kumulatif</th>
                                                <th>Deviasi Progress Fisik</th>
                                                <th>Rencana Keuangan Harian</th>
                                                <th>Rencana Keuangan Komulatif</th>
                                                <th>Realisasi Keuangan Harian</th>
                                                <th>Realisasi Keuangan Harian1</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bodyJembatan">
                                            @foreach ($getProyekDetail as $data)
                                            <tr>
                                                <td>{{$loop->index + 1}}</td>
                                                <td>{{$data->DETAIL_TANGGAL}}</td>
                                                <td>{{number_format($data->RENCANA_VOLUME_HARIAN*100,2) . "%"}}</td>
                                                <td>{{number_format($data->RENCANA_VOLUME_KUMULATIF*100,2) ."%"}}</td>
                                                <td>{{number_format($data->REALISASI_VOLUME_HARIAN*100,2) ."%"}}</td>
                                                <td>{{number_format($data->REALISASI_VOLUME_KOMULATIF*100,2) ."%"}}</td>
                                                <td>{{number_format($data->PROGRESS_FISIK_RENCANA_BOBOT*100,2) ."%"}}</td>
                                                <td>{{number_format($data->PROGRESS_FISIK_RENCANA_KUMULATIF*100,2) ."%"}}</td>
                                                <td>{{number_format($data->PROGRESS_FISIK_REALISASI_BOBOT*100,2) ."%"}}</td>
                                                <td>{{number_format($data->PROGRESS_FISIK_REALISASI_KUMULATIF*100,2) ."%"}}</td>
                                                <td>{{number_format($data->DEVIASI_PROGRESS_FISIK*100,2) ."%"}}</td>
                                                <td>{{$data->RENCANA_KEUANGAN_HARIAN}}</td>
                                                <td>{{$data->RENCANA_KEUANGAN_KOMULATIF}}</td>
                                                <td>{{$data->REALISASI_KEUANGAN_HARIAN}}</td>
                                                <td>{{$data->REALISASI_KEUANGAN_HARIAN1}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                     </div>
                </div>
            </div>
    </div>
</div>
                                            <!-- statustic-card start -->




</div>


@endsection
@section('script')
<script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net/js/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/responsive.bootstrap4.min.js') }}"></script>

<script src="{{ asset('assets/vendor/list-scroll\list-custom.js') }}"></script>


<script>
    $(document).ready(function() {
        $("#dttable").DataTable();
    });
        </script>

@endsection
