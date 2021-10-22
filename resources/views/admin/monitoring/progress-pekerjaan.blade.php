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
                <h4>Peta Jabar Distribusi Proyek Kontrak</h4>
             </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Supervisi Proyek Kontrak</a> </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('page-body')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-block">
                <div id="viewDiv" style="width:100%;height:600px;padding: 0;margin: 0;"></div>
            </div>
    </div>
</div>
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
        title: "{jenis}",
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
      const requestProgressPekerjaan = esriRequest(url, {
        responseType: "json",
      }).then(function(response){
        const json = response.data;
        const data = json.data;
        let i = 1;
        if(data.length == 0){
            table.innerHTML =   `<tr>
                                    <td colspan="5">Data Kosong</td>
                                </tr>`;
        }else{
            data.forEach(item => {
                var point = new Point(item.LNG, item.LAT);
                view.graphics.add(new Graphic({
                    geometry: point,
                    symbol: symbol,
                    attributes: item,
                    popupTemplate: popupTemplate
                }));
                table.innerHTML +=  `<tr>
                                        <td>
                                            <b>${item.NAMA_PAKET}</b>
                                        </td>
                                        <td>
                                            ${item.STATUS}
                                        </td>
                                        <td>
                                            ${item.TANGGAL}
                                        </td>
                                        <td>
                                            ${item.JENIS_PEKERJAAN}
                                        </td>
                                        <td>
                                            ${item.RUAS_JALAN}
                                        </td>
                                        <td>
                                            ${item.LOKASI}
                                        </td>
                                        <td>
                                            ${item.RENCANA}
                                        </td>
                                        <td>
                                            ${item.REALISASI}
                                        </td>
                                        <td>
                                            ${item.DEVIASI}
                                        </td>
                                    </tr>`;
            });
        }

      }).catch(function (error) {
        console.log(error);
      });


    });
</script>
@endsection
