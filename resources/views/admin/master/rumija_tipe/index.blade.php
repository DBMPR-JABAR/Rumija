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
                @if (hasAccess(Auth::user()->internal_role_id, "Rumija", "Create"))
                <a data-toggle="modal" href="#addModal" class="btn btn-mat btn-primary mb-3">Tambah</a>
                @endif
                <div class="dt-responsive table-responsive">
                    <table id="rumija_table" class="table table-striped table-bordered able-responsive">
                        <thead>
                            <tr>
                                <th style="width: 1mm">No</th>
                                <th>Nama</th>
                                <th>Slug</th>
                                <th style="width: 1mm">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rumija_tipe as $no => $rumija_tipe)
                            <tr>
                                <th>{{++$no}}</th>
                                <td>{{@$rumija_tipe->name}}</td>
                                <td>{{@$rumija_tipe->slug}}</td>
                                <td>
                                    {{-- <a href="{{ route('admin.rumija.report.show',$rumija_tipe->id) }}" class="btn btn-success btn-sm">Detail</a> --}}
                                    <a href="{{ route('admin.rumija-tipe.delete',$rumija_tipe->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data?')">Hapus</a>
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
                <form action="{{route('admin.rumija-tipe.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Tipe</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="col-form-label">Nama Tipe</label>
                            <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
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
    </script>
@endsection

