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
                <h4>Laporan Masyarakkat</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Laporan Masyarakat</a> </li>
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
                <h5>Daftar Laporan Masyarakat</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                <a data-toggle="modal" href="#addModal" class="btn btn-mat btn-primary mb-3">Tambah</a>
                <table id="dttable" class="table table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Pengaduan</th>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Email</th>
                            <th>Jenis</th>
                            <th>Gambar</th>
                            <th>Lokasi</th>
                            <th>Lat</th>
                            <th>Long</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th>UPTD</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 1?>
                        @foreach ($laporan as $data)
                        <tr>
                            <td>{{$number++}}</td>
                            <td>{{$data->nomorPengaduan}}</td>
                            <td>{{$data->nama}}</td>
                            <td>{{$data->nik}}</td>
                            <td>{{$data->alamat}}</td>
                            <td>{{$data->telp}}</td>
                            <td>{{$data->email}}</td>
                            <td>{{$data->jenis}}</td>
                            <td><img class="img-fluid" style="max-width: 100px" src="{{$data->gambar}}" alt="" srcset=""></td>
                            <td>{{$data->lokasi}}</td>
                            <td>{{$data->lat}}</td>
                            <td>{{$data->long}}</td>
                            <td>{{$data->deskripsi}}</td>
                            <td>{{$data->status}}</td>
                            <td>UPTD {{$data->uptd_id}}</td>
                            <td>{{$data->created_at}}</td>
                            <td>{{$data->updated_at}}</td>
                            <td>
                                <a type="button" href="{{ route('detailLandingLaporanMasyarakat',$data->id) }}"  class="btn btn-primary btn-mini waves-effect waves-light"><i class="icofont icofont-check-circled"></i>Rincian</a>
                                <a type="button" href="#editModal" data-toggle="modal" data-id="{{$data->id}}"  class="btn btn-primary btn-mini waves-effect waves-light"><i class="icofont icofont-check-circled"></i>Edit</a>
                                <a type="button"href="#delModal"  data-toggle="modal" data-id="{{$data->id}}"     class="btn btn-primary btn-mini waves-effect waves-light"><i class="icofont icofont-check-circled"></i>Hapus</a>
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

                <form action="{{route('createLandingLaporanMasyarakat')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Laporan Masyarakat</h4>
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
                            <label class="col-md-2 col-form-label">No KTP</label>
                            <div class="col-md-10">
                                <input name="nik" type="text" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Alamat</label>
                            <div class="col-md-10">
                                <input name="alamat" type="text" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Telepon</label>
                            <div class="col-md-10">
                                <input name="telp" type="tel" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Email</label>
                            <div class="col-md-10">
                                <input name="email" type="email" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Jenis</label>
                            <div class="col-md-10">
                                <input name="jenis" type="text" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Gambar</label>
                            <div class="col-md-6">
                                <input name="gambar" type="file" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">lokasi</label>
                            <div class="col-md-10">
                                <input name="lokasi" type="text" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Lat</label>
                            <div class="col-md-10">
                                <input name="lat" type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Long</label>
                            <div class="col-md-10">
                                <input name="long" type="text" class="form-control" required>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Deskripsi</label>
                            <div class="col-md-10">
                                <textarea name="deskripsi" rows="3" cols="3" class="form-control" placeholder="Masukkan Deskripsi" required></textarea>
                            </div>
                        </div>

                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">UPTD</label>
                                    <select name="uptd_id" class="custom-select " id="pilihanUptd" required>
                                        <option selected>Pilih...</option>
                                        <option value="1">UPTD-I</option>
                                        <option value="2">UPTD-II</option>
                                        <option value="3">UPTD-III</option>
                                        <option value="4">UPTD-IV</option>
                                        <option value="5">UPTD-V</option>
                                        <option value="6">UPTD-VI</option>
                                    </select>
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
                        <h4 class="modal-title">Hapus Data Laporan Masyarakat</h4>
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

    <div class="modal-only">
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <form action="{{route('updateLandingLaporanMasyarakat')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Laporan Masyarakat</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Nama</label>
                            <div class="col-md-10">
                                <input name="nama" id="nama" type="text" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">NIK</label>
                            <div class="col-md-10">
                                <input name="nik" id="nik" type="text" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Alamat</label>
                            <div class="col-md-10">
                                <input name="alamat" id="alamat" type="text" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Telepon</label>
                            <div class="col-md-10">
                                <input name="telp" id="telp" type="tel" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Email</label>
                            <div class="col-md-10">
                                <input name="email" id="email" type="email" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Jenis</label>
                            <div class="col-md-10">
                                <input name="jenis" id="jenis" type="text" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Gambar</label>
                            <div class="col-md-6" id="gambar">
                                <input name="gambar" type="file" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">lokasi</label>
                            <div class="col-md-10">
                                <input name="lokasi" id="lokasi" type="text" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Lat</label>
                            <div class="col-md-10">
                                <input name="lat" id="lat" type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Long</label>
                            <div class="col-md-10">
                                <input name="long" id="long" type="text" class="form-control" required>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Deskripsi</label>
                            <div class="col-md-10">
                                <textarea name="deskripsi" id="deskripsi" rows="3" cols="3" class="form-control" placeholder="Masukkan Deskripsi" required></textarea>
                            </div>
                        </div>

                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">UPTD</label>
                                    <select name="uptd_id" class="custom-select " id="pilihanUptd" required>
                                        <option selected>Pilih...</option>
                                        <option value="1" id="uptd1">UPTD-I</option>
                                        <option value="2" id="uptd2">UPTD-II</option>
                                        <option value="3" id="uptd3">UPTD-III</option>
                                        <option value="4" id="uptd4">UPTD-IV</option>
                                        <option value="5" id="uptd5">UPTD-V</option>
                                        <option value="6" id="uptd6">UPTD-VI</option>
                                    </select>
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

            const url = `{{ url('admin/landing-page/laporan-masyarakat/delete') }}/` + id;

            const modal = $(this);
            modal.find('.modal-footer #delHref').attr('href',url);
        });
    });
    $('#editModal').on('show.bs.modal', function(event) {
            const link = $(event.relatedTarget);
            const id = link.data('id');
            console.log(id);
            const baseUrl = `{{ url('admin/landing-page/laporan-masyarakat/edit') }}/` + id;
            $.get(baseUrl, { id: id },
                function(response){
                    console.log(response.nama);
                    $('#nama').val(response.data[0].nama);
                    $('#nik').val(response.data[0].nik);
                    $('#alamat').val(response.data[0].alamat);
                    $('#telp').val(response.data[0].telp);
                    $('#email').val(response.data[0].email);
                    $('#jenis').val(response.data[0].jenis);
                    $('#lat').val(response.data[0].lat);
                    $('#long').val(response.data[0].long);
                    $('#deskripsi').html(response.data[0].deskripsi);
                    for(var i=1;i<=6;i++){
                        if($('#uptd'+i).val() == response.data[0].uptd_id){
                            $('#uptd'+i).attr('selected','selected');
                        }
                    }
                });
        });
</script>
@endsection
