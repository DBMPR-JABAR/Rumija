@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection
@section('head')
<link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables.net/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables.net/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/data-table/extensions/responsive/css/responsive.dataTables.css') }}">
@endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Video Controls</h4>
                <span>Mengatur video di landing page.</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Video</a> </li>
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
                <h5>Berita Video pada Landing Page</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                {{-- <a data-toggle="modal" href="#addModal" class="btn btn-mat btn-primary mb-3">Tambah</a> --}}
                <div class="dt-responsive table-responsive">
                <table id="dttable" class="table table-striped table-bordered able-responsivee">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Video</th>
                            <th>Link Video</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $video)
                        <tr>
                            <td>{{++$index}}</td>
                            <td>{{$video->title}}</td>
                            <td>{{$video->url}}</td>
                            <td>
                                <a type='button' href='#editModal' data-toggle="modal" data-id="{{$video->id}}"  class='btn btn-primary btn-mini waves-effect waves-light'><i class='icofont icofont-check-circled'></i>Edit</a>
                                {{-- <a type='button' href='#delModal'  data-toggle='modal' data-id='{{$video->id}}'     class='btn btn-warning btn-mini waves-effect waves-light'><i class='icofont icofont-trash'></i>Hapus</a><br/> --}}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">Data Tidak Ada</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <div class="modal-only">
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Data Berita Video</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form action="{{route('video-news.store')}}" method="post">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Judul Video</label>
                        <div class="col-md-10">
                            <input name="title" type="text" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">URL Video</label>
                        <div class="col-md-10">
                            <input name="url" type="text" class="form-control" required>
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
    </div> --}}

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">Ubah Data Berita Video</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="content">

                    </div>
            </div>
        </div>
    </div>

    {{-- <div class="modal fade" id="delModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">Hapus Data Video</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <p>Apakah anda yakin ingin menghapus data ini?</p>
                    </div>

                    <div class="modal-footer">
                        <form id="delHref" action="" method="get">
                            <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-danger waves-effect waves-light ">Hapus</button>
                        </form>
                    </div>

            </div>
        </div>
    </div> --}}
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
        $('#editModal').on('show.bs.modal', function (event) {
            const link = $(event.relatedTarget);
            const id = link.data('id');

            const url = "{{ url('admin/landing-page/video-news') }}/" + id;

            const modal = $(this);
            modal.find('.content').load(url);
        });
        // $('#delModal').on('show.bs.modal', function (event) {
        //     const link = $(event.relatedTarget);
        //     const id = link.data('id');

        //     const url = `{{ url('admin/landing-page/video-news') }}/` + id + '/edit';

        //     const modal = $(this);
        //     modal.find('.modal-footer #delHref').attr('action',url);
        // });
    });
</script>
@endsection
