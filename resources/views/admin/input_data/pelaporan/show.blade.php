@extends('admin.layout.index')

@section('title') Edit pelaporan @endsection
@section('head')
<script src="http://maps.googleapis.com/maps/api/js"></script>
<script>
function initialize() {
    var lapor = {!! json_encode($pelaporan->toArray()) !!};
    console.log(lapor);
  var propertiPeta = {
    center:new google.maps.LatLng(lapor.lat,lapor.long),
    zoom:9,
    mapTypeId:google.maps.MapTypeId.ROADMAP
  };
  
  var peta = new google.maps.Map(document.getElementById("googleMap"), propertiPeta);
  
  // membuat Marker
  var marker=new google.maps.Marker({
      position: new google.maps.LatLng(lapor.lat,lapor.long),
      map: peta,
      animation: google.maps.Animation.BOUNCE
  });

}

// event jendela di-load  
google.maps.event.addDomListener(window, 'load', initialize);
</script>
@endsection
@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Laporan Rumija UPTD {{ Str::upper($pelaporan->uptd_id) }}</h4>
                {{-- <span>Data Seluruh Rumija</span> --}}
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class="breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}">
                        <i class="feather icon-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="/admin">Rumija</a></li>
                <li class="breadcrumb-item"><a href="#!">Pelaporan Rumija</a></li>

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
                <h5>{{@$pelaporan->data_sup->name}}</h5>
                <br>
                <h5>{{@$pelaporan->ruas->nama_ruas_jalan}}</h5>
                <div class="card-header-right">
                    <i class="feather icon-maximize full-card"></i>
                    <i class="feather icon-minus minimize-card"></i>
                </div>
            </div>
            <div class="card-block">

                <div class="row">
                    <div class="col-md-12">
                        <label class="col-md-12 col-form-label"><b>Keterangan</b></label>
                        <hr>
                        <label class="col-md-12 col-form-label">{{$pelaporan->keterangan}}</label>
                    </div>
                </div>
                 
                <div class="form-group row">
                    <label class="col-md-12 col-form-label text-center">Dokumentasi</label>
                    <img style="max-height: 400px;" class="img-thumbnail rounded mx-auto d-block" src="{{ Storage::url('public/laporan_rumija/'.$pelaporan->image) }}" alt="">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="col-md-6 col-form-label"><b>Koordinat X</b></label>
                        <hr>
                        <label class="col-md-12 col-form-label">{{$pelaporan->lat}}</label>
                    </div>
                    <div class="col-md-6">
                        <label class="col-md-6 col-form-label"><b>Koordinat Y</b></label>
                        <hr>
                        <label class="col-md-12 col-form-label">{{$pelaporan->long}}</label>
                    </div>
                </div>
                <div id="googleMap" style="width:100%;height:380px;"></div>
            </div>
        </div>
     
        <a href="{{ url()->previous() }}"><button type="button" class="btn btn-danger waves-effect " data-dismiss="modal">Kembali</button></a>

    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net/js/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery/js/jquery.mask.js') }}"></script>
<script>
    $(document).ready(function() {
        // Format mata uang.
        $('.formatRibuan').mask('000.000.000.000.000', {
            reverse: true
        });

        // Format untuk lat long.
        $('.formatLatLong').keypress(function(evt) {
            return (/^\-?[0-9]*\.?[0-9]*$/).test($(this).val() + evt.key);
        });
    });

    function ubahOption() {

        //untuk select Ruas
        id = document.getElementById("uptd").value
        url = "{{ url('admin/input-data/kondisi-jalan/getRuasJalan') }}"
        id_select = '#ruas_jalan'
        text = 'Pilih Ruas Jalan'
        option = 'nama_ruas_jalan'

        setDataSelect(id, url, id_select, text, option, option)

        //untuk select SUP
        url = "{{ url('admin/master-data/ruas-jalan/getSUP') }}"
        id_select = '#sup'
        text = 'Pilih SUP'
        option = 'name'

        setDataSelect(id, url, id_select, text, option, option)
    }
   

</script>
@endsection
