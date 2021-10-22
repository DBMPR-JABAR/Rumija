@extends('admin.layout.index')

@section('title') CCTV Control Room @endsection

@section('head')
  <link href="https://vjs.zencdn.net/7.2.3/video-js.css" rel="stylesheet">
  <link href="https://unpkg.com/@videojs/themes@1/dist/forest/index.css" rel="stylesheet">

@endsection
@section('page-header')
  <div class="row align-items-end">
    <div class="col-lg-8">
      <div class="page-header-title">
        <div class="d-inline">
          <h4>CCTV Control Room</h4>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="page-header-breadcrumb">
        <ul class=" breadcrumb breadcrumb-title">
          <li class="breadcrumb-item">
            <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
          </li>
          <li class="breadcrumb-item"><a href="#!">CCTV Control Room</a> </li>
        </ul>
      </div>
    </div>
  </div>
@endsection

@section('page-body')
  <div class="row d-flex justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-block accordion-block">
          <div id="accordion" role="tablist" aria-multiselectable="true">
            <div class="accordion-panel">
              <div class="accordion-heading" role="tab" id="headingOne">
                <h3 class="card-title accordion-title">
                  <a class="accordion-msg" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                    aria-expanded="true" aria-controls="collapseOne">
                    Filter
                  </a>
                </h3>
              </div>
              <div id="collapseOne" class="panel-collapse collapse in show" role="tabpanel" aria-labelledby="headingOne">
                <div class="accordion-content accordion-desc">
                  <div class="card-block">
                    <div class="row">
                      <div class="col-sm-12 col-xl-3 m-b-30">
                        <h4 class="sub-title">UPTD</h4>
                        <select id="filterUPTD" name="select" class="form-control form-control-primary"
                          onchange="changeUPTD(this,true)">
                          @if (Auth::user()->internalRole->uptd)
                            <option id="initUptd"
                              value="{{ str_replace('uptd', '', Auth::user()->internalRole->uptd) }}" selected>
                              UPTD
                              {{ str_replace('uptd', '', Auth::user()->internalRole->uptd) }}
                            </option>
                          @else
                            <option id="initUptd" value="semua" selected>Semua</option>
                            @foreach ($userUptdList as $uptd)
                              <option value="{{ $uptd->id }}">{{ $uptd->nama }}</option>
                            @endforeach
                          @endif
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="card p-3">
        <div id="containerVideo" class="row g-1">
        </div>
      </div>
    </div>
  </div>
  <style>
    .videoContainer {
      width: 400px;
      height: 300px;
    }

    .videoIdentity {
      width: 100%;
      height: 100%;
    }

  </style>
@endsection

@section('script')
  <!-- <script src="https://vjs.zencdn.net/ie8/ie8-version/videojs-ie8.min.js"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.14.1/videojs-contrib-hls.js"></script>
  <script src="https://vjs.zencdn.net/7.2.3/video.js"></script>

  <script>
    const cctvs = @json($cctv)

    $(document).ready(() => {
      const init = document.getElementById("initUptd");
      changeUPTD(init, false)
    })
    const uptdList = {!! json_encode($uptd_lists->toArray()) !!};
    //console.log(uptdList)

    const templateCCTV = (data) => {
      let html = "";
      data.forEach((item) => {
        if (item.url) {
          html += `<div class="col-xl-3 col-lg-4 col-md-6 d-flex justify-content-center cctvItem">
                            <div class="card videoContainer">
                                <video id='CCTV-${item.id}' class="video-js vjs-theme-forest videoIdentity"
                                    controls autoplay>
                                    <source type="application/x-mpegURL" src="${item.url}">
                                </video>
                                <div class="card-footer bg-c-blue">
                                    <div class="row align-items-center">
                                        <div class="col-9">
                                            <p class="text-white m-b-0">${item.lokasi}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>`
        }
      })

      if (data.length == 0) {
        html =
          `<div class="col-12 d-flex justify-content-center mx-auto cctvItem mt-3"><p style="text-align : center;">Tidak ada data</p></div>`
      }
      return html;
    }

    function changeUPTD(filter, isUpdate) {
      let cctvList;
      let html;

      if (filter.value == "semua") {
        cctvList = cctvs
      } else {
        cctvList = cctvs.filter((item) => {
          return item.uptd_id == Number(filter.value)
        })
      }

      if (isUpdate) {
        cctvList.forEach((item) => {
          if (item.url) {
            const player = videojs(`CCTV-${item.id}`)
            player.dispose()
            player.on('error', function(e) {
              $(e.target).html(
                `<div class="text-center" style="position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);"><p>TERJADI KESALAHAN ATAU CCTV NONAKTIF</p>
                    <a class="bg-white px-1" href="http://124.81.122.131/cctv-http?lokasi=${item.lokasi}&url=${item.url}" target="_blank">COBA AKSES DISINI..</a></div>`
              )
            });
          }
        })
      }

      html = templateCCTV(cctvList)
      document.getElementById("containerVideo").innerHTML = html

      cctvList.forEach((item) => {
        if (item.url) {
          const player = videojs(`CCTV-${item.id}`)
          player.autoplay(false)
          player.on('error', function(e) {
            $(e.target).html(
              `<div class="text-center" style="position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);"><p>TERJADI KESALAHAN ATAU CCTV NONAKTIF</p>
                    <a class="bg-white px-1" href="http://124.81.122.131/cctv-http?lokasi=${item.lokasi}&url=${item.url}" target="_blank">COBA AKSES DISINI..</a></div>`
            )
          });
        }
      })
    }
  </script>
@endsection
