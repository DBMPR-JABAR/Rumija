@extends('admin.layout.index')

@section('title') Rekap @endsection
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
                <h4>Rekap</h4>
                <span>Data Rekap</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Rekap</a> </li>
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
                <h5>Tabel Rekap</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                <!-- <a data-toggle="modal" href="#addModal" class="btn btn-mat btn-primary mb-3">Tambah</a> -->
                <div class="dt-responsive table-responsive">
                    <table id="rekap-table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Bulan</th>
                                <th>SUP</th>
                                <th>Jenis Pekerjaan</th>
                                <th>Ruas Jalan</th>
                                <th>Volume</th>
                                <th>Satuan</th>
                                <th>Foto Awal</th>
                                <th>Foto (50%)</th>
                                <th>Foto (100%)</th>
                                <th>Video</th>
                                <th style="min-width: 75px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="bodyJembatan">
                           <!--  @foreach ($rekap as $data)
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>{{$data->bulan}}</td>
                                <td>{{$data->sup}}</td>
                                <td>{{$data->jenis_pekerjaan}}</td>
                                <td>{{$data->ruas_jalan}}</td>
                                <td>{{$data->volume}}</td>
                                <td>{{$data->satuan}}</td>
                                <td><img class="img-fluid" style="max-width: 100px" src="{!! url('storage/pekerjaan/'.$data->foto_awal) !!}" alt="" srcset=""></td>
                                <td><img class="img-fluid" style="max-width: 100px" src="{!! url('storage/pekerjaan/'.$data->foto_sedang) !!}" alt="" srcset=""></td>
                                <td><img class="img-fluid" style="max-width: 100px" src="{!! url('storage/pekerjaan/'.$data->foto_akhir) !!}" alt="" srcset=""></td>
                                <td><video width='150' height='100' controls>
                                        <source src="{!! url('storage/pekerjaan/'.$data->video) !!}" type='video/*' Sorry, your browser doesn't support the video element.></video></td>

                                <td style="min-width: 75px;">
                                    <div class="btn-group " role="group" data-placement="top" title="" data-original-title=".btn-xlg">
                                        <a href='http://www.google.com/maps/place/{{$data->lat}},{{$data->lng}}' target='_blank' data-toggle="tootip" title="Lokasi" class="btn btn-warning btn-sm waves-effect waves-light"><i class="icofont icofont-location-pin"></i></a>
                                        <a href='https://www.google.com/maps/@?api=1&map_action=pano&viewpoint={{$data->lat}},{{$data->lng}}&heading=13&pitch=93&fov=80' target='_blank' data-toggle="tootip" title="StreetView" class="btn btn-primary btn-sm waves-effect waves-light"><i class="icofont icofont-street-view"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach -->
                        </tbody>
                    </table>
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
<script>
    $(document).ready(function() {
        // $("#dttable").DataTable();
        $('#delModal').on('show.bs.modal', function(event) {
            const link = $(event.relatedTarget);
            const id = link.data('id');
            console.log(id);
            const url = `{{ url('admin/input-data/pekerjaan/delete') }}/` + id;
            console.log(url);
            const modal = $(this);
            modal.find('.modal-footer #delHref').attr('href', url);
        });

        $('#date-start').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
        });

        let table = $('#rekap-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: 'rekap/json',
            columns: [
                {'mRender': function (data, type, full,meta) {
                    return +meta.row +1;
                    }
                },
                { data: 'bulan', name: 'bulan' },
                { data: 'sup', name: 'sup' },
                { data: 'jenis_pekerjaan', name: 'jenis_pekerjaan' },
                { data: 'ruas_jalan', name: 'ruas_jalan' },
                { data: 'volume', name: 'volume' },
                { data: 'satuan', name: 'satuan' },
                {'mRender': function (data, type, full) {
                    return '<img class="img-fluid" style="max-width: 100px" src="'+`{{!! url('storage/') }}` +'/pekerjaan/'+full['foto_awal']+'" alt="" srcset="">';
                    }
                },
                {'mRender': function (data, type, full) {
                    return '<img class="img-fluid" style="max-width: 100px" src="'+`{{!! url('storage/') }}` +'/pekerjaan/'+full['foto_sedang']+'" alt="" srcset="">';
                    }
                },
                {'mRender': function (data, type, full) {
                    return '<img class="img-fluid" style="max-width: 100px" src="'+`{{!! url('storage/') }}` +'/pekerjaan/'+full['foto_akhir']+'" alt="" srcset="">';
                    }
                },
                {'mRender': function (data, type, full) {
                    return '<video width="150" height="100" controls><source src="'+`{!! url('storage/'}}`+'/pekerjaan/'+full['video'] +'" type="video/*" Sorry, your browser doesnt support the video element.></video>';
                    }
                },
                { data: 'action', name: 'action' },
            ]
        });

        table.on( 'order.dt search.dt', function () {
            table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();

        $('select').attr('value').trigger('change');
    });
</script>
@endsection
