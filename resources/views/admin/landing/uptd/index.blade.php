@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection

@section('head')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables.net/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables.net/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/data-table/extensions/responsive/css/responsive.dataTables.css') }}">
@endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>UPTD</h4>
                <span>Seluruh UPTD yang ada di naungan DBMPR Jabar</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">UPTD</a> </li>
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
                <h5>Daftar UPTD</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                @if(hasAccess(Auth::user()->internal_role_id, 'UPTD', 'Create'))
                <a data-toggle="modal" href="#addModal" class="btn btn-mat btn-primary mb-3">Tambah</a>
                @endif
                <table id="dttable" class="table table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama</th>
                            <th>Nama Lain</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($uptd as $data)
                        <tr>
                            <td><img class="img-fluid" style="max-width: 100px" src="{!! url('storage/'.$data->gambar) !!}" alt="" srcset=""></td>
                            <td>{{$data->nama}}</td>
                            <td>{{$data->altnama}}</td>
                            <td>{{$data->deskripsi}}</td>
                            <td>
                                <a type='button' href='{{ route('editMasterUPTD',$data->id) }}'  class='btn btn-primary btn-mini waves-effect waves-light'><i class='icofont icofont-check-circled'></i>Edit</a>
                                <a type='button' href='#delModal'  data-toggle='modal' data-id='{{$data->id}}'     class='btn btn-warning btn-mini waves-effect waves-light'><i class='icofont icofont-trash'></i>Hapus</a><br/>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-only">
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <form action="{{route('createLandingUPTD')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Data UPTD</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Nama</label>
                            <div class="col-md-10">
                                <input name="nama" type="text" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Nama Lain</label>
                            <div class="col-md-10">
                                <input name="altnama" type="text" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Deskripsi</label>
                            <div class="col-md-10">
                                <textarea name="deskripsi" rows="3" cols="3" class="form-control" placeholder="Masukkan Deskripsi" required></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Gambar</label>
                            <div class="col-md-6">
                                <input name="gambar" type="file" class="form-control">
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

    <div class="modal fade" id="delModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">Hapus Data UPTD</h4>
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
</li>
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

            const url = `{{ url('admin/landing-page/uptd/delete') }}/` + id;

            const modal = $(this);
            modal.find('.modal-footer #delHref').attr('href',url);
        });
    });
</script>
@endsection
