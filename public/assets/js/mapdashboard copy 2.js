function getMap(baseUrl, gsvrUrl) {
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

                if (hasTanggal(kegiatan)) {
                    $('#filterDate').removeClass('d-none');

                    let today = new Date().toISOString().substr(0, 10);;
                    $('.sampaiTanggal').val(today);
                    $('.mulaiTanggal').val("2000-01-01");
                } else {
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
                if ($.inArray('kondisijalan_titik', kegiatan) >= 0) {
                    addPembangunan();
                } else {
                    map.remove(map.findLayerById('pr_bangun'));
                }
                if ($.inArray('kondisijalan_titik', kegiatan) >= 0) {
                    addPeningkatan();
                } else {
                    map.remove(map.findLayerById('pr_tingkat'));
                }
                if ($.inArray('kondisijalan_titik', kegiatan) >= 0) {
                    addRehabilitasi();
                } else {
                    map.remove(map.findLayerById('pr_rehab'));
                }

                if (kegiatan.length > 0) { // kalau masih ada pilihan lain di kegiatan
                    // Request data from API
                    let requestUrl = baseUrl + '/api/map/dashboard/data';
                    let requestBody = new FormData();

                    const date_from = $('.mulaiTanggal').val();
                    const date_to = $('.sampaiTanggal').val();
                    requestBody.append("date_from", date_from);
                    requestBody.append("date_to", date_to);

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
                        // console.log(date_from);
                        // console.log(date_to);
                        // console.log(json);
                        if (json.status === "success") {
                            if (kegiatan.indexOf('jembatan') >= 0) {
                                addJembatan(data.jembatan);
                            } else {
                                map.remove(map.findLayerById('jembatan'));
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
                                altText: "Foto Tidak Dapat Ditampilkan",
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
                // console.log(rawanbencana);
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
                                altText: "Foto Tidak Dapat Ditampilkan",
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
                                // console.log(feature.graphic.attributes);
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

            function addTitikKondisiJalan() {
                const popupTemplate = {
                    title: "{nm_ruas}",
                    content: [{
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
                                label: "Altitude / 10"
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
                    }],
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
                    map.add(rjp_skj_titik, 0);
                }
                // rjp_skj.definitionExpression = whereUptd;
            }

            function addJembatan(jembatan) {
                var prepImg = {
                    title: "Lihat Foto",
                    id: "prep-img",
                    className: "feather icon-image"
                };
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
                            type: "custom",
                            title: "<b>Foto Jembatan</b>",
                            outFields: ["*"],
                            creator: function(graphic) {
                                const vidElem = document.createElement('div');
                                vidElem.id = 'imgjembatan'; // + f.graphic.attributes.ID;
                                return vidElem;
                            }
                        }
                    ],
                    actions: [prepImg]
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
                        },
                        {
                            name: "FOTO",
                            alias: "Foto",
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


                view.popup.on("trigger-action", function(event) {
                    if (event.action.id === "prep-img") {
                        var attributes = view.popup.viewModel.selectedFeature.attributes;
                        var foto = attributes.FOTO;
                        let fotoArr = foto.split(',');
                        const vidElem = document.getElementById('imgjembatan');
                        fotoArr.forEach(foto => {
                            vidElem.innerHTML += `
                                <img src="${baseUrl}/storage/${foto}"/>
                            `;
                        });
                        $('div.esri-popup__action[title="Lihat Foto"]').remove();
                    }
                });

                view.when(function() {
                    view.popup.watch("selectedFeature", function(graphic) {
                        if (graphic) {
                            var graphicTemplate = graphic.getEffectivePopupTemplate();
                            graphicTemplate.actions.items[0].visible = (graphic.attributes.FOTO != '') ?
                                true :
                                false;
                        }
                    });
                });

                map.add(newJembatanLayer);
            }

            function addPembangunan() {
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

                // let uptdSel = $('#uptd').val();
                // let whereUptd = 'uptd=' + uptdSel.shift().charAt(4);
                // $.each(uptdSel, function(idx, elem) {
                //     whereUptd = whereUptd + ' OR uptd=' + elem.charAt(4);
                // });
                let pr_bangun = map.findLayerById('pr_bangun');
                if (!pr_bangun) {
                    pr_bangun = new FeatureLayer({
                        url: gsvrUrl + "/geoserver/gsr/services/temanjabar/FeatureServer/10",
                        title: 'Pembangunan',
                        id: 'pr_bangun',
                        outFields: ["*"],
                        popupTemplate: popupTemplate,
                        renderer: {
                            type: "simple",
                            symbol: symbol
                        }
                    });
                    map.add(pr_bangun, 0);
                }
                // pr_bangun.definitionExpression = whereUptd;
            }

            function addPeningkatan() {
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
                // let uptdSel = $('#uptd').val();
                // let whereUptd = 'uptd=' + uptdSel.shift().charAt(4);
                // $.each(uptdSel, function(idx, elem) {
                //     whereUptd = whereUptd + ' OR uptd=' + elem.charAt(4);
                // });
                let pr_tingkat = map.findLayerById('pr_tingkat');
                if (!pr_tingkat) {
                    pr_tingkat = new FeatureLayer({
                        url: gsvrUrl + "/geoserver/gsr/services/temanjabar/FeatureServer/9",
                        title: 'Peningkatan',
                        id: 'pr_tingkat',
                        outFields: ["*"],
                        popupTemplate: popupTemplate,
                        renderer: {
                            type: "simple",
                            symbol: symbol
                        }
                    });
                    map.add(pr_tingkat, 0);
                }
                // pr_bangun.definitionExpression = whereUptd;
            }

            function addRehabilitasi() {
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
                // let uptdSel = $('#uptd').val();
                // let whereUptd = 'uptd=' + uptdSel.shift().charAt(4);
                // $.each(uptdSel, function(idx, elem) {
                //     whereUptd = whereUptd + ' OR uptd=' + elem.charAt(4);
                // });
                let pr_rehab = map.findLayerById('pr_tingkat');
                if (!pr_rehab) {
                    pr_rehab = new FeatureLayer({
                        url: gsvrUrl + "/geoserver/gsr/services/temanjabar/FeatureServer/8",
                        title: 'Rehabilitasi',
                        id: 'pr_rehab',
                        outFields: ["*"],
                        popupTemplate: popupTemplate,
                        renderer: {
                            type: "simple",
                            symbol: symbol
                        }
                    });
                    map.add(pr_rehab, 0);
                }
                // pr_bangun.definitionExpression = whereUptd;
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
                                altText: "Foto Tidak Dapat Ditampilkan",
                                value: {
                                    sourceURL: `${baseUrl}/storage/pekerjaan/{FOTO_AWAL}`
                                }
                            }]
                        },
                        {
                            type: "media",
                            mediaInfos: [{
                                title: "<b>Foto Pekerjaan</b>",
                                type: "image",
                                altText: "Foto Tidak Dapat Ditampilkan",
                                value: {
                                    sourceURL: `${baseUrl}/storage/pekerjaan/{FOTO_SEDANG}`
                                }
                            }]
                        },
                        {
                            type: "media",
                            mediaInfos: [{
                                title: "<b>Foto Pekerjaan</b>",
                                type: "image",
                                altText: "Foto Tidak Dapat Ditampilkan",
                                value: {
                                    sourceURL: `${baseUrl}/storage/pekerjaan/{FOTO_AKHIR}`
                                }
                            }]
                        },
                        {
                            title: "<b>Video Pekerjaan</b>",
                            type: "custom",
                            outFields: ["*"],
                            creator: function(feature) {
                                var video = feature.graphic.attributes.VIDEO;
                                return `
                                    <div class="esri-feature-media__item">
                                        <video controls class="esri-feature-media__item">
                                            <source src="${baseUrl}/storage/pekerjaan/${video}" type="video/mp4">
                                            Video tidak ada atau tidak dapat ditampilkan!
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
                            name: "RUAS_JALAN",
                            alias: "Ruas Jalan",
                            type: "string"
                        },
                        {
                            name: "FOTO_AWAL",
                            alias: "Foto Awal",
                            type: "string"
                        },
                        {
                            name: "FOTO_SEDANG",
                            alias: "Foto Sedang",
                            type: "string"
                        },
                        {
                            name: "FOTO_AKHIR",
                            alias: "Foto Akhir",
                            type: "string"
                        },
                        {
                            name: "VIDEO",
                            alias: "Video",
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
                    title: "Lihat CCTV",
                    id: "prep-vid-vc",
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
                                vidElem.style = 'background:gray;';
                                vidElem.width = '275';
                                vidElem.height = '200';
                                return vidElem;
                            })
                        },
                        {
                            type: "fields",
                            fieldInfos: [{
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

                function aprepVid() {
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
                    if (event.action.id === "prep-vid-vc") {
                        aprepVid();
                        $('div.esri-popup__action[title="Lihat CCTV"]').remove();
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
                            name: "URL",
                            alias: "URL",
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
                        $('div.esri-popup__action[title="Lihat Video"]').remove();
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

        });
}

function dep(params) {
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
}

