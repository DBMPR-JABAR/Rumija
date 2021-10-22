@extends('admin.layout.index')

@section('title') Nama Pengujian LabKon @endsection

@section('page-header')
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Nama Pengujian LabKon</h4>
                    <span>Master Data Nama Pengujian LabKon</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('bahan_uji_labkon.index') }}">Nama Pengujian LabKon</a>
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
                        <h5>Tambah Data Nama Pengujian LabKon</h5>
                    @else
                        <h5>Perbaharui Data Nama Pengujian LabKon</h5>
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
                        <form action="{{ route('detail_bahan_uji_labkon.store') }}" method="post">
                        @else
                            <form action="{{ route('detail_bahan_uji_labkon.update', $nama_uji_labkon->id) }}" method="post">
                                @method('PUT')
                    @endif
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Bahan Uji</label>
                        <div class="col-md-9">
                            <select class="searchableField form-control" style="width: 100%" id="id_bahan_uji" name="id_bahan_uji">
                                @foreach ($bahan_uji_labkon as $data)
                                <option value="{{$data->id}}" {{ @$nama_uji_labkon->id_bahan_uji == $data->id ? 'selected' : '' }}>{{$data->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Nama Pengujian</label>
                        <div class="col-md-9">
                                <input name="nama_pengujian" value="{{ @$nama_uji_labkon->nama_pengujian }}" type="text" class="form-control"
                                    required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Status</label>
                        <div class="col-md-9 border-checkbox-section">
                            <div class="border-checkbox-group border-checkbox-group-primary">
                                <input class="border-checkbox" type="checkbox" name="status" id="status" {{ @$nama_uji_labkon->status == 'aktif' ? 'checked' : '' }}>
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
