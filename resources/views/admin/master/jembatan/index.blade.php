@extends('admin.layout.index')

@section('title') Jembatan @endsection
@section('head')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables.net/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables.net/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/data-table/extensions/responsive/css/responsive.dataTables.css') }}">

<link rel="stylesheet" href="https://js.arcgis.com/4.17/esri/themes/light/main.css">

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
                <h4>Jembatan</h4>
                <span>Data Seluruh Jembatan</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Jembatan</a> </li>
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
                <h5>Tabel Jembatan</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                @if (hasAccess(Auth::user()->internal_role_id, "Jembatan", "Create"))
                <a href="{{ route('addJembatan') }}" class="btn btn-mat btn-primary mb-3">Tambah</a>
                @endif
                <div class="dt-responsive table-responsive">
                    <table id="dttable" class="table table-striped table-bordered able-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Jembatan</th>
                                <th>Lokasi</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Panjang (meter)</th>
                                <th>Lebar (meter)</th>
                                <th>Ruas Jalan</th>
                                <th>UPTD</th>
                                <!-- <th>Foto</th> -->
                                <th style="min-width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        {{-- <!-- <tbody id="bodyJembatan">
                            @foreach ($jembatan as $data)
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>{{$data->nama_jembatan}}</td>
                                <td>{{$data->lokasi}}</td>
                                <td>{{$data->lat}}</td>
                                <td>{{$data->lng}}</td>
                                <td>{{$data->panjang}}</td>
                                <td>{{$data->lebar}}</td>
                                <td>{{$data->ruas_jalan}}</td>
                                <td><img class="img-fluid" style="max-width: 100px" src="{!! url('storage/'.$data->foto) !!}" alt="" srcset=""></td>
                                <td class="mx-auto" style="min-width: 80px;">
                                    <div class="btn-group " role="group" data-placement="top" title="" data-original-title=".btn-xlg">
                                        @if (hasAccess(Auth::user()->internal_role_id, "Jembatan", "Update"))
                                        <a href="{{ route('editJembatan',$data->id) }}"><button data-toggle="tooltip" title="Edit" class="btn btn-primary btn-sm waves-effect waves-light"><i class="icofont icofont-pencil"></i></button></a>
                                        @endif
                                        @if (hasAccess(Auth::user()->internal_role_id, "Jembatan", "Delete"))
                                        <a href="#delModal" data-id="{{$data->id}}" data-toggle="modal"><button data-toggle="tooltip" title="Hapus" class="btn btn-danger btn-sm waves-effect waves-light"><i class="icofont icofont-trash"></i></button></a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody> --> --}}
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-only">
    @if (hasAccess(Auth::user()->internal_role_id, "Jembatan", "Create"))
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <form action="{{route('createJembatan')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Data Jembatan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Id Jembatan</label>
                            <div class="col-md-10">
                                <input name="id_jembatan" type="text" class="form-control" maxlength="10" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Nama Jembatan</label>
                            <div class="col-md-10 my-auto">
                                <input name="nama_jembatan" type="text" class="form-control" required>
                            </div>
                        </div>

                        @if(Auth::user()->internalRole->uptd)
                        <input type="hidden" id="uptd" name="uptd" value="{{Auth::user()->internalRole->uptd}}">
                        @else
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">UPTD</label>
                            <div class="col-md-10">
                                <select class="form-control" id="uptd" name="uptd" required onchange="ubahOption()">
                                    <option>Pilih UPTD</option>
                                    @foreach ($uptd as $data)
                                    <option value="{{$data->slug}}">{{$data->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Ruas Jalan</label>
                            <div class="col-md-10">
                                <select id="ruas_jalan" id="ruas_jalan" name="ruas_jalan" class="form-control" required>
                                    @if (Auth::user()->internalRole->uptd)
                                    @foreach ($ruasJalan as $data)
                                    <option value="{{$data->nama_ruas_jalan}}">{{$data->nama_ruas_jalan}}</option>
                                    @endforeach
                                    @else
                                    <option>-</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">SUP</label>
                            <div class="col-md-10">
                                <select class="form-control" id="sup" name="sup" required>
                                    @if (Auth::user()->internalRole->uptd)
                                    @foreach ($sup as $data)
                                    <option value="{{$data->name}}">{{$data->name}}</option>
                                    @endforeach
                                    @else
                                    <option>-</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Lokasi</label>
                            <div class="col-md-10">
                                <input name="lokasi" type="text" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Panjang (meter)</label>
                            <div class="col-md-10 my-auto">
                                <input name="panjang" type="text" class="form-control formatRibuan" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Lebar (meter)</label>
                            <div class="col-md-10">
                                <input name="lebar" type="text" class="form-control formatRibuan" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Jumlah Bentang</label>
                            <div class="col-md-10 my-auto">
                                <input name="jumlah_bentang" type="number" class="form-control" step="any" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Koordinat X (Lat)</label>
                            <div class="col-md-10 my-auto">
                                <input name="lat" type="text" class="form-control formatLatLong" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Koordinat Y (Lon)</label>
                            <div class="col-md-10 my-auto">
                                <input name="lng" type="text" class="form-control formatLatLong" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Keterangan</label>
                            <div class="col-md-10">
                                <input name="ket" type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Foto Jembatan</label>
                            <div class="col-md-6">
                                <input name="foto" type="file" class="form-control">
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light ">Simpan</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    @endif

    @if (hasAccess(Auth::user()->internal_role_id, "Jembatan", "Delete"))
    <div class="modal fade" id="delModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Hapus Data Jembatan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>Apakah anda yakin ingin menghapus data ini?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Tutup</button>
                    <a id="delHref" href="" class="btn btn-danger waves-effect waves-light ">Hapus</a>
                </div>

            </div>
        </div>
    </div>
    @endif
</div>
@endsection
@section('script')
<script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net/js/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery/js/jquery.mask.js') }}"></script>
<script>
    $(document).ready(function() {
        // $("#dttable").DataTable();
        $('#delModal').on('show.bs.modal', function(event) {
            const link = $(event.relatedTarget);
            const id = link.data('id');
            console.log(id);
            const url = `{{ url('admin/master-data/jembatan/delete') }}/` + id;
            console.log(url);
            const modal = $(this);
            modal.find('.modal-footer #delHref').attr('href', url);
        });

        // Format mata uang.
        $('.formatRibuan').mask('000.000.000.000.000', {
            reverse: true
        });

        // Format untuk lat long.
        $('.formatLatLong').keypress(function(evt) {
            return (/^\-?[0-9]*\.?[0-9]*$/).test($(this).val() + evt.key);
        });

        var table = $('#dttable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('admin/master-data/jembatan/json') }}",
            columns: [{
                    'mRender': function(data, type, full, meta) {
                        return +meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'nama_jembatan',
                    name: 'nama_jembatan'
                },
                {
                    data: 'lokasi',
                    name: 'lokasi'
                },
                {
                    data: 'lat',
                    name: 'lat'
                },
                {
                    data: 'lng',
                    name: 'lng'
                },
                {
                    data: 'panjang',
                    name: 'panjang'
                },

                {
                    data: 'lebar',
                    name: 'lebar'
                },
                {
                    data: 'ruas_jalan',
                    name: 'ruas_jalan'
                },
                {
                    data: 'uptd_format',
                    name: 'uptd_format'
                },
                // {
                //     'mRender': function(data, type, full) {
                //         return '<img class="img-fluid" style="max-width: 100px" src="/storage/' + full['foto'] + '" alt="" srcset="">';
                //     }
                // },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    });

    function ubahOption() {

        //untuk select Ruas
        val = document.getElementById("uptd").value
        id = parseInt(val.slice(val.length - 1))

        url = "{{ url('admin/input-data/kondisi-jalan/getRuasJalan') }}"
        id_select = '#ruas_jalan'
        text = 'Pilih Ruas Jalan'
        option = 'nama_ruas_jalan'

        setDataSelect(id, url, id_select, text, option, option)

        //untuk select SUP
        url = "{{ url('admin/master-data/ruas-jalan/getSUP') }}"
        id_select = '#sup'
        text = 'Pilih SUP'
        option = 'name'

        setDataSelect(id, url, id_select, text, option, option)
    }
</script>
@endsection
