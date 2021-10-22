@php $jenis_laporan = DB::table('utils_jenis_laporan')->get(); $lokasi =
DB::table('utils_lokasi')->get(); @endphp @extends('admin.layout.index')
@section('title') Data Aduan @endsection @section('head')
<link
    rel="stylesheet"
    href="https://js.arcgis.com/4.18/esri/themes/light/main.css"
/>
@endsection @section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Data Aduan</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class="breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}">
                        <i class="feather icon-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('getLapor') }}">Laporan</a>
                </li>
                <li class="breadcrumb-item"><a href="#">Tambah</a></li>
            </ul>
        </div>
    </div>
</div>
@endsection @section('page-body')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Data Aduan</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{--
                        <li><i class="feather icon-maximize full-card"></i></li>
                        --}}
                        <li>
                            <i class="feather icon-minus minimize-card"></i>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                <form
                    action="{{ route('createLapor') }}"
                    method="post"
                    enctype="multipart/form-data"
                >
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Nama</label>
                        <div class="col-md-10">
                            <input
                                name="nama"
                                type="text"
                                class="form-control"
                                placeholder="Nama"
                                required
                            />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">No KTP</label>
                        <div class="col-md-10">
                            <input
                                name="nik"
                                type="text"
                                class="form-control"
                                placeholder="320xxxxxxxxxxxxxx"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                                required
                            />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Alamat</label>
                        <div class="col-md-10">
                            <input
                                name="alamat"
                                type="text"
                                class="form-control"
                                placeholder="Alamat"
                                required
                            />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Telepon</label>
                        <div class="col-md-10">
                            <input
                                name="telp"
                                type="tel"
                                class="form-control"
                                placeholder="081xxxxxxxxx"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                                required
                            />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Email</label>
                        <div class="col-md-10">
                            <input
                                name="email"
                                type="email"
                                class="form-control"
                                placeholder="example@mail.com"
                                required
                            />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Jenis</label>
                        <div class="col-md-10">
                            <select
                                name="jenis"
                                class="custom-select my-1 mr-sm-2 w-100"
                                id="pilihanKeluhan"
                                required
                            >
                                <option selected>Pilih...</option>
                                @foreach ($jenis_laporan as $laporan)
                                <option value="{{ $laporan->id }}">
                                    {{ $laporan->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Lokasi</label>
                        <div class="col-md-10">
                            <select
                                name="lokasi"
                                class="custom-select my-1 mr-sm-2 w-100"
                                id="pilihanKeluhan"
                                required
                            >
                                <option selected>Pilih...</option>
                                @foreach ($lokasi as $kabkota)
                                <option value="{{ $kabkota->name }}">
                                    {{ $kabkota->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Latitude</label>
                        <div class="col-md-10">
                            <input
                                name="lat"
                                id="lat"
                                type="text"
                                class="form-control formatLatLong"
                                required
                                placeholder="Contoh : -7.236980000000000"
                            />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Longitude</label>
                        <div class="col-md-10">
                            <input
                                name="long"
                                id="long"
                                type="text"
                                class="form-control formatLatLong"
                                required
                                placeholder="Contoh : 107.234326980000000000"
                            />
                        </div>
                    </div>

                    <div
                        id="mapLatLong"
                        class="full-map mb-2"
                        style="height: 300px; width: 100%"
                    ></div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Deskripsi</label>
                        <div class="col-md-10">
                            <textarea
                                name="deskripsi"
                                rows="3"
                                cols="3"
                                class="form-control"
                                placeholder="Masukkan Deskripsi"
                                required
                            ></textarea>
                        </div>
                    </div>

                    @if (Auth::user()->internalRole->uptd)
                    <?php $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
                    ?>
                    <input
                        type="hidden"
                        id="uptd"
                        name="uptd_id"
                        value="{{ $uptd_id }}"
                    />
                    @else
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">UPTD</label>
                        <div class="col-md-10">
                            <select
                                class="form-control"
                                id="uptd"
                                name="uptd_id"
                                required
                            >
                                <option>Pilih UPTD</option>
                                @foreach ($uptd as $data)
                                <option value="{{ $data->id }}">
                                    {{ $data->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Foto</label>
                        <div class="col-md-6">
                            <input
                                name="gambar"
                                type="file"
                                class="form-control"
                            />
                        </div>
                    </div>

                    <div class="form-group row m-1">
                        <a href="{{ route('getLapor') }}"
                            ><button
                                type="button"
                                class="btn btn-default waves-effect"
                            >
                                Daftar Laporan
                            </button></a
                        >
                        <button
                            type="submit"
                            class="btn btn-primary waves-effect waves-light ml-2"
                        >
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection @section('script')
<script src="{{
        asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js')
    }}"></script>
<script src="{{
        asset('assets/vendor/datatables.net/js/dataTables.buttons.min.js')
    }}"></script>
<script src="{{
        asset('assets/vendor/datatables.net/js/dataTables.bootstrap4.min.js')
    }}"></script>

<script src="{{
        asset(
            'assets/vendor/data-table/extensions/responsive/js/dataTables.responsive.min.js'
        )
    }}"></script>
<script src="{{
        asset(
            'assets/vendor/data-table/extensions/responsive/js/responsive.bootstrap4.min.js'
        )
    }}"></script>
<script src="{{ asset('assets/vendor/jquery/js/jquery.mask.js') }}"></script>
<script src="https://js.arcgis.com/4.18/"></script>
<script>
    $(document).ready(function () {
        // Format mata uang.
        $(".formatRibuan").mask("000.000.000.000.000", {
            reverse: true,
        });

        // Format untuk lat long.
        $(".formatLatLong").keypress(function (evt) {
            return /^\-?[0-9]*\.?[0-9]*$/.test($(this).val() + evt.key);
        });

        $("#mapLatLong").ready(() => {
            require([
                "esri/Map",
                "esri/views/MapView",
                "esri/Graphic",
            ], function (Map, MapView, Graphic) {
                const map = new Map({
                    basemap: "hybrid",
                });

                const view = new MapView({
                    container: "mapLatLong",
                    map: map,
                    center: [107.6191, -6.9175],
                    zoom: 8,
                });

                let tempGraphic;
                view.on("click", function (event) {
                    if ($("#lat").val() != "" && $("#long").val() != "") {
                        view.graphics.remove(tempGraphic);
                    }
                    var graphic = new Graphic({
                        geometry: event.mapPoint,
                        symbol: {
                            type: "picture-marker", // autocasts as new SimpleMarkerSymbol()
                            url:
                                "http://esri.github.io/quickstart-map-js/images/blue-pin.png",
                            width: "14px",
                            height: "24px",
                        },
                    });
                    tempGraphic = graphic;
                    $("#lat").val(event.mapPoint.latitude);
                    $("#long").val(event.mapPoint.longitude);

                    view.graphics.add(graphic);
                });
                $("#lat, #long").keyup(function () {
                    if ($("#lat").val() != "" && $("#long").val() != "") {
                        view.graphics.remove(tempGraphic);
                    }
                    var graphic = new Graphic({
                        geometry: {
                            type: "point",
                            longitude: $("#long").val(),
                            latitude: $("#lat").val(),
                        },
                        symbol: {
                            type: "picture-marker", // autocasts as new SimpleMarkerSymbol()
                            url:
                                "http://esri.github.io/quickstart-map-js/images/blue-pin.png",
                            width: "14px",
                            height: "24px",
                        },
                    });
                    tempGraphic = graphic;

                    view.graphics.add(graphic);
                });
            });
        });
    });
</script>
@endsection
