$(document).ready(() => {
    const baseUrl = "{{url('/')}}";

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
            view.on("click", function(event){
                if($("#lat").val() != '' && $("#long").val() != ''){
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
        });
    });

    $('#mapOffice').ready(() => {
        require([
        "esri/Map",
        "esri/views/MapView",
        "esri/Graphic",
        ], function(Map, MapView, Graphic) {
            const map = new Map({
                basemap: "streets-relief-vector"
            });

            const view = new MapView({
                container: "mapOffice",
                map: map,
                center: [107.6088113, -6.9213147],
                zoom: 16,
            });

            const point = {
                type: "point",
                longitude: 107.6108861,
                latitude: -6.9213706
            };

            const marker = {
                type: "picture-marker",
                url: "https://static.arcgis.com/images/Symbols/Shapes/BlackStarLargeB.png",
                width: "32px",
                height: "32px"
            };

            const pointGraphic = new Graphic({ geometry: point, symbol: marker });

            view.graphics.add(pointGraphic);
        });
    });
});
