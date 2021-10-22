@extends('admin.layout.index')

@section('title') Admin Teman Jabar
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('assets/css/progress_buble.css') }}" />
    <link rel="stylesheet" href="https://js.arcgis.com/4.18/esri/themes/light/main.css" />
@endsection

@section('page-header')
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Pengujian Bahan LABKON</h4>
                    <span>Input Data Pengujian Bahan LABKON </span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ url('admin/pengujian_bahan/dashboard') }}">Pengujian Bahan
                            LABKON</a> </li>
                    <li class="breadcrumb-item"><a href="#">Tambah</a> </li>
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
                    <h5>Pendaftaran Pengujian Bahan LABKON</h5>
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                            <li><i class="feather icon-minus minimize-card"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="card-block pl-5 pr-5 pb-5">
                    <ul class="progress-indicator mb-3">
                        <li class="completed">
                            <span class="bubble"></span> Pendaftaran
                        </li>
                        <li class="active">
                            <span class="bubble"></span> Bahan Uji
                        </li>
                        <li>
                            <span class="bubble"></span> Pengkajian Permintaan Pengujian
                        </li>
                        <li>
                            <span class="bubble"></span> Pengujian
                        </li>
                        <li>
                            <span class="bubble"></span> Selesai
                        </li>
                    </ul>

                    <form action="javascript:void(0)" method="post">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">No Permohonan Sementara</label>
                            <div class="col-md-9">
                                <input name="no_permohonan" type="text" class="form-control" required
                                    value="{{ $datas->no_permohonan }}" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Uptd</label>
                            <div class="col-md-9">
                                <select style="width: 100%" class=" searchableField" id="uptd" name="uptd">
                                    <option>Pilih UPTD</option>
                                    @foreach ($input_uptd_lists as $data)
                                        <option value="{{ $data->id }}">{{ $data->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="check_box_bahan_uji"></div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Jumlah Bahan Uji</label>
                            <div class="col-md-9">
                                <input name="jumlah_bahan_uji" type="number" class="form-control" required
                                    placeholder="Masukan Jumlah Bahan Uji Sampel">
                                <small id="jumlah_bahan_uji_help" class="form-text text-muted">*Jika bahan tidak sesuai
                                    dengan aturan tidak akan diproses</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Waktu Pengambilan Sampel</label>
                            <div class="col-md-9">
                                <input name="waktu_pengambilan_sampel" type="date" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Lokasi Pengambilan Sampel</label>
                            <div class="col-md-9">
                                <input name="lokasi_pengambilan_sampel" type="text" class="form-control" required
                                    placeholder="Masukan Lokasi Pengambilan Sampel">
                            </div>
                        </div>
                        <div class="form-group row"><label class="col-md-3 col-form-label">Metode Pengujian</label>
                            <div class="col-md-9"><select class="searchableField form-control" style="width: 100%"
                                    id="metode_pengujian" name="metode_pengujian"></select></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Latitude</label>
                            <div class="col-md-9 my-auto">
                                <input id="lat" name="lat" type="text" class="form-control formatLatLong"
                                    placeholder="-6.84221" required />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Longitude</label>
                            <div class="col-md-9 my-auto">
                                <input id="long" name="long" type="text" class="form-control formatLatLong"
                                    placeholder="107.324" required />
                            </div>
                        </div>
                        <div class="row mapsWithGetLocationButton">
                            <div id="mapLatLong" class="full-map mb-2" style="height: 300px; width: 100%"></div>
                            <button id="btn_geoLocation" onclick="getLocation({idLat:'lat', idLong:'long'})" type="button"
                                class="btn bg-white text-secondary locationButton">
                                <i class="ti-location-pin"></i>
                            </button>
                        </div>
                        <div class=" form-group row">
                            <a href="{{ route('listPengujianLabKon') }}"><button type="button"
                                    class="btn btn-default waves-effect">Batal</button></a>
                            <button id="simpan" type="submit"
                                class="btn btn-primary waves-effect waves-light ml-2">Simpan</button>
                            <a href="{{ route('cetakPermohonanPengujianLabkon', $datas->no_permohonan) }}"
                                target="_blank"><button id="print" type="button"
                                    class="btn btn-secondary waves-effect waves-light ml-2 ">Cetak Surat
                                    Permohonan</button></a>
                            <button id="upload_ttd" type="button" class="btn btn-primary waves-effect waves-light ml-2"
                                data-toggle="modal" data-target="#upload_ttd_modal">Upload Surat Permohonan Tanda Tangan
                                Basah</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade searchableModalContainer" id="upload_ttd_modal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Upload Permohonan Tanda Tangan Basah
                    </h5>
                </div>
                <div class="modal-body">
                    {{ csrf_field() }}

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">File Permohonan</label>
                        <div class="col-md-9">
                            <input type="file" name="file_permohonan" class="form-control " required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Upload
                    </button>
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
    <script src="{{ asset('assets/vendor/jquery/js/jquery.mask.js') }}"></script>
    <script src="https://js.arcgis.com/4.18/"></script>
    <script>
        const bahanUji = [{
            menu: 'Aspal',
            checksBox: [{
                name: 'Penetrasi pada 25 derajat Celcius, 100g, 5 detik',
                id: 'A1'
            }, {
                name: 'Berat Jenis',
                id: 'A2'
            }, {
                name: 'Daktilitas pada 25 derajat Celcius, 5cm/menit',
                id: 'A3'
            }, {
                name: 'Titik Lembek',
                id: 'A4'
            }, {
                name: 'Titik Nyala',
                id: 'A5'
            }, {
                name: 'Viskositas',
                id: 'A6'
            }, {
                name: 'Kehilangan Berat (TFOT)',
                id: 'A7'
            }, {
                name: 'Penetrasi setelah TFOT',
                id: 'A8'
            }, {
                name: 'Daktilitas setelah TFOT',
                id: 'A9'
            }, {
                name: 'Kadar Parafin',
                id: 'A10'
            }]
        }, {
            menu: 'Agregat',
            checksBox: [{
                name: 'Analisa Saringan / Gradasi',
                id: 'B1'
            }, {
                name: 'Berat Jenis Agregat Kasar',
                id: 'B2'
            }, {
                name: 'Berat Jenis Agregat Halus',
                id: 'B3'
            }, {
                name: 'Berat Isi',
                id: 'B4'
            }, {
                name: 'Keausan/Abration (LA Test)',
                id: 'B5'
            }, {
                name: 'Bentuk (Kepipihan,Kelonjongan)',
                id: 'B6'
            }, {
                name: 'Penyerapan',
                id: 'B7'
            }, {
                name: 'Kelekatan agregat terhadap aspal',
                id: 'B8'
            }, {
                name: 'Sand equivalent agregat halus',
                id: 'B9'
            }]
        }, {
            menu: 'Campuran Aspal',
            checksBox: [{
                name: 'Marshall Test',
                id: 'C1'
            }, {
                name: 'Core Drill',
                id: 'C2'
            }, {
                name: 'Density',
                id: 'C3'
            }, {
                name: 'Kadar Aspal/Ekstraksi',
                id: 'C4'
            }, {
                name: 'Gradasi Campuran',
                id: 'C5'
            }, {
                name: 'Benkelmen Beam/BB',
                id: 'C6'
            }]
        }, {
            menu: 'Tanah',
            checksBox: [{
                name: 'Sondir',
                id: 'D1'
            }, {
                name: 'Hand Boring',
                id: 'D2'
            }, {
                name: 'Dyanamic Core Penetrometer/DCP',
                id: 'D3'
            }, {
                name: 'CBR Lab',
                id: 'D4'
            }, {
                name: 'Sand Cone',
                id: 'D5'
            }, {
                name: 'Direct Shear',
                id: 'D6'
            }, {
                name: 'Atterberg Limit (Plastic Limit, Liquid Limit)',
                id: 'D7'
            }, {
                name: 'Berat Isi',
                id: 'D8'
            }, {
                name: 'Pemadatan',
                id: 'D9'
            }]
        }, {
            menu: 'Beton',
            checksBox: [{
                name: 'Slump Test',
                id: 'E1'
            }, {
                name: 'Kuat Tekan',
                id: 'E2'
            }, {
                name: 'Kuat Lentur',
                id: 'E3'
            }]
        }]

        let htmlCheckBoxBahanUji = ""
        bahanUji.forEach(bahan => {
            htmlCheckBoxBahanUji +=
                `<div class="form-group row">
                                                                                                                                                                                                                <h4 class="col-md-3 col-form-label">${bahan.menu}</h4>
                                                                                                                                                                                                                <div class="col-md-9 border-checkbox-section row">`
            bahan.checksBox.forEach(checkBox => {
                htmlCheckBoxBahanUji +=
                    `<div class="col-md-6 border-checkbox-group border-checkbox-group-primary">
                                                                                                                                                                                                                    <input class="border-checkbox" type="checkbox" name="${checkBox.id}" id="${checkBox.id}">
                                                                                                                                                                                                                    <label class="border-checkbox-label" for="${checkBox.id}">${checkBox.name}</label>
                                                                                                                                                                                                                    </div>`
            })
            htmlCheckBoxBahanUji +=
                `</div>
                                                                                                                                                                                                            </div>`
        })

        const metodePengujian = [{
            name: 'Pilih Metode Pengujian',
            value: 0
        }, {
            name: 'Metode Pengujian 1',
            value: 1
        }, {
            name: 'Metode Pengujian 2',
            value: 2
        }, {
            name: 'Metode Pengujian 3',
            value: 3
        }, {
            name: 'Metode Pengujian 4',
            value: 4
        }]
        let htmlDropdownMetodePengujian = '';
        metodePengujian.forEach(metode => {
            htmlDropdownMetodePengujian += `<option value="${metode.value}">${metode.name}</option>`
        })

        const onSaveClick = () => {
            let formData = $('form').serializeArray();
            let formPermohonan = {};
            formData.forEach(value => {
                formPermohonan[value.name] = value.value
            });
            let data = @json($datas->all());
            formPermohonan = Object.assign(formPermohonan, data)
            let permohonanLabkon = [];
            if (localStorage.getItem('permohonanLabkon')) permohonanLabkon = JSON.parse(localStorage.getItem(
                'permohonanLabkon'))
            permohonanLabkon.push(formPermohonan);
            localStorage.setItem('permohonanLabkon', JSON.stringify(permohonanLabkon));
            console.log(permohonanLabkon)
            $(':input[type="submit"]').prop('disabled', true);
            $('#print').show();
            $('#upload_ttd').show();
        }

        $(document).ready(() => {
            $('#print').hide();
            $('#upload_ttd').hide();
            $('#check_box_bahan_uji').html(htmlCheckBoxBahanUji);
            $('#metode_pengujian').html(htmlDropdownMetodePengujian);
            $(".formatLatLong").keypress(function(evt) {
                return /^\-?[0-9]*\.?[0-9]*$/.test($(this).val() + evt.key);
            });
            $("#mapLatLong").ready(() => {
                require([
                    "esri/Map",
                    "esri/views/MapView",
                    "esri/Graphic",
                ], function(Map, MapView, Graphic) {
                    const map = new Map({
                        basemap: "osm",
                    });

                    const view = new MapView({
                        container: "mapLatLong",
                        map: map,
                        center: [107.6191, -6.9175],
                        zoom: 8,
                    });

                    let tempGraphic;
                    view.on("click", function(event) {
                        if ($("#lat").val() != "" && $("#long").val() != "") {
                            view.graphics.remove(tempGraphic);
                        }
                        var graphic = new Graphic({
                            geometry: event.mapPoint,
                            symbol: {
                                type: "picture-marker", // autocasts as new SimpleMarkerSymbol()
                                url: "http://esri.github.io/quickstart-map-js/images/blue-pin.png",
                                width: "14px",
                                height: "24px",
                            },
                        });
                        tempGraphic = graphic;
                        $("#lat").val(event.mapPoint.latitude);
                        $("#long").val(event.mapPoint.longitude);

                        view.graphics.add(graphic);
                    });
                    $("#lat, #long").keyup(function() {
                        if ($("#lat").val() != "" && $("#long").val() != "") {
                            view.graphics.remove(tempGraphic);
                        }
                        var graphic = new Graphic({
                            geometry: {
                                type: "point",
                                longitude: $("#long").val(),
                                latitude: $("#lat").val(),
                            },
                            symbol: {
                                type: "picture-marker", // autocasts as new SimpleMarkerSymbol()
                                url: "http://esri.github.io/quickstart-map-js/images/blue-pin.png",
                                width: "14px",
                                height: "24px",
                            },
                        });
                        tempGraphic = graphic;

                        view.graphics.add(graphic);
                    });
                });
            })


            $('#simpan').bind('click', () => onSaveClick())
        })

    </script>
@endsection
