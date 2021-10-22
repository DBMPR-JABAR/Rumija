$(document).ready(() => {
            const pembagianProgres = document.getElementById("pembagian_progres");
            const targetContainer = document.getElementById("target_container");
            const buktiNavContainer = document.getElementById("bukti_nav_container");
            const buktiContentContainer = document.getElementById(
                "bukti_content_container"
            );
            const isVerifiedContainer = $("#isVerifiedOnly");
            isVerifiedContainer.hide();
            const isVerified = document.getElementById("gridRadios1");
            const isNoVerified = document.getElementById("gridRadios2");
            const rencanaText = document.getElementById("rencana_text");

            const disableFormRequired = () => {
                $("#ditunjukan_untuk").prop("required", false);
                $("#tanggal_smpk").prop("required", false);
                $("#no_smpk").prop("required", false);
                $("#penyedia_jasa").prop("required", false);
                $("#konsultasi_supervisi").prop("required", false);
                $("#nama_ppk").prop("required", false);
                $("#nama_se").prop("required", false);
                $("#nama_gs").prop("required", false);
                $("#pembagian_progres").prop("required", false);
                $("#waktu_pelaksanaan").prop("required", false);
            };

            if (isNoVerified && isNoVerified.checked) {
                disableFormRequired();
            }
            if (verified_access) {
                isNoVerified.onchange = (event) => {
                    if (event.target.checked) {
                        isVerifiedContainer.hide();
                        disableFormRequired();
                        if (action == "update")
                            rencanaText.innerText =
                            "Perbaharui Data Rencana Bantuan Keuangan";
                        else
                            rencanaText.innerText =
                            "Tambah Data Rencana Bantuan Keuangan";
                    }
                };
                isVerified.onchange = (event) => {
                    if (event.target.checked) {
                        isVerifiedContainer.show();
                        $("#ditunjukan_untuk").prop("required", true);
                        $("#tanggal_smpk").prop("required", true);
                        $("#no_smpk").prop("required", true);
                        $("#penyedia_jasa").prop("required", true);
                        $("#konsultasi_supervisi").prop("required", true);
                        $("#nama_ppk").prop("required", true);
                        $("#nama_se").prop("required", true);
                        $("#nama_gs").prop("required", true);
                        $("#pembagian_progres").prop("required", true);
                        $("#waktu_pelaksanaan").prop("required", true);
                        if (action == "update")
                            rencanaText.innerText =
                            "Perbaharui Data Realisasi Bantuan Keuangan";
                        else
                            rencanaText.innerText =
                            "Tambah Data Realisasi Bantuan Keuangan";
                    }
                };
            }

            const buktiTemplate = ({ ke, data }) => `<div class="tab-pane ${ke == 1 ? "active show" : ""
        }" id="bukti_${ke}" role="tabpanel" aria-selected="${ke == 1 ? "true" : "false"
        }">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Foto ${ke}.1</label>
                                        <div class="col-md-5">
                                            <img ${data &&
        data.foto_1 &&
        `src="${urlStorage}/${data.foto_1}"`
        } class="mx-auto rounded img-thumbnail d-block" id="foto_${ke}_preview_1">
                                        </div>
                                        <div class="col-md-5">
                                            <input id="foto_${ke}_1" name="foto_${ke}_1" type="file" accept="image/*" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Foto ${ke}.2</label>
                                        <div class="col-md-5">
                                            <img ${data &&
        data.foto_2 &&
        `src="${urlStorage}/${data.foto_2}"`
        } class="mx-auto rounded img-thumbnail d-block" id="foto_${ke}_preview_2">
                                        </div>
                                        <div class="col-md-5">
                                            <input id="foto_${ke}_2" name="foto_${ke}_2" type="file" accept="image/*" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Foto ${ke}.3</label>
                                        <div class="col-md-5">
                                            <img ${data &&
        data.foto_3 &&
        `src="${urlStorage}/${data.foto_3}"`
        } class="mx-auto rounded d-block img-thumbnail" id="foto_${ke}_preview_3">
                                        </div>
                                        <div class="col-md-5">
                                            <input id="foto_${ke}_3" name="foto_${ke}_3" type="file" accept="image/*" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Video ${ke}</label>
                                        <div class="col-md-5">
                                            <video ${data &&
        data.video &&
        `src="${urlStorage}/${data.video}"`
        } class="mx-auto rounded img-thumbnail d-block" id="video_${ke}_preview"
                                                src="" alt="" controls>
                                        </div>
                                        <div class="col-md-5">
                                            <input id="video_${ke}" name="video_${ke}" type="file" accept="video/mp4" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Dokumen ${ke} (Optional)</label>
                                            <div class="col-md-5">
                                                <input id="dokumen_${ke}" name="dokumen_${ke}" type="file" accept="application/pdf"
                                                    class="form-control">
                                            </div>
                                            ${(data &&
            data.dokumen &&
            action == "update" &&
            `<div class="col-md-3">
                                        <a href="${urlStorage}/${data.dokumen}" download><button type="button"
                                        class="btn btn-default waves-effect">Unduh</button></a>
                                        </div>`) ||
        ""
        }
                                        </div>


                                    <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Status Laporan Ke-${ke}</label>
                                    <div class="col-md-5">
                                        <p class="mt-1">${data &&
            data.is_verified &&
            data.is_verified == 1
            ? `<p style="color:green;font-weight:bold">Terverifikasi</p>`
            : data &&
                data.is_verified &&
                data.is_verified == 2
                ? `<p style="color:red;font-weight:bold">Ditolak dengan catatan "${data.catatan}", silahkan perbaharui<p>`
                : `<p style="color:gray;font-weight:bold">Belum diverifikasi</p>` ||
                `<p style="color:gray;font-weight:bold">Belum diverifikasi</p>`
        }</p>
                                    </div>
                                </div>
                                </div>`;

    const navTemplate = ({ ke, data }) => `<li class="nav-item">
                                    <a class="nav-link ${ke == 1 ? "active show" : ""
        }" data-toggle="tab" href="#bukti_${ke}" role="tab" aria-selected="${ke == 1 ? "true" : "false"
        }">Target ke-${ke}</a>
                                </li>`;

    const targetTemplate = ({ ke, data }) => `<div class=" form-group row">
                            <label class="col-md-4 col-form-label">Target ke-${ke}</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="date" ${data && `value="${data.tanggal}"`
        } name="tanggal_target_${ke}" class="form-control" ${!access && "readonly"
        }>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input ${data &&
        `value="${data.persentase}"`
        } type="number" name="persentase_target_${ke}" step="1" class="form-control" ${!access && "readonly"
        }>
                                            <span class="input-group-append">
                                                <label class="input-group-text">%</label>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;

    pembagianProgres.onchange = (event) => {
        let i = 1;
        const value = Number(event.target.value);
        let htmlTarget = "";
        let htmlBuktiNav = "";
        let htmlBuktiContent = "";
        const filePreviews = [];
        for (i; i <= value; i++) {
            filePreviews.push({
                input: `foto_${i}_1`,
                preview: `foto_${i}_preview_1`,
            });
            filePreviews.push({
                input: `foto_${i}_2`,
                preview: `foto_${i}_preview_2`,
            });
            filePreviews.push({
                input: `foto_${i}_3`,
                preview: `foto_${i}_preview_3`,
            });
            filePreviews.push({
                input: `video_${i}`,
                preview: `video_${i}_preview`,
            });
            htmlTarget += targetTemplate({ ke: i });
            htmlBuktiNav += navTemplate({ ke: i });
            htmlBuktiContent += buktiTemplate({ ke: i });
        }
        targetContainer.innerHTML = htmlTarget;
        buktiNavContainer.innerHTML = htmlBuktiNav;
        buktiContentContainer.innerHTML = htmlBuktiContent;

        filePreviews.forEach((data) => {
            const inputElement = document.getElementById(data.input);
            inputElement.onchange = (event) => {
                const [file] = inputElement.files;
                if (file)
                    document.getElementById(data.preview).src =
                        URL.createObjectURL(file);
            };
        });
    };

    const filePreviews = [
        {
            input: `foto_1_1`,
            preview: `foto_1_preview_1`,
        },
        {
            input: `foto_1_2`,
            preview: `foto_1_preview_2`,
        },
        {
            input: `foto_1_3`,
            preview: `foto_1_preview_3`,
        },
        {
            input: `video_1`,
            preview: `video_1_preview`,
        },
    ];

    filePreviews.forEach((data) => {
        const inputElement = document.getElementById(data.input);
        inputElement.onchange = (event) => {
            const [file] = inputElement.files;
            if (file)
                document.getElementById(data.preview).src =
                    URL.createObjectURL(file);
        };
    });

    const progressPercentage = document.getElementById("proggress_percent");
    const progressSlider = document.getElementById("proggress_slider");
    const onChange = (event) => {
        if (event.target.value < Number(progressBefore)) {
            progressPercentage.innerText = progressBefore;
            progressSlider.value = progressBefore;
        } else {
            progressPercentage.innerText = event.target.value;
            progressSlider.value = event.target.value;
        }
    };
    progressSlider.oninput = onChange;
    progressSlider.onclick = onChange;

    if (action == "store") progressSlider.value = 0;

    if (exitsData && exitsData.is_verified == "1") {
        $("#isVerifiedOnly").show();
    }

    if (exitsData && exitsProgres) {
        if (exitsData.is_verified == "1") {
            $("#isVerifiedOnly").show();
            const rencanaText = document.getElementById("rencana_text");
            if (action == "update")
                rencanaText.innerText =
                    "Perbaharui Data Realisasi Bantuan Keuangan";
            else
                rencanaText.innerText =
                    "Tambah Data Realisasi Bantuan Keuangan";
            isVerifiedContainer.show();
        }
        let i = 1;
        const value = Number(exitsData.pembagian_progres);
        let htmlTarget = "";
        let htmlBuktiNav = "";
        let htmlBuktiContent = "";
        const filePreviews = [];
        for (i; i <= value; i++) {
            filePreviews.push({
                input: `foto_${i}_1`,
                preview: `foto_${i}_preview_1`,
            });
            filePreviews.push({
                input: `foto_${i}_2`,
                preview: `foto_${i}_preview_2`,
            });
            filePreviews.push({
                input: `foto_${i}_3`,
                preview: `foto_${i}_preview_3`,
            });
            filePreviews.push({
                input: `video_${i}`,
                preview: `video_${i}_preview`,
            });
            htmlTarget += targetTemplate({ ke: i, data: exitsProgres[i - 1] });
            htmlBuktiNav += navTemplate({ ke: i, data: exitsProgres[i - 1] });
            htmlBuktiContent += buktiTemplate({
                ke: i,
                data: exitsProgres[i - 1],
            });
        }
        targetContainer.innerHTML = htmlTarget;
        buktiNavContainer.innerHTML = htmlBuktiNav;
        buktiContentContainer.innerHTML = htmlBuktiContent;

        filePreviews.forEach((data) => {
            const inputElement = document.getElementById(data.input);
            inputElement.onchange = (event) => {
                const [file] = inputElement.files;
                if (file)
                    document.getElementById(data.preview).src =
                        URL.createObjectURL(file);
            };
        });
    }

    disableFormRequired();
});

$("#mapLatLong").ready(() => {
    require([
        "esri/Map",
        "esri/views/MapView",
        "esri/Graphic",
        "esri/views/draw/Draw",
        "esri/geometry/geometryEngine",
        "esri/widgets/BasemapToggle",
        "esri/geometry/support/webMercatorUtils",
    ], function (
        Map,
        MapView,
        Graphic,
        Draw,
        geometryEngine,
        BasemapToggle,
        webMercatorUtils
    ) {
        const map = new Map({
            basemap: "osm",
        });

        const view = new MapView({
            container: "mapLatLong",
            map,
            center: [107.6191, -6.9175],
            zoom: 9,
        });

        const toggle = new BasemapToggle({
            view,
            nextBasemap: "hybrid",
        });

        view.ui.add(toggle, "top-right");

        const draw = new Draw({
            view: view,
        });

        let gambarManual;
        const drawMode = () => {
            gambarManual = document.createElement("div");
            gambarManual.className =
                "esri-widget esri-widget--button esri-interactive";
            gambarManual.title = "Draw polyline";
            gambarManual.innerHTML = '<span class="esri-icon-polyline"></span>';

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
                        "draw-complete",
                    ],
                    updateVertices
                );
            };
        };
        // drawMode();

        const geoJson = document.getElementById("geo_json");

        const updateVertices = (event) => {
            if (event.vertices.length > 1) {
                const result = createGraphic(event);
                if (result.selfIntersects) {
                    event.preventDefault();
                }
            }
        };

        const createGraphic = (event) => {
            const vertices = event.vertices;
            view.graphics.removeAll();

            const graphic = new Graphic({
                geometry: {
                    type: "polyline",
                    paths: vertices,
                    spatialReference: view.spatialReference,
                },
                symbol: {
                    type: "simple-line",
                    color: [255, 0, 0],
                    width: 4,
                    cap: "round",
                    join: "round",
                },
            });

            const { paths } = webMercatorUtils.webMercatorToGeographic(
                graphic.geometry
            );
            geoJson.value = JSON.stringify(paths);
            const intersectingSegment = getIntersectingSegment(
                graphic.geometry
            );

            if (intersectingSegment) {
                view.graphics.addMany([graphic, intersectingSegment]);
            } else {
                view.graphics.add(graphic);
            }

            return {
                selfIntersects: intersectingSegment,
            };
        };

        const isSelfIntersecting = (polyline) => {
            if (polyline.paths[0].length < 3) {
                return false;
            }
            const line = polyline.clone();

            const lastSegment = getLastSegment(polyline);
            line.removePoint(0, line.paths[0].length - 1);

            return geometryEngine.crosses(lastSegment, line);
        };

        const getIntersectingSegment = (polyline) => {
            if (isSelfIntersecting(polyline)) {
                return new Graphic({
                    geometry: getLastSegment(polyline),
                    symbol: {
                        type: "simple-line",
                        style: "short-dot",
                        width: 3.5,
                        color: "yellow",
                    },
                });
            }
            return null;
        };

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
                        [lastXYPoint.x, lastXYPoint.y],
                    ],
                ],
            };
        };

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
                    join: "round",
                },
            });

            view.graphics.removeAll();
            view.graphics.add(graphic);
            view.goTo(
                {
                    target: graphic.geometry,
                    tilt: 70,
                    zoom: 13,
                },
                {
                    duration: 1500,
                    easing: "in-out-expo",
                }
            );
        };

        const onChangeRuasJalan = async (event) => {
            try {
                view.ui.remove(gambarManual);
            } catch (e) {
                console.log(e);
            }
            let geo_id = event.target.value;
            if (geo_id != -1) {
                $("#nama_lokasi").hide();
                $("#nama_lokasi_value").prop("required", false);
            } else {
                drawMode();
                $("#nama_lokasi").show();
                $("#nama_lokasi_value").prop("required", true);
                if (exitsData !== null) {
                    if (exitsData?.geo_id != -1) {
                        geo_id = exitsData?.geo_id || -1;
                    } else {
                        const paths = JSON.parse(exitsData.geo_json);
                        addPolyLine(paths);
                        geoJson.value = JSON.stringify(paths);
                        $("#nama_lokasi_value").val(exitsData.nama_lokasi);
                    }
                } else {
                    $("#nama_lokasi").show();
                }
            }
            if (geo_id != -1) {
                const response = await fetch(`${url}/${geo_id}`);
                const ruasJalan = await response.json();
                console.log([ruasJalan.coordinates[0]]);
                addPolyLine([ruasJalan.coordinates]);
            }
        };

        const inputRuasJalan = document.getElementById("ruas_jalan");
        inputRuasJalan.onchange = (event) => onChangeRuasJalan(event);

        view.when(() => {
            if (exitsData !== null) {
                onChangeRuasJalan({
                    target: { value: exitsData?.geo_id || -1 },
                });
            } else {
                onChangeRuasJalan({ target: { value: -1 } });
            }
        });
    });
});


// var _validFileExtensions = [".shp", ".SHP", ".zip"];
// function Validate(oForm) {
//     var arrInputs = oForm.getElementsByTagName("input");
//     for (var i = 0; i < arrInputs.length; i++) {
//         var oInput = arrInputs[i];
//         if (oInput.type == "file") {
//             var sFileName = oInput.value;
//             if (sFileName.length > 0) {
//                 var blnValid = false;
//                 for (var j = 0; j < _validFileExtensions.length; j++) {
//                     var sCurExtension = _validFileExtensions[j];
//                     if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
//                         blnValid = true;
//                         break;
//                     }
//                 }

//                 if (!blnValid) {
//                     alert("Maaf, " + sFileName + " tidak valid, hanya diizinkan mengupload shapefile: " + _validFileExtensions.join(", "));
//                     return false;
//                 }
//             }
//         }
//     }

//     return true;
// }