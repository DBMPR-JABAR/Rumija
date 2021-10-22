<html>

    <head>
        <title>
            @section('title')
                ~ Teman Jabar
            @show
        </title>
        <link rel="stylesheet" type="text/css"
            href="{{ asset('assets/files/bower_components/bootstrap/css/bootstrap.min.css') }}">
        <script type="text/javascript" src="{{ asset('assets/files/bower_components/jquery/js/jquery.min.js') }}">
        </script>
        <script type="text/javascript" src="{{ asset('assets/files/bower_components/jquery-ui/js/jquery-ui.min.js') }}">
        </script>
        <script type="text/javascript" src="{{ asset('assets/files/bower_components/popper.js/js/popper.min.js') }}">
        </script>
        <script type="text/javascript" src="{{ asset('assets/files/bower_components/bootstrap/js/bootstrap.min.js') }}">
        </script>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
        
    </head>

<body>
    
    @yield('content')
    
    <style>
        .no_border {
            border: none !important
        }

        #table_judul {
            border: none;
            font-size: 12px
        }

        table {
            font-size: 12px
        }

        @page {
            size: 21cm 29.7cm;
            margin: 0;
            padding: 0
        }

        .form-group .row {
            margin-bottom: 0;
            padding-bottom: 0
        }

        .no_right_pad {
            padding-right: 0;
            padding-left: 0
        }

        .hal_ {
            margin: 0;
            padding: 3px 0 5px 0
        }

        .form-group {
            margin-top: 0
        }

        .colon {
            text-align: right;
            margin: 0;
            padding: 0
        }

        label {
            height: 0;
        }

        input {
            height: 12px;
            width: 100%
        }

        .dotted {
            border: 0;
            border-bottom: 1px dotted;
        }

        body {
            background: rgb(204, 204, 204);
            margin: 0;
            padding: 0;
            font-size: 12px !important
        }

        html {
            margin: 0;
            padding: 0;
            min-width: 21cm;
            min-height: 29.7cm;
            font-size: 8px;
        }

        page {
            background: white;
            display: block;
            margin: 0 auto;
            margin-bottom: 0.5cm;
            box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
        }

        page[size="A4"] {
            width: 21cm;
            height: 29.7cm;
            padding: 1cm 1.5cm

        }
        page[size="struck"] {
            width: 57mm;
            padding: 0.5cm 0.5cm

        }

        @media print {

            #cetak {
                display: none;
                z-index:1;
            }

            body,
            page {
                margin: 0;
                box-shadow: 0;
            }


            .no_border {
                border: none !important
            }

            .ttd {
                border: solid;
                white !important;
                border-width: 0 !important;
                border-bottom-style: none;
            }

            .ttd th,
            .ttd td {
                border: solid;
                white !important;
                border-width: 0 !important;
                border-bottom-style: none;
            }
        }

       

        .judul {
           
        }

        .judul p {
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 1.5px
        }

    </style>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('cetak').addEventListener('click', () => {
                window.print();
            })
        })

    </script>
    @stack('scripts')

</body>

</html>
