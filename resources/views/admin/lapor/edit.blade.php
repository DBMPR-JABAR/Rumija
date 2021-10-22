@extends('admin.layout.index')

@section('title') Data Laporan @endsection

@section('head')
    <link rel="stylesheet" href="https://js.arcgis.com/4.18/esri/themes/light/main.css">
@endsection

@section('page-header')
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Data Laporan</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('getLapor') }}">Laporan</a> </li>
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
                    <h5>Data Laporan</h5>
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                            <li><i class="feather icon-minus minimize-card"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="card-block">
                    <form action="{{ route('updateLapor') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="id" value="{{ $aduan->id }}">

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Nama Lengkap</label>
                            <div class="col-md-10">
                                <input name="nama" type="text" class="form-control" required value="{{ $aduan->nama }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Tanggal</label>
                            <div class="col-md-10">
                                <input name="tanggal" type="date" class="form-control" required
                                    value="{{ $aduan->tanggal }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">No. KTP</label>
                            <div class="col-md-10">
                                <input name="nik" type="text" class="form-control" required value="{{ $aduan->nik }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Alamat</label>
                            <div class="col-md-10">
                                <input name="alamat" type="text" class="form-control" required
                                    value="{{ $aduan->alamat }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Telp</label>
                            <div class="col-md-10">
                                <input name="telp" type="text" class="form-control" required value="{{ $aduan->telp }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">E-mail</label>
                            <div class="col-md-10">
                                <input name="email" type="email" class="form-control" required
                                    value="{{ $aduan->email }}">
                            </div>
                        </div>

                        @if (Auth::user()->internalRole->uptd)
                            <input type="hidden" id="uptd" name="uptd_id"
                                value="{{ str_replace('uptd', '', Auth::user()->internalRole->uptd) }}">
                        @else
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">UPTD</label>
                                <div class="col-md-10">
                                    <select class="form-control" id="uptd" name="uptd_id" onchange="ubahOption()" required>
                                        <!-- <option value="">Pilih UPTD</option> -->
                                        @foreach ($uptd as $data)
                                            @if ($data->id == $aduan->uptd_id)
                                                <option value="{{ $data->id }}" selected>{{ $data->nama }}</option>
                                            @else
                                                <option value="{{ $data->id }}">{{ $data->nama }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class=" form-group row">
                            <label class="col-md-2 col-form-label">Lokasi Pekerjaan</label>
                            <div class="col-md-10">
                                <select class="form-control select2" id="ruas_jalan" name="ruas_jalan"
                                    style="min-width: 100%;" required>
                                    <option value="<?php echo $aduan->ruas_jalan; ?>"><?php echo $aduan->ruas_jalan; ?></option>
                                    <option disabled></option>
                                    @foreach ($ruasJalan as $ruasJalanData)
                                        <option
                                            value="<?php echo $ruasJalanData->nama_ruas_jalan; ?>">
                                            <?php echo $ruasJalanData->nama_ruas_jalan; ?>
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Koordinat X</label>
                            <div class="col-md-10">
                                <input name="lat" id="lat" type="text" class="form-control formatLatLong" required
                                    value="{{ $aduan->lat }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Koordinat Y</label>
                            <div class="col-md-10">
                                <input name="lng" id="long" type="text" class="form-control formatLatLong" required
                                    value="{{ $aduan->lng }}">
                            </div>
                        </div>

                        <div id="mapLatLong" class="full-map mb-2" style="height: 300px; width: 100%"></div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Permasalahan</label>
                            <div class="col-md-10">
                                <input name="permasalahan" type="text" class="form-control" required
                                    value="{{ $aduan->permasalahan }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Foto</label>
                            <div class="col-md-6">
                                <input name="foto_awal" type="file" class="form-control" value="{{ $aduan->foto_awal }}">
                            </div>
                        </div>



                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Status</label>
                            <div class="col-md-6">
                                <select class="form-control" name="status">
                                    @foreach ($status as $data)
                                        <option value="{{ $data['id'] }}"
                                            {{ $data['name'] == $aduan->status ? 'selected' : '' }}>
                                            {{ $data['name'] }}</option>
                                    @endforeach
                                </select>
                                <!-- <input name="status" type="text" class="form-control" disabled required> -->
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

        function ubahOption() {

            //untuk select Ruas
            id = document.getElementById("uptd").value
            url = "{{ url('admin/input-data/kondisi-jalan/getRuasJalan') }}"
            id_select = '#ruas_jalan'
            text = 'Pilih Ruas Jalan'
            option = 'nama_ruas_jalan'

            setDataSelect(id, url, id_select, text, option, option)

        }

    </script>
@endsection
