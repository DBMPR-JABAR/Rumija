@extends('admin.layout.index')

@section('title') Bantuan Keuangan @endsection
@section('head')
<link rel="stylesheet" href="https://js.arcgis.com/4.19/esri/themes/light/main.css">
{{-- <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer>
</script> --}}
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
                <h5>Tambah Data Bantuan Keuangan</h5>
                @else
                <h5>Perbaharui Data Bantuan Keuangan</h5>
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



                        {{-- <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Latitude dan Longitude Awal (Marker Biru)</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input id="lat0" name="latitude_awal" value="{{ @$bankeu->latitude_awal }}"
                        type="text" class="form-control formatLatLong" required
                        placeholder="Latitude Awal">
            </div>
            <div class=" col-md-6">
                <input id="long0" name="longitude_awal" value="{{ @$bankeu->longitude_awal }}" type="text"
                    class="form-control formatLatLong" required placeholder="Longitude Awal">
            </div>
        </div>
    </div>
</div>

<div class=" form-group row">
    <label class="col-md-4 col-form-label">Latitude dan Longitude Akhir (Marker Hijau)</label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <input id="lat1" name="latitude_akhir" value="{{ @$bankeu->latitude_akhir }}" type="text"
                    class="form-control formatLatLong" required placeholder="Latitude Akhir">
            </div>
            <div class="col-md-6">
                <input id="long1" name="longitude_akhir" value="{{ @$bankeu->longitude_akhir }}" type="text"
                    class="form-control formatLatLong" required placeholder="Longitude Akhir">
            </div>
        </div>
    </div>
</div> --}}

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

<div class="form-group row">
    <label class="col-md-2 col-form-label">Foto Bukti Kegiatan</label>
    <div class="col-md-5">
        <img class="mx-auto rounded img-thumbnail d-block" id="foto_preview"
            src="{{ url('storage/' . @$bankeu->foto) }}" alt="">
    </div>
    <div class="col-md-5">
        <input id="foto" name="foto" type="file" accept="image/*" class="form-control">
    </div>
</div>
<div class="form-group row">
    <label class="col-md-2 col-form-label">Foto Bukti Kegiatan</label>
    <div class="col-md-5">
        <img class="mx-auto rounded img-thumbnail d-block" id="foto_preview_1"
            src="{{ url('storage/' . @$bankeu->foto_1) }}" alt="">
    </div>
    <div class="col-md-5">
        <input id="foto_1" name="foto_1" type="file" accept="image/*" class="form-control">
    </div>
</div>
<div class="form-group row">
    <label class="col-md-2 col-form-label">Foto Bukti Kegiatan</label>
    <div class="col-md-5">
        <img class="mx-auto rounded img-thumbnail d-block" id="foto_preview_2"
            src="{{ url('storage/' . @$bankeu->foto_2) }}" alt="">
    </div>
    <div class="col-md-5">
        <input id="foto_2" name="foto_2" type="file" accept="image/*" class="form-control">
    </div>
</div>
<div class="form-group row">
    <label class="col-md-2 col-form-label">Video Bukti Kegiatan</label>
    <div class="col-md-5">
        <video class="mx-auto rounded img-thumbnail d-block" id="video_preview"
            src="{{ url('storage/' . @$bankeu->video) }}" alt="" controls>
    </div>
    <div class="col-md-5">
        <input id="video" name="video" type="file" accept="video/mp4" class="form-control">
    </div>
</div>


<div class=" form-group row">
    <label class="col-md-4 col-form-label">Ruas Jalan</label>
    <div class="col-md-8">
        <select id="ruas_jalan" style="max-width: 100%" class="searchableField" name="geo_id" required>
            <option value="-1">Gambar Manual</option>
            @foreach ($ruas_jalan as $data)
            <option value="{{ $data->geo_id }}" @isset($bankeu) {{ $bankeu->geo_id == $data->geo_id ? 'selected' : '' }}
                @endisset>
                {{ $data->nama_ruas_jalan }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class=" form-group row" id="nama_lokasi">
    <label class="col-md-4 col-form-label">Nama Lokasi</label>
    <div class="col-md-8">
                <input id="nama_lokasi_value" name="nama_lokasi" value="{{@$bankeu->nama_lokasi}}" type="text"
                    class="form-control">
    </div>
</div>

<input id="geo_json" name="geo_json" style="display:none" />

<small class="mb-1">*Jika tidak terdapat pada ruas jalan yang tersedia, anda dapat menggambar manual dengan klik icon
    polyline pada peta, gunakan jarak zoom terdekat untuk lebih presisi.</small>
<small class="text-danger">Klik 2x untuk mengakhiri gambar</small>
<div id="mapLatLong" class="mb-3 full-map" style="height: 300px; width: 100%">
    <div id="tempel_disini"></div>
</div>
@if(hasAccess(Auth::user()->internal_role_id,
'Verifikasi Bantuan Keuangan', 'Update'))
<fieldset class="form-group row">
    <legend class="col-form-label col-sm-2 float-sm-left pt-0">Terverifikasi ?</legend>
    <div class="col-sm-8">
      <div class="form-check-inline">
        <input class="form-check-input" type="radio" name="is_verified" id="gridRadios1" value="1" {{@$bankeu->is_verified == '1' ? 'checked' : ''}}>
        <label class="form-check-label" for="gridRadios1">
          Iya
        </label>
      </div>
      <div class="form-check-inline">
        <input class="form-check-input" type="radio" name="is_verified" id="gridRadios2" value="0" {{@$bankeu->is_verified == '0' ? 'checked' : ''}}>
        <label class="form-check-label" for="gridRadios2">
          Tidak
        </label>
      </div>
    </div>
  </fieldset>
@endif
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
let exitsData = null
@isset($bankeu)
exitsData = @json($bankeu)
@endisset

    $(document).ready(() => {
        const filePreviews = [
            {
                input:"foto",
                preview:"foto_preview"
            },{
                input:"foto_1",
                preview:"foto_preview_1"
            },{
                input:"foto_2",
                preview:"foto_preview_2"
            },{
                input:"video",
                preview:"video_preview"
            },
        ]
        filePreviews.forEach(data=>{
            const inputElement = document.getElementById(data.input)
            inputElement.onchange = event => {
            const [file] = inputElement.files
            if(file) document.getElementById(data.preview).src = URL.createObjectURL(file)
        }
        })


        const progressBefore = `{{ @$bankeu->progress }}`

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

            const uptd = document.getElementById("edit_uptd");
            if (uptd.length == 2) uptd.value = uptd[1].value;

        })

</script>
<script type="text/javascript" src="{{ asset('assets/js/bankeu.js') }}"></script>
@endsection
