<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" />

    <link rel="icon" href="{{ asset('assets/images/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/feather/css/feather.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor/chosen_v1.8.7/docsupport/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/chosen_v1.8.7/docsupport/prism.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/chosen_v1.8.7/chosen.css') }}">

    <link rel="stylesheet" href="https://js.arcgis.com/4.18/esri/themes/light/main.css">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script src='https://cdn.fluidplayer.com/v3/current/fluidplayer.min.js'></script>
    <link rel="stylesheet" href="{{ asset('assets/css/filterMapsInternal.css') }}">

    <title>Survei Kondisi Jalan</title>
</head>
<body>
    <div id="viewDiv"></div>
    <div id="grupKontrol" style="display:inline-flex;">
        <div id="logo">
            <img width="200" class="img-fluid" src="{{ asset('assets/images/brand/text_putih.png')}}" alt="Logo DBMPR">
        </div>
        <div>
            <div id="showFilter">
                <button data-toggle="tooltip" data-placement="right" title="Fitur Filter">
                    <i class="feather icon-list"></i>
                </button>
            </div>
            <div id="showBaseMaps">
                <button data-toggle="tooltip" data-placement="right" title="Fitur Filter">
                    <i class="feather icon-map"></i>
                </button>
            </div>
            <div id="fullscreen">
                <button data-toggle="tooltip" data-placement="right" title="Fullscreen / Normal">
                    <i class="feather icon-maximize full-card"></i>
                </button>
            </div>
            <div id="back">
                <a href="{{ url('/admin') }}">
                    <button data-toggle="tooltip" data-placement="right" title="Kembali kehalaman Sebelumnya">
                        <i class="feather icon-arrow-left"></i>
                    </button>
                </a>
            </div>
        </div>
    </div>
    <div id="filter" class="bg-white">
        <div id="preloader" style="display:none">
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
            </div>
        </div>
        <form class="py-3">
            {{-- <div class="row">
                <div class="col">
                    <button type="button" class="btn btn-sm btn-block btn-secondary clustering">Disable Clustering</button>
                </div>
            </div>
            <hr> --}}
            <div class="form-group">
                <label for="uptd"><i class="feather icon-target text-primary"></i> UPTD</label>
                <select id="uptd" class="form-control chosen-select chosen-select-uptd" id="uptd" multiple data-placeholder="Pilih UPTD">
                    <option value=""></option>
                </select>
            </div>
            <div class="form-group">
                <label for="spp_filter"><i class="feather icon-corner-down-right text-danger"></i> SPP / SUP</label>
                <select id="spp_filter" data-placeholder="Pilih UPTD dengan SPP" class="chosen-select" multiple tabindex="6">
                    <option value=""></option>
                </select>
            </div>
            <div class="form-group">
                <label for="kegiatan"><i class="feather icon-activity text-warning"></i> Kegiatan</label>
                <select data-placeholder="Pilih kegiatan" multiple class="chosen-select" tabindex="8" id="kegiatan">
                </select>
            </div>
            <!-- {{-- <div class="form-group">
                <label for="proyek"><i class="feather icon-calendar text-success"></i> Proyek Kontrak</label>
                <select class="chosen-select form-control" id="proyek" data-placeholder="Pilih kegiatan" multiple tabindex="4">
                    <option value="onprogress">On-Progress</option>
                    <option value="critical">Critical Contract</option>
                    <option value="finish">Finish</option>
                </select>
            </div> --}} -->
            <!-- <div class="form-group">
                <label for="basemap">Basemap</label>
                <select data-placeholder="Basemap..." class="chosen-select form-control" id="basemap" tabindex="-1">
                    <option value="streets">Street</option>
                    <option value="hybrid" selected>Hybrid</option>
                    <option value="satellite">Satelite</option>
                    <option value="topo">Topo</option>
                    <option value="gray">Gray</option>
                    <option value="national-geographic">National Geographic</option>
                </select>
            </div> -->
            <div class="form-group">
                <label for="zoom"><i class="feather icon-zoom-in"></i> Zoom</label>
                <select class="chosen-select form-control" id="zoom">
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9" selected>9</option>
                    <option value="10">10</option>
                </select>
            </div>
            <div id="filterDate" class="d-none">
                <div class="form-group mt-2">
                    <label for="dari"><i class="feather icon-calendar text-success"></i> Mulai Tanggal: </label>
                    <input class="form-control mulaiTanggal" type="date" id="dari" style="height: 30px;">
                </div>
                <div class="form-group mt-2">
                    <label for="sampai"><i class="feather icon-calendar text-primary"></i> Sampai Tanggal: </label>
                    <input class="form-control sampaiTanggal" type="date" id="sampai" style="height: 30px;">
                </div>
            </div>

            <!-- dimz-add -->
            <div class="form-group mt-2">
                <input type="button" class="form-control" id="btnProses" value="Proses" disabled>
            </div>
        </form>
    </div>
    <div id="baseMaps" class="bg-white">
        {{-- <div class="row">
                <div class="col">
                    <h6>Tipe Maps</h6>
                </div>
                <div class="col p-0">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-success active">
                            <input type="radio" name="options" id="option1" autocomplete="off" checked>2D
                        </label>
                        <label class="btn btn-success">
                            <input type="radio" name="options" id="option2" autocomplete="off"> 3D
                        </label>
                    </div>
                </div>
            </div>
            <hr> --}}
        <div class="listMaps">
            <div class="row mb-4">
                <div class="col">
                    <h6>Tampilan Jenis Peta Dasar</h6>
                </div>
            </div>
            <ul class="row">
                <li>
                    <button class="baseMapBtn" data-map="streets">
                        <img _ngcontent-btg-c5="" alt="Rupa Bumi Indonesia" title="Rupa Bumi Indonesia" src="https://portal.ina-sdi.or.id/arcgis/rest/services/RBI/Basemap/MapServer/info/thumbnail">
                    </button>
                </li>
                <li>
                    <button class="baseMapBtn" data-map="gray">
                        <img _ngcontent-pmm-c5="" alt="Cartodb Light All" title="Cartodb Light All" src="https://satupeta-dev.digitalservice.id/assets/img/basemap-thumbnail/cartodb_light.png">
                    </button>
                </li>
                <li>
                    <button class="baseMapBtn" data-map="streets-night-vector">
                        <img _ngcontent-vgg-c5="" alt="Cartodb Dark All" title="Streets Night Vector" src="https://satupeta-dev.digitalservice.id/assets/img/basemap-thumbnail/cartodb_dark.png">
                    </button>
                </li>
                <li>
                    <button class="baseMapBtn" data-map="national-geographic">
                        <img _ngcontent-vgg-c5="" alt="National Geographic" title="National Geographic" src="https://js.arcgis.com/4.14/esri/images/basemap/national-geographic.jpg">
                    </button>
                </li>
                <li>
                    <button class="baseMapBtn" data-map="topo">
                        <img _ngcontent-lqn-c5="" alt="Topographic" title="Topographic" src="https://satupeta-dev.digitalservice.id/assets/img/basemap-thumbnail/topo.png"></button>
                </li>
                </button>
                </li>
                <li>
                    <button class="baseMapBtn" data-map="dark-gray">
                        <img _ngcontent-lqn-c5="" alt="Dark Gray" title="Dark Gray" src="https://js.arcgis.com/4.14/esri/images/basemap/dark-gray.jpg">
                    </button>
                </li>
                <li>
                    <button class="baseMapBtn" data-map="osm">
                        <img _ngcontent-lqn-c5="" alt="Open Street Map" title="Open Street Map" src="https://js.arcgis.com/4.14/esri/images/basemap/osm.jpg">
                    </button>
                </li>
                <li>
                    <button class="baseMapBtn" data-map="hybrid">
                        <img _ngcontent-lqn-c5="" alt="hybrid" title="hybrid" src="https://js.arcgis.com/4.14/esri/images/basemap/hybrid.jpg">
                    </button>
                </li>
                <li>
                    <button class="baseMapBtn" data-map="terrain">
                        <img _ngcontent-lqn-c5="" alt="terrain" title="terrain" src="https://js.arcgis.com/4.14/esri/images/basemap/terrain.jpg">
                    </button>
                </li>
            </ul>
        </div>
    </div>
</body>
<script>
    // toggle filter
    const showFilterElmnt = document.querySelector("#showFilter");
    const filter = document.querySelector("#filter");
    const mainElement = document.querySelector("#viewDiv");
    const showBaseMapsElmnt = document.querySelector("#showBaseMaps");
    const baseMaps = document.querySelector("#baseMaps");

    //create chevron elmn
    let chevron = document.createElement('i');
    chevron.setAttribute('class', 'feather icon-chevrons-right')

    showFilterElmnt.addEventListener("click", event => {
        filter.classList.toggle("open");
        baseMaps.classList.contains('open') ? baseMaps.classList.toggle('open') : '';
        event.stopPropagation();
    });

    showBaseMapsElmnt.addEventListener("click", event => {
        baseMaps.classList.toggle("open");
        filter.classList.contains('open') ? filter.classList.toggle('open') : '';
        event.stopPropagation();
    });

    mainElement.addEventListener("click", event => {
        filter.classList.remove("open");
        baseMaps.classList.remove("open");
        event.stopPropagation();
    })

    //toggle fullscreen
    function getFullscreenElement() {
        return document.fullscreenElement ||
            document.webkitFullscreenElement ||
            document.mozFullscreenElement ||
            document.msFullscreenElement;
    }

    function toggleFullscreen() {
        if (getFullscreenElement()) {
            document.exitFullscreen();
        } else {
            document.documentElement.requestFullscreen().catch((e) => {
                console.log(e);
            });
        }
    }

    const fullScreenElemn = document.querySelector('#fullscreen');
    fullScreenElemn.addEventListener('click', () => {
        toggleFullscreen();
    });

    // <!-- enable clustering -->
    const clusteringElmn = document.querySelector('.clustering');
</script>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://js.arcgis.com/4.18/"></script>

<script type="text/javascript" src="{{ asset('assets/vendor/chosen_v1.8.7/chosen.jquery.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/vendor/chosen_v1.8.7/docsupport/prism.js') }}" type="text/javascript" charset="utf-8"></script>

<script>
    function fillSUP(uptd) {
        return new Promise(function(resolve, reject) {
            $.ajax({
                type: "POST",
                url: "{{ route('api.supdata') }}",
                data: {
                    uptd: uptd
                },
                success: function(response) {
                    $("#spp_filter").empty();
                    let len = '';
                    let spp = '';
                    if (response['data'] != null) {
                        len = response['data']['uptd'];
                        spp = response['data']['spp'];
                    }
                    if (len.length > 0) {
                        // Read data and create <option>
                        let select = '';
                        for (let i = 0; i < len.length; i++) {
                            select += '<optgroup label=' + len[i] + '>';
                            select += '';
                            for (let j = 0; j < spp.length; j++) {
                                if (len[i] == spp[j].UPTD) {
                                    select += '<option ' + 'value="' + spp[j].SUP + '" selected>' + spp[j].SUP + '</option>';
                                }
                            }
                            select += '</optgroup>';
                        }

                        $('#spp_filter').html(select).trigger('liszt:updated');
                        $('#spp_filter').trigger("chosen:updated");
                        // $('#spp_filter .chosen-select').append("<li id='spp_filter" + id + "' class='active-result' style>" +name+ "</li>");
                    }
                    $("#preloader").hide();
                    return resolve($("#spp_filter").val());
                }
            });
        });
    }
    async function getSUPData() {
        const uptd = $("#uptd").val();
        let data = [];
        $("#preloader").show();
        // getMapData(uptd,basemap);
        // option = "";
        if (uptd.length == 0) {
            $("#spp_filter").empty();
            $('#spp_filter').trigger("chosen:updated");
        } else {
            data = await fillSUP(uptd);
        }
        return data;
    }

    function initFilter() {
        $("#uptd").empty();
        const roleUptd = `{{ Auth::user()->internalRole->uptd }}`;
        select = "";
        if (roleUptd == "") {
            for (let i = 1; i <= 6; i++) {
                select += `<option value="uptd${i}">UPTD ${i}</option>`;
            }
        } else {
            select += `<option value="${roleUptd}">UPTD ${roleUptd.replace('uptd','')}</option>`;
        }
        $('#uptd').html(select).trigger('liszt:updated');
        $('#uptd').trigger("chosen:updated");

        $("#spp_filter").empty();
        $('#spp_filter').trigger("chosen:updated");

        $("#kegiatan").empty();
        kegiatan = `
                    <option value="kondisijalan_titik">Survei Kondisi Jalan (Titik)</option>
                    <option value="kondisijalan">Survei Kondisi Jalan</option>
                    `;
        $('#kegiatan').html(kegiatan).trigger('liszt:updated');
        $('#kegiatan').trigger("chosen:updated");

        // $("#proyek").empty();
        // proyek = `<option value="onprogress">On-Progress</option>
        //             <option value="criticalprogress">Critical Contract</option>
        //             <option value="offprogress">Off Progress</option>
        //             <option value="finishprogress">Finish</option>`;
        // $('#proyek').html(proyek).trigger('liszt:updated');
        // $('#proyek').trigger("chosen:updated");
    }

    function difference(array1, array2) {
        let result = [];
        for (let i = 0; i < array1.length; i++) {
            if (array2.indexOf(array1[i]) === -1) {
                result.push(array1[i]);
            }
        }
        return result;
    }

    $(document).ready(function() {
        initFilter();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const config = {
            '.chosen-select': {
                width: '100%',
                padding: '0'
            },
            '.chosen-select-deselect': {
                allow_single_deselect: true
            },
            '.chosen-select-no-single': {
                disable_search_threshold: 10
            },
            '.chosen-select-no-results': {
                no_results_text: 'Oops, nothing found!'
            },
            '.chosen-select-rtl': {
                rtl: true
            },
            '.chosen-select-width': {
                width: '95%'
            }
        };
        for (let selector in config) {
            $(selector).chosen(config[selector]);
        }

        // dimz-add
        require([
            "esri/Map",
            "esri/views/MapView",
            "esri/request",
            "esri/geometry/Point",
            "esri/Graphic",
            "esri/layers/GroupLayer",
            "esri/layers/FeatureLayer",
            "esri/widgets/LayerList",
            "esri/widgets/Legend",
            "esri/widgets/Expand"
        ], function(Map, MapView, esriRequest, Point, Graphic, GroupLayer,
            FeatureLayer, LayerList, Legend, Expand) {

            // Map Initialization
            const baseUrl = "{{url('/')}}";
            const gsvrUrl = "{{ env('GEOSERVER') }}";
            const authKey = "9bea4cef-904d-4e00-adb2-6e1cf67b24ae";

            let basemap = "hybrid";

            const map = new Map({
                basemap: basemap
            });
            const view = new MapView({
                container: "viewDiv",
                map: map,
                center: [107.6191, -6.9175], // longitude, latitude
                zoom: 9,
                extent: {
                    spatialReference: 4326
                }
            });
            const layerList = new Expand({
                content: new LayerList({
                    view: view,
                    id: 'layerList'
                }),
                view: view,
                expanded: true,
                expandIconClass: 'esri-icon-layers',
                expandTooltip: 'Layer Aktif'
            });
            const legend = new Expand({
                content: new Legend({
                    view: view,
                    id: 'lgd',
                    // style: "card" // other styles include 'classic'
                }),
                view: view,
                expanded: true,
                expandIconClass: 'esri-icon-legend',
                expandTooltip: 'Legenda'
            });

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

            // Button Initialization
            view.ui.add('grupKontrol', 'top-right');
            $("#spp_filter, #kegiatan").chosen().change(function() {
                changeBtnProses();
            });
            $("#uptd").chosen().change(async function() {
                await getSUPData();
                changeBtnProses();
            });
            $(".baseMapBtn").click(function(event) {
                basemap = $(this).data('map');
                map.basemap = basemap;
            });
            $("#zoom").change(function() {
                const zoom = this.value;
                view.zoom = zoom;
            });

            function hasTanggal(kegiatan) {
                const result = kegiatan.includes('pembangunan') || kegiatan.includes('rehabilitasi') ||
                               kegiatan.includes('peningkatan') || kegiatan.includes('pemeliharaan');
                return result;
            }

            function changeBtnProses() {
                let sup = $("#spp_filter").val().length !== 0;
                let kegiatan = $("#kegiatan").val();

                if (sup) {
                    $('#btnProses').addClass('btn-primary');
                    $('#btnProses').removeAttr('disabled');
                } else {
                    $('#btnProses').removeClass('btn-primary');
                    $('#btnProses').attr('disabled', 'disabled');
                }

                if(hasTanggal(kegiatan)){
                    $('#filterDate').removeClass('d-none');

                    let today = new Date().toISOString().substr(0, 10);;
                    $('.sampaiTanggal').val(today);
                    $('.mulaiTanggal').val("2000-01-01");
                }else{
                    $('#filterDate').addClass('d-none');
                }

            }
            $("#btnProses").click(function(event) {
                caseRender();
            });

            // Render Layer
            function caseRender() {
                let sup = $("#spp_filter").val();
                let kegiatan = $("#kegiatan").val();

                if ($.inArray('kondisijalan', kegiatan) >= 0) {
                    addKondisiJalan();
                    kegiatan.splice(kegiatan.indexOf('kondisijalan'), 1); // remove 'kemantapanjalan' dari kegiatan
                } else {
                    map.remove(map.findLayerById('rjp_skj'));
                }
                if ($.inArray('kondisijalan_titik', kegiatan) >= 0) {
                    addTitikKondisiJalan();
                    kegiatan.splice(kegiatan.indexOf('kondisijalan_titik'), 1); // remove 'kemantapanjalan' dari kegiatan
                } else {
                    map.remove(map.findLayerById('rjp_skj_titik'));
                }

                view.when(function() {
                    if (!view.ui.find("layerList")) {
                        view.ui.add(layerList, "bottom-right");
                    }
                    if (!view.ui.find("lgd")) {
                        view.ui.add(legend, "bottom-left");
                    }
                });
            }

            function addKondisiJalan() {
                const popupTemplate = {
                    title: "{nm_ruas}",
                    content: [{
                            type: "custom",
                            title: "<b>Survei Kondisi Jalan</b>",
                            outFields: ["*"],
                            creator: function(feature) {
                                var id = feature.graphic.attributes.idruas;
                                var div = document.createElement("div");
                                console.log(feature.graphic.attributes);
                                div.className = "myClass";
                                div.innerHTML = `<h5>Kode Ruas Jalan: ${id}</h5>
                                                <iframe
                                                    src="${baseUrl}/admin/monitoring/roadroid-survei-kondisi-jalan/${id}"
                                                    title="W3Schools Free Online Web Tutorials"
                                                    style="width:100%"/>
                                                `;
                                return div;
                            }
                        },
                        {
                            type: "fields",
                            fieldInfos: [{
                                    fieldName: "idruas",
                                    label: "Nomor Ruas"
                                },
                                {
                                    fieldName: "idsegmen",
                                    label: "Nomor Segmen"
                                },
                                {
                                    fieldName: "KOTA_KAB",
                                    label: "Kota/Kabupaten"
                                },
                                {
                                    fieldName: "e_IRI",
                                    label: "Estimasi IRI"
                                },
                                {
                                    fieldName: "c_IRI",
                                    label: "Kalkulasi IRI"
                                },
                                {
                                    fieldName: "avg_speed",
                                    label: "Kecepatan Rata-Rata Pengukuran IRI"
                                },
                                {
                                    fieldName: "KETERANGAN",
                                    label: "Keterangan"
                                },
                                {
                                    fieldName: "nm_sppjj",
                                    label: "SPP/ SUP"
                                },
                                {
                                    fieldName: "wil_uptd",
                                    label: "UPTD"
                                }
                            ]
                        }
                    ],
                    actions: [prepSVAction]
                };
                let uptdSel = $('#uptd').val();
                let whereUptd = 'uptd=' + uptdSel.shift().charAt(4);
                $.each(uptdSel, function(idx, elem) {
                    whereUptd = whereUptd + ' OR uptd=' + elem.charAt(4);
                });
                let rjp_skj = map.findLayerById('rjp_skj');
                if (!rjp_skj) {
                    rjp_skj = new FeatureLayer({
                        url: gsvrUrl + "/geoserver/gsr/services/temanjabar/FeatureServer/6/",
                        customParameters: {
                            ak: authKey
                        },
                        title: 'Hasil Survei Kondisi Jalan',
                        id: 'rjp_skj',
                        outFields: ["*"],
                        popupTemplate: popupTemplate,
                        renderer: {
                            type: "unique-value", // autocasts as new UniqueValueRenderer()
                            valueExpression: "When($feature.e_iri <= 4, 'Baik', $feature.e_iri > 4 && $feature.e_iri <= 8, 'Sedang', $feature.e_iri > 8 && $feature.e_iri <= 12, 'Rusak Ringan', 'Rusak Berat')",
                            uniqueValueInfos: [{
                                    value: 'Baik',
                                    symbol: {
                                        type: "simple-line", // autocasts as new SimpleLineSymbol()
                                        color: "green",
                                        width: "2px",
                                        style: "solid",
                                    },
                                },
                                {
                                    value: 'Sedang',
                                    symbol: {
                                        type: "simple-line", // autocasts as new SimpleLineSymbol()
                                        color: "orange",
                                        width: "2px",
                                        style: "solid",
                                    },
                                },
                                {
                                    value: 'Rusak Ringan',
                                    symbol: {
                                        type: "simple-line", // autocasts as new SimpleLineSymbol()
                                        color: "red",
                                        width: "2px",
                                        style: "solid",
                                    },
                                },
                                {
                                    value: 'Rusak Berat',
                                    symbol: {
                                        type: "simple-line", // autocasts as new SimpleLineSymbol()
                                        color: "#990b0b",
                                        width: "2px",
                                        style: "solid",
                                    },
                                },
                            ]
                        }
                    });
                    map.add(rjp_skj);
                }
                rjp_skj.definitionExpression = whereUptd;
            }
            function addTitikKondisiJalan() {
                const popupTemplate = {
                    title: "{nm_ruas}",
                    content: [
                        {
                            type: "custom",
                            title: "<b>Survei Kondisi Jalan</b>",
                            outFields: ["*"],
                            creator: function (feature) {
                                var id = feature.graphic.attributes.id_ruas_jalan;
                                var div = document.createElement("div");
                                console.log(feature.graphic.attributes);
                                div.className = "myClass";
                                div.innerHTML = `<h5>Kode Ruas Jalan: ${id}</h5>
                                                <iframe
                                                    src="${baseUrl}/admin/monitoring/roadroid-survei-kondisi-jalan/${id}"
                                                    title="W3Schools Free Online Web Tutorials"
                                                    style="width:100%"/>
                                                `;
                                return div;
                            }
                        },
                        {
                            type: "fields",
                            fieldInfos: [{
                                    fieldName: "id_ruas_jalan",
                                    label: "Nomor Ruas"
                                },
                                {
                                    fieldName: "latitude",
                                    label: "Latitude"
                                },
                                {
                                    fieldName: "longitude",
                                    label: "Longitude"
                                },
                                {
                                    fieldName: "distance",
                                    label: "Jarak"
                                },
                                {
                                    fieldName: "altitude",
                                    label: "Altitude"
                                },
                                {
                                    fieldName: "altitude_10",
                                    label: "Altitude 10"
                                },
                                {
                                    fieldName: "eiri",
                                    label: "Estimasi IRI"
                                },
                                {
                                    fieldName: "ciri",
                                    label: "Kalkulasi IRI"
                                }
                            ]
                        }
                    ],
                    actions: [prepSVAction]
                };
                // let uptdSel = $('#uptd').val();
                // let whereUptd = 'uptd=' + uptdSel.shift().charAt(4);
                // $.each(uptdSel, function(idx, elem) {
                //     whereUptd = whereUptd + ' OR uptd=' + elem.charAt(4);
                // });
                let rjp_skj_titik = map.findLayerById('rjp_skj_titik');
                if (!rjp_skj_titik) {
                    rjp_skj_titik = new FeatureLayer({
                        url: gsvrUrl + "/geoserver/gsr/services/temanjabar/FeatureServer/7",
                        customParameters: {
                            ak: authKey
                        },
                        title: 'Hasil Survei Kondisi Jalan (Titik)',
                        id: 'rjp_skj_titik',
                        outFields: ["*"],
                        popupTemplate: popupTemplate,
                        renderer: {
                            type: "unique-value", // autocasts as new UniqueValueRenderer()
                            valueExpression: "When($feature.eiri <= 4, 'Baik', $feature.eiri > 4 && $feature.eiri <= 8, 'Sedang', $feature.eiri > 8 && $feature.eiri <= 12, 'Rusak Ringan', 'Rusak Berat')",
                            uniqueValueInfos: [{
                                    value: 'Baik',
                                    symbol: {
                                        type: "simple-marker", // autocasts as new SimpleMarkerSymbol()
                                        color: "green",
                                        size: "15px",
                                        style: "circle",
                                    },
                                },
                                {
                                    value: 'Sedang',
                                    symbol: {
                                        type: "simple-marker", // autocasts as new SimpleMarkerSymbol()
                                        color: "orange",
                                        size: "15px",
                                        style: "circle",
                                    },
                                },
                                {
                                    value: 'Rusak Ringan',
                                    symbol: {
                                        type: "simple-marker", // autocasts as new SimpleMarkerSymbol()
                                        color: "red",
                                        size: "15px",
                                        style: "circle",
                                    },
                                },
                                {
                                    value: 'Rusak Berat',
                                    symbol: {
                                        type: "simple-marker", // autocasts as new SimpleMarkerSymbol()
                                        color: "#990b0b",
                                        size: "15px",
                                        style: "circle",
                                    },
                                },
                            ]
                        }
                    });
                    map.add(rjp_skj_titik);
                }
                // rjp_skj.definitionExpression = whereUptd;
            }


            /* Deleted Proyek Kontrak
                function addProgressGroup(progress) {
                    const symbol = {
                        type: "picture-marker", // autocasts as new PictureMarkerSymbol()
                        url: baseUrl + "/assets/images/marker/pembangunan.png",
                        width: "24px",
                        height: "24px"
                    };
                    const popupTemplate = {
                        title: "{NAMA_PAKET}",
                        content: [{
                                type: "fields",
                                fieldInfos: [{
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
                            },
                            {
                                type: "media",
                                mediaInfos: [{
                                    title: "<b>Foto Pekerjaan</b>",
                                    type: "image",
                                    value: {
                                        sourceURL: baseUrl + "/assets/images/sample/sample.png"
                                    }
                                }]
                            },
                            {
                                title: "<b>Video Pekerjaan</b>",
                                type: "custom",
                                outFields: ["*"],
                                creator: function(graphic) {
                                    return `
                                        <div class="esri-feature-media__item">
                                            <video controls class="esri-feature-media__item">
                                                <source src="${baseUrl}/assets/videos/sample.mp4" type="video/mp4">
                                            </video>
                                        </div>`;
                                }
                            }
                        ]
                    };
                    const fields = [{
                            name: "ID",
                            alias: "ID",
                            type: "integer"
                        },
                        {
                            name: "TANGGAL",
                            alias: "Tanggal",
                            type: "string"
                        },
                        {
                            name: "WAKTU_KONTRAK",
                            alias: "Waktu Kontrak",
                            type: "integer"
                        },
                        {
                            name: "TERPAKAI",
                            alias: "Terpakai",
                            type: "integer"
                        },
                        {
                            name: "JENIS_PEKERJAAN",
                            alias: "Jenis Pekerjaan",
                            type: "string"
                        },
                        {
                            name: "RUAS_JALAN",
                            alias: "Ruas Jalan",
                            type: "string"
                        },
                        {
                            name: "LAT",
                            alias: "Latitude",
                            type: "double"
                        },
                        {
                            name: "LONG",
                            alias: "Longitude",
                            type: "double"
                        },
                        {
                            name: "LOKASI",
                            alias: "Lokasi",
                            type: "string"
                        },
                        {
                            name: "SUP",
                            alias: "SUP",
                            type: "string"
                        },
                        {
                            name: "RENCANA",
                            alias: "Rencana",
                            type: "double"
                        },
                        {
                            name: "REALISASI",
                            alias: "Realisasi",
                            type: "double"
                        },
                        {
                            name: "DEVIASI",
                            alias: "Deviasi",
                            type: "double"
                        },
                        {
                            name: "NILAI_KONTRAK",
                            alias: "Nilai Kontrak",
                            type: "double"
                        },
                        {
                            name: "PENYEDIA_JASA",
                            alias: "Penyedia Jasa",
                            type: "string"
                        },
                        {
                            name: "KEGIATAN",
                            alias: "Kegiatan",
                            type: "string"
                        },
                        {
                            name: "STATUS_PROYEK",
                            alias: "Status",
                            type: "string"
                        },
                        {
                            name: "UPTD",
                            alias: "UPTD",
                            type: "string"
                        }
                    ];

                // cari dan hapus layer bila ada pd map
                let allProgressLayer = map.findLayerById('progress_all');
                if (allProgressLayer) {
                    map.remove(allProgressLayer);
                }
                let newAllProgressLayer = new GroupLayer({
                    title: 'Progress  Proyek Kontrak',
                    id: 'progress_all'
                });

                    // buat layer baru
                    let newOn = [],
                        newOff = [],
                        newCrit = [],
                        newFin = [];
                    progress.forEach(item => {
                        let point = new Point(item.LNG, item.LAT);
                        let fitur = new Graphic({
                            geometry: point,
                            attributes: item
                        });
                        switch (item.STATUS_PROYEK) {
                            case "ON PROGRESS":
                                newOn.push(fitur);
                                break;
                            case "OFF PROGRESS":
                                newOff.push(fitur);
                                break;
                            case "CRITICAL CONTRACT":
                                newCrit.push(fitur);
                                break;
                            case "FINISH":
                                newFin.push(fitur);
                                break;
                            default:
                                break;
                        }
                    });

                    let onProgress = new FeatureLayer({
                        title: "On-Progress",
                        id: "onprogress",
                        fields: fields,
                        objectIdField: "ID",
                        geometryType: "point",
                        spatialReference: {
                            wkid: 4326
                        },
                        source: newOn,
                        popupTemplate: popupTemplate,
                        renderer: {
                            type: "simple",
                            symbol: symbol
                        }
                    });
                    let offProgress = new FeatureLayer({
                        title: "Off-Progress",
                        id: "offprogress",
                        fields: fields,
                        objectIdField: "ID",
                        geometryType: "point",
                        spatialReference: {
                            wkid: 4326
                        },
                        source: newOff,
                        popupTemplate: popupTemplate,
                        renderer: {
                            type: "simple",
                            symbol: symbol
                        }
                    });
                    let criticalProgress = new FeatureLayer({
                        title: "Critical",
                        id: "criticalprogress",
                        fields: fields,
                        objectIdField: "ID",
                        geometryType: "point",
                        spatialReference: {
                            wkid: 4326
                        },
                        source: newCrit,
                        popupTemplate: popupTemplate,
                        renderer: {
                            type: "simple",
                            symbol: symbol
                        }
                    });
                    let finishProgress = new FeatureLayer({
                        title: "Finish",
                        id: "finishprogress",
                        fields: fields,
                        objectIdField: "ID",
                        geometryType: "point",
                        spatialReference: {
                            wkid: 4326
                        },
                        source: newFin,
                        popupTemplate: popupTemplate,
                        renderer: {
                            type: "simple",
                            symbol: symbol
                        }
                    });

                    newAllProgressLayer.add(onProgress);
                    newAllProgressLayer.add(offProgress);
                    newAllProgressLayer.add(criticalProgress);
                    newAllProgressLayer.add(finishProgress);
                    map.add(newAllProgressLayer);
                }
            */
        });
    });
</script>
</html>
