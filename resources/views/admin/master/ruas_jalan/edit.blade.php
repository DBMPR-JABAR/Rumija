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
                    <h4>Ruas Jalan</h4>
                    <span>Seluruh Ruas Jalan yang ada di naungan DBMPR Jabar</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('getMasterRuasJalan') }}">Ruas Jalan</a> </li>
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
                    <h5>Edit Data Ruas Jalan</h5>
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                            <li><i class="feather icon-minus minimize-card"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="pb-5 pl-5 pr-5 card-block">

                    <form action="{{ route('updateMasterRuasJalan') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $ruasJalan->id }}">

                        <!-- <div class="form-group row">
                                                        <label class="col-md-2 col-form-label">Id Ruas Jalan</label>
                                                        <div class="col-md-10">
                                                            <input name="id_ruas_jalan" type="text" class="form-control" required value="{{ $ruasJalan->id_ruas_jalan }}">
                                                        </div>
                                                    </div> -->
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Kode Ruas Jalan</label>

                            <div class="col-md-10">
                                <input name="id_ruas_jalan" type="text" class="form-control" maxlength="6" value="{{ $ruasJalan->id_ruas_jalan }}" required>
                            </div>
                        </div>
                        <div class=" form-group row">
                            <label class="col-md-2 col-form-label">Nama Ruas Jalan</label>
                            <div class="col-md-10">
                                <input name="nama_ruas_jalan" type="text" class="form-control" required
                                    value="{{ $ruasJalan->nama_ruas_jalan }}">
                            </div>
                        </div>

                        <?php
                        use Illuminate\Support\Facades\Auth;

                        if (Auth::user()->internalRole->uptd) {
                        $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd); ?>
                        <input name="uptd_id" type="number" class="form-control" value="{{ $uptd_id }}" hidden>
                        <?php
                        } else {
                        ?>
                        <div class=" form-group row">
                            <label class="col-md-2 col-form-label">UPTD</label>
                            <div class="col-md-10">
                                <select class="form-control searchableField" id="uptd_id" name="uptd_id"
                                    style="min-width: 100%;" onchange="ubahDataSUP()" required>
                                    @foreach ($uptd as $uptdData)
                                        @if ($ruasJalan->uptd_id == $uptdData->id)
                                            <option value="<?php echo $uptdData->id; ?>"
                                                selected><?php echo $uptdData->nama; ?>
                                            </option>
                                        @else
                                            <option value="<?php echo $uptdData->id; ?>"><?php echo $uptdData->nama; ?></option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <?php
                        }
                        ?>

                        <div class=" form-group row">
                            <label class="col-md-2 col-form-label">SUP</label>
                            <div class="col-md-10">
                                <select class="form-control searchableField" id="sup" name="sup" style="min-width: 100%;" required>
                                    <!-- <option value="" selected>- Event Name -</option> -->

                                    @foreach ($sup as $supData)
                                        @if ($supData->id == $ruasJalan->sup)
                                            <option value="<?php echo $supData->id; ?>"
                                                selected><?php echo $supData->name; ?>
                                            </option>
                                        @else
                                            <option value="<?php echo $supData->id; ?>"><?php echo $supData->name; ?></option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-2 col-form-label">Lokasi</label>
                            <div class="col-md-10">
                                <input name="lokasi" type="text" class="form-control" required
                                    value="{{ $ruasJalan->lokasi }}">
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-2 col-form-label">Panjang (meter)</label>
                            <div class="col-md-10">
                                <input name="panjang" type="number" step="1" pattern="^[\d]+$" class="form-control" required
                                    value="{{ $ruasJalan->panjang }}">
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-2 col-form-label">STA Awal</label>
                            <div class="col-md-10">
                                <input name="sta_awal" type="text" step="0.01" class="form-control" required
                                    value="{{ $ruasJalan->sta_awal }}">
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-2 col-form-label">STA Akhir</label>
                            <div class="col-md-10">
                                <input name="sta_akhir" type="text" placeholder="contoh : KM.BDG 9+000" class="form-control"
                                    required value="{{ $ruasJalan->sta_akhir }}">
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-2 col-form-label">Lat Awal</label>
                            <div class="col-md-10">
                                <input id="lat0" name="lat_awal" placeholder="contoh : KM.BDG 9+700" type="text" class="form-control"
                                    required value="{{ $ruasJalan->lat_awal }}">
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-2 col-form-label">Long Awal</label>
                            <div class="col-md-10">
                                <input id="long0" name="long_awal" type="text" class="form-control" required
                                    value="{{ $ruasJalan->long_awal }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Latitude Titik Tengah (Centroid)</label>
                            <div class="col-md-10">
                                <input id="lat1" name="lat_ctr" type="text" class="form-control formatLatLong"
                                    value="{{ $ruasJalan->lat_ctr }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Longitude Titik Tengah (Centroid)</label>
                            <div class="col-md-10">
                                <input id="long1" name="long_ctr" type="text" class="form-control formatLatLong"
                                    value="{{ $ruasJalan->long_ctr }}">
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-2 col-form-label">Lat Akhir</label>
                            <div class="col-md-10">
                                <input id="lat2" name="lat_akhir" type="text" class="form-control" required
                                    value="{{ $ruasJalan->lat_akhir }}">
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-2 col-form-label">Long Akhir</label>
                            <div class="col-md-10">
                                <input id="long2" name="long_akhir" type="text" class="form-control" required
                                    value="{{ $ruasJalan->long_akhir }}">
                            </div>
                        </div>

                        <p>Marker Biru: Titik Awal <br> Marker Hijau: Titik Tengah <br> Marker Merah: Titik Akhir <br> (Dipilih Bergantian) </p>
                        <div id="mapLatLong" class="mb-2 full-map" style="height: 300px; width: 100%"></div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Kabupaten Kota</label>
                            <div class="col-md-10">
                                <input name="kab_kota" type="text" class="form-control" required
                                    value="{{ $ruasJalan->kab_kota }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Foto 1</label>
                            <div class="col-md-5">
                                <img style="min-height: 40px" class="mx-auto rounded img-thumbnail d-block" id="foto_preview"
                                    src="{{ url('storage/' . @$ruasJalan->foto) }}" alt="">
                            </div>
                            <div class="col-md-5">
                                <input id="foto" name="foto" type="file" accept="image/*" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Foto 2</label>
                            <div class="col-md-5">
                                <img style="min-height: 40px" class="mx-auto rounded img-thumbnail d-block" id="foto_preview_1"
                                    src="{{ url('storage/' . @$ruasJalan->foto_1) }}" alt="">
                            </div>
                            <div class="col-md-5">
                                <input  id="foto_1" name="foto_1" type="file" accept="image/*" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Foto 3</label>
                            <div class="col-md-5">
                                <img style="min-height: 40px" class="mx-auto rounded img-thumbnail d-block" id="foto_preview_2"
                                    src="{{ url('storage/' . @$ruasJalan->foto_2) }}" alt="">
                            </div>
                            <div class="col-md-5">
                                <input id="foto_2" name="foto_2" type="file" accept="image/*" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Video</label>
                            <div class="col-md-5">
                                <video class="mx-auto rounded img-thumbnail d-block" id="video_preview"
                                    src="{{ url('storage/' . @$ruasJalan->video) }}" alt="" controls>
                            </div>
                            <div class="col-md-5">
                                <input id="video" name="video" type="file" accept="video/mp4" class="form-control">
                            </div>
                        </div>


                        <a href="{{ url()->previous() }}"><button type="button" class="btn btn-danger waves-effect "
                                data-dismiss="modal">Kembali</button></a>

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
        // Format mata uang.
        $('.formatRibuan').mask('000.000.000.000.000', {
            reverse: true
        });

        function ubahDataSUP() {

            val = document.getElementById("uptd_id").value

            $.ajax({
                url: "{{ url('admin/master-data/ruas-jalan/getSUP') }}",
                method: 'get',
                dataType: 'JSON',
                data: {
                    id: val
                },
                complete: function(result) {
                    $('#sup').empty(); // remove old options
                    $('#sup').append($("<option></option>").text('Pilih SUP'));

                    result.responseJSON.forEach(function(item) {
                        $('#sup').append($("<option></option>").attr("value", item["name"]).text(item[
                            "name"]));
                    });
                }
            });
        }

        $('#mapLatLong').ready(() => {

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
            require([
            "esri/Map",
            "esri/views/MapView",
            "esri/Graphic"
            ], function(Map, MapView, Graphic) {

                const map = new Map({
                    basemap: "streets"
                });

                const view = new MapView({
                    container: "mapLatLong",
                    map: map,
                    center: [107.6191, -6.9175],
                    zoom: 9,
                });

                let tempGraphic = [];

                if($("#lat0").val() != '' && $("#long0").val() != ''){
                    addTitik(0, $("#lat0").val(), $("#long0").val(), "blue");
                }
                if($("#lat1").val() != '' && $("#long1").val() != ''){
                    addTitik(1, $("#lat1").val(), $("#long1").val(), "green");
                }
                if($("#lat2").val() != '' && $("#long2").val() != ''){
                    addTitik(2, $("#lat2").val(), $("#long2").val(), "red");
                }

                let mouseclick = 0;

                view.on("click", function(event){
                    const lat = event.mapPoint.latitude;
                    const long = event.mapPoint.longitude;

                    // Genap = Titik Awal
                    if(mouseclick % 3 == 0){
                        addTitik(0, lat, long, "blue");
                        $("#lat0").val(lat);
                        $("#long0").val(long);
                    }else if(mouseclick % 3 == 1){
                        addTitik(1, lat, long, "green");
                        $("#lat1").val(lat);
                        $("#long1").val(long);
                    }else{
                        addTitik(2, lat, long, "red");
                        $("#lat2").val(lat);
                        $("#long2").val(long);
                    }
                    mouseclick++;
                });

                $("#lat0, #long0").keyup(function () {
                    const lat = $("#lat0").val();
                    const long = $("#long0").val();
                    addTitik(0, lat, long, "blue");
                });
                $("#lat1, #long1").keyup(function () {
                    const lat = $("#lat1").val();
                    const long = $("#long1").val();
                    addTitik(1, lat, long, "green");
                });
                $("#lat2, #long2").keyup(function () {
                    const lat = $("#lat2").val();
                    const long = $("#long2").val();
                    addTitik(2, lat, long, "red");
                });

                function addTitik(point, lat, long, color){
                    if($("#lat"+point).val() != '' && $("#long"+point).val() != ''){
                        view.graphics.remove(tempGraphic[point]);
                    }
                    var graphic = new Graphic({
                        geometry: {
                            type: "point",
                            longitude: long,
                            latitude: lat
                        },
                        symbol: {
                            type: "picture-marker",
                            url: `http://esri.github.io/quickstart-map-js/images/${color}-pin.png`,
                            width: "14px",
                            height: "24px"
                        }
                    });
                    tempGraphic[point] = graphic;

                    view.graphics.add(graphic);
                }
            });
        });

    </script>
@endsection
