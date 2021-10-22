@extends('admin.layout.index')

@section('title') Jenis Laporan @endsection

@section('page-header')
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>SUP</h4>
                    <span>Master Data SUP</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ url('admin/master-data/sup') }}">SUP</a> </li>
                    <li class="breadcrumb-item"><a href="#">Edit</a> </li>
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
                    <h5>Perbaharui Data SUP</h5>
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                            <li><i class="feather icon-minus minimize-card"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="card-block pl-5 pr-5 pb-5">
                    <form action="{{ route('updateSUP', $sup->id) }}" method="post">
                    @method('PUT')
                    @csrf

                    @if(Auth::user() && Auth::user()->internalRole->uptd == null)
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">UPTD</label>
                        <div class="col-md-10">
                            <select name="uptd_id" class="form-control">
                                <option value="">== Select UPTD ==</option>
                                @foreach ($uptd_lists as $no => $data)
                                    <option value="{{ $data->id }}" @if($data->id == $sup->uptd_id) selected  @endif>{{ $data->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Nama SUP</label>
                        <div class="col-md-10">
                            <input name="name" id="sup_name" type="text" class="form-control" value="{{ @$sup->name }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Kode SUP</label>
                        <div class="col-md-10">
                            <input name="kd_sup" id="kd_sup" type="text" class="form-control" value="{{ @$sup->kd_sup }}" required>
                        </div>
                    </div>
                    <div class=" form-group row">
                        <a href="{{ url('admin/master-data/sup') }}"><button type="button" class="btn btn-primary waves-effect">Batal</button></a>
                        <button type="submit" class="btn btn-danger waves-effect waves-light ml-2">Simpan</button>
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
