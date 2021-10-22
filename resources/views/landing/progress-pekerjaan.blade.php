@extends('landing.template')
@section('head')
<link rel="stylesheet" href="https://js.arcgis.com/4.17/esri/themes/light/main.css">
@endsection
@section('body')
<section id="main-banner-page" class="position-relative page-header service-detail-header section-nav-smooth parallax">
    <div class="overlay overlay-dark opacity-7 z-index-1"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 offset-lg-2">
                <div class="page-titles whitecolor text-center padding_top padding_bottom">
                    <h3 class="font-xlight">Pemetaan</h3>
                    <h3 class="font-bold">Progress Pekerjaan</h3>
                </div>
            </div>
        </div>
        <div class="gradient-bg title-wrap">
            <div class="row">
                <div class="col-lg-12 col-md-12 whitecolor">
                    <h3 class="float-left">Progress Pekerjaan</h3>
                    <ul class="breadcrumb top10 bottom10 float-right">
                        <li class="breadcrumb-item hover-light"><a href="{{ url('')}}">Home</a></li>
                        <li class="breadcrumb-item hover-light">Progress Pekerjaan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!--Page Header ends -->
<br>
<!-- Services us -->
<main class="container-fluid bglight">
    <!-- content -->
    <section id="content" class="row">
        <div id="features" class="col-lg-3">

            <div class="container-fluid">
                <div class="row whitebox top15">
                    <div class="col-lg-12 col-md-12">
                        <div class="image widget bottom20"><img alt="temanjabar" src="{{ asset('assets/images/brand/text_hitam.png') }}"></div>
                        <div class="widget heading_space text-center text-md-left">
                            <h4 class="text-capitalize darkcolor bottom20">Lapor?</h4>
                            <div class="contact-table colorone d-table bottom15">
                                <div class="d-table-cell cells">
                                    <span class="icon-cell"><i class="fas fa-mobile-alt"></i></span>
                                </div>
                                <div class="d-table-cell cells">
                                    <p class="bottom0">{{ $profil->kontak }}</p>
                                </div>
                            </div>
                            <div class="contact-table colorone d-table bottom15 text-left">
                                <div class="d-table-cell cells">
                                    <span class="icon-cell"><i class="fas fa-map-marker-alt"></i></span>
                                </div>
                                <div class="d-table-cell cells">
                                    <p class="bottom0">{{ $profil->alamat }}
                                    </p>
                                </div>
                            </div>
                            <div class="contact-table colorone d-table text-left">
                                <div class="d-table-cell cells">
                                    <span class="icon-cell"><i class="far fa-clock"></i></span>
                                </div>
                                <div class="d-table-cell cells">
                                    <p class="bottom0">Senin - Jumat: {{$profil->jam_layanan}}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ url('/#laporan') }}" class="button btnsecondary gradient-btn top30"> Form Lapor</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div id="wrapper" class="col-lg-9">
            <!-- Services us -->
            <section id="our-services" class="pt-5 bglight">
                <div class="container">
                    <div class="row whitebox top15">

                        <div class="col-lg-12 col-md-12">
                            <div class="widget heading_space text-center text-md-left">
                                <h3 class="darkcolor font-normal bottom15">Progress Pekerjaan</h3>
                                <p class="bottom30">Berikut merupakan pemetaan dari progress pekerjaan projek pembangunan infrasutruktur yang sudah dikerjakan oleh DBMPR.</p>
                                <div class="col-12 px-0">
                                    <div class="w-100">
                                        <div class="full-map short-map" id="viewDiv" style="max-height: 567px"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Services us ends -->
        </div>
    </div>
    <!-- content ends -->

</main>
@endsection
@section('script')
<script src="https://js.arcgis.com/4.17/"></script>
<script>
    require([
      "esri/Map",
      "esri/views/MapView",
      "esri/request",
      "esri/geometry/Point",
      "esri/Graphic",
    ], function (Map, MapView, esriRequest, Point, Graphic) {
      const baseUrl = "{{url('')}}";

      const map = new Map({
        basemap: "hybrid"
      });
      const view = new MapView({
        container: "viewDiv",
        map: map,
        center: [107.6191, -6.9175], // longitude, latitude
        zoom: 8
      });

      const symbol = {
        type: "picture-marker",  // autocasts as new PictureMarkerSymbol()
        url: "http://esri.github.io/quickstart-map-js/images/blue-pin.png",
        width: "19px",
        height: "36px"
      };
      const popupTemplate = {
        title: "{JENIS_PEKERJAAN}",
        content: [
            {
              type: "fields",
              fieldInfos: [
                  {
                    fieldName: "NAMA_PAKET",
                    label: "Nama Paket"
                  },
                  {
                    fieldName: "STATUS",
                    label: "Status"
                  },
                  {
                    fieldName: "TANGGAL",
                    label: "Tanggal"
                  },
                  {
                    fieldName: "JENIS_PEKERJAAN",
                    label: "Jenis Pekerjaan"
                  },
                  {
                    fieldName: "RUAS_JALAN",
                    label: "Ruas Jalan"
                  },
                  {
                    fieldName: "LOKASI",
                    label: "Lokasi"
                  },
                  {
                    fieldName: "RENCANA",
                    label: "Rencana"
                  },
                  {
                    fieldName: "REALISASI",
                    label: "Realisasi"
                  },
                  {
                    fieldName: "DEVIASI",
                    label: "Deviasi"
                  }
              ]
            }
        ]
      };

      const url = baseUrl + "/api/progress-mingguan";
      const requestLaporan = esriRequest(url, {
        responseType: "json",
      }).then(function(response){
        const json = response.data;
        const data = json.data;
        data.forEach(item => {
            var point = new Point(item.LNG, item.LAT);
            view.graphics.add(new Graphic({
                geometry: point,
                symbol: symbol,
                attributes: item,
                popupTemplate: popupTemplate
            }));
        });

      }).catch(function (error) {
        console.log(error);
      });


    });
</script>
@endsection
