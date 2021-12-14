<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <link rel="shortcut icon" href="images/logo/jabar.png" />
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui" />
    <style>
        html,
        body,
        main,
        #root {
            padding: 0;
            margin: 0;
            height: 100%;
            width: 100%;
        }

        #maps_container {
            padding: 0;
            margin: 0;
            height: 100%;
            width: 100%;
            background-color: rgba(146, 146, 146, 0.54);
        }

        #inner_text_map_container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, 0);
        }

        #logo {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translate(-50%, 0);
            z-index: 999;
            background-color: rgba(0, 0, 0, 0.267);
            padding: 5px;
            border-radius: 5px;
            /* Black background with opacity */
        }

        @media only screen and (max-width: 900px) {
            #logo {
                display: none;
            }
        }

        #basemaps-wrapper {
            position: absolute;
            bottom: 10px;
            left: 10px;
            z-index: 400;
            background: rgba(255, 255, 255, 0.8);
            padding: 5px;
        }

        .offcanvas-bottom {
            height: 50 vh !important;
            max-height: 100%;
        }
    </style>
    <title>SYSTARUMIJA - MONITORING</title>
    <script defer="defer" src="{{asset('admin/monitoring/main.bundle.js')}}"></script>
</head>

<body>
    <main id="container">
        <div id="maps_container"><b id="inner_text_map_container">Memuat data...</b></div>
        <div id="logo"><img width="200" class="img-fluid" src="images/logo/text_putih.png" alt="Logo DBMPR" /></div>
        <div id="basemaps-wrapper" class="leaflet-bar"><select id="basemaps">
                <option value="Streets">Streets</option>
                <option value="Topographic">Topographic</option>
                <option value="NationalGeographic">National Geographic</option>
                <option value="Oceans">Oceans</option>
                <option value="Gray">Gray</option>
                <option value="DarkGray">Dark Gray</option>
                <option value="Imagery">Imagery</option>
                <option value="ImageryClarity">Imagery (Clarity)</option>
                <option value="ImageryFirefly">Imagery (Firefly)</option>
                <option value="ShadedRelief">Shaded Relief</option>
            </select></div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="filterPanel" aria-labelledby="filterPanelLabel"
            data-bs-backdrop="false">
            <div class="offcanvas-header mb-0 pb-0">
                <h5 id="filterPanelLabel" class="mb-0 pb-0"></h5><button type="button" class="btn-close text-reset"
                    data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body mt-0 pt-0" id="filterPanelBody"></div>
        </div>
        <div class="offcanvas offcanvas-bottom h-50" tabindex="-1" id="offcanvasBottom"
            aria-labelledby="offcanvasBottomLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasBottomLabel"></h5><button type="button"
                    class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div id="offcanvasBottomBody" class="offcanvas-body"></div>
        </div>
    </main>
    <script>
        function onClickInventarisRumija() {
      const labelEl = document.getElementById("offcanvasBottomLabel")
      labelEl.innerText =
        "Inventaris Rumija";
      const target = document.getElementById("inventarisRumijaButton");
      const idRuadJalan = target.getAttribute("data-properties");
      const body = document.getElementById("offcanvasBottomBody");
      body.innerHTML = "Memuat data inventaris RUMIJA..";
      fetch(
        `https://124.81.122.131/rumija/public/index.php/api/rumija/report/inventaris/${idRuadJalan}`
      )
        .then((response) => response.json())
        .then((response) => {
          body.innerHTML = "Memproses data..";
          if (response.status) {
            labelEl.innerHTML = `Inventaris RUMIJA - ${response.properties.nama_ruas_jalan}`
            let html = "";
            const keys = Object.keys(response.data);
            console.log(keys);
            keys.map((key) => {
              if (response.data[key].length > 0) {
                html += `<h5>${key.replace("_", " ").toUpperCase()}</h5>`;
                html += '<table style="width: 100%"" class="table table-sm table-bordered">';
                const column = Object.keys(response.data[key][0]);
                let colHTML = "<tr>";
                column.forEach((col) => {
                  colHTML += `<th>${col
                    .substring(2, col.length)
                    .replace("dt", "TINGGI")
                    .replace("dl", "LEBAR")
                    .replace("dp", "PANJANG")
                    .replace("ka_ki", "POSISI")
                    .replace("_", " ")
                    .toUpperCase()}</th>`;
                });
                colHTML += "</tr>";
                html += colHTML;
                response.data[key].forEach((data) => {
                  html += `<tr>`;
                  column.forEach((col) => {
                    html += `<td>${data[col] || "-"}</td>`;
                  });
                  html += `</tr>`;
                });
                html += "</table>";
              }
            });
            html += "</table>";
            body.innerHTML = html;
          } else body.innerHTML = "Tidak terdapat data inventaris RUMIJA";
        })
        .catch((error) => {
          body.innerHTML =
            "Terjadi kesalahan saat memuat data inventaris RUMIJA..";
          console.log(error);
        });
    }
    </script>
</body>

</html>
