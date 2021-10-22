@extends('admin.layout.index')
@section('title') Master Ruas Jalan @endsection
@section('head')
<link rel="stylesheet" href="https://js.arcgis.com/4.19/esri/themes/light/main.css">
@endsection
@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Master Ruas Jalan</h4>
                <span>Tools Master Ruas Jalan</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('bankeu.index') }}">Master Ruas Jalan</a>
                </li>
                <li class="breadcrumb-item"><a href="#">Editor</a> </li>
            </ul>
        </div>
    </div>
</div>
@endsection
@section('page-body')
<div class="row">
    <div class="col-md-8">
        <input id="geo_json" name="geo_json" style="display:none" />
        <div id="mapLatLong" class="mb-3 full-map" style="height: 450px; width: 100%">
            <div id="tempel_disini"></div>
        </div>
    </div>
    <div class="col-4 card">

        <form enctype="multipart/form-data" method="post" id="uploadForm">
        <div class="form-group row mb-2">
            <label class="col-sm-12 col-form-label">Upload Shapefile.zip</label>
            <div class="col-sm-12">
                <input type="file" name="file" class="form-control form-control-sm" id="inFile">
            </div>
        </div>
        {{-- <div class="form-group row">
            <div class="col-sm-12">
                <button type="button" class="btn btn-primary btn-sm">
                    Muat shapefile
                </button>
            </div>
        </div> --}}
        <form>
        <span class="file-upload-status" style="opacity: 1" id="upload-status"></span>
        <div id="fileInfo"></div>
    </div>
</div>
@endsection

@section('script')
<script src="https://js.arcgis.com/4.19/"></script>
<script src="https://unpkg.com/shpjs@latest/dist/shp.js"></script>
<script type="text/javascript">
    const url = "{{url('/admin/input-data/bankeu/get_ruas_jalan_by_geo_id')}}"
    $("#mapLatLong")
    .ready(() => {
        require(["esri/Map",
            "esri/views/MapView",
            "esri/Graphic",
            "esri/views/draw/Draw",
            "esri/geometry/geometryEngine",
            "esri/widgets/BasemapToggle",
            "esri/geometry/SpatialReference",
            "esri/geometry/support/webMercatorUtils",
            "esri/layers/FeatureLayer",
            "esri/request",
            "esri/layers/support/Field",
        ], function(
            Map,
            MapView,
            Graphic,
            Draw,
            geometryEngine,
            BasemapToggle,
            SpatialReference,
            webMercatorUtils,
            FeatureLayer,
            request,
            Field,
        ) {

            const portalUrl = "https://www.arcgis.com";

            const map = new Map({
                basemap: "hybrid",
            });

            const view = new MapView({
                container: "mapLatLong",
                map,
                center: [107.6191, -6.9175],
                zoom: 9,
                popup: {
                    defaultPopupTemplateEnabled: true,
                },
            });


            const toggle = new BasemapToggle({
                view,
                nextBasemap: "osm",
            });

            view.ui.add(toggle, 'top-right');



            const draw = new Draw({
                view: view
            });

            let gambarManual
            const drawMode = () => {
                    gambarManual = document.createElement("div")
                    gambarManual.className = 'esri-widget esri-widget--button esri-interactive'
                    gambarManual.title = "Draw polyline"
                    gambarManual.innerHTML = '<span class="esri-icon-polyline"></span>'

                    view.ui.add(gambarManual, "top-left");
                    gambarManual.onclick = () => {
                        view.graphics.removeAll();
                        const action = draw.create("polyline");
                        view.focus();
                        action.on(
                            [
                                "vertex-add",
                                "vertex-remove",
                                "cursor-update",
                                "redo",
                                "undo",
                                "draw-complete"
                            ],
                            updateVertices
                        );
                    };
                }
                drawMode();

            const geoJson = document.getElementById('geo_json')

            const updateVertices = (event) => {
                if (event.vertices.length > 1) {
                    const result = createGraphic(event);
                    if (result.selfIntersects) {
                        event.preventDefault();
                    }
                }
            }

            const createGraphic = (event) => {
                const vertices = event.vertices;
                view.graphics.removeAll();

                const graphic = new Graphic({
                    geometry: {
                        type: "polyline",
                        paths: vertices,
                        spatialReference: view.spatialReference
                    },
                    symbol: {
                        type: "simple-line",
                        color: [255, 0, 0],
                        width: 4,
                        cap: "round",
                        join: "round"
                    }
                });

                const { paths } = webMercatorUtils.webMercatorToGeographic(graphic.geometry)
                geoJson.value = JSON.stringify(paths)
                const intersectingSegment = getIntersectingSegment(graphic.geometry);

                if (intersectingSegment) {
                    view.graphics.addMany([graphic, intersectingSegment]);
                } else {
                    view.graphics.add(graphic);
                }

                return {
                    selfIntersects: intersectingSegment
                };
            }

            const isSelfIntersecting = (polyline) => {
                if (polyline.paths[0].length < 3) {
                    return false;
                }
                const line = polyline.clone();

                const lastSegment = getLastSegment(polyline);
                line.removePoint(0, line.paths[0].length - 1);

                return geometryEngine.crosses(lastSegment, line);
            }

            const getIntersectingSegment = (polyline) => {
                if (isSelfIntersecting(polyline)) {
                    return new Graphic({
                        geometry: getLastSegment(polyline),
                        symbol: {
                            type: "simple-line",
                            style: "short-dot",
                            width: 3.5,
                            color: "yellow"
                        }
                    });
                }
                return null;
            }

            const getLastSegment = (polyline) => {
                const line = polyline.clone();
                const lastXYPoint = line.removePoint(0, line.paths[0].length - 1);
                const existingLineFinalPoint = line.getPoint(
                    0,
                    line.paths[0].length - 1
                );

                return {
                    type: "polyline",
                    spatialReference: view.spatialReference,
                    hasZ: false,
                    paths: [
                        [
                            [existingLineFinalPoint.x, existingLineFinalPoint.y],
                            [lastXYPoint.x, lastXYPoint.y]
                        ]
                    ]
                };
            }

            const addPolyLine = (paths) => {
                const graphic = new Graphic({
                    geometry: {
                        type: "polyline",
                        paths,
                    },
                    symbol: {
                        type: "simple-line",
                        color: [255, 0, 0],
                        width: 4,
                        cap: "round",
                        join: "round"
                    }
                });

                view.graphics.removeAll();
                view.graphics.add(graphic)
                view.goTo({
                    target: graphic.geometry,
                    tilt: 70,
                    zoom: 13,
                }, {
                    duration: 1500,
                    easing: "in-out-expo",
                })
            }

            document
          .getElementById("uploadForm")
          .addEventListener("change", (event) => {
            const fileName = event.target.value.toLowerCase();
            console.log(fileName)
            if (fileName.indexOf(".zip") !== -1) {
              //is file a zip - if not notify user
              generateFeatureCollection(fileName);
            } else {
              document.getElementById("upload-status").innerHTML =
                '<p style="color:red">Add shapefile as .zip file</p>';
            }
          });

          function generateFeatureCollection(fileName) {
          let name = fileName.split(".");
          // Chrome adds c:\fakepath to the value - we need to remove it
          name = name[0].replace("c:\\fakepath\\", "");

          document.getElementById("upload-status").innerHTML =
            "<b>Loading </b>" + name;

          // define the input params for generate see the rest doc for details
          // https://developers.arcgis.com/rest/users-groups-and-items/generate.htm
          const params = {
            name: name,
            targetSR: view.spatialReference,
            maxRecordCount: 1000,
            enforceInputFileSizeLimit: true,
            enforceOutputJsonSizeLimit: true,
          };

          // generalize features to 10 meters for better performance
          params.generalize = true;
          params.maxAllowableOffset = 10;
          params.reducePrecision = true;
          params.numberOfDigitsAfterDecimal = 0;

          const myContent = {
            filetype: "shapefile",
            publishParameters: JSON.stringify(params),
            f: "json",
          };

          // use the REST generate operation to generate a feature collection from the zipped shapefile
          request(portalUrl + "/sharing/rest/content/features/generate", {
            query: myContent,
            body: document.getElementById("uploadForm"),
            responseType: "json",
          })
            .then((response) => {
              const layerName =
                response.data.featureCollection.layers[0].layerDefinition.name;
              document.getElementById("upload-status").innerHTML =
                "<b>Loaded: </b>" + layerName;
              addShapefileToMap(response.data.featureCollection);
            })
            .catch(errorHandler);
        }

        function errorHandler(error) {
          document.getElementById("upload-status").innerHTML =
            "<p style='color:red;max-width: 500px;'>" + error.message + "</p>";
        }

        function addShapefileToMap(featureCollection) {
          // add the shapefile to the map and zoom to the feature collection extent
          // if you want to persist the feature collection when you reload browser, you could store the
          // collection in local storage by serializing the layer using featureLayer.toJson()
          // see the 'Feature Collection in Local Storage' sample for an example of how to work with local storage
          let sourceGraphics = [];

          const layers = featureCollection.layers.map((layer) => {
            const graphics = layer.featureSet.features.map((feature) => {
              return Graphic.fromJSON(feature);
            });
            sourceGraphics = sourceGraphics.concat(graphics);
            const featureLayer = new FeatureLayer({
              objectIdField: "FID",
              source: graphics,
              fields: layer.layerDefinition.fields.map((field) => {
                return Field.fromJSON(field);
              }),
            });
            return featureLayer;
            // associate the feature with the popup on click to enable highlight and zoom to
          });
          map.addMany(layers);
          view.goTo(sourceGraphics).catch((error) => {
            if (error.name != "AbortError") {
              console.error(error);
            }
          });

          document.getElementById("upload-status").innerHTML = "";
        }
            // END DOJO
        });
    });

</script>
@endsection
