@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection
@section('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
@endsection
@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Jembatan</h4>
                <span>Seluruh Jembatan yang ada di naungan DBMPR Jabar</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('getMasterJembatan') }}">Jembatan</a> </li>
                <li class="breadcrumb-item"><a href="#">Tambah</a> </li>
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
                <h5>Detail Foto Jembatan</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block pl-5 pr-5 pb-5">
                <div class="row">
                @foreach($foto as $data)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{$data->nama}}</h5>
                            <hr>
                        </div>
                        <div class="card-block pl-5 pr-5 pb-5">
                            <center>
                                <a data-fancybox="gallery" href="{{url('storage/'.$data->foto)}}" data-caption="{{$data->nama}}">
                                    <img class="img-fluid" style="width: 100%;height: auto;object-fit: cover;" src="{{url('storage/'.$data->foto)}}" alt="" srcset="">
                                </a>
                            </center>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('assets/vendor/jquery/js/jquery.mask.js') }}"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
@endsection
