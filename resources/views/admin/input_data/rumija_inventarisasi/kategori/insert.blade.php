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
                <h4>Rumija</h4>
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
                <li class="breadcrumb-item"><a href="{{ route('rumija.index') }}">Rumija</a>
                </li>
                <li class="breadcrumb-item"><a href="#">Kategori Inventaris</a> </li>
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
                <h5>Tambah Kategori Inventaris</h5>
                @else
                <h5>Perbaharui Kategori Inventaris</h5>
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
                <form action="{{ route('rumija.inventarisasi.kategori.store') }}" method="post" enctype="multipart/form-data">
                @else
                <form action="{{ route('rumija.inventarisasi.kategori.update', $data->id) }}" method="post"
                    enctype="multipart/form-data">
                    @method('PUT')
                @endif
                    @csrf
                    <div class=" form-group row">
                        <label class="col-md col-form-label">Nama</label>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <input name="name" value="{{@$data->name}}" type="text" class="form-control"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>

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
                        basemap: "streets"
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
