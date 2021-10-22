@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Profil DBMPR</h4>
                <span>Mengubah profil DBMPR untuk keperluan Landing Page</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Profil</a> </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('page-body')
<form action="{{ route('updateLandingProfil') }}" method="post" enctype="multipart/form-data">
@csrf
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Profil Singkat</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Nama Instansi</label>
                    <div class="col-md-6">
                        <input name="nama" type="text" class="form-control" value="{{$profil->nama}}" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Deskripsi Singkat</label>
                    <div class="col-md-10">
                        <textarea name="deskripsi" rows="5" cols="5" class="form-control" placeholder="Masukkan Deskripsi" required>{{$profil->deskripsi}}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Foto</label>
                    <div class="col-md-5">
                        <input name="gambar" type="file" class="form-control">
                    </div>
                    <div class="col-md-5">
                        <img class="img-thumbnail rounded mx-auto d-block" src="{!! url('storage/'.$profil->gambar) !!}" alt="" srcset="">
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Pencapaian</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Jumlah Infrastruktur Selesai</label>
                    <div class="col-md-3">
                        <input name="pencapaian_selesai" type="number" class="form-control" value="{{$profil->pencapaian_selesai}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Jumlah Target Infrastruktur</label>
                    <div class="col-md-3">
                        <input name="pencapaian_target" type="number" class="form-control" value="{{$profil->pencapaian_target}}" required>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Informasi Detail</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Alamat</label>
                    <div class="col-md-10">
                        <input name="alamat" type="text" class="form-control" value="{{$profil->alamat}}" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Email</label>
                    <div class="col-md-4">
                        <input name="email" type="text" class="form-control" value="{{$profil->email}}" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Kontak</label>
                    <div class="col-md-4">
                        <input name="kontak" type="text" class="form-control" value="{{$profil->kontak}}" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Jam Layanan</label>
                    <div class="col-md-4">
                        <input name="jam_layanan" type="text" class="form-control" value="{{ ($profil->jam_layanan) ?? '' }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Link Website</label>
                    <div class="col-md-4">
                        <input name="link_website" type="text" class="form-control" value="{{ ($profil->link_website) ?? '' }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Link Instagram</label>
                    <div class="col-md-4">
                        <input name="link_instagram" type="text" class="form-control" value="{{ ($profil->link_instagram) ?? '' }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Link Facebook</label>
                    <div class="col-md-4">
                        <input name="link_facebook" type="text" class="form-control" value="{{ ($profil->link_facebook) ?? '' }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Link Twitter</label>
                    <div class="col-md-4">
                        <input name="link_twitter" type="text" class="form-control" value="{{ ($profil->link_twitter) ?? '' }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Link Youtube</label>
                    <div class="col-md-4">
                        <input name="link_youtube" type="text" class="form-control" value="{{ ($profil->link_youtube) ?? '' }}">
                    </div>

                </div><div class="form-group row">
                    <label class="col-md-2 col-form-label">Link Playstore</label>
                    <div class="col-md-4">
                        <input name="link_playstore" type="text" class="form-control" value="{{ ($profil->link_playstore) ?? '' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-block">

                <button type="submit" class="btn btn-mat btn-success">Simpan Perubahan</button>

            </div>
        </div>
    </div>
</div>

</form>
@endsection
