@extends('admin.layout.index')

@section('title') Kondisi Jalan @endsection
@section('head')
    <link rel="stylesheet" href="https://js.arcgis.com/4.18/esri/themes/light/main.css">
@endsection

@section('page-header')
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Kondisi Jalan</h4>
                    <span>Seluruh Kondisi Jalan yang ada di naungan DBMPR Jabar</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('kondisi_jalan.index') }}">Kondisi Jalan</a>
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
                        <h5>Tambah Data Kondisi Jalan</h5>
                    @else
                        <h5>Perbaharui Data Kondisi Jalan</h5>
                    @endif
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                            <li><i class="feather icon-minus minimize-card"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="card-block pl-5 pr-5 pb-5">

                    @if ($action == 'store')
                        <form action="{{ route('kondisi_jalan.store') }}" method="post">
                        @else
                            <form action="{{ route('kondisi_jalan.update', $kondisi_jalan->ID_KEMANTAPAN) }}"
                                method="post">
                                @method('PUT')
                    @endif
                    @csrf

                    <div class=" form-group row">
                        <label class="col-md-4 col-form-label">No Ruas, SUB Ruas, dan Suffix</label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <input name="NO_RUAS" value="{{ @$kondisi_jalan->NO_RUAS }}" type="number"
                                        class="form-control" placeholder="No Ruas">
                                </div>
                                <div class="col-md-4">
                                    <input name="SUB_RUAS" value="{{ @$kondisi_jalan->SUB_RUAS }}" type="text"
                                        class="form-control" placeholder="Sub Ruas">
                                </div>
                                <div class="col-md-4">
                                    <input name="SUFFIX" value="{{ @$kondisi_jalan->SUFFIX }}" type="number"
                                        class="form-control" placeholder="Suffix">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-4 col-form-label">Bulan dan Tahun</label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <input name="BULAN" value="{{ @$kondisi_jalan->BULAN }}" type="number" min="1"
                                        max="12" class="form-control" required placeholder="Bulan">
                                </div>
                                <div class="col-md-6">
                                    <input name="TAHUN" value="{{ @$kondisi_jalan->TAHUN }}" type="number" min="1945"
                                        max="2099" class="form-control" required placeholder="Tahun">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-4 col-form-label">UPTD dan SUP</label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-control" id="edit_uptd" name="UPTD" onchange="editOption()"
                                        required>
                                        <option>Pilih UPTD</option>
                                        @foreach ($uptd as $data)
                                            <option value="{{ 'uptd' . $data->id }}" id="uptd_{{ $data->id }}"
                                                @isset($kondisi_jalan)
                                                    {{ $kondisi_jalan->UPTD == 'uptd' . $data->id ? 'selected' : '' }}
                                                @endisset>
                                                {{ $data->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control edit_sup_select" name="SUP" id="edit_sup_select" required>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">Nama Ruas Jalan dan Kota Kabupaten</label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-7">
                                    <select class="form-control edit_ruas_select" name="RUAS_JALAN" id="edit_ruas_select"
                                        required></select>
                                </div>
                                <div class="col-md-5">
                                    <input name="KOTA_KAB" value="{{ @$kondisi_jalan->KOTA_KAB }}" type="text"
                                        class="form-control" required placeholder="Kota/Kab">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class=" form-group row">
                        <label class="col-md-4 col-form-label">KM Asal, Lokasi dan Panjang (m)</label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <select id="KM_ASAL" name="KM_ASAL" class="form-control" required>
                                        <option value="Bdg" @isset($kondisi_jalan)
                                        {{ 'Bdg' == $kondisi_jalan->KM_ASAL ? 'selected' : '' }} @endisset>
                                        BDG
                                    </option>
                                        <option value="Cn" @isset($kondisi_jalan)
                                            {{ 'Cn' == $kondisi_jalan->KM_ASAL ? 'selected' : '' }} @endisset>
                                            CN
                                        </option>
                                        <option value="Jkt" @isset($kondisi_jalan)
                                            {{ 'Jkt' == $kondisi_jalan->KM_ASAL ? 'selected' : '' }} @endisset>
                                            JKT
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <input id="LOKASI" name="LOKASI" value="{{ @$kondisi_jalan->LOKASI }}" type="text"
                                        class="form-control" required placeholder="Lokasi" readonly>
                                </div>
                                <div class="col-md-3">
                                    <input id="PANJANG" name="PANJANG" value="{{ @$kondisi_jalan->PANJANG }}" type="text"
                                        class="form-control" required placeholder="Panjang" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-4 col-form-label">KM Awal dan KM Akhir</label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-3">
                                    <input id="NO_KM_ASAL" name="NO_KM_ASAL" value="{{ @$kondisi_jalan->NO_KM_ASAL }}"
                                        type="number" min="0" class="form-control" required placeholder="KM Asal"
                                        onchange="onChangeLokasi()">
                                </div>
                                <div class="col-md-3">
                                    <input id="NO_M_ASAL" name="NO_M_ASAL" value="{{ @$kondisi_jalan->NO_M_ASAL }}"
                                        type="number" min="0" class="form-control" required placeholder="M Asal"
                                        onchange="onChangeLokasi()" max="999">
                                </div>
                                <div class="col-md-3">
                                    <input id="NO_KM_AKHIR" name="NO_KM_AKHIR" value="{{ @$kondisi_jalan->NO_KM_AKHIR }}"
                                        type="number" min="0" class="form-control" required placeholder="KM Akhir"
                                        onchange="onChangeLokasi()">
                                </div>
                                <div class="col-md-3">
                                    <input id="NO_M_AKHIR" name="NO_M_AKHIR" value="{{ @$kondisi_jalan->NO_M_AKHIR }}"
                                        type="number" min="0" class="form-control" required placeholder="M Akhir"
                                        onchange="onChangeLokasi()" max="999">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class=" form-group row">
                        <label class="col-md-4 col-form-label">Latitude dan Longitude Awal (Marker Biru)</label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <input id="lat0" name="LAT_AWAL" value="{{ @$kondisi_jalan->LAT_AWAL }}" type="text"
                                        class="form-control formatLatLong" required placeholder="Latitude Awal">
                                </div>
                                <div class=" col-md-6">
                                    <input id="long0" name="LONG_AWAL" value="{{ @$kondisi_jalan->LONG_AWAL }}"
                                        type="text" class="form-control formatLatLong" required
                                        placeholder="Longitude Awal">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-4 col-form-label">Latitude dan Longitude Akhir (Marker Hijau)</label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <input id="lat1" name="LAT_AKHIR" value="{{ @$kondisi_jalan->LAT_AKHIR }}"
                                        type="text" class="form-control formatLatLong" required
                                        placeholder="Latitude Akhir">
                                </div>
                                <div class="col-md-6">
                                    <input id="long1" name="LONG_AKHIR" value="{{ @$kondisi_jalan->LONG_AKHIR }}"
                                        type="text" class="form-control formatLatLong" required
                                        placeholder="Longitude Akhir">
                                </div>
                            </div>
                        </div>
                    </div>

                    <p>(Dipilih Bergantian) </p>
                    <div id="mapLatLong" class="full-map mb-3" style="height: 300px; width: 100%"></div>

                    <div class=" form-group row">
                        <label class="col-md-4 col-form-label">Luas, Sangat Baik, dan Baik (m2)</label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <input name="LUAS" value="{{ @$kondisi_jalan->LUAS }}" type="number"
                                        class="form-control" required placeholder="Luas">
                                </div>
                                <div class="col-md-4">
                                    <input name="SANGAT_BAIK" value="{{ @$kondisi_jalan->SANGAT_BAIK }}" type="number"
                                        class="form-control" required placeholder="Sangat Baik">
                                </div>
                                <div class="col-md-4">
                                    <input name="BAIK" value="{{ @$kondisi_jalan->BAIK }}" type="number"
                                        class="form-control" required placeholder="Baik">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-4 col-form-label">Sedang, Jelek, dan Parah (m2)</label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <input name="SEDANG" value="{{ @$kondisi_jalan->SEDANG }}" type="number"
                                        class="form-control" required placeholder="Sedang">
                                </div>
                                <div class="col-md-4">
                                    <input name="JELEK" value="{{ @$kondisi_jalan->JELEK }}" type="number"
                                        class="form-control" required placeholder="Jelek">
                                </div>
                                <div class="col-md-4">
                                    <input name="PARAH" value="{{ @$kondisi_jalan->PARAH }}" type="number"
                                        class="form-control" required placeholder="Parah">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-4 col-form-label">Sangat Parah dan Hancur (m2)</label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <input name="SANGAT_PARAH" value="{{ @$kondisi_jalan->SANGAT_PARAH }}" type="number"
                                        class="form-control" required placeholder="Sangat parah">
                                </div>
                                <div class="col-md-6">
                                    <input name="HANCUR" value="{{ @$kondisi_jalan->HANCUR }}" type="number"
                                        class="form-control" required placeholder="Hancur">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-4 col-form-label">Keterangan</label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12">
                                    <input name="KETERANGAN" value="{{ @$kondisi_jalan->KETERANGAN }}" type="text"
                                        class="form-control" placeholder="Keterangan" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" form-group row">
                        <a href="{{ route('kondisi_jalan.index') }}"><button type="button"
                                class="btn btn-default waves-effect">Batal</button></a>
                        <button type="submit" class="btn btn-primary waves-effect waves-light ml-2">Simpan</button>
                    </div>
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
        const panjang = $('#PANJANG');
        const lokasi = $('#LOKASI');
        const noKmAsal = $('#NO_KM_ASAL');
        const noMAsal = $('#NO_M_ASAL');
        const noKmAkhir = $('#NO_KM_AKHIR');
        const noMAkhir = $('#NO_M_AKHIR');

        function onChangeLokasi() {
            const kmAsal = noKmAsal.val() ? noKmAsal.val() : 0;
            const mAsal = noMAsal.val() ? noMAsal.val() : 0;
            const kmAkhir = noKmAkhir.val() ? noKmAkhir.val() : 0;
            const mAkhir = noMAkhir.val() ? noMAkhir.val() : 0;
            const awal = (Number(kmAsal) * 1000) + Number(mAsal);
            const akhir = (Number(kmAkhir) * 1000) + Number(mAkhir);
            panjang.val(akhir - awal);
            lokasi.val(`${kmAsal}+${mAsal} s/d ${kmAkhir}+${mAkhir}`);
        }

        function editOption() {
            const kondisi_jalan = @json(@$kondisi_jalan);
            let sup = "";
            let ruas_jalan = "";
            if (kondisi_jalan) {
                sup = kondisi_jalan.SUP;
                ruas_jalan = kondisi_jalan.RUAS_JALAN;
            }
            id = document.getElementById("edit_uptd").value;
            const baseUrl = `{{ url('admin/master-data/CCTV/getDataSUP/') }}/` + id.substring(4, 5);
            $.get(baseUrl, {
                    id: id
                },
                function(response) {
                    $('.edit_sup_select').empty();
                    for (let i = 0; i < response.sup.length; i++) {
                        $('.edit_sup_select').append(
                            `<option value="${response.sup[i].name}" class="sup" id="edit_sup_${i}" ${response.sup[i].name === sup ? "selected" :
                                                                                                                                                    "" }>${response.sup[i].name}</option>`
                        );
                    }
                });

            const baseUrl2 = `{{ url('admin/input-data/kondisi_jalan/get_ruas_jalan/') }}/` + id.substring(4, 5);

            $.get(baseUrl2, {
                    id: id.substring(4, 5)
                },
                function(response) {
                    $(".edit_ruas_select").select2("destroy").empty().select2({
                        theme: "bootstrap"
                    }).css("border-radius", 0);
                    for (let i = 0; i < response.ruas_jalan.length; i++) {
                        $('.edit_ruas_select').append(
                            `<option value="${response.ruas_jalan[i].nama_ruas_jalan}" class="RJ" id="edit_ruas_${i}" ${response.ruas_jalan[i].nama_ruas_jalan === ruas_jalan ? "selected" :
                                                                                                                                                    "" }>${response.ruas_jalan[i].nama_ruas_jalan}</option>`
                        );
                    }
                });
        }

        $(document).ready(() => {
            const uptd = document.getElementById("edit_uptd");
            if (uptd.length == 2) uptd.value = uptd[1].value;
            $('.edit_ruas_select').select2({
                theme: "bootstrap"
            }).css("border-radius", 0);
            editOption();
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
                        zoom: 9,
                    });

                    let tempGraphic = [];

                    if ($("#lat0").val() != undefined && $("#long0").val() !=
                        undefined) {
                        addTitik(0, $("#lat0").val(), $("#long0").val(), "blue");
                    }
                    if ($("#lat2").val() != undefined && $("#long2").val() !=
                        undefined) {
                        addTitik(1, $("#lat2").val(), $("#long2").val(), "green");
                    }

                    let mouseclick = 0;

                    view.on("click", function(event) {
                        const lat = event.mapPoint.latitude;
                        const long = event.mapPoint.longitude;

                        // Genap = Titik Awal
                        if (mouseclick % 2 == 0) {
                            addTitik(0, lat, long, "blue");
                            $("#lat0").val(lat);
                            $("#long0").val(long);
                        } else {
                            addTitik(1, lat, long, "green");
                            $("#lat1").val(lat);
                            $("#long1").val(long);
                        }
                        mouseclick++;
                    });

                    $("#lat0, #long0").keyup(function() {
                        const lat = $("#lat0").val();
                        const long = $("#long0").val();
                        addTitik(0, lat, long, "blue");
                    });
                    $("#lat1, #long1").keyup(function() {
                        const lat = $("#lat1").val();
                        const long = $("#long1").val();
                        addTitik(1, lat, long, "green");
                    });

                    function addTitik(point, lat, long, color) {
                        if ($("#lat" + point).val() != '' && $("#long" + point).val() != '') {
                            view.graphics.remove(tempGraphic[point]);
                        }

                        console.log(lat, long)
                        const graphic = new Graphic({
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
        })

    </script>
@endsection
