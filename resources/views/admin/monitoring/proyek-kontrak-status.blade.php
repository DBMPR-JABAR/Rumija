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
                <h4>Kendali Kontrak per Status</h4>

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
                                <div class="dt-responsive table-responsive">
                                    <table id="dttable" class="table table-striped table-bordered able-responsive">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nomor Paket</th>
                                                <th>UPTD</th>
                                                <th>Nama Kegiatan</th>
                                                <th>Jenis Pekerjaan</th>
                                                <th>Penyedia Jasa</th>
                                                <th>Status Proyek</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bodyJembatan">
                                            @foreach ($getProyekDetail as $data)
                                            <tr>
                                                <td>{{$loop->index + 1}}</td>
                                                <td>{{$data->NO_PAKET}}</td>
                                                <td>{{$data->UPTD}}</td>
                                                <td>{{$data->NAMA_KEGIATAN}}</td>
                                                <td>{{$data->JENIS_PEKERJAAN}}</td>
                                                <td>{{$data->PENYEDIA_JASA}}</td>
                                                <td>{{$data->STATUS_PROYEK}}</td>
                                                <td><a href="{{route('detailProyekKontrakID',$data->ID)}}"><button data-toggle="tooltip" title="Rincian" class="btn btn-primary btn-sm waves-effect waves-light"><i class="icofont icofont-check-circled"></i></button></a></td>

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
