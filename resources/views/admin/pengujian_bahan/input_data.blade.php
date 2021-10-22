@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection
@section('head')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/vendor/datatables.net/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/vendor/datatables.net/css/buttons.dataTables.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/vendor/data-table/extensions/responsive/css/responsive.dataTables.css') }}" />
@endsection
@section('page-header')
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Input Data Pengujian Bahan LABKON</h4>
                    {{-- <span>lorem ipsum dolor sit amet, consectetur adipisicing elit</span> --}}
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#!">Input Data Pengujian Bahan</a> </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('page-body')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('addPengujianLabkon') }}">
                        <h5 class="btn btn-primary text-white">Pengajuan Baru</h5>
                    </a>
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            <li>
                                <i class="feather icon-minus minimize-card"></i>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-block p-b-0">

                    <div class="table-responsive">
                        <table id="tabel_pengujian" class="table table-hover m-b-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Pengujian</th>
                                    <th>Penanggung Jawab</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tabel_pengujian_body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables.net/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables.net/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/responsive.bootstrap4.min.js') }}">
    </script>
    <script src="{{ asset('assets/js/labkon_.js') }}"></script>
@endsection
