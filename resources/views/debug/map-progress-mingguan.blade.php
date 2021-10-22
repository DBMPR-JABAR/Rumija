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
    <link rel="stylesheet" href="https://js.arcgis.com/4.17/esri/themes/light/main.css">
</head>
<body>
    <div id="viewDiv"></div>
</body>
<script src="https://js.arcgis.com/4.17/"></script>
{{-- <script>
    require(["esri/map",
      "esri/dijit/PopupTemplate",
      "esri/symbols/PictureMarkerSymbol",
      "esri/geometry/Point",
      "esri/graphic",
      "esri/graphicsUtils",
      "esri/request",
      "dojo/on",
      "dojo/keys",
      "dojo/_base/fx",
      "dojo/fx/easing",
      "dojo/dom-style",
      "dojo/dom",
      "dojo/domReady!"],
      function(Map, PopupTemplate, PictureMarkerSymbol, Point, Graphic, GraphicsUtils, Request, on, keys, fx, easing, domStyle, dom) {
        "use strict"

        // Create map
        var map = new Map("viewDiv",{
          basemap: "streets-navigation-vector",
          center: [107.6191, -6.9175], // longitude, latitude
          zoom: 9
        });

        // Set popup
        var popup = map.infoWindow;
        popup.highlight = false;
        popup.titleInBody = false;
        popup.domNode.style.marginTop = "-20px";
        popup.domNode.className += " light";


        // Wire UI Events
        on(dom.byId("btnGo"), "click", loadPhotos);
        on(dom.byId("inputSearchTags"), "keydown", function(event) {
          if (event.keyCode === keys.ENTER) {
            loadPhotos();
          }
        });

        function showInfoWindow(graphic) {
          if (map.infoWindow.selectedIndex === -1 || graphic !== map.infoWindow.features[map.infoWindow.selectedIndex]) {
            map.infoWindow.setFeatures([graphic]);
            map.infoWindow.show(graphic.geometry);
          }
        }

        // Map loaded
        map.on("load", function () {
          // Manually show popup instead of using template
          map.graphics.on("click", function (e) {
            showInfoWindow(e.graphic);
          });

          map.graphics.on("mouse-over", function (e) {
            showInfoWindow(e.graphic);
          });

          map.infoWindow.on("selection-change", function () {
              domStyle.set(map.infoWindow.domNode, "opacity", 0);
              fx.fadeIn({node: map.infoWindow.domNode,
                duration:350,
                easing: easing.quadIn}).play();
          });
        });

        // Popup reposition when extent changes
        map.on("extent-change", function(e) {
          if (map.infoWindow.isShowing) {
            map.infoWindow.reposition();
          }
        });

        // Get symbol
        var symbol = new createPictureSymbol("http://esri.github.io/quickstart-map-js/images/blue-pin.png", 0, 12, 13, 24);

        // Request to Flickr service
        function loadPhotos(){
          clearGraphics();
          var flickrPhotos = Request({
            url: "http://api.flickr.com/services/feeds/geo",
            content:{
                format:"json",
                tagmode: "any",
                tags: "satay"
            },
            callbackParamName: "jsoncallback"
          });
          flickrPhotos.then(addPhotos);
        }

        // Create graphics for each Flickr item
        function addPhotos(data){
          // Format popup content
          var template = new PopupTemplate({
            title: "<b>{title}</b>",
            description:"{description}"
          });
          for (var i in data.items) {
            var item = data.items[i];
            var loc = new Point(item.longitude, item.latitude);
            map.graphics.add(new Graphic(loc, symbol, item, template));
          }
          var extent = GraphicsUtils.graphicsExtent(map.graphics.graphics).expand(1.5);
          map.setExtent(extent);
        }


        function createPictureSymbol(url, xOffset, yOffset, xWidth, yHeight) {
          return new PictureMarkerSymbol(
          {
            "angle": 0,
            "xoffset": xOffset, "yoffset": yOffset, "type": "esriPMS",
            "url": url,
            "contentType": "image/png",
            "width":xWidth, "height": yHeight
          });
        }

        function clearGraphics() {
          map.graphics.clear();
          map.infoWindow.hide();
        }
    });
</script> --}}
<script>
    require([
      "esri/Map",
      "esri/views/MapView",
      "esri/request",
      "esri/geometry/Point",
      "esri/Graphic",
    ], function (Map, MapView, esriRequest, Point, Graphic) {
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
        title: "{NAMA_PAKET}",
        content: [
            {
              type: "fields",
              fieldInfos: [
                  {
                    fieldName: "TANGGAL",
                    label: "Tanggal"
                  },
                  {
                    fieldName: "WAKTU_KONTRAK",
                    label: "Waktu Kontrak"
                  },
                  {
                    fieldName: "TERPAKAI",
                    label: "Terpakai"
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
                  },
                  {
                    fieldName: "NILAI_KONTRAK",
                    label: "Nilai Kontrak"
                  },
                  {
                    fieldName: "PENYEDIA_JASA",
                    label: "Penyedia Jasa"
                  },
                  {
                    fieldName: "KEGIATAN",
                    label: "Kegiatan"
                  },
                  {
                    fieldName: "STATUS_PROYEK",
                    label: "Status"
                  },
                  {
                    fieldName: "UPTD",
                    label: "UPTD"
                  }
              ]
            }
        ]
      };


      const url = "http://localhost:8000/api/progress-mingguan";
      const requestProgress = esriRequest(url, {
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
</html>
