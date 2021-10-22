@extends('admin.layout.index')

@section('title') Rincian CCTV @endsection
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
                <h4>Rincian CCTV</h4>

            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Rincian CCTV</a> </li>
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
                     <table style="padding:0;margin:0" class="table table-striped table-bordered nowrap dataTable table-responsive row">
                      <tr><td class="col-4">Lokasi</td><td class="col-8">{{$cctv[0]->lokasi}}</td></tr>
                      <tr><td class="col-4">Lat</td><td class="col-8">{{$cctv[0]->lat}}</td></tr>
                      <tr><td class="col-4">Long</td><td class="col-8">{{$cctv[0]->long}}</td></tr>
                      <tr><td class="col-4">Url</td><td class="col-8">{{$cctv[0]->url}}</td></tr>
                      <tr><td class="col-4">Keterangan</td><td class="col-8">{{$cctv[0]->description}}</td></tr>
                      {{-- <tr><td class="col-4">Category</td><td class="col-8">{{$cctv[0]->category}}</td></tr>
                      <tr><td class="col-4">Status</td><td class="col-8">{{$cctv[0]->status}}</td></tr> --}}
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
