@extends('admin.layout.index')

@section('title') List Data @endsection
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
                <h4>List Data</h4>
                <!-- <span>Data Seluruh Jembatan</span> -->
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">List Data</a> </li>
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
                <h5>Tabel List Data</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                <div class="dt-responsive table-responsive">
                    <table id="dttable" class="table table-striped table-bordered able-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No SPP</th>
                                <th>Tanggal</th>
                                <th>SUP</th>
                                <th>Ruas Jalan</th>
                                <th>Jenis Pekerjaan</th>
                                <th>Lokasi</th>
                                <th>Foto (0%)</th>
                                <th>Foto (50%)</th>
                                <th>Foto (100%)</th>
                                <th>Video</th>
                                <th>File PDF</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="bodyJembatan">
                            @foreach ($kemandoran as $data)
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>{{$data->id_pek}}</td>
                                <td>{{$data->tanggal}}</td>
                                <td>{{$data->sup}}</td>
                                <td>{{$data->ruas_jalan}}</td>
                                <td>{{$data->jenis_pekerjaan}}</td>
                                <td>{{$data->lokasi}}</td>
                                <td><img class="img-fluid" style="max-width: 100px" src="{!! url('storage/'.$data->foto_awal) !!}" alt="" srcset=""></td>
                                <td><img class="img-fluid" style="max-width: 100px" src="{!! url('storage/'.$data->foto_sedang) !!}" alt="" srcset=""></td>
                                <td><img class="img-fluid" style="max-width: 100px" src="{!! url('storage/'.$data->foto_akhir) !!}" alt="" srcset=""></td>
                                <td><video width ='150' height='100' controls><source src="{!! url('storage/pekerjaan/'.$data->video) !!}" type='video/*' Sorry, your browser doesn't support the video element.></video></td>
                                <td><a href="{!! url('storage/'.$data->file) !!}" target="_blanks">Preview</a></td>
                                <td>
                                    @if (hasAccess(Auth::user()->internal_role_id, "List Data", "Edit"))
                                    <a href="{{ route('editKeuangan',$data->id_pek) }}" class="mb-2 btn btn-sm btn-warning btn-mat">Edit</a><br>
                                    @endif
                                    @if (hasAccess(Auth::user()->internal_role_id, "List Data", "Delete"))
                                    <a href="#delModal" data-id="{{$data->id_pek}}" data-toggle="modal" class="btn btn-sm btn-danger btn-mat">Hapus</a>
                                    @endif
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
    <div class="modal fade" id="delModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hapus Data Keuangan</h4>
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
</div>
@endsection
@section('script')
<script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}" ></script>
<script src="{{ asset('assets/vendor/datatables.net/js/dataTables.buttons.min.js') }}" ></script>
<script src="{{ asset('assets/vendor/datatables.net/js/dataTables.bootstrap4.min.js') }}" ></script>

<script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $("#dttable").DataTable();
        $('#delModal').on('show.bs.modal', function (event) {
            const link = $(event.relatedTarget);
            const id = link.data('id');
            console.log(id);
            const url = `{{ url('admin/input-data/keuangan/delete') }}/` + id;
            console.log(url);
            const modal = $(this);
            modal.find('.modal-footer #delHref').attr('href',url);
        });
    });
</script>
@endsection
