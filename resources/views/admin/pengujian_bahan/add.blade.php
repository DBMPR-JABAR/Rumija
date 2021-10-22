@extends('admin.layout.index')

@section('title') Admin Teman Jabar
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('assets/css/progress_buble.css') }}" />
@endsection

@section('page-header')
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Pengujian Bahan LABKON</h4>
                    <span>Input Data Pengujian Bahan LABKON </span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ url('admin/pengujian_bahan/dashboard') }}">Pengujian Bahan
                            LABKON</a> </li>
                    <li class="breadcrumb-item"><a href="#">Tambah</a> </li>
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
                    <h5>Pendaftaran Pengujian Bahan LABKON</h5>
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                            <li><i class="feather icon-minus minimize-card"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="card-block pl-5 pr-5 pb-5">
                    <ul class="progress-indicator">
                        <li class="active">
                            <span class="bubble"></span> Pendaftaran
                        </li>
                        <li>
                            <span class="bubble"></span> Bahan Uji
                        </li>
                        <li>
                            <span class="bubble"></span> Pengkajian Permintaan Pengujian
                        </li>
                        <li>
                            <span class="bubble"></span> Pengujian
                        </li>
                        <li>
                            <span class="bubble"></span> Selesai
                        </li>
                    </ul>

                    <form action="{{ route('bahanUjiPengujianLabkon') }}" method="post">
                        @csrf
                        <h5 class="col-12 text-left pl-0 font-weight-bold">Perusahaan</h5>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Nama</label>
                            <div class="col-md-10">
                                <input name="nama_perusahaan" type="text" class="form-control" required
                                    placeholder="Masukan Nama Perusahaan">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Alamat</label>
                            <div class="col-md-10">
                                <input name="alamat_perusahaan" type="text" class="form-control" required
                                    placeholder="Masukan Alamat Perusahaan">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">No. Telp</label>
                            <div class="col-md-10">
                                <input name="telp_perusahaan" type="number" class="form-control" required
                                    placeholder="Masukan No. Telp Perushaan">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Email</label>
                            <div class="col-md-10">
                                <input name="email_perusahaan" type="email" class="form-control" required
                                    placeholder="Masukan Email Perushaan">
                            </div>
                        </div>
                        <h5 class="col-12 text-left pl-0 font-weight-bold">Penanggung Jawab</h5>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Nama</label>
                            <div class="col-md-10">
                                <input name="nama" type="text" class="form-control" required placeholder="Masukan Nama">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">NIK/NIP</label>
                            <div class="col-md-10">
                                <input name="telp" type="number" class="form-control" required
                                    placeholder="Masukan NIK/NIP">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Alamat</label>
                            <div class="col-md-10">
                                <input name="alamat" type="text" class="form-control" required placeholder="Masukan Alamat">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">No. Telp</label>
                            <div class="col-md-10">
                                <input name="telp" type="number" class="form-control" required
                                    placeholder="Masukan No. Telp">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Email</label>
                            <div class="col-md-10">
                                <input name="email" type="email" class="form-control" required placeholder="Masukan Email">
                            </div>
                        </div>
                        <div class=" form-group row">
                            <a href="{{ route('listPengujianLabKon') }}"><button type="button"
                                    class="btn btn-default waves-effect">Batal</button></a>
                            <button type="submit" class="btn btn-primary waves-effect waves-light ml-2">Lanjutkan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables.net/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables.net/js/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery/js/jquery.mask.js') }}"></script>

    <script>
    </script>
@endsection
