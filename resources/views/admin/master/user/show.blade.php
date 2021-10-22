@extends('admin.layout.index')

@section('title')Role Akses @endsection
@section('head')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables.net/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables.net/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/data-table/extensions/responsive/css/responsive.dataTables.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/chosen_v1.8.7/chosen.css') }}">
<link rel="stylesheet" href="https://js.arcgis.com/4.17/esri/themes/light/main.css">

<style>
.chosen-container.chosen-container-single {
    width: 300px !important; /* or any value that fits your needs */
}

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
                    <h4>Akun & Profil </h4>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#!">Akun & Profil</a> </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('page-body')
    <div class="row">
        <div class="col-sm-12">

            <div class="card">
                <div class="card-header ">
                <h4 class="card-title">Profile {!! Str::title(@$profile->nama) !!}</h4>
                    <div class="card-header-right">
                            {{-- <button type="submit" class="btn btn-responsive btn-warning">Edit Password</button>
                            <button type="submit" class="btn btn-responsive btn-primary">Edit Profil</button> --}}
                    </div>
                </div>
                <div class="card-block">
                    {{-- <a href="{{ route('createRoleAccess') }}" class="btn btn-mat btn-primary mb-3">Tambah</a> --}}
                    {{-- <form action="{{url('admin/user/account/'.Auth::user()->id)}}" method="post" enctype="multipart/form-data"> --}}
                        <div class="dt-responsive table-responsive">
                            {{-- Content here --}}
                            {{-- <button type="submit" class="btn btn-responsive btn-warning">Edit Password</button> --}}
                            @if(Request::segment(2) == 'profile')
                            <a type="button"href="#editModal"  data-toggle="modal" data-id="{{Auth::user()->id}}"  class="btn btn-responsive btn-warning">
                                {{-- <i class="icofont icofont-check-circled"></i> --}}
                                <i class="icofont icofont-key"></i>
                                Edit Password
                            </a>
                            <a type="button" href="{{ url('admin/edit/profile', Auth::user()->id) }}" class="btn btn-responsive btn-primary">
                                {{-- <i class="icofont icofont-check-circled"></i> --}}
                                <i class="icofont icofont-edit"></i>
                                Edit Profil
                            </a>
                            @endif
                            <br>&nbsp;
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <label style="font-weight: bold;">Informasi Pribadi</label>
                                    <table class="table table-striped">

                                        <tr>
                                            <td width="20%">Nama Lengkap</td>
                                            <td >{!! Str::title(@$profile->name) !!}</td>
                                        </tr>
                                        <tr>
                                            <td>NIP / NIK</td>
                                            <td >{{ old('no_pegawai', @$profile->pegawai->no_pegawai) }}</td>
                                        </tr>

                                        <tr>
                                            <td>Tempat / Tanggal Lahir</td>
                                            <td >{{ old('tgl_lahir', @$profile->pegawai->tgl_lahir) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Jenis Kelamin</td>
                                            <td>{{ old('jenis_kelamin', @$profile->pegawai->jenis_kelamin) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Agama</td>
                                            <td >
                                                {{ old('agama', @$profile->pegawai->agama) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Telepon</td>
                                            <td >{{ old('phone', @$profile->pegawai->no_tlp) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Telepon Rumah</td>
                                            <td >{{ old('phone', @$profile->pegawai->no_tlp_rumah) }}</td>
                                        </tr>

                                    </table>
                                    <label style="font-weight: bold;">Informasi Akun</label>
                                    <table class="table table-striped">
                                        {{-- <tr>
                                            <td width="20%">Username</td>
                                            <td >{!! Str::title(Auth::user()->name) !!}</td>
                                        </tr> --}}
                                        <tr>
                                            <td width="20%">Email</td>
                                            <td >{{ @$profile->email }}</td>
                                        </tr>
                                        <tr>
                                            <td>Created At</td>
                                            <td>1 month ago</td>
                                        </tr>
                                    </table>
                                    <label style="font-weight: bold;">Alamat Domisili</label>
                                    <table class="table table-striped">
                                        <tr>
                                            <td width="20%">Alamat Lengkap</td>
                                            <td >{!! old('alamat', @$profile->pegawai->alamat) !!}</td>
                                        </tr>
                                        <tr>
                                            <td>Provinsi</td>
                                            <td>{{ old('provinsi', @$profile->provinsi) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Kota / Kabupaten</td>
                                            <td>{{ old('kota', @$profile->kota) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Kode Pos</td>
                                            <td>{{ old('kode_pos', @$profile->pegawai->kode_pos) }}</td>
                                        </tr>
                                    </table>
                                    <label style="font-weight: bold;">Riwayat Pendidikan</label>
                                    <table class="table table-striped">
                                        <tr>
                                            <td width="20%">Jejang</td>
                                            <td >{{ old('jejang', @$profile->pegawai->jejang) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Jurusan</td>
                                            <td>{{ old('jurusan_pendidikan', @$profile->pegawai->jurusan_pendidikan) }}</td>
                                        </tr>
                                    </table>
                                    <label style="font-weight: bold;">Pekerjaan</label>
                                    <table class="table table-striped">
                                        <tr>
                                            <td width="20%">Jabatan</td>
                                            <td> {{ @$profile->internalRole->role }}</td>
                                        </tr>
                                        <tr>
                                            <td width="20%">UPTD</td>
                                            <td>{{ Str::upper(@$profile->internalRole->uptd) }}</td>
                                        </tr>
                                        <tr>
                                            <td>SUP</td>
                                            <td>{{ $profile->sup }}</td>
                                        </tr>
                                        <tr>
                                            <td>Ruas Jalan</td>
                                            <td>
                                                @foreach (@$profile->ruas as $item)

                                                {{ @$item->nama_ruas_jalan }}@if (count($profile->ruas)>1);<br> @endif
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Mulai</td>
                                            <td>{{ old('tgl_mulai_kerja', @$profile->pegawai->tgl_mulai_kerja) }}</td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                            <a href="{{ url()->previous() }}"><button type="button" class="btn btn-danger waves-effect "
                                data-dismiss="modal">Kembali</button></a>
                        </div>
                </div>
            </div>

        </div>
    </div>
    <div class="modal-only">
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form action="{{url('admin/user/account/'.Auth::user()->id)}}" method="post" enctype="multipart/form-data">
                    {{-- <form action="{{url('admin/edit/profile/'.Auth::user()->id)}}" method="POST" enctype="multipart/form-data"> --}}

                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Password</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body p-5">

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">email</label>
                                <div class="col-md-9">
                                    <input type="text" name="email" id="email" value="{{ Auth::user()->email }}" class="form-control"></input>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Password Lama</label>
                                <div class="col-md-9">
                                    <input type="password" name="password_lama" id="password_lama"  placeholder="Masukkan Password Lama" class="form-control"></input>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password" id="password" value="{{ old('password') }}" placeholder="Masukkan Password Baru"
                                            class="form-control @error('password') is-invalid @enderror">
                                        @error('password')
                                        <div class="invalid-feedback" style="display: block; color:red">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ulangi Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="Masukkan Konfirmasi Password Baru"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <i style="color :red; font-size: 10px;">Biarkan jika tidak ada perubahan</i>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary waves-effect " data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-danger waves-effect waves-light ">Simpan</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
<script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net/js/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendor/chosen_v1.8.7/chosen.jquery.js') }}" type="text/javascript"></script>

<script>
    $(document).ready(function() {
        $(".chosen-select").chosen( { width: '100%' } );
        $(".chosen-jenis-instruksi").chosen( { width: '100%' } );

        $('#editModal').on('show.bs.modal', function(event) {
            const link = $(event.relatedTarget);
            const id = link.data('id');

            console.log(id);
            const baseUrl = `{{ url('admin/master-data/user/role-akses/getData') }}/` + id;
            $.get(baseUrl, { id: id },
                function(response){


            });
        });

    });
</script>
@endsection
