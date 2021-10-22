@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection
@section('head')
<link rel="stylesheet" href="https://js.arcgis.com/4.17/esri/themes/light/main.css">
@endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Executive Dashboard</h4>
             </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Survey Kondisi Jalan</a> </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('page-body')
<style>

#controls {
  overflow: hidden;
  padding-bottom: 3px;
}
</style>

<div class="row">
<div class="col-lg-12">
   <div class="card">
        <div class="card-block accordion-block">
            <div id="accordion" role="tablist" aria-multiselectable="true">
                <div class="accordion-panel">
                    <div class="accordion-heading" role="tab" id="headingOne">
                        <h3 class="card-title accordion-title">
                            <a class="accordion-msg" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Filter
                            </a>
                        </h3>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                        <div class="accordion-content accordion-desc">
                            <div class="card-block">
                                <div class="row">
                                    <div class="col-sm-12 col-xl-3 m-b-30">
                                        <h4 class="sub-title">UPTD</h4>
                                        <select name="select" id="uptd" class="form-control form-control-primary">
                                            <option value="">Semua</option>
                                            <option value="uptd1">UPTD 1</option>
                                            <option value="uptd2">UPTD 2 </option>
                                            <option value="uptd3">UPTD 3 </option>
                                            <option value="uptd4">UPTD 4 </option>
                                            <option value="uptd5">UPTD 5 </option>
                                            <option value="uptd6">UPTD 6  </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-xl-3 m-b-30">
                                        <h4 class="sub-title">SPP</h4>
                                        <select name="select" class="form-control form-control-primary">
                                            <option value="opt1">-</option>
                                            <option value="opt2">SPP KAB/KOTA SMI-1</option>
                                            <option value="opt3">SPP KAB SMI-2</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-xl-3 m-b-30">
                                        <h4 class="sub-title">Kegiatan</h4>
                                        <select name="select" class="form-control form-control-primary">
                                            <option value="opt1">Semua</option>
                                            <option value="opt2">Ruas Jalam</option>
                                            <option value="opt2">Jembatan</option>
                                            <option value="pembangunan">Pemeliharaan</option>
                                            <option value="pembangunan">Peningkatan</option>
                                            <option value="pembangunan">Pembangunan</option>
                                            <option value="peningkatan">Peningkatan</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-xl-3 m-b-30">
                                        <h4 class="sub-title">Proyek Kontrak</h4>
                                        <select name="select" class="form-control form-control-primary">
                                        <option value="opt1">Semua</option>
                                            <option value="opt1">On-Progress</option>
                                            <option value="opt2">Critical Contract</option>
                                            <option value="opt2">Off Progress</option>
                                            <option value="pembangunan">Finish</option>

                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="col-xl-12 col-md-12 mb-4">
      <a href="{{ url('admin/map-dashboard') }}">
        <button class="btn btn-primary">Lihat Dalam Mode Pengamatan
        <i class="feather icon-airplay"></i>
        </button>
      </a>
    </div>
    <div class="col-xl-12 col-md-12">
        <div class="card">
            <div class="card-header" style="padding-bottom: 3px">
                <h5>Maps</h5>
                <span class="text-muted">  </span>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                        <li><i class="feather icon-trash-2 close-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                <div id="viewDiv" style="width:100%;height:600px;padding: 0;margin: 0;"></div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
{{-- <script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDBSpJ4v4aOY7DEg4QAIwcSFCXljmPJFUg&callback=initMap">
</script> --}}
{{-- <script>
    var points = [
        ['UPTD 1', -6.806124, 107.145195, 12, `{{ route('kondisiJalanUPTD','uptd1') }}`],
        ['UPTD 2', -6.930913, 106.937304, 11, `{{ route('kondisiJalanUPTD','uptd2') }}`],
        ['UPTD 3', -6.9147444,107.6098111, 10, `{{ route('kondisiJalanUPTD','uptd3') }}`],
        ['UPTD 4', -6.953272, 107.942720, 9, `{{ route('kondisiJalanUPTD','uptd4') }}`],
        ['UPTD 5', -7.461755, 108.386391, 8, `{{ route('kondisiJalanUPTD','uptd5') }}`],
        ['UPTD 6', -6.741323, 108.364521, 7, `{{ route('kondisiJalanUPTD','uptd6') }}`]
    ];

    // function setMarkers(map, locations) {
    //     var shape = {
    //         coord: [1, 1, 1, 20, 18, 20, 18 , 1],
    //         type: 'poly'
    //     };

    //     for (var i = 0; i < locations.length; i++) {
    //         // var flag = new google.maps.MarkerImage('markers/' + (i + 1) + '.png',
    //         // new google.maps.Size(17, 19),
    //         // new google.maps.Point(0,0),
    //         // new google.maps.Point(0, 19));

    //         var place = locations[i];
    //         var myLatLng = new google.maps.LatLng(place[1], place[2]);
    //         var marker = new google.maps.Marker({
    //             position: myLatLng,
    //             map: map,
    //             title: place[0],
    //             zIndex: place[3],
    //             url: place[4]
    //         });
    //         google.maps.event.addListener(marker, 'click', function() {
    //             window.location.href = this.url;
    //         });
    //     }
    // }

    // function initMap() {
    //     var options = {
    //         center:new google.maps.LatLng(-6.9032739,107.5731165),
    //         zoom:9,
    //         mapTypeId:google.maps.MapTypeId.ROADMAP
    //     };
    //     var map = new google.maps.Map(document.getElementById("googleMap"),options);
    //     setMarkers(map, points);
    // }
        // event jendela di-load
        //google.maps.event.addDomListener(window, 'load', initialize);
</script> --}}
<script src="https://js.arcgis.com/4.17/"></script>
<script>
$(document).ready(function () {

    function getMapData(uptd){
        require([
            "esri/Map",
            "esri/views/MapView",
            "esri/request",
            "esri/geometry/Point",
            "esri/Graphic",
            "esri/layers/GraphicsLayer",
            "esri/layers/GroupLayer",
            "esri/tasks/RouteTask",
            "esri/tasks/support/RouteParameters",
            "esri/tasks/support/FeatureSet"
        ], function (Map, MapView, esriRequest, Point, Graphic, GraphicsLayer,
                    GroupLayer, RouteTask, RouteParameters, FeatureSet) {
        const baseUrl = "{{url('/')}}";
        const map = new Map({
            basemap: "hybrid"
        });

        const view = new MapView({
            container: "viewDiv",
            map: map,
            center: [107.6191, -6.9175], // longitude, latitude
            zoom: 8
        });

        const jembatanLayer = new GraphicsLayer();
        const pembangunanLayer = new GraphicsLayer();
        const ruasjalanLayer = new GraphicsLayer();
        const peningkatanLayer = new GraphicsLayer();
        const ruteLayer = new GraphicsLayer();
        const rehabilitasiLayer = new GraphicsLayer();
        const routeTask = new RouteTask({
            url: "https://utility.arcgis.com/usrsvcs/appservices/AzkCUV7fdmgx72RP/rest/services/World/Route/NAServer/Route_World/solve"
            // url: "https://route.arcgis.com/arcgis/rest/services/World/Route/NAServer/Route_World"
        });

        //ruas jalan
        const urlRuasjalan = baseUrl + "/api/ruas-jalan";
        const requestRuasjalan = esriRequest(urlRuasjalan, {
            responseType: "json"
        }).then(function (response) {

            var json = response.data;
            var data =  json.data;

            var symbol = {
                type: "picture-marker",  // autocasts as new PictureMarkerSymbol()
                url: baseUrl + "/assets/images/marker/jalan.png",
                width: "24px",
                height: "24px"
            };
            var popupTemplate = {
                title: "{NAMA_JALAN}",
                content: [{
                type: "fields",
                fieldInfos: [
                    {
                    fieldName: "LAT_AWAL",
                    label: "Latitude 0"
                    },
                    {
                    fieldName: "LONG_AWAL",
                    label: "Longitude 0"
                    },
                    {
                    fieldName: "LAT_AKHIR",
                    label: "Latitude 1"
                    },
                    {
                    fieldName: "LONG_AKHIR",
                    label: "Longitude 1"
                    },
                    {
                    fieldName: "SUP",
                    label: "SUP"
                    },
                    {
                    fieldName: "UPTD",
                    label: "UPTD"
                    }
                ]}
            ]};

            data.forEach(item => {
            if(item.UPTD === uptd) {

                var pointAwal = new Point(item.LONG_AWAL, item.LAT_AWAL);
                var pointAkhir = new Point(item.LONG_AKHIR, item.LAT_AKHIR);
                var markerAwal = new Graphic({
                    geometry: pointAwal,
                    symbol: symbol,
                    attributes: item,
                    popupTemplate: popupTemplate
                });
                var markerAkhir = new Graphic({
                    geometry: pointAkhir,
                    symbol: symbol,
                    attributes: item,
                    popupTemplate: popupTemplate
                });

                ruasjalanLayer.graphics.add(markerAwal);
                ruasjalanLayer.graphics.add(markerAkhir);

                var featureSet = new FeatureSet({
                    features: [markerAwal, markerAkhir]
                });
                var routeParams = new RouteParameters({
                    stops: featureSet
                });
                routeTask.solve(routeParams).then(function(data) {
                    data.routeResults.forEach(function(result) {
                        result.route.symbol = {
                            type: "simple-line",
                            color: [5, 150, 255],
                            width: 3
                        };
                        result.route.attributes = item;
                        result.route.popupTemplate = popupTemplate;
                        ruasjalanLayer.graphics.add(result.route);
                    });
                });
            }
            });

        }).catch(function (error) {
            console.log(error);
        });


        // Pembangunan --> Pembangunan
        const urlPembangunan = baseUrl + "/api/pembangunan/category/pb";
        const requestPembangunan = esriRequest(urlPembangunan, {
            responseType: "json",
        }).then(function(response){
            var json = response.data;
            var data = json.data;

            var symbol = {
                type: "picture-marker",  // autocasts as new PictureMarkerSymbol()
                url: baseUrl + "/assets/images/marker/pembangunan.png",
                width: "24px",
                height: "24px"
            };
            var popupTemplate = {
                title: "{NAMA_PAKET}",
                content: [{
                type: "fields",
                fieldInfos: [
                    {
                    fieldName: "NOMOR_KONTRAK",
                    label: "Nomor Kontrak"
                    },
                    {
                    fieldName: "TGL_KONTRAK",
                    label: "Tanggal Kontrak"
                    },
                    {
                    fieldName: "WAKTU_PELAKSANAAN_HK",
                    label: "Waktu Kontrak (Hari Kerja)"
                    },
                    {
                    fieldName: "KEGIATAN",
                    label: "Jenis Pekerjaan"
                    },
                    {
                    fieldName: "JENIS_PENANGANAN",
                    label: "Jenis Penanganan"
                    },
                    {
                    fieldName: "RUAS_JALAN",
                    label: "Ruas Jalan"
                    },
                    {
                    fieldName: "LAT",
                    label: "Latitude"
                    },
                    {
                    fieldName: "LNG",
                    label: "Longitude"
                    },
                    {
                    fieldName: "LOKASI",
                    label: "Lokasi"
                    },
                    {
                    fieldName: "SUP",
                    label: "SUP"
                    },
                    {
                    fieldName: "NILAI_KONTRAK",
                    label: "Nilai Kontrak"
                    },
                    {
                    fieldName: "PAGU_ANGGARAN",
                    label: "Pagu Anggaran"
                    },
                    {
                    fieldName: "PENYEDIA_JASA",
                    label: "Penyedia Jasa"
                    },
                    {
                    fieldName: "UPTD",
                    label: "UPTD"
                    }
                ]}
            ]};


            data.forEach(item => {
            if(uptd!==""){
                if(  item.UPTD === uptd) {
                var point = new Point(item.LNG, item.LAT);
                pembangunanLayer.graphics.add(new Graphic({
                    geometry: point,
                    symbol: symbol,
                    attributes: item,
                    popupTemplate: popupTemplate
                }));
            }} else{
                var point = new Point(item.LNG, item.LAT);
                pembangunanLayer.graphics.add(new Graphic({
                    geometry: point,
                    symbol: symbol,
                    attributes: item,
                    popupTemplate: popupTemplate
                }));
            }
            });

        }).catch(function (error) {
            console.log(error);
        });


        // Pembangunan --> Peningkatan
        const urlPeningkatan = baseUrl + "/api/pembangunan/category/pn";
        const requestPeningkatan = esriRequest(urlPeningkatan, {
            responseType: "json",
        }).then(function(response){
            var json = response.data;
            var data = json.data;

            var symbol = {
                type: "picture-marker",  // autocasts as new PictureMarkerSymbol()
                url: baseUrl + "/assets/images/marker/peningkatan.png",
                width: "24px",
                height: "24px"
            };
            var popupTemplate = {
                title: "{NAMA_PAKET}",
                content: [{
                type: "fields",
                fieldInfos: [
                    {
                    fieldName: "NOMOR_KONTRAK",
                    label: "Nomor Kontrak"
                    },
                    {
                    fieldName: "TGL_KONTRAK",
                    label: "Tanggal Kontrak"
                    },
                    {
                    fieldName: "WAKTU_PELAKSANAAN_HK",
                    label: "Waktu Kontrak (Hari Kerja)"
                    },
                    {
                    fieldName: "KEGIATAN",
                    label: "Jenis Pekerjaan"
                    },
                    {
                    fieldName: "JENIS_PENANGANAN",
                    label: "Jenis Penanganan"
                    },
                    {
                    fieldName: "RUAS_JALAN",
                    label: "Ruas Jalan"
                    },
                    {
                    fieldName: "LAT",
                    label: "Latitude"
                    },
                    {
                    fieldName: "LNG",
                    label: "Longitude"
                    },
                    {
                    fieldName: "LOKASI",
                    label: "Lokasi"
                    },
                    {
                    fieldName: "SUP",
                    label: "SUP"
                    },
                    {
                    fieldName: "NILAI_KONTRAK",
                    label: "Nilai Kontrak"
                    },
                    {
                    fieldName: "PAGU_ANGGARAN",
                    label: "Pagu Anggaran"
                    },
                    {
                    fieldName: "PENYEDIA_JASA",
                    label: "Penyedia Jasa"
                    },
                    {
                    fieldName: "UPTD",
                    label: "UPTD"
                    }
                ]}
            ]};

            data.forEach(item => {
            if(uptd!==""){
                if(  item.UPTD === uptd) {
                var point = new Point(item.LNG, item.LAT);
                peningkatanLayer.graphics.add(new Graphic({
                    geometry: point,
                    symbol: symbol,
                    attributes: item,
                    popupTemplate: popupTemplate
                }));
                }
            }else{
                var point = new Point(item.LNG, item.LAT);
                peningkatanLayer.graphics.add(new Graphic({
                    geometry: point,
                    symbol: symbol,
                    attributes: item,
                    popupTemplate: popupTemplate
                }));

            }
            });
        }).catch(function (error) {
            console.log(error);
        });

        // Pembangunan --> Rehabilitasi
        const urlRehabilitasi = baseUrl + "/api/pembangunan/category/rb";
        const requestRehabilitasi = esriRequest(urlRehabilitasi, {
            responseType: "json",
        }).then(function(response){
            var json = response.data;
            var data = json.data;

            var symbol = {
                type: "picture-marker",  // autocasts as new PictureMarkerSymbol()
                url: baseUrl + "/assets/images/marker/rehabilitasi.png",
                width: "24px",
                height: "24px"
            };
            var popupTemplate = {
                title: "{NAMA_PAKET}",
                content: [{
                type: "fields",
                fieldInfos: [
                    {
                    fieldName: "NOMOR_KONTRAK",
                    label: "Nomor Kontrak"
                    },
                    {
                    fieldName: "TGL_KONTRAK",
                    label: "Tanggal Kontrak"
                    },
                    {
                    fieldName: "WAKTU_PELAKSANAAN_HK",
                    label: "Waktu Kontrak (Hari Kerja)"
                    },
                    {
                    fieldName: "KEGIATAN",
                    label: "Jenis Pekerjaan"
                    },
                    {
                    fieldName: "JENIS_PENANGANAN",
                    label: "Jenis Penanganan"
                    },
                    {
                    fieldName: "RUAS_JALAN",
                    label: "Ruas Jalan"
                    },
                    {
                    fieldName: "LAT",
                    label: "Latitude"
                    },
                    {
                    fieldName: "LNG",
                    label: "Longitude"
                    },
                    {
                    fieldName: "LOKASI",
                    label: "Lokasi"
                    },
                    {
                    fieldName: "SUP",
                    label: "SUP"
                    },
                    {
                    fieldName: "NILAI_KONTRAK",
                    label: "Nilai Kontrak"
                    },
                    {
                    fieldName: "PAGU_ANGGARAN",
                    label: "Pagu Anggaran"
                    },
                    {
                    fieldName: "PENYEDIA_JASA",
                    label: "Penyedia Jasa"
                    },
                    {
                    fieldName: "UPTD",
                    label: "UPTD"
                    }
                ]}
            ]};


            data.forEach(item => {
            if(uptd!=="") {
                if(  item.UPTD === uptd)  {
                var point = new Point(item.LNG, item.LAT);
                rehabilitasiLayer.graphics.add(new Graphic({
                    geometry: point,
                    symbol: symbol,
                    attributes: item,
                    popupTemplate: popupTemplate
                }));
                }
                }  else{
                var point = new Point(item.LNG, item.LAT);
                rehabilitasiLayer.graphics.add(new Graphic({
                    geometry: point,
                    symbol: symbol,
                    attributes: item,
                    popupTemplate: popupTemplate
                }));
                }
            });
        }).catch(function (error) {
            console.log(error);
        });


        // Jembatan
        const urlJembatan = baseUrl + "/api/jembatan";
        const requestJembatan = esriRequest(urlJembatan, {
            responseType: "json"
        }).then(function (response) {

            var json = response.data;
            var data = json.data;

            var symbol = {
                type: "picture-marker",  // autocasts as new PictureMarkerSymbol()
                url: baseUrl + "/assets/images/marker/jembatan.png",
                width: "24px",
                height: "24px"
            };
            var popupTemplate = {
                title: "{NAMA_JEMBATAN}",
                content: [{
                type: "fields",
                fieldInfos: [
                    {
                    fieldName: "PANJANG",
                    label: "Panjang"
                    },
                    {
                    fieldName: "LEBAR",
                    label: "Lebar"
                    },
                    {
                    fieldName: "RUAS_JALAN",
                    label: "Ruas Jalan"
                    },
                    {
                    fieldName: "LAT",
                    label: "Latitude"
                    },
                    {
                    fieldName: "LNG",
                    label: "Longitude"
                    },
                    {
                    fieldName: "LOKASI",
                    label: "Lokasi"
                    },
                    {
                    fieldName: "SUP",
                    label: "SUP"
                    },
                    {
                    fieldName: "UPTD",
                    label: "UPTD"
                    }
                ]}
            ]};

            data.forEach(item => {
            if(uptd!==""){
                if(  item.UPTD === uptd) {
                var point = new Point(item.LNG, item.LAT);
                jembatanLayer.graphics.add(new Graphic({
                    geometry: point,
                    symbol: symbol,
                    attributes: item,
                    popupTemplate: popupTemplate
                }));
                }}else{
                var point = new Point(item.LNG, item.LAT);
                jembatanLayer.graphics.add(new Graphic({
                    geometry: point,
                    symbol: symbol,
                    attributes: item,
                    popupTemplate: popupTemplate
                }));
                }
            });

        }).catch(function (error) {
            console.log(error);
        });

        const groupLayer = new GroupLayer();


        groupLayer.add(ruasjalanLayer);
        groupLayer.add(pembangunanLayer);
        groupLayer.add(peningkatanLayer);
        groupLayer.add(rehabilitasiLayer);
        groupLayer.add(jembatanLayer);

        map.add(groupLayer);
        });

        $(document).ready(function(){
            $("#uptd").change(function(){
                var uptd = this.value;
                getMapData(uptd);
            });
        });
    }
    getMapData("");
});
</script>


@endsection
