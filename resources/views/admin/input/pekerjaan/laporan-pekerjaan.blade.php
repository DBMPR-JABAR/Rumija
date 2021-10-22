@extends('admin.layout.index')

@section('title')Permission @endsection
@section('head')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/vendor/datatables.net/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/vendor/datatables.net/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/vendor/data-table/extensions/responsive/css/responsive.dataTables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/chosen_v1.8.7/chosen.css') }}">
    <link rel="stylesheet" href="https://js.arcgis.com/4.17/esri/themes/light/main.css">

    <style>
        .chosen-container.chosen-container-single {
            width: 300px !important;
            /* or any value that fits your needs */
        }

        table.table-bordered tbody td {
            word-break: break-word;
            vertical-align: top;
        }

    </style>
    <style>
        .switch {
          position: relative;
          display: inline-block;
          width: 60px;
          height: 34px;
        }
        
        .switch input { 
          opacity: 0;
          width: 0;
          height: 0;
        }
        
        .slider {
          position: absolute;
          cursor: pointer;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background-color: #ccc;
          -webkit-transition: .4s;
          transition: .4s;
        }
        
        .slider:before {
          position: absolute;
          content: "";
          height: 26px;
          width: 26px;
          left: 4px;
          bottom: 4px;
          background-color: white;
          -webkit-transition: .4s;
          transition: .4s;
        }
        
        input:checked + .slider {
          background-color: #2196F3;
        }
        
        input:focus + .slider {
          box-shadow: 0 0 1px #2196F3;
        }
        
        input:checked + .slider:before {
          -webkit-transform: translateX(26px);
          -ms-transform: translateX(26px);
          transform: translateX(26px);
        }
        
        /* Rounded sliders */
        .slider.round {
          border-radius: 34px;
        }
        
        .slider.round:before {
          border-radius: 50%;
        }
    </style>
@endsection

@section('page-header')
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Laporan </h4>
                    <span>Laporan Pekerjaan</span>

                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ url('admin/input-data/pekerjaan') }}">Pekerjaan</a> </li>
                    <li class="breadcrumb-item"><a href="#!">Laporan</a> </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('page-body')

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Laporan</h4>
                    {{-- <span>lorem ipsum dolor sit amet, consectetur adipisicing elit</span> --}}
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            {{-- {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                            <li><i class="feather icon-minus minimize-card"></i></li> --}}
                        </ul>
                    </div>
                </div>
                <div class="card-block">
                    <form action="{{route('generateLapPekerjaan')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @if (Auth::user()->internalRole->uptd == null)
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">UPTD</label>
                            <div class="col-md-10">
                                <select class="form-control" id="uptd" name="uptd_id" onchange="ubahOption()" required>
                                    <option value="">Pilih UPTD</option>
                                    @foreach ($input_uptd_lists as $data)
                                    <option value="{{$data->id}}">{{$data->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        @if (Auth::user()->sup_id == null)
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">SUP</label>
                            <div class="col-md-10">
                                {{-- <select class=" searchableField" id="sup" name="sup" required > --}}
                                <select class="form-control searchableField"  id="sup" name="sup" onchange="ubahOption1()" required>
                                    @if (Auth::user()->internalRole->uptd != null)
                                        @foreach ($input_sup as $data)
                                        <option value="{{$data->kd_sup}}">{{$data->name}}</option>
                                        @endforeach
                                    @else
                                        <option value="">-</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Ruas Jalan</label>
                            <div class="col-md-10">
                                <select class="form-control searchableField" id="ruas_jalan" name="ruas_jalan" required>
                                    @if (Auth::user()->sup_id != null)
                                        @foreach ($input_ruas_jalan as $data)
                                        <option value="{{$data->id_ruas_jalan}}">{{$data->nama_ruas_jalan}}</option>
                                        @endforeach
                                    @else
                                        <option value="">-</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Tanggal Pelaksanaan</label>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="start_date" type="date" id="start" class="form-control" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    s/d
                                    <div class="col-md">
                                        <input name="end_date" type="date" id="end" class="form-control" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary waves-effect waves-light ">Simpan</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
    
@endsection
@section('script')
    <script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables.net/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables.net/js/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendor/chosen_v1.8.7/chosen.jquery.js') }}"
        type="text/javascript"></script>

    <script>
        $(document).ready(function() {
            
            var start = document.getElementById('start');
            var end = document.getElementById('end');

            start.addEventListener('change', function() {
                if (start.value)
                    end.min = start.value;
            }, false);
            end.addEventLiseter('change', function() {
                if (end.value)
                    start.max = end.value;
            }, false);
        });

        
        function ubahOption() {

            //untuk select SUP
            id = document.getElementById("uptd").value
            url = "{{ url('admin/master-data/ruas-jalan/getSUP') }}"
            id_select = '#sup'
            text = 'Pilih SUP'
            option = 'name'
            id_supp = 'kd_sup'

            setDataSelect(id, url, id_select, text, id_supp, option)

            //untuk select Ruas
            url = "{{ url('admin/input-data/kondisi-jalan/getRuasJalan') }}"
            id_select = '#ruas_jalan'
            text = 'Pilih Ruas Jalan'
            option = 'nama_ruas_jalan'
            id_ruass = 'id_ruas_jalan'

            setDataSelect(id, url, id_select, text, id_ruass, option)
        }
        function ubahOption1() {

        //untuk select SUP
        id = document.getElementById("sup").value
       
        //untuk select Ruas
        url = "{{ url('admin/input-data/kondisi-jalan/getRuasJalanBySup') }}"
        id_select = '#ruas_jalan'
        text = 'Pilih Ruas Jalan'
        option = 'nama_ruas_jalan'
        id_ruass = 'id_ruas_jalan'

        setDataSelect(id, url, id_select, text, id_ruass, option)
        }
    </script>
@endsection
