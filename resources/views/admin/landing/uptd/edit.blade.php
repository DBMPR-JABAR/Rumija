@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection

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
                <li class="breadcrumb-item"><a href="{{ route('getLandingUPTD') }}">UPTD</a> </li>
                <li class="breadcrumb-item"><a href="#">Edit</a> </li>
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
                <h5>Edit Data UPTD</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">

                <form action="{{ route('updateLandingUPTD') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$uptd->id}}">

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Nama</label>
                        <div class="col-md-10">
                            <input name="nama" type="text" class="form-control" required value="{{$uptd->nama}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Nama Lain</label>
                        <div class="col-md-10">
                            <input name="altnama" type="text" class="form-control" required value="{{$uptd->altnama}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Deskripsi</label>
                        <div class="col-md-10">
                            <textarea name="deskripsi" rows="3" cols="3" class="form-control" placeholder="Masukkan Deskripsi" required>{{$uptd->deskripsi}}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Gambar</label>
                        <div class="col-md-5">
                            <img class="img-thumbnail rounded mx-auto d-block" src="{{url('storage/' . $uptd->gambar)}}"  alt="">
                        </div>
                        <div class="col-md-5">
                            <input name="gambar" type="file" class="form-control">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-mat btn-success">Simpan Perubahan</button>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection
