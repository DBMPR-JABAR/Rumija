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
<script>
    require(["esri/Map", "esri/views/MapView"], function (Map, MapView) {
      var map = new Map({
        basemap: "streets-navigation-vector"
      });

      var view = new MapView({
        container: "viewDiv",
        map: map,
        center: [107.6191, -6.9175], // longitude, latitude
        zoom: 9
      });
    });
  </script>
</html>
