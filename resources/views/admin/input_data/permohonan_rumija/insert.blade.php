@extends('admin.layout.index')

@section('title') Permohonan Rumija @endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Permohonan Rumija</h4>
                <span>Master Data Permohonan Rumija</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('permohonan_rumija.index') }}">Permohonan Rumija</a> </li>
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
                <h5>Tambah Data Permohonan Rumija</h5>
                @else
                <h5>Perbaharui Data Permohonan Rumija</h5>
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
                <form action="{{ route('permohonan_rumija.store') }}" method="post" enctype="multipart/form-data"
                    onsubmit="return Validate(this);">
                    @else
                    <form action="{{ route('permohonan_rumija.update', $permohonan_rumija->id) }}" method="post"
                        enctype="multipart/form-data" onsubmit="return Validate(this);">
                        @method('PUT')
                        @endif
                        @csrf
                        <h4 class="sub-title">Data Surat Permohonan</h4>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Nomor</label>
                            <div class="col-md-9">
                                <input name="nomor" value="{{ @$permohonan_rumija->nomor }}" type="text"
                                    class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Perihal</label>
                            <div class="col-md-9">
                                <input name="perihal" value="{{ @$permohonan_rumija->perihal }}" type="text"
                                    class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Sifat</label>
                            <div class="col-md-9">
                                <input name="sifat" value="{{ @$permohonan_rumija->sifat }}" type="text"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Tanggal</label>
                            <div class="col-md-9">
                                <input name="tanggal" value="{{ @$permohonan_rumija->tanggal }}" type="date"
                                    class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Nama Pemohon</label>
                            <div class="col-md-9">
                                <input name="nama_pemohon" value="{{ @$permohonan_rumija->nama_pemohon }}" type="text"
                                    class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Alamat</label>
                            <div class="col-md-9">
                                <input name="alamat" value="{{ @$permohonan_rumija->alamat }}" type="text"
                                    class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">No dan Tanggal</label>
                            <div class="col-md-9">
                                <input name="nomor_dan_tanggal" value="{{ @$permohonan_rumija->nomor_dan_tanggal }}"
                                    type="text" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Surat/Berkas</label>
                            <div class="col-md-9">
                                <input name="surat_berkas" value="{{ @$permohonan_rumija->surat_berkas }}" type="text"
                                    class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Jenis Ijin</label>
                            <div class="col-md-9">
                                <input name="jenis_ijin" value="{{ @$permohonan_rumija->jenis_ijin }}" type="text"
                                    class="form-control" required>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-3 col-form-label">UPTD</label>
                            <div class="col-md-9">
                                <select class="form-control searchableField" id="edit_uptd" name="uptd_id" required>
                                    @foreach ($uptd as $data)
                                    <option value="{{ $data->id }}" id="{{ $data->id }}" @isset($permohonan_rumija)
                                        {{ $permohonan_rumija->uptd_id == $data->id ? 'selected' : '' }} @endisset>
                                        {{ $data->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <h4 class="sub-title">Persyaratan Permohonan</h4>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Tipe Permohonan</label>
                            <div class="col-md-9">
                                <select class="form-control searchableField" id="tipe_permohonan" name="tipe_permohonan"
                                    required>
                                    
                                    @foreach ($rumija_tipe as $rumija_tipe)
                                        <option value="{{ $rumija_tipe->id }}">{{ $rumija_tipe->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <input id="selectedPersyaratanForm" style="display: none" name="selectedPersyaratanForm">

                        <div id="persyaratan_container"></div>

                        <div class=" form-group row">
                            <a href="{{ route('permohonan_rumija.index') }}"><button type="button"
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
<script src="{{ asset('assets/vendor/jquery/js/jquery.mask.js') }}">
</script>
<script src="{{ asset('assets/js/rumija/permohonan_rumija.js') }}"></script>
<script>
    const data = @json(@$permohonan_rumija)

    const action = @json(@$action)

    const storageURL = `{{url('storage/')}}`

</script>
@endsection
