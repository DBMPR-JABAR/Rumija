<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" />
    <title>ArcGIS API for JavaScript Tutorials: Create a JavaScript starter app</title>

    <style>
        html,
        body,
        #viewDiv {
            padding: 0;
            margin: 0;
            height: 100%;
            width: 100%;
        }

    </style>
    <link rel="stylesheet" href="https://js.arcgis.com/4.18/esri/themes/light/main.css">

</head>
<body>
    <div id="viewDiv"></div>
</body>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://js.arcgis.com/4.18/"></script>
<script>
    require(["esri/Map", "esri/views/MapView", "esri/layers/GroupLayer", "esri/layers/FeatureLayer"], function (Map, MapView, GroupLayer, FeatureLayer) {
      const baseUrl = "{{url('/')}}";
      const gsvrUrl = "{{ env('GEOSERVER') }}";
      var map = new Map({
        basemap: "streets-navigation-vector"
      });

      var view = new MapView({
        container: "viewDiv",
        map: map,
        center: [107.6191, -6.9175], // longitude, latitude
        zoom: 9
      });
      view.popup.dockEnabled = true;

      // Persiapan Street View
      var msLat = 0,
          msLong = 0;
      view.on("click", function(event) {
          // Get the coordinates of the click on the view
          msLat = Math.round(event.mapPoint.latitude * 10000) / 10000;
          msLong = Math.round(event.mapPoint.longitude * 10000) / 10000;
      });
      // aksi untuk siapkan SV dari selected feature
      let prepSVAction = {
          title: "Lihat Street View",
          id: "prep-sv",
          className: "feather icon-video"
      };
      // fungsi yg dipanggil saat trigger aktif
      function prepSV() {
          window.open('http://maps.google.com/maps?q=&layer=c&cbll=' + msLat + ',' + msLong, 'SV_map_bmpr');
      }
      view.popup.on("trigger-action", function(event) {
          if (event.action.id === "prep-sv") {
              prepSV();
          }
      });

      function addRuteJalan() {
        let rutejalanLayer = map.findLayerById('rj');
        if (!rutejalanLayer) {
            rutejalanLayer = new GroupLayer({
                title: 'Ruas Jalan',
                id: 'rj'
            });
            rutejalanLayer.add(jalanProvinsi(), 3);
            map.add(rutejalanLayer);
        }

        function jalanProvinsi() {
          const popupTemplate = {
              title: "{nm_ruas}",
              content: [
                {
                    type: "custom",
                    title: "<b>Foto Jembatan</b>",
                    outFields: ["*"],
                    creator: function (feature) {
                        var id = feature.graphic.attributes.IDRuas;
                        var div = document.createElement("div");
                        div.className = "myClass";
                        div.innerHTML = `<h4>${id}</h4>
                                        <iframe
                                            src="${baseUrl}/admin/monitoring/roadroid-survei-kondisi-jalan/1"
                                            title="W3Schools Free Online Web Tutorials"
                                            style="width:100%"/>
                                        `;
                        return div;
                    }
                }
              ],
              actions: [prepSVAction]
          };
          let rjp = rutejalanLayer.findLayerById('rjp');
          if (!rjp) {
              rjp = new FeatureLayer({
                  url: gsvrUrl + "/geoserver/gsr/services/temanjabar/FeatureServer/0/",
                  title: 'Ruas Jalan Provinsi',
                  id: 'rjp',
                  outFields: ["*"],
                  popupTemplate: popupTemplate,
                  renderer: {
                      type: "simple", // autocasts as new SimpleRenderer()
                      symbol: {
                          type: "simple-line", // autocasts as new SimpleLineSymbol()
                          color: "green",
                          width: "2px",
                          style: "solid",
                          //   marker: { // autocasts from LineSymbolMarker
                          //      color: "orange",
                          //   placement: "begin-end",
                          // style: "circle"
                          // }
                      }
                  }
              });
          }
          return rjp;
        }
      }

      addRuteJalan();
    });
  </script>
</html>
