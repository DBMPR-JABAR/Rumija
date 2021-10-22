<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Map Dashboard UPTD</title>
    <link rel="icon" href="{{ asset('assets/images/favicon/favicon.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://js.arcgis.com/4.19/esri/themes/light/main.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/filterMapsInternal.css') }}" />
    <style>
        html,
        body,
        #root,
        .esri-view,
        .map-view {
            padding: 0;
            margin: 0;
            height: 100%;
            width: 100%;
            z-index: -1;
        }

        #logo {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translate(-50%, 0);
            z-index: 99;
            background-color: rgba(0, 0, 0, 0.267);
            padding: 5px;
            border-radius: 5px;
            /* Black background with opacity */
        }

        .esri-attribution__sources,
        .esri-attribution__powered-by {
            display: none;
        }

        @media only screen and (max-width: 900px) {
            #logo {
                display: none;
            }
        }

    </style>
    <script src="https://kit.fontawesome.com/a5c9211044.js" crossorigin="anonymous"></script>
    <script defer="defer" src="{{ asset('assets/js/executive/mapdashboard.uptd.js') }}"></script>
</head>

<body>
    <div id="logo"><img width="200" class="img-fluid" src="{{ asset('assets/images/brand/text_putih.png') }}"
            alt="Logo DBMPR"></div>
    <div id="root"></div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
</script>
<script src="https://cdn.fluidplayer.com/v3/current/fluidplayer.min.js"></script>

</html>
