<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <title>Temanjabar &middot; @yield('title')</title>

    <!--[if lt IE 10]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />


    <link rel="icon" href="{{ asset('assets/images/favicon/favicon.ico') }}" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:500,700" rel="stylesheet">

    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/files/bower_components/bootstrap/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/files/assets/pages/waves/css/waves.min.css') }}" type="text/css"
        media="all">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/files/assets/icon/feather/css/feather.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/files/assets/css/font-awesome-n.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/themify-icons/themify-icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/icofont/css/icofont.css') }}">

    {{-- <link rel="stylesheet" href="{{ asset('assets/files/bower_components/chartist/css/chartist.css') }}"
        type="text/css" media="all"> --}}

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/files/assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/files/assets/css/widget.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/second_style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }} ">

    <!-- Tombol get lokasi diatas maps -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/map_geolocation_btn.css') }} ">

    <!-- searchable field, kalau kau pake tinggal tambahin class searchableField
        !!jika ada dalam modal, modalnya kasih class searchableModalContainer fieldnya kasih class searchableModalField -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css"
        integrity="sha512-kq3FES+RuuGoBW3a9R2ELYKRywUEQv0wvPTItv3DSGqjpbNtGWVdvT8qwdKkqvPzT93jp8tSF4+oN4IeTEIlQA=="
        crossorigin="anonymous" />
    <style>
        #scrollable {
            max-height: 80vh;
            overflow: scroll;
        }
        .read_notif::after {
        content: "♥";
        color: blue;
        }
        
    </style>
    @yield('head')
</head>

<body>

    <div class="loader-bg">
        <div class="loader-bar"></div>
    </div>

    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            @if(Auth::user())
            @include('admin.layout.navbar')
            @endif
            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    @if(Auth::user())

                    @include('admin.layout.sidebar')
                    
                    <div class="pcoded-content">
                        @endif

                        {{-- <div class="row align-items-end">
                                <div class="col-lg-8">
                                    <div class="page-header-title">
                                        <i class="feather icon-home bg-c-blue"></i>
                                        <div class="d-inline">
                                            <h5>Dashboard</h5>
                                            <span>lorem ipsum dolor sit amet, consectetur adipisicing elit</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="page-header-breadcrumb">
                                        <ul class=" breadcrumb breadcrumb-title">
                                            <li class="breadcrumb-item">
                                                <a href="index.html"><i class="feather icon-home"></i></a>
                                            </li>
                                            <li class="breadcrumb-item"><a href="#!">Dashboard</a> </li>
                                        </ul>
                                    </div>
                                </div>
                            </div> --}}

                        <div class="pcoded-inner-content">
                            <div class="main-body">
                                <div class="page-wrapper">
                                    @if (Session::has('msg'))
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-{{ Session::get('color') }} alert-dismissible fade show"
                                                    role="alert">
                                                    {{ Session::get('msg') }}
                                                    <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="page-header card">
                                        @yield('page-header')
                                    </div>
                                    <div class="page-body">
                                        @yield('page-body')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="styleSelector">
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!--[if lt IE 10]>
    <div class="ie-warning">
        <h1>Warning!!</h1>
        <p>You are using an outdated version of Internet Explorer, please upgrade
            <br/>to any of the following web browsers to access this website.
        </p>
        <div class="iew-container">
            <ul class="iew-download">
                <li>
                    <a href="http://www.google.com/chrome/">
                        <img src="{{ asset('assets/files/assets/images/browser/chrome.png') }}" alt="Chrome">
                        <div>Chrome</div>
                    </a>
                </li>
                <li>
                    <a href="https://www.mozilla.org/en-US/firefox/new/">
                        <img src="{{ asset('assets/files/assets/images/browser/firefox.png') }}" alt="Firefox">
                        <div>Firefox</div>
                    </a>
                </li>
                <li>
                    <a href="http://www.opera.com">
                        <img src="{{ asset('assets/files/assets/images/browser/opera.png') }}" alt="Opera">
                        <div>Opera</div>
                    </a>
                </li>
                <li>
                    <a href="https://www.apple.com/safari/">
                        <img src="{{ asset('assets/files/assets/images/browser/safari.png') }}" alt="Safari">
                        <div>Safari</div>
                    </a>
                </li>
                <li>
                    <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                        <img src="{{ asset('assets/files/assets/images/browser/ie.png') }}" alt="">
                        <div>IE (9 & above)</div>
                    </a>
                </li>
            </ul>
        </div>
        <p>Sorry for the inconvenience!</p>
    </div>
<![endif]-->

    <script type="text/javascript" src="{{ asset('assets/files/bower_components/jquery/js/jquery.min.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('assets/files/bower_components/jquery-ui/js/jquery-ui.min.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('assets/files/bower_components/popper.js/js/popper.min.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('assets/files/bower_components/bootstrap/js/bootstrap.min.js') }}">
    </script>

    <script src="{{ asset('assets/files/assets/pages/waves/js/waves.min.js') }}"></script>

    <script type="text/javascript"
        src="{{ asset('assets/files/bower_components/jquery-slimscroll/js/jquery.slimscroll.js') }}"></script>

    {{-- <script src="{{ asset('assets/files/assets/pages/chart/float/jquery.flot.js') }}"></script>
    <script src="{{ asset('assets/files/assets/pages/chart/float/jquery.flot.categories.js') }}"></script>
    <script src="{{ asset('assets/files/assets/pages/chart/float/curvedLines.js') }}"></script>
    <script src="{{ asset('assets/files/assets/pages/chart/float/jquery.flot.tooltip.min.js') }}"></script>

    <script src="{{ asset('assets/files/bower_components/chartist/js/chartist.js') }}"></script>

    <script src="{{ asset('assets/files/assets/pages/widget/amchart/amcharts.js') }}"></script>
    <script src="{{ asset('assets/files/assets/pages/widget/amchart/serial.js') }}"></script>
    <script src="{{ asset('assets/files/assets/pages/widget/amchart/light.js') }}"></script> --}}

    <script src="{{ asset('assets/files/assets/js/pcoded.min.js') }}"></script>
    <script src="{{ asset('assets/files/assets/js/vertical/vertical-layout.min.js') }}"></script>
    {{-- <script type="text/javascript" src="{{ asset('assets/files/assets/pages/dashboard/custom-dashboard.min.js') }}">
    </script> --}}
    <script type="text/javascript" src="{{ asset('assets/files/assets/js/script.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/custom.js') }}"></script>

    <!-- Tombol get lokasi diatas maps
    Maps dan button di bungkus div dengan class "mapsWithGetLocationButton",
    Buttonnya = <button id="btn_geoLocation" onclick="getLocation({idLat:'??', idLong:'??'})" type="button"
                                    class="btn bg-white text-secondary locationButton"><i class="ti-location-pin"></i></button>
    -->
    <script type="text/javascript" src="{{ asset('assets/js/map_geolocation_btn.js') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-23581568-13');

    </script>
    <!-- searchable field -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(() => {
            $(".searchableModalField").select2({
                dropdownParent: $('.searchableModalContainer'),
                theme: "bootstrap"
            })
            $(".searchableField").select2({
                theme: "bootstrap"
            })
            $(".select2-selection").css("border-radius", 0);
        })

    </script>
    @yield('script')
</body>

</html>
