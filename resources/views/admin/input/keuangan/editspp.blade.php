@extends('admin.layout.index')

@section('title') Insert No SPP @endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Insert No SPP</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('getKeuangan') }}">List Data</a> </li>
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

                <form action="{{ route('updateKeuangan') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input name="id_pek" type="hidden" class="form-control" value="{{$kemandoran->id_pek}}" required>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">No. Pekerjaan</label>
                        <div class="col-md-10">
                            <input name="" type="text" class="form-control" value="{{$kemandoran->id_pek}}" required disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Tanggal</label>
                        <div class="col-md-10">
                            <input name="tanggal" type="date" class="form-control" value="{{$kemandoran->tanggal}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">No. SPP</label>
                        <div class="col-md-10">
                            <input name="no_spp" type="text" class="form-control" value="{{$kemandoran->no_spp}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Dokumentasi</label>
                        <div class="col-md-6">
                            <input name="file" type="file" class="form-control">
                            <small class="form-text text-muted">Kosongkan jika tidak akan merubah file dokumentasi </small>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-mat btn-success">Simpan Perubahan</button>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection
