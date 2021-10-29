@extends('admin.layout.index')

@section('title') Rumija @endsection
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
                <h4>Pemanfaatan Rumija</h4>
                <span>Rumija DBMPR Jabar</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('rumija.index') }}">Pemanfaatan Rumija</a>
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
                <h5>Tambah Data Pemanfaatan Rumija</h5>
                @else
                <h5>Perbaharui Data Pemanfaatan Rumija</h5>
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
                <form action="{{ route('rumija.store') }}" method="post" enctype="multipart/form-data">
                    @else
                    <form action="{{ route('rumija.update', $rumija->id) }}" method="post"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @endif
                        @csrf
                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Nama</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="nama" value="{{@$rumija->nama}}" type="text" class="form-control"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Alamat</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="alamat" value="{{@$rumija->alamat}}" type="text"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">No. Ijin</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="no_ijin" value="{{@$rumija->no_ijin}}" type="number"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Tanggal Ijin</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="tanggal_ijin" value="{{@$rumija->tanggal_ijin}}" type="date"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Ruas Jalan</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <select class="form-control searchableField" name="ruas_jalan" required>
                                            <option>Pilih Ruas Jalan</option>
                                            @foreach ($ruas_jalan as $data)
                                            <option value="{{ $data->nama_ruas_jalan }}" @isset($rumija)
                                                {{ $rumija->ruas_jalan == $data->nama_ruas_jalan ? 'selected' : '' }}
                                                @endisset>
                                                {{ $data->nama_ruas_jalan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">KM</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="km" value="{{@$rumija->km}}" type="text" placeholder=""
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Kab/Kota</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <select class="form-control searchableField" name="kab_kota" required>
                                            <option>Pilih Kab/Kota</option>
                                            @foreach ($kab_kota as $data)
                                            <option value="{{ $data->name }}" @isset($rumija)
                                                {{ $rumija->kab_kota == $data->name ? 'selected' : '' }} @endisset>
                                                {{ $data->name }}</option>
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
                                        <select class="form-control searchableField" id="edit_uptd" name="uptd"
                                            required>
                                            @foreach ($uptd as $data)
                                            <option value="{{ $data->id }}" id="{{ $data->id }}" @isset($rumija)
                                                {{ $rumija->uptd == $data->id ? 'selected' : '' }} @endisset>
                                                {{ $data->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Luas (m<sup>2</sup>)</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input pattern="([0-9]+.{0,1}[0-9]*,{0,1})*[0-9]" required name="luas"
                                            value="{{@$rumija->luas}}" type="number" class="form-control" min="0"
                                            step="0.01">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Jenis Penggunaan</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="jenis_penggunaan" value="{{@$rumija->jenis_penggunaan}}"
                                            type="text" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Uraian</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <textarea rows="3" name="uraian" value="{{@$rumija->uraian}}"
                                            class="form-control" required>{{@$rumija->uraian}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Foto 1</label>
                            <div class="col-md-5">
                                <img style="min-height: 40px" class="mx-auto rounded img-thumbnail d-block"
                                    id="foto_preview" src="{{ url('storage/' . @$rumija->foto) }}" alt="">
                            </div>
                            <div class="col-md-5">
                                <input id="foto" name="foto" type="file" accept="image/*" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Foto 2</label>
                            <div class="col-md-5">
                                <img style="min-height: 40px" class="mx-auto rounded img-thumbnail d-block"
                                    id="foto_preview_1" src="{{ url('storage/' . @$rumija->foto_1) }}" alt="">
                            </div>
                            <div class="col-md-5">
                                <input id="foto_1" name="foto_1" type="file" accept="image/*" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Foto 3</label>
                            <div class="col-md-5">
                                <img style="min-height: 40px" class="mx-auto rounded img-thumbnail d-block"
                                    id="foto_preview_2" src="{{ url('storage/' . @$rumija->foto_2) }}" alt="">
                            </div>
                            <div class="col-md-5">
                                <input id="foto_2" name="foto_2" type="file" accept="image/*" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Video</label>
                            <div class="col-md-5">
                                <video class="mx-auto rounded img-thumbnail d-block" id="video_preview"
                                    src="{{ url('storage/' . @$rumija->video) }}" alt="" controls>
                            </div>
                            <div class="col-md-5">
                                <input id="video" name="video" type="file" accept="video/mp4" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Video (link Youtube)</label>
                            <div class="col-md-5">
                                <div id="video_yt_frame_container"
                                    style="min-height: 40px;@if(@$rumija->video_yt) height:350px @endif"
                                    class="border embed-responsive">
                                    <iframe id="video_yt_frame" class="embed-responsive-item"
                                        src="{{@$rumija->video_yt}}"></iframe>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <input id="video_yt_input" name="video_yt" value="{{@$rumija->video_yt}}" type="text"
                                    class="form-control"
                                    placeholder="https://www.youtube.com/embed/ZrkHsRb3xI0?controls=0">
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Koordinat X</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="lat" id="lat" type="text" class="form-control formatLatLong"
                                            required value="{{@$rumija->lat}}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-4 col-form-label">Koordinat Y</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="lng" id="long" type="text" class="form-control formatLatLong"
                                            value="{{@$rumija->lng}}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="mapLatLong" class="mb-2 full-map" style="height: 300px; width: 100%"></div>

                        <div class=" form-group row">
                            <a href="{{ route('rumija.index') }}"><button type="button"
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
    $(document).ready(function() {
        const ytFrameContainer = document.getElementById('video_yt_frame_container')
        const ytFrame = document.getElementById('video_yt_frame')
        const ytInput = document.getElementById('video_yt_input')
        ytInput.onchange = (event) => {
            if(String(event.target.value).includes('embed')){
            ytFrame.src = event.target.value
            ytFrameContainer.style.height = '350px'}
            else {
                ytFrame.src = null
            ytFrameContainer.style.height = '40px'
            }
        }

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
                    if ($("#lat").val() != '' && $("#long").val() != '') {
                        var graphic = new Graphic({
                            geometry:{
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
                        }
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
