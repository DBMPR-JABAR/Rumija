@extends('admin.layout.index')

@section('title') User @endsection
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
                <h4>User</h4>
                <!-- <span>Dashboard Pemetaan Kerusakan Infrastruktur Berdasarkan Laporan Masyarakat</span> -->
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">User</a> </li>
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
                <h5>Tabel User</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                @if (hasAccess(Auth::user()->internal_role_id, "Manajemen User", "Create"))
                <a data-toggle="modal" href="#addModal" class="btn btn-mat btn-primary mb-3">Tambah</a>
                @endif
                <div class="dt-responsive table-responsive">
                    <table id="dttable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Email</th>
                                <th>Nama Lengkap</th>
                                <th>NIP</th>
                                <th>SUP</th>
                                <th>Jabatan</th>
                                <th>No. Tlp</th>
                                <th>Blokir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $data)
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>{{$data->email}}</td>
                                <td>{{$data->name}}</td>
                                <td>@if($data->pegawai) {{$data->pegawai->no_pegawai}} @endif</td>
                                <td>{{$data->sup}}</td>
                                <td>@if($data->internalRole) {{$data->internalRole->role}} @endif</td>
                                <td>@if($data->pegawai) {{$data->pegawai->no_tlp}} @endif</td>
                                <td>{{$data->blokir}}
                                </td>
                                <td>
                                    @if (hasAccess(Auth::user()->internal_role_id, "Manajemen User", "Update"))
                                    <a href="{{ route('editUser',$data->id) }}" class="mb-2 btn btn-sm btn-warning btn-mat">Edit</a><br>
                                    @endif
                                    @if (hasAccess(Auth::user()->internal_role_id, "Manajemen User", "Delete"))
                                    <a href="#delModal" data-id="{{$data->id}}" data-toggle="modal" class="btn btn-sm btn-danger btn-mat">Hapus</a>
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
    @if (hasAccess(Auth::user()->internal_role_id, "Manajemen User", "Create"))
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <form action="{{route('createUser')}}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Data User</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Email</label>
                            <div class="col-md-10">
                                <input name="email" type="email" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Password</label>
                            <div class="col-md-10">
                                <div class="row" style="margin-left: 0px; margin-right: 0px;">
                                    <input id="password-field" name="password" type="password" class="form-control" required>
                                    <span style="cursor: pointer; margin-left: -30px;" class="ti-eye my-auto toggle-password" toggle="#password-field"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Nama Lengkap</label>
                            <div class="col-md-10">
                                <input name="name" type="text" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">NIP</label>
                            <div class="col-md-10">
                                <input name="no_pegawai" type="text" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">No. Telp/HP</label>
                            <div class="col-md-10">
                                <input name="no_tlp" type="text" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">SPP</label>
                            <div class="col-md-10">
                                <select class="form-control" required name="sup">
                                    <option>Pilih SPP</option>
                                    @foreach ($sup as $data)
                                    <option value="{{$data->name}}">{{$data->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Pilih Jabatan</label>
                            <div class="col-md-10">
                                <select class="form-control" required name="internal_role_id">
                                    <option>Pilih Jabatan</option>
                                    @foreach ($role as $data)
                                    <option value="{{$data->id}}">{{$data->role}}</option>
                                    @endforeach
                                </select>
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

    @if (hasAccess(Auth::user()->internal_role_id, "Manajemen User", "Delete"))
    <div class="modal fade" id="delModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Hapus Data User</h4>
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
<script>
    $(document).ready(function() {
        $("#dttable").DataTable();
        $('#delModal').on('show.bs.modal', function(event) {
            const link = $(event.relatedTarget);
            const id = link.data('id');
            console.log(id);
            const url = `{{ url('admin/master-data/user/delete') }}/` + id;
            console.log(url);
            const modal = $(this);
            modal.find('.modal-footer #delHref').attr('href', url);
        });
    });

    $(".toggle-password").click(function() {
        $(this).toggleClass("ti-eye ti-lock");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
</script>
@endsection
