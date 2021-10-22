@extends('admin.layout.index')

@section('title') Bahan Uji LabKon @endsection

@section('page-header')
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Bahan Uji LabKon</h4>
                    <span>Master Data Bahan Uji LabKon</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('bahan_uji_labkon.index') }}">Bahan Uji LabKon</a>
                    </li>
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
                    @if ($action == 'store')
                        <h5>Tambah Data Bahan Uji LabKon</h5>
                    @else
                        <h5>Perbaharui Data Bahan Uji LabKon</h5>
                    @endif
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                            <li><i class="feather icon-minus minimize-card"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="card-block pl-5 pr-5 pb-5">


                    @if ($action == 'store')
                        <form action="{{ route('bahan_uji_labkon.store') }}" method="post">
                        @else
                            <form action="{{ route('bahan_uji_labkon.update', $bahan_uji_labkon->id) }}" method="post">
                                @method('PUT')
                    @endif
                    @csrf

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Nama Bahan Uji LabKon</label>
                        <div class="col-md-9">
                            <div class="col-md-9">
                                <input name="nama" value="{{ @$bahan_uji_labkon->nama }}" type="text" class="form-control"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Status</label>
                        <div class="col-md-9 border-checkbox-section">
                            <div class="border-checkbox-group border-checkbox-group-primary p-2 ml-2">
                                <input class="border-checkbox" type="checkbox" name="status" id="status" {{ @$bahan_uji_labkon->status == 'aktif' ? 'checked' : '' }}>
                                <label class="border-checkbox-label" for="status">Aktif</label>
                            </div>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <a href="{{ route('bahan_uji_labkon.index') }}"><button type="button"
                                class="btn btn-default waves-effect">Batal</button></a>
                        <button type="submit" class="btn btn-primary waves-effect waves-light ml-2">Simpan</button>
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
@endsection
