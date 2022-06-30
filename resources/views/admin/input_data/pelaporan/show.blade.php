@extends('admin.layout.index')

@section('title') Edit pelaporan @endsection
@section('head')
<link rel="stylesheet" href="https://js.arcgis.com/4.24/esri/themes/light/main.css">
<script src="https://js.arcgis.com/4.24/"></script>
<style>
   
    #viewDiv {
        padding: 0;
        margin: 0;
        height: 100%;
        width: 100%;
      }
</style>


<script>
    var lapor = {!! json_encode($pelaporan->toArray()) !!};
 
    require([
      "esri/config",
      "esri/Map",
      "esri/views/MapView",

      "esri/widgets/BasemapToggle",
      "esri/widgets/BasemapGallery",
      "esri/Graphic",
      "esri/layers/GraphicsLayer"

    ], function(esriConfig, Map, MapView, BasemapToggle, BasemapGallery, Graphic, GraphicsLayer) {

        esriConfig.apiKey = "AAPK2021e3c0ade243ac91fc03c5cc16af553UoLz7PP3cuznJsJw2hQOU6G-m47W2PWSfHujOs9JYI-UmZOtUw7TvgwWHUSIDPI";

        const map = new Map({
            basemap: "arcgis-topographic"
        });

        const view = new MapView({
            container: "viewDiv",
            map: map,
            center: [lapor.long, lapor.lat],
            zoom: 9
        });

        const basemapToggle = new BasemapToggle({
            view: view,
            nextBasemap: "arcgis-imagery"
        });

        view.ui.add(basemapToggle,"bottom-right");

        const basemapGallery = new BasemapGallery({
            view: view,
            source: {
            query: {
                title: '"World Basemaps for Developers" AND owner:esri'
            }
            }
        });

        view.ui.add(basemapGallery, "top-right"); // Add to the view
        
        const graphicsLayer = new GraphicsLayer();
        map.add(graphicsLayer);
        const point = { //Create a point
            type: "point",
            longitude: lapor.long,
            latitude: lapor.lat
        };
        const simpleMarkerSymbol = {
            type: "simple-marker",
            color: [226, 119, 40],  // Orange
            outline: {
                color: [255, 255, 255], // White
                width: 1
            }
        };
        const pointGraphic = new Graphic({
            geometry: point,
            symbol: simpleMarkerSymbol
        });
        graphicsLayer.add(pointGraphic);

    });

</script>

{{-- <script src="http://maps.googleapis.com/maps/api/js"></script>
<script>
    function initialize() {
        var lapor = {!! json_encode($pelaporan->toArray()) !!};
        console.log(lapor);
    var propertiPeta = {
        center:new google.maps.LatLng(lapor.lat,lapor.long),
        zoom:9,
        mapTypeId:google.maps.MapTypeId.ROADMAP
    };
    
    var peta = new google.maps.Map(document.getElementById("googleMap"), propertiPeta);
    
    // membuat Marker
    var marker=new google.maps.Marker({
        position: new google.maps.LatLng(lapor.lat,lapor.long),
        map: peta,
        animation: google.maps.Animation.BOUNCE
    });

    }

    // event jendela di-load  
    google.maps.event.addDomListener(window, 'load', initialize);
</script> --}}
@endsection
@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Laporan Rumija UPTD {{ Str::upper($pelaporan->uptd_id) }}</h4>
                {{-- <span>Data Seluruh Rumija</span> --}}
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
                <li class="breadcrumb-item"><a href="/admin">Rumija</a></li>
                <li class="breadcrumb-item"><a href="#!">Pelaporan Rumija</a></li>

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
                <h5>{{@$pelaporan->data_sup->name}}</h5>
                <br>
                <h5>{{@$pelaporan->ruas->nama_ruas_jalan}}</h5>
                <div class="card-header-right">
                    <i class="feather icon-maximize full-card"></i>
                    <i class="feather icon-minus minimize-card"></i>
                </div>
            </div>
            <div class="card-block">

                <div class="row">
                    <div class="col-md-6">
                        <label class="col-md-12 col-form-label"><b>Keterangan</b></label>
                        <hr>
                        <label class="col-md-12 col-form-label">{{$pelaporan->keterangan}}</label>
                    </div>
                    <div class="col-md-6">
                        <label class="col-md-12 col-form-label"><b>Tipe Laporan</b></label>
                        <hr>
                        <label class="col-md-12 col-form-label">{{$pelaporan->rumija_tipe->name}}</label>
                    </div>
                </div>
                 
                <div class="form-group row">
                    <label class="col-md-12 col-form-label text-center">Dokumentasi</label>
                    <img style="max-height: 400px;" class="img-thumbnail rounded mx-auto d-block" src="{{ Storage::url('public/laporan_rumija/'.$pelaporan->image) }}" alt="">
                    @if ($pelaporan->video)
                    <video style="max-height: 400px;" controls src="{{ Storage::url('public/laporan_rumija/'.$pelaporan->video) }}">
                        Your browser does not support the video tag.
                    </video>
                        
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="col-md-6 col-form-label"><b>Koordinat X</b></label>
                        <hr>
                        <label class="col-md-12 col-form-label">{{$pelaporan->lat}}</label>
                    </div>
                    <div class="col-md-6">
                        <label class="col-md-6 col-form-label"><b>Koordinat Y</b></label>
                        <hr>
                        <label class="col-md-12 col-form-label">{{$pelaporan->long}}</label>
                    </div>
                </div>
                {{-- <div id="googleMap" style="width:100%;height:380px;"></div> --}}
                <div id="viewDiv" style="width:100%;height:380px;"></div>

            </div>
        </div>
     
        <a href="{{ url()->previous() }}"><button type="button" class="btn btn-danger waves-effect " data-dismiss="modal">Kembali</button></a>

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
    });

    function ubahOption() {

        //untuk select Ruas
        id = document.getElementById("uptd").value
        url = "{{ url('admin/input-data/kondisi-jalan/getRuasJalan') }}"
        id_select = '#ruas_jalan'
        text = 'Pilih Ruas Jalan'
        option = 'nama_ruas_jalan'

        setDataSelect(id, url, id_select, text, option, option)

        //untuk select SUP
        url = "{{ url('admin/master-data/ruas-jalan/getSUP') }}"
        id_select = '#sup'
        text = 'Pilih SUP'
        option = 'name'

        setDataSelect(id, url, id_select, text, option, option)
    }
   

</script>
@endsection
