@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Master Ruas Jalan</h4>

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
                <a data-toggle="modal" href="#addModal" class="btn btn-mat btn-primary mb-3">Tambah</a>
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Id Ruas Jalan</th>
                            <th>Nama Ruas Jalan</th>
                            <th>Sup</th>
                            <th>Lokasi</th>
                            <th>Panjang</th>
                            <th>Sta Awal</th>
                            <th>Sta Akhir</th>
                            <th>Lat Awal</th>
                            <th>Long Awal</th>
                            <th>Lat Akhir</th>
                            <th>Long Akhir</th>
                            <th>Create Date</th>
                            <th>Create By</th>
                            <th>Update Date</th>
                            <th>Update By</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ruas_jalan as $data)
                        <tr>
                            <td>  <td>
                            <td>{{$data->id_ruas_jalan}}</td>
                            <td>{{$data->nama_ruas_jalan}}</td>
                            <td>{{$data->sup}}</td>
                            <td>{{$data->lokasi}}</td>
                            <td>{{$data->panjang}}</td>
                            <td>{{$data->sta_awal}}</td>
                            <td>{{$data->sta_akhir}}</td>
                            <td>{{$data->lat_awal}}</td>
                            <td>{{$data->long_awal}}</td>
                            <td>{{$data->lat_akhir}}</td>
                            <td>{{$data->long_akhir}}</td>
                            <td>{{$data->created_date}}</td>
                            <td>{{$data->created_by}}</td>
                            <td>{{$data->updated_date}}</td>
                            <td>{{$data->updated_by}}</td>
                            <td>
                                <a href=""
                                    class="mb-2 btn btn-sm btn-warning btn-mat">Edit</a><br>
                                <a href="#delModal" data-id="{{$data->id}}" data-toggle="modal" class="btn btn-sm btn-danger btn-mat">Hapus</a>
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
<script>
    $(document).ready(function () {
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
