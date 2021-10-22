@extends('admin.layout.index')

@section('title') Rincian Grant access Role Aplikasi @endsection
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
    <div class="col-lg-6">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Rincian Grant access Role Aplikasi</h4>

            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('getRoleAkses') }}">Role Akses</a> </li>

                <li class="breadcrumb-item"><a href="#!">Rincian Grant access Role Aplikasi</a> </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('page-body')
<div class="row">
<div class="col-xl-12 col-md-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{Str::title($alldata['role'])}}</h4>

        </div>
        <div class="card-block">
                <div class="modal-body p-2">
                    <div class="form-group row">
                        <label class="col-md-2">Role Akses</label>
                        <div class="col-md-10">
                           @foreach ($alldata['menu'] as $item)
                           <button class="button btn-sm btn-success mb-1 mr-1">
                               {{$item}}
                           </button>
                           @endforeach
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2">UPTD Access</label>
                        <div class="col-md-10">
                            @foreach ($alldata['uptd_akses'] as $item)
                           <button class="button btn-sm btn-success mb-1 mr-1">
                               UPTD {{$item}}
                           </button>
                           @endforeach
                        </div>
                    </div>
                </div>
                <a href="{{ url()->previous() }}"><button type="button" class="btn btn-danger waves-effect " data-dismiss="modal">Kembali</button></a>

        </div>
    </div>
</div>
</div>


@endsection
@section('script')

@endsection
