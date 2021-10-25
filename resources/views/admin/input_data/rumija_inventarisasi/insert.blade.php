@extends('admin.layout.index')

@section('title') Rumija @endsection
@section('head')
<link rel="stylesheet" href="https://js.arcgis.com/4.18/esri/themes/light/main.css">
{{-- <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer>
</script> --}}
@endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Rumija Inventarisasi</h4>
                <span>Rumija DBMPR Jabar</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('rumija.index') }}">Rumija</a>
                </li>
                <li class="breadcrumb-item"><a href="#">Tambah Inventaris</a> </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('page-body')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                @if ($action == 'store')
                <h5>Tambah Data Inventaris</h5>
                @else
                <h5>Perbaharui Data Inventaris</h5>
                @endif
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="pb-5 pl-5 pr-5 card-block">

                @if ($action == 'store')
                <form action="{{ route('rumija.inventarisasi.store') }}" method="post" enctype="multipart/form-data">
                    @else
                    <form action="{{ route('rumija.update', $inventaris->id) }}" method="post"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @endif
                        @csrf
                        
                        <div class=" form-group row">
                            <label class="col-md-2 col-form-label">Kategori</label>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-12">
                                        <select class="form-control searchableField" id="rumija_inventarisasi_kategori_id" name="rumija_inventarisasi_kategori_id"
                                            required>
                                            @foreach ($category as $data)
                                            <option value="{{ $data->id }}" id="{{ $data->id }}" @isset($inventaris)
                                                {{ $inventaris->rumija_inventarisasi_kategori_id == $data->id ? 'selected' : '' }} @endisset>
                                                {{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (Auth::user()->internalRole->uptd)
                            <input type="hidden" id="uptd" name="uptd_id" value="{{Auth::user()->internalRole->uptd}}">
                            @else
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Uptd</label>
                                <div class="col-md-10">
                                    <select class="form-control searchableField" id="uptd" name="uptd_id" onchange="ubahOption()" required>
                                        <option>Pilih UPTD</option>
                                        @foreach ($input_uptd_lists as $data)
                                        <option value="{{$data->id}}">{{$data->nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">SUP</label>
                                <div class="col-md-10">
                                    <select class="form-control searchableField" id="sup" name="sup_id" onchange="ubahOption1()" required >
                                        @if (Auth::user()->internalRole->uptd)
                                        @foreach ($sup as $data)
                                        <option value="{{$data->kd_sup}}" @if(Auth::user()->sup_id != null && Auth::user()->sup_id == $data->id) selected @endif>{{$data->name}}</option>
                                        @endforeach
                                        @else
                                        <option>-</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Ruas Jalan</label>
                                <div class="col-md-10">
                                    <select class="form-control searchableField" id="ruas_jalan" name="id_ruas_jalan" required>
                                        @if (Auth::user()->internalRole->uptd)
                                            @foreach ($input_ruas_jalan as $data)
                                                <option value="{{$data->id_ruas_jalan}}">{{$data->nama_ruas_jalan}}</option>
                                            @endforeach
                                        @else
                                            <option>-</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        <div class=" form-group row">
                            <label class="col-md-2 col-form-label">Lokasi</label>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input name="kode_lokasi" value="{{@$inventaris->lokasi}}" type="text" placeholder="KM.CN" class="form-control" required>
                                            </div>
                                            <div class="col-md-8">
                                                <input name="lokasi" value="{{@$inventaris->lokasi}}" type="text" placeholder="56+600 - 56+700" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        

                        <div class=" form-group row">
                            <label class="col-md-2 col-form-label">Koordinat X</label>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="lat" id="lat" type="text" class="form-control formatLatLong"
                                            required value="{{@$inventaris->lat}}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-md-2 col-form-label">Koordinat Y</label>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="lng" id="long" type="text" class="form-control formatLatLong"
                                            value="{{@$inventaris->lng}}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="mapLatLong" class="mb-2 full-map" style="height: 300px; width: 100%"></div>
                        <div class=" form-group row">
                            <label class="col-md-2 col-form-label">Jenis Pohon</label>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="name" value="{{@$inventaris->name}}" type="text" placeholder="" class="form-control">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row">
                            {{-- <label class="col-md-2 col-form-label">Luas (m<sup>2</sup>)</label> --}}
                            <label class="col-md-2 col-form-label">Jumlah</label>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input pattern="([0-9]+,{0,1}[0-9]*,{0,1})*[0-9]" name="jumlah"
                                            value="{{@$inventaris->jumlah}}" type="number" class="form-control" min="0"
                                            step="0.01">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row">
                            {{-- <label class="col-md-2 col-form-label">Luas (m<sup>2</sup>)</label> --}}
                            <label class="col-md-2 col-form-label">Panjang</label>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input pattern="([0-9]+,{0,1}[0-9]*,{0,1})*[0-9]" name="panjang"
                                            value="{{@$inventaris->panjang}}" type="number" class="form-control" min="0"
                                            step="0.01">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row">
                            {{-- <label class="col-md-2 col-form-label">Luas (m<sup>2</sup>)</label> --}}
                            <label class="col-md-2 col-form-label">Lebar</label>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input pattern="([0-9]+,{0,1}[0-9]*,{0,1})*[0-9]" name="lebar"
                                            value="{{@$inventaris->lebar}}" type="number" class="form-control" min="0"
                                            step="0.01">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row">
                            {{-- <label class="col-md-2 col-form-label">Luas (m<sup>2</sup>)</label> --}}
                            <label class="col-md-2 col-form-label">Tinggi</label>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input pattern="([0-9]+,{0,1}[0-9]*,{0,1})*[0-9]" name="tinggi"
                                            value="{{@$inventaris->tinggi}}" type="number" class="form-control" min="0"
                                            step="0.01">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row">
                            {{-- <label class="col-md-2 col-form-label">Luas (m<sup>2</sup>)</label> --}}
                            <label class="col-md-2 col-form-label">Diameter</label>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input pattern="([0-9]+,{0,1}[0-9]*,{0,1})*[0-9]" name="diameter"
                                            value="{{@$inventaris->diameter}}" type="number" class="form-control" min="0"
                                            step="0.01">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row">
                            <label class="col-md-2 col-form-label">Kontruksi</label>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="kontruksi" value="{{@$inventaris->kontruksi}}" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class=" form-group row">
                            <label class="col-md-2 col-form-label">Posisi</label>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-12">
                                        <select class="form-control searchableField" name="posisi" >
                                            <option value="">Pilih Posisi</option>
                                            <option value="Kanan" @if(Auth::user()->sup_id == 'Kanan') selected @endif>Kanan</option>
                                            <option value="Kiri" @if(Auth::user()->sup_id == 'Kiri') selected @endif>Kiri</option>
                                           
                                        </select>    
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="form-group row">
                            <label class="col-md-2 col-form-label">Foto 1</label>
                            <div class="col-md-5">
                                <img style="min-height: 40px" class="mx-auto rounded img-thumbnail d-block"
                                    id="foto_preview" src="{{ url('storage/' . @$inventaris->foto) }}" alt="">
                            </div>
                            <div class="col-md-5">
                                <input id="foto" name="foto" type="file" accept="image/*" class="form-control">
                            </div>
                        </div> --}}
                        <div class=" form-group row">
                            <label class="col-md-2 col-form-label">Desa</label>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="desa" value="{{@$inventaris->desa}}" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row">
                            <label class="col-md-2 col-form-label">Keterangan</label>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="keterangan" value="{{@$inventaris->keterangan}}" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row">
                            <a href="{{ route('rumija.index') }}"><button type="button"
                                    class="btn btn-default waves-effect">Batal</button></a>
                            <button type="submit" class="ml-2 btn btn-primary waves-effect waves-light">Simpan</button>
                        </div>
                    </form>

            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}">
</script>
<script src="{{ asset('assets/vendor/datatables.net/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net/js/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery/js/jquery.mask.js') }}"></script>
<script src="https://js.arcgis.com/4.18/"></script>

<script>
    $(document).ready(function() {
        

        const filePreviews = [
            {
                input:"foto",
                preview:"foto_preview"
            },{
                input:"foto_1",
                preview:"foto_preview_1"
            },{
                input:"foto_2",
                preview:"foto_preview_2"
            },{
                input:"video",
                preview:"video_preview"
            },
        ]
        // filePreviews.forEach(data=>{
        //     const inputElement = document.getElementById(data.input)
        //     inputElement.onchange = event => {
        //     const [file] = inputElement.files
        //     if(file) document.getElementById(data.preview).src = URL.createObjectURL(file)
        // }
        // })
            // Format mata uang.
            $('.formatRibuan').mask('000.000.000.000.000', {
                reverse: true
            });

            // Format untuk lat long.
            $('.formatLatLong').keypress(function(evt) {
                return (/^\-?[0-9]*\.?[0-9]*$/).test($(this).val() + evt.key);
            });

            $('#mapLatLong').ready(() => {
                require([
                    "esri/Map",
                    "esri/views/MapView",
                    "esri/Graphic"
                ], function(Map, MapView, Graphic) {

                    const map = new Map({
                        basemap: "hybrid"
                    });

                    const view = new MapView({
                        container: "mapLatLong",
                        map: map,
                        center: [107.6191, -6.9175],
                        zoom: 8,
                    });
                    let tempGraphic;
                    if ($("#lat").val() != '' && $("#long").val() != '') {
                        var graphic = new Graphic({
                            geometry:{
                                type: "point",
                                longitude: $("#long").val(),
                                latitude: $("#lat").val()
                            },
                            symbol: {
                                type: "picture-marker", // autocasts as new SimpleMarkerSymbol()
                                url: "http://esri.github.io/quickstart-map-js/images/blue-pin.png",
                                width: "14px",
                                height: "24px"
                            }
                        });
                        tempGraphic = graphic;

                        view.graphics.add(graphic);
                        }
                    view.on("click", function(event) {
                        if ($("#lat").val() != '' && $("#long").val() != '') {
                            view.graphics.remove(tempGraphic);
                        }
                        var graphic = new Graphic({
                            geometry: event.mapPoint,
                            symbol: {
                                type: "picture-marker", // autocasts as new SimpleMarkerSymbol()
                                url: "http://esri.github.io/quickstart-map-js/images/blue-pin.png",
                                width: "14px",
                                height: "24px"
                            }
                        });
                        tempGraphic = graphic;
                        $("#lat").val(event.mapPoint.latitude);
                        $("#long").val(event.mapPoint.longitude);

                        view.graphics.add(graphic);
                    });
                    $("#lat, #long").keyup(function() {
                        if ($("#lat").val() != '' && $("#long").val() != '') {
                            view.graphics.remove(tempGraphic);
                        }
                        var graphic = new Graphic({
                            geometry: {
                                type: "point",
                                longitude: $("#long").val(),
                                latitude: $("#lat").val()
                            },
                            symbol: {
                                type: "picture-marker", // autocasts as new SimpleMarkerSymbol()
                                url: "http://esri.github.io/quickstart-map-js/images/blue-pin.png",
                                width: "14px",
                                height: "24px"
                            }
                        });
                        tempGraphic = graphic;

                        view.graphics.add(graphic);
                    });
                });
            });
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
