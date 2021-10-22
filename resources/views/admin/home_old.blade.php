@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Selamat Datang di Dashboard Teman Jabar</h4>
                {{-- <span>lorem ipsum dolor sit amet, consectetur adipisicing elit</span> --}}
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Home</a> </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('page-body')
@php
  if (hasAccess(Auth::user()->internal_role_id, "User", "View")) {
     $number = 4;
  }else{
    $number = 6;
  }
@endphp
<div class="row">
    <div class="col-xl-{{ $number }} col-md-6">
        <div class="card">
            <a href="{{ url('admin/master-data/ruas-jalan') }}">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="text-c-yellow f-w-600">
                            @if(Auth::user()->internalRole->uptd == null)
                                {{ count($ruas_jalan_lists) }}
                            @else
                                {{ count($ruas_jalan_lists_uptd) }}
                            @endif
                        </h4>
                        {{-- <h6 class="text-muted m-b-0">Critical Contract</h6> --}}
                    </div>
                    <div class="col-4 text-right">
                        <i class="feather icon-map f-28"></i>
                    </div>
                </div>
            </div>
            </a>
            <div class="card-footer bg-c-yellow">
                <div class="row align-items-center">
                    <div class="col-9">
                        <p class="text-white m-b-0">Ruas Jalan</p>
                    </div>
                    <div class="col-3 text-right">
                        <i class="feather icon-trending-up text-white f-16"></i>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-xl-{{ $number }} col-md-6">
        <div class="card">
            <a href="{{ url('admin/master-data/jembatan') }}">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="text-c-green f-w-600">
                            @if(Auth::user()->internalRole->uptd == null)
                            {{ count($jembatan_lists) }}
                            @else
                            {{ count($jembatan_lists_uptd) }}
                            @endif

                        </h4>
                        {{-- <h6 class="text-muted m-b-0">On Progress</h6> --}}
                    </div>
                    <div class="col-4 text-right">
                        <i class="feather icon-file-text f-28"></i>
                    </div>
                </div>
            </div>
            </a>
            <div class="card-footer bg-c-green">
                <div class="row align-items-center">
                    <div class="col-9">
                        <p class="text-white m-b-0">Jembatan</p>
                    </div>
                    <div class="col-3 text-right">
                        <i class="feather icon-trending-up text-white f-16"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-{{ $number }} col-md-12 col-sm-12">
        <div class="card">
            <a href="{{ url('admin/master-data/CCTV') }}">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="text-warning f-w-600">
                            @if(Auth::user()->internalRole->uptd == null)
                                {{ count($cctv_lists) }}
                            @else
                                {{ count($cctv_lists_uptd) }}
                            @endif
                        </h4>
                        {{-- <h6 class="text-muted m-b-0">Finish</h6> --}}
                    </div>
                    <div class="col-4 text-right">
                        <i class="feather icon-video f-28"></i>
                    </div>
                </div>
            </div>
            </a>
            <div class="card-footer bg-warning">
                <div class="row align-items-center">
                    <div class="col-9">
                        <p class="text-white m-b-0">CCTV</p>
                    </div>
                    <div class="col-3 text-right">
                        <i class="feather icon-trending-up text-white f-16"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-md-12">
        <div class="card">
            <a href="{{ url('admin/master-data/rawanbencana') }}">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="text-danger f-w-600">
                            @if(Auth::user()->internalRole->uptd == null)
                                {{ count($rawan_bencana_lists) }}
                            @else
                                {{ count($rawan_bencana_lists_uptd) }}
                            @endif
                        </h4>
                        {{-- <h6 class="text-muted m-b-0">Finish</h6> --}}
                    </div>
                    <div class="col-4 text-right">
                        <i class="feather icon-bar-chart f-28"></i>
                    </div>
                </div>
            </div>
            </a>
            <div class="card-footer bg-danger">
                <div class="row align-items-center">
                    <div class="col-9">
                        <p class="text-white m-b-0">Rawan Bencana</p>
                    </div>
                    <div class="col-3 text-right">
                        <i class="feather icon-trending-up text-white f-16"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (hasAccess(Auth::user()->internal_role_id, "User", "View"))
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <a href="{{ route('getMasterUser') }}">
                <div class="card-block">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h4 class="text-c-blue f-w-600">@if(Auth::user() && Auth::user()->internalRole->uptd != null){{ count($user_lists_uptd) }}@else {{ count($user_lists) }} @endif

                            </h4>
                            {{-- <h6 class="text-muted m-b-0">Finish</h6> --}}
                        </div>
                        <div class="col-4 text-right">
                            <i class="feather icon-users f-28"></i>
                        </div>
                    </div>
                </div>
                </a>
                <div class="card-footer bg-c-blue">
                    <div class="row align-items-center">
                        <div class="col-9">
                            <p class="text-white m-b-0">Registered Users</p>
                        </div>
                        <div class="col-3 text-right">
                            <i class="feather icon-trending-up text-white f-16"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if (hasAccess(Auth::user()->internal_role_id, 'Daftar Laporan', 'View'))
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Pengaduan</h4>
                {{-- <span>lorem ipsum dolor sit amet, consectetur adipisicing elit</span> --}}
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li> --}}
                    </ul>
                </div>
            </div>
            <div class="card-block">

                <div class="card-deck col-md-12">
                    <div class="card w-100">
                        <a href="{{ url('admin/lapor') }}">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="text-primary f-w-600">
                                        @if(Auth::user()->internalRole->uptd == null)
                                            {{ count($submitted) }}
                                        @else
                                            {{ count($submitted_uptd) }}
                                        @endif
                                    </h4>
                                    {{-- <h6 class="text-muted m-b-0">Finish</h6> --}}
                                </div>
                                <div class="col-4 text-right">
                                    <i class="feather icon-arrow-down f-28"></i>
                                </div>
                            </div>
                        </div>
                        </a>
                        <div class="card-footer bg-primary">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <p class="text-white m-b-0">Submitted</p>
                                </div>
                                <div class="col-3 text-right">
                                    <i class="feather icon-trending-up text-white f-16"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card w-100">
                        <a href="{{ url('admin/lapor') }}">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="text-c-blue f-w-600">
                                        @if(Auth::user()->internalRole->uptd == null)
                                            {{ count($approved) }}
                                        @else
                                            {{ count($approved_uptd) }}
                                        @endif
                                    </h4>
                                    {{-- <h6 class="text-muted m-b-0">Finish</h6> --}}
                                </div>
                                <div class="col-4 text-right">
                                    <i class="feather icon-arrow-up f-28"></i>
                                </div>
                            </div>
                        </div>
                        </a>
                        <div class="card-footer bg-c-blue">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <p class="text-white m-b-0">Approved</p>
                                </div>
                                <div class="col-3 text-right">
                                    <i class="feather icon-trending-up text-white f-16"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card w-100">
                        <a href="{{ url('admin/lapor') }}">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="text-c-yellow f-w-600">
                                        @if(Auth::user()->internalRole->uptd == null)
                                            {{ count($progress) }}
                                        @else
                                            {{ count($progress_uptd) }}
                                        @endif
                                    </h4>
                                    {{-- <h6 class="text-muted m-b-0">Finish</h6> --}}
                                </div>
                                <div class="col-4 text-right">
                                    <i class="feather icon-clock f-28"></i>
                                </div>
                            </div>
                        </div>
                        </a>
                        <div class="card-footer bg-c-yellow">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <p class="text-white m-b-0">Progress</p>
                                </div>
                                <div class="col-3 text-right">
                                    <i class="feather icon-trending-up text-white f-16"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card w-100">
                        <a href="{{ url('admin/lapor') }}">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="text-c-green f-w-600">
                                        @if(Auth::user()->internalRole->uptd == null)
                                            {{ count($done) }}
                                        @else
                                            {{ count($done_uptd) }}
                                        @endif
                                    </h4>
                                    {{-- <h6 class="text-muted m-b-0">Finish</h6> --}}
                                </div>
                                <div class="col-4 text-right">
                                    <i class="feather icon-check-circle f-28"></i>
                                </div>
                            </div>
                        </div>
                        </a>
                        <div class="card-footer bg-c-green">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <p class="text-white m-b-0">Done</p>
                                </div>
                                <div class="col-3 text-right">
                                    <i class="feather icon-trending-up text-white f-16"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-deck col-md-12">
                    <div class="card w-100">
                        <a href="{{ url('admin/lapor') }}">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="text-danger f-w-600">
                                        @if(Auth::user()->internalRole->uptd == null)
                                            {{ count($total_aduan) }}
                                        @else
                                            {{ count($total_aduan_uptd) }}
                                        @endif
                                    </h4>
                                    {{-- <h6 class="text-muted m-b-0">Finish</h6> --}}
                                </div>
                                <div class="col-4 text-right">
                                    {{-- <i class="feather-archive"></i> --}}
                                    <i class="feather icon-clipboard f-28"></i>
                                </div>
                            </div>
                        </div>
                        </a>
                        <div class="card-footer bg-danger">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <p class="text-white m-b-0">Total Pengaduan</p>
                                </div>
                                <div class="col-3 text-right">
                                    <i class="feather icon-trending-up text-white f-16"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    @endif

    <div class="col-sm-12">
        <div class="card">

            <div class="card-header">
                <h4>Pengumuman</h4>
                {{-- <span>lorem ipsum dolor sit amet, consectetur adipisicing elit</span> --}}
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li> --}}
                    </ul>
                </div>
            </div>
            <div class="card-block">
                <p>
                    <h6>
                        Panduan Penggunaan Teman Jabar : <a href="{{ url('admin/file') }}" style="color: blue; font-weight: bold" target="_blank">File here</a>
                    </h6>
                </p>
                @foreach ($pengumuman_internal as $item)
                    <div class="card w-100 mb-2">
                        <a href="{{ route('announcement.show', $item->slug) }}" target="_blank">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h6 class="card-title">{{ $item->title }}</h6>
                                    {{-- <h6 class="text-muted m-b-0">Finish</h6> --}}
                                    <span style="color :grey; font-size: 10px;"><i class='icofont icofont-user'></i> {{ $item->nama_user }}|| <i class='icofont icofont-time'></i> {{ Carbon\Carbon::parse($item->created_at)->diffForHumans()}}</span>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="feather icon-arrow-down f-20"></i>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                @endforeach
                {{ $pengumuman_internal->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

