@if (count($surveiKondisiJalan) > 0)
    <figure class="highcharts-figure">
        <div id="containerEiri"></div>
        <p class="highcharts-description">

        </p>
    </figure>
    <figure class="highcharts-figure">
        <div id="containerSpeedAltitude"></div>
        <p class="highcharts-description">

        </p>
    </figure>
    @else
        <div><h2 id="noData">Belum Ada Data</h2></div>
@endif
<style>
    #noData {
        text-align: center
    }
    .highcharts-figure,
    .highcharts-data-table table {
        min-width: 360px;
        max-width: 800px;
        margin: 1em auto;
    }

    .highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #EBEBEB;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }

    .highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }

    .highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }

    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
        padding: 0.5em;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }

    .highcharts-data-table tr:hover {
        background: #f1f7ff;
    }

</style>

<!-- Resources -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/boost.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<!-- Chart code -->
<script>
    const surveiKondisiJalan = @json($surveiKondisiJalan);
    //console.log(surveiKondisiJalan)

    const surveiKondisiJalanOrder = surveiKondisiJalan.sort((a, b) => {
        return Number(a.distance) > Number(b.distance) ? 1 : -1;
    })

    //console.log(surveiKondisiJalanOrder)

    const eiri = [],
        ciri = [],
        speed = [],
        altitude = [];

    surveiKondisiJalanOrder.forEach(items => {
        eiri.push([
            Number(items.distance),
            Number(items.e_iri)
        ])
        ciri.push([
            Number(items.distance),
            Number(items.c_iri)
        ])
        speed.push([
            Number(items.distance),
            Number(items.speed)
        ])
        altitude.push([
            Number(items.distance),
            Number(items.altitude)
        ])
    });

    //console.log(eiri, ciri, altitude, speed)

    Highcharts.chart('containerEiri', {

        chart: {
            zoomType: 'x'
        },

        colors: ['red', 'green', ],

        title: {
            text: 'eIRI & cIRI'
        },

        // subtitle: {
        //     text: 'Using the Boost module'
        // },

        tooltip: {
            valueDecimals: 2
        },

        xAxis: {
            type: 'linear'
        },

        series: [{
                data: eiri,
                lineWidth: 0.5,
                name: 'eIRI'
            },
            {
                data: ciri,
                lineWidth: 0.5,
                name: 'cIRI'
            }
        ]

    });

    Highcharts.chart('containerSpeedAltitude', {

        chart: {
            zoomType: 'x'
        },
        colors: ['red', 'green', ],
        title: {
            text: 'Speed & Altitude'
        },

        // subtitle: {
        //     text: 'Using the Boost module'
        // },

        tooltip: {
            valueDecimals: 2
        },

        xAxis: {
            type: 'linear'
        },

        series: [{
                data: speed,
                lineWidth: 0.5,
                name: 'Speed'
            },
            {
                data: altitude,
                lineWidth: 0.5,
                name: 'Altitude'
            }
        ]
    });

</script>
