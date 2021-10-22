@php
$jenis_laporan = DB::table('utils_jenis_laporan')->get();
$lokasi = DB::table('utils_lokasi')->get();
@endphp
@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection

@section('head')
<link rel="stylesheet" href="https://js.arcgis.com/4.18/esri/themes/light/main.css">
@endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Laporan Masyarakat</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('getLandingUPTD') }}">Laporan Masyarakat</a> </li>
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
                <h5>Laporan Masyarakat</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">

                <form action="{{ route('updateLandingLaporanMasyarakat') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $data->id }}">

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Nama</label>
                        <div class="col-md-10">
                            <input name="nama" type="text" class="form-control" value="{{ $data->nama }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">NIK</label>
                        <div class="col-md-10">
                            <input name="nik" type="text" class="form-control" value="{{ $data->nik }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Alamat</label>
                        <div class="col-md-10">
                            <input name="alamat" type="text" class="form-control" value="{{ $data->alamat }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Telepon</label>
                        <div class="col-md-10">
                            <input name="telp" type="tel" class="form-control" value="{{ $data->telp }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Email</label>
                        <div class="col-md-10">
                            <input name="email" type="email" class="form-control" value="{{ $data->email }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Jenis</label>
                        <div class="col-md-10">
                            <select name="jenis" class="custom-select my-1 mr-sm-2 w-100" id="pilihanKeluhan" required>
                                <option selected>Pilih...</option>
                                @foreach ($jenis_laporan as $laporan)
                                <option value="{{ $laporan->id }}" {{ $laporan->id == $data->jenis ? 'selected' : '' }}>
                                    {{ $laporan->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Gambar</label>
                        <div class="col-md-5">
                            <img src="{{ url('storage/' . $data->gambar) }}"
                                class="img-thumbnail rounded mx-auto d-block" alt="">
                        </div>
                        <div class="col-md-5">
                            <input name="gambar" type="file" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">lokasi</label>
                        <div class="col-md-10">
                            <select name="lokasi" class="custom-select my-1 mr-sm-2 w-100" id="pilihanKeluhan" required>
                                <option selected>Pilih...</option>
                                @foreach ($lokasi as $kabkota)
                                <option value="{{ $kabkota->name }}"
                                    {{ strtolower($kabkota->name) == strtolower($data->lokasi) ? 'selected' : '' }}>
                                    {{ $kabkota->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Lat</label>
                        <div class="col-md-10">
                            <input name="lat" id="lat" type="text" class="form-control" value="{{ $data->lat }}"
                                required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Long</label>
                        <div class="col-md-10">
                            <input name="long" id="long" type="text" class="form-control" value="{{ $data->long }}"
                                required>
                        </div>
                    </div>

                    <div id="mapLatLong" class="full-map mb-2" style="height: 300px; width: 100%"></div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Deskripsi</label>
                        <div class="col-md-10">
                            <textarea name="deskripsi" rows="3" cols="3" class="form-control"
                                placeholder="Masukkan Deskripsi" required>{{ $data->deskripsi }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">UPTD</label>
                        <div class="col-md-10">
                            <select name="uptd_id" class="custom-select my-1 mr-sm-2" id="pilihanUptd" required>
                                <option>Pilih...</option>
                                <option value="1" <?php if ($data->uptd_id == 1) {
                                        echo 'selected';
                                        } ?>>UPTD-I</option>
                                <option value="2" <?php if ($data->uptd_id == 2) {
                                        echo 'selected';
                                        } ?>>UPTD-II</option>
                                <option value="3" <?php if ($data->uptd_id == 3) {
                                        echo 'selected';
                                        } ?>>UPTD-III</option>
                                <option value="4" <?php if ($data->uptd_id == 4) {
                                        echo 'selected';
                                        } ?>>UPTD-IV</option>
                                <option value="5" <?php if ($data->uptd_id == 5) {
                                        echo 'selected';
                                        } ?>>UPTD-V</option>
                                <option value="6" <?php if ($data->uptd_id == 6) {
                                        echo 'selected';
                                        } ?>>UPTD-VI</option>
                            </select>
                        </div>
                    </div>

                    @if (strpos($data->nomorPengaduan, 'QR') === false)

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Status </label>
                        <div class="col-md-10">
                            <select class="form-control" name="status">
                                <option value="Submitted" @if (strpos($data->status, 'Submitted') !== false) selected
                                    @endif>Submitted</option>
                                <option value="Approved" @if (strpos($data->status, 'Approved') !== false) selected
                                    @endif>Approved</option>
                                <option value="Progress" @if (strpos($data->status, 'Progress') !== false) selected
                                    @endif>Progress</option>
                                <option value="Done" @if (strpos($data->status, 'Done') !== false) selected @endif>Done
                                </option>
                            </select>
                            <!-- <input name="status" type="text" class="form-control" disabled required> -->
                        </div>
                    </div>
                    @endif
                    <a href="{{ route('getLapor') }}"><button type="button"
                            class="btn btn-default waves-effect">Kembali</button></a>
                    <button type="submit" class="btn btn-mat btn-success">Simpan Perubahan</button>
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
<script src="https://js.arcgis.com/4.18/"></script>
<script>
    $(document).ready(function() {
            // Format mata uang.
            $('.formatRibuan').mask('000.000.000.000.000', {
                reverse: true
            });

            // Format untuk lat long.
            $('.formatLatLong').keypress(function(evt) {
                return (/^\-?[0-9]*\.?[0-9]*$/).test($(this).val() + evt.key);
            });

            $('#mapLatLong').ready(() => {
                require([
                    "esri/Map",
                    "esri/views/MapView",
                    "esri/Graphic"
                ], function(Map, MapView, Graphic) {

                    const map = new Map({
                        basemap: "hybrid"
                    });

                    const view = new MapView({
                        container: "mapLatLong",
                        map: map,
                        center: [107.6191, -6.9175],
                        zoom: 8,
                    });

                    let tempGraphic;
                    view.on("click", function(event) {
                        if ($("#lat").val() != '' && $("#long").val() != '') {
                            view.graphics.remove(tempGraphic);
                        }
                        var graphic = new Graphic({
                            geometry: event.mapPoint,
                            symbol: {
                                type: "picture-marker", // autocasts as new SimpleMarkerSymbol()
                                url: "http://esri.github.io/quickstart-map-js/images/blue-pin.png",
                                width: "14px",
                                height: "24px"
                            }
                        });
                        tempGraphic = graphic;
                        $("#lat").val(event.mapPoint.latitude);
                        $("#long").val(event.mapPoint.longitude);

                        view.graphics.add(graphic);
                    });
                    $("#lat, #long").keyup(function() {
                        if ($("#lat").val() != '' && $("#long").val() != '') {
                            view.graphics.remove(tempGraphic);
                        }
                        var graphic = new Graphic({
                            geometry: {
                                type: "point",
                                longitude: $("#long").val(),
                                latitude: $("#lat").val()
                            },
                            symbol: {
                                type: "picture-marker", // autocasts as new SimpleMarkerSymbol()
                                url: "http://esri.github.io/quickstart-map-js/images/blue-pin.png",
                                width: "14px",
                                height: "24px"
                            }
                        });
                        tempGraphic = graphic;

                        view.graphics.add(graphic);
                    });
                });
            });
        });

</script>
@endsection
