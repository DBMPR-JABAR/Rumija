@extends('admin.layout.index') @section('title') Admin Dashboard @endsection
@section('head')
<link
    rel="stylesheet"
    href="https://js.arcgis.com/4.18/esri/themes/light/main.css"
/>
@endsection @section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Jembatan</h4>
                <span>Seluruh Jembatan yang ada di naungan DBMPR Jabar</span>
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
                    <a href="{{ route('getMasterJembatan') }}">Jembatan</a>
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
                <h5>Tambah Jembatan</h5>
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
            <div class="card-block pl-5 pr-5 pb-5">
                <form
                    action="{{ route('createJembatan') }}"
                    method="post"
                    enctype="multipart/form-data"
                >
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Id Jembatan</label
                            >
                            <div class="col-md-10">
                                <input
                                    name="id_jembatan"
                                    type="text"
                                    class="form-control"
                                    maxlength="10"
                                    placeholder="Contoh : 22.017.005"
                                    required
                                />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Nama Jembatan</label
                            >
                            <div class="col-md-10 my-auto">
                                <input
                                    name="nama_jembatan"
                                    type="text"
                                    class="form-control"
                                    placeholder="Contoh : Cihaurwangi"
                                    required
                                />
                            </div>
                        </div>
                        {{--
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Status</label
                            >
                            <div class="col-md-10">
                                <input
                                    name="status"
                                    type="text"
                                    class="form-control"
                                    required
                                />
                            </div>
                        </div>
                        --}} @if (Auth::user()->internalRole->uptd)
                        <input
                            type="hidden"
                            id="uptd"
                            name="uptd"
                            value="{{ Auth::user()->internalRole->uptd }}"
                        />
                        @else
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">UPTD</label>
                            <div class="col-md-10">
                                <select
                                    class="form-control searchableField"
                                    id="uptd"
                                    name="uptd"
                                    required
                                    onchange="ubahOption()"
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
                            <label class="col-md-2 col-form-label"
                                >Ruas Jalan</label
                            >
                            <div class="col-md-10">
                                <select
                                    id="ruas_jalan"
                                    id="ruas_jalan"
                                    name="ruas_jalan"
                                    class="form-control searchableField"
                                    required
                                >
                                    @if (Auth::user()->internalRole->uptd)
                                    @foreach ($ruasJalan as $data)
                                    <option
                                        value="{{ $data->nama_ruas_jalan }}"
                                    >
                                        {{ $data->nama_ruas_jalan }}
                                    </option>
                                    @endforeach @else
                                    <option>-</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">SUP</label>
                            <div class="col-md-10">
                                <select
                                    class="form-control searchableField"
                                    id="sup"
                                    name="sup"
                                    required
                                >
                                    @if (Auth::user()->internalRole->uptd)
                                    @foreach ($sup as $data)
                                    <option value="{{ $data->name }}">
                                        {{ $data->name }}
                                    </option>
                                    @endforeach @else
                                    <option>-</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Lokasi</label
                            >
                            <div class="col-md-10">
                                <input
                                    name="lokasi"
                                    type="text"
                                    class="form-control"
                                    placeholder="Contoh : Km. Bdg. 40 + 540"
                                    required
                                />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Panjang (meter)</label
                            >
                            <div class="col-md-10 my-auto">
                                <input
                                    name="panjang"
                                    type="text"
                                    class="form-control formatRibuan"
                                    placeholder="Contoh : 3"
                                    required
                                />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Lebar (meter)</label
                            >
                            <div class="col-md-10">
                                <input
                                    name="lebar"
                                    type="text"
                                    class="form-control formatRibuan"
                                    placeholder="Contoh : 6"
                                    required
                                />
                            </div>
                        </div>
                        <!--
                                             <div class="form-group row">
                                                <label class="col-md-2 col-form-label">Debit Air</label>
                                                <div class="col-md-10">
                                                    <input name="debit_air" type="text" class="form-control formatRibuan" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-2 col-form-label">Tinggi Jagaan</label>
                                                <div class="col-md-10">
                                                    <input name="tinggi_jagaan" type="number" class="form-control" step="any" required>
                                                </div>
                                            </div> -->

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Tinggi Muka Air Banjir</label
                            >
                            <div class="col-md-10">
                                <input
                                    name="tinggi_muka_air_banjir"
                                    type="text"
                                    class="form-control formatRibuan"
                                    placeholder="Contoh : 7"
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Kondisi</label
                            >
                            <div class="col-md-10">
                                <input
                                    name="kondisi"
                                    type="text"
                                    class="form-control"
                                    placeholder="Contoh Baik, tambah keterangan jika perlu"
                                    required
                                />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Tipe</label>
                            <div class="col-md-10">
                                <input
                                    name="tipe"
                                    type="text"
                                    class="form-control"
                                    placeholder="GTI, RBB, RBA"
                                    required
                                />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Jumlah Bentang</label
                            >
                            <div class="col-md-10 my-auto">
                                <input
                                    id="jumlah_bentang"
                                    name="jumlah_bentang"
                                    type="number"
                                    class="form-control"
                                    step="any"
                                    placeholder="Contoh : 1"
                                    required
                                />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"></label>
                            <div class="col-md-10 my-auto">
                                <a
                                    id="btnBentang"
                                    class="btn btn-mat btn-success w-100"
                                    >Input Bentang</a
                                >
                                <div class="form2 w-100"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Latitude</label
                            >
                            <div class="col-md-10 my-auto">
                                <input
                                    id="lat"
                                    name="lat"
                                    type="text"
                                    class="form-control formatLatLong"
                                    placeholder="-6.84221"
                                    required
                                />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Longitude</label
                            >
                            <div class="col-md-10 my-auto">
                                <input
                                    id="long"
                                    name="lng"
                                    type="text"
                                    class="form-control formatLatLong"
                                    placeholder="107.324"
                                    required
                                />
                            </div>
                        </div>
                        <div class="row mapsWithGetLocationButton">
                            <div
                                id="mapLatLong"
                                class="full-map mb-2"
                                style="height: 300px; width: 100%"
                            ></div>
                            <button
                                id="btn_geoLocation"
                                onclick="getLocation({idLat:'lat', idLong:'long'})"
                                type="button"
                                class="btn bg-white text-secondary locationButton"
                            >
                                <i class="ti-location-pin"></i>
                            </button>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Keterangan</label
                            >
                            <div class="col-md-10">
                                <input
                                    name="ket"
                                    type="text"
                                    class="form-control"
                                    placeholder="Keterangan"
                                    required
                                />
                            </div>
                        </div>
                        <hr />
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Foto Jembatan</label
                            ><br />
                        </div>
                        <div id="inputFormRow">
                            <div class="input-group">
                                <input
                                    type="text"
                                    name="nama[]"
                                    class="form-control m-input"
                                    placeholder="Judul Foto"
                                    autocomplete="off"
                                    required
                                />
                                <input
                                    type="file"
                                    name="foto[]"
                                    class="form-control m-input"
                                    accept="image/*"
                                    required
                                />
                                <div class="input-group-append">
                                    <button
                                        id="removeRow"
                                        type="button"
                                        class="btn btn-danger"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="newRow"></div>
                        <button id="addRow" type="button" class="btn btn-info">
                            Tambah Foto Jembatan
                        </button>
                    </div>

                    <div class="modal-footer">
                        <a href="{{ url()->previous() }}"
                            ><button
                                type="button"
                                class="btn btn-danger waves-effect"
                                data-dismiss="modal"
                            >
                                Kembali
                            </button></a
                        >
                        <button
                            type="submit"
                            class="btn btn-primary waves-effect waves-light"
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
        $("#dttable").DataTable();
        $("#delModal").on("show.bs.modal", function (event) {
            const link = $(event.relatedTarget);
            const id = link.data("id");
            console.log(id);
            const url = `{{ url('admin/master-data/jembatan/delete') }}/` + id;
            console.log(url);
            const modal = $(this);
            modal.find(".modal-footer #delHref").attr("href", url);
        });

        // Format mata uang.
        $(".formatRibuan").mask("000.000.000.000.000", {
            reverse: true,
        });

        // Format untuk lat long.
        $(".formatLatLong").keypress(function (evt) {
            return /^\-?[0-9]*\.?[0-9]*$/.test($(this).val() + evt.key);
        });

        $("#btnBentang").click(function () {
            console.log("klik bentang");
            jumBentang = document.getElementById("jumlah_bentang").value;

            if (jumBentang != "" && jumBentang > 0) {
                console.log(jumBentang);
                var html =
                    '<div class="form-group row w-100 mx-auto mb-0">' +
                    '<div class="col-md-2"><p class="my-1 p-1">Bentang</p></div>' +
                    '<div class="col-md-5"><p class="my-1 p-1">Panjang (meter)</p></div>' +
                    '<div class="col-md-5"><p class="my-1 p-1">Tipe Bangunan Atas</p></div></div>';

                $.ajax({
                    url:
                        "{{ url('admin/master-data/jembatan/getTipeBangunan') }}",
                    method: "get",
                    dataType: "JSON",
                    complete: function (result) {
                        console.log(result.responseJSON);
                        textOption = "";
                        result.responseJSON.forEach(function (item) {
                            textOption +=
                                '<option value="' +
                                item["id"] +
                                '">' +
                                item["nama"] +
                                "</option>";
                        });

                        for (var j = 0; j < jumBentang; j++) {
                            text =
                                '<div class="form-group row w-100 mx-auto">' +
                                '<div class="col-md-2">' +
                                '<input type="number" class="form-control h-100" value ="' +
                                (j + 1) +
                                '" readonly>' +
                                '</div><div class="col-md-5">' +
                                '<input name="panjangBentang' +
                                j +
                                '" type="number" class="form-control h-100" step="any" required></div>' +
                                '<div class="col-md-5">' +
                                '<select class="form-control" name="tipe' +
                                j +
                                '" required>' +
                                textOption +
                                "</select></div></div>";

                            html += text;
                        }

                        $(".form2").html(html);
                    },
                });
            } else {
                alert("Isi jumlah bentang terlebih dahulu");
            }
        });

        $("#mapLatLong").ready(() => {
            require([
                "esri/Map",
                "esri/views/MapView",
                "esri/Graphic",
            ], function (Map, MapView, Graphic) {
                const map = new Map({
                    basemap: "osm",
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

    $("#addRow").click(function () {
        var html = "";
        html += '<div id="inputFormRow">';
        html += '<div class="input-group">';
        html +=
            '<input type="text" name="nama[]" class="form-control m-input" placeholder="Enter title" autocomplete="off" required>';
        html +=
            '<input type="file" name="foto[]" class="form-control m-input" required>';
        html += '<div class="input-group-append">';
        html +=
            '<button id="removeRow" type="button" class="btn btn-danger">Hapus</button>';
        html += "</div>";
        html += "</div>";

        $("#newRow").append(html);
    });

    // remove row
    $(document).on("click", "#removeRow", function () {
        $(this).closest("#inputFormRow").remove();
    });

    function ubahOption() {
        //untuk select Ruas
        val = document.getElementById("uptd").value;
        id = parseInt(val.slice(val.length - 1));

        url = "{{ url('admin/input-data/kondisi-jalan/getRuasJalan') }}";
        id_select = "#ruas_jalan";
        text = "Pilih Ruas Jalan";
        option = "nama_ruas_jalan";

        setDataSelect(id, url, id_select, text, option, option);

        //untuk select SUP
        url = "{{ url('admin/master-data/ruas-jalan/getSUP') }}";
        id_select = "#sup";
        text = "Pilih SUP";
        option = "name";

        setDataSelect(id, url, id_select, text, option, option);
    }
</script>
@endsection
