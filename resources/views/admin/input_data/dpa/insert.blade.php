@extends('admin.layout.index')

@section('title') DPA @endsection
@section('head')
<link rel="stylesheet" href="https://js.arcgis.com/4.18/esri/themes/light/main.css">
{{-- <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer>
</script> --}}
@endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>DPA</h4>
                <span>DPA DBMPR Jabar</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('dpa.index') }}">DPA</a>
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
                <h5>Tambah Data DPA</h5>
                @else
                <h5>Perbaharui Data DPA</h5>
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
                <form action="{{ route('dpa.store') }}" method="post">
                    @else
                    <form action="{{ route('dpa.update', $dpa->no_urut) }}" method="post">
                        @method('PUT')
                        @endif
                        @csrf
                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Nama Paket</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="nama_paket" value="{{@$dpa->nama_paket}}" type="text"
                                            class="form-control"  required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Kategori</label></label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <select class="form-control" id="edit_uptd" name="kategori" required>
                                            @foreach ($kategori as $data)
                                            <option value="{{$data->id }}" id="{{ $data->id }}"
                                                @isset($dpa)
                                                {{ $dpa->kategori == $data->id ? 'selected' : '' }} @endisset>
                                                {{ $data->nama_kategori }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">UPTD</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <select class="form-control" id="edit_uptd" name="uptd" required>
                                            @foreach ($uptd_dpa as $data)
                                            <option value="{{ $data->id }}" id="{{ $data->id }}"
                                                @isset($dpa)
                                                {{ $dpa->uptd == $data->id ? 'selected' : '' }} @endisset>
                                                {{ $data->nama_uptd }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Tahun Anggaran</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="tahun_anggaran" value="{{@$dpa->tahun_anggaran}}"  type="number" class="form-control"
                                            min="1971" max="9999" required>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Pagu Anggaran (Rp)</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="pagu_anggaran" value="{{@$dpa->pagu_anggaran}}"  type="number" class="form-control"
                                         required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Pagu Anggaran DPA Pergeseran (Rp)</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="pagu_anggaran_dpa_pergeseran" value="{{@$dpa->pagu_anggaran_dpa_pergeseran}}"  type="number" class="form-control"
                                         required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Check</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <select class="form-control" name="check" required>
                                            @foreach ($check as $data)
                                            <option value="{{ $data->id }}" id="{{ $data->id }}"
                                                @isset($dpa)
                                                {{ $dpa->check == $data->id ? 'selected' : '' }} @endisset>
                                                {{ $data->nama_check }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Pendanaan</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <select class="form-control" name="pendanaan" required>
                                            @foreach ($pendanaan as $data)
                                            <option value="{{ $data->id }}" id="{{ $data->id }}"
                                                @isset($dpa)
                                                {{ $dpa->pendanaan == $data->id ? 'selected' : '' }} @endisset>
                                                {{ $data->nama_pendanaan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Jenis Pengadaan</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <select class="form-control" name="jenis_pengadaan" required>
                                            @foreach ($pengadaan as $data)
                                            <option value="{{ $data->id }}" id="{{ $data->id }}"
                                                @isset($dpa)
                                                {{ $dpa->jenis_pengadaan == $data->id ? 'selected' : '' }} @endisset>
                                                {{ $data->nama_pengadaan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Ruas Jalan</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <select class="form-control searchableField" name="kode_ruas_jalan" required>
                                            <option>Pilih Ruas Jalan</option>
                                            @foreach ($ruas_jalan as $data)
                                            <option value="{{ $data->id_ruas_jalan }}"
                                                @isset($dpa)
                                                {{ $dpa->kode_ruas_jalan == $data->id_ruas_jalan ? 'selected' : '' }} @endisset>
                                                {{ $data->nama_ruas_jalan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <a href="{{ route('dpa.index') }}"><button type="button"
                                    class="btn btn-default waves-effect">Batal</button></a>
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
<script src="https://js.arcgis.com/4.18/"></script>

<script>
    $(document).ready(() => {
        const progressBefore = `{{ @$dpa->progress }}`

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
                    if ($("#lat1").val() != undefined && $("#long1").val() !=
                        undefined) {
                        addTitik(1, $("#lat1").val(), $("#long1").val(), "green");
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
