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
                    <h4>Data Laporan Rawan Bencana</h4>
                    <span>Seluruh Data Rawan Bencana di naungan DBMPR Jabar</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('getDataLaporanBencana') }}">Data Laporan Rawan Bencana</a> </li>
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
                    <h5>Edit Data Laporan Rawan Bencana</h5>
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                            <li><i class="feather icon-minus minimize-card"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="card-block">

                    <form action="{{ route('updateDataLaporanBencana', $laporan_bencana->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Waktu Kejadian</label>
                            <div class="col-md-10">
                                <input name="waktu_kejadian" type="datetime-local" class="form-control" required
                                    value="{{ $waktu_kejadian_parse }}">
                            </div>
                        </div>
                        <!-- <input type="hidden" name="uptd_id" value="{{ $laporan_bencana->uptd_id }}"> -->
                        <input type="hidden" name="id" value="{{ $laporan_bencana->id }}">

                        @if (Auth::user()->internalRole->uptd)
                            <input type="hidden" id="uptd" name="uptd_id" value="{{ $laporan_bencana->uptd_id }}">
                        @else
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Uptd</label>
                                <div class="col-md-10">
                                    <select class="form-control searchableField" id="uptd" name="uptd_id" onchange="ubahOption()">
                                        @foreach ($uptd as $data)
                                            <option value="{{ $data->id }}" @if($laporan_bencana->uptd_id == $data->id) {{"selected"}} @endif>{{ $data->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">SUP</label>
                            <div class="col-md-10">
                                <select class="form-control searchableField" id="sup" name="sup" required >
                                    @foreach ($sup as $data)
                                    <option class="sup" value="{{$data->name}}" @if($laporan_bencana->sup == $data->name) {{"selected"}} @endif>{{$data->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Ruas Jalan</label>
                            <div class="col-md-10">
                                <select id="ruas_jalan" name="ruas_jalan" class="form-control searchableField">
                                    <option value="{{ $laporan_bencana->no_ruas }}">{{ $laporan_bencana->ruas_jalan }}</option>>
                                    <option>Pilih Ruas Jalan</option>
                                    @foreach ($ruas as $data)
                                        <option value="{{ $data->id_ruas_jalan }}" @if($laporan_bencana->no_ruas == $data->id_ruas_jalan) selected @endif>{{ $data->nama_ruas_jalan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Icon</label>
                            <div class="col-md-10">
                                <select name="icon_id" class="form-control searchableField" onchange="getURL()" id="icon">
                                    @foreach ($icon as $data)
                                        <option value="{{ $data->id }}" @if($laporan_bencana->icon_id == $data->id) selected @endif>{{ $data->icon_name }}</option>
                                    @endforeach
                                </select>
                                @if ($icon_curr == null)
                                    <img class="img-fluid mt-2" style="max-width: 100px" src="#" alt="" srcset=""
                                        id="icon-img">
                                @endif
                                @if ($icon_curr != null)
                                    <img class="img-fluid mt-2" style="max-width: 100px"
                                        src="{{ $icon_curr->icon_image }}" alt="" srcset="" id="icon-img">
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Lokasi</label>
                            <div class="col-md-10">
                                <input name="lokasi" type="text" class="form-control" value="{{ $laporan_bencana->lokasi }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Daerah</label>
                            <div class="col-md-10">
                                <input name="daerah" type="text" class="form-control" value="{{ $laporan_bencana->daerah }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Latitude</label>
                            <div class="col-md-10">
                                <input name="lat" id="etlat" type="text" class="form-control" value="{{ $laporan_bencana->lat }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Longitude</label>
                            <div class="col-md-10">
                                <input name="long" id="etlong" type="text" class="form-control"
                                    value="{{ $laporan_bencana->long }}">
                            </div>
                        </div>

                        <div class="row mapsWithGetLocationButton">
                            <div id="etmapLatLong" class="full-map mb-2" style="height: 300px; width: 100%"></div>
                            <button id="btn_geoLocation" onclick="getLocation({idLat:'etlat', idLong:'etlong'})" type="button"
                                class="btn bg-white text-secondary locationButton"><i class="ti-location-pin"></i></button>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Foto</label>
                            <div class="col-md-5">
                                <img class="img-thumbnail rounded mx-auto d-block"
                                    src="{{ url('storage/laporan_bencana/' . $laporan_bencana->foto) }}" alt="">
                            </div>
                            <div class="col-md-5">
                                <input name="foto" type="file" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Video</label>
                            <div class="col-md-4">
                                <video style="max-height: 400px;" controls class="img-thumbnail rounded mx-auto d-block">
                                    <source src="{{ url('storage/laporan_bencana/' . $laporan_bencana->video) }}" type="video/mp4" />
                                </video>
                            </div>
                            <div class="col-md-5">
                                <input name="video" type="file" class="form-control" accept="video/mp4">
                                <label for="video">Maksimum ukuran file 1024 Mb</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Keterangan</label>
                            <div class="col-md-10">
                                <textarea name="keterangan" rows="3" cols="3" class="form-control"
                                    placeholder="Masukkan Keterangan">{{ $laporan_bencana->keterangan }}</textarea>
                            </div>
                        </div>

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
    <script src="https://js.arcgis.com/4.18/"></script>

    <script>
        $(document).ready(function() {
            // ubahOption();
            let etlong = "{{ $laporan_bencana->long }}";
            let etlat = "{{ $laporan_bencana->lat }}";
            $('#etmapLatLong').ready(() => {
                require([
                    "esri/Map",
                    "esri/views/MapView",
                    "esri/Graphic"
                ], function(Map, MapView, Graphic) {

                    const map = new Map({
                        basemap: "osm"
                    });

                    const view = new MapView({
                        container: "etmapLatLong",
                        map: map,
                        center: [
                            (etlong != 0) ? etlong : 107.6191,
                            (etlat != 0) ? etlat : -6.9175
                        ],
                        zoom: 8,
                    });

                    let tempGraphic;
                    if (etlong != 0 && etlat != 0) {
                        var graphic = new Graphic({
                            geometry: {
                                type: "point",
                                longitude: etlong,
                                latitude: etlat
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
                    }

                    view.on("click", function(event) {
                        if ($("#etlat").val() != '' && $("#etlong").val() != '') {
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
                        $("#etlat").val(event.mapPoint.latitude);
                        $("#etlong").val(event.mapPoint.longitude);

                        view.graphics.add(graphic);
                    });
                    $("#etlat, #etlong").keyup(function() {
                        if ($("#etlat").val() != '' && $("#etlong").val() != '') {
                            view.graphics.remove(tempGraphic);
                        }
                        var graphic = new Graphic({
                            geometry: {
                                type: "point",
                                longitude: $("#etlong").val(),
                                latitude: $("#etlat").val()
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

        function ubahOption() {

            //untuk select Ruas
             id = document.getElementById("uptd").value
            url = "{{ url('admin/input-data/kondisi-jalan/getRuasJalan') }}"
            id_select = '#ruas_jalan'
            text = 'Pilih Ruas Jalan'
            optionVal = 'id_ruas_jalan'
            option = 'nama_ruas_jalan'

            setDataSelect(id, url, id_select, text, optionVal, option);
            const baseUrl = `{{ url('admin/master-data/rawanbencana/getDataSUP/') }}/` + id;
            $.get(baseUrl, {
                    id: id
                },
                function(response) {
                    $('.sup').remove();
                    for (var i = 0; i < response.sup.length; i++) {
                        $('#sup').append("<option value='" + response.sup[i].name + "' class='sup' >" + response.sup[i]
                            .name + "</option>");
                    }
                });
        }

        function getURL() {
            var id = document.getElementById("icon").value;
            const baseUrl = `{{ url('admin/master-data/rawanbencana/getURL') }}/` + id;
            $.get(baseUrl, {
                    id: id
                },
                function(response) {
                    console.log(response);
                    $('#icon-img').attr('src', response.icon[0].icon_image);
                });
        }

    </script>
@endsection
