@extends('admin.layout.index')

@section('title') Bantuan Keuangan @endsection
@section('head')
<link rel="stylesheet" href="https://js.arcgis.com/4.19/esri/themes/light/main.css">
@endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Bantuan Keuangan</h4>
                <span>Bantuan Keuangan DBMPR Jabar</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('bankeu.index') }}">Bantuan Keuangan</a>
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
                <h5 id="rencana_text">Tambah Data Rencana Bantuan Keuangan</h5>
                @else
                <h5 id="rencana_text">Perbaharui Data Rencana Bantuan Keuangan</h5>
                @endif
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="pb-5 pl-5 pr-5 card-block">

                @if ($action == 'store')
                <form action="{{ route('bankeu.store') }}"  onsubmit="return Validate(this);" method="post" enctype="multipart/form-data">
                    @else
                    <form action="{{ route('bankeu.update', $bankeu->id) }}" method="post"  onsubmit="return Validate(this);"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @endif
                        @csrf
                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Pemda</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="pemda" value="PEMERINTAH PROVINSI JAWA BARAT" type="text" readonly
                                            class="form-control" placeholder="PEMERINTAH PROVINSI JAWA BARAT" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">OPD</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="opd" value="DINAS BINA MARGA DAN PENATAAN RUANG" readonly
                                            type="text" class="form-control"
                                            placeholder="DINAS BINA MARGA DAN PENATAAN RUANG" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Kab/Kota</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($access)
                                        <select class="form-control searchableField" name="kab_kota" required
                                            {{@$access ? '': 'readonly'}}>
                                            <option>Pilih Kab/Kota</option>
                                            @foreach ($kab_kota as $data)
                                            <option value="{{ $data->name }}" @isset($bankeu)
                                                {{ $bankeu->kab_kota == $data->name ? 'selected' : '' }} @endisset>
                                                {{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                        @else
                                        <input name="kab_kota" value="{{ @$bankeu->kab_kota }}" type="text"
                                            class="form-control" required readonly>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Kategori Paket Pekerjaan</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($access)
                                        <select class="form-control searchableField" name="kategori" required>
                                            <option>Pilih Kategori Paket Pekerjaan</option>
                                            @foreach ($kategori as $data)
                                            <option value="{{ $data->nama }}" @isset($bankeu)
                                                {{ $bankeu->kategori == $data->nama ? 'selected' : '' }} @endisset>
                                                {{ $data->nama }}</option>
                                            @endforeach
                                        </select>
                                        @else
                                        <input name="kategori" value="{{ @$bankeu->kategori }}" type="text"
                                            class="form-control" required readonly>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Nama Kegiatan / Paket</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="nama_kegiatan" value="{{ @$bankeu->nama_kegiatan }}" type="text"
                                            class="form-control" placeholder="Bantuan keuangan perbaikan jalan" required
                                            {{@$access ? '': 'readonly'}}>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Panjang (m) dan Nilai Kontrak (Rp)</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="panjang" value="{{ @$bankeu->panjang }}" type="number"
                                            class="form-control" placeholder="" {{@$access ? '': 'readonly'}}>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="nilai_kontrak" value="{{ @$bankeu->nilai_kontrak }}" type="number"
                                            class="form-control" placeholder="19000000" {{@$access ? '': 'readonly'}}>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Panjang (m) dan Waktu Pelaksanaan (hari)</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="panjang" value="{{ @$bankeu->panjang }}" type="number"
                        class="form-control" placeholder="" {{@$access ? '': 'readonly'}}>
            </div>
            <div class="col-md-6">
                <input name="waktu_pelaksanaan" value="{{ @$bankeu->waktu_pelaksanaan}}" type="number"
                    class="form-control" {{@$access ? '': 'readonly'}}>
            </div>
        </div>
    </div>
</div> --}}

{{-- <div class=" form-group row">
                            <label class="col-md-4 col-form-label">PPK Kegiatan</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="ppk_kegiatan" value="{{ @$bankeu->ppk_kegiatan }}" type="text"
class="form-control" placeholder="" required {{@$access ? '': 'readonly'}}>
</div>
</div>
</div>
</div> --}}


<div class=" form-group row">
    <label class="col-md-4 col-form-label">Ruas Jalan</label>
    <div class="col-md-8">

        @if ($access)
        <select id="ruas_jalan" style="width: 100%" class="searchableField" name="geo_id" required
            {{@$access ? '': 'readonly'}}>
            <option value="-1">Gambar Manual</option>
            @foreach ($ruas_jalan as $data)
            <option value="{{ $data->geo_id }}" @isset($bankeu) {{ $bankeu->geo_id == $data->geo_id ? 'selected' : '' }}
                @endisset>
                {{ $data->nama_ruas_jalan }}</option>
            @endforeach
        </select>
        @else
        <input id="ruas_jalan" name="geo_id" value="{{ @$ruas_jalan_selected->geo_id }}" style="display:none" />
        <input
            value="{{ @$ruas_jalan_selected->nama_ruas_jalan ? $ruas_jalan_selected->nama_ruas_jalan : (@$bankeu->nama_lokasi ? @$bankeu->nama_lokasi : "-") }}"
            type="text" class="form-control" required readonly>
        @endif
    </div>
</div>

<div class=" form-group row" id="nama_lokasi">
    <label class="col-md-4 col-form-label">Nama Ruas</label>
    <div class="col-md-8">
        <input id="nama_lokasi_value" name="nama_lokasi" value="{{@$bankeu->nama_lokasi?$bankeu->nama_lokasi:'-'}}"
            type="text" class="form-control" {{@$access ? '': 'readonly'}}>
    </div>
</div>

<input id="geo_json" name="geo_json" style="display:none" />

<small class="mb-1">*Jika tidak terdapat pada ruas jalan yang tersedia, anda dapat menggambar
    manual dengan klik icon
    polyline pada peta, gunakan jarak zoom terdekat untuk lebih presisi. atau upload file shp (membutuhkan waktu untuk
    tim teman jabar menampilkannya di peta)</small>
<small class="text-danger">Klik 2x untuk mengakhiri gambar</small>
<div id="mapLatLong" class="mb-3 full-map" style="height: 300px; width: 100%">
    <div id="tempel_disini"></div>
</div>


<div class=" form-group row">
    <label class="col-md-4 col-form-label">Upload SHP</label>
    <div class="col-md-8">
        <input id="shp" name="shp" type="file" accept=".shp,.SHP,.zip" class="form-control"
            {{@$access ? '': 'readonly'}}>
    </div>
</div>


@if(hasAccess(Auth::user()->internal_role_id,
'Verifikasi Bantuan Keuangan', 'Update'))
<fieldset class="form-group row">
    <legend class="col-form-label col-sm-2 float-sm-left pt-0">Apakah kegiatan ini akan direalisasikan?</legend>
    <div class="col-sm-8">
        <div class="form-check-inline">
            <input class="form-check-input" type="radio" name="is_verified" id="gridRadios1" value="1"
                {{@$bankeu->is_verified == '1' ? 'checked' : ''}}>
            <label class="form-check-label" for="gridRadios1">
                Iya
            </label>
        </div>
        <div class="form-check-inline">
            <input class="form-check-input" type="radio" name="is_verified" id="gridRadios2" value="0"
                {{@$bankeu->is_verified == '0' ? 'checked' : ''}} {{@$action == 'store'? 'checked' : ''}}>
            <label class="form-check-label" for="gridRadios2">
                Tidak
            </label>
        </div>
    </div>
</fieldset>
@endif

<div id="isVerifiedOnly">
    @if ($access)
    <div class=" form-group row">
        <label class="col-md-4 col-form-label">Ditunjukan untuk</label>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <select id="ditunjukan_untuk" class="form-control form-select searchableField"
                        name="ditunjukan_untuk[]" required multiple>
                        <option>Pilih Pengguna untuk dikirimkan pemberitahuan</option>
                        @foreach ($users as $data)
                        <option value="{{ $data->id }}" @isset($ditunjukan_untuk) @foreach ($ditunjukan_untuk as $exits)
                            {{$exits == $data->id ? 'selected' : ''}} @endforeach @endisset>
                            {{ $data->name }} - {{$data->role}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    @endif


    <div class=" form-group row">
        <label class="col-md-4 col-form-label">No Kontrak dan Tanggal Kontrak</label>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    <input name="no_kontrak" value="{{ @$bankeu->no_kontrak }}" type="text" class="form-control"
                        placeholder="602.1/1521.Ting.02/KTR/PjPK/PJ2WP.III/2021" {{@$access ? '': 'readonly'}}>
                </div>
                <div class="col-md-6">
                    <input name="tanggal_kontrak" value="{{ @$bankeu->tanggal_kontrak }}" type="date"
                        class="form-control" {{@$access ? '': 'readonly'}}>
                </div>
            </div>
        </div>
    </div>

    <div class=" form-group row">
        <label class="col-md-4 col-form-label">No SMPK dan Tanggal SPMK</label>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    <input id="no_smpk" name="no_spmk" value="{{ @$bankeu->no_spmk }}" type="text" class="form-control"
                        placeholder="" {{@$access ? '': 'readonly'}} required>
                </div>
                <div class="col-md-6">
                    <input id="tanggal_smpk" name="tanggal_spmk" value="{{ @$bankeu->tanggal_spmk }}" type="date"
                        class="form-control" {{@$access ? '': 'readonly'}} required>
                </div>
            </div>
        </div>
    </div>

    <div class=" form-group row">
        <label class="col-md-4 col-form-label">Penyedia Jasa</label>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <input id="penyedia_jasa" name="penyedia_jasa" value="{{ @$bankeu->penyedia_jasa }}" type="text"
                        class="form-control" placeholder="" required {{@$access ? '': 'readonly'}}>
                </div>
            </div>
        </div>
    </div>


    <div class=" form-group row">
        <label class="col-md-4 col-form-label">Konsultan Supervisi</label>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <input id="konsultasi_supervisi" name="konsultasi_supervisi"
                        value="{{ @$bankeu->konsultasi_supervisi }}" type="text" class="form-control" placeholder=""
                        required {{@$access ? '': 'readonly'}}>
                </div>
            </div>
        </div>
    </div>

    <div class=" form-group row">
        <label class="col-md-4 col-form-label">Nama PPK</label>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <input id="nama_ppk" name="nama_ppk" value="{{ @$bankeu->nama_ppk }}" type="text"
                        class="form-control" placeholder="" required {{@$access ? '': 'readonly'}}>
                </div>
            </div>
        </div>
    </div>

    <div class=" form-group row">
        <label class="col-md-4 col-form-label">Nama SE dan Nama Gs</label>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    <input id="nama_se" name="nama_se" value="{{ @$bankeu->nama_se }}" type="text" class="form-control"
                        placeholder="" {{@$access ? '': 'readonly'}} required>
                </div>
                <div class="col-md-6">
                    <input id="nama_gs" name="nama_gs" value="{{ @$bankeu->nama_gs}}" type="text" class="form-control"
                        {{@$access ? '': 'readonly'}} required>
                </div>
            </div>
        </div>
    </div>


    <div class=" form-group row">
        <label class="col-md-4 col-form-label">Waktu Pelaksanaan (hari)</label>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <input id="waktu_pelaksanaan" name="waktu_pelaksanaan" value="{{ @$bankeu->waktu_pelaksanaan}}"
                        type="number" class="form-control" {{@$access ? '': 'readonly'}}>
                </div>
            </div>
        </div>
    </div>



    <div class=" form-group row">
        <label class="col-md-4 col-form-label">Pembagian Progres</label>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-5">
                    @if ($access)
                    <select id="pembagian_progres" class="form-control" name="pembagian_progres" required
                        {{@$access ? '': 'readonly'}}>
                        <option value="1" {{@$bankeu->pembagian_progres == "1" ? 'selected' : ''}}>
                            Tahunan</option>
                        <option value="2" {{@$bankeu->pembagian_progres == "2" ? 'selected' : ''}}>
                            Semester</option>
                        <option value="4" {{@$bankeu->pembagian_progres == "4" ? 'selected' : ''}}>
                            Quartal</option>
                    </select>
                    @else
                    <input name="pembagian_progres" value="{{@$bankeu->pembagian_progres}}" style="display: none">
                    <input name="pembagian_progres_view" id="pembagian_progres"
                        value="@if(@$bankeu->pembagian_progres == '1') Tahunan @elseif(@$bankeu->pembagian_progres == "
                        2") Semester @else Quartal @endif" type="text" class="form-control" readonly>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="target_container">
        <div class=" form-group row">
            <label class="col-md-4 col-form-label">Target ke-1</label>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="number" step="1" class="form-control">
                            <span class="input-group-append">
                                <label class="input-group-text">%</label>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class=" form-group row">
        <label class="col-md-4 col-form-label">Proggress (<span
                id="proggress_percent">{{@$bankeu->progress}}</span>%)</label>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <input name="progress_old" class="d-none" value="{{ @$bankeu->progress }}">
                    <div id="progress_container">
                        <input id="proggress_slider" name="progress" value="{{ @$bankeu->progress }}" type="range"
                            class="form-control-range" placeholder="Proggress" required min="0" max="100">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="sub-title">Bukti Progres Kegiatan</div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs  tabs" role="tablist" id="bukti_nav_container">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#bukti_1" role="tab" aria-selected="true">Target
                    ke-1</a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content tabs card-block" id="bukti_content_container">
            <div class="tab-pane active" id="bukti_1" role="tabpanel">
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Foto 1.1</label>
                    <div class="col-md-5">
                        <img class="mx-auto rounded img-thumbnail d-block" id="foto_1_preview_1">
                    </div>
                    <div class="col-md-5">
                        <input id="foto_1_1" name="foto_1_1" type="file" accept="image/*" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Foto 1.2</label>
                    <div class="col-md-5">
                        <img class="mx-auto rounded img-thumbnail d-block" id="foto_1_preview_2">
                    </div>
                    <div class="col-md-5">
                        <input id="foto_1_2" name="foto_1_2" type="file" accept="image/*" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Foto 1.3</label>
                    <div class="col-md-5">
                        <img class="mx-auto rounded d-block img-thumbnail" id="foto_1_preview_3">
                    </div>
                    <div class="col-md-5">
                        <input id="foto_1_3" name="foto_1_3" type="file" accept="image/*" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Video 1</label>
                    <div class="col-md-5">
                        <video class="mx-auto rounded img-thumbnail d-block" id="video_1_preview"
                            src="{{ url('storage/' . @$bankeu->video) }}" alt="" controls>
                    </div>
                    <div class="col-md-5">
                        <input id="video_1" name="video_1" type="file" accept="video/mp4" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Dokumen 1 (Optional)</label>
                    <div class="col-md-5">
                        <input id="dokumen_1" name="dokumen_1" type="file" accept="application/pdf"
                            class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class=" form-group row">
    <a href="{{ route('bankeu.index') }}"><button type="button" class="btn btn-default waves-effect">Batal</button></a>
    <button type="submit" class="ml-2 btn btn-primary waves-effect waves-light">Simpan</button>
</div>
</form>

</div>
</div>
</div>
</div>

@endsection

@section('script')
<script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}">
</script>
<script src="{{ asset('assets/vendor/datatables.net/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net/js/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery/js/jquery.mask.js') }}"></script>
<script src="https://js.arcgis.com/4.19/"></script>

<script type="text/javascript">
    const url = "{{url('/admin/input-data/bankeu/get_ruas_jalan_by_geo_id')}}"
let exitsData, exitsProgres = null
@isset($bankeu)
exitsData = @json($bankeu)
@endisset

@isset($bankeu_progres)
exitsProgres = @json($bankeu_progres)
@endisset

const urlStorage = `{{ url('storage/') }}`

const access = @json($access)

const action = @json($action)

const progressBefore = `{{ @$bankeu->progress }}`;

const verified_access = @json($verified_access)

</script>
<script type="text/javascript" src="{{ asset('assets/js/bankeu.js') }}"></script>
@endsection
