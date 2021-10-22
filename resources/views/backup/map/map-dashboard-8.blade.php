<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

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

    <title>Map Dashboard</title>
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
                <a href="{{ url('/admin/monitoring/kendali-kontrak') }}">
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

<!-- chosen -->

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
        kegiatan = `<option value="ruasjalan">Ruas Jalan</option>
                    <option value="pembangunan">Pembangunan</option>
                    <option value="peningkatan">Peningkatan</option>
                    <option value="rehabilitasi">Rehabilitasi</option>
                    <option value="pemeliharaan">Pemeliharaan</option>
                    <option value="vehiclecounting">Vehicle Counting</option>
                    <option value="kemantapanjalan">Kemantapan Jalan</option>
                    <option value="jembatan">Jembatan</option>
                    <option value="cctv">CCTV</option>
                    <option value="rawanbencana">Titik Rawan Bencana</option>
                    <option value="datarawanbencana">Area Rawan Bencana</option>`;
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
                // kegiatan.push("progressmingguan");
                if ($.inArray('datarawanbencana', kegiatan) >= 0) {
                    rawanBencana();
                    kegiatan.splice(kegiatan.indexOf('datarawanbencana'), 1); // remove 'ruasjalan' dari kegiatan
                } else {
                    map.remove(map.findLayerById('rbl'));
                }

                if ($.inArray('ruasjalan', kegiatan) >= 0) {
                    addRuteJalan();
                    kegiatan.splice(kegiatan.indexOf('ruasjalan'), 1); // remove 'ruasjalan' dari kegiatan
                } else {
                    map.remove(map.findLayerById('rj'));
                }
                if ($.inArray('kemantapanjalan', kegiatan) >= 0) {
                    addKemantapanJalan();
                    kegiatan.splice(kegiatan.indexOf('kemantapanjalan'), 1); // remove 'kemantapanjalan' dari kegiatan
                } else {
                    map.remove(map.findLayerById('rj_mantap'));
                }

                if (kegiatan.length > 0) { // kalau masih ada pilihan lain di kegiatan
                    // Request data from API
                    let requestUrl = baseUrl + '/api/map/dashboard/data';
                    let requestBody = new FormData();

                    const date_from = $('.mulaiTanggal').val();
                    const date_to = $('.sampaiTanggal').val();
                    requestBody.append("date_from",date_from);
                    requestBody.append("date_to",date_to);

                    for (i in kegiatan) {
                        requestBody.append("kegiatan[]", kegiatan[i]);
                    }
                    for (i in sup) {
                        requestBody.append("sup[]", sup[i]);
                    }

                    let requestApi = esriRequest(requestUrl, {
                        responseType: "json",
                        method: "post",
                        body: requestBody
                    }).then(function(response) {
                        let json = response.data;
                        let data = json.data;
                        console.log(date_from);
                        console.log(date_to);
                        console.log(json);
                        if (json.status === "success") {
                            if (kegiatan.indexOf('jembatan') >= 0) {
                                addJembatan(data.jembatan);
                            } else {
                                map.remove(map.findLayerById('jembatan'));
                            }
                            if (kegiatan.indexOf('pembangunan') >= 0) {
                                addPembangunan(data.pembangunan);
                            } else {
                                map.remove(map.findLayerById('pr_bangun'));
                            }
                            if (kegiatan.indexOf('peningkatan') >= 0) {
                                addPeningkatan(data.peningkatan);
                            } else {
                                map.remove(map.findLayerById('pr_tingkat'));
                            }
                            if (kegiatan.indexOf('rehabilitasi') >= 0) {
                                addRehabilitasi(data.rehabilitasi);
                            } else {
                                map.remove(map.findLayerById('pr_rehab'));
                            }
                            if (kegiatan.indexOf('pemeliharaan') >= 0) {
                                addPemeliharaan(data.pemeliharaan);
                            } else {
                                map.remove(map.findLayerById('pr_pem'));
                            }
                            if (kegiatan.indexOf('vehiclecounting') >= 0) {
                                addVehicleCounting(data.vehiclecounting);
                            } else {
                                map.remove(map.findLayerById('vc'));
                            }
                            if (kegiatan.indexOf('rawanbencana') >= 0) {
                                addTitikRawanBencana(data.rawanbencana, data.iconrawanbencana);
                            } else {
                                map.remove(map.findLayerById('tx_rawanbencana'));
                            }
                            if (kegiatan.indexOf('cctv') >= 0) {
                                addCCTV(data.cctv);
                            } else {
                                map.remove(map.findLayerById('tx_cctv'));
                            }
                            /* Deleted Proyek Kontrak
                                if (kegiatan.indexOf('progressmingguan') >= 0) {
                                    addProgressGroup(data.progressmingguan);
                                } else {
                                    map.remove(map.findLayerById('progress_all'));
                                }
                            */

                            /* $("#proyek").chosen().change(function() {
                                const proyek = $("#proyek").val();
                                const allProyek = ["onprogress", "criticalprogress", "offprogress", "finishprogress"];
                                const diff = difference(allProyek, proyek);

                                for (i in proyek) {
                                    allProgressLayer.findLayerById(proyek[i]).visible = true;
                                }
                                for (i in diff) {
                                    allProgressLayer.findLayerById(diff[i]).visible = false;
                                }
                            }); */
                        } else { // json.status != success
                            // do something
                        }
                    }).catch(function(error) {
                        // do something
                        // console.log(error.httpStatus);
                    });
                }

                view.when(function() {
                    if (!view.ui.find("layerList")) {
                        // Add widget to the bottom right corner of the view
                        view.ui.add(layerList, "bottom-right");
                    }
                    if (!view.ui.find("lgd")) {
                        // Add widget to the bottom left corner of the view
                        view.ui.add(legend, "bottom-left");
                    }
                });
            }

            function addRuteJalan() {
                let rutejalanLayer = map.findLayerById('rj');
                if (!rutejalanLayer) {
                    rutejalanLayer = new GroupLayer({
                        title: 'Ruas Jalan',
                        id: 'rj'
                    });
                    rutejalanLayer.add(jalanTolKonstruksi(), 0);
                    rutejalanLayer.add(jalanTolOperasi(), 1);
                    rutejalanLayer.add(jalanNasional(), 2);
                    // rutejalanLayer.add(gerbangTol(), 4);

                    map.add(rutejalanLayer);
                }
                rutejalanLayer.add(jalanProvinsi(), 3);
                // rutejalanLayer.reorder();

                function jalanProvinsi() {
                    const popupTemplate = {
                        title: "{nm_ruas}",
                        content: [{
                            type: "fields",
                            fieldInfos: [{
                                    fieldName: "IDruas",
                                    label: "Kode Ruas"
                                },
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
                                    fieldName: "kab_kota",
                                    label: "Kab/Kota"
                                },
                                {
                                    fieldName: "wil_uptd",
                                    label: "UPTD"
                                },
                                {
                                    fieldName: "nm_sppjj",
                                    label: "SUP"
                                },
                                {
                                    fieldName: "expression/pjg_km",
                                }
                            ]
                        }],
                        expressionInfos: [{
                            name: "pjg_km",
                            title: "Panjang Ruas (KM)",
                            expression: "Round($feature.pjg_ruas_m / 1000, 2)"
                        }],
                        actions: [prepSVAction]
                    };
                    let uptdSel = $('#uptd').val();
                    let whereUptd = 'uptd=' + uptdSel.shift().charAt(4);
                    $.each(uptdSel, function(idx, elem) {
                        whereUptd = whereUptd + ' OR uptd=' + elem.charAt(4);
                    });
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
                    rjp.definitionExpression = whereUptd;
                    return rjp;
                }

                function jalanNasional() {
                    const layer = new FeatureLayer({
                        url: gsvrUrl + "/geoserver/gsr/services/temanjabar/FeatureServer/2/",
                        title: 'Ruas Jalan Nasional'
                    });
                    const popupTemplate = {
                        title: "{NAMA_SK}",
                        content: [{
                            type: "fields",
                            fieldInfos: [{
                                    fieldName: "NO_RUAS",
                                    label: "Nomor Ruas"
                                },
                                {
                                    fieldName: "PJG_SK",
                                    label: "Panjang (KM)"
                                },
                                {
                                    fieldName: "KLS_JALAN",
                                    label: "Kelas Jalan"
                                },
                                {
                                    fieldName: "LINTAS",
                                    label: "Lintas"
                                },
                                {
                                    fieldName: "TAHUN",
                                    label: "Tahun"
                                }
                            ]
                        }],
                        actions: [prepSVAction]
                    };
                    layer.popupTemplate = popupTemplate;
                    layer.renderer = {
                        type: "simple", // autocasts as new SimpleRenderer()
                        symbol: {
                            type: "simple-line", // autocasts as new SimpleLineSymbol()
                            color: "red",
                            width: "2px",
                            style: "solid",
                            //   marker: { // autocasts from LineSymbolMarker
                            //      color: "orange",
                            //   placement: "begin-end",
                            // style: "circle"
                            // }
                        }
                    }
                    return layer;
                }

                function jalanTolOperasi() {
                    const layer = new FeatureLayer({
                        url: gsvrUrl + "/geoserver/gsr/services/temanjabar/FeatureServer/3/",
                        title: 'Ruas Jalan Tol (Operasional)'
                    });
                    const popupTemplate = {
                        title: "{NAMA}",
                        content: [{
                            type: "fields",
                            fieldInfos: [{
                                    fieldName: "PANJANG",
                                    label: "Panjang"
                                },
                                {
                                    fieldName: "PENGELOLA",
                                    label: "Pengelola"
                                },
                                {
                                    fieldName: "STATUS",
                                    label: "Status"
                                },
                                {
                                    fieldName: "Kabupaten",
                                    label: "Kabupaten"
                                },
                                {
                                    fieldName: "Propinsi",
                                    label: "Propinsi"
                                }
                            ]
                        }]
                    };
                    layer.popupTemplate = popupTemplate;
                    layer.renderer = {
                        type: "simple", // autocasts as new SimpleRenderer()
                        symbol: {
                            type: "simple-line", // autocasts as new SimpleLineSymbol()
                            color: "yellow",
                            width: "2px",
                            style: "solid",
                            //marker: { // autocasts from LineSymbolMarker
                            //   color: "orange",
                            // placement: "begin-end",
                            //style: "circle"
                            //}
                        }
                    }
                    return layer;
                }

                function jalanTolKonstruksi() {
                    const layer = new FeatureLayer({
                        url: gsvrUrl + "/geoserver/gsr/services/temanjabar/FeatureServer/4/",
                        title: 'Ruas Jalan Tol (Konstruksi)'
                    });
                    const popupTemplate = {
                        title: "{Nama}",
                        content: [{
                            type: "fields",
                            fieldInfos: [{
                                    fieldName: "panjang",
                                    label: "Panjang"
                                },
                                {
                                    fieldName: "pengelola",
                                    label: "Pengelola"
                                },
                                {
                                    fieldName: "kabupaten",
                                    label: "Kabupaten"
                                },
                                {
                                    fieldName: "propinsi",
                                    label: "Propinsi"
                                },
                                {
                                    fieldName: "keterangan",
                                    label: "Keterangan"
                                }
                            ]
                        }]
                    };
                    layer.popupTemplate = popupTemplate;
                    layer.renderer = {
                        type: "simple", // autocasts as new SimpleRenderer()
                        symbol: {
                            type: "simple-line", // autocasts as new SimpleLineSymbol()
                            color: "purple",
                            width: "2px",
                            style: "solid",
                            // marker: { // autocasts from LineSymbolMarker
                            //   color: "orange",
                            // placement: "begin-end",
                            //style: "circle"
                            //}
                        }
                    }
                    return layer;
                }

                function gerbangTol() {
                    const layer = new FeatureLayer({
                        url: gsvrUrl + "/geoserver/gsr/services/temanjabar/FeatureServer/5/",
                        title: 'Gerbang Tol'
                    });
                    const popupTemplate = {
                        title: "{Nama}",
                        content: [{
                            type: "media",
                            mediaInfos: [{
                                title: "<b>Foto</b>",
                                type: "image",
                                value: {
                                    sourceURL: "{foto}"
                                }
                            }]
                        }],
                        expressionInfos: [{
                            name: "status",
                            title: "Status",
                            expression: "Konstruksi"
                        }]
                    };
                    layer.popupTemplate = popupTemplate;
                    return layer;
                }
            }

            function rawanBencana() {
                let rawanBencanaLayer = map.findLayerById('rbl');
                if (!rawanBencanaLayer) {
                    rawanBencanaLayer = new GroupLayer({
                        title: 'Rawan Bencana',
                        id: 'rbl'
                    });

                    rawanBencanaLayer.add(rawanGempaBumi(), 1);
                    rawanBencanaLayer.add(rawanGerakanTanah(), 0);
                    rawanBencanaLayer.add(rawanLongsor(), 3);
                    rawanBencanaLayer.add(indexResikoBanjir(), 4);
                    rawanBencanaLayer.add(indexResikoBanjirBandang(), 5);

                    map.add(rawanBencanaLayer);
                }

                function rawanGempaBumi() {
                    const popupTemplate = {
                        title: "{nm_ruas}",
                        content: [{
                            type: "fields",
                            fieldInfos: [{
                                    fieldName: "UNSUR",
                                    label: "Unsur"
                                },
                                {
                                    fieldName: "KETERANGAN",
                                    label: "Keterangan"
                                }
                            ]
                        }]
                    }
                    let rgt = map.findLayerById('rgtId');
                    if (!rgt) {
                        rgt = new FeatureLayer({
                            url: "https://satupeta.jabarprov.go.id/arcgis/rest/services/SATUPETA_BPBD/Kebencanaan/MapServer/1",
                            title: 'Gempa Bumi',
                            id: 'rgtId',
                            popupTemplate: popupTemplate
                        });
                        rgt.refresh();
                    }
                    return rgt;
                }

                function rawanGerakanTanah() {
                    const popupTemplate = {
                        title: "{nm_ruas}",
                        content: [{
                            type: "fields",
                            fieldInfos: [{
                                    fieldName: "GERTAN",
                                    label: "gertan"
                                },
                                {
                                    fieldName: "SUMBER",
                                    label: "sumber"
                                }
                            ]
                        }]
                    }
                    let rgt2 = map.findLayerById('rgt2Id');
                    if (!rgt2) {
                        rgt2 = new FeatureLayer({
                            url: "https://satupeta.jabarprov.go.id/arcgis/rest/services/SATUPETA_BPBD/Kebencanaan/MapServer/0",
                            title: 'Rawan Gerakan Tanah',
                            id: 'rgt2Id',
                            popupTemplate: popupTemplate
                        });
                        rgt2.refresh();
                    }
                    return rgt2;
                }


                function indexResikoBanjir() {
                    const popupTemplate = {
                        title: "{nm_ruas}",
                        content: [{
                            type: "fields",
                            fieldInfos: [{
                                    fieldName: "kelas",
                                    label: "kelas"
                                },
                                {
                                    fieldName: "Shape_Leng",
                                    label: "Shape Leng"
                                },
                                {
                                    fieldName: "Shape_Area",
                                    label: "Shape Area"
                                },
                                {
                                    fieldName: "Luas_HA",
                                    label: "Luas HA"
                                }
                            ]
                        }]
                    }
                    let irb = map.findLayerById('irbId');
                    if (!irb) {
                        irb = new FeatureLayer({
                            url: "https://satupeta.jabarprov.go.id/arcgis/rest/services/SATUPETA_BPBD/Kebencanaan/MapServer/7",
                            title: 'Index Resiko Banjir',
                            id: 'irbId',
                            popupTemplate: popupTemplate
                        });
                        irb.refresh();
                    }
                    return irb;
                }


                function indexResikoBanjirBandang() {
                    const popupTemplate = {
                        title: "{nm_ruas}",
                        content: [{
                            type: "fields",
                            fieldInfos: [{
                                    fieldName: "kelas",
                                    label: "kelas"
                                },
                                {
                                    fieldName: "Shape_Leng",
                                    label: "Shape Leng"
                                },
                                {
                                    fieldName: "Shape_Area",
                                    label: "Shape Area"
                                }

                            ]
                        }]
                    }
                    let irbb = map.findLayerById('irbbId');
                    if (!irbb) {
                        irbb = new FeatureLayer({
                            url: "https://satupeta.jabarprov.go.id/arcgis/rest/services/SATUPETA_BPBD/Kebencanaan/MapServer/8",
                            title: 'Index Resiko Banjir Bandang',
                            id: 'irbbId',
                            popupTemplate: popupTemplate
                        });
                        irbb.refresh();
                    }
                    return irbb;
                }


                function rawanLongsor() {
                    const popupTemplate = {
                        title: "{nm_ruas}",
                        content: [{
                            type: "fields",
                            fieldInfos: [{
                                    fieldName: "Shape",
                                    label: "Shape"
                                },
                                {
                                    fieldName: "OBJECTID",
                                    label: "OBJECTID "
                                },
                                {
                                    fieldName: "GRIDCODE",
                                    label: "GRIDCODE"
                                },
                                {
                                    fieldName: "kelas",
                                    label: "kelas "
                                },
                                {
                                    fieldName: "Shape_Leng",
                                    label: "Shape_Leng "
                                },
                                {
                                    fieldName: "Shape_Area",
                                    label: "Shape_Area "
                                }
                            ]
                        }]
                    }
                    let longsor = map.findLayerById('longsorId');
                    if (!longsor) {
                        longsor = new FeatureLayer({
                            url: "https://satupeta.jabarprov.go.id/arcgis/rest/services/SATUPETA_BPBD/Kebencanaan/MapServer/9",
                            title: 'Longsor',
                            id: 'longsorId',
                            popupTemplate: popupTemplate
                        });
                        longsor.refresh();
                    }
                    return longsor;
                }

            }

            function addTitikRawanBencana(rawanbencana, iconrawanbencana) {
                let uniqueValue = [];
                console.log(rawanbencana);
                iconrawanbencana.forEach((data) => {
                    uniqueValue.push({
                        value: data.ICON_NAME,
                        symbol: {
                            type: "picture-marker", // autocasts as new PictureMarkerSymbol()
                            url: data.ICON_IMAGE,
                            width: "28px",
                            height: "28px"
                        }
                    });
                });

                const popupTemplate = {
                    title: "{RUAS_JALAN}",
                    content: [{
                            type: "fields",
                            fieldInfos: [{
                                    fieldName: "NO_RUAS",
                                    label: "Nomor Ruas",
                                },
                                {
                                    fieldName: "STATUS",
                                    label: "Status",
                                },
                                {
                                    fieldName: "LOKASI",
                                    label: "Lokasi",
                                },
                                {
                                    fieldName: "DAERAH",
                                    label: "Daerah",
                                },
                                {
                                    fieldName: "LAT",
                                    label: "Latitude",
                                },
                                {
                                    fieldName: "LONG",
                                    label: "Longitude",
                                },
                                {
                                    fieldName: "KETERANGAN",
                                    label: "Keterangan",
                                },
                                {
                                    fieldName: "SUP",
                                    label: "SUP",
                                },
                                {
                                    fieldName: "UPTD_ID",
                                    label: "UPTD",
                                }
                            ]
                        },
                        {
                            type: "media",
                            mediaInfos: [{
                                title: "<b>Foto Aktual</b>",
                                type: "image",
                                value: {
                                    sourceURL: "{FOTO}"
                                }
                            }]
                        }
                    ]
                };

                // cari dan hapus layer bila ada pd map
                let titikRawanBencanaLayer = map.findLayerById('tx_rawanbencana');
                if (titikRawanBencanaLayer) {
                    map.remove(titikRawanBencanaLayer);
                }

                // buat layer baru
                let newTitikRawanBencana = [];
                rawanbencana.forEach(item => {
                    let point = new Point(item.LONG, item.LAT);
                    newTitikRawanBencana.push(new Graphic({
                        geometry: point,
                        attributes: item
                    }));
                });
                let newTitikRawanBencanaLayer = new FeatureLayer({
                    title: 'Titik Rawan Bencana',
                    id: 'tx_rawanbencana',
                    fields: [{
                            name: "ID",
                            alias: "ID",
                            type: "integer"
                        },
                        {
                            name: "NO_RUAS",
                            alias: "Nomor Ruas",
                            type: "string"
                        },
                        {
                            name: "STATUS",
                            alias: "Status",
                            type: "string"
                        },
                        {
                            name: "RUAS_JALAN",
                            alias: "Ruas Jalan",
                            type: "string"
                        },
                        {
                            name: "LOKASI",
                            alias: "Lokasi",
                            type: "string"
                        },
                        {
                            name: "DAERAH",
                            alias: "Daerah",
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
                            name: "KETERANGAN",
                            alias: "Keterangan",
                            type: "string"
                        },
                        {
                            name: "FOTO",
                            alias: "Foto",
                            type: "string"
                        },
                        {
                            name: "SUP",
                            alias: "SUP",
                            type: "string"
                        },
                        {
                            name: "ICON_NAME",
                            alias: "Jenis Titik Rawan Bencana",
                            type: "string"
                        },
                        {
                            name: "ICON_IMAGE",
                            alias: "Icon Image",
                            type: "string"
                        },
                        {
                            name: "UPTD_ID",
                            alias: "UPTD",
                            type: "string"
                        }
                    ],
                    outFields: ["*"],
                    objectIdField: "ID",
                    geometryType: "point",
                    spatialReference: {
                        wkid: 4326
                    },
                    source: newTitikRawanBencana,
                    popupTemplate: popupTemplate,
                    renderer: {
                        type: "unique-value", // autocasts as new UniqueValueRenderer()
                        field: "ICON_NAME",
                        uniqueValueInfos: uniqueValue
                    }
                });
                map.add(newTitikRawanBencanaLayer);
            }

            function addKemantapanJalan() {
                const popupTemplate = {
                    title: "{nm_ruas}",
                    content: [{
                            type: "media",
                            mediaInfos: [{
                                title: "<b>Kondisi Jalan</b>",
                                type: "pie-chart",
                                caption: "Dari Luas Jalan {l} m2",
                                value: {
                                    fields: ["sangat_baik", "baik", "sedang", "jelek", "parah", "sangat_parah", "hancur"]
                                }
                            }]
                        },
                        {
                            type: "media",
                            mediaInfos: [{
                                title: "<b>Jalan Mantap</b>",
                                type: "pie-chart",
                                value: {
                                    fields: ["sangat_baik", "baik", "sedang"]
                                }
                            }]
                        },
                        {
                            type: "media",
                            mediaInfos: [{
                                title: "<b>Jalan Tidak Mantap</b>",
                                type: "pie-chart",
                                value: {
                                    fields: ["jelek", "parah", "sangat_parah", "hancur"]
                                }
                            }]
                        },
                        {
                            type: "fields",
                            fieldInfos: [{
                                    fieldName: "idruas",
                                    label: "Nomor Ruas"
                                },
                                {
                                    fieldName: "KOTA_KAB",
                                    label: "Kota/Kabupaten"
                                },
                                {
                                    fieldName: "LAT_AWAL",
                                    label: "Latitude Awal"
                                },
                                {
                                    fieldName: "LONG_AWAL",
                                    label: "Longitude Awal"
                                },
                                {
                                    fieldName: "LAT_AKHIR",
                                    label: "Latitude Akhir"
                                },
                                {
                                    fieldName: "LONG_AKHIR",
                                    label: "Longitude Akhir"
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
                    ]
                };
                let uptdSel = $('#uptd').val();
                let whereUptd = 'uptd=' + uptdSel.shift().charAt(4);
                $.each(uptdSel, function(idx, elem) {
                    whereUptd = whereUptd + ' OR uptd=' + elem.charAt(4);
                });
                let rj_mantap = map.findLayerById('rj_mantap');
                if (!rj_mantap) {
                    rj_mantap = new FeatureLayer({
                        url: gsvrUrl + "/geoserver/gsr/services/temanjabar/FeatureServer/1/",
                        title: 'Kemantapan Jalan',
                        id: 'rj_mantap',
                        outFields: ["*"],
                        popupTemplate: popupTemplate,
                        renderer: {
                            type: "simple", // autocasts as new SimpleRenderer()
                            symbol: {
                                type: "simple-line", // autocasts as new SimpleLineSymbol()
                                color: "green",
                                width: "2px",
                                style: "solid",
                                marker: { // autocasts from LineSymbolMarker
                                    color: "orange",
                                    placement: "begin-end",
                                    style: "circle"
                                }
                            }
                        }
                    });
                    map.add(rj_mantap);
                }
                rj_mantap.definitionExpression = whereUptd;
            }

            function addJembatan(jembatan) {
                const symbol = {
                    type: "picture-marker", // autocasts as new PictureMarkerSymbol()
                    url: baseUrl + "/assets/images/marker/jembatan.png",
                    width: "24px",
                    height: "24px"
                };
                const popupTemplate = {
                    title: "{NAMA_JEMBATAN}",
                    content: [{
                            type: "fields",
                            fieldInfos: [{
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
                            ]
                        },
                        {
                            type: "media",
                            mediaInfos: [{
                                title: "<b>Foto Pekerjaan</b>",
                                type: "image",
                                value: {
                                    sourceURL: "{FOTO}"
                                }
                            }]
                        }
                    ]
                };

                // cari dan hapus layer bila ada pd map
                let jembatanLayer = map.findLayerById('jembatan');
                if (jembatanLayer) {
                    map.remove(jembatanLayer);
                }

                // buat layer baru
                let newJembatan = [];
                jembatan.forEach(item => {
                    let point = new Point(item.LNG, item.LAT);
                    newJembatan.push(new Graphic({
                        geometry: point,
                        attributes: item
                    }));
                });
                let newJembatanLayer = new FeatureLayer({
                    title: 'Jembatan',
                    id: 'jembatan',
                    fields: [{
                            name: "ID",
                            alias: "ID",
                            type: "integer"
                        },
                        {
                            name: "PANJANG",
                            alias: "Panjang",
                            type: "integer"
                        },
                        {
                            name: "LEBAR",
                            alias: "Lebar",
                            type: "integer"
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
                            name: "LNG",
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
                            name: "NAMA_JEMBATAN",
                            alias: "Nama Jembatan",
                            type: "string"
                        },
                        {
                            name: "UPTD",
                            alias: "UPTD",
                            type: "string"
                        }
                    ],
                    objectIdField: "ID",
                    geometryType: "point",
                    spatialReference: {
                        wkid: 4326
                    },
                    source: newJembatan,
                    popupTemplate: popupTemplate,
                    renderer: {
                        type: "simple",
                        symbol: symbol
                    }
                });
                map.add(newJembatanLayer);
            }

            function addPembangunan(pembangunan) {
                const symbol = {
                    type: "picture-marker", // autocasts as new PictureMarkerSymbol()
                    url: baseUrl + "/assets/images/marker/pembangunan.png",
                    width: "28px",
                    height: "28px"
                };
                const popupTemplate = {
                    title: "{NAMA_PAKET}",
                    content: [{
                        type: "fields",
                        fieldInfos: [{
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
                        ]
                    }]
                };

                // cari dan hapus layer bila ada pd map
                let pembangunanLayer = map.findLayerById('pr_bangun');
                if (pembangunanLayer) {
                    map.remove(pembangunanLayer);
                }

                // buat layer baru
                let newPembangunan = [];
                pembangunan.forEach(item => {
                    let point = new Point(item.LNG, item.LAT);
                    newPembangunan.push(new Graphic({
                        geometry: point,
                        attributes: item
                    }));
                });
                let newPembangunanLayer = new FeatureLayer({
                    title: 'Pembangunan',
                    id: 'pr_bangun',
                    fields: [{
                            name: "KODE_PAKET",
                            alias: "KODE_PAKET",
                            type: "integer"
                        },
                        {
                            name: "NOMOR_KONTRAK",
                            alias: "Nomor Kontrak",
                            type: "string"
                        },
                        {
                            name: "TGL_KONTRAK",
                            alias: "Tanggal Kontrak",
                            type: "string"
                        },
                        {
                            name: "WAKTU_PELAKSANAAN_HK",
                            alias: "Waktu Kontrak (Hari Kerja)",
                            type: "string"
                        },
                        {
                            name: "KEGIATAN",
                            alias: "Jenis Pekerjaan",
                            type: "string"
                        },
                        {
                            name: "JENIS_PENANGANAN",
                            alias: "Jenis Penanganan",
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
                            name: "LNG",
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
                            name: "NILAI_KONTRAK",
                            alias: "Nilai Kontrak",
                            type: "double"
                        },
                        {
                            name: "PAGU_ANGGARAN",
                            alias: "Pagu Anggaran",
                            type: "double"
                        },
                        {
                            name: "PENYEDIA_JASA",
                            alias: "Penyedia Jasa",
                            type: "string"
                        },
                        {
                            name: "UPTD",
                            alias: "UPTD",
                            type: "string"
                        }
                    ],
                    objectIdField: "KODE_PAKET",
                    geometryType: "point",
                    spatialReference: {
                        wkid: 4326
                    },
                    source: newPembangunan,
                    popupTemplate: popupTemplate,
                    renderer: {
                        type: "simple",
                        symbol: symbol
                    }
                });
                map.add(newPembangunanLayer);
            }

            function addPeningkatan(peningkatan) {
                const symbol = {
                    type: "picture-marker", // autocasts as new PictureMarkerSymbol()
                    url: baseUrl + "/assets/images/marker/peningkatan.png",
                    width: "28px",
                    height: "28px"
                };
                const popupTemplate = {
                    title: "{NAMA_PAKET}",
                    content: [{
                        type: "fields",
                        fieldInfos: [{
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
                            // {
                            //     fieldName: "RUAS_JALAN",
                            //     label: "Ruas Jalan"
                            // },
                            {
                                fieldName: "LAT",
                                label: "Latitude"
                            },
                            {
                                fieldName: "LNG",
                                label: "Longitude"
                            },
                            {
                                fieldName: "LOKASI_PEKERJAAN",
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
                        ]
                    }]
                };

                // cari dan hapus layer bila ada pd map
                let peningkatanLayer = map.findLayerById('pr_tingkat');
                if (peningkatanLayer) {
                    map.remove(peningkatanLayer);
                }

                // buat layer baru
                let newPeningkatan = [];
                peningkatan.forEach(item => {
                    let point = new Point(item.LNG, item.LAT);
                    newPeningkatan.push(new Graphic({
                        geometry: point,
                        attributes: item
                    }));
                });
                let newPeningkatanLayer = new FeatureLayer({
                    title: 'Peningkatan',
                    id: 'pr_tingkat',
                    fields: [{
                            name: "KODE_PAKET",
                            alias: "KODE_PAKET",
                            type: "integer"
                        },
                        {
                            name: "NOMOR_KONTRAK",
                            alias: "Nomor Kontrak",
                            type: "string"
                        },
                        {
                            name: "TGL_KONTRAK",
                            alias: "Tanggal Kontrak",
                            type: "string"
                        },
                        {
                            name: "WAKTU_PELAKSANAAN_HK",
                            alias: "Waktu Kontrak (Hari Kerja)",
                            type: "string"
                        },
                        {
                            name: "KEGIATAN",
                            alias: "Jenis Pekerjaan",
                            type: "string"
                        },
                        {
                            name: "JENIS_PENANGANAN",
                            alias: "Jenis Penanganan",
                            type: "string"
                        },
                        {
                            name: "LAT",
                            alias: "Latitude",
                            type: "double"
                        },
                        {
                            name: "LNG",
                            alias: "Longitude",
                            type: "double"
                        },
                        {
                            name: "LOKASI_PEKERJAAN",
                            alias: "Lokasi",
                            type: "string"
                        },
                        {
                            name: "SUP",
                            alias: "SUP",
                            type: "string"
                        },
                        {
                            name: "NILAI_KONTRAK",
                            alias: "Nilai Kontrak",
                            type: "double"
                        },
                        {
                            name: "PAGU_ANGGARAN",
                            alias: "Pagu Anggaran",
                            type: "double"
                        },
                        {
                            name: "PENYEDIA_JASA",
                            alias: "Penyedia Jasa",
                            type: "string"
                        },
                        {
                            name: "UPTD",
                            alias: "UPTD",
                            type: "string"
                        }
                    ],
                    objectIdField: "KODE_PAKET",
                    geometryType: "point",
                    spatialReference: {
                        wkid: 4326
                    },
                    source: newPeningkatan,
                    popupTemplate: popupTemplate,
                    renderer: {
                        type: "simple",
                        symbol: symbol
                    }
                });
                map.add(newPeningkatanLayer);
            }

            function addRehabilitasi(rehabilitasi) {
                const symbol = {
                    type: "picture-marker", // autocasts as new PictureMarkerSymbol()
                    url: baseUrl + "/assets/images/marker/rehabilitasi.png",
                    width: "32px",
                    height: "32px"
                };
                const popupTemplate = {
                    title: "{NAMA_PAKET}",
                    content: [{
                        type: "fields",
                        fieldInfos: [{
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
                        ]
                    }]
                };

                // cari dan hapus layer bila ada pd map
                let rehabilitasiLayer = map.findLayerById('pr_rehab');
                if (rehabilitasiLayer) {
                    map.remove(rehabilitasiLayer);
                }

                // buat layer baru
                let newRehabilitasi = [];
                rehabilitasi.forEach(item => {
                    let point = new Point(item.LNG, item.LAT);
                    newRehabilitasi.push(new Graphic({
                        geometry: point,
                        attributes: item
                    }));
                });
                let newRehabilitasiLayer = new FeatureLayer({
                    title: 'Rehabilitasi',
                    id: 'pr_rehab',
                    fields: [{
                            name: "KODE_PAKET",
                            alias: "KODE_PAKET",
                            type: "integer"
                        },
                        {
                            name: "NOMOR_KONTRAK",
                            alias: "Nomor Kontrak",
                            type: "string"
                        },
                        {
                            name: "TGL_KONTRAK",
                            alias: "Tanggal Kontrak",
                            type: "string"
                        },
                        {
                            name: "WAKTU_PELAKSANAAN_HK",
                            alias: "Waktu Kontrak (Hari Kerja)",
                            type: "string"
                        },
                        {
                            name: "KEGIATAN",
                            alias: "Jenis Pekerjaan",
                            type: "string"
                        },
                        {
                            name: "JENIS_PENANGANAN",
                            alias: "Jenis Penanganan",
                            type: "string"
                        },
                        {
                            name: "LAT",
                            alias: "Latitude",
                            type: "double"
                        },
                        {
                            name: "LNG",
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
                            name: "NILAI_KONTRAK",
                            alias: "Nilai Kontrak",
                            type: "double"
                        },
                        {
                            name: "PAGU_ANGGARAN",
                            alias: "Pagu Anggaran",
                            type: "double"
                        },
                        {
                            name: "PENYEDIA_JASA",
                            alias: "Penyedia Jasa",
                            type: "string"
                        },
                        {
                            name: "UPTD",
                            alias: "UPTD",
                            type: "string"
                        }
                    ],
                    objectIdField: "KODE_PAKET",
                    geometryType: "point",
                    spatialReference: {
                        wkid: 4326
                    },
                    source: newRehabilitasi,
                    popupTemplate: popupTemplate,
                    renderer: {
                        type: "simple",
                        symbol: symbol
                    }
                });
                map.add(newRehabilitasiLayer);
            }

            function addPemeliharaan(pemeliharaan) {
                const symbol = {
                    type: "picture-marker", // autocasts as new PictureMarkerSymbol()
                    url: baseUrl + "/assets/images/marker/pemeliharaan.png",
                    width: "28px",
                    height: "28px"
                };
                const popupTemplate = {
                    title: "{RUAS_JALAN}",
                    content: [{
                            type: "fields",
                            fieldInfos: [{
                                    fieldName: "TANGGAL",
                                    label: "Tanggal"
                                },
                                {
                                    fieldName: "JENIS_PEKERJAAN",
                                    label: "Jenis Pekerjaan"
                                },
                                {
                                    fieldName: "NAMA_MANDOR",
                                    label: "Nama Mandor"
                                },
                                {
                                    fieldName: "PANJANG",
                                    label: "Panjang "
                                },
                                {
                                    fieldName: "PERALATAN",
                                    label: "Peralatan"
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
                            ]
                        },
                        {
                            type: "media",
                            mediaInfos: [{
                                title: "<b>Foto Pekerjaan</b>",
                                type: "image",
                                value: {
                                    sourceURL: "{FOTO_AKHIR}"
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

                // cari dan hapus layer bila ada pd map
                let pemeliharaanLayer = map.findLayerById('pr_pem');
                if (pemeliharaanLayer) {
                    map.remove(pemeliharaanLayer);
                }

                // buat layer baru
                let newPemeliharaan = [];
                pemeliharaan.forEach(item => {
                    let point = new Point(item.LNG, item.LAT);
                    newPemeliharaan.push(new Graphic({
                        geometry: point,
                        attributes: item
                    }));
                });
                let newPemeliharaanLayer = new FeatureLayer({
                    title: 'Pemeliharaan',
                    id: 'pr_pem',
                    fields: [{
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
                            name: "JENIS_PEKERJAAN",
                            alias: "Jenis Pekerjaan",
                            type: "string"
                        },
                        {
                            name: "NAMA_MANDOR",
                            alias: "Nama Mandor",
                            type: "string"
                        },
                        {
                            name: "PANJANG",
                            alias: "Panjang",
                            type: "string"
                        },
                        {
                            name: "PERALATAN",
                            alias: "Peralatan",
                            type: "string"
                        },
                        {
                            name: "LAT",
                            alias: "Latitude",
                            type: "double"
                        },
                        {
                            name: "LNG",
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
                            name: "UPTD",
                            alias: "UPTD",
                            type: "string"
                        }
                    ],
                    objectIdField: "ID",
                    geometryType: "point",
                    spatialReference: {
                        wkid: 4326
                    },
                    source: newPemeliharaan,
                    popupTemplate: popupTemplate,
                    renderer: {
                        type: "simple",
                        symbol: symbol
                    }
                });
                map.add(newPemeliharaanLayer);
            }

            function addVehicleCounting(vehiclecounting) {
                const symbol = {
                    type: "picture-marker", // autocasts as new PictureMarkerSymbol()
                    url: baseUrl + "/assets/images/marker/vehiclecounting.png",
                    width: "24px",
                    height: "24px"
                };

                // Aksi untuk siapkan video player dari selected feature
                var prepVidAction = {
                    title: "Lihat Video",
                    id: "prep-vid",
                    className: "feather icon-video"
                };
                const popupTemplate = {
                    title: "{LOKASI}",
                    content: [{
                            type: "custom",
                            outFields: ["*"],
                            creator: (function(f) {
                                const vidElem = document.createElement('video');
                                vidElem.id = 'vid'; // + f.graphic.attributes.ID;
                                // vidElem.class = 'hls-video';
                                vidElem.style = 'background:gray;';
                                vidElem.width = '275';
                                vidElem.height = '200';
                                const vidSrcElem = document.createElement('source');
                                vidSrcElem.src = '//45.118.114.26:80/camera/TolPasteur.m3u8';
                                vidSrcElem.type = 'application/x-mpegURL';
                                vidElem.appendChild(vidSrcElem);
                                return vidElem;
                            })
                        },
                        {
                            type: "fields",
                            fieldInfos: [
                                {
                                    fieldName: "CHANNEL",
                                    label: "Channel"
                                },
                                {
                                    fieldName: "LAT",
                                    label: "Latitude"
                                },
                                {
                                    fieldName: "LONG",
                                    label: "Longitude"
                                },
                                {
                                    fieldName: "JUMLAH_ORANG_UP",
                                    label: "Jumlah Orang Up"
                                },
                                {
                                    fieldName: "JUMLAH_ORANG_DOWN",
                                    label: "Jumlah Orang Down"
                                },
                                {
                                    fieldName: "JUMLAH_ORANG_STAY",
                                    label: "Jumlah Orang Stay"
                                },
                                {
                                    fieldName: "JUMLAH_MOBIL_UP",
                                    label: "Jumlah Mobil Up"
                                },
                                {
                                    fieldName: "JUMLAH_MOBIL_DOWN",
                                    label: "Jumlah Mobil Down"
                                },
                                {
                                    fieldName: "JUMLAH_MOBIL_STAY",
                                    label: "Jumlah Mobil Stay"
                                },
                                {
                                    fieldName: "JUMLAH_MOTOR_UP",
                                    label: "Jumlah Motor Up"
                                },
                                {
                                    fieldName: "JUMLAH_MOTOR_DOWN",
                                    label: "Jumlah Motor Down"
                                },
                                {
                                    fieldName: "JUMLAH_MOTOR_STAY",
                                    label: "Jumlah Motor Stay"
                                },
                                {
                                    fieldName: "JUMLAH_BIS_UP",
                                    label: "Jumlah Bis Up"
                                },
                                {
                                    fieldName: "JUMLAH_BIS_DOWN",
                                    label: "Jumlah Bis Down"
                                },
                                {
                                    fieldName: "JUMLAH_BIS_STAY",
                                    label: "Jumlah Bis Stay"
                                },
                                {
                                    fieldName: "JUMLAH_TRUK_UP",
                                    label: "Jumlah Truk Up"
                                },
                                {
                                    fieldName: "JUMLAH_TRUK_DOWN",
                                    label: "Jumlah Truk Down"
                                },
                                {
                                    fieldName: "JUMLAH_TRUK_STAY",
                                    label: "Jumlah Truk Stay"
                                },
                                {
                                    fieldName: "SUP",
                                    label: "SUP"
                                },
                                {
                                    fieldName: "UPTD",
                                    label: "UPTD"
                                },
                                {
                                    fieldName: "CREATED_AT",
                                    label: "Terakhir Diperbarui"
                                },
                                {
                                    fieldName: "UPDATED_AT",
                                    label: "Upload Terakhir"
                                },
                            ]
                        }
                    ],
                    actions: [prepVidAction]
                };
                var player;

                function prepVid() {
                    // bila player udah didefinisikan sebelumnya
                    if (typeof(player) != 'undefined') {
                        player = null; // kosongkan pointer player
                    }
                    player = fluidPlayer(
                        'vid', {
                            layoutControls: {
                                fillToContainer: true,
                                autoPlay: true,
                            }
                        }
                    );
                }
                view.popup.on("trigger-action", function(event) {
                    if (event.action.id === "prep-vid") {
                        prepVid();
                        $('div.esri-popup__action[title="Lihat Video"]').remove();
                    }
                });

                // cari dan hapus layer bila ada pd map
                let veCountLayer = map.findLayerById('vc');
                if (veCountLayer) {
                    map.remove(veCountLayer);
                }

                // buat layer baru
                let newVC = [];
                vehiclecounting.forEach(item => {
                    let point = new Point(item.LONG, item.LAT);
                    newVC.push(new Graphic({
                        geometry: point,
                        attributes: item
                    }));
                });
                let newVeCountLayer = new FeatureLayer({
                    title: 'Jumlah Kendaraan (VC)',
                    id: 'vc',
                    fields: [{
                            name: "ID",
                            alias: "ID",
                            type: "integer"
                        },
                        {
                            name: "LOKASI",
                            alias: "Lokasi",
                            type: "string"
                        },
                        {
                            name: "CHANNEL",
                            alias: "Channel",
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
                            name: "JUMLAH_ORANG_UP",
                            alias: "Jumlah Orang Up",
                            type: "string"
                        },
                        {
                            name: "JUMLAH_ORANG_DOWN",
                            alias: "Jumlah Orang Down",
                            type: "string"
                        },
                        {
                            name: "JUMLAH_ORANG_STAY",
                            alias: "Jumlah Orang Stay",
                            type: "string"
                        },
                        {
                            name: "JUMLAH_MOBIL_UP",
                            alias: "Jumlah Mobil Up",
                            type: "string"
                        },
                        {
                            name: "JUMLAH_MOBIL_DOWN",
                            alias: "Jumlah Mobil Down",
                            type: "string"
                        },
                        {
                            name: "JUMLAH_MOBIL_STAY",
                            alias: "Jumlah Mobil Stay",
                            type: "string"
                        },
                        {
                            name: "JUMLAH_MOTOR_UP",
                            alias: "Jumlah Motor Up",
                            type: "string"
                        },
                        {
                            name: "JUMLAH_MOTOR_DOWN",
                            alias: "Jumlah Motor Down",
                            type: "string"
                        },
                        {
                            name: "JUMLAH_MOTOR_STAY",
                            alias: "Jumlah Motor Stay",
                            type: "string"
                        },
                        {
                            name: "JUMLAH_BIS_UP",
                            alias: "Jumlah Bis Up",
                            type: "string"
                        },
                        {
                            name: "JUMLAH_BIS_DOWN",
                            alias: "Jumlah Bis Down",
                            type: "string"
                        },
                        {
                            name: "JUMLAH_BIS_STAY",
                            alias: "Jumlah Bis Stay",
                            type: "string"
                        },
                        {
                            name: "JUMLAH_TRUK_UP",
                            alias: "Jumlah Truk Up",
                            type: "string"
                        },
                        {
                            name: "JUMLAH_TRUK_DOWN",
                            alias: "Jumlah Truk Down",
                            type: "string"
                        },
                        {
                            name: "JUMLAH_TRUK_STAY",
                            alias: "Jumlah Truk Stay",
                            type: "string"
                        },
                        {
                            name: "SUP",
                            alias: "SUP",
                            type: "string"
                        },
                        {
                            name: "UPTD",
                            alias: "UPTD",
                            type: "string"
                        },
                        {
                            name: "CREATED_AT",
                            alias: "Terakhir Diperbarui",
                            type: "string"
                        },
                        {
                            name: "UPDATED_AT",
                            alias: "Upload Terakhir",
                            type: "string"
                        },
                    ],
                    objectIdField: "ID",
                    geometryType: "point",
                    spatialReference: {
                        wkid: 4326
                    },
                    source: newVC,
                    popupTemplate: popupTemplate,
                    renderer: {
                        type: "simple",
                        symbol: symbol
                    }
                });
                map.add(newVeCountLayer);
            }

            function addCCTV(cctv) {
                const symbol = {
                    type: "picture-marker", // autocasts as new PictureMarkerSymbol()
                    url: baseUrl + "/assets/images/marker/cctv.png",
                    width: "24px",
                    height: "24px"
                };
                // Aksi untuk siapkan video player dari selected feature
                var prepVidAction = {
                    title: "Lihat Video",
                    id: "prep-vid",
                    className: "feather icon-video"
                };
                const popupTemplate = {
                    title: "{LOKASI}",
                    content: [{
                            title: "Video",
                            type: "custom",
                            outFields: ["*"],
                            creator: function(graphic) {
                                const vidElem = document.createElement('video');
                                vidElem.id = 'vid'; // + f.graphic.attributes.ID;
                                vidElem.style = 'background:gray;';
                                vidElem.width = '275';
                                vidElem.height = '200';
                                return vidElem;
                            }
                        },
                        {
                            type: "fields",
                            fieldInfos: [{
                                    fieldName: "LAT",
                                    label: "Latitude"
                                },
                                {
                                    fieldName: "LONG",
                                    label: "Longitude"
                                },
                                {
                                    fieldName: "URL",
                                    label: "Url"
                                },
                                {
                                    fieldName: "DESCRIPTION",
                                    label: "Deskripsi"
                                },
                                {
                                    fieldName: "CATEGORY",
                                    label: "Kategori"
                                },
                                {
                                    fieldName: "STATUS",
                                    label: "Status"
                                },
                                {
                                    fieldName: "SUP",
                                    label: "SUP"
                                },
                                {
                                    fieldName: "UPTD_ID",
                                    label: "UPTD"
                                }
                            ]
                        }
                    ],
                    actions: [prepVidAction]
                };
                var player;

                view.when(function() {
                    view.popup.watch("selectedFeature", function(graphic) {
                        if (graphic) {
                            var graphicTemplate = graphic.getEffectivePopupTemplate();
                            graphicTemplate.actions.items[0].visible = graphic.attributes.URL ?
                                true :
                                false;
                        }
                    });
                });

                function prepVid() {
                    // bila player udah didefinisikan sebelumnya
                    if (typeof(player) != 'undefined') {
                        player = null; // kosongkan pointer player
                    }
                    var attributes = view.popup.viewModel.selectedFeature.attributes;
                    var url = attributes.URL;

                    const vidElem = document.getElementById('vid');
                    const vidSrcElem = document.createElement('source');
                    vidSrcElem.src = url;
                    vidSrcElem.type = 'application/x-mpegURL';
                    vidElem.appendChild(vidSrcElem);

                    player = fluidPlayer(
                        'vid', {
                            layoutControls: {
                                fillToContainer: true,
                                autoPlay: true,
                            }
                        }
                    );
                }

                view.popup.on("trigger-action", function(event) {
                    if (event.action.id === "prep-vid") {
                        prepVid();
                        // $('div.esri-popup__action[title="Lihat Video"]').remove();
                    }
                });

                // cari dan hapus layer bila ada pd map
                let cctvLayer = map.findLayerById('tx_cctv');
                if (cctvLayer) {
                    map.remove(cctvLayer);
                }

                // buat layer baru
                let newCCTV = [];
                cctv.forEach(item => {
                    let point = new Point(item.LONG, item.LAT);
                    newCCTV.push(new Graphic({
                        geometry: point,
                        attributes: item
                    }));
                });
                let newCCTVLayer = new FeatureLayer({
                    title: 'CCTV',
                    id: 'tx_cctv',
                    fields: [{
                            name: "ID",
                            alias: "ID",
                            type: "integer"
                        },
                        {
                            name: "LOKASI",
                            alias: "Lokasi",
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
                            name: "DESCRIPTION",
                            alias: "Deskripsi",
                            type: "string"
                        },
                        {
                            name: "CATEGORY",
                            alias: "Kategori",
                            type: "string"
                        },
                        {
                            name: "URL",
                            alias: "URL",
                            type: "string"
                        },
                        {
                            name: "STATUS",
                            alias: "Status",
                            type: "string"
                        },
                        {
                            name: "SUP",
                            alias: "SUP",
                            type: "string"
                        },
                        {
                            name: "UPTD_ID",
                            alias: "UPTD_ID",
                            type: "string"
                        }
                    ],
                    objectIdField: "ID",
                    geometryType: "point",
                    spatialReference: {
                        wkid: 4326
                    },
                    source: newCCTV,
                    popupTemplate: popupTemplate,
                    renderer: {
                        type: "simple",
                        symbol: symbol
                    }
                });
                map.add(newCCTVLayer);
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
                        /* replaced
            function addKondisiJalan(){
                const popupTemplate = {
                    title: "{nm_ruas}",
                    content: [
                        {
                            type: "custom",
                            title: "<b>Survei Kondisi Jalan</b>",
                            outFields: ["*"],
                            creator: function (feature) {
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
            */
        });
        // end dimz-add
    });
</script>

</html>
