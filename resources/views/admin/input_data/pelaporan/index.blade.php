@extends('admin.layout.index')

@section('title') Pekerjaan @endsection
@section('head')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables.net/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables.net/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/data-table/extensions/responsive/css/responsive.dataTables.css') }}">

<link rel="stylesheet" href="https://js.arcgis.com/4.17/esri/themes/light/main.css">
<link rel="stylesheet" href="https://js.arcgis.com/4.18/esri/themes/light/main.css">

<style>
    table.table-bordered tbody td {
        word-break: break-word;
        vertical-align: top;
    }
</style>
@endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Pelaporan Rumija</h4>
                {{-- <span>Data Seluruh Rumija</span> --}}
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class="breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}">
                        <i class="feather icon-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="/admin">Rumija</a></li>
                <li class="breadcrumb-item"><a href="#!">Pelaporan Rumija</a></li>

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
                <h5 id="tbltitle">Pelaporan Rumija</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                @if (hasAccess(Auth::user()->internal_role_id, "Pekerjaan", "Create"))
                <a data-toggle="modal" href="#addModal" class="btn btn-mat btn-primary mb-3">Tambah</a>
                @endif
                <div class="dt-responsive table-responsive">
                    <table id="rumija_table" class="table table-striped table-bordered able-responsive">
                        <thead>
                            <tr>
                                <th style="width: 1mm">No</th>
                                <th>Nama</th>
                                <th>Keterangan</th>
                                <th>Foto</th>
                                <th>Ruas</th>
                                <th style="width: 1mm">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pelaporan as $pelaporan)
                            <tr>
                                <th>{{$loop->iteration}}</th>
                                <td>{{@$pelaporan->user->name}}</td>
                                <td>{{@$pelaporan->keterangan}}</td>
                                <td class="text-center">
                                    <img src="{{ Storage::url('public/laporan_rumija/'.$pelaporan->image) }}" alt="" style="width: 90px;object-fit:cover">
                                </td>
                                <td>{{@$pelaporan->ruas->nama_ruas_jalan}}</td>
                                <td>
                                    <a href="{{ route('admin.rumija.report.show',$pelaporan->id) }}" class="btn btn-success btn-sm">Detail</a>
                                    <a href="{{ route('admin.rumija.report.edit',$pelaporan->id) }}" class="btn btn-warning btn-sm">Ubah</a>
                                    <a href="{{ route('admin.rumija.report.delete',$pelaporan->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data?')">Hapus</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
   
</div>

<div class="modal-only">
    <div class="modal fade searchableModalContainer" id="addModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <form action="{{route('admin.rumija.report.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Laporan Rumija</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="col-form-label">UPTD</label>
                            <select class="form-control" id="uptd" name="uptd_id" onchange="ubahOption()" required>
                                <option>Pilih UPTD</option>
                                @foreach ($uptd_lists as $data)
                                <option value="{{$data->id}}">{{$data->nama}}</option>
                                @endforeach
                            </select>
                        </div>						  

                        <div class="mb-2">
                            <label for="RuasJalan" class="col-form-label">Ruas Jalan</label>
                            <select class="searchableModalField form-control" id="ruas_jalan" name="ruas_jalan_id" style="width: 100%" required>
                                <option>-</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="col-form-label">Dokumentasi</label>
                            <input name="image" type="file" class="form-control @error('keterangan') is-invalid @enderror" accept="image/*" required>
                            @error('image')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>			  						  						  						  
                        
                        <div class="mb-2">
                            <label class="col-form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror"></textarea>
                            @error('keterangan')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>

                        <div id="mapLatLong" class="full-map mb-2" style="height: 300px; width: 100%"></div>
                        
                        <div class="mb-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="col-form-label">Koordinat X</label>
                                    <input id="lat" name="lat" type="text" class="form-control formatLatLong" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label">Koordinat Y</label>
                                    <input id="long" name="long" type="text" class="form-control formatLatLong" required>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light " >Simpan</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
<div class="modal-only">
    <div class="modal fade" id="delModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hapus Inventaris</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>Apakah anda yakin ingin menghapus data ini?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">
                        Tutup
                    </button>
                    <a id="delHref" href="" class="btn btn-danger waves-effect waves-light">Hapus</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <!-- <script src="{{ asset('assets/vendor/jquery/js/jquery-3.4.1.min.js') }}" ></script> -->
    <script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables.net/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables.net/js/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery/js/jquery.mask.js') }}"></script>
    <script src="https://js.arcgis.com/4.18/"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

   <script>
        $(document).ready(function() {
            // Format mata uang.
            $('.formatRibuan').mask('000.000.000.000.000', {
                reverse: true
            });

            // Format untuk lat long.
            $('.formatLatLong').keypress(function(evt) {
                return (/^\-?[0-9]*\.?[0-9]*$/).test($(this).val() + evt.key);
            });

            $("#dttable").DataTable(
                {
                "bInfo" : false
                }
            );
            $("#delModal").on("show.bs.modal", function (event) {
                const link = $(event.relatedTarget);
                const id = link.data("id");
                console.log(id);
                const url = `{{ url('admin/input-data/rumija/pelaporan/delete') }}/` + id;
                console.log(url);
                const modal = $(this);
                modal.find(".modal-footer #delHref").attr("href", url);
            });
            $('#submitModal').on('show.bs.modal', function(event) {
                const link = $(event.relatedTarget);
                const id = link.data('id');
                console.log(id);
                const url = `{{ url('admin/input-data/pekerjaan/submit') }}/` + id;
                console.log(url);
                const modal = $(this);
                modal.find('.modal-footer #submitHref').attr('href', url);
            });
            // $('select').attr('value').trigger('change');

            function getYearFilter() {
                return {
                    yearFrom : $("#yearFrom").val(),
                    yearTo : $("#yearTo").val()
                };
            }


            $('#yearFrom, #yearTo').change(function () {
                // $("#tbltitle").html(`Data Pekerjaan dari Tahun ${getYearFilter().yearFrom} hingga Tahun ${getYearFilter().yearTo}`);
                $('#dttable').DataTable().ajax.reload(null, false);
            })


            $('#mapLatLong').ready(() => {
                require([
                "esri/Map",
                "esri/views/MapView",
                "esri/Graphic"
                ], function(Map, MapView, Graphic) {

                    const map = new Map({
                        basemap: "osm"
                    });

                    const view = new MapView({
                        container: "mapLatLong",
                        map: map,
                        center: [107.6191, -6.9175],
                        zoom: 8,
                    });

                    let tempGraphic;
                    view.on("click", function(event){
                        if($("#lat").val() != '' && $("#long").val() != ''){
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
                    $("#lat, #long").keyup(function () {
                        if($("#lat").val() != '' && $("#long").val() != ''){
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

    // });

        // });

    function ubahOption() {
        //untuk select Ruas
        id = document.getElementById("uptd").value
        url = "{{ url('admin/input-data/kondisi-jalan/getRuasJalan') }}"
        id_select = '#ruas_jalan'
        text = 'Pilih Ruas Jalan'
        option = 'nama_ruas_jalan'
        id_ruass = 'id_ruas_jalan'

        setDataSelect(id, url, id_select, text, id_ruass, option)
    }
    </script>
@endsection
