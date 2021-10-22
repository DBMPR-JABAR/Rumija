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
                <h4>SUP</h4>
                <span>Data SUP</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">SUP</a> </li>
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
                <h5>Daftar SUP</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                @if(hasAccess(Auth::user()->internal_role_id, 'SUP', 'Create'))
                <a data-toggle="modal" href="#addModal" class="btn btn-mat btn-primary mb-3">Tambah</a>
                @endif
                <div class="dt-responsive table-responsive">
                    <table id="dttable" class="table table-striped table-bordered able-responsive">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="1%">Kode SUP</th>
                                <th>Nama</th>
                                <th width="5%">UPTD</th>

                                <th width="5%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="bodyJembatan">
                             @foreach ($sup as $no => $data)

                                <tr>
                                    <td>{{++$no}}</td>
                                    <td>{{$data->kd_sup}}</td>

                                    <td>{{$data->name}}</td>
                                    <td>UPTD {{$data->uptd_id}}</td>

                                    <td>

                                            @if (hasAccess(Auth::user()->internal_role_id, "SUP", "Update"))
                                            <a type='button' href='{{route('editSUP',$data->id)}}'  class='btn btn-primary btn-mini waves-effect waves-light'><i class='icofont icofont-edit'></i>Edit</a>
                                            @endif
                                            @if (hasAccess(Auth::user()->internal_role_id, "SUP", "Delete"))
                                            <a type='button' href='#delModal'  data-toggle='modal' data-id='{{$data->id}}' class='btn btn-warning btn-mini waves-effect waves-light'><i class='icofont icofont-trash'></i>Hapus</a><br/>
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
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <form action="{{ url('admin/master-data/sup/store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Data SUP</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        @if(Auth::user() && Auth::user()->internalRole->uptd == null)
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">UPTD</label>
                                <div class="col-md-10">
                                    <select name="uptd_id" id="province" class="form-control">
                                        <option value="">== Select UPTD ==</option>
                                        @foreach ($uptd_lists as $no => $data)
                                            <option value="{{ $data->id }}">{{ $data->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Nama SUP</label>
                            <div class="col-md-10">
                                <input name="name" type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Kode SUP</label>
                            <div class="col-md-10">
                                <input name="kd_sup" type="text" class="form-control" required>
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

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Edit SUP</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-5">
                        @if(Auth::user() && Auth::user()->internalRole->uptd == null)
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">UPTD</label>
                                <div class="col-md-10">
                                    <select name="uptd_id" class="form-control">
                                        <option value="">== Select UPTD ==</option>
                                        @foreach ($uptd_lists as $no => $data)
                                            <option value="{{ $data->id }}">{{ $data->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Nama SUP</label>
                            <div class="col-md-10">
                                <input name="name" id="sup_name" type="text" class="form-control" required>
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
                        <h4 class="modal-title">Hapus Data SUP</h4>
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

            const url = `{{ url('admin/master-data/sup/delete') }}/` + id;

            const modal = $(this);
            modal.find('.modal-footer #delHref').attr('href',url);
        });
        $('#editModal').on('show.bs.modal', function(event) {
            const link = $(event.relatedTarget);
            const id = link.data('id');
            console.log(id);
            const baseUrl = `{{ url('admin/master-data/sup/edit') }}/` + id;
            $.get(baseUrl, { id: id },
                function(response){
                    // $('#id').val(response.icon[0].id);
                    $('#sup_name').val(response.icon[0].sup_name);
                });
            });
    });
</script>
@endsection
