@extends('admin.layout.index')

@section('title') Edit Pekerjaan @endsection

@section('head')
    <link rel="stylesheet" href="https://js.arcgis.com/4.18/esri/themes/light/main.css">
@endsection

@section('page-header')
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Edit Pekerjaan</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('getDataPekerjaan') }}">Data Pekerjaan</a> </li>
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
                    <h5>Edit Data Pekerjaan</h5>
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                            <li><i class="feather icon-minus minimize-card"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="card-block">

                    <form action="{{ route('updateDataPekerjaan', $pekerjaan->id_pek) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_pek" value="{{ $pekerjaan->id_pek }}">
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Sub Kegiatan</label>
                            <div class="col-md-10">
                                <input name="sub_kegiatan" type="text" value="{{ $pekerjaan->sub_kegiatan }}" placeholder="Entry Sub Kegiatan" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Tanggal</label>
                            <div class="col-md-10">
                                <input name="tanggal" type="date" class="form-control" required
                                    value="{{ $pekerjaan->tanggal }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Mandor</label>
                            @if (Auth::user()->internalRole->role != null && str_contains(Auth::user()->internalRole->role, 'Mandor'))
                                <div class="col-md-10">
                                    <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                                </div>
                            @else
                                <div class="col-md-10">
                                    <select class="form-control searchableField" name="nama_mandor" required>
                                        @foreach ($mandor as $data)
                                            <option value="{{ $data->name }},{{ $data->id }}"
                                                {{ $data->id == $pekerjaan->user_id ? 'selected' : '' }}>
                                                {{ $data->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Jenis Pekerjaan</label>
                            <div class="col-md-10">
                                <select class="form-control searchableField" name="jenis_pekerjaan" required>
                                    @foreach ($jenis_laporan_pekerjaan as $data)
                                        <option value="{{ $data->id }}"
                                            {{ $data->id == $pekerjaan->jenis_pekerjaan ? 'selected' : '' }}>
                                            {{ $data->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Jenis Kegiatan</label>
                            <div class="col-md-10">
                                <select class="form-control searchableField" id="paket" name="paket">
                                    @foreach ($nama_kegiatan_pekerjaan as $data)
                                    <option value="{{$data->name}}" @if ($pekerjaan->paket)
                                        {{$pekerjaan->paket == $data->name ? 'selected' : ''}}
                                    @endif>{{$data->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if (Auth::user()->internalRole->uptd)
                            <input type="hidden" id="uptd" name="uptd_id" value="{{ Auth::user()->internalRole->uptd }}"
                                value="{{ $pekerjaan->uptd_id }}">
                        @else
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Uptd</label>
                                <div class="col-md-10">
                                    <select class="form-control searchableField" id="uptd" name="uptd_id"
                                        value="{{ $pekerjaan->uptd_id }}" onchange="ubahOption()">
                                        @foreach ($uptd as $data)
                                            <option value="{{ $data->id }}"
                                                {{ $data->id == $pekerjaan->uptd_id ? 'selected' : '' }}>
                                                {{ $data->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">SUP</label>
                            <div class="col-md-10">
                                <select class="form-control searchableField" id="sup" name="sup" required
                                    value="{{ $pekerjaan->sup }}">
                                   
                                    <option></option>
                                    @foreach ($sup as $data)
                                        <option value="{{ $data->name }},{{ $data->id }}"
                                            {{ $data->name == $pekerjaan->sup ? 'selected' : '' }}>{{ $data->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Ruas Jalan</label>
                            <div class="col-md-10">
                                <select class="form-control searchableField" id="ruas_jalan" name="ruas_jalan" required
                                    value="{{ $pekerjaan->ruas_jalan }}">

                                    @foreach ($ruas_jalan as $data)
                                        <option value="{{ $data->nama_ruas_jalan }},{{ $data->id_ruas_jalan }}"
                                            {{ $data->id_ruas_jalan == $pekerjaan->ruas_jalan_id ? 'selected' : '' }}>
                                            {{ $data->nama_ruas_jalan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Lokasi</label>
                            <div class="col-md-10">
                                <input name="lokasi" type="text" class="form-control" required
                                    value="{{ $pekerjaan->lokasi }}" placeholder="KM Bdg 100+0 s.d 120+900">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Koordinat X</label>
                            <div class="col-md-10">
                                <input name="lat" id="lat" type="text" class="form-control formatLatLong" required
                                    value="{{ $pekerjaan->lat }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Koordinat Y</label>
                            <div class="col-md-10">
                                <input name="lng" id="long" type="text" class="form-control formatLatLong"
                                    value="{{ $pekerjaan->lng }}">
                            </div>
                        </div>

                        <div id="mapLatLong" class="full-map mb-2" style="height: 300px; width: 100%"></div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Panjang (meter)</label>
                            <div class="col-md-10">
                                <input name="panjang" type="number" class="form-control formatRibuan" required
                                    value="{{ $pekerjaan->panjang }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Perkiraan Kuantitas</label>
                            <div class="col-md-10">
                                <input name="perkiraan_kuantitas" type="number" class="form-control" required
                                    value="{{ $pekerjaan->perkiraan_kuantitas }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Foto Dokumentasi (Sebelum)</label>
                            <div class="col-md-4">
                                <img style="max-height: 400px;" class="img-thumbnail rounded mx-auto d-block"
                                    src="{{ url('storage/pekerjaan/' . $pekerjaan->foto_awal) }}" alt="">
                            </div>
                            <div class="col-md-5">
                                <input name="foto_awal" type="file" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Foto Dokumentasi (Sedang)</label>
                            <div class="col-md-4">
                                <img style="max-height: 400px;" class="img-thumbnail rounded mx-auto d-block"
                                    src="{{ url('storage/pekerjaan/' . $pekerjaan->foto_sedang) }}" alt="">
                            </div>
                            <div class="col-md-5">
                                <input name="foto_sedang" type="file" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Foto Dokumentasi (Sesudah)</label>
                            <div class="col-md-4">
                                <img style="max-height: 400px;" class="img-thumbnail rounded mx-auto d-block"
                                    src="{{ url('storage/pekerjaan/' . $pekerjaan->foto_akhir) }}" alt="">
                            </div>
                            <div class="col-md-5">
                                <input name="foto_akhir" type="file" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Foto Dokumentasi (Pegawai)</label>
                            <div class="col-md-4">
                                <img style="max-height: 400px;" class="img-thumbnail rounded mx-auto d-block"
                                    src="{{ url('storage/pekerjaan/' . @$pekerjaan->foto_pegawai) }}" alt="">
                            </div>
                            <div class="col-md-5">
                                <input name="foto_pegawai" type="file" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Video Dokumentasi</label>
                            <div class="col-md-4">
                                <video style="max-height: 400px;" controls class="img-thumbnail rounded mx-auto d-block">
                                    <source src="{{ url('storage/pekerjaan/' . $pekerjaan->video) }}" type="video/mp4" />
                                </video>
                            </div>
                            <div class="col-md-5">
                                <input name="video" type="file" class="form-control" accept="video/mp4">
                                <label for="video">Maksimum ukuran file 1024 Mb</label>
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
                        basemap: "osm"
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
            option_id = 'id_ruas_jalan'


            setDataSelect(id, url, id_select, text, option_id, option)

            //untuk select SUP
            url = "{{ url('admin/master-data/ruas-jalan/getSUP') }}"
            id_select = '#sup'
            text = 'Pilih SUP'
            option = 'name'
            option_id = 'id'


            setDataSelect(id, url, id_select, text, option_id, option)
        }

    </script>
@endsection
