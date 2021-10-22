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
                <h5>Tambah Data Rencana Bantuan Keuangan</h5>
                @else
                <h5>Perbaharui Data Rencana Bantuan Keuangan</h5>
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
                <form action="{{ route('bankeu.store') }}" method="post" enctype="multipart/form-data">
                    @else
                    <form action="{{ route('bankeu.update', $bankeu->id) }}" method="post"
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
                            <label class="col-md-4 col-form-label">UPTD</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <select class="form-control" id="edit_uptd" name="unor" required>
                                            <option>Pilih UPTD</option>
                                            @foreach ($uptd_lists as $data)
                                            <option value="{{ 'uptd' . $data->id }}" id="uptd_{{ $data->id }}"
                                                @isset($bankeu)
                                                {{ $bankeu->unor == 'uptd' . $data->id ? 'selected' : '' }} @endisset>
                                                {{ $data->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Kategori Paket Pekerjaan</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <select class="form-control" name="kategori" required>
                                            <option>Pilih Kategori Paket Pekerjaan</option>
                                            @foreach ($kategori as $data)
                                            <option value="{{ $data->nama }}" @isset($bankeu)
                                                {{ $bankeu->kategori == $data->nama ? 'selected' : '' }} @endisset>
                                                {{ $data->nama }}</option>
                                            @endforeach
                                        </select>
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
                                            class="form-control" placeholder="Bantuan keuangan perbaikan jalan"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">No Kontrak, Tanggal Kontrak, dan Nilai Kontrak
                                (Rp)</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input name="no_kontrak" value="{{ @$bankeu->no_kontrak }}" type="text"
                                            class="form-control"
                                            placeholder="602.1/1521.Ting.02/KTR/PjPK/PJ2WP.III/2021">
                                    </div>
                                    <div class="col-md-4">
                                        <input name="tanggal_kontrak" value="{{ @$bankeu->tanggal_kontrak }}"
                                            type="date" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <input name="nilai_kontrak" value="{{ @$bankeu->nilai_kontrak }}" type="number"
                                            class="form-control" placeholder="19000000">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">No SMPK dan Tanggal SPMK</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="no_spmk" value="{{ @$bankeu->no_spmk }}" type="text"
                                            class="form-control" placeholder="">
                                    </div>
                                    <div class="col-md-6">
                                        <input name="tanggal_spmk" value="{{ @$bankeu->tanggal_spmk }}" type="date"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Panjang (km) dan Waktu Pelaksanaan (hari)</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="panjang" value="{{ @$bankeu->panjang }}" type="number"
                                            class="form-control" placeholder="">
                                    </div>
                                    <div class="col-md-6">
                                        <input name="waktu_pelaksanaan" value="{{ @$bankeu->waktu_pelaksanaan}}"
                                            type="number" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">PPK Kegiatan</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="ppk_kegiatan" value="{{ @$bankeu->ppk_kegiatan }}" type="text"
                                            class="form-control" placeholder="" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Penyedia Jasa</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <select class="form-control" name="penyedia_jasa" required>
                                            <option>Pilih Penyedia Jasa</option>
                                            @foreach ($penyedia_jasa as $data)
                                            <option value="{{ $data->nama }}" @isset($bankeu)
                                                {{ $bankeu->penyedia_jasa == $data->nama ? 'selected' : '' }} @endisset>
                                                {{ $data->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Konsultan Supervisi</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <select class="form-control" name="konsultasi_supervisi" required>
                                            <option>Pilih Konsultan</option>
                                            @foreach ($konsultan as $data)
                                            <option value="{{ $data->nama }}" @isset($bankeu)
                                                {{ $bankeu->konsultasi_supervisi == $data->nama ? 'selected' : '' }}
                                                @endisset>
                                                {{ $data->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Nama PPK</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <select class="form-control" name="nama_ppk" required>
                                            <option>Pilih PPK</option>
                                            @foreach ($ppk as $data)
                                            <option value="{{ $data->nama }}" @isset($bankeu)
                                                {{ $bankeu->nama_ppk == $data->nama ? 'selected' : '' }} @endisset>
                                                {{ $data->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Nama SSE dan Nama Gs</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="nama_sse" value="{{ @$bankeu->nama_sse }}" type="text"
                                            class="form-control" placeholder="">
                                    </div>
                                    <div class="col-md-6">
                                        <input name="nama_gs" value="{{ @$bankeu->nama_gs}}" type="text"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Ruas Jalan</label>
                            <div class="col-md-8">
                                <select id="ruas_jalan" style="max-width: 100%" class="searchableField" name="geo_id"
                                    required>
                                    <option value="-1">Gambar Manual</option>
                                    @foreach ($ruas_jalan as $data)
                                    <option value="{{ $data->geo_id }}" @isset($bankeu)
                                        {{ $bankeu->geo_id == $data->geo_id ? 'selected' : '' }} @endisset>
                                        {{ $data->nama_ruas_jalan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class=" form-group row" id="nama_lokasi">
                            <label class="col-md-4 col-form-label">Nama Lokasi</label>
                            <div class="col-md-8">
                                <input id="nama_lokasi_value" name="nama_lokasi" value="{{@$bankeu->nama_lokasi}}"
                                    type="text" class="form-control">
                            </div>
                        </div>

                        <input id="geo_json" name="geo_json" style="display:none" />

                        <small class="mb-1">*Jika tidak terdapat pada ruas jalan yang tersedia, anda dapat menggambar
                            manual dengan klik icon
                            polyline pada peta, gunakan jarak zoom terdekat untuk lebih presisi.</small>
                        <small class="text-danger">Klik 2x untuk mengakhiri gambar</small>
                        <div id="mapLatLong" class="mb-3 full-map" style="height: 300px; width: 100%">
                            <div id="tempel_disini"></div>
                        </div>


                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Pembagian Progres</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-2">
                                        <select id="pembagian_progres" class="form-control" name="pembagian_progres"
                                            required>
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                        </select>
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
                                                <input id="proggress_slider" name="progress"
                                                    value="{{ @$bankeu->progress }}" type="range"
                                                    class="form-control-range" placeholder="Proggress" required min="0"
                                                    max="100">
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
                                        <a class="nav-link active" data-toggle="tab" href="#bukti_1" role="tab"
                                            aria-selected="true">Target ke-1</a>
                                    </li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content tabs card-block" id="bukti_content_container">
                                    <div class="tab-pane active" id="bukti_1" role="tabpanel">
                                        <div class="form-group row">
                                            <label class="col-md-2 col-form-label">Foto 1.1</label>
                                            <div class="col-md-5">
                                                <img class="mx-auto rounded img-thumbnail d-block"
                                                    id="foto_1_preview_1">
                                            </div>
                                            <div class="col-md-5">
                                                <input id="foto" name="foto_1_1" type="file" accept="image/*"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-2 col-form-label">Foto 1.2</label>
                                            <div class="col-md-5">
                                                <img class="mx-auto rounded img-thumbnail d-block"
                                                    id="foto_1_preview_2">
                                            </div>
                                            <div class="col-md-5">
                                                <input id="foto_1" name="foto_1_2" type="file" accept="image/*"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-2 col-form-label">Foto 1.3</label>
                                            <div class="col-md-5">
                                                <img class="mx-auto rounded d-block img-thumbnail"
                                                    id="foto_1_preview_3">
                                            </div>
                                            <div class="col-md-5">
                                                <input id="foto_2" name="foto_1_3" type="file" accept="image/*"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-2 col-form-label">Video 1</label>
                                            <div class="col-md-5">
                                                <video class="mx-auto rounded img-thumbnail d-block"
                                                    id="video_1_preview" src="{{ url('storage/' . @$bankeu->video) }}"
                                                    alt="" controls>
                                            </div>
                                            <div class="col-md-5">
                                                <input id="video" name="video_1" type="file" accept="video/mp4"
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Dokumen (Optional) 1</label>
                                            <div class="col-md-5">
                                                <input id="dokumen" name="dokumen_1" type="file" accept="application/pdf"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>


                        @if(hasAccess(Auth::user()->internal_role_id,
                        'Verifikasi Bantuan Keuangan', 'Update'))
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-2 float-sm-left pt-0">Terverifikasi ?</legend>
                            <div class="col-sm-8">
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_verified" id="gridRadios1"
                                        value="1" {{@$bankeu->is_verified == '1' ? 'checked' : ''}}>
                                    <label class="form-check-label" for="gridRadios1">
                                        Iya
                                    </label>
                                </div>
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_verified" id="gridRadios2"
                                        value="0" {{@$bankeu->is_verified == '0' ? 'checked' : ''}}>
                                    <label class="form-check-label" for="gridRadios2">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                        @endif

                        <div id="isVerifiedOnly">
                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Catatan Perbaikan</label>
                            <div class="col-md-8">
                                <input id="nama_lokasi_value" name="catatan_perbaikan"
                                    type="text" class="form-control">
                            </div>
                        </div>
                        </div>

                        <div class=" form-group row">
                            <a href="{{ route('bankeu.index') }}"><button type="button"
                                    class="btn btn-default waves-effect">Batal</button></a>
                            <button type="button" class="ml-2 btn btn-primary waves-effect waves-light">Simpan</button>
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
let exitsData = null
@isset($bankeu)
exitsData = @json($bankeu)
@endisset

    $(document).ready(() => {
        const progressBefore = `{{ @$bankeu->progress }}`


        const isVerifiedContainer = $('#isVerifiedOnly')
        isVerifiedContainer.hide()
        const isVerified = document.getElementById('gridRadios1')
        const isNoVerified = document.getElementById('gridRadios2')

        isNoVerified.onchange = (event) => {
            if(event.target.checked)  isVerifiedContainer.show()
        }
        isVerified.onchange = (event) => {
            if(event.target.checked)  isVerifiedContainer.hide()

        }

            const uptd = document.getElementById("edit_uptd");
            if (uptd.length == 2) uptd.value = uptd[1].value;

            const buktiTemplate = (ke) => `<div class="tab-pane ${ke === 1 ? "active" :''}" id="bukti_${ke}" role="tabpanel">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Foto ${ke}.1</label>
                                        <div class="col-md-5">
                                            <img class="mx-auto rounded img-thumbnail d-block" id="foto_${ke}_preview_1">
                                        </div>
                                        <div class="col-md-5">
                                            <input id="foto" name="foto_${ke}_1" type="file" accept="image/*" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Foto ${ke}.2</label>
                                        <div class="col-md-5">
                                            <img class="mx-auto rounded img-thumbnail d-block" id="foto_${ke}_preview_2">
                                        </div>
                                        <div class="col-md-5">
                                            <input id="foto_1" name="foto_${ke}_2" type="file" accept="image/*" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Foto ${ke}.3</label>
                                        <div class="col-md-5">
                                            <img class="mx-auto rounded d-block img-thumbnail" id="foto_${ke}_preview_3">
                                        </div>
                                        <div class="col-md-5">
                                            <input id="foto_2" name="foto_${ke}_3" type="file" accept="image/*" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Video ${ke}</label>
                                        <div class="col-md-5">
                                            <video class="mx-auto rounded img-thumbnail d-block" id="video_${ke}_preview"
                                                src="{{ url('storage/' . @$bankeu->video) }}" alt="" controls>
                                        </div>
                                        <div class="col-md-5">
                                            <input id="video" name="video_${ke}" type="file" accept="video/mp4" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Dokumen (Optional) ${ke}</label>
                                            <div class="col-md-5">
                                                <input id="dokumen" name="dokumen_${ke}" type="file" accept="application/pdf"
                                                    class="form-control">
                                            </div>
                                        </div>
                                </div>`

            const navTemplate = (ke) => `<li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#bukti_${ke}" role="tab" aria-selected="true">Target ke-${ke}</a>
                                </li>`

            const targetTemplate = (ke) => `<div class=" form-group row">
                            <label class="col-md-4 col-form-label">Target ke-${ke}</label>
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
                        </div>`
            const pembagianProgres = document.getElementById("pembagian_progres")
            const targetContainer = document.getElementById("target_container")
            const buktiNavContainer = document.getElementById("bukti_nav_container")
            const buktiContentContainer = document.getElementById("bukti_content_container")
            pembagianProgres.onchange = (event) => {
                let i = 1;
                const value = Number(event.target.value);
                    let htmlTarget = ""
                    let htmlBuktiNav = ""
                    let htmlBuktiContent = ""
                    for(i;i<=value;i++){
                         htmlTarget += targetTemplate(i)
                         htmlBuktiNav += navTemplate(i)
                         htmlBuktiContent += buktiTemplate(i)
                        }
                        targetContainer.innerHTML = htmlTarget
                        buktiNavContainer.innerHTML = htmlBuktiNav
                        buktiContentContainer.innerHTML = htmlBuktiContent
            }

        const progressPercentage = document.getElementById('proggress_percent')
        const progressSlider =
        document.getElementById('proggress_slider')
        const onChange = (event) => {
            if(event.target.value < Number(progressBefore)) {
                progressPercentage.innerText = progressBefore
                progressSlider.value = progressBefore
            }
            else{
            progressPercentage.innerText = event.target.value
            progressSlider.value = event.target.value}
        }
        progressSlider.oninput = onChange
        progressSlider.onclick = onChange

        })

</script>
<script type="text/javascript" src="{{ asset('assets/js/bankeu.js') }}"></script>
@endsection
