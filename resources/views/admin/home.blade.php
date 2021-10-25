@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection

@section('head')
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/data.js"></script>
  <script src="https://code.highcharts.com/modules/drilldown.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>
  <style>
    .highcharts-credits {
      display: none !important
    }

    .highcharts-figure,
    .highcharts-data-table table {
      min-width: 310px;
      /* max-width: 800px; */
      margin: 1em auto;
      width: 100%
    }

    #container {
      height: 400px;
      width: 100%
    }

    .highcharts-data-table table {
      font-family: Verdana, sans-serif;
      border-collapse: collapse;
      border: 1px solid #EBEBEB;
      margin: 10px auto;
      text-align: center;
      width: 100%;
      /* max-width: 500px; */
    }

    .highcharts-data-table caption {
      padding: 1em 0;
      font-size: 1.2em;
      color: #555;
    }

    .highcharts-data-table th {
      font-weight: 600;
      padding: 0.5em;
    }

    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
      padding: 0.5em;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
      background: #f8f8f8;
    }

    .highcharts-data-table tr:hover {
      background: #f1f7ff;
    }

  </style>
@endsection

@section('page-header')
  <div class="row align-items-end">
    <div class="col-lg-8">
      <div class="page-header-title">
        <div class="d-inline">
          <h4>Selamat Datang di Dashboard SYSTARUMIJA</h4>
          <span>SYSTEM INVENTARISASI RUANG MILIK JALAN</span>
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
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h5>Pengumuman</h5>
          <div class="card-header-right">
            <ul class="list-unstyled card-option">
              <li><i class="feather icon-minus minimize-card"></i></li>
            </ul>
          </div>
        </div>
        <div class="card-block">
          @foreach ($pengumuman_internal as $item)
            <div class="card w-100 mb-2">
              <a href="{{ route('announcement.show', $item->slug) }}" target="_blank">
                <div class="card-block">
                  <div class="row align-items-center">
                    <div class="col-8">
                      <h6 class="card-title">{{ $item->title }}</h6>
                      <span style="color :grey; font-size: 10px;"><i class='icofont icofont-user'></i>
                        {{ $item->nama_user }}|| <i class='icofont icofont-time'></i>
                        {{ Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</span>
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

@section('script')
@endsection
