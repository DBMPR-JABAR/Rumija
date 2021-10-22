@extends('admin.layout.index')

@section('title') Rincian User Role @endsection
@section('head')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables.net/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables.net/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/data-table/extensions/responsive/css/responsive.dataTables.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/chosen_v1.8.7/chosen.css') }}">
<link rel="stylesheet" href="https://js.arcgis.com/4.17/esri/themes/light/main.css">

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
                <h4>Rincian User Role</h4>

            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Rincian User Role</a> </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('page-body')
<div class="row">
<div class="col-xl-8 col-md-12">
    <div class="card">

        <div class="card-block-big">
            <ul class="nav nav-tabs  tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#Detail" role="tab">Detail</a>
                 </li>


            </ul>
                                                                <!-- Tab panes -->
            <div class="tab-content tabs card-block">
                <div class="tab-pane active" id="Detail" role="tabpanel">
                     <table style="padding:0;margin:0" class="table table-striped table-bordered nowrap dataTable">
                     @foreach ($detail as $data)
                     <tr><td>	No Pengaduan</td><td>{{$data->nomorPengaduan}}</td></tr>
                      <tr><td>	Nama</td><td>{{$data->nama}}</td></tr>
                      <tr><td>  NIK</td><td>{{$data->nik}}</td></tr>
                      <tr><td>  Alamat</td><td>{{$data->alamat}}</td></tr>
                      <tr><td>  Telepon</td><td>{{$data->telp}}</td></tr>
                      <tr><td>  Email</td><td>{{$data->email}}</td></tr>
                      <tr><td> Jenis</td><td>{{$data->jenis}}</td></tr>
                      <tr><td> Gambar</td><td><img src="{{ $data->gambar }}" class="img-fluid" alt=""></td></tr>
                      <tr><td> Lokasi</td><td>{{$data->lokasi}}</td></tr>
                      <tr><td> Lat</td><td>{{$data->lat}}</td></tr>
                      <tr><td> Long</td><td>{{$data->long}}</td></tr>
                      <tr><td> Deskripsi</td><td>{{$data->deskripsi}}</td></tr>
                      <tr><td> Status</td><td>{{$data->status}}</td></tr>
                      <tr><td> UPTD</td><td>UPTD {{$data->uptd_id}}</td></tr>
                      <tr><td> Created At</td><td>{{$data->created_at}}</td></tr>
                      <tr><td> Updated at</td><td>{{$data->updated_at}}</td></tr>
                    @endforeach
                     </table>
                 </div>

            </div>
        </div>

    </div>
</div>
</div>


@endsection
@section('script')

@endsection
